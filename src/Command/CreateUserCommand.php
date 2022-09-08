<?php

namespace Pragnesh\LaravelPackageHelper\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

// the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'make:user')]
class CreateUserCommand extends Command
{
	protected function configure(): void
	{
		$this
			->addArgument('username', InputArgument::REQUIRED, 'The username of the user.')
			->setHelp('This command allows you to create a user...');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		// outputs multiple lines to the console (adding "\n" at the end of each line)
		$output->writeln([
			'User Creator',
			'============',
			'',
		]);

		// outputs a message followed by a "\n"
		$output->writeln('Whoa!');

		// outputs a message without adding a "\n" at the end of the line
		$output->write('You are about to ');
		$output->write('create a user.');

		$output->writeln('Username: ' . $input->getArgument('username'));

		$output->writeln('User successfully generated!');

		return Command::SUCCESS;
	}
}