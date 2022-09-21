<?php

namespace Pragnesh\LaravelPackageHelper\Tests\Command;

use Pragnesh\LaravelPackageHelper\Tests\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Pragnesh\LaravelPackageHelper\Command\MakeModelCommand;
use Pragnesh\LaravelPackageHelper\Command\MakeControllerCommand;
use Pragnesh\LaravelPackageHelper\Command\MakeRequestCommand;

class ControllerCommandTest extends TestCase
{
	public function test_controller_command()
	{
		$files = ['appTest/src/Http/Controllers/DemoController.php'];
		$this->removeFiles($files);
		$this->application->add(new MakeControllerCommand());

		$command = $this->application->find('make:controller');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'name' => 'Demo'
		]);

		// the output of the command in the console
		$output = $commandTester->getDisplay();
		$this->assertStringContainsString('[OK]', $output);
		$this->assertFilesAndRemove($files);
	}

	public function test_controller_command_with_api_option()
	{
		$files = ['appTest/src/Http/Controllers/DemoController.php'];
		$this->removeFiles($files);
		$this->application->add(new MakeControllerCommand());

		$command = $this->application->find('make:controller');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'name' => 'Demo',
			'--api' => true
		]);

		// the output of the command in the console
		$output = $commandTester->getDisplay();
		$this->assertStringContainsString('[OK]', $output);
		$this->assertFilesAndRemove($files);
	}

	public function test_controller_command_with_invokable_option()
	{
		$files = ['appTest/src/Http/Controllers/DemoController.php'];
		$this->removeFiles($files);
		$this->application->add(new MakeControllerCommand());

		$command = $this->application->find('make:controller');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'name' => 'Demo',
			'--invokable' => true
		]);

		// the output of the command in the console
		$output = $commandTester->getDisplay();
		$this->assertStringContainsString('[OK]', $output);
		$this->assertFilesAndRemove($files);
	}

	public function test_controller_command_with_resource_option()
	{
		$files = ['appTest/src/Http/Controllers/DemoController.php'];
		$this->removeFiles($files);
		$this->application->add(new MakeControllerCommand());

		$command = $this->application->find('make:controller');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'name' => 'Demo',
			'--resource' => true
		]);

		// the output of the command in the console
		$output = $commandTester->getDisplay();
		$this->assertStringContainsString('[OK]', $output);
		$this->assertFilesAndRemove($files);
	}

	public function test_controller_command_with_model_option()
	{
		$files = ['appTest/src/Http/Controllers/DemoController.php', 'appTest/src/Models/Demo.php'];
		$this->removeFiles($files);
		$this->application->addCommands(
			[
				new MakeModelCommand,
				new MakeControllerCommand,
			]
		);

		$command = $this->application->find('make:controller');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'name' => 'Demo',
			'--model' => 'Demo'
		]);

		// the output of the command in the console
		$output = $commandTester->getDisplay();
		$this->assertStringContainsString('[OK]', $output);
		$this->assertFilesAndRemove($files);
	}

	public function test_controller_command_with_request_option()
	{
		$files = [
			'appTest/src/Http/Controllers/DemoController.php',
			'appTest/src/Http/Requests/StoreDemoRequest.php',
			'appTest/src/Http/Requests/UpdateDemoRequest.php'
		];
		$this->removeFiles($files);
		$this->application->addCommands(
			[
				new MakeControllerCommand,
				new MakeModelCommand,
				new MakeRequestCommand
			]
		);

		$command = $this->application->find('make:controller');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'name' => 'Demo',
			'--requests' => true
		]);

		// the output of the command in the console
		$output = $commandTester->getDisplay();
		$this->assertStringContainsString('[OK]', $output);
		$this->assertFilesAndRemove($files);
	}
}