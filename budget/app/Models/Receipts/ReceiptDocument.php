<?php

namespace App\Models\Receipts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Receipts\Receipt;
class ReceiptDocument extends Model
{
    use HasFactory;

	protected $table = 'ReceiptDocuments';
	protected $primaryKey = 'ID';

	protected $fillable = [
		'Receipt_ID',
		'Name', //User visible name of file
		'UUID', //UUID file name on server
		'User_ID',
	];

	public function receipt(): BelongsTo {
		return $this->belongsTo(Receipt::class);
	}
}
