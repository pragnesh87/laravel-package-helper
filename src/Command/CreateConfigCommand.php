<?php

namespace Pragnesh\LaravelPackageHelper\Command;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

// the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'config:install', description: 'Generate config/larapack.php file in root directory')]
class CreateConfigCommand extends BaseCommand
{
	protected function configure(): void
	{
		$this
			->setHelp('This command generate config/larapack.php file in root directory');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$io = new SymfonyStyle($input, $output);

		$file = "config/larapack.php";
		$stubTemplate = $this->getStub($input);

		$this->writeFile($file, $stubTemplate, $io);
		return Command::SUCCESS;
	}

	protected function getStub(InputInterface $input)
	{
		return $this->resolveStubPath('larapack.stub');
	}
}