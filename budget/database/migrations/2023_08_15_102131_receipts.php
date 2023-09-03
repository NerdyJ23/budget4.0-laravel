<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	const TABLE = 'Receipts';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
		if (!Schema::hasTable(self::TABLE)) {
			Schema::create(self::TABLE, function (Blueprint $table) {
				$table->id('ID');
				$table->string('Store', 100)->nullable();
				$table->string('Location', 30)->nullable();
				$table->string('ReceiptNumber', 60)->nullable();
				$table->double('Cost')->default(0); //automatically calculated
				$table->date('Date');//receipt date
				$table->string('Category')->nullable(); //automatically calculated
				$table->unsignedBigInteger('User');
				$table->boolean('Archive')->default(false);

				$table->timestamp('CreatedUTC');
				$table->timestamp('EditedUTC')->nullable();
			});
		}

		if (Schema::hasTable('Users')) {
			Schema::table(self::TABLE, function (Blueprint $table) {
				$table->foreign('User')->references('id')->on('Users');
			});
		}
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
