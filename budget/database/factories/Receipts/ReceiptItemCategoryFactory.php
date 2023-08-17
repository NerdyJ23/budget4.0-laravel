<?php

namespace Database\Factories\Receipts;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Receipts\ReceiptItemCategory;
use App\Models\Users\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ReceiptItemCategoryFactory extends Factory
{

	protected $model = ReceiptItemCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
			'User_ID' => User::factory(),
			'Name' => fake()->firstName(),
			'Archived' => false
        ];
    }
}
