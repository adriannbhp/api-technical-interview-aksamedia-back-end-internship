<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DivisionController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\NilaiController;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Public Routes for Nilai
Route::get('/nilaiRT', [NilaiController::class, 'nilaiRT'])->name('nilaiRT');
Route::get('/nilaiST', [NilaiController::class, 'nilaiST'])->name('nilaiST');

Route::middleware([JwtMiddleware::class])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/divisions', [DivisionController::class, 'index'])->name('divisions.index');
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::get('/employees/{employee}', [EmployeeController::class, 'show'])->name('employees.show');
    Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
});




