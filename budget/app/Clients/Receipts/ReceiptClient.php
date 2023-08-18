<?php
namespace App\Clients\Receipts;

//Models
use App\Models\Receipts\Receipt;

//Clients
use App\Clients\BaseClient;
use App\Clients\Users\UserClient;

//Exceptions
use App\Exceptions\Http\UnauthorizedException;

class ReceiptClient extends BaseClient {

	static function list(string $token): array {
		$userModel = UserClient::getByToken(token: $token);
		if ($userModel == null) {
			throw new UnauthorizedException('User not logged in');
		}

		return Receipt::where('User', $userModel->id)->get()->all();
	}

	static function get(string $token, string $receiptId): mixed {
		$receiptModel = Receipt::where('ID', parent::decrypt($receipt))->first();
		$userModel = UserClient::getByToken(token: $token);

		if ($receiptModel == null) {
			// throw new UnauthorizedException;
		}

		return Receipt::where('User', 3)
		->get()->all();
	}
}