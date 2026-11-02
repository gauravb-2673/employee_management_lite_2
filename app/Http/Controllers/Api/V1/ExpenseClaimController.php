<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ExpenseClaimResource;
use App\Mail\WelcomeEmployeeMail;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Project;
use App\Models\RoleMaster;
use Illuminate\Http\Request;
use Throwable;
use App\Models\ExpenseClaims;


class ExpenseClaimController extends Controller
{
    public function index()
    {
        $query = ExpenseClaims::with('employee')->get();

        $claims = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();
        // dd($claims);
        return ExpenseClaimResource::collection($claims);
    }
}
