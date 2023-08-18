<?php
namespace App\Filters\Receipts;

use App\Filters\BaseFilter;
use Illuminate\Http\Request;
use App\Models\Receipts\ReceiptItemCategory;

class ReceiptCategoryFilter extends BaseFilter {

	private ?bool $archived = null;

	function __construct(Request $request) {
		if ($request->query('archived') != null) {
			$this->setArchived($request->query('archived'));
		}
	}

	public function setArchived(bool $archived) {
		$this->archived = $archived;
	}

	//Filter out items that do not match the categories
	public function filter(ReceiptItemCategory $category): bool {
		if (!is_null($this->archived) && $this->archived != (bool) $category->Archived) {
			return false;
		}

		return true;
	}
}