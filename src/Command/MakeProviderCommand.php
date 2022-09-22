<?php

namespace Pragnesh\LaravelPackageHelper\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Pragnesh\LaravelPackageHelper\Command\BaseCommand;

#[AsCommand(name: 'make:provider', description: 'Create a new service provider class')]
class MakeProviderCommand extends BaseCommand
{
	protected string $type = 'provider';

	protected function configure(): void
	{
		$this
			->addArgument('name', InputArgument::REQUIRED, 'The name of the provider class');
	}

	protected function getStub()
	{
		return $this->resolveStubPath('provider.stub');
	}

	protected function updateStubContent()
	{
		$stub = $this->getStub();
		$namespace = $this->resolveNamespace();

		$replace = [
			'{{ class }}' => $this->getQualifyClassName(),
			'{{ namespace }}' => $namespace,
		];
		return strtr($stub, $replace);
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function handle()
	{
		if ($this->isReservedName($this->getNameInput())) {
			$this->output->error('The name "' . $this->getNameInput() . '" is reserved by PHP.');
			return false;
		}

		$path = $this->resolvePath();
		$stubTemplate = $this->updateStubContent();
		$file = $path . DIRECTORY_SEPARATOR . $this->getQualifyClassName() . '.php';

		$this->writeFile($file, $stubTemplate);

		return true;
	}
}