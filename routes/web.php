<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashBoardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'homePage']);
Route::get('/dashboard', [DashBoardController::class, 'dashboardPage'])->name('dashboard');
Route::get('/categoryPage', [CategoryController::class, 'categoryPage'])->name('category');
Route::get('userProfile', [UserController::class, 'profilePage'])->name('profile');
Route::get('/userLogin', [UserController::class, 'userLoginPage'])->name('login');
Route::get('/userRegistration', [UserController::class, 'userRegistrationPage'])->name('registration');
Route::get('/sendOtp', [UserController::class, 'sendOtpPage'])->name('sendOtp');
Route::get('/verifyOtp', [UserController::class, 'verifyOtpPage'])->name('verifyOtp');
Route::get('/resetPassword', [UserController::class, 'resetPasswordPage'])->name('resetPassword');
Route::get('/productPage', [ProductController::class, 'productPage'])->name('product');
Route::get('/invoicePage', [InvoiceController::class, 'invoicePage'])->name('invoice');
Route::get('/reportPage', [ReportController::class, 'reportPage'])->name('report');
Route::get('/salePage', [SaleController::class, 'salePage'])->name('sale');
Route::get('/customerPage', [CustomerController::class, 'customerPage'])->name('customer');
