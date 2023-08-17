<?php

namespace App\Clients\Auth;

use App\Clients\BaseClient;
use App\Clients\Security\EncryptionClient;
use App\Models\Users\User;

class LoginClient extends BaseClient {

	static function validUser(string $username, string $password): bool {
		$hashedPassword = EncryptionClient::hashPassword($password);
		$dbPass = User::where([
			'username' => $username,
			'password' => $password
		])->first();
		return $dbPass != null;
	}
}