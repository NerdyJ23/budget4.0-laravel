<?php
namespace App\Clients\Documents;

use App\Clients\BaseClient;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class DocumentClient extends BaseClient {

	private $disk = 'local';

	protected function create(string $path, UploadedFile $file): string {
		$name = Storage::disk($this->disk)->put($path, $file);
		return basename($name);
	}

	protected function get(string $path) {
		return Storage::disk($this->disk)->get($path);
	}

	protected function download(string $path) {
		return Storage::disk($this->disk)->download($path);
	}

	protected function delete(string $fullpath) {
		return Storage::disk($this->disk)->delete($fullpath);
	}

	protected function path(string $filename) {
		return Storage::disk($this->disk)->path($filename);
	}
}