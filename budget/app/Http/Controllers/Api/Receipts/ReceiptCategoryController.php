<?php

namespace App\Http\Controllers\Api\Receipts;

use App\Http\Controllers\Controller;
use App\Clients\Receipts\ReceiptItemCategoryClient as CategoryClient;
use App\Clients\Users\UserClient;

use App\Http\Schemas\Schema;
use App\Filters\Receipts\ReceiptCategoryFilter;

use App\Http\Requests\Receipts\ReceiptCategoryPostRequest;
use Illuminate\Http\Request;


class ReceiptCategoryController extends Controller
{
	static function list(Request $request) {
		$filter = new ReceiptCategoryFilter(request: $request);
		$user = UserClient::getByToken($request->cookie('token'));
		$result = CategoryClient::list(user: $user, filter: $filter);

		return parent::sendResponse(body: Schema::schema($result, 'ReceiptItemCategory'));
	}

	static function create(ReceiptCategoryPostRequest $request) {
		$user = UserClient::getByToken($request->cookie('token'));
		$category = CategoryClient::create(user: $user, name: $request->input('name'));
		return parent::sendResponse(
			body: Schema::schema($category, 'ReceiptItemCategory'),
			code: 201
		);
	}

	static function get(Request $request, string $category) {
		$user = UserClient::getByToken($request->cookie('token'));
		$category = CategoryClient::get(user: $user, category: $category);
		return parent::sendResponse(body: Schema::schema($category, 'ReceiptItemCategory'));
	}

	static function archive(Request $request, string $category) {
		$user = UserClient::getByToken($request->cookie('token'));

		$success = CategoryClient::archive(user: $user, category: $category);
		return parent::sendResponse(
			code: $success ? 204 : 500
		);
	}
    //
}
