<?php

namespace App\Models\Receipts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

use App\Models\Receipts\Receipt;
use App\Models\Receipts\ReceiptItemCategory;

class ReceiptItem extends Model
{
    use HasFactory;

	protected $table = 'Items';
	protected $primaryKey = 'ID';
	public $timestamps = false;

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

	public function receipt(): BelongsTo {
		return $this->belongsTo(Receipt::class, 'ID', 'Receipt');
	}

	public function receiptItemCategory(): HasOne {
		return $this->hasOne(ReceiptItemCategory::class, 'ID', 'Category');
	}
}
