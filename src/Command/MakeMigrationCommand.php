<?php

namespace Pragnesh\LaravelPackageHelper\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Pragnesh\LaravelPackageHelper\Command\BaseCommand;
use Pragnesh\LaravelPackageHelper\Helpers\TableGuesser;

#[AsCommand(name: 'make:migration', description: 'Create a new migration file')]
class MakeMigrationCommand extends BaseCommand
{
	protected string $type = 'migration';

	protected function configure(): void
	{
		$this
			->addArgument('name', InputArgument::REQUIRED, 'The name of the migration')
			->addOption('create', null, InputOption::VALUE_OPTIONAL, 'The table to be created')
			->addOption('table', null, InputOption::VALUE_OPTIONAL, 'The table to migrate')
			->addOption('path', null, InputOption::VALUE_OPTIONAL, 'The location where the migration file should be created')
			->addOption('realpath', null, InputOption::VALUE_NONE, 'Indicate any provided migration file paths are pre-resolved absolute paths')
			->addOption('fullpath', null, InputOption::VALUE_NONE, 'Output the full path of the migration');
	}

	protected function getStub()
	{
		$name = $this->getNameInput();
		$table = $this->option('table');
		$create = $this->option('create') ?: false;

		if (!$table && is_string($create)) {
			$table = $create;
			$create = true;
		}

		if (!$table) {
			[$table, $create] = TableGuesser::guess($name);
		}
		if (is_null($table)) {
			return $this->resolveStubPath('migration.stub');
		} elseif ($create) {
			return $this->resolveStubPath('migration.create.stub');
		} else {
			return $this->resolveStubPath('migration.update.stub');
		}
	}

	public function updateStubContent()
	{
		$stub = $this->getStub();

		$name = strtolower($this->getNameInput());
		$table = $this->option('table');
		$create = $this->option('create') ?: false;

		if (!$table && is_string($create)) {
			$table = $create;
			$create = true;
		}

		if (!$table) {
			[$table, $create] = TableGuesser::guess($name);
		}

		$replace = [
			'{{ table }}' => $table,
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

		$class = strtolower($this->getNameInput());
		$path = $this->resolvePath();
		$stubTemplate = $this->updateStubContent();
		$file = $path . DIRECTORY_SEPARATOR . date('Y_m_d_His') . '_' . $class . '.php';

		$this->writeFile($file, $stubTemplate);

		return true;
	}
}