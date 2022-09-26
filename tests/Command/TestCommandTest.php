<?php

namespace Pragnesh\LaravelPackageHelper\Tests\Command;

use Pragnesh\LaravelPackageHelper\Tests\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Pragnesh\LaravelPackageHelper\Command\MakeTestCommand;

class TestCommandTest extends TestCase
{
	public function test_feature_test_command()
	{
		$files = ['appTest/tests/Feature/DemoTest.php'];
		$this->removeFiles($files);
		$this->application->add(new MakeTestCommand);

		$command = $this->application->find('make:test');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'name' => 'DemoTest'
		]);

		// the output of the command in the console
		$output = $commandTester->getDisplay();
		$this->assertStringContainsString('[OK]', $output);
		$this->assertFilesAndRemove($files);
	}

	public function test_unit_test_command()
	{
		$files = ['appTest/tests/Unit/DemoTest.php'];
		$this->removeFiles($files);
		$this->application->add(new MakeTestCommand);

		$command = $this->application->find('make:test');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'name' => 'DemoTest',
			'--unit' => true
		]);

		// the output of the command in the console
		$output = $commandTester->getDisplay();
		$this->assertStringContainsString('[OK]', $output);
		$this->assertFilesAndRemove($files);
	}

	public function test_feature_test_with_pest_command()
	{
		$files = ['appTest/tests/Feature/DemoTest.php'];
		$this->removeFiles($files);
		$this->application->add(new MakeTestCommand);

		$command = $this->application->find('make:test');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'name' => 'DemoTest',
			'--pest' => true
		]);

		// the output of the command in the console
		$output = $commandTester->getDisplay();
		$this->assertStringContainsString('[OK]', $output);
		$this->assertFilesAndRemove($files);
	}

	public function test_unit_test_with_pest_command()
	{
		$files = ['appTest/tests/Unit/DemoTest.php'];
		$this->removeFiles($files);
		$this->application->add(new MakeTestCommand);

		$command = $this->application->find('make:test');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'name' => 'DemoTest',
			'--unit' => true,
			'--pest' => true
		]);

		// the output of the command in the console
		$output = $commandTester->getDisplay();
		$this->assertStringContainsString('[OK]', $output);
		$this->assertFilesAndRemove($files);
	}
}