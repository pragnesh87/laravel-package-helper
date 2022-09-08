<?php

namespace Pragnesh\LaravelPackageHelper\Command;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

// the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'make:test')]
class MakeTestCommand extends BaseCommand
{
	protected function configure(): void
	{
		$this
			->addArgument('name', InputArgument::REQUIRED, 'The name of the test class')
			->addOption('unit')
			->setHelp('This will create test class');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$this->loadConfig();
		$this->setPackageNamespace();

		if (!$this->isConfigExist()) {
			$output->writeln('Configuration file not found.');
			$output->writeln('Please run `config:install` command');
			return Command::FAILURE;
		}

		$pacakgeNS = $this->getPackageNamespace();

		$filesystem = new Filesystem();

		$testName = $input->getArgument('name');

		$suffix = $input->getOption('unit') ? '.unit.stub' : '.stub';
		$type = $input->getOption('unit') ? 'unit_test' : 'feature_test';

		$stub = $this->getStub('test' . $suffix);

		$namespace = $pacakgeNS . '\\' . $this->getConfig('namespace.' . $type);
		$path = $this->getConfig('paths.' . $type);

		$stubTemplate = str_replace(
			['{{ class }}', '{{ namespace }}', '{{ VENDORPACKAGE  }}'],
			[$testName, $namespace, $pacakgeNS],
			$stub
		);

		$file = $path . DIRECTORY_SEPARATOR . $testName . '.php';

		$filesystem->dumpFile($file, $stubTemplate);

		/* if ($filesystem->exists('tests/Unit')) {
		} else {
			$filesystem->mkdir('tests/Unit', 0700);
			$filesystem->copy('./stubs/test.unit.stub', 'tests/Unit/FirstTest.php');
		} */

		$output->writeln('File created: ' . $file);
		return Command::SUCCESS;
	}
}