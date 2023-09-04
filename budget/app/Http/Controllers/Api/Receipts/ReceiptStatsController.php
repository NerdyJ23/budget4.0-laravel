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
		$key = 'user-id-' . $user->id . ':costs:yearly:' . $year;
		if (count(Redis::hgetall($key)) > 0) {
			$result = [];
			foreach (Redis::hgetall($key) as $cost) {
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
		for ($i = 0; $i < count($result); $i++) {
			Redis::hset($key, $i+1, $result[$i]);
		}
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
		$key = 'user-id-' . $user->id . ':stats:stores:' . $year;
		if (count(Redis::hgetall($key)) > 0) {
			$result = [];
			foreach (Redis::hgetall($key) as $data) {
				$result[] = json_decode($data);
			}
			return [
				'result' => $result
			];
		}
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
			Redis::hset($key, $store, json_encode($obj));
		}
		return [
			'result' => $output
		];
	}
}