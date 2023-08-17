<?php

namespace App\Http\Controllers\Api\Receipts;

use App\Http\Controllers\Api\BaseApiController;
use App\Clients\Receipts\ReceiptClient;
use App\Http\Schemas\Schema;

use Illuminate\Http\Request;

class ReceiptController extends BaseApiController {

	static function list() {
		$items = ReceiptClient::list();
		return parent::sendResponse(Schema::schema($items, 'Receipt'));
	}
}