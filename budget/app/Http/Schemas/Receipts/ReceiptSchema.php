<?php
namespace App\Http\Schemas\Receipts;

use App\Http\Schemas\Schema;
use App\Http\Schemas\SchemaInterface;

use App\Models\Receipts\Receipt;
use App\Clients\Security\EncryptionClient;

class ReceiptSchema extends Schema implements SchemaInterface {
	static function toExtendedSchema($receipt): mixed {
		$result = null;
		if ($receipt instanceof Receipt) {
			$result = self::toSummarizedSchema($receipt);

			//Add receipt items
			$result += [
			];
		}
		return $result;
	}

	static function toSummarizedSchema($receipt): mixed {
		$result = null;
		if ($receipt instanceof Receipt) {
			return [
				'id' => EncryptionClient::encrypt($receipt->ID),
				'date' => $receipt->Date,
				'store' => $receipt->Store,
				'location' => $receipt->Location,
				'reference' => $receipt->ReceiptNumber,
				'cost' => $receipt->Cost,
				'category' => $receipt->Category,
				'createdUtc' => $receipt->CreatedUTC,
				'editedUtc' => $receipt->EditedUTC,
				'items' => Schema::schema($receipt->receiptItem->all(), 'ReceiptItem'),
				'documents' => Schema::schema($receipt->receiptDocument->all(), 'ReceiptDocument')
			];
		}
		return $result;
	}
}