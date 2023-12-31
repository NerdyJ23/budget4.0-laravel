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
		$result = ReceiptDocumentClient::upload(user: $user, receipt: $receipt, file: $file);
		return parent::sendResponse(code: 201, body: Schema::schema($result, 'ReceiptDocument'));
	}

	static function get(Request $request, string $receiptId, string $id) {
		$user = UserClient::getByToken($request->cookie('token'));
		$receipt = ReceiptClient::get(user: $user, id: $receiptId);
		return ReceiptDocumentClient::getFile(id: $id, receipt: $receipt, user: $user);
	}

	static function delete(Request $request, string $receiptId, string $id) {
		$user = UserClient::getByToken($request->cookie('token'));
		$receipt = ReceiptClient::get(user: $user, id: $receiptId);

		ReceiptDocumentClient::deleteFile(id: $id, receipt: $receipt, user: $user);
		return parent::sendResponse(code: 204);
	}
}