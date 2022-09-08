<?php

namespace Pragnesh\LaravelPackageHelper\Command;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

// the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'make:controller', description: 'Create a new controller class')]
class MakeControllerCommand extends BaseCommand
{
	protected function configure(): void
	{
		$this
			->addArgument('name', InputArgument::REQUIRED, 'The name of the controller class')
			->addOption(name: 'api', description: "Exclude the create and edit methods from the controller.")
			->addOption(name: 'invokable', description: 'Generate a single method, invokable controller class.')
			->addOption(name: 'model', description: 'Generate a resource controller for the given model.')
			->addOption(name: 'resource', description: 'Generate a resource controller class.')
			->addOption(name: 'requests', description: 'Generate FormRequest classes for store and update.');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$io = new SymfonyStyle($input, $output);
		if (!$this->isConfigExist()) {
			$io->error(['Configuration file not found.', 'Please run `./vendor/bin/larapack config:install` command']);
			return Command::FAILURE;
		}

		$class = $input->getArgument('name');
		$type = 'controller';
		$stub = $this->getStub($input);
		$namespace = $this->resolveNamespace($type);
		$path = $this->resolvePath($type);

		$stubTemplate = str_replace(
			['{{ class }}', '{{ namespace }}', '{{ VENDORPACKAGE  }}'],
			[$class, $namespace, $this->getPackageNamespace()],
			$stub
		);

		$file = $path . DIRECTORY_SEPARATOR . $class . '.php';
		$this->writeFile($file, $stubTemplate, $io);

		return Command::SUCCESS;
	}

	protected function getStub(InputInterface $input)
	{
		$stub = null;

		if ($input->getOption('model')) {
			$stub = 'controller.model.stub';
		} elseif ($input->getOption('invokable')) {
			$stub = 'controller.invokable.stub';
		} elseif ($input->getOption('resource')) {
			$stub = 'controller.stub';
		}

		if ($input->getOption('api') && is_null($stub)) {
			$stub = 'controller.api.stub';
		} elseif ($input->getOption('api') && !is_null($stub) && !$input->getOption('invokable')) {
			$stub = str_replace('.stub', '.api.stub', $stub);
		}

		$stub ??= 'controller.plain.stub';

		return $this->resolveStubPath($stub);
	}
}