<?php

namespace App\Http\Controllers\Api\Receipts;

use App\Http\Controllers\Api\BaseApiController;
use App\Clients\Receipts\ReceiptClient;
use App\Clients\Receipts\ReceiptItemClient;
use App\Clients\Users\UserClient;

use App\Http\Schemas\Schema;

use Illuminate\Http\Request;
use App\Http\Requests\Receipts\ReceiptListRequest;
use App\Http\Requests\Receipts\ReceiptPostRequest;

use App\Exceptions\Http\UnauthorizedException;
use App\Exceptions\InputValidationException;

class ReceiptController extends BaseApiController {

	static function list(Request $request) {
		$items = ReceiptClient::list(user: UserClient::getByToken($request->cookie('token')));
		return parent::sendResponse(Schema::schema($items, 'Receipt'));
	}

	static function create(ReceiptPostRequest $request) {
		$user = UserClient::getByToken($request->cookie('token'));
		$receipt = ReceiptClient::create(
			name: $request->input('name'),
			location: $request->input('location'),
			reference: $request->input('reference'),
			date: $request->input('date'),
			user: $user
		);

		//Add Items
		$items = json_decode($request->input('items'));
		$errors = [];
		foreach ($items as $item) {
			try {
				$item = ReceiptItemClient::create(
					receipt: $receipt,
					name: $item->name,
					count: $item->count,
					cost: $item->cost,
					category: $item->category,
					user: $user
				);
			} catch (InputValidationException $e) {
				$errors += [$item->name . ': ' . $e->getMessage()];
			}
		}

		//Update Receipt Cost and Total
		$receipt = ReceiptClient::generateCostAndCategory(receipt: $receipt->refresh());

		return parent::sendResponse(
			body: Schema::schema($receipt, 'Receipt'),
			errors: $errors,
			code: 201
		);
	}

	static function get(Request $request, string $id) {
		$user = UserClient::getByToken(token: $request->cookie('token'));

		return parent::sendResponse(
			body: Schema::schema(ReceiptClient::get(id: $id, user: $user), 'Receipt')
		);
		return null;
	}
}