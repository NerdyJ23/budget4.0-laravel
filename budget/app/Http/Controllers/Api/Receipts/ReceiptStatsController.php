<?php

namespace App\Http\Controllers\Api\Receipts;

use App\Http\Controllers\Api\BaseApiController;
use App\Clients\Users\UserClient;
use App\Clients\Receipts\ReceiptClient;

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
				$result[] = floatval($cost);
			}
			return [
				'result' => $result
			];
		}

		$result = ReceiptClient::getYearlyReceiptCosts(
			user: $user,
			year: $year
		);
		for ($i = 0; $i < count($result); $i++) {
			Redis::hset($key, $i+1, $result[$i]);
		}
		Redis::expire($key, 86400);
		return [
			'result' => $result
		];
	}

	static function getFavouriteStores(Request $request): array {
		$user = UserClient::getByToken($request->cookie('token'));
		$year = $request->query('year') ?? date('Y');
	}
}