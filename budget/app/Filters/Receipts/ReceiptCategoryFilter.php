<?php
namespace App\Filters\Receipts;

use App\Filters\BaseFilter;
use Illuminate\Http\Request;
use App\Models\Receipts\ReceiptItemCategory;
use App\Enums\Filters\StringFilterType;

use App\Exceptions\InputValidationException;

class ReceiptCategoryFilter extends BaseFilter {

	private ?bool $archived = null;

	private ?string $name = null;
	private ?StringFilterType $nameFilterType = null;

	function __construct(Request $request = null) {
		if ($request == null) {
			return $this;
		}

		if ($request->query('archived') != null) {
			$this->archived = $request->query('archived');
		}

		if ($request->query('name') != null) {
			$this->setNameFilter(name: $request->query('name'));
		} else if ($request->query('name_like') != null) {
			$this->setNameFilter(
				name: $request->query('name_like'),
				filtertype: StringtypeFilter::MATCH_PARTIAL
			);
		} else if ($request->query('name_endswith') != null) {
			$this->setNameFilter(
				name: $request->query('name_endswith'),
				filterType: StringFilterType::MATCH_BEFORE
			);
		} else if ($request->query('name_beginswith') != null) {
			$this->setNameFilter(
				name: $request->query('name_beginswith'),
				filterType: StringFilterType::MATCH_AFTER
			);
		} else if ($request->query('name_exact') != null) {
			$this->setNameFilter(
				name: $request->query('name_exact'),
				filterType: StringFilterType::MATCH_EXACT
			);
		}
	}

	public function setArchived(bool $archived) {
		$this->archived = $archived;
	}

	public function setNameFilter(string $name, ?StringFilterType $filterType = null) {
		if (trim($name) == '') {
			throw new InputValidationException('Receipt Category Name cannot be empty');
		}

		$this->name = trim(strtoupper($name));
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