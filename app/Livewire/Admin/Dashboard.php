<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Employee;
use App\Models\TimeRecord;

class Dashboard extends Component
{

    public function render()
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

        return view('livewire.admin.dashboard', compact(
            'totalEmployees',
            'todayRecords', 
            'incompleteRecords',
            'recentRecords'
        ))->layout('layouts.admin');
    }
}
