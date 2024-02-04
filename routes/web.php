<?php

use Illuminate\Support\Facades\Route;


Route::view('/login','pages.auth.login-page')->name('login');
Route::view('/new-login','pages.auth.new-login-page');
Route::view('/create-password','pages.auth.create-password-page');
Route::view('/dashboard','pages.dashboard.dashboard-page');
Route::view('/profile','pages.dashboard.profile-page');
Route::view('/role-management','pages.dashboard.role-management');
Route::view('/category','pages.dashboard.category-page');
Route::view('/product','pages.dashboard.product-page');
Route::view('/customer','pages.dashboard.customer-page');
Route::view('/sale-page','pages.dashboard.sale-page');
Route::view('/invoicePage','pages.dashboard.invoice-page');