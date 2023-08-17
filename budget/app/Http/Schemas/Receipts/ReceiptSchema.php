<?php
namespace App\Http\Schemas\Receipts;

use App\Http\Schemas\Schema;
use App\Http\Schemas\SchemaInterface;

use App\Models\Receipts\Receipt;
use App\Clients\Security\EncryptionClient;

class ReceiptSchema extends Schema implements SchemaInterface {
	static function toExtendedSchema($item): mixed {
		$result = null;
		if ($item instanceof Receipt) {
			$result = self::toSummarizedSchema($item);
			// $result += [

			// ];
		}
		return $receipt;
	}

	static function toSummarizedSchema($item): mixed {
		$result = null;
		if ($item instanceof Receipt) {
			return [
				'id' => EncryptionClient::encrypt($item->ID),
				'store' => $item->Name,
				'location' => $item->Location
			];
		}
		return $receipt;
	}
}