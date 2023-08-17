<?php

namespace App\Clients\Users;

use App\Clients\BaseClient;
use App\Clients\Security\EncryptionClient;
use App\Models\Users\User;
use App\Models\Tokens\UserToken;
use Illuminate\Support\Facades\Hash;

use App\Exceptions\InputValidationException;

class UserClient extends BaseClient {

	static function create(
		string $username,
		string $password,
		string $first_name,
		string $last_name = null
	): User {

		if (User::where(['username' => $username])->first() != null) {
			throw new InputValidationException('User already exists');
		}

		return User::create([
			'username' => $username,
			'password' => EncryptionClient::hashPassword($password),
			'first_name' => $first_name,
			'last_name' => $last_name
		]);
	}

	static function getByToken(string $token): mixed {
		return UserToken::where(['token' => $token])->first()?->user();
	}

	static function getByCredentials(string $username, string $password): mixed {
		$user = User::where(['username' => $username])->first();
		return Hash::check($password, $user->password) ? $user : null;
	}
}