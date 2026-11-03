<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClaimRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Mail\WelcomeEmployeeMail;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use App\Models\Project;
use App\Models\RoleMaster;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Throwable;
use App\Models\EmployeeSalaryHistories;
use App\Models\ExpenseClaims;


use function Pest\Laravel\json;

class ExpenseClaimController extends Controller
{

    public function index(Request $request)
    {
        //Gate::authorize('view', $employee);

        $query = ExpenseClaims::with('employee', 'user');
        $statussearch = $request->input('statussearch');
        if ($request->filled('statussearch')) {
            $query->where('status', 'like', $statussearch . '%');
        }

        $claims = $query->paginate(10)->withQueryString();
        // dd($query);
        return view('claims.index', ['claims' => $claims,]);
    }

    public function create()
    {
        $employees = Employee::all();
        $users = User::all();

        return view('claims.create', ['employees' => $employees, 'users' => $users]);
    }

    public function store(StoreClaimRequest $request)
    {
        // Get validated form data
        $data = $request->validated();

        // Check if a document was uploaded
        if ($request->hasFile('document')) {

            // Store the uploaded file in storage/app/public/expense-claims
            $receiptPath = $request->file('document')
                ->store('expense-claims', 'public');

            // Save the generated file path in receipt_path column
            $data['receipt_path'] = $receiptPath;

            // Remove document because it is not a database column
            unset($data['document']);
        }

        // Set default status
        $data['status'] = 'pending';

        // Create database record
        ExpenseClaims::create($data);

        return redirect()
            ->route('claims.index')
            ->with('success', 'Expense claim submitted successfully.');
    }
    public function review(ExpenseClaims $claim)
    {
        $claim->load('employee', 'user');
        //dd($claim);
        return view('claims.review', ['claim' => $claim]);
    }

    public function approve(ExpenseClaims $claim)
    {
        if ($claim->status !== 'pending') {
            return redirect()->route('claims.index')
                ->with('error', 'This claim has already been reviewed.');
        }

        $claim->update([
            'status' => 'approved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'rejection_reason' => null,
        ]);

        return redirect()->route('claims.index')
            ->with('success', 'Claim approved successfully.');
    }
    public function reject(Request $request, ExpenseClaims $claim)
    {
        if ($claim->status !== 'pending') {
            return redirect()->route('claims.index')
                ->with('error', 'This claim has already been reviewed.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $claim->update([
            'status' => 'rejected',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return redirect()->route('claims.index')
            ->with('success', 'Claim rejected successfully.');
    }
}
