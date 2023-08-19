<?php

namespace App\Models\Receipts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiptDocuments extends Model
{
    use HasFactory;

	protected $table = 'ReceiptDocuments';

	protected $fillable = [
		'Receipt_ID',
		'Name', //User visible name of file
		'UUID', //UUID file name on server
		'User_ID',
	];
}
