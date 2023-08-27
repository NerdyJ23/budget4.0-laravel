<?php

namespace App\Http\Controllers\Web\Receipts;

use App\Http\Controllers\Controller as BaseController;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Response;
use App\Clients\Documents\DocumentClient;

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
		$fileModel = ReceiptDocumentClient::getModel(id: $docId, receipt: $receipt, user: $user);
		$filePath = ReceiptDocumentClient::getFullFilePath(receipt: $receipt, user: $user, file: $fileModel);

		if ($request->input('method') && $request->input('method') == '_blank') { //show in browser
			return response()->download($filePath, $fileModel->Name, [], 'inline');
		}
		return response()->download($filePath, $fileModel->Name);
	}

	static function graph(Request $request) {
		return;
	}
}