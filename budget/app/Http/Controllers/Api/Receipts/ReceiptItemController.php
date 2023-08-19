<?php

namespace App\Http\Controllers\Api\Receipts;

use App\Http\Controllers\Api\BaseApiController;
use App\Clients\Receipts\ReceiptClient;
use App\Clients\Receipts\ReceiptItemClient;
use App\Clients\Users\UserClient;

use Illuminate\Http\Request;

use App\Http\Schemas\Schema;

class ReceiptItemController extends BaseApiController {

	static function bulkDelete(Request $request, string $receiptId) {
		$user = UserClient::getByToken(token: $request->cookie('token'));
		$receipt = ReceiptClient::get(user: $user, id: $receiptId);

		if ($request->input('items') != null) {
			foreach (json_decode($request->input('items')) as $item) {
				$receiptItem = ReceiptItemClient::get(id: $item, receipt: $receipt, user: $user);
				if ($receiptItem != null) {
					ReceiptItemClient::delete(
						item: $receiptItem,
						user: $user,
						receipt: $receipt
					);
				}
			}
		}

		return parent::sendResponse(code: 204);
	}
}