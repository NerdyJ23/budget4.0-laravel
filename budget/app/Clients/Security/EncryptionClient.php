<?php
namespace App\Clients\Security;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class EncryptionClient {
	const METHOD = 'aes128';

	//2 way transformation for obscuring ID's
	static function encrypt($value) {
		return base64_encode(openssl_encrypt($value, self::METHOD, $_ENV['URI_KEY'], $options=0,  $_ENV['URI_KEY']));
	}

	//2 way transformation for obscuring ID's
	static function decrypt($value) {
		return openssl_decrypt(base64_decode($value), self::METHOD, $_ENV['URI_KEY'], $options=0, $_ENV['URI_KEY']);
	}

	//1 way hash for securely storing passwords
	static function hashPassword($pass) {
		return Hash::make($pass, [
			'rounds' => 12
		]);
	}

	static function checkPasswordSecurity($pass): bool {
		return Hash::needsRehash($pass);
	}
}