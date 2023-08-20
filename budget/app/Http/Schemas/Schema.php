<?php
namespace App\Http\Schemas;

class Schema
{
	static $schemas = [
		'Receipt' => 'App\Http\Schemas\Receipts\ReceiptSchema',
		'ReceiptItem' => 'App\Http\Schemas\Receipts\ReceiptItemSchema',
		'ReceiptDocument' => 'App\Http\Schemas\Receipts\ReceiptDocumentSchema',
		'ReceiptItemCategory' => 'App\Http\Schemas\Receipts\ReceiptItemCategorySchema',

		'User' => 'App\Http\Schemas\Users\UserSchema',
	];

	//@subschema: If the schema is called within another schema and needs to return a singleton non-array schema item
	static function schema(mixed $item, string $schema, bool $subschema = false): mixed {
		if (is_null($item)) {
			return null;
		}

		if (is_array($item)) {
			$result = [];
			foreach ($item as $i) {
				$result[] = Schema::$schemas[$schema]::toSummarizedSchema($i);
			}
			return $result;
		}
		$result = Schema::$schemas[$schema]::toExtendedSchema($item);
		//As per JSONAPI specs even a single item must be returned as an array https://jsonapi.org/format/ 7.1
		return $subschema ? $result : [$result];
	}
}
