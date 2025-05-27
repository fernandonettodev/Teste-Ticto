<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Auth;

Route::middleware('guest:web')->group(function () {
    Route::get('/admin/login', fn () => view('livewire.admin.login'))->name('admin.login');

    Route::post('/admin/login', function (LoginForm $form) {
        $form->authenticate();
        return redirect()->intended(route('admin.dashboard'));
    });
});

Route::middleware('auth:web')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', App\Livewire\Admin\Dashboard::class)->name('dashboard');
    Route::get('/employees', App\Livewire\Admin\EmployeesManagement::class)->name('employees');
});
