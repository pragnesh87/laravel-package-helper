<?php

namespace Pragnesh\LaravelPackageHelper\Tests\Command;

use Pragnesh\LaravelPackageHelper\Tests\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Pragnesh\LaravelPackageHelper\Command\MakeEventCommand;

class EventCommandTest extends TestCase
{
	public function test_event_command()
	{
		$files = ['appTest/src/Events/DemoEvent.php'];
		$this->removeFiles($files);
		$this->application->add(new MakeEventCommand);

		$command = $this->application->find('make:event');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'name' => 'DemoEvent'
		]);

		// the output of the command in the console
		$output = $commandTester->getDisplay();
		$this->assertStringContainsString('[OK]', $output);
		$this->assertFilesAndRemove($files);
	}
}