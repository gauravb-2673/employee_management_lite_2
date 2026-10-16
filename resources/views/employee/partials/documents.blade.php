{{-- EMPLOYEE DOCUMENTS --}}
<div class="mt-8 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    <div class="border-b border-slate-200 bg-slate-50 px-5 py-5 sm:px-6">
        <h2 class="text-lg font-semibold text-slate-900">
            Employee Documents
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Upload, download, or delete employee documents.
        </p>
    </div>

    <div class="p-5 sm:p-6">

        {{-- Success message --}}
        @if (session('success'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-3 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        {{-- Error message --}}
        @if (session('error'))
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-800">
                {{ session('error') }}
            </div>
        @endif

        {{-- Upload form --}}
        @can('update', $employee)
            <form action="{{ route('employees.documents.store', $employee) }}" method="POST" enctype="multipart/form-data"
                class="mb-6">
                @csrf

                <label for="document" class="mb-2 block text-sm font-medium text-slate-700">
                    Select document
                </label>

                <input type="file" name="document" id="document" accept=".pdf,.doc,.docx" required
                    class="block w-full rounded-lg border border-slate-300 bg-white p-2.5 text-sm">

                @error('document')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <p class="mt-1 text-xs text-slate-500">
                    Accepted formats: PDF, DOC, DOCX. Maximum size: 10 MB.
                </p>

                <button type="submit"
                    class="mt-3 inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700">
                    Upload Document
                </button>
            </form>
        @endcan

        {{-- List documents --}}
        <div class="space-y-3">

            @forelse ($employee->documents as $document)
                <div
                    class="flex flex-col gap-3 rounded-lg border border-slate-200 p-4 sm:flex-row sm:items-center sm:justify-between">

                    <div class="min-w-0">
                        <p class="break-words text-sm font-medium text-slate-900">
                            {{ $document->original_name }}
                        </p>

                        <p class="mt-1 text-xs text-slate-500">
                            {{ number_format($document->size / 1024, 2) }} KB
                        </p>

                        <p class="text-xs text-slate-500">
                            Uploaded:
                            {{ $document->created_at->format('d M Y, h:i A') }}
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">

                        {{-- Download --}}
                        <a href="{{ route('employee-documents.download', $document) }}"
                            class="inline-flex items-center rounded-lg border border-slate-300 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                            Download
                        </a>

                        {{-- Delete --}}
                        @can('update', $employee)
                            <form action="{{ route('employee-documents.destroy', $document) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this document?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="inline-flex items-center rounded-lg bg-red-600 px-3 py-2 text-sm font-medium text-white hover:bg-red-700">
                                    Delete
                                </button>
                            </form>
                        @endcan

                    </div>
                </div>

            @empty

                <div class="rounded-lg border border-dashed border-slate-300 p-6 text-center">
                    <p class="text-sm text-slate-500">
                        No documents have been uploaded for this employee.
                    </p>
                </div>
            @endforelse

        </div>

    </div>

</div>
