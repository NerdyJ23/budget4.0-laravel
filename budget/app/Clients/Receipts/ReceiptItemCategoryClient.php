<?php
namespace App\Clients\Receipts;

use App\Models\Reciepts\Receipt;
use App\Models\Receipts\ReceiptItem;
use App\Models\Receipts\ReceiptItemCategory;
use App\Models\Users\User;

use App\Clients\BaseClient;

use App\Exceptions\InputValidationException;

class ReceiptItemCategoryClient extends BaseClient {

	static function list(User $user): array {
		//auth check
		return ReceiptItemCategories::where(['User' => $user->id])->get()->all();
	}

	static function get(
		User $user,
		string $category
		// ?string $categoryId
	): ReceiptItemCategory|null {

		return ReceiptItemCategory::where([
			'User_ID' => $user->id,
			'Name' => $category
		])->first();
	}
	static function create(
		string $name,
		User $user
	): ReceiptItemCategory {
		if ($name == '' || $name == null) {
			throw new InputValidationException('Category name cannot be empty');
		}
		return ReceiptItemCategory::create([
			'Name' => $name,
			'User_ID' => $user->id
		]);
	}
}