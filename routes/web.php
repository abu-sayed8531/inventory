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
use App\Http\Middleware\TokenVerificationMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'homePage']);
Route::get('/dashboard', [DashBoardController::class, 'dashboardPage'])->name('dashboard');
Route::get('/category-page', [CategoryController::class, 'categoryPage'])->name('category');
Route::get('user-profile', [UserController::class, 'profilePage'])->name('profile');
Route::get('/user-login', [UserController::class, 'userLoginPage'])->name('login.page');
Route::post('/user-login', [UserController::class, 'userLogin'])->name('login');
Route::get('/user-registration', [UserController::class, 'userRegistrationPage'])->name('registration.page');
Route::post('/user-registration', [UserController::class, 'userRegistration'])->name('registration');
Route::get('/logout', [UserController::class, 'logout'])->middleware(TokenVerificationMiddleware::class);
Route::get('/send-otp', [UserController::class, 'sendOtpPage'])->name('sendOtp');
Route::post('/send-otp', [UserController::class, 'sendOtp']);
Route::get('/verify-otp', [UserController::class, 'verifyOtpPage'])->name('verifyOtp');
Route::post('/verify-otp', [UserController::class, 'verifyOtp']);
Route::get('/reset-password', [UserController::class, 'resetPasswordPage'])->name('resetPassword');
Route::post('/reset-password', [UserController::class, 'resetPassword'])->middleware(TokenVerificationMiddleware::class);
Route::get('/product-page', [ProductController::class, 'productPage'])->name('product');
Route::get('/invoice-page', [InvoiceController::class, 'invoicePage'])->name('invoice');
Route::get('/report-page', [ReportController::class, 'reportPage'])->name('report');
Route::get('/sale-page', [SaleController::class, 'salePage'])->name('sale');
Route::get('/customer-page', [CustomerController::class, 'customerPage'])->name('customer');
