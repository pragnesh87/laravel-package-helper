<?php

namespace Pragnesh\LaravelPackageHelper\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Pragnesh\LaravelPackageHelper\Command\BaseCommand;

// the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'config:install', description: 'Generate config/larapack.php file in root directory')]
class CreateConfigCommand extends BaseCommand
{
	protected string $type = 'config';

	protected function configure(): void
	{
		$this
			->setHelp('This command generate config/larapack.php file in root directory');
	}

	protected function getStub()
	{
		return $this->resolveStubPath('config.stub');
	}

	public function handle()
	{
		$stubTemplate = $this->getStub();

		$file = "config/larapack.php";
		$this->writeFile($file, $stubTemplate);

		return true;
	}
}