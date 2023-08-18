<?php
namespace App\Http\Schemas\Receipts;

use App\Http\Schemas\Schema;
use App\Http\Schemas\SchemaInterface;

use App\Models\Receipts\ReceiptItemCategory;
use App\Clients\Security\EncryptionClient;

class ReceiptItemCategorySchema extends Schema implements SchemaInterface {
	static function toExtendedSchema($category): mixed {
		$result = null;
		if ($category instanceof ReceiptItemCategory) {
			$result = self::toSummarizedSchema($category);
		}
		return $result;
	}

	static function toSummarizedSchema($category): mixed {
		$result = null;
		if ($category instanceof ReceiptItemCategory) {
			return [
				'id' => EncryptionClient::encrypt($category->ID),
				'name' => $category->Name,
				'archived' => $category->Archived
			];
		}
		return $result;
	}
}