<?php
namespace App\Clients;

use App\Clients\Security\EncryptionClient;

class BaseClient {
	static function encrypt(mixed $value) {
		return EncryptionClient::encrypt($value);
	}

	static function decrypt(mixed $value) {
		return EncryptionClient::decrypt($value);
	}
}