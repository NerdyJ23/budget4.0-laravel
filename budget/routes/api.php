<?php

use Illuminate\Http\Request;
use App\Http\Requests\Users\UserPostRequest;
use App\Http\Requests\Login\LoginPostRequest;
use App\Http\Requests\Receipts\ReceiptPostRequest;
use App\Http\Requests\Receipts\ReceiptCategoryPostRequest;

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Receipts\ReceiptController;
use App\Http\Controllers\Api\Receipts\ReceiptCategoryController;
use App\Http\Controllers\Api\Users\UserController;
use App\Http\Controllers\Api\Login\LoginController;

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

Route::prefix('receipt')->middleware('logged_in')->group(function () {
	Route::prefix('category')->group(function () {
		Route::post('/', function (ReceiptCategoryPostRequest $request) {
			return ReceiptCategoryController::create(request: $request);
		});
	});

	Route::get('/', function (Request $request) {
		return ReceiptController::list(request: $request);
	});

	Route::post('/', function (ReceiptPostRequest $request) {
		return Receiptcontroller::create(request: $request);
	});

	Route::get('/{uuid}', function (Request $request, string $receiptId) {
		return ReceiptController::get(request: $request, id: $receiptId);
	});
});

Route::prefix('user')->group(function () {
	Route::post('/', function (UserPostRequest $request) {
		return UserController::create(request: $request);
	});

	Route::post('/login', function (LoginPostRequest $request) {
		return LoginController::login(request: $request);
	});
});
