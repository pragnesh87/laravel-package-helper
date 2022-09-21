<?php

namespace Pragnesh\LaravelPackageHelper\Tests;


use Symfony\Component\Console\Application;
use Symfony\Component\Filesystem\Filesystem;
use PHPUnit\Framework\TestCase as FrameworkTestCase;

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
	}


	protected function tearDown(): void
	{
		parent::tearDown();
	}

	protected function removeFiles(array $files)
	{
		$fileSystem = new Filesystem();
		$fileSystem->remove($files);
	}

	protected function assertFilesExists(array $files)
	{
		foreach ($files as $file) {
			$this->assertFileExists($file);
		}
	}

	protected function assertFilesAndRemove(array $files)
	{
		$this->assertFilesExists($files);
		$this->removeFiles($files);
	}
}