<?php

use App\Http\Controllers\PaymentController;
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

Route::get('/generateToken', [PaymentController::class, 'generateToken']);
Route::get('/registerIPN', [PaymentController::class, 'registerIPN']);
Route::get('/getIPNList', [PaymentController::class, 'getIPNList']);
Route::get('/submitOrder', [PaymentController::class, 'submitOrder']);
Route::get('/callback', [PaymentController::class, 'callback']);
Route::get('/getTransactionStatus/{orderTrackingId}', [PaymentController::class, 'getTransactionStatus'])->name('get.transaction.status');




