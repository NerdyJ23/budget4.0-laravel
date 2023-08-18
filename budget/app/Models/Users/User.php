<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;

use App\Models\Tokens\UserToken;

class User extends Authenticatable
{
    use HasFactory;
	protected $table = 'Users';
	public $timestamps = false;

    protected $fillable = [
        'username',
        'password',
		'first_name',
		'last_name',
		'token',
		'token_valid_until',
		'last_logged_in'
    ];

    protected $hidden = [
        'password',
    ];

	protected $casts = [
        'token_valid_until' => 'datetime',
		'last_logged_in' => 'datetime',
        'password' => 'hashed',
    ];

	public function token(): HasOne {
		return $this->hasOne(UserToken::class);
	}
}
