<?php

namespace App\Models\Receipts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiptItem extends Model
{
    use HasFactory;

	protected $table = 'Items';

	protected $fillable = [
		'Receipt',
		'Name',
		'Count',
		'Cost',
		'Category'
	];

	protected $casts = [
		'Cost' => 'double'
	];
}
