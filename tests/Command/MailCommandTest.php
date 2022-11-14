<?php

namespace Pragnesh\LaravelPackageHelper\Tests\Command;

use Pragnesh\LaravelPackageHelper\Tests\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Pragnesh\LaravelPackageHelper\Command\MakeMailCommand;

class MailCommandTest extends TestCase
{
	public function test_mail_command()
	{
		$files = [
			'appTest/src/Mail/DemoMail.php',
		];

		$this->removeFiles($files);
		$this->application->add(new MakeMailCommand);

		$command = $this->application->find('make:mail');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'name' => 'DemoMail'
		]);

		// the output of the command in the console
		$output = $commandTester->getDisplay();
		$this->assertStringContainsString('[OK]', $output);
		$this->assertFilesAndRemove($files);
	}

	public function test_mail_markdown_option_command()
	{
		$files = [
			'appTest/src/Mail/DemoMail.php',
			'appTest/resources/views/mail/demo-mail.blade.php',
		];
		$this->removeFiles($files);
		$this->application->add(new MakeMailCommand);

		$command = $this->application->find('make:mail');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'name' => 'DemoMail',
			'--markdown' => 'demo-mail'
		]);

		// the output of the command in the console
		$output = $commandTester->getDisplay();
		$this->assertStringContainsString('[OK]', $output);
		$this->assertFilesAndRemove($files);
	}
}