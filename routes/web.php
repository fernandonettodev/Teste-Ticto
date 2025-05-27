<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::guard('web')->check()) {
        return redirect()->route('admin.dashboard');
    }

    if (Auth::guard('employee')->check()) {
        return redirect()->route('employee.dashboard');
    }

    return redirect()->route('login');
});

Route::post('/logout', function (Logout $logout) {
    $logout();
    return redirect('/');
})->name('logout');

require __DIR__.'/admin.php';
require __DIR__.'/employee.php';
require __DIR__.'/auth.php';
