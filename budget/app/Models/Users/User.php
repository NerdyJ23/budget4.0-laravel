<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;
	protected $table = 'Users';

    protected $fillable = [
        'id',
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
}
