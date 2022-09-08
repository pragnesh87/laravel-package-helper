<?php

namespace Pragnesh\LaravelPackageHelper\Command;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

// the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'config:install')]
class CreateConfigCommand extends BaseCommand
{
	protected function configure(): void
	{
		$this
			->setHelp('This command generate config.php file in root directory');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$filesystem = new Filesystem();

		$file = "config/larapack.php";
		$stubTemplate = $this->getStub('larapack.stub');

		if ($filesystem->exists($file)) {
			$helper = $this->getHelper('question');
			$question = new ConfirmationQuestion(
				'File already exist, would you like to overwrite it? (y/N): ',
				false,
				'/^(y|j)/i'
			);

			$answer = $helper->ask($input, $output, $question);
			//
			if ($answer) {
				$filesystem->dumpFile($file, $stubTemplate);
				$output->writeln('Updated Config File');
			} else {
				$output->writeln('Did not updated file');
				return Command::FAILURE;
			}
		} else {
			$filesystem->dumpFile($file, $stubTemplate);
			$output->writeln('Generated Config File');
		}

		return Command::SUCCESS;
	}
}