<?php
namespace App\Clients\Receipts;

use App\Models\Receipts\Receipt;
use App\Models\Receipts\ReceiptItem;
use App\Models\Receipts\ReceiptItemCategory;
use App\Models\Users\User;

use App\Clients\BaseClient;
use App\Clients\Receipts\ReceiptItemCategoryClient;

use App\Enums\Filters\StringFilterType;
use App\Filters\Receipts\ReceiptCategoryFilter;

class ReceiptItemClient extends BaseClient {

	static function list(Receipt $receipt, User $user): array {
		//auth check
		return ReceiptItem::where([
			'Receipt' => $receipt->ID
		])->get()->all();
	}

	static function create(
		Receipt $receipt,
		string $name,
		int $count,
		float $cost,
		string $category,
		User $user
	): ReceiptItem {
		$filter = new ReceiptCategoryFilter;
		$filter->setName(name: $category, filterType: StringFilterType::MATCH_EXACT);
		$categoryList = ReceiptItemCategoryClient::list(user: $user, filter: $filter);

		$categoryModel = sizeOf($categoryList) == 0 ? ReceiptItemCategoryClient::create(name: $category, user: $user) : reset($categoryList);

		return ReceiptItem::create([
			'Receipt' => $receipt->ID,
			'Name' => $name,
			'Count' => $count,
			'Cost' => $cost,
			'Category' => $categoryModel->ID
		])->refresh();
	}

	static function get(
		string $id,
		Receipt $receipt,
		User $user
	): ReceiptItem|null {
		return ReceiptItem::where([
			'Receipt' => $receipt->ID,
			'ID' => parent::decrypt($id)
		])->first();
	}

	static function update(
		ReceiptItem $item,
		User $user,
		?string $name,
		?int $count,
		?float $cost,
		?ReceiptItemCategory $category
	): ReceiptItem {
		$item->Name = $name ?? $item->Name;
		$item->Count = $count ?? $item->Count;
		$item->Cost = $cost ?? $item->Cost;
		$item->Category = $category?->ID ?? $item->Category;

		$item->save();
		return $item->refresh();
	}

	static function delete(
		ReceiptItem $item,
		User $user,
		Receipt $receipt
	) {
		//check permissions
		$item->delete();
		ReceiptClient::generateCostAndCategory($receipt);
	}
}