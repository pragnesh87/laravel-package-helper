<?php

namespace Pragnesh\LaravelPackageHelper\Tests\Command;

use Pragnesh\LaravelPackageHelper\Tests\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Pragnesh\LaravelPackageHelper\Command\MakeRuleCommand;

class RuleCommandTest extends TestCase
{
	public function test_rule_command()
	{
		$files = ['appTest/src/Rules/DemoRule.php'];
		$this->removeFiles($files);
		$this->application->add(new MakeRuleCommand);

		$command = $this->application->find('make:rule');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'name' => 'DemoRule'
		]);

		// the output of the command in the console
		$output = $commandTester->getDisplay();
		$this->assertStringContainsString('[OK]', $output);
		$this->assertFilesAndRemove($files);
	}
}