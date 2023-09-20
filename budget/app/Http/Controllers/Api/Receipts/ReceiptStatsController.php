<?php

namespace App\Http\Controllers\Api\Receipts;

use App\Http\Controllers\Api\BaseApiController;
use App\Clients\Users\UserClient;
use App\Clients\Receipts\ReceiptClient;
use App\Clients\Receipts\ReceiptStatsClient;

use App\Http\Schemas\Schema;
use Illuminate\Support\Facades\Redis;

use Illuminate\Http\Request;

use App\Filters\Receipts\ReceiptFilter;
use App\Filters\Receipts\ReceiptCategoryFilter;

use App\Exceptions\Http\UnauthorizedException;

class ReceiptStatsController extends BaseApiController {

	static function getYearlyCosts(Request $request): array {
		$user = UserClient::getByToken($request->cookie('token'));
		$year = $request->query('year') ?? date('Y');
		$key = 'user-id-' . $user->id . ':stats:costs:yearly';

		if (Redis::hget($key, $year) != null) {
			$result = [];
			$cached = (array) json_decode(Redis::hget($key, $year));
			foreach ($cached as $cost) {
				$result[] = round(floatval($cost), 2);
			}
			return [
				'result' => $result
			];
		}

		$result = ReceiptStatsClient::getTotalCostPerMonth(
			user: $user,
			year: $year
		);
		Redis::hset($key, $year, json_encode($result));
		Redis::expire($key, 86400);

		$output = [];
		foreach ($result as $i) {
			$output[] = round($i, 2);
		}
		return [
			'result' => $output
		];
	}

	static function getFavouriteStores(Request $request) {
		$user = UserClient::getByToken($request->cookie('token'));
		$year = $request->query('year') ?? date('Y');
		$key = 'user-id-' . $user->id . ':stats:stores:yearly';
		if (Redis::hexists($key, $year) == 1) {
			$output = json_decode(Redis::hget($key, $year));
		} else {
			$result = ReceiptStatsClient::getStoreCount(user: $user, year: $year);
			$output = [];
			foreach($result->sortDesc()->all() as $store => $value) {
				$cost = ReceiptStatsClient::getYearlyCostOfStore(user: $user, store: $store, year: $year);
				$obj = [
					'store' => $store,
					'count' => $value,
					'total' => round($cost, 2)
				];
				$output[] = $obj;
			}
			Redis::hset($key, $year, json_encode($output));
		}
		return [
			'result' => $output
		];
	}

	static function getFavouriteCategories(Request $request) {
		$user = UserClient::getByToken($request->cookie('token'));
		$year = $request->query('year') ?? date('Y');
		$key = 'user-id-' . $user->id . ':stats:categories:yearly';
		if (Redis::hexists($key, $year) == 1) {
			$result = json_decode(Redis::hget($key, $year));
		} else {
			$result = ReceiptStatsClient::getYearlyCostOfCategory(user: $user, year: $year);
			Redis::hset($key, $year, json_encode($result));
		}
		return [
			'result' => $result
		];
	}
}