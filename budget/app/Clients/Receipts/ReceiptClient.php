<?php
namespace App\Clients\Receipts;

//Models
use App\Models\Receipts\Receipt;
use App\Models\Users\User;

//Clients
use App\Clients\BaseClient;

//Exceptions
use App\Exceptions\Http\UnauthorizedException;

class ReceiptClient extends BaseClient {

	static function list(User $user): array {
		return Receipt::where('User', $user->id)->get()->all();
	}

	static function create(
		?string $name,
		?string $location,
		?string $reference,
		string $date,
		User $user
	): Receipt {
		return Receipt::create([
			'Name' => $name,
			'Location' => $location,
			'ReceiptNumber' => $reference,
			'Date' => $date,
			'User' => $user->id
		]);
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