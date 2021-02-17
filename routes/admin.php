<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\RegisteredAdminController;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\NewPasswordController;
use App\Http\Controllers\Admin\Auth\PasswordResetLinkController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group. Now create something great!
|
*/
Route::get('/refresh-csrf', function () {
    return csrf_token();
})->name('admin.csrf');

Route::group(['middleware' => 'guest:admin'], function () {
    
    Route::get('/', function () {
	    return redirect(route('admin.login'));
	})->name('admin');

    Route::get('/register', [RegisteredAdminController::class, 'create'])->name('admin.register');
	Route::post('/register', [RegisteredAdminController::class, 'store'])->name('admin.register');

	Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('admin.login');
	Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('admin.login');

	Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('admin.password.request');              
	Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('admin.password.email');
                
	Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('admin.password.reset');               
	Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('admin.password.update');                

});

Route::group(['middleware' => 'auth:admin'], function () {
    
    Route::view('/dashboard', 'admin.dashboard')->name('admin.dashboard');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('admin.logout');

    Route::resource('category', CategoryController::class)->except(['show']);
    Route::post('/category/status', [CategoryController::class, 'updateStatus'])->name('category.status.update');

   	Route::resource('product', ProductController::class);
   	Route::post('/product/status', [ProductController::class, 'updateStatus'])->name('product.status.update');

    Route::post('/categories', [CategoryController::class, 'getCategories'])->name('admin.dt.categories');
    Route::post('/products', [ProductController::class, 'getProducts'])->name('admin.dt.products');

});
