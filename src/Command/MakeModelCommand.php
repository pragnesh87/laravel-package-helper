<?php

namespace Pragnesh\LaravelPackageHelper\Command;

use Pragnesh\LaravelPackageHelper\Helpers\Str;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Pragnesh\LaravelPackageHelper\Command\BaseCommand;

#[AsCommand(name: 'make:model', description: 'Create a new Eloquent model class')]
class MakeModelCommand extends BaseCommand
{
	protected string $type = 'model';

	protected function configure(): void
	{
		$this
			->addArgument('name', InputArgument::REQUIRED, 'The name of the model class')
			->addOption('all', 'a', InputOption::VALUE_NONE, 'Generate a migration, seeder, factory, policy, resource controller, and form request classes for the model')
			->addOption('controller', 'c', InputOption::VALUE_NONE, 'Create a new controller for the model')
			->addOption('factory', 'f', InputOption::VALUE_NONE, 'Create a new factory for the model')
			->addOption('migration', 'm', InputOption::VALUE_NONE, 'Create a new migration file for the model')
			->addOption('morph-pivot', null, InputOption::VALUE_NONE, 'Indicates if the generated model should be a custom polymorphic intermediate table model')
			->addOption('policy', null, InputOption::VALUE_NONE, 'Create a new policy for the model')
			->addOption('seed', 's', InputOption::VALUE_NONE, 'Create a new seeder for the model')
			->addOption('pivot', 'p', InputOption::VALUE_NONE, 'Indicates if the generated model should be a custom intermediate table model')
			->addOption('resource', 'r', InputOption::VALUE_NONE, 'Indicates if the generated controller should be a resource controller')
			->addOption('api', null, InputOption::VALUE_NONE, 'Indicates if the generated controller should be an API resource controller')
			->addOption('requests', 'R', InputOption::VALUE_NONE, 'Create new form request classes and use them in the resource controller');
	}

	protected function getStub()
	{
		if ($this->option('pivot')) {
			return $this->resolveStubPath('model.pivot.stub');
		}

		if ($this->option('morph-pivot')) {
			return $this->resolveStubPath('model.morph-pivot.stub');
		}

		return $this->resolveStubPath('model.stub');
	}

	protected function updateStubContent()
	{
		$stub = $this->getStub();
		$class = ucfirst($this->getNameInput());
		$namespace = $this->resolveNamespace();

		$replace = [
			'{{ class }}' => $class,
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

		$class = ucfirst($this->getNameInput());
		$path = $this->resolvePath();
		$stubTemplate = $this->updateStubContent();
		$file = $path . DIRECTORY_SEPARATOR . $class . '.php';
		$this->writeFile($file, $stubTemplate);

		if ($this->option('all')) {
			$this->input->setOption('factory', true);
			$this->input->setOption('seed', true);
			$this->input->setOption('migration', true);
			$this->input->setOption('controller', true);
			$this->input->setOption('policy', true);
			$this->input->setOption('resource', true);
			$this->input->setOption('requests', true);
		}

		if ($this->option('factory')) {
			$this->createFactory();
		}

		if ($this->option('migration')) {
			$this->createMigration();
		}

		if ($this->option('seed')) {
			$this->createSeeder();
		}

		if (
			$this->option('controller')
			|| $this->option('resource')
			|| $this->option('api')
			|| $this->option('requests')
		) {
			$this->createController();
		}

		if ($this->option('policy')) {
			$this->createPolicy();
		}
		return true;
	}

	/**
	 * Create a controller for the model.
	 *
	 * @return void
	 */
	protected function createController()
	{
		$modelName = Str::studly($this->getNameInput());

		$command = $this->getApplication()->find('make:controller');

		$arguments = [
			'name' => "{$modelName}Controller",
			'--model' => $modelName,
			'--api' => $this->option('api'),
			'--requests' => $this->option('requests') || $this->option('all'),
		];

		$greetInput = new ArrayInput($arguments);
		return $command->run($greetInput, $this->output);
	}

	/**
	 * Create a model factory for the model.
	 *
	 * @return void
	 */
	protected function createFactory()
	{
		$factory = Str::studly($this->getNameInput());

		$command = $this->getApplication()->find('make:factory');

		$arguments = [
			'name' => "{$factory}Factory",
			'--model' => $factory,
		];

		$greetInput = new ArrayInput($arguments);
		return $command->run($greetInput, $this->output);
	}

	/**
	 * Create a migration file for the model.
	 *
	 * @return void
	 */
	protected function createMigration()
	{
		$table = Str::snake(Str::pluralStudly($this->getNameInput()));

		if ($this->option('pivot')) {
			$table = Str::singular($table);
		}

		$command = $this->getApplication()->find('make:migration');

		$arguments = [
			'name' => "create_{$table}_table",
			'--create' => $table,
		];

		$greetInput = new ArrayInput($arguments);
		return $command->run($greetInput, $this->output);
	}

	/**
	 * Create a seeder file for the model.
	 *
	 * @return void
	 */
	protected function createSeeder()
	{
		$seeder = Str::studly($this->getNameInput());

		$command = $this->getApplication()->find('make:seeder');

		$arguments = [
			'name' => "{$seeder}Seeder"
		];

		$greetInput = new ArrayInput($arguments);
		return $command->run($greetInput, $this->output);
	}

	/**
	 * Create a policy file for the model.
	 *
	 * @return void
	 */
	protected function createPolicy()
	{
		$policy = Str::studly($this->getNameInput());

		$command = $this->getApplication()->find('make:policy');

		$arguments = [
			'name' => "{$policy}Policy",
			'--model' => $this->getQualifyModelName($this->getNameInput()),
		];

		$greetInput = new ArrayInput($arguments);
		return $command->run($greetInput, $this->output);
	}
}