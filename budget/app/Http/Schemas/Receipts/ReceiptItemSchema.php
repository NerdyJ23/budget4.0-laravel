<?php
namespace App\Http\Schemas\Receipts;

use App\Http\Schemas\Schema;
use App\Http\Schemas\SchemaInterface;

use App\Models\Receipts\ReceiptItem;
use App\Clients\Security\EncryptionClient;

class ReceiptItemSchema extends Schema implements SchemaInterface {
	static function toExtendedSchema($receiptItem): mixed {
		$result = null;
		if ($receiptItem instanceof ReceiptItem) {
			$result = self::toSummarizedSchema($receiptItem);
		}
		return $result;
	}

	static function toSummarizedSchema($receiptItem): mixed {
		$result = null;
		if ($receiptItem instanceof ReceiptItem) {
			return [
				'id' => EncryptionClient::encrypt($receiptItem->ID),
				'name' => $receiptItem->Name,
				'count' => $receiptItem->Count,
				'cost' => $receiptItem->Cost,
				'category' => Schema::schema($receiptItem->receiptItemCategory, 'ReceiptItemCategory', true)
			];
		}
		return $result;
	}
}