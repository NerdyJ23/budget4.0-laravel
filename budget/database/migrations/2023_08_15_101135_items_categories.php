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
		Schema::create('ItemsCategories', function (Blueprint $table) {
			$table->id('ID');
			$table->foreignId('User_ID')->constrained();
			$table->string('Name', 255);
			$table->tinyInt('Archived')->default(0);
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
