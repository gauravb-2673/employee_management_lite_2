<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadEmployeeDocumentRequest;
use App\Models\Employee;
use App\Models\EmployeeDocument;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Throwable;

class EmployeeDocumentController extends Controller
{
    /**
     * Upload a document for an employee.
     */
    public function store(
        UploadEmployeeDocumentRequest $request,
        Employee $employee
    ) {
        // Only users allowed to update this employee can upload documents.
        Gate::authorize('update', $employee);

        // Retrieve the validated file.
        $file = $request->file('document');

        // Store it using a generated filename on the private local disk.
        // Get the original filename without its extension.
        $originalName = pathinfo(
            $file->getClientOriginalName(),
            PATHINFO_FILENAME
        );

        // Replace spaces and special characters with underscores.
        $originalName = preg_replace('/[^A-Za-z0-9_-]/', '_', $originalName);

        // Get the uploaded file's extension.
        $extension = $file->getClientOriginalExtension();

        // Generate the current date and time.
        $timestamp = now()->format('Y-m-d_H-i-s-u');

        // Build the new filename.
        $fileName = $originalName
            . '_employee_' . $employee->id
            . '_' . $timestamp
            . '.' . $extension;

        // Store the file with the custom filename.
        $path = $file->storeAs(
            'employee-documents',
            $fileName,
            'local'
        );

        try {
            // Save the document's metadata in the database.
            $employee->documents()->create([
                'original_name' => $file->getClientOriginalName(),
                'path' => $path,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'uploaded_by' => $request->user()->id,
            ]);
        } catch (Throwable $exception) {
            // If the database operation fails, remove the newly stored file.
            Storage::disk('local')->delete($path);

            // Preserve normal Laravel error reporting and handling.
            throw $exception;
        }

        return redirect()
            ->route('employees.show', $employee)
            ->with('success', 'Document uploaded successfully.');
    }

    /**
     * Download a private employee document.
     */
    public function download(EmployeeDocument $document)
    {
        // Authorize access to the employee's document.
        Gate::authorize('view', $document->employee);

        // Build the absolute path to the private file.
        $filePath = storage_path('app/private/' . $document->path);

        // Return 404 if the file does not exist.
        abort_unless(is_file($filePath), 404, 'Document not found.');

        // Download the file using its original filename.
        return response()->download(
            $filePath,
            $document->original_name
        );
    }

    /**
     * Delete a document and its stored file.
     */
    public function destroy(EmployeeDocument $document)
    {
        // Only users allowed to update this employee can delete documents.
        Gate::authorize('update', $document->employee);

        $path = $document->path;
        $employee = $document->employee;

        // Delete the database record first.
        $document->delete();

        // Then remove the physical file.
        Storage::disk('local')->delete($path);

        return redirect()
            ->route('employees.show', $employee)
            ->with('success', 'Document deleted successfully.');
    }

    public function view(EmployeeDocument $document)
    {
        // Authorize access to the employee associated with this document.
        Gate::authorize('view', $document->employee);

        // Only allow supported image types.
        $allowedMimeTypes = [
            'image/jpeg',
            'image/png',
        ];

        abort_unless(
            in_array($document->mime_type, $allowedMimeTypes, true),
            404
        );

        // Check whether the physical file exists.
        abort_unless(
            Storage::disk('local')->exists($document->path),
            404
        );

        // Get the absolute path to the private file.
        $absolutePath = Storage::disk('local')->path($document->path);

        // Display the image in the browser instead of downloading it.
        return response()->file($absolutePath, [
            'Content-Type' => $document->mime_type,
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
