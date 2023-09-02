<?php

namespace App\Http\Controllers\Api\Receipts;

use App\Http\Controllers\Api\BaseApiController;
use App\Clients\Receipts\ReceiptClient;
use App\Clients\Receipts\ReceiptItemClient;
use App\Clients\Receipts\ReceiptItemCategoryClient;
use App\Clients\Users\UserClient;

use App\Http\Schemas\Schema;
use Illuminate\Support\Facades\Redis;

use Illuminate\Http\Request;
use App\Http\Requests\Receipts\ReceiptPostRequest;
use App\Http\Requests\Receipts\ReceiptPatchRequest;

use App\Filters\Receipts\ReceiptFilter;
use App\Filters\Receipts\ReceiptCategoryFilter;

use App\Exceptions\Http\UnauthorizedException;
use App\Exceptions\InputValidationException;

class ReceiptController extends BaseApiController {

	static function list(Request $request) {
		$filter = new ReceiptFilter(request: $request);
		$items = ReceiptClient::list(
			user: UserClient::getByToken($request->cookie('token')),
			filter: $filter
		);
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
	}

	static function update(ReceiptPatchRequest $request, string $id) {
		$user = UserClient::getByToken(token: $request->cookie('token'));
		$receipt = ReceiptClient::update(
			receipt: ReceiptClient::get(user: $user, id: $id),
			name: $request->input('name'),
			location: $request->input('location'),
			reference: $request->input('reference'),
			date: $request->input('date'),
			user: $user
		);


		//Update receipt items too
		$items = json_decode($request->input('items'));
		$errors = [];
		foreach ($items as $item) {
			try {
				$filter = new ReceiptCategoryFilter();
				$filter->setName($item->category);

				$categoryList = ReceiptItemCategoryClient::list(user: $user, filter: $filter);
				$categoryModel = sizeOf($categoryList) == 0 ? ReceiptItemCategoryClient::create(name: $item->category, user: $user) : reset($categoryList);
				$receiptItem = null;

				if (property_exists($item, 'id')) {
					$receiptItem = ReceiptItemClient::get(id: $item->id, receipt: $receipt, user: $user);
				}

				if ($receiptItem != null) {
					$receiptItem = ReceiptItemClient::update(
						item: $receiptItem,
						name: $item->name,
						count: $item->count,
						cost: $item->cost,
						category: $categoryModel,
						user: $user
					);
				} else {
					$receiptItem = ReceiptItemClient::create(
						receipt: $receipt,
						name: $item->name,
						count: $item->count,
						cost: $item->cost,
						user: $user,
						category: $categoryModel->Name
					);
				}
			} catch (InputValidationException $e) {
				$errors += [$item->name . ': ' . $e->getMessage()];
			}
		}

		//Update Receipt Cost and Total
		$receipt = ReceiptClient::generateCostAndCategory(receipt: $receipt->refresh());
		return parent::sendResponse(body: Schema::schema($receipt, 'Receipt'));
	}

	static function getYearlyCosts(Request $request): array {
		$user = UserClient::getByToken($request->cookie('token'));
		$key = 'user-id-' . $user->id . ':costs:yearly';
		if (count(Redis::hgetall($key)) > 0) {
			$result = [];
			foreach (Redis::hgetall($key) as $cost) {
				$result[] = floatval($cost);
			}
			return [
				'result' => $result
			];
		}

		$result = ReceiptClient::getYearlyReceiptCosts(
			user: $user,
			year: date('Y')
		);
		for ($i = 0; $i < count($result); $i++) {
			Redis::hset($key, $i+1, $result[$i]);
		}
		Redis::expire($key, 86400);
		return [
			'result' => $result
		];
	}
}