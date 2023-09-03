<?php
namespace App\Clients\Receipts;

//AccessCheck
use Illuminate\Support\Facades\Gate;

//Models
use App\Models\Receipts\Receipt;
use App\Models\Users\User;

//Clients
use App\Clients\BaseClient;

//Exceptions
use App\Exceptions\Http\UnauthorizedException;

//Filters
use App\Filters\Receipts\ReceiptFilter;

class ReceiptStatsClient extends BaseClient {
	static function getYearlyReceiptCosts(User $user, int $year) {
		$costs = [];
		for ($i = 1; $i <= 12; $i++) {
			$costs[] = Receipt::where('User', $user->id)
			->whereMonth('Date', $i)
			->whereYear('Date', $year)
			->sum('Cost');
		}
		return $costs;
	}
}