<?php

namespace Pragnesh\LaravelPackageHelper\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Pragnesh\LaravelPackageHelper\Command\BaseCommand;

#[AsCommand(name: 'make:test', description: 'Create a new test class')]
class MakeTestCommand extends BaseCommand
{
	protected string $type = 'test';

	protected function configure(): void
	{
		$this
			->addArgument('name', InputArgument::REQUIRED, 'The name of the test class')
			->addOption('unit', 'u', InputOption::VALUE_NONE, 'Create a unit test')
			->addOption('pest', 'p', InputOption::VALUE_NONE, 'Create a Pest test');
	}

	protected function getStub()
	{
		$suffix = $this->option('unit') ? '.unit.stub' : '.stub';

		return $this->option('pest')
			? $this->resolveStubPath('pest' . $suffix)
			: $this->resolveStubPath('test' . $suffix);
	}

	protected function updateStubContent()
	{
		$stub = $this->getStub();
		$rootNamespace = $this->resolveNamespace();

		if ($this->option('unit')) {
			$namespace = $rootNamespace . '\Unit';
		} else {
			$namespace = $rootNamespace . '\Feature';
		}

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
		if ($this->option('unit')) {
			$path = $path . DIRECTORY_SEPARATOR . 'Unit';
		} else {
			$path = $path . DIRECTORY_SEPARATOR . 'Feature';
		}
		$stubTemplate = $this->updateStubContent();
		$file = $path . DIRECTORY_SEPARATOR . $this->getNameInput() . '.php';

		$this->writeFile($file, $stubTemplate);

		return true;
	}
}