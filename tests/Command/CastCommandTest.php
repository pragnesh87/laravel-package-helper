<?php

namespace Pragnesh\LaravelPackageHelper\Tests\Command;

use Pragnesh\LaravelPackageHelper\Command\MakeCastCommand;
use Pragnesh\LaravelPackageHelper\Command\MakeConsoleCommand;
use Pragnesh\LaravelPackageHelper\Tests\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CastCommandTest extends TestCase
{
	public function test_cast_command()
	{
		$files = ['appTest/src/Casts/DemoCast.php'];
		$this->removeFiles($files);
		$this->application->add(new MakeCastCommand);

		$command = $this->application->find('make:cast');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'name' => 'DemoCast'
		]);

		// the output of the command in the console
		$output = $commandTester->getDisplay();
		$this->assertStringContainsString('[OK]', $output);
		$this->assertFilesAndRemove($files);
	}

	public function test_cast_command_with_inbound_option()
	{
		$files = ['appTest/src/Casts/DemoCast.php'];
		$this->removeFiles($files);
		$this->application->add(new MakeCastCommand);

		$command = $this->application->find('make:cast');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'name' => 'DemoCast',
			'--inbound' => true,
		]);

		// the output of the command in the console
		$output = $commandTester->getDisplay();
		$this->assertStringContainsString('[OK]', $output);
		$this->assertFilesAndRemove($files);
	}
}