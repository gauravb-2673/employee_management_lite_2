<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClaimRequest;
use Illuminate\Http\Request;


use App\Mail\WelcomeEmployeeMail;
use App\Models\Department;
use App\Models\Employee;
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

        $claims = ExpenseClaims::with('employee')->get();
        $statussearch = $request->input('statussearch');
        if ($request->filled('statussearch')) {
            $claims->where('name', 'like', $statussearch . '%');
        }

        //$claims = $query->paginate(10)->withQueryString();
        // dd($query);
        return view('claims.index', ['claims' => $claims,]);
    }

    public function store(StoreClaimRequest $request)
    {
        $data = $request->validated();


        $file = $request->file('document');

        $claims = ExpenseClaims::create($data);

        return view('claims.index', ['claims' => $claims,]);
    }

    public function approve(Employee $employee, StoreClaimRequest $request)
    {
        try {

            $data = $request->validated();


            $file = $request->file('document');


            DB::beginTransaction();
            $claims = ExpenseClaims::update($data);

            DB::commit();



            return view('claims.index', ['claims' => $claims,]);
        } catch (Throwable) {
            DB::commit();
        }
    }

    public function reject(Employee $employee, StoreClaimRequest $request)
    {
        try {

            $data = $request->validated();


            $file = $request->file('document');


            DB::beginTransaction();
            $claims = ExpenseClaims::update($data);

            DB::commit();



            return view('claims.index', ['claims' => $claims,]);
        } catch (Throwable) {
            DB::commit();
        }
    }
}
