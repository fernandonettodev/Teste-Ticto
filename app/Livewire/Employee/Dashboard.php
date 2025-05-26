<?php

namespace App\Livewire\Employee;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public $employee;
    public $todayRecord;

    public function mount()
    {
        $this->employee = Auth::guard('employee')->user();
        $this->loadTodayRecord();
    }

    public function loadTodayRecord()
    {
        $this->todayRecord = $this->employee->todayRecord();
    }

    public function render()
    {
        return view('livewire.employee.dashboard')->layout('layouts.employee');
    }
}
