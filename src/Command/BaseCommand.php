<?php

namespace Pragnesh\LaravelPackageHelper\Command;

use Symfony\Component\Console\Command\Command;

class BaseCommand extends Command
{
	protected array $paths = [
		'controller' => 'src' . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'Controllers',
		'model' => 'src' . DIRECTORY_SEPARATOR . 'Models',
		'feature_test' => 'tests' . DIRECTORY_SEPARATOR . 'Feature',
		'unit_test' => 'tests' . DIRECTORY_SEPARATOR . 'Unit',
		'request' => 'src' . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'Requests',
		'factory' => 'aatabase' . DIRECTORY_SEPARATOR . 'Factories',
		'views' => 'resources' . DIRECTORY_SEPARATOR . 'views',
		'migration' => 'database' . DIRECTORY_SEPARATOR . 'Migrations',
	];

	protected array $namespace = [
		'controller' => 'VENDOR\PACKAGE\Http\Controllers',
		'model' => 'VENDOR\PACKAGE\App\Models',
		'feature_test' => 'VENDOR\PACKAGE\Tests\Feature',
		'unit_test' => 'VENDOR\PACKAGE\Tests\Unit',
		'request' => 'VENDOR\PACKAGE\Http\Requests',
		'factory' => 'VENDOR\PACKAGE\Database\Factories',
	];

	protected function getStub($type)
	{
		$type = strtolower($type);
		return file_get_contents(__DIR__ . "/stubs/$type");
	}
}