<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\TransactionController;
use App\Http\Controllers\api\CategoryController;
use App\Http\Controllers\api\VcardController;
use App\Http\Controllers\api\DefaultCategoryController;
use App\Http\Controllers\api\AuthController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');

//Users

Route::get('users', [UserController::class, 'index']);
Route::get('users/{user}', [UserController::class, 'show']);
Route::put('users/{user}', [UserController::class, 'update']);

//Vcards

Route::get('vcards', [VcardController::class, 'index']);
Route::get('vcards/{vcard}', [VcardController::class, 'show']);
Route::post('vcards', [VcardController::class, 'store']);
Route::put('vcards/{vcard}', [VcardController::class, 'update']);
Route::patch('vcards/{vcard}/maxDebit', [VcardController::class, 'updateMaxDebit']);
Route::patch('vcards/{vcard}/blocked', [VCardController::class, 'updateBlocked']);
Route::get('vcards/{vcard}/transactions', [VCardController::class, 'getTransactiosOfVcard']);
Route::delete('vcards/{vcard}', [VCardController::class,'destroy']);
//Route::get('vcards/{vcard}/categories', [CategoryController::class, 'getCategoryOfVcard']);

//Transactions

Route::get('transactions', [TransactionController::class, 'index']);
Route::get('transactions/{transaction}', [TransactionController::class, 'show']);
Route::put('transactions/{transaction}', [TransactionController::class,'update']);
Route::delete('transactions/{transaction}', [TransactionController::class, 'destroy']);

//Categorias

Route::get('categories', [DefaultCategoryController::class, 'index']);
Route::get('categories/{category}', [DefaultCategoryController::class, 'show']);
//Route::get('categories/{category}/transactions', [TransactionController::class, 'getCategoryOfTransaction']);

