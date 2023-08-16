<?php

namespace App\Http\Controllers\Api\Receipts;

use App\Http\Controllers\Api\BaseApiController;
use App\Clients\Receipts\ReceiptItemClient;

use Illuminate\Http\Request;

class ReceiptController extends ApiController {

	public function list() {
		$items = ReceiptItemClient::list(1);
		print_r($items);
	}
}