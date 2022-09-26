<?php

namespace Pragnesh\LaravelPackageHelper\Tests\Command;

use Pragnesh\LaravelPackageHelper\Tests\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Pragnesh\LaravelPackageHelper\Command\MakeChannelCommand;

class ChannelCommandTest extends TestCase
{
	public function test_channel_command()
	{
		$files = ['appTest/src/Broadcasting/DemoChannel.php'];
		$this->removeFiles($files);
		$this->application->add(new MakeChannelCommand);

		$command = $this->application->find('make:channel');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'name' => 'DemoChannel'
		]);

		// the output of the command in the console
		$output = $commandTester->getDisplay();
		$this->assertStringContainsString('[OK]', $output);
		$this->assertFilesAndRemove($files);
	}
}