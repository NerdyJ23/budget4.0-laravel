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
		//need to add filter by name
		return $categories->filter(function (ReceiptItemCategory $category) use ($filter) {
			return $filter->filter($category);
		})->all();
	}

	static function get(
		User $user,
		string $id
	): ReceiptItemCategory|null {

		$categoryModel = ReceiptItemCategory::where([
			'User_ID' => $user->id,
			'ID' => parent::decrypt($id)
			// 'Name' => strtoupper($category)
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

		$filter = new ReceiptCategoryFilter();
		$filter->setName($name);
		$categoryList = self::list(user: $user, filter: $filter);
		$categoryExists = sizeOf($categoryList) != 0;
		if ($categoryExists) {
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
		ReceiptItemCategory $category,
		?string $name,
		?string $archived
	): ReceiptItemCategory {

		$category->Name = $name ?? $category->Name;
		$category->Archived = is_null($archived) ? $category->Archived : (bool) $archived;

		$category->Name = trim(strtoupper($category->Name));
		$category->save();
		return $category->refresh();
	}

	static function archive(User $user, string $id): bool {
		$category = self::get(user: $user, id: $id);
		$category->Archived = true;
		return $category->save();
	}
}