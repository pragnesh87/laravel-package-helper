<?php

namespace Pragnesh\LaravelPackageHelper\Tests\Command;

use Pragnesh\LaravelPackageHelper\Tests\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Pragnesh\LaravelPackageHelper\Command\MakeObserverCommand;

class ObserverCommandTest extends TestCase
{
	public function test_observer_command()
	{
		$files = ['appTest/src/Observers/DemoObserver.php'];
		$this->removeFiles($files);
		$this->application->add(new MakeObserverCommand);

		$command = $this->application->find('make:observer');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'name' => 'DemoObserver'
		]);

		// the output of the command in the console
		$output = $commandTester->getDisplay();
		$this->assertStringContainsString('[OK]', $output);
		$this->assertFilesAndRemove($files);
	}

	public function test_observer_command_with_model_option()
	{
		$files = ['appTest/src/Observers/DemoObserver.php'];
		$this->removeFiles($files);
		$this->application->add(new MakeObserverCommand);

		$command = $this->application->find('make:observer');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'name' => 'DemoObserver',
			'--model' => 'User'
		]);

		// the output of the command in the console
		$output = $commandTester->getDisplay();
		$this->assertStringContainsString('[OK]', $output);
		$this->assertFilesAndRemove($files);
	}
}