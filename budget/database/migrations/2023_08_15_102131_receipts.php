<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
		Schema::create('Receipts', function (Blueprint $table) {
			$table->id('ID');
			$table->string('Name', 100)->nullable();
			$table->string('Location', 30)->nullable();
			$table->string('ReceiptNumber', 60)->nullable();
			$table->double('Cost')->default(0); //automatically calculated
			$table->date('Date');//receipt date
			$table->string('Category')->nullable(); //automatically calculated
			$table->foreignId('User')->constrained();
			$table->bit('Archive')->default(0);

			$table->timestamp('CreatedUTC');
			$table->timestamp('EditedUTC')->nullable();
		});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
