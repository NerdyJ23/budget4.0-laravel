<?php
namespace App\Exceptions;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Throwable, Exception;

class InputValidationException extends Exception {

	public function __construct($message = '', $code = 400, Throwable $previous = null) {
		parent::__construct($message, $code, $previous);
	}

	public function __toString() {
		return __CLASS__ . ": [{$this->code}]: {$this->message} at " . __LINE__ . "\n";
	}

	public function render(Request $request): JsonResponse {
		return response()->json([
			'message' => $this->getMessage()
		], $this->getCode());
	}
}