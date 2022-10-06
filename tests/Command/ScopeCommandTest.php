<?php

namespace Pragnesh\LaravelPackageHelper\Tests\Command;

use Pragnesh\LaravelPackageHelper\Tests\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Pragnesh\LaravelPackageHelper\Command\MakeScopeCommand;

class ScopeCommandTest extends TestCase
{
	public function test_scope_command()
	{
		$files = ['appTest/src/Models/Scopes/DemoScope.php'];
		$this->removeFiles($files);
		$this->application->add(new MakeScopeCommand);

		$command = $this->application->find('make:scope');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'name' => 'DemoScope'
		]);

		// the output of the command in the console
		$output = $commandTester->getDisplay();
		$this->assertStringContainsString('[OK]', $output);
		$this->assertFilesAndRemove($files);
	}
}