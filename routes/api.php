<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\CustomerController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/account_verification', [CustomerController::class, 'accountVerification']);
Route::post('/otp_confirmation', [CustomerController::class, 'otpConfirmation']);
Route::post('/card_upload', [CustomerController::class, 'cardUpload']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
