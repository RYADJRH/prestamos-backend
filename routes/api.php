<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\BeneficiaryController;

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

Route::post('user/login',  LoginController::class);
Route::post('user/logout', LogoutController::class);


Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/user', UserController::class);
    /* Beneficiarios */
    Route::controller(BeneficiaryController::class)->group(function () {
        Route::post('/beneficiary', 'create');
        Route::delete('/beneficiary/{beneficiary}', 'destroy');
    });
});

