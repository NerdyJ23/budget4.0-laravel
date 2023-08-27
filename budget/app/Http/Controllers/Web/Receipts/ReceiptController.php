<?php

namespace App\Http\Controllers\Web\Receipts;

use App\Http\Controllers\Controller as BaseController;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Http\Schemas\Schema;

use App\Clients\Receipts\ReceiptClient;
use App\Clients\Users\UserClient;
use App\Clients\Documents\ReceiptDocumentClient;

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

	static function getDocument(Request $request, string $receiptId, string $docId) {
		$user = UserClient::getByToken($request->cookie('token'));
		$receipt = ReceiptClient::get(user: $user, id: $receiptId);
		$file = ReceiptDocumentClient::getFile(id: $docId, receipt: $receipt, user: $user);
		if ($request->input('method') && $request->input('method') == '_blank') { //show in browser
			return parent::sendResponse($file);
		}
		return $file;
	}

	static function graph(Request $request) {
		return;
	}
}