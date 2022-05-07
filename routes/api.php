<?php

use App\Http\Controllers\AmortizationController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\BeneficiaryController;
use App\Http\Controllers\BorrowerController;
use App\Http\Controllers\GroupController;

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
    /* Beneficiary */
    Route::controller(BeneficiaryController::class)->group(function () {
        Route::post('/beneficiary', 'create');
        Route::put('/beneficiary/{beneficiary}', 'update');
        Route::delete('/beneficiary/{beneficiary}', 'destroy');
        Route::get('/beneficiary', 'getAll');
    });

    /* Borrower */
    Route::controller(BorrowerController::class)->group(function () {
        Route::post('/borrower', 'create');
        Route::get('/borrower/beneficiary/{beneficiary}', 'getAll');
        Route::post('/borrower/{borrower}', 'update');
        Route::delete('/borrower/{borrower}', 'delete');
        Route::get('/borrower/add/group/{group:slug}', 'listBorrowerAddGroup');
    });

    /* Group */
    Route::controller(GroupController::class)->group(function () {
        Route::post('/group', 'create');
        Route::delete('/group/{group}', 'delete');
        Route::put('/group/{group}', 'update');
        Route::get('/group/{beneficiary}', 'getAll');
        Route::get('/group/slug/{group:slug}', 'group');

        /* members */
        Route::get('/group/members/{group:slug}', 'groupMembers');
        Route::post('/group/member', 'addMember');
        Route::delete('/group/member/{groupBorrower}',  'deleteMember');
    });

    /* Amortization */
    Route::controller(AmortizationController::class)->group(function () {
        Route::post('/amortization/group/calculated', 'fnCalculatedAmortizationGroup');
    });
});
