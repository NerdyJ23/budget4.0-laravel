<?php

namespace App\Http\Controllers\Web\Receipts;

use App\Http\Controllers\Controller as BaseController;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

use App\Client\Users\User;

class ReceiptController extends BaseController {

	static function list(Request $request) {
		return Inertia::render('Receipts');
	}

	static function graph(Request $request) {
		return;
	}
}