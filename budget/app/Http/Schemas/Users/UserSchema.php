<?php
namespace App\Http\Schemas\Users;

use App\Http\Schemas\Schema;
use App\Http\Schemas\SchemaInterface;

use App\Models\Users\User;
use App\Clients\Security\EncryptionClient;

class UserSchema extends Schema implements SchemaInterface {
	static function toExtendedSchema($item): mixed {
		$result = null;
		if ($item instanceof User) {
			$result = self::toSummarizedSchema($item);
			// $result += [

			// ];
		}
		return $item;
	}

	static function toSummarizedSchema($item): mixed {
		$result = null;
		if ($item instanceof User) {
			return [
				'id' => EncryptionClient::encrypt($item->id),
				'username' => $item->username,
				'firstName' => $item->first_name,
				'lastLoggedIn' => $item->last_logged_in
			];
		}
		return $result;
	}
}