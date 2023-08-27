<?php

namespace App\Clients\Documents;

use App\Clients\Documents\DocumentClient;
use Illuminate\Http\UploadedFile;
use App\Models\Receipts\Receipt;
use App\Models\Users\User;
use App\Models\Receipts\ReceiptDocument;

use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Exceptions\Http\NotFoundException;

class ReceiptDocumentClient extends DocumentClient {

	static function getFilePath(User $user, Receipt $receipt): string {
		return $user->id . '/' . $receipt->ID;
	}

	static function listFiles(User $user, Receipt $receipt) {
		return ReceiptDocument::where([
			'Receipt_ID' => $receipt->ID,
			'User_ID' => $user->id
		])->get()->all();
	}

	static function upload(User $user, Receipt $receipt, UploadedFile $file) {
		$client = new DocumentClient;
		$filename = $client->create(
			path: self::getFilePath(user: $user, receipt: $receipt),
			file: $file,
		);
		ReceiptDocument::create([
			'Name' => $file->getClientOriginalName(),
			'UUID' => $filename,
			'Receipt_ID' => $receipt->ID,
			'User_ID' => $user->id
		]);
	}

	static function getModel(string $id, Receipt $receipt, User $user) {
		return ReceiptDocument::where([
			'ID' => parent::decrypt($id),
			'Receipt_ID' => $receipt->ID,
			'User_ID' => $user->id
		])->first();
	}
	static function getFile(string $id, Receipt $receipt, User $user) {
		$fileModel = self::getModel(id: $id, receipt: $receipt, user: $user);
		if ($fileModel == null) {
			throw new NotFoundException;
		}

		$path = self::getFilePath(user: $user, receipt: $receipt) . '/' . $fileModel->UUID;
		$client = new DocumentClient;
		return $client->get($path);
	}

	static function downloadFile(string $id, Receipt $receipt, User $user): StreamedResponse {
		$fileModel = self::getModel(id: $id, receipt: $receipt, user: $user);
		if ($fileModel == null) {
			throw new NotFoundException;
		}

		$path = self::getFilePath(user: $user, receipt: $receipt) . '/' . $fileModel->UUID;
		$client = new DocumentClient;
		return $client->download($path);
	}
	static function deleteFile(string $id, Receipt $receipt, User $user) {
		$fileModel = self::getModel(id: $id, receipt: $receipt, user: $user);
		if ($fileModel == null) {
			throw new NotFoundException;
		}

		$client = new DocumentClient;
		$path = self::getFilePath(user: $user, receipt: $receipt) . '/' . $fileModel->UUID;

		if ($client->delete($path)) {
			ReceiptDocument::destroy($fileModel->ID);
		}
		return;
	}
}