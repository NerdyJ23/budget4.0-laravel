<?php

namespace Database\Factories\Receipts;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Receipts\Receipt;
use App\Models\Receipts\ReceiptItem;
use App\Models\Receipts\ReceiptItemCategory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ReceiptItemFactory extends Factory
{
	protected $model = ReceiptItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
			'Receipt' => Receipt::factory(),
			'Name' => fake()->word(),
			'Count' => fake()->randomNumber(2),
			'Cost' => fake()->randomFloat(2),
			'Category' => ReceiptItemCategory::factory() //new category
        ];
    }
}
