<?php

namespace Pragnesh\LaravelPackageHelper\Tests\Command;

use Pragnesh\LaravelPackageHelper\Tests\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Pragnesh\LaravelPackageHelper\Command\MakeListenerCommand;

class ListenerCommandTest extends TestCase
{
	public function test_listener_command()
	{
		$files = ['appTest/src/Listeners/DemoListener.php'];
		$this->removeFiles($files);
		$this->application->add(new MakeListenerCommand);

		$command = $this->application->find('make:listener');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'name' => 'DemoListener'
		]);

		// the output of the command in the console
		$output = $commandTester->getDisplay();
		$this->assertStringContainsString('[OK]', $output);
		$this->assertFilesAndRemove($files);
	}
}