<?php

namespace App\Http\Controllers\Api\Receipts;

use App\Http\Controllers\Api\BaseApiController;
use App\Clients\Receipts\ReceiptClient;
use App\Clients\Users\UserClient;

use App\Http\Schemas\Schema;

use Illuminate\Http\Request;
use App\Http\Requests\Receipts\ReceiptListRequest;
use App\Http\Requests\Receipts\ReceiptPostRequest;

use App\Exceptions\Http\UnauthorizedException;

class ReceiptController extends BaseApiController {

	static function list(Request $request) {
		$items = ReceiptClient::list(user: UserClient::getByToken($request->cookie('token')));
		return parent::sendResponse(Schema::schema($items, 'Receipt'));
	}

	static function create(ReceiptPostRequest $request) {
		$receipt = ReceiptClient::create(
			name: $request->input('name'),
			location: $request->input('location'),
			reference: $request->input('reference'),
			date: $request->input('date'),
			user: UserClient::getByToken($request->cookie('token'))
		);
		$items = json_decode($request->input('items'));
		foreach ($items as $item) {
			dump($item);
		}
		return parent::sendResponse(Schema::schema($receipt, 'Receipt'));
	}

	static function get(Request $request, string $id): mixed {
		return null;
	}
}