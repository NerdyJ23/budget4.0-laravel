<?php

namespace App\Http\Controllers\Api\Receipts;

use App\Http\Controllers\Api\BaseApiController;
use App\Clients\Receipts\ReceiptClient;
use App\Http\Schemas\Schema;

use Illuminate\Http\Request;

use App\Exceptions\Http\UnauthorizedException;

class ReceiptController extends BaseApiController {

	static function list(Request $request) {
		if ($request->cookie('token') == null) {
			throw new UnauthorizedException('User not logged in');
		}
		$items = ReceiptClient::list(token: $request->cookie('token'));
		return parent::sendResponse(Schema::schema($items, 'Receipt'));
	}

	static function get(Request $request, string $id): mixed {
		return null;
	}
}