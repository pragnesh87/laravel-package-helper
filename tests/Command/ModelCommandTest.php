<?php

namespace Pragnesh\LaravelPackageHelper\Tests\Command;

use Pragnesh\LaravelPackageHelper\Command\MakeModelCommand;
use Pragnesh\LaravelPackageHelper\Tests\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ModelCommandTest extends TestCase
{
	public function test_make_command()
	{
		$this->application->add(new MakeModelCommand());

		$command = $this->application->find('make:model');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'name' => 'Demo'
		]);

		// the output of the command in the console
		$output = $commandTester->getDisplay();
		$this->assertStringContainsString('[OK]', $output);
	}
}