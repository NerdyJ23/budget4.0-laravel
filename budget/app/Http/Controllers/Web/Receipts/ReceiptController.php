<?php

namespace App\Http\Controllers\Web\Receipts;

use App\Http\Controllers\Controller as BaseController;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Http\Schemas\Schema;

use App\Clients\Receipts\ReceiptClient;
use App\Clients\Users\UserClient;

use App\Filters\Receipts\ReceiptFilter;
class ReceiptController extends BaseController {

	static function list(Request $request) {
		$filter = new ReceiptFilter($request);

		if ($request->input('month') == null) {
			$filter->setMonth(date('n', time()));
		}

		if ($request->input('year') == null) {
			$filter->setYear(date('Y', time()));
		}

		$user = UserClient::getByToken($request->cookie('token'));
		return Inertia::render('Receipts/ReceiptList', [
			'receipts' => Schema::schema(ReceiptClient::list(user: $user, filter: $filter), 'Receipt')
		]);
	}

	static function graph(Request $request) {
		return;
	}
}