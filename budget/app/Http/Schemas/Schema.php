<?php
namespace App\Http\Schemas;

class Schema
{
	static $schemas = [
		'Receipt' => 'App\Http\Schemas\Receipts\ReceiptSchema',
		'ReceiptItem' => 'App\Http\Schemas\Receipts\ReceiptItemSchema',
		'ReceiptItemCategory' => 'App\Http\Schemas\Receipts\ReceiptItemCategorySchema',

		'User' => 'App\Http\Schemas\Users\UserSchema',
	];

	static function schema(mixed $item, string $schema): mixed {
		if (is_array($item)) {
			$result = [];
			foreach ($item as $i) {
				$result[] = Schema::$schemas[$schema]::toSummarizedSchema($i);
			}
			return $result;
		}
		//As per JSONAPI specs even a single item must be returned as an array https://jsonapi.org/format/ 7.1
		return is_null($item) ? null : [Schema::$schemas[$schema]::toExtendedSchema($item)];
	}
}
