<?php

namespace Pragnesh\LaravelPackageHelper\Command;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

// the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'make:test', description: 'Create a new test class')]
class MakeTestCommand extends BaseCommand
{
	protected function configure(): void
	{
		$this
			->addArgument('name', InputArgument::REQUIRED, 'The name of the test class')
			->addOption(name: 'unit', description: "Create a unit test.")
			->addOption(name: 'pest', description: 'Create a Pest test.')
			->setHelp('This will create test class');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		if (!$this->isConfigExist()) {
			$output->writeln('Configuration file not found.');
			$output->writeln('Please run `config:install` command');
			return Command::FAILURE;
		}

		$class = $input->getArgument('name');
		$type = $input->getOption('unit') ? 'unit_test' : 'feature_test';

		$stub = $this->getStub($input);
		$namespace = $this->resolveNamespace($type);
		$path = $this->resolvePath($type);

		$stubTemplate = str_replace(
			['{{ class }}', '{{ namespace }}', '{{ VENDORPACKAGE  }}'],
			[$class, $namespace, $this->getPackageNamespace()],
			$stub
		);

		$file = $path . DIRECTORY_SEPARATOR . $class . '.php';
		$this->writeFile($file, $stubTemplate, $input, $output);
		return Command::SUCCESS;
	}

	protected function getStub(InputInterface $input)
	{
		$suffix = $input->getOption('unit') ? '.unit.stub' : '.stub';

		return $input->getOption('pest')
			? $this->resolveStubPath('pest' . $suffix)
			: $this->resolveStubPath('test' . $suffix);
	}
}