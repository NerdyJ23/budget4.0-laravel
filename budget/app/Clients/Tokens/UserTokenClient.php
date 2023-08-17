<?php
namespace App\Clients\Tokens;

use App\Clients\BaseClient;
use App\Models\Users\User;
use App\Models\Tokens\UserToken;

class UserTokenClient extends BaseClient {
	const TOKEN_VALID_UNTIL_DAYS = 7;

	static function createToken(User $user): string {
		$token = UserToken::create([
			'token' => bin2hex(random_bytes(16)),
			'user_id' => $user->id,
			'token_created_at' => now(),
			'token_valid_until' => date('c', strtotime(date('c') . ' + ' . self::TOKEN_VALID_UNTIL_DAYS . 'days'))
		]);

		return $token->token;
	}
}