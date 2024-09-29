<?php

use App\Http\Controllers\StripeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Unprotected Routes
Route::middleware('guest')->group(function(){
    Route::get('/register',[UserController::class, 'register'])->name('register');
    Route::post('/register',[UserController::class, 'registerStore'])->name('user.register');
    
    //Login Routes
    Route::get('/login',[UserController::class, 'login'])->name('login');
    Route::post('/login',[UserController::class, 'verifyingUser'])->name('user.login');
});

//Protected Routes
Route::middleware('auth')->group(function(){

    Route::get('/home', [UserController::class, 'dashboard'])->name('user.dashboard');
    Route::post('/logout', [UserController::class, 'logout'])->name('user.logout');


    //stripe routes
    Route::post('/create-checkout-session', [StripeController::class, 'checkoutStripeSession'])->name('checkout.session');
    Route::get('/checkout-success', [StripeController::class, 'success'])->name('checkout.success');
    Route::get('/checkout-cancel', [StripeController::class, 'cancel'])->name('checkout.cancel');

});
