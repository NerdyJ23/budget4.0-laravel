<?php

namespace App\Http\Controllers\Api\Receipts;

use App\Http\Controllers\Controller;
use App\Clients\Receipts\ReceiptItemCategoryClient as CategoryClient;
use App\Clients\Users\UserClient;

use App\Http\Schemas\Schema;

use App\Http\Requests\Receipts\ReceiptCategoryPostRequest;
use Illuminate\Http\Response;


class ReceiptCategoryController extends Controller
{
	static function create(ReceiptCategoryPostRequest $request) {
		$user = UserClient::getByToken($request->cookie('token'));
		$category = CategoryClient::create(user: $user, name: $request->input('name'));
		return parent::sendResponse(
			body: Schema::schema($category, 'ReceiptItemCategory'),
			code: 201
		);
	}
    //
}
