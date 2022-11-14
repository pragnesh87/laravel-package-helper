<?php

namespace Pragnesh\LaravelPackageHelper\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Pragnesh\LaravelPackageHelper\Command\BaseCommand;

#[AsCommand(name: 'make:rule', description: 'Create a new validation rule')]
class MakeRuleCommand extends BaseCommand
{
	protected string $type = 'rule';

	protected function configure(): void
	{
		$this
			->addArgument('name', InputArgument::REQUIRED, 'The name of the rule class')
			->addOption('implicit', 'i', InputOption::VALUE_NONE, 'Generate an implicit rule')
			->addOption('invokable', null, InputOption::VALUE_NONE, 'Generate a single method, invokable rule class');
	}

	protected function getStub()
	{
		$stub = 'rule.stub';

		if ($this->option('invokable')) {
			$stub = 'rule.invokable.stub';
		}

		if ($this->option('implicit') && $this->option('invokable')) {
			$stub = str_replace('.stub', '.implicit.stub', $stub);
		}
		return $this->resolveStubPath($stub);
	}

	protected function updateStubContent()
	{
		$stub = $this->getStub();
		$namespace = $this->resolveNamespace();

		$replace = [
			'{{ class }}' => $this->getQualifyClassName(),
			'{{ namespace }}' => $namespace,
			'{{ ruleType }}' => $this->option('implicit') ? 'ImplicitRule' : 'Rule',
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