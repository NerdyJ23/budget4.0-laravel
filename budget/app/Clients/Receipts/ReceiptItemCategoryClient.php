<?php
namespace App\Clients\Receipts;

use App\Models\Reciepts\Receipt;
use App\Models\Receipts\ReceiptItem;
use App\Models\Receipts\ReceiptItemCategory;
use App\Models\Users\User;

use App\Clients\BaseClient;

use App\Exceptions\InputValidationException;
use App\Exceptions\Http\ConflictException;

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
		$name = trim(strtoupper($name));
		if ($name == '' || $name == null) {
			throw new InputValidationException('Category name cannot be empty');
		}

		if (self::get(user: $user, category: $name) != null) {
			throw new ConflictException("Category '$name' already exists");
		}

		return ReceiptItemCategory::create([
			'Name' => $name,
			'User_ID' => $user->id
		])->refresh();
		//Create methods need to refresh afterwards, the model only knows what we put in, not what happened in the db
	}
}