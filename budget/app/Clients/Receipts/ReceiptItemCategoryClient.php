<?php
namespace App\Clients\Receipts;

use App\Models\Reciepts\Receipt;
use App\Models\Receipts\ReceiptItem;
use App\Models\Receipts\ReceiptItemCategory;
use App\Models\Users\User;

use App\Clients\BaseClient;

use App\Exceptions\InputValidationException;
use App\Exceptions\Http\ConflictException;
use App\Exceptions\Http\NotFoundException;

use App\Filters\Receipts\ReceiptCategoryFilter;
class ReceiptItemCategoryClient extends BaseClient {

	static function list(User $user, ReceiptCategoryFilter $filter): array {
		$categories = ReceiptItemCategory::where(['User_ID' => $user->id])->get();

		return $categories->filter(function (ReceiptItemCategory $category) use ($filter) {
			return $filter->filter($category);
		})->all();
	}

	static function get(
		User $user,
		string $category
	): ReceiptItemCategory|null {

		$categoryModel = ReceiptItemCategory::where([
			'User_ID' => $user->id,
			'Name' => strtoupper($category)
		])->first();

		if ($categoryModel == null) {
			throw new NotFoundException;
		}

		return $categoryModel;
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

	static function update(
		User $user,
		string $category,
		?string $name,
		?string $archived
	): ReceiptItemCategory {
		$categoryModel = self::get(user: $user, category: $category);

		if (!is_null($name)) {
			$categoryModel->Name = trim(strtoupper($name));
		}

		if (!is_null($archived)) {
			$categoryModel->Archived = $archived;
		}

		if ($categoryModel->isDirty()) {
			$categoryModel->save();
		}

		return $categoryModel->refresh();
	}

	static function archive(User $user, string $category): bool {
		$category = self::get(user: $user, category: $category);
		$category->Archived = true;
		return $category->save();
	}
}