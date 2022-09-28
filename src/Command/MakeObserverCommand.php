<?php

namespace Pragnesh\LaravelPackageHelper\Command;

use InvalidArgumentException;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Pragnesh\LaravelPackageHelper\Command\BaseCommand;

#[AsCommand(name: 'make:observer', description: 'Create a new observer class')]
class MakeObserverCommand extends BaseCommand
{
	protected string $type = 'observer';

	protected function configure(): void
	{
		$this
			->addArgument('name', InputArgument::REQUIRED, 'The name of the observer class')
			->addOption('model', 'm', InputOption::VALUE_OPTIONAL, 'The model that the observer applies to');
	}

	protected function getStub()
	{
		return $this->option('model')
			? $this->resolveStubPath('observer.stub')
			: $this->resolveStubPath('observer.plain.stub');
	}

	protected function updateStubContent()
	{
		$stub = $this->getStub();
		$namespace = $this->resolveNamespace();

		$replace = [
			'{{ class }}' => $this->getQualifyClassName(),
			'{{ namespace }}' => $namespace,
		];
		if ($this->option('model')) {
			$replace = array_merge($replace, $this->replaceModels());
		}
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

	private function replaceModels()
	{
		$model = $this->option('model');
		if (preg_match('([^A-Za-z0-9_/\\\\])', $model)) {
			throw new InvalidArgumentException('Model name contains invalid characters.');
		}

		$model = $this->getQualifyModelName($model);
		return [
			'{{ namespacedModel }}' => $this->getPackageNamespace() . '\\' . $this->getConfig('namespace.model') . '\\' . ucfirst($model),
			'{{ model }}' => ucfirst($model),
			'{{ modelVariable }}' => strtolower($model)
		];
	}
}