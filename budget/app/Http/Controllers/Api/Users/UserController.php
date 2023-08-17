<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Api\BaseApiController;
use App\Clients\Users\UserClient;
use App\Http\Schemas\Schema;

use App\Http\Requests\Users\UserPostRequest;

class UserController extends BaseApiController {

	static function create(UserPostRequest $request) {

		$user = UserClient::create(
			username: $request->input('username'),
			password: $request->input('password'),
			first_name: $request->input('first_name'),
			last_name: $request->input('last_name')
		);

		return parent::sendResponse(code: $user == null ? 500 : 201);
		// return parent::sendResponse(Schema::schema($items, 'Receipt'));
	}
}