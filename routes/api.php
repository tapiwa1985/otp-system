<?php

use App\Http\Controllers\Api\OTPRequestController;
use App\Http\Controllers\Api\OTPVerificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(
    ['middleware' => 'api.logging'],
    function () {
        Route::post('otp', [OTPRequestController::class, 'store']);
        Route::post('verify', [OTPVerificationController::class, 'verify']);
    });