<?php

namespace App\Clients\Documents;

use App\Clients\Documents\DocumentClient;

use Illuminate\Http\UploadedFile;
use App\Models\Receipts\Receipt;
use App\Models\Users\User;
use App\Models\Receipts\ReceiptDocument;
class ReceiptDocumentClient extends DocumentClient {

	static function getFilePath(User $user, Receipt $receipt): string {
		return $user->id . '/' . $receipt->ID;
	}

	static function upload(User $user, Receipt $receipt, UploadedFile $file) {
		$client = new DocumentClient;
		$filename = $client->create(
			path: self::getFilePath(user: $user, receipt: $receipt),
			file: $file,
		);
		ReceiptDocument::create([
			'Name' => $file->getClientOriginalName(),
			'UUID' => $filename,
			'Receipt_ID' => $receipt->ID,
			'User_ID' => $user->id
		]);
	}

	static function getFile(string $receiptDocument, Receipt $receipt, User $user) {
		$client = new DocumentClient;
	}
}