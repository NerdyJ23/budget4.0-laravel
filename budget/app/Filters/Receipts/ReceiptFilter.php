<?php
namespace App\Filters\Receipts;

use App\Filters\BaseFilter;
use Illuminate\Http\Request;
use App\Models\Receipts\Receipt;
use App\Enums\Filters\StringFilterType;

use App\Exceptions\InputValidationException;

class ReceiptFilter extends BaseFilter {

	private ?string $store = null;
	private ?StringFilterType $storeFilterType = null;

	private ?string $location = null;
	private ?StringFilterType $locationFilterType = null;

	private ?string $reference = null;
	private ?StringFilterType $referenceFilterType = null;

	private ?int $month = null;
	private ?int $year = null;
	private ?int $cost = null;
	private ?bool $archived = null;

	function __construct(?Request $request) {
		if ($request == null) {
			return $this;
		}
		parent::setupFilter([
			'store' => 'string',
			'location' => 'string',
			'reference' => 'string',
			'month' => 'int',
			'year' => 'int',
			'cost' => 'int',
			'archived' => 'boolean'
		], $this, $request);
	}

	public function setMonth(int $month) {
		$this->month = $month;
	}

	public function setYear(int $year) {
		$this->year = $year;
	}

	public function setStore(string $store, ?StringFilterType $filterType = null) {
		if (trim($store) == '') {
			throw new InputValidationException('Receipt Name cannot be empty');
		}

		$this->store = trim($store);
		$this->storeFilterType = $filterType;
	}

	public function setLocation(string $loc, ?StringFilterType $filterType = null) {
		if (trim($loc) == '') {
			throw new InputValidationException('Receipt Location cannot be empty');
		}

		$this->location = trim($loc);
		$this->locationFilterType = $filterType;
	}

	public function setReference(string $reference, ?StringFilterType $filterType = null) {
		if (trim($reference) == '') {
			throw new InputValidationException('Receipt Reference cannot be empty');
		}

		$this->reference = trim($reference);
		$this->referenceFilterType = $filterType;
	}

	//Filter out items that do not match the categories
	public function filter(Receipt $receipt): bool {
		if (!is_null($this->month) && $this->month != date('n', strtotime($receipt->Date))) {
			return false;
		}

		if (!is_null($this->year) && $this->year != date('Y', strtotime($receipt->Date))) {
			return false;
		}

		if (!is_null($this->store) && !parent::matchString(type: $this->storeFilterType, toFind: $this->store, value: $receipt->Name)) {
			return false;
		}

		if (!is_null($this->location) && !parent::matchString(type: $this->locationFilterType, toFind: $this->location, value: $receipt->Location)) {
			return false;
		}

		if (!is_null($this->reference) && !parent::matchString(type: $this->referenceFilterType, toFind: $this->reference, value: $receipt->ReceiptNumber)) {
			return false;
		}
		return true;
	}
}