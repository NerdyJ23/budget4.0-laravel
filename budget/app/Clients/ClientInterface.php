<?php
namespace App\Clients;

interface ClientInterface {
	static function assertAccess(string $token): void;
}