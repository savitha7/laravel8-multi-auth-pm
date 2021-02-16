<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\RegisteredAdminController;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\NewPasswordController;
use App\Http\Controllers\Admin\Auth\PasswordResetLinkController;

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

});
