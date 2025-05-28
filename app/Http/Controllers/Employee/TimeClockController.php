<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\TimeRecord;
use Illuminate\Http\Request;

class TimeClockController extends Controller
{
    public function clock($action)
    {
        $employee = Auth::guard('employee')->user();
        $record = TimeRecord::firstOrCreate([
            'employee_id' => $employee->id,
            'date' => today(),
        ]);

        $currentTime = now()->format('H:i:s');

        $validationResult = $this->validateAction($action, $record);
        if ($validationResult !== true) {
            return redirect()->back()->with('error', $validationResult);
        }

        $record->update([$action => $currentTime]);
        $record->updateStatus();

        $messages = [
            'clock_in' => 'Entrada registrada com sucesso!',
            'lunch_out' => 'Saída para almoço registrada!',
            'lunch_in' => 'Volta do almoço registrada!',
            'clock_out' => 'Saída registrada com sucesso!',
        ];

        return redirect()->back()->with('success', $messages[$action]);
    }

    private function validateAction($action, $record)
    {
        return match ($action) {
            'clock_in'  => $record->clock_in ? 'Entrada já registrada hoje.' : true,
            'lunch_out' => !$record->clock_in ? 'Registre a entrada primeiro.' :
                           ($record->lunch_out ? 'Saída para almoço já registrada.' : true),
            'lunch_in'  => !$record->lunch_out ? 'Registre a saída para almoço primeiro.' :
                           ($record->lunch_in ? 'Volta do almoço já registrada.' : true),
            'clock_out' => !$record->clock_in ? 'Registre a entrada primeiro.' :
                           ($record->clock_out ? 'Saída já registrada hoje.' : true),
            default => 'Ação inválida.',
        };
    }
}
