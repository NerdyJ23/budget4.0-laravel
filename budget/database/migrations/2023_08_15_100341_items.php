<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	const TABLE = 'Items';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
		if (!Schema::hasTable(self::TABLE)) {
			Schema::create(self::TABLE, function (Blueprint $table) {
				$table->id('ID');
				$table->unsignedBigInteger('Receipt');
				$table->string('Name', 255);
				$table->double('Count');
				$table->double('Cost')->default(0);
				$table->unsignedBigInteger('Category');
			});
		}

		if (Schema::hasTable('Receipts')) {
			Schema::table(self::TABLE, function (Blueprint $table) {
				$table->foreign('Receipt')->references('ID')->on('Receipts');
			});
		}

		if (Schema::hasTable('ItemsCategories')) {
			Schema::table(self::TABLE, function (Blueprint $table) {
				$table->foreign('Category')->references('ID')->on('ItemsCategories');
			});
		}
        //
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
