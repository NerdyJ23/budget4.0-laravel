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
		//Deprecated
		return;
		Schema::create('Items', function (Blueprint $table) {
			$table->id('ID');
			$table->foreignId('Receipt')->constrained();
			$table->string('Name', 255);
			$table->double('Count');
			$table->double('Cost')->default(0);
			$table->foreignId('Category')->constrained();
		});
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
