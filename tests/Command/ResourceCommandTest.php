<?php

namespace Pragnesh\LaravelPackageHelper\Tests\Command;

use Pragnesh\LaravelPackageHelper\Tests\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Pragnesh\LaravelPackageHelper\Command\MakeResourceCommand;

class ResourceCommandTest extends TestCase
{
	public function test_resource_command()
	{
		$files = ['appTest/src/Http/Resources/DemoResource.php'];
		$this->removeFiles($files);
		$this->application->add(new MakeResourceCommand);

		$command = $this->application->find('make:resource');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'name' => 'DemoResource'
		]);

		// the output of the command in the console
		$output = $commandTester->getDisplay();
		$this->assertStringContainsString('[OK]', $output);
		$this->assertFilesAndRemove($files);
	}
}