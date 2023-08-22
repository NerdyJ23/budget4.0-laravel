<?php

namespace App\Http\Controllers\Api\Login;

use App\Http\Controllers\Controller;
use App\Http\Requests\Login\LoginPostRequest;
use App\Exceptions\Http\UnauthorizedException;
use Illuminate\Http\Response;
use DateTime;

use App\Clients\Users\UserClient;
use App\Clients\Tokens\UserTokenClient;

class LoginController extends Controller
{
	static function login(LoginPostRequest $request) {
		$user = UserClient::getByCredentials(
			username: $request->input('username'),
			password: $request->input('password')
		);

		if ($user == null) {
			throw new UnauthorizedException('Login details incorrect');
		}
		$token = UserTokenClient::createToken(user: $user);
		$user->last_logged_in = now();
		$user->save();
		$response = new Response(content: null, status: 200);
		return $response->withCookie(cookie('token', $token, (new DateTime('+ 7 days'))->getTimestamp()));
	}
    //
}
