<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller as BaseController;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Clients\Users\UserClient;
use App\Client\Users\User;

class HomeController extends BaseController {

	static function home(Request $request) {
		$user = UserClient::getByToken($request->cookie('token'));
		if ($request->cookie('token') == null || $user == null) {
			return Inertia::render('Home');
		}

		return redirect()->route('dashboard');
	}

	static function dashboard(Request $request) {
		return Inertia::render('Dashboard');
	}
}