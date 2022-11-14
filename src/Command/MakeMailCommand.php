<?php

namespace Pragnesh\LaravelPackageHelper\Command;

use Pragnesh\LaravelPackageHelper\Helpers\Str;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Pragnesh\LaravelPackageHelper\Command\BaseCommand;

#[AsCommand(name: 'make:mail', description: 'Create a new email class')]
class MakeMailCommand extends BaseCommand
{
	protected string $type = 'mail';

	protected function configure(): void
	{
		$this
			->addArgument('name', InputArgument::REQUIRED, 'The name of the mail class')
			->addOption('markdown', 'm', InputOption::VALUE_OPTIONAL, 'Create a new Markdown template for the mailable', false);
	}

	protected function getStub()
	{
		return $this->resolveStubPath(
			$this->option('markdown') !== false
				? 'markdown-mail.stub'
				: 'mail.stub'
		);
	}

	protected function updateStubContent()
	{
		$stub = $this->getStub();
		$namespace = $this->resolveNamespace();

		$replace = [
			'{{ class }}' => $this->getQualifyClassName(),
			'{{ namespace }}' => $namespace,
			'{{ view }}' => 'mail.' . $this->getView(),
		];
		return strtr($stub, $replace);
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function handle()
	{
		if ($this->isReservedName($this->getNameInput())) {
			$this->output->error('The name "' . $this->getNameInput() . '" is reserved by PHP.');
			return false;
		}

		if ($this->option('markdown') !== false) {
			$this->writeMarkdownTemplate();
		}

		$path = $this->resolvePath();
		$stubTemplate = $this->updateStubContent();
		$file = $path . DIRECTORY_SEPARATOR . $this->getQualifyClassName() . '.php';

		$this->writeFile($file, $stubTemplate);

		return true;
	}

	/**
	 * Write the Markdown template for the mailable.
	 *
	 * @return void
	 */
	protected function writeMarkdownTemplate()
	{
		$path = $this->resolvePath('view') . '/mail/' . str_replace('.', '/', $this->getView()) . '.blade.php';

		if (!$this->files->exists(dirname($path))) {
			$this->files->mkdir(dirname($path), 0777, true, true);
		}

		$stubTemplate = $this->resolveStubPath('markdown.stub');

		$this->files->dumpFile($path, $stubTemplate);
	}

	/**
	 * Get the view name.
	 *
	 * @return string
	 */
	protected function getView()
	{
		$view = $this->option('markdown');

		if (!$view) {
			$name = str_replace('\\', '/', $this->getNameInput());

			$collect = explode('/', $name);
			$viewName = [];
			foreach ($collect as $part) {
				$viewName[] = Str::kebab($part);
			}
			$view = 'mail.' . join('.', $viewName);
		}

		return $view;
	}
}