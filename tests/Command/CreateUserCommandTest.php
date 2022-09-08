<?php

// tests/Command/CreateUserCommandTest.php
namespace Pragnesh\LaravelPackageHelper\Tests\Command;

use PHPUnit\Framework\TestCase;
use Pragnesh\LaravelPackageHelper\Command\CreateUserCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class CreateUserCommandTest extends TestCase
{
	public function testExecute()
	{
		$application = new Application();
		$application->add(new CreateUserCommand());


		//$application->setAutoExit(false);
		//$tester = new ApplicationTester($application);

		$command = $application->find('make:user');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'username' => 'Wouter',
		]);

		$commandTester->assertCommandIsSuccessful();

		// the output of the command in the console
		$output = $commandTester->getDisplay();
		$this->assertStringContainsString('Username: Wouter', $output);
	}
}