<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller as BaseController;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

use App\Client\Users\User;

class HomeController extends BaseController {

	static function home(Request $request) {
		if ($request->cookie('token') == null) {
			return Inertia::render('Home');
		}
		$user = $request->cookie('token');
		if ($user == null) {
			return redirect()->route('home');
		}
		return redirect()->route('dashboard');
	}

	static function dashboard(Request $request) {
		return Inertia::render('Dashboard');
	}
}