<?php

namespace Pragnesh\LaravelPackageHelper\Tests\Command;

use Pragnesh\LaravelPackageHelper\Tests\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Pragnesh\LaravelPackageHelper\Command\MakeProviderCommand;

class ProviderCommandTest extends TestCase
{
	public function test_provider_command()
	{
		$files = ['appTest/src/DemoServiceProvider.php'];
		$this->removeFiles($files);
		$this->application->add(new MakeProviderCommand);

		$command = $this->application->find('make:provider');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'name' => 'DemoServiceProvider'
		]);

		// the output of the command in the console
		$output = $commandTester->getDisplay();
		$this->assertStringContainsString('[OK]', $output);
		$this->assertFilesAndRemove($files);
	}
}