<?php

namespace App\Models\Receipts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Model;
use App\Models\Receipts\ReceiptItem;
use App\Models\Receipts\ReceiptDocument;
use App\Models\Users\User;

class Receipt extends Model
{
    use HasFactory;

	protected $table = 'Receipts';
	protected $primaryKey = 'ID';

	const CREATED_AT = 'CreatedUTC';
	const UPDATED_AT = 'EditedUTC';

	protected $fillable = [
		'Store',
		'Location',
		'ReceiptNumber',
		'Date',
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

	public function receiptItem(): HasMany {
		return $this->hasMany(ReceiptItem::class, 'Receipt', 'ID');
	}

	public function receiptDocument(): HasMany {
		return $this->hasMany(ReceiptDocument::class, 'Receipt_ID', 'ID');
	}

	public function user(): BelongsTo {
		return $this->belongsTo(User::class);
	}
}
