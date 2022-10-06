<?php

namespace Pragnesh\LaravelPackageHelper\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Pragnesh\LaravelPackageHelper\Command\BaseCommand;

#[AsCommand(name: 'make:exception', description: 'Create a new custom exception class')]
class MakeExceptionCommand extends BaseCommand
{
	protected string $type = 'exception';

	protected function configure(): void
	{
		$this
			->addArgument('name', InputArgument::REQUIRED, 'The name of the exception class')
			->addOption('render', null, InputOption::VALUE_NONE, 'Create the exception with an empty render method')
			->addOption('report', null, InputOption::VALUE_NONE, 'Create the exception with an empty report method');
	}

	protected function getStub()
	{
		if ($this->option('render')) {
			return $this->option('report')
				? $this->resolveStubPath('exception-render-report.stub')
				: $this->resolveStubPath('exception-render.stub');
		}

		return $this->option('report')
			? $this->resolveStubPath('exception-report.stub')
			: $this->resolveStubPath('exception.stub');
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