<?php
namespace App\Clients\Receipts;

use App\Clients\BaseClient;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;

use App\Models\Users\User;

class ReceiptRedisClient extends BaseClient {
	const KEYS = [
		'stats:costs:yearly',
		'stats:stores:yearly',
		'stats:categories:yearly'
	];

	static function breakCache(User $user) {
		self::breakStatsCache(user: $user);
	}

	static function breakStatsCache(User $user) {
		foreach (self::KEYS as $key) {
			Redis::expire('user-id-' . $user->id . ':' . $key, 0);
		}
	}
}