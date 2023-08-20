<?php

namespace App\Http\Controllers\Api\Receipts;

use App\Http\Controllers\Api\BaseApiController;
use App\Clients\Documents\ReceiptDocumentClient;
use App\Clients\Receipts\ReceiptClient;
use App\Clients\Users\UserClient;

use App\Http\Schemas\Schema;
use Illuminate\Http\Request;

use App\Models\Receipts\Receipt;

class ReceiptDocumentController extends BaseApiController {

	static function list(Request $request, string $receiptId) {
		$user = UserClient::getByToken($request->cookie('token'));
		$receipt = ReceiptClient::get(user: $user, id: $receiptId);
		$list = ReceiptDocumentClient::listFiles(user: $user, receipt: $receipt);
		return parent::sendResponse(Schema::schema($list, 'ReceiptDocument'));
	}

	static function upload(Request $request, string $receiptId) {
		$user = UserClient::getByToken($request->cookie('token'));
		$receipt = ReceiptClient::get(user: $user, id: $receiptId);
		$file = $request->file('file');
		ReceiptDocumentClient::upload(user: $user, receipt: $receipt, file: $file);
	}

	static function get(Request $request, string $receiptId, string $fileId) {
		$user = UserClient::getByToken($request->cookie('token'));
		$receipt = ReceiptClient::get(user: $user, id: $receiptId);
		$file = ReceiptDocumentClient::getFile(id: $fileId, receipt: $receipt, user: $user);
		return response()->download($file);
	}
}