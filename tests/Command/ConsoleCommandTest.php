<?php

namespace Pragnesh\LaravelPackageHelper\Tests\Command;

use Pragnesh\LaravelPackageHelper\Command\MakeConsoleCommand;
use Pragnesh\LaravelPackageHelper\Tests\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ConsoleCommandTest extends TestCase
{
	public function test_console_command()
	{
		$files = ['appTest/src/Console/Commands/DemoCommand.php'];
		$this->removeFiles($files);
		$this->application->add(new MakeConsoleCommand);

		$command = $this->application->find('make:command');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'name' => 'DemoCommand'
		]);

		// the output of the command in the console
		$output = $commandTester->getDisplay();
		$this->assertStringContainsString('[OK]', $output);
		$this->assertFilesAndRemove($files);
	}
}