<?php
namespace App\Http\Schemas;

interface SchemaInterface {
	static function toExtendedSchema($item);
	static function toSummarizedSchema($item);
}