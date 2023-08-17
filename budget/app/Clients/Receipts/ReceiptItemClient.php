<?php
namespace App\Clients\Receipts;

use App\Models\Receipts\ReceiptItem;
use App\Clients\BaseClient;

class ReceiptItemClient extends BaseClient {

	static function list(int $receipt_id) {
		//auth check
		return ReceiptItem::where('Receipt', $receipt_id)
		->first();
	}
}