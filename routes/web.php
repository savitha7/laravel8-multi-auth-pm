<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::group(['middleware' => 'auth'], function () {   
   	Route::get('/products', [ProductController::class, 'index'])->name('user.product.index');
   	Route::get('/product/{product}', [ProductController::class, 'show'])->name('user.product.show');
    Route::post('/dt/products', [ProductController::class, 'getProducts'])->name('dt.products');
});

Route::get('/refresh-csrf', function () {
    return csrf_token();
});

require __DIR__.'/auth.php';
