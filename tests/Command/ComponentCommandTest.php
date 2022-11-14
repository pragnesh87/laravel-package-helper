<?php

namespace Pragnesh\LaravelPackageHelper\Tests\Command;

use Pragnesh\LaravelPackageHelper\Tests\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Pragnesh\LaravelPackageHelper\Command\MakeComponentCommand;

class ComponentCommandTest extends TestCase
{
	public function test_component_command()
	{
		$files = [
			'appTest/src/View/Components/Forms/Input/DemoComponent.php',
			'appTest/resources/views/components/forms/input/demo-component.blade.php'
		];

		$this->removeFiles($files);
		$this->application->add(new MakeComponentCommand);

		$command = $this->application->find('make:component');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'name' => 'Forms/Input/DemoComponent'
		]);

		// the output of the command in the console
		$output = $commandTester->getDisplay();
		$this->assertStringContainsString('[OK]', $output);
		$this->assertFilesAndRemove($files);
	}

	public function test_component_inline_command()
	{
		$files = [
			'appTest/src/View/Components/Forms/Input/DemoComponent.php',
		];
		$this->removeFiles($files);
		$this->application->add(new MakeComponentCommand);

		$command = $this->application->find('make:component');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'name' => 'Forms/Input/DemoComponent',
			'--inline' => true
		]);

		// the output of the command in the console
		$output = $commandTester->getDisplay();
		$this->assertStringContainsString('[OK]', $output);
		$this->assertFilesAndRemove($files);
	}

	public function test_component_view_command()
	{
		$files = [
			'appTest/resources/views/components/forms/input/demo-component.blade.php',
		];
		$this->removeFiles($files);
		$this->application->add(new MakeComponentCommand);

		$command = $this->application->find('make:component');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'name' => 'Forms/Input/DemoComponent',
			'--view' => true
		]);

		// the output of the command in the console
		$output = $commandTester->getDisplay();
		$this->assertStringContainsString('[OK]', $output);
		$this->assertFilesAndRemove($files);
	}
}