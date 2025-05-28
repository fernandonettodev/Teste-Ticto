<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\EmployeesManagementController;
use App\Http\Controllers\Admin\Reports\TimeRecordReportController;

use App\Http\Controllers\Employee\Auth\LoginController as EmployeeLoginController;
use App\Http\Controllers\Employee\DashboardController;
use App\Http\Controllers\Employee\TimeClockController;
use App\Http\Controllers\Employee\PasswordController;


Route::redirect('/', '/employee/login');


// Rotas de login para ADMIN
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminLoginController::class, 'login']);
    });

    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
        Route::resource('employees', EmployeesManagementController::class)->except(['show']);
        Route::post('/employees', [EmployeesManagementController::class, 'store'])->name('employees.store');
        Route::get('employees/fetch-cep', [EmployeesManagementController::class, 'fetchCep'])->name('employees.fetch-cep');
        Route::get('reports/time-records', [TimeRecordReportController::class, 'index'])->name('reports.time-records');
        Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');
    });
});

// Rotas de login para EMPLOYEE
Route::prefix('employee')->name('employee.')->group(function () {
    Route::middleware('guest:employee')->group(function () {
        Route::get('/login', [EmployeeLoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [EmployeeLoginController::class, 'login']);
    });

    Route::middleware('auth:employee')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/clock/{action}', [TimeClockController::class, 'clock'])->name('clock');
        Route::get('/senha', [PasswordController::class, 'edit'])->name('password.change');
        Route::post('/senha', [PasswordController::class, 'update'])->name('password.update');
        Route::post('/logout', [EmployeeLoginController::class, 'logout'])->name('logout');
    });
});




