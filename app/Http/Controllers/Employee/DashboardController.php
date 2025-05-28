<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $employee    = Auth::guard('employee')->user();
        $todayRecord = $employee->todayRecord(); 

        return view('employee.dashboard', compact('employee', 'todayRecord'));
    }
}
