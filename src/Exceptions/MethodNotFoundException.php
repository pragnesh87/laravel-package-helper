<?php

namespace Pragnesh\LaravelPackageHelper\Exceptions;

use Exception;
use Pragnesh\LaravelPackageHelper\Enums\HttpStatus;

class MethodNotFoundException extends Exception
{
	protected $code;
	protected $message = "Called Method not found.";

	public function __construct()
	{
		$this->code = HttpStatus::Not_Found->value;
	}
}