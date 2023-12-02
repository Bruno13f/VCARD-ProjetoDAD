<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\TransactionController;
use App\Http\Controllers\api\CategoryController;
use App\Http\Controllers\api\AdminController;
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
Route::middleware('auth:api')->group(function () {
    
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('users/me', [UserController::class, 'show_me']);

    //Admins
    Route::put('admins/{admin}', [AdminController::class,'update']);
    Route::patch('admins/{admin}/password', [AdminController::class,'update_password']);

    //Users
    Route::get('users', [UserController::class, 'index'])->middleware('can:viewAny,App\Models\User');
    Route::get('users/{user}', [UserController::class, 'show'])->middleware('can:view,user');
    Route::put('users/{user}', [UserController::class, 'update'])->middleware('can:update,user');
    
    //Vcards
    Route::get('vcards', [VcardController::class, 'index']);
    Route::get('vcards/{vcard}', [VcardController::class, 'show']);
    Route::put('vcards/{vcard}', [VcardController::class, 'update']);
    Route::patch('vcards/{vcard}/maxDebit', [VcardController::class, 'updateMaxDebit']);
    Route::patch('vcards/{vcard}/blocked', [VCardController::class, 'updateBlocked']);
    Route::patch('vcards/{vcard}/profile', [VCardController::class, 'updateProfile']);
    Route::get('vcards/{vcard}/transactions', [VCardController::class, 'getTransactionsOfVcard']);
    Route::delete('vcards/{vcard}', [VCardController::class,'destroy']);
    Route::patch('vcards/{vcard}/password', [VCardController::class, 'update_password']);
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
});

Route::post('vcards', [VcardController::class, 'store']);
Route::post('transactions', [TransactionController::class, 'store']);












