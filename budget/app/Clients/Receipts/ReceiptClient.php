<?php
namespace App\Clients\Receipts;

use App\Models\Receipts\Receipt;
use App\Clients\BaseClient;

class ReceiptClient extends BaseClient {

	static function list(): mixed {
		return Receipt::where('User', 3)
		->get()->all();
	}
}