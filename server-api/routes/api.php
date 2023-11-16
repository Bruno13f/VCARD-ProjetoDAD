<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\TransactionController;
use App\Http\Controllers\api\CategoryController;
use App\Http\Controllers\api\VcardController;
use App\Http\Controllers\api\DefaultCategoryController;


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

//Users

Route::get('users', [UserController::class, 'index']);

//Vcards

Route::get('vcards', [VcardController::class, 'index']);
Route::get('vcards/{vcard}/transactions', [TransactionController::class, 'getTransactionOfVcard']);
Route::get('vcards/{vcard}/categories', [CategoryController::class, 'getCategoryOfVcard']);
Route::put('vcards/{vcard}', [VcardController::class, 'update']);


//Transactions

Route::get('transactions', [TransactionController::class, 'index']);

//Categorias

Route::get('categories', [DefaultCategoryController::class, 'index']);
Route::get('categories/{category}/transactions', [TransactionController::class, 'getCategoryOfTransaction']);

