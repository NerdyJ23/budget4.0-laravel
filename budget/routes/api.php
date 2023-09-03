<?php

//Requests
use Illuminate\Http\Request;
use App\Http\Requests\Users\UserPostRequest;
use App\Http\Requests\Login\LoginPostRequest;
use App\Http\Requests\Receipts\ReceiptPostRequest;
use App\Http\Requests\Receipts\ReceiptPatchRequest;
use App\Http\Requests\Receipts\ReceiptCategoryPostRequest;
use App\Http\Requests\Receipts\ReceiptCategoryPatchRequest;

use Illuminate\Support\Facades\Route;

//Controllers
use App\Http\Controllers\Api\Receipts\ReceiptController;
use App\Http\Controllers\Api\Receipts\ReceiptStatsController;
use App\Http\Controllers\Api\Receipts\ReceiptDocumentController;
use App\Http\Controllers\Api\Receipts\ReceiptItemController;
use App\Http\Controllers\Api\Receipts\ReceiptCategoryController;
use App\Http\Controllers\Api\Users\UserController;
use App\Http\Controllers\Api\Login\LoginController;

use App\Http\Controllers\RedisController;
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
		Route::get('/', function (Request $request) {
			return ReceiptCategoryController::list(request: $request);
		});

		Route::get('/{uuid}', function (Request $request, string $categoryId) {
			return ReceiptCategoryController::get(request: $request, id: $categoryId);
		});

		Route::post('/', function (ReceiptCategoryPostRequest $request) {
			return ReceiptCategoryController::create(request: $request);
		});

		Route::patch('/{uuid}', function (ReceiptCategoryPatchRequest $request, string $categoryId) {
			return ReceiptCategoryController::update(request: $request, id: $categoryId);
		});

		Route::delete('/{uuid}', function (Request $request, string $categoryId) {
			return ReceiptCategoryController::archive(request: $request, id: $categoryId);
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

	Route::patch('/{uuid}', function (ReceiptPatchRequest $request, string $receiptId) {
		return ReceiptController::update(request: $request, id: $receiptId);
	});

	Route::delete('/{uuid}/items', function (Request $request, string $receiptId) {
		return ReceiptItemController::bulkDelete(request: $request, receiptId: $receiptId);
	});

	//Receipt Documents
	Route::get('/{uuid}/documents', function (Request $request, string $receiptId) {
		return ReceiptDocumentController::list(request: $request, receiptId: $receiptId);
	});

	Route::post('/{uuid}/documents', function (Request $request, string $receiptId) {
		return ReceiptDocumentController::upload(request: $request, receiptId: $receiptId);
	});

	Route::get('/{uuid}/documents/{doc}', function (Request $request, string $receiptId, string $docId) {
		return ReceiptDocumentController::get(request: $request, receiptId: $receiptId, id: $docId);
	});

	Route::delete('/{uuid}/documents/{doc}', function (Request $request, string $receiptId, string $docId) {
		return ReceiptDocumentController::delete(request: $request, receiptId: $receiptId, id: $docId);
	});

	//Receipt Stats
	Route::get('/costs/yearly', function (Request $request) {
		return ReceiptStatsController::getYearlyCosts(request: $request);
	});
});

Route::prefix('user')->group(function () {
	Route::post('/', function (UserPostRequest $request) {
		return UserController::create(request: $request);
	});

	Route::post('/login', function (LoginPostRequest $request) {
		return LoginController::login(request: $request);
	});

	Route::get('/logout', function (Request $request) {
		return LoginController::logout(request: $request);
	});
});
