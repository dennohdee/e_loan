<?php

use Illuminate\Support\Facades\Route;

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
    return redirect('home');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
/** 
 * array question function route
 * */
Route::get('/array_function', [App\Http\Controllers\HomeController::class, 'arrayQuestion']);

/**
 * users
 * */
Route::prefix('users')->group(function () {
    Route::get('/', [App\Http\Controllers\UserController::class, 'index'])->name('users');
    Route::get('/create', [App\Http\Controllers\UserController::class, 'create'])->name('users.create');
    Route::post('/', [App\Http\Controllers\UserController::class, 'store'])->name('users.store');
    Route::get('/{user}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
    Route::post('/{user}', [App\Http\Controllers\UserController::class, 'update'])->name('users.update');
    Route::delete('/{user}', [App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');
});
/**
 * customers
 * */
Route::prefix('customers')->group(function () {
    Route::get('/', [App\Http\Controllers\CustomerController::class, 'index'])->name('customers');
    Route::get('/create', [App\Http\Controllers\CustomerController::class, 'create'])->name('customers.create');
    Route::post('/', [App\Http\Controllers\CustomerController::class, 'store'])->name('customers.store');

    Route::get('/customers/{customer}/edit', [App\Http\Controllers\CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{customer}', [App\Http\Controllers\CustomerController::class, 'update'])->name('customers.update');
});

/**
 * Loans
 *  */ 
Route::prefix('loans')->group(function () {
    Route::get('/', [App\Http\Controllers\LoanController::class, 'index'])->name('loans');
    Route::get('/create', [App\Http\Controllers\LoanController::class, 'create'])->name('loans.create');
    Route::post('/', [App\Http\Controllers\LoanController::class, 'store'])->name('loans.store');
    Route::get('/approve/{loan}', [App\Http\Controllers\LoanController::class, 'approve'])->name('loans.approve');
    Route::get('/reject/{loan}', [App\Http\Controllers\LoanController::class, 'reject'])->name('loans.reject');
    Route::post('/disburse/{loan}', [App\Http\Controllers\LoanController::class, 'disburse'])->name('loans.disburse');
    Route::post('/repay/{loan}', [App\Http\Controllers\LoanController::class, 'repay'])->name('loans.repay');
});
/**
 * Loan products
 */
Route::prefix('loan-products')->group(function () {
    Route::get('/', [App\Http\Controllers\LoanProductController::class, 'index'])->name('loan-products');
    Route::get('/create', [App\Http\Controllers\LoanProductController::class, 'create'])->name('loan-products.create');
    Route::post('/', [App\Http\Controllers\LoanProductController::class, 'store'])->name('loan-products.store');
    Route::get('/{user}/edit', [App\Http\Controllers\LoanProductController::class, 'edit'])->name('loan-products.edit');
    Route::post('/{user}', [App\Http\Controllers\LoanProductController::class, 'update'])->name('loan-products.update');
});
