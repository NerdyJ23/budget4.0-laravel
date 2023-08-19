<?php
namespace App\Clients\Documents;

use App\Clients\BaseClient;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentClient extends BaseClient {

	private $disk = 'local';

	protected function create(
		string $path,
		mixed $file,
		?string $filename
	) {
		$uuid = str_random(30);
		if ($filename) {
			$uuid += '.' . pathinfo($filename, PATHINFO_EXTENSION);
		}

		Storage::disk($this->disk)->put($uuid, $file);
	}

	protected function delete(
		string $fullpath
	) {
		Storage::disk($this->disk)->delete($fullpath);
	}
}