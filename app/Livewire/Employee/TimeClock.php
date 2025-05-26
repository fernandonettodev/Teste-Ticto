<?php

namespace App\Livewire\Employee;

use Livewire\Component;
use App\Models\TimeRecord;
use Illuminate\Support\Facades\Auth;

class TimeClock extends Component
{
    public $employee;
    public $todayRecord;
    public $nextActions = [];

    public function mount()
    {
        $this->employee = Auth::guard('employee')->user();
        $this->loadTodayRecord();
    }

    public function loadTodayRecord()
    {
        $this->todayRecord = $this->employee->todayRecord();
        $this->nextActions = $this->getNextActions();
    }

    public function clockAction($action)
    {
        $record = TimeRecord::firstOrCreate([
            'employee_id' => $this->employee->id,
            'date' => today(),
        ]);

        $currentTime = now()->format('H:i:s');

        $validationResult = $this->validateAction($action, $record);
        if ($validationResult !== true) {
            session()->flash('error', $validationResult);
            return;
        }

        $record->update([$action => $currentTime]);
        $record->updateStatus();

        $messages = [
            'clock_in' => 'Entrada registrada com sucesso!',
            'lunch_out' => 'Saída para almoço registrada!',
            'lunch_in' => 'Volta do almoço registrada!',
            'clock_out' => 'Saída registrada com sucesso!',
        ];

        session()->flash('success', $messages[$action]);
        $this->loadTodayRecord();
    }

    private function validateAction($action, $record)
    {
        switch ($action) {
            case 'clock_in':
                if ($record->clock_in) return 'Entrada já registrada hoje.';
                break;
            case 'lunch_out':
                if (!$record->clock_in) return 'Registre a entrada primeiro.';
                if ($record->lunch_out) return 'Saída para almoço já registrada.';
                break;
            case 'lunch_in':
                if (!$record->lunch_out) return 'Registre a saída para almoço primeiro.';
                if ($record->lunch_in) return 'Volta do almoço já registrada.';
                break;
            case 'clock_out':
                if (!$record->clock_in) return 'Registre a entrada primeiro.';
                if ($record->clock_out) return 'Saída já registrada hoje.';
                break;
        }
        return true;
    }

    private function getNextActions()
    {
        if (!$this->todayRecord) {
            return ['clock_in' => 'Registrar Entrada'];
        }

        $actions = [];
        
        if (!$this->todayRecord->clock_in) {
            $actions['clock_in'] = 'Registrar Entrada';
        } elseif (!$this->todayRecord->lunch_out) {
            $actions['lunch_out'] = 'Saída para Almoço';
        } elseif (!$this->todayRecord->lunch_in) {
            $actions['lunch_in'] = 'Volta do Almoço';
        } elseif (!$this->todayRecord->clock_out) {
            $actions['clock_out'] = 'Registrar Saída';
        }

        return $actions;
    }

    public function render()
    {
        return view('livewire.employee.time-clock');
    }
}
