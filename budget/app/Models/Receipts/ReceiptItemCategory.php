<?php

namespace App\Models\Receipts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\Users\User;
use App\Models\Receipts\ReceiptItem;

class ReceiptItemCategory extends Model
{
    use HasFactory;

	protected $table = 'ItemsCategories';
	protected $primaryKey = 'ID';

	public $timestamps = false;
	protected $fillable = [
		'User_ID',
		'Name',
		'Archived'
	];

	protected $casts = [
		'Archived' => 'boolean'
	];

	public function user(): BelongsTo {
		return $this->belongsTo(User::class);
	}

	public function receiptItem(): BelongsTo {
		return $this->belongsTo(ReceiptItem::class, 'ID', 'Category');
	}
}
