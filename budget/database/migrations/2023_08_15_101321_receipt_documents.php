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
		Schema::create('ReceiptDocuments', function (Blueprint $table) {
			$table->id('ID');
			$table->foreignId('Receipt_ID')->constrained();
			$table->string('Name', 50);
			$table->foreignId('User_ID')->constrained();
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
