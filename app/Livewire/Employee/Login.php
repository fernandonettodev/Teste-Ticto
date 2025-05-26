<?php

namespace App\Livewire\Employee;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public $registration_number = '';
    public $password = '';
    public $remember = false;

    protected $rules = [
        'registration_number' => 'required',
        'password' => 'required',
    ];

    public function login()
    {
        $this->validate();

        if (Auth::guard('employee')->attempt([
            'registration_number' => $this->registration_number,
            'password' => $this->password,
            'active' => true
        ], $this->remember)) {
            session()->regenerate();
            return redirect()->intended(route('employee.dashboard'));
        }

        $this->addError('registration_number', 'Credenciais inválidas.');
    }

    public function render()
    {
        return view('livewire.employee.login')->layout('layouts.guest');
    }
}
