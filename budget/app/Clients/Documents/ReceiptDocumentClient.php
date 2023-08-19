<?php

namespace App\Clients\Documents;

use App\Clients\DocumentClient;
use App\Models\Receipts\Receipt;
use App\Models\Users\User;

class ReceiptDocumentClient extends DocumentClient {

	static function getFilePath(User $user, Receipt $receipt): string {
		return $user->ID . '/' . $receipt->ID;
	}
}