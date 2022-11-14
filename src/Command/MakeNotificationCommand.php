<?php

namespace Pragnesh\LaravelPackageHelper\Command;

use Pragnesh\LaravelPackageHelper\Helpers\Str;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Pragnesh\LaravelPackageHelper\Command\BaseCommand;

#[AsCommand(name: 'make:notification', description: 'Create a new notification class')]
class MakeNotificationCommand extends BaseCommand
{
	protected string $type = 'notification';

	protected function configure(): void
	{
		$this
			->addArgument('name', InputArgument::REQUIRED, 'The name of the notification class')
			->addOption('markdown', 'm', InputOption::VALUE_OPTIONAL, 'Create a new Markdown template for the notification');
	}

	protected function getStub()
	{
		return $this->option('markdown')
			? $this->resolveStubPath('markdown-notification.stub')
			: $this->resolveStubPath('notification.stub');
	}

	protected function updateStubContent()
	{
		$stub = $this->getStub();
		$namespace = $this->resolveNamespace();

		$replace = [
			'{{ class }}' => $this->getQualifyClassName(),
			'{{ namespace }}' => $namespace,
			'{{ view }}' => $this->option('markdown')
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

		if ($this->option('markdown')) {
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
		$path = $this->resolvePath('view') . '/' . str_replace('.', '/', $this->option('markdown')) . '.blade.php';

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