<?php

namespace Pragnesh\LaravelPackageHelper\Tests\Command;

use Pragnesh\LaravelPackageHelper\Command\MakeControllerCommand;
use Pragnesh\LaravelPackageHelper\Command\MakeFactoryCommand;
use Pragnesh\LaravelPackageHelper\Command\MakeMigrationCommand;
use Pragnesh\LaravelPackageHelper\Command\MakeModelCommand;
use Pragnesh\LaravelPackageHelper\Command\MakePolicyCommand;
use Pragnesh\LaravelPackageHelper\Command\MakeRequestCommand;
use Pragnesh\LaravelPackageHelper\Command\MakeSeederCommand;
use Pragnesh\LaravelPackageHelper\Tests\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ModelCommandTest extends TestCase
{
	public function test_make_model_command()
	{
		$files = ['appTest/src/Models/Demo.php'];
		$this->removeFiles($files);
		$this->application->add(new MakeModelCommand());

		$command = $this->application->find('make:model');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'name' => 'Demo'
		]);

		// the output of the command in the console
		$output = $commandTester->getDisplay();
		$this->assertStringContainsString('[OK]', $output);
		$this->assertFilesAndRemove($files);
	}

	public function test_model_command_with_factory_option()
	{
		$files = ['appTest/src/Models/Demo.php', 'appTest/database/factories/DemoFactory.php'];
		$this->removeFiles($files);
		$this->application->addCommands(
			[
				new MakeModelCommand(),
				new MakeFactoryCommand
			]
		);

		$command = $this->application->find('make:model');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'name' => 'Demo',
			'--factory' => true
		]);

		// the output of the command in the console
		$output = $commandTester->getDisplay();
		$this->assertStringContainsString('[OK]', $output);
		$this->assertFilesAndRemove($files);
	}

	public function test_model_command_with_controller_option()
	{
		$files = ['appTest/src/Models/Demo.php', 'appTest/src/Http/Controllers/DemoController.php'];
		$this->removeFiles($files);
		$this->application->addCommands(
			[
				new MakeModelCommand(),
				new MakeControllerCommand()
			]
		);

		$command = $this->application->find('make:model');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'name' => 'Demo',
			'--controller' => true
		]);

		// the output of the command in the console
		$output = $commandTester->getDisplay();
		$this->assertStringContainsString('[OK]', $output);
		$this->assertFilesAndRemove($files);
	}

	public function test_model_command_with_migration_option()
	{
		$files = ['appTest/src/Models/Demo.php'];
		$this->removeFiles($files);
		$this->application->addCommands(
			[
				new MakeModelCommand,
				new MakeMigrationCommand
			]
		);

		$command = $this->application->find('make:model');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'name' => 'Demo',
			'--migration' => true
		]);

		// the output of the command in the console
		$output = $commandTester->getDisplay();
		$this->assertStringContainsString('[OK]', $output);
		$this->assertFilesAndRemove($files);
	}

	public function test_model_command_with_seed_option()
	{
		$files = ['appTest/src/Models/Demo.php', 'appTest/database/seeders/DemoSeeder.php'];
		$this->removeFiles($files);
		$this->application->addCommands(
			[
				new MakeModelCommand,
				new MakeSeederCommand
			]
		);

		$command = $this->application->find('make:model');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'name' => 'Demo',
			'--seed' => true
		]);

		// the output of the command in the console
		$output = $commandTester->getDisplay();
		$this->assertStringContainsString('[OK]', $output);
		$this->assertFilesAndRemove($files);
	}

	public function test_model_command_with_resource_option()
	{
		$files = ['appTest/src/Models/Demo.php', 'appTest/src/Http/Controllers/DemoController.php'];
		$this->removeFiles($files);
		$this->application->addCommands(
			[
				new MakeModelCommand,
				new MakeControllerCommand
			]
		);

		$command = $this->application->find('make:model');
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

	public function test_model_command_with_api_option()
	{
		$files = ['appTest/src/Models/Demo.php', 'appTest/src/Http/Controllers/DemoController.php'];
		$this->removeFiles($files);
		$this->application->addCommands(
			[
				new MakeModelCommand,
				new MakeControllerCommand
			]
		);

		$command = $this->application->find('make:model');
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

	public function test_model_command_with_policy_option()
	{
		$files = ['appTest/src/Models/Demo.php', 'appTest/src/Policies/DemoPolicy.php'];
		$this->removeFiles($files);
		$this->application->addCommands(
			[
				new MakeModelCommand,
				new MakePolicyCommand
			]
		);

		$command = $this->application->find('make:model');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'name' => 'Demo',
			'--policy' => true
		]);

		// the output of the command in the console
		$output = $commandTester->getDisplay();
		$this->assertStringContainsString('[OK]', $output);
		$this->assertFilesAndRemove($files);
	}

	public function test_model_command_with_morphpivot_option()
	{
		$files = ['appTest/src/Models/Demo.php'];
		$this->removeFiles($files);
		$this->application->addCommands(
			[
				new MakeModelCommand,
			]
		);

		$command = $this->application->find('make:model');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'name' => 'Demo',
			'--morph-pivot' => true
		]);

		// the output of the command in the console
		$output = $commandTester->getDisplay();
		$this->assertStringContainsString('[OK]', $output);
		$this->assertFilesAndRemove($files);
	}

	public function test_model_command_with_pivot_option()
	{
		$files = ['appTest/src/Models/Demo.php'];
		$this->removeFiles($files);
		$this->application->addCommands(
			[
				new MakeModelCommand,
			]
		);

		$command = $this->application->find('make:model');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'name' => 'Demo',
			'--pivot' => true
		]);

		// the output of the command in the console
		$output = $commandTester->getDisplay();
		$this->assertStringContainsString('[OK]', $output);
		$this->assertFilesAndRemove($files);
	}

	public function test_model_command_with_request_option()
	{
		$files = [
			'appTest/src/Models/Demo.php',
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

		$command = $this->application->find('make:model');
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

	public function test_model_command_with_all_option()
	{
		$files = [
			'appTest/src/Models/Demo.php',
			'appTest/database/factories/DemoFactory.php',
			'appTest/src/Http/Controllers/DemoController.php',
			'appTest/database/seeders/DemoSeeder.php',
			'appTest/src/Policies/DemoPolicy.php',
			'appTest/src/Http/Requests/StoreDemoRequest.php',
			'appTest/src/Http/Requests/UpdateDemoRequest.php'
		];
		$this->removeFiles($files);
		$this->application->addCommands(
			[
				new MakeModelCommand,
				new MakeControllerCommand,
				new MakeFactoryCommand,
				new MakeSeederCommand,
				new MakePolicyCommand,
				new MakeRequestCommand,
				new MakeMigrationCommand,
			]
		);

		$command = $this->application->find('make:model');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'name' => 'Demo',
			'--all' => true
		]);

		// the output of the command in the console
		$output = $commandTester->getDisplay();
		$this->assertStringContainsString('[OK]', $output);
		$this->assertFilesAndRemove($files);
	}
}