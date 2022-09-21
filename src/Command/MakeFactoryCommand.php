<?php

namespace Pragnesh\LaravelPackageHelper\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Pragnesh\LaravelPackageHelper\Command\BaseCommand;

#[AsCommand(name: 'make:factory', description: 'Create a new model factory')]
class MakeFactoryCommand extends BaseCommand
{
	protected string $type = 'factory';

	protected function configure(): void
	{
		$this
			->addArgument('name', InputArgument::REQUIRED, 'The name of the model class')
			->addOption('model', 'm', InputOption::VALUE_OPTIONAL, 'The name of the model');
	}

	protected function getStub()
	{
		return $this->resolveStubPath('factory.stub');
	}

	protected function updateStubContent()
	{
		$stub = $this->getStub();
		$namespace = $this->resolveNamespace();

		$replace = [
			'{{ class }}' => $this->getQualifyClassName(),
			'{{ namespace }}' => $namespace,
			'{{ namespacedModel }}' => $this->getModelNamespace(),
		];
		return strtr($stub, $replace);
	}

	protected function getModelNamespace()
	{
		return $this->getPackageNamespace() . '\\' . $this->getConfig('namespace.model');
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