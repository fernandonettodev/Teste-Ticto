<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Employee\Login as EmployeeLogin;

Route::middleware('guest:employee')->group(function () {
    Route::get('/employee/login', EmployeeLogin::class)->name('employee.login');
});

Route::middleware('auth:employee')->prefix('employee')->name('employee.')->group(function () {
    Route::get('/dashboard', App\Livewire\Employee\Dashboard::class)->name('dashboard');
    Route::get('/time-clock', App\Livewire\Employee\TimeClock::class)->name('time-clock');
    Route::get('/change-password', App\Livewire\Employee\ChangePassword::class)->name('change-password');
});
