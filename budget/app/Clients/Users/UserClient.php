<?php

namespace App\Clients\Users;

use App\Clients\BaseClient;
use App\Clients\Security\EncryptionClient;
use App\Models\Users\User;

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

	static function validate(): bool {
		return true;
	}
}