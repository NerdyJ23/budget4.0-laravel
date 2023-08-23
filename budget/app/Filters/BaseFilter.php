<?php
namespace App\Filters;

use App\Enums\Filters\StringFilterType;
use Illuminate\Http\Request;

class BaseFilter {
	protected function setupFilter(array $keys, BaseFilter $filter, Request $request) {
		foreach ($keys as $key => $type) {
			$func = 'set' . $key;
			if ($request->query($key) != null) {
				$filter->$func($request->query($key));
			}

			if ($type == 'string') {
				if ($request->query($key . '_like') != null) {
					$filter->$func($request->query ($key . '_like'), StringFilterType::MATCH_PARTIAL);
				} else if ($request->query($key . '_endswith') != null) {
					$filter->$func($request->query($key . '_endswith'), StringFilterType::MATCH_BEFORE);
				} else if ($request->query($key . '_beginswith') != null) {
					$filter->$func($request->query($key . '_beginswith'), StringFilterType::MATCH_AFTER);
				} else if ($request->query($key . '_exact') != null) {
					$filter->$func($request->query($key . '_exact'), StringFilter::MATCH_EXACT);
				}
			}
		}
	}

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