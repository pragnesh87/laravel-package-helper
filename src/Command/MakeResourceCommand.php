<?php

namespace Pragnesh\LaravelPackageHelper\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Pragnesh\LaravelPackageHelper\Command\BaseCommand;

#[AsCommand(name: 'make:resource', description: 'Create a new resource')]
class MakeResourceCommand extends BaseCommand
{
	protected string $type = 'resource';

	protected function configure(): void
	{
		$this
			->addArgument('name', InputArgument::REQUIRED, 'The name of the resource class')
			->addOption('collection', 'c', InputOption::VALUE_NONE, 'Create a resource collection');
	}

	protected function getStub()
	{
		return $this->collection()
			? $this->resolveStubPath('resource-collection.stub')
			: $this->resolveStubPath('resource.stub');
	}

	protected function updateStubContent()
	{
		$stub = $this->getStub();
		$namespace = $this->resolveNamespace();

		$replace = [
			'{{ class }}' => ucfirst($this->getNameInput()),
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

	protected function collection()
	{
		return $this->option('collection') ||
			str_ends_with($this->getNameInput(), 'Collection');
	}
}