<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\TimeRecord;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEmployees = Employee::where('active', true)->count();
        $todayRecords = TimeRecord::where('date', today())->count();
        $incompleteRecords = TimeRecord::where('date', today())
            ->where('status', 'incomplete')->count();
        
        $recentRecords = TimeRecord::with('employee')
            ->where('date', today())
            ->latest('updated_at')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalEmployees',
            'todayRecords',
            'incompleteRecords',
            'recentRecords'
        ));
    }
}
