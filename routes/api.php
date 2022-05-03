<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\BeneficiaryController;
use App\Http\Controllers\BorrowerController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\PayslipController;

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
        Route::post('/group/member', 'addMember');
        Route::put('/group/member/{groupBorrower}',  'updateMember');
        Route::delete('/group/member/{groupBorrower}',  'deleteMember');
        Route::get('/group/members/{group:slug}', 'groupMembers');

        /* payslip */

        Route::post('/group/payslip', 'addPaySlip');
        Route::put('/group/payslip/{payslip}', 'updatePayslip');
        Route::delete('/group/payslip/{payslip}', 'deletePayslip');
        Route::get('/group/payslip/{group:slug}', 'listPayslips');
    });

    /* Payslip */

    Route::controller(PayslipController::class)->group(function () {
        Route::get('/payslip/{payslip:slug}', 'getPayslip');
        Route::get('/payslip/payments/{payslip:slug}', 'getPayments');

        Route::get('/payslip/payments/member/{payslip:slug}', 'addPaymentsMemberPayslip');
        Route::post('/payslip/payments/member', 'registerPaymentsPayslip');

        Route::delete('/payslip/payments/member/{payments:id_payment}', 'deletePaymentPasyslip');
        Route::put('/payslip/payments/member/{payments:id_payment}', 'updatePaymentPayslip');
        Route::post('/payslip/payments/status', 'changeStatusPayment');
    });
});
