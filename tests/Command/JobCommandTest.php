<?php

namespace Pragnesh\LaravelPackageHelper\Tests\Command;

use Pragnesh\LaravelPackageHelper\Tests\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Pragnesh\LaravelPackageHelper\Command\MakeJobCommand;

class JobCommandTest extends TestCase
{
	public function test_job_command()
	{
		$files = ['appTest/src/Jobs/DemoJob.php'];
		$this->removeFiles($files);
		$this->application->add(new MakeJobCommand);

		$command = $this->application->find('make:job');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'name' => 'DemoJob'
		]);

		// the output of the command in the console
		$output = $commandTester->getDisplay();
		$this->assertStringContainsString('[OK]', $output);
		$this->assertFilesAndRemove($files);
	}
}