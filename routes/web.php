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
use App\Models\Customer;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'homePage']);
Route::get('/dashboard', [DashBoardController::class, 'dashboardPage'])->name('dashboard')->middleware(TokenVerificationMiddleware::class);
Route::get('/category-page', [CategoryController::class, 'categoryPage'])->name('category');
Route::get('/user-profile', [UserController::class, 'profilePage'])->name('profile')->middleware(TokenVerificationMiddleware::class);
Route::get('/get-profile', [UserController::class, 'GetUserProfile'])->middleware(TokenVerificationMiddleware::class);
Route::get('/user-login', [UserController::class, 'userLoginPage'])->name('login.page');
Route::get('/user-registration', [UserController::class, 'userRegistrationPage'])->name('registration.page');
Route::get('/logout', [UserController::class, 'logout'])->middleware(TokenVerificationMiddleware::class);
Route::get('/send-otp', [UserController::class, 'sendOtpPage'])->name('sendOtp');
Route::get('/verify-otp', [UserController::class, 'verifyOtpPage'])->name('verifyOtp');
Route::get('/reset-password', [UserController::class, 'resetPasswordPage'])->name('resetPassword');
Route::get('/product-page', [ProductController::class, 'productPage'])->name('product')->middleware(TokenVerificationMiddleware::class);
Route::get('/invoice-page', [InvoiceController::class, 'invoicePage'])->name('invoice')->middleware(TokenVerificationMiddleware::class);
Route::get('/report-page', [ReportController::class, 'reportPage'])->name('report')->middleware(TokenVerificationMiddleware::class);
Route::get('/sale-page', [SaleController::class, 'salePage'])->name('sale')->middleware(TokenVerificationMiddleware::class);
Route::get('/customer-page', [CustomerController::class, 'customerPage'])->name('customer')->middleware(TokenVerificationMiddleware::class);
// User controller Api routes 
Route::post('/user-registration', [UserController::class, 'userRegistration'])->name('registration');
Route::post('/send-otp', [UserController::class, 'sendOtp']);
Route::post('/verify-otp', [UserController::class, 'verifyOtp']);
Route::post('/reset-password', [UserController::class, 'resetPassword'])->middleware(TokenVerificationMiddleware::class);
Route::post('/user-login', [UserController::class, 'userLogin'])->name('login');
Route::post('/user-update', [UserController::class, 'UserUpdate'])->middleware(TokenVerificationMiddleware::class);

//Category Api
Route::post('/category-list', [CategoryController::class, 'CategoryList'])->middleware(TokenVerificationMiddleware::class);
Route::post('/category-create', [CategoryController::class, 'CategoryCreate'])->middleware(TokenVerificationMiddleware::class);
Route::post('/category-update', [CategoryController::class, 'CategoryUpdate'])->middleware(TokenVerificationMiddleware::class);
Route::post('/category-delete', [CategoryController::class, 'CategoryDelete'])->middleware(TokenVerificationMiddleware::class);
Route::post('/category-by-id', [CategoryController::class, 'CategoryById'])->middleware(TokenVerificationMiddleware::class);

// customer Api
Route::post('/customer-list', [CustomerController::class, 'CustomerList'])->middleware(TokenVerificationMiddleware::class);
Route::post('/customer-create', [CustomerController::class, 'CustomerCreate'])->middleware(TokenVerificationMiddleware::class);
Route::post('/customer-update', [CustomerController::class, 'CustomerUpdate'])->middleware(TokenVerificationMiddleware::class);
Route::post('/customer-delete', [CustomerController::class, 'CustomerDelete'])->middleware(TokenVerificationMiddleware::class);
Route::post('/customer-by-id', [CustomerController::class, 'CustomerById'])->middleware(TokenVerificationMiddleware::class);

// Product Api routes
Route::post('/product-create', [ProductController::class, 'ProductCreate'])->middleware(TokenVerificationMiddleware::class);
Route::post('/product-list', [ProductController::class, 'ProductList'])->middleware(TokenVerificationMiddleware::class);
Route::post('/product-by-id', [ProductController::class, 'ProductById'])->middleware(TokenVerificationMiddleware::class);
Route::post('/product-update', [ProductController::class, 'ProductUpdate'])->middleware(TokenVerificationMiddleware::class);
Route::post('/product-delete', [ProductController::class, 'ProductDelete'])->middleware(TokenVerificationMiddleware::class);
// Invoice API routes
Route::post('/invoice-create', [InvoiceController::class, 'InvoiceCreate'])->middleware(TokenVerificationMiddleware::class);
Route::post('/invoice-details', [InvoiceController::class, 'InvoiceDetails'])->middleware(TokenVerificationMiddleware::class);
Route::post('/invoice-list', [InvoiceController::class, 'InvoiceList'])->middleware(TokenVerificationMiddleware::class);
Route::post('/invoice-delete', [InvoiceController::class, 'InvoiceDelete'])->middleware(TokenVerificationMiddleware::class);
// Dashboard Api routes
Route::post('/dashboard-list', [DashBoardController::class, 'DashboardList'])->middleware(TokenVerificationMiddleware::class);
// Report API route
Route::get('/sales-report/{FromDate}/{ToDate}', [ReportController::class, 'SalesReport'])->middleware(TokenVerificationMiddleware::class)->name('sales.report');
Route::get('/stock-report/{PFromDate}/{PToDate}', [ReportController::class, 'StockReport'])->middleware(TokenVerificationMiddleware::class)->name('stock.report');
Route::get('/customer-report/{FromDate}/{ToDate}', [ReportController::class, 'CustomerReport'])->middleware(TokenVerificationMiddleware::class)->name('customer.report');
