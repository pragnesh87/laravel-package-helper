<?php

namespace Pragnesh\LaravelPackageHelper\Tests\Command;

use Pragnesh\LaravelPackageHelper\Tests\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Pragnesh\LaravelPackageHelper\Command\MakeNotificationCommand;

class NotificationCommandTest extends TestCase
{
	public function test_notification_command()
	{
		$files = ['appTest/src/Notifications/DemoNotification.php'];
		$this->removeFiles($files);
		$this->application->add(new MakeNotificationCommand);

		$command = $this->application->find('make:notification');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'name' => 'DemoNotification'
		]);

		// the output of the command in the console
		$output = $commandTester->getDisplay();
		$this->assertStringContainsString('[OK]', $output);
		$this->assertFilesAndRemove($files);
	}

	public function test_notification_markdown_option_command()
	{
		$files = [
			'appTest/src/Notifications/DemoNotification.php',
			'appTest/resources/views/demo-notify.blade.php',
		];
		$this->removeFiles($files);
		$this->application->add(new MakeNotificationCommand);

		$command = $this->application->find('make:notification');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'name' => 'DemoNotification',
			'--markdown' => 'demo-notify'
		]);

		// the output of the command in the console
		$output = $commandTester->getDisplay();
		$this->assertStringContainsString('[OK]', $output);
		$this->assertFilesAndRemove($files);
	}
}