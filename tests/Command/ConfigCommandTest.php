<?php

namespace Pragnesh\LaravelPackageHelper\Tests\Command;

use PHPUnit\Framework\TestCase;
use Pragnesh\LaravelPackageHelper\Command\CreateConfigCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ConfigCommandTest extends TestCase
{
	public function test_config_install_command()
	{
		$application = new Application();
		$application->add(new CreateConfigCommand());

		$command = $application->find('config:install');
		$commandTester = new CommandTester($command);
		$commandTester->execute([]);
		//$commandTester->assertCommandIsSuccessful();

		// the output of the command in the console
		$output = $commandTester->getDisplay();
		unlink('config/larapack.php');
		$this->assertStringContainsString('[OK] File Generated: config/larapack.php', $output);
	}
}