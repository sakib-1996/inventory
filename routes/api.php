<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\TokenAuthenticate;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoicesController;
use App\Http\Middleware\TokenVerificationMiddleware;

// ====== User related route ======
Route::post('/user-login', [AuthController::class, 'UserLogin']);
Route::post('/CreateProfile', [ProfileController::class, 'CreateProfile'])->middleware([TokenVerificationMiddleware::class]);
Route::get('/ReadProfile', [ProfileController::class, 'ReadProfile'])->middleware([TokenVerificationMiddleware::class]);

// User Logout
Route::get('/logout', [AuthController::class, 'UserLogout']);

// ====== New User related route ======
Route::post('/CreateUser', [ProfileController::class, 'CreateUser'])->middleware([TokenVerificationMiddleware::class]);
Route::get('/user-list', [ProfileController::class, 'UsrList'])->middleware([TokenVerificationMiddleware::class]);
Route::post('/VarifyNewUser', [AuthController::class, 'VarifyNewUser']);
Route::post('/CreatePasswordNewUser', [AuthController::class, 'CreatePasswordNewUser'])->middleware([TokenVerificationMiddleware::class]);
// ========


// ====== Category related route ======
Route::post("/create-category", [CategoryController::class, 'CategoryCreate'])->middleware([TokenVerificationMiddleware::class]);
Route::post("/update-category", [CategoryController::class, 'CategoryUpdate'])->middleware([TokenVerificationMiddleware::class]);
Route::get("/list-category", [CategoryController::class, 'CategoryList'])->middleware([TokenVerificationMiddleware::class]);
Route::get("/category-by-id", [CategoryController::class, 'CategoryByID'])->middleware([TokenVerificationMiddleware::class]);
Route::post("/delete-category", [CategoryController::class, 'CategoryDelete'])->middleware([TokenVerificationMiddleware::class]);


// ====== Product related route ======
Route::post("/create-product", [ProductController::class, 'CreateProduct'])->middleware([TokenVerificationMiddleware::class]);
Route::post("/update-product", [ProductController::class, 'UpdateProduct'])->middleware([TokenVerificationMiddleware::class]);
Route::get("/list-product", [ProductController::class, 'ProductList'])->middleware([TokenVerificationMiddleware::class]);
Route::get("/product-by-id", [ProductController::class, 'ProductByID'])->middleware([TokenVerificationMiddleware::class]);
Route::post("/delete-product", [ProductController::class, 'DeleteProduct'])->middleware([TokenVerificationMiddleware::class]);


// Customer API
Route::post("/create-customer", [CustomerController::class, 'CustomerCreate'])->middleware([TokenVerificationMiddleware::class]);
Route::get("/list-customer", [CustomerController::class, 'CustomerList'])->middleware([TokenVerificationMiddleware::class]);
Route::post("/delete-customer", [CustomerController::class, 'CustomerDelete'])->middleware([TokenVerificationMiddleware::class]);
Route::post("/update-customer", [CustomerController::class, 'CustomerUpdate'])->middleware([TokenVerificationMiddleware::class]);
Route::get("/customer-by-id", [CustomerController::class, 'CustomerByID'])->middleware([TokenVerificationMiddleware::class]);



// Invoice
Route::post("/invoice-create", [InvoicesController::class, 'invoiceCreate'])->middleware([TokenVerificationMiddleware::class]);

Route::get("/invoice-select", [InvoicesController::class, 'invoiceSelect'])->middleware([TokenVerificationMiddleware::class]);
Route::post("/invoice-details", [InvoicesController::class, 'InvoiceDetails'])->middleware([TokenVerificationMiddleware::class]);
Route::post("/invoice-delete", [InvoicesController::class, 'invoiceDelete'])->middleware([TokenVerificationMiddleware::class]);
