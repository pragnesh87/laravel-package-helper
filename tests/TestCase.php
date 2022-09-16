<?php

namespace Pragnesh\LaravelPackageHelper\Tests;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use PHPUnit\Framework\TestCase as FrameworkTestCase;
use Pragnesh\LaravelPackageHelper\Command\CreateConfigCommand;

class TestCase extends FrameworkTestCase
{
	public Application $application;
	/**
	 * Setup the test environment.
	 *
	 * @return void
	 */
	public function setUp(): void
	{
		parent::setUp();
		$this->application = new Application('Test Application');
		$this->runConfigInstall();
	}

	public function runConfigInstall()
	{
		$this->application->add(new CreateConfigCommand());
		$command = $this->application->find('config:install');
		$commandTester = new CommandTester($command);
		$commandTester->execute([]);
	}

	protected function tearDown(): void
	{
		parent::tearDown();
		unlink('config/larapack.php');
	}
}