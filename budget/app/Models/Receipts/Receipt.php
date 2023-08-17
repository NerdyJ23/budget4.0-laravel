<?php

namespace App\Models\Receipts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Receipts\ReceiptItem;

class Receipt extends Model
{
    use HasFactory;

	protected $table = 'Receipts';
	protected $primaryKey = 'ID';

	const CREATED_AT = 'CreatedUTC';
	const UPDATED_AT = 'EditedUTC';

	protected $fillable = [
		'Name',
		'Location',
		'ReceiptNumber',
		'Cost',
		'Category',
		'User',
		'Archive',
		'CreatedUTC',
		'EditedUTC'
	];

	protected $casts = [
		'Cost' => 'double',
		'CreatedUTC', 'datetime',
		'EditedUTC', 'datetime'
	];
}
