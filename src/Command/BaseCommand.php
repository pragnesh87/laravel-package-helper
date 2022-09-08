<?php

namespace Pragnesh\LaravelPackageHelper\Command;

use Symfony\Component\Filesystem\Filesystem;
use Pragnesh\LaravelPackageHelper\Helpers\Arr;
use Symfony\Component\Console\Command\Command;

class BaseCommand extends Command
{
	protected array $config = [];
	private string $package_namespace = '';

	protected function getStub($type)
	{
		$type = strtolower($type);
		return file_get_contents(__DIR__ . "/stubs/$type");
	}

	protected function isConfigExist()
	{
		$filesystem = new Filesystem();

		$file = "config/larapack.php";
		return $filesystem->exists($file);
	}

	protected function loadConfig()
	{
		$this->config = require_once("config/larapack.php");
	}

	protected function getConfig($config)
	{
		return Arr::get($this->config, $config);
	}

	protected function setPackageNamespace()
	{
		$this->package_namespace = $this->getConfig('package-namespace');
	}

	protected function getPackageNamespace()
	{
		return $this->package_namespace;
	}
}