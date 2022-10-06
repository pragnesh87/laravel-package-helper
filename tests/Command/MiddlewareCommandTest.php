<?php

namespace Pragnesh\LaravelPackageHelper\Tests\Command;

use Pragnesh\LaravelPackageHelper\Tests\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Pragnesh\LaravelPackageHelper\Command\MakeMiddlewareCommand;

class MiddlewareCommandTest extends TestCase
{
	public function test_middleware_command()
	{
		$files = ['appTest/src/Http/Middleware/DemoMiddleware.php'];
		$this->removeFiles($files);
		$this->application->add(new MakeMiddlewareCommand);

		$command = $this->application->find('make:middleware');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'name' => 'DemoMiddleware'
		]);

		// the output of the command in the console
		$output = $commandTester->getDisplay();
		$this->assertStringContainsString('[OK]', $output);
		$this->assertFilesAndRemove($files);
	}
}