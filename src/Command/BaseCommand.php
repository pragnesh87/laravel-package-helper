<?php

namespace Pragnesh\LaravelPackageHelper\Command;

use OuterIterator;
use Symfony\Component\Filesystem\Filesystem;
use Pragnesh\LaravelPackageHelper\Helpers\Arr;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

abstract class BaseCommand extends Command
{
	protected array $config = [];
	private string $package_namespace = '';

	/**
	 * Get the stub file for the generator.
	 *
	 * @return string
	 */
	abstract protected function getStub(InputInterface $input);

	protected function resolveStubPath($type)
	{
		$type = strtolower($type);
		return file_get_contents(__DIR__ . "/stubs/$type");
	}

	protected function resolveNamespace($type)
	{
		return $this->getPackageNamespace() . '\\' . $this->getConfig('namespace.' . $type);
	}

	protected function resolvePath($type)
	{
		return $this->getConfig('paths.' . $type);
	}

	protected function isConfigExist()
	{
		$filesystem = new Filesystem();

		$file = "config/larapack.php";
		if ($filesystem->exists($file)) {
			$this->loadConfig();
			$this->setPackageNamespace();
			return true;
		}
		return false;
	}

	protected function loadConfig()
	{
		$this->config = require_once("config/larapack.php");
	}

	protected function getConfig($config)
	{
		return Arr::get($this->config, $config);
	}

	protected function setPackageNamespace()
	{
		$this->package_namespace = $this->getConfig('package-namespace');
	}

	protected function getPackageNamespace()
	{
		return $this->package_namespace;
	}

	/* protected function confirm(
		InputInterface $input,
		OutputInterface $output,
		$question = 'File already exist, would you like to overwrite it? (y/N):',
	) {
		$helper = $this->getHelper('question');
		$question = new ConfirmationQuestion(
			$question,
			false,
			'/^(y|j)/i'
		);

		$answer = $helper->ask($input, $output, $question);
		return $answer;
	} */

	protected function writeFile($file, $stubTemplate, SymfonyStyle $io)
	{
		$filesystem = new Filesystem();
		if ($filesystem->exists($file)) {
			if ($io->confirm('File already exist, would you like to overwrite it?', false)) {
				$filesystem->dumpFile($file, $stubTemplate);
				$io->success('File Updated: ' . $file);
			} else {
				$io->warning('did nothing');
			}
		} else {
			$filesystem->dumpFile($file, $stubTemplate);
			$io->success('File Generated: ' . $file);
		}
	}
}