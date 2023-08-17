<?php

namespace App\Models\Tokens;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Users\User;

class UserToken extends Model
{
	protected $table = 'UserLoginTokens';

	const CREATED_AT = 'token_created_at';
	public $timestamps = false;

	protected $fillable = [
		'user_id',
		'token',
		'token_valid_until',
		'token_created_at'
	];

	protected $casts = [
		'token_valid_until' => 'datetime',
		'token_created_at' => 'datetime'
	];

	public function user(): BelongsTo {
		return $this->belongsTo(User::class);
	}
}
