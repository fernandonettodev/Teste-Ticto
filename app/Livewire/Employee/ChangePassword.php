<?php

namespace App\Livewire\Employee;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePassword extends Component
{
    public $current_password = '';
    public $new_password = '';
    public $new_password_confirmation = '';

    protected $rules = [
        'current_password' => 'required',
        'new_password' => 'required|min:6|confirmed',
    ];

    public function changePassword()
    {
        $this->validate();

        $employee = Auth::guard('employee')->user();

        if (!Hash::check($this->current_password, $employee->password)) {
            $this->addError('current_password', 'Senha atual incorreta.');
            return;
        }

        $employee->update([
            'password' => Hash::make($this->new_password)
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        session()->flash('success', 'Senha alterada com sucesso!');
    }

    public function render()
    {
        return view('livewire.employee.change-password');
    }
}
