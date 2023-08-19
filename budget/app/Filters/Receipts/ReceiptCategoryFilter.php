<?php
namespace App\Filters\Receipts;

use App\Filters\BaseFilter;
use Illuminate\Http\Request;
use App\Models\Receipts\ReceiptItemCategory;
use App\Enums\Filters\StringFilterType;

class ReceiptCategoryFilter extends BaseFilter {

	private ?bool $archived = null;

	private ?string $name = null;
	private ?StringFilterType $nameFilterType = null;

	function __construct(Request $request) {
		if ($request->query('archived') != null) {
			$this->archived = $request->query('archived');
		}

		if ($request->query('name') != null) {
			$this->name = strtoupper($request->query('name'));
		} else if ($request->query('name_like') != null) {
			$this->name = strtoupper($request->query('name_like'));
			$this->nameFilterType = StringFilterType::MATCH_PARTIAL;
		} else if ($request->query('name_endswith') != null) {
			$this->name = strtoupper($request->query('name_endswith'));
			$this->nameFilterType = StringFilterType::MATCH_BEFORE;
		} else if ($request->query('name_beginswith') != null) {
			$this->name = strtoupper($request->query('name_beginswith'));
			$this->nameFilterType = StringFilterType::MATCH_AFTER;
		} else if ($request->query('name_exact') != null) {
			$this->name = strtoupper($request->query('name_exact'));
			$this->nameFilterType = StringFilterType::MATCH_EXACT;
		}
	}

	public function setArchived(bool $archived) {
		$this->archived = $archived;
	}

	public function setNameFilter(string $name, ?StringFilterType $filterType) {
		$this->name = $name;
		$this->nameFilterType = $filterType;
	}

	//Filter out items that do not match the categories
	public function filter(ReceiptItemCategory $category): bool {
		if (!is_null($this->archived) && $this->archived != (bool) $category->Archived && $result) {
			return false;
		}

		if (!is_null($this->name) && !parent::matchString(type: $this->nameFilterType, toFind: $this->name, value: $category->Name)) {
			return false;
		}

		return true;
	}
}