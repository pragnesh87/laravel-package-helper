<?php

namespace Pragnesh\LaravelPackageHelper\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Pragnesh\LaravelPackageHelper\Command\BaseCommand;

#[AsCommand(name: 'make:cast', description: 'Create a new custom Eloquent cast class')]
class MakeCastCommand extends BaseCommand
{
	protected string $type = 'cast';

	protected function configure(): void
	{
		$this
			->addArgument('name', InputArgument::REQUIRED, 'The name of the cast class')
			->addOption('inbound', null, InputOption::VALUE_OPTIONAL, 'Generate an inbound cast class');
	}

	protected function getStub()
	{
		return $this->option('inbound')
			? $this->resolveStubPath('cast.inbound.stub')
			: $this->resolveStubPath('cast.stub');
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
		$file = $path . DIRECTORY_SEPARATOR . $this->getNameInput() . '.php';

		$this->writeFile($file, $stubTemplate);

		return true;
	}
}