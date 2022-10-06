<?php

namespace Pragnesh\LaravelPackageHelper\Tests\Command;

use Pragnesh\LaravelPackageHelper\Tests\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Pragnesh\LaravelPackageHelper\Command\MakeExceptionCommand;

class ExceptionCommandTest extends TestCase
{
	public function test_exception_command()
	{
		$files = ['appTest/src/Exceptions/DemoException.php'];
		$this->removeFiles($files);
		$this->application->add(new MakeExceptionCommand);

		$command = $this->application->find('make:exception');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'name' => 'DemoException'
		]);

		// the output of the command in the console
		$output = $commandTester->getDisplay();
		$this->assertStringContainsString('[OK]', $output);
		$this->assertFilesAndRemove($files);
	}
}