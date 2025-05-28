<?php

namespace App\Http\Controllers\Employee;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class PasswordController extends Controller
{
    public function edit()
    {
        return view('employee.password.edit');
    }

    public function update(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $employee = Auth::guard('employee')->user();

        if (!Hash::check($request->current_password, $employee->password)) {
            return back()->withErrors(['current_password' => 'A senha atual está incorreta.']);
        }

        $employee->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('status', 'Senha alterada com sucesso!');
    }
}

