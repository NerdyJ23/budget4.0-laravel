<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller as Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Response;
use App\Exceptions\Types\InputValidationException;

class BaseApiController extends Controller {
	use AuthorizesRequests;
}