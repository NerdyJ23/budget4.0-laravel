<?php
namespace App\Clients\Receipts;

use App\Models\Receipts\Receipt;
use App\Models\Receipts\ReceiptItem;
use App\Models\Users\User;

use App\Clients\BaseClient;
use App\Clients\Receipts\ReceiptItemCategoryClient;

class ReceiptItemClient extends BaseClient {

	static function list(Receipt $receipt, User $user): array {
		//auth check
		return ReceiptItem::where([
			'Receipt' => $receipt->ID,
			'User' => $user->id
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
		$categoryModel = ReceiptItemCategoryClient::get(user: $user, category: $category);

		if ($categoryModel == null) {
			$categoryModel = ReceiptItemCategoryClient::create(name: $category, user: $user);
		}

		return ReceiptItem::create([
			'Receipt' => $receipt->ID,
			'Name' => $name,
			'Count' => $count,
			'Cost' => $cost,
			'Category' => $categoryModel->ID
		])->refresh();
	}
}