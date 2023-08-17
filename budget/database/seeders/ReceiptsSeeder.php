<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Receipts\Receipt;
use App\Models\Receipts\ReceiptItem;
use App\Models\Users\User;

class ReceiptsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
		// $user = User::factory()->create();

		// $receipt = Receipt::factory()
		// ->create();

		ReceiptItem::factory()->count(5)
		->has(Receipt::factory(), 'Receipt')
		->recycle(User::factory())
		->create();

    }
}
