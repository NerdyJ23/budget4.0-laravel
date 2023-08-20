<?php
namespace App\Http\Schemas\Receipts;

use App\Http\Schemas\Schema;
use App\Http\Schemas\SchemaInterface;

use App\Models\Receipts\ReceiptDocument;
use App\Clients\Security\EncryptionClient;

class ReceiptDocumentSchema extends Schema implements SchemaInterface {
	static function toExtendedSchema($document): mixed {
		$result = null;
		if ($document instanceof ReceiptDocument) {
			$result = self::toSummarizedSchema($document);
		}
		return $result;
	}

	static function toSummarizedSchema($document): mixed {
		$result = null;
		if ($document instanceof ReceiptDocument) {
			return [
				'id' => EncryptionClient::encrypt($document->ID),
				'name' => $document->Name,
				'uploadedUtc' => $document->created_at
			];
		}
		return $result;
	}
}