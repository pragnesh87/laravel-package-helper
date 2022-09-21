<?php

namespace Pragnesh\LaravelPackageHelper\Command;

use Pragnesh\LaravelPackageHelper\Helpers\Str;
use Symfony\Component\Console\Input\ArrayInput;
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
			->addOption('resource', 'r', InputOption::VALUE_NONE, 'Generate a resource controller class.')
			->addOption('requests', 'R', InputOption::VALUE_NONE, 'Generate FormRequest classes for store and update.');
	}

	protected function getStub()
	{
		$stub = null;

		if ($type = $this->option('type')) {
			$stub = "controller.{$type}.stub";
		} elseif ($this->option('model') || $this->option('requests')) {
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
		];
		if ($this->option('requests')) {
			$replace = array_merge($replace, $this->requestReplacement());
		}

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

		if ($this->option('model')) {
			$this->createModel();
		}
		if ($this->option('requests')) {
			$this->createRequests();
		}
		return true;
	}

	protected function createModel()
	{
		$model = $this->getQualifyModelName($this->getNameInput());

		if (!file_exists($this->getModelPath() . '/' . $model . '.php')) {
			$command = $this->getApplication()->find('make:model');

			$arguments = [
				'name' => $model,
			];

			$greetInput = new ArrayInput($arguments);
			return $command->run($greetInput, $this->output);
		}
	}

	protected function createRequests()
	{
		$model = $this->getQualifyModelName($this->getNameInput());

		$command = $this->getApplication()->find('make:request');

		$arguments = [
			'name' => "Store{$model}Request",
		];

		$greetInput = new ArrayInput($arguments);
		$command->run($greetInput, $this->output);

		$arguments = [
			'name' => "Update{$model}Request",
		];

		$greetInput = new ArrayInput($arguments);

		return $command->run($greetInput, $this->output);
	}

	protected function requestReplacement(): array
	{
		$model = $this->getQualifyModelName($this->getNameInput());

		$useStr = "use " . $this->getNamespacedRequests() . "\\Store{$model}Request;\nuse " . $this->getNamespacedRequests() . "\\update{$model}Request;";

		return [
			"{{ namespacedModel }}" => $this->getModelNamespace() . "\\" . $model,
			"{{ model }}" => ucfirst($model),
			"{{ modelVariable }}" => strtolower($model),
			"{{ storeRequest }}" => "Store{$model}Request",
			"{{ updateRequest }}" => "Update{$model}Request",
			"{{ namespacedRequests }}" => $useStr,
		];
	}

	protected function getNamespacedRequests()
	{
		return $this->getPackageNamespace() . '\\' . $this->getConfig('namespace.request');
	}
}