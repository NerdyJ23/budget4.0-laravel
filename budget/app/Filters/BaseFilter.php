<?php
namespace App\Filters;

use App\Enums\Filters\StringFilterType;
class BaseFilter {

	protected function matchString(?StringFilterType $type, string $toFind, string $value): bool {
		switch ($type) {
			case StringFiltertype::MATCH_BEFORE:
				return preg_match("/.*" . $toFind . "/", $value) != false;
				break;

			case StringFilterType::MATCH_AFTER:
				return preg_match("/" . $toFind . ".*/", $value) != false;
				break;

			case StringFilterType::MATCH_PARTIAL:
				return preg_match("/.*" . $toFind . ".*/", $value) != false;
			//match exact

			case StringFilterType::MATCH_EXACT:
			default:
				return preg_match("/\b" . $toFind . "\b/", $value) != false;
				break;
		}
	}
}