<?php

namespace Pragnesh\LaravelPackageHelper\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Pragnesh\LaravelPackageHelper\Command\BaseCommand;

// the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'make:controller', description: 'Create a new controller class')]
class MakeControllerCommand extends BaseCommand
{
	protected string $type = 'controller';

	protected function configure(): void
	{
		$this
			->addArgument('name', InputArgument::REQUIRED, 'The name of the controller class')
			->addOption('api', null, InputOption::VALUE_NONE, 'Exclude the create and edit methods from the controller.')
			->addOption('type', null, InputOption::VALUE_REQUIRED, 'Manually specify the controller stub file to use.')
			->addOption('invokable', 'i', InputOption::VALUE_NONE, 'Generate a single method, invokable controller class.')
			->addOption('model', 'm', InputOption::VALUE_OPTIONAL, 'Generate a resource controller for the given model.')
			->addOption('parent', 'p', InputOption::VALUE_OPTIONAL, 'Generate a nested resource controller class.')
			->addOption('resource', 'r', InputOption::VALUE_NONE, 'Generate a resource controller class.')
			->addOption('requests', 'R', InputOption::VALUE_NONE, 'Generate FormRequest classes for store and update.');
	}

	protected function getStub()
	{
		$stub = null;

		if ($type = $this->option('type')) {
			$stub = "controller.{$type}.stub";
		} elseif ($this->option('parent')) {
			$stub = 'controller.nested.stub';
		} elseif ($this->option('model')) {
			$stub = 'controller.model.stub';
		} elseif ($this->option('invokable')) {
			$stub = 'controller.invokable.stub';
		} elseif ($this->option('resource')) {
			$stub = 'controller.stub';
		}

		if ($this->option('api') && is_null($stub)) {
			$stub = 'controller.api.stub';
		} elseif ($this->option('api') && !is_null($stub) && !$this->option('invokable')) {
			$stub = str_replace('.stub', '.api.stub', $stub);
		}

		$stub ??= 'controller.plain.stub';

		return $this->resolveStubPath($stub);
	}

	protected function updateStubContent()
	{
		$stub = $this->getStub();
		$namespace = $this->resolveNamespace();

		$replace = [
			'{{ class }}' => $this->getQualifyClassName(),
			'{{ namespace }}' => $namespace,
			"{{ namespacedModel }}" => $this->getModelNamespace(),
			"{{ model }}" => ucfirst($this->option('model')),
			"{{ modelVariable }}" => strtolower($this->option('model'))
		];
		return strtr($stub, $replace);
	}

	protected function getModelNamespace()
	{
		return $this->getPackageNamespace() . '\\' . $this->getConfig('namespace.model') . '\\' . ucfirst($this->option('model'));
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

		/* if ($this->option('all')) {
			$this->input->setOption('factory', true);
			$this->input->setOption('seed', true);
			$this->input->setOption('migration', true);
			$this->input->setOption('controller', true);
			$this->input->setOption('policy', true);
			$this->input->setOption('resource', true);
		}

		if ($this->option('factory')) {
			//$this->createFactory();
		}

		if ($this->option('migration')) {
			//$this->createMigration();
		}

		if ($this->option('seed')) {
			//$this->createSeeder();
		}

		if ($this->option('controller') || $this->option('resource') || $this->option('api')) {
			//$this->createController();
		}

		if ($this->option('policy')) {
			//$this->createPolicy();
		} */
		return true;
	}
}