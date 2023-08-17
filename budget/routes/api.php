<?php

use Illuminate\Http\Request;
use App\Http\Requests\Users\UserPostRequest;

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Receipts\ReceiptController;
use App\Http\Controllers\Api\Users\UserController;

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

Route::prefix('receipt')->group(function () {
	Route::get('/', function () {
		return ReceiptController::list();
	});
});

Route::prefix('user')->group(function () {
	Route::post('/', function (UserPostRequest $request) {
		return UserController::create(request: $request);
	});
	Route::post('/login', function (Request $request) {
		return UserController::login(request: $request);
	});
});
