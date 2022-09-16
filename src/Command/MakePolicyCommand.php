<?php

namespace Pragnesh\LaravelPackageHelper\Command;

use Pragnesh\LaravelPackageHelper\Helpers\Str;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Pragnesh\LaravelPackageHelper\Command\BaseCommand;

#[AsCommand(name: 'make:policy', description: 'Create a new policy class')]
class MakePolicyCommand extends BaseCommand
{
	protected string $type = 'policy';

	protected function configure(): void
	{
		$this
			->addArgument('name', InputArgument::REQUIRED, 'The name of the model class')
			->addOption('model', 'm', InputOption::VALUE_OPTIONAL, 'The model that the policy applies to')
			->addOption('guard', 'g', InputOption::VALUE_OPTIONAL, 'The guard that the policy relies on');
	}

	protected function getStub()
	{
		return $this->option('model')
			? $this->resolveStubPath('policy.stub')
			: $this->resolveStubPath('policy.plain.stub');
	}

	protected function updateStubContent()
	{
		$stub = $this->getStub();
		$namespace = $this->resolveNamespace();

		$replace = [
			'{{ class }}' => $this->getQualifyClassName(),
			'{{ namespace }}' => $namespace,
			'{{ namespacedModel }}' => $this->getModelNamespace(),
			"{{ namespacedUserModel }}" => 'App\Models\User',
			"{{ user }}" => 'User',
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

		return true;
	}
}