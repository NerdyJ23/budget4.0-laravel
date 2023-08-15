<?php

namespace App\Models\Receipts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;

	protected $table = 'Receipts';

	protected $fillable = [
		'Name',
		'Location',
		'ReceiptNumber',
		'Count',
		'Cost',
		'Category'
	];

	protected $casts = [
		'Cost' => 'double'
	];
}
