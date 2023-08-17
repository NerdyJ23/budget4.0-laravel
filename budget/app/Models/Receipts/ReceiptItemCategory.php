<?php

namespace App\Models\Receipts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

use App\Models\Users\User;
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

	public function user(): HasOne {
		return $this->hasOne(User::class);
	}

	public function receiptItem(): HasMany {
		return $this->hasMany(ReceiptItem::class, 'ID', 'Category');
	}
}
