<?php

namespace Pragnesh\LaravelPackageHelper\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Pragnesh\LaravelPackageHelper\Command\BaseCommand;

#[AsCommand(name: 'make:listener', description: 'Create a new event listener class')]
class MakeListenerCommand extends BaseCommand
{
	protected string $type = 'listener';

	protected function configure(): void
	{
		$this
			->addArgument('name', InputArgument::REQUIRED, 'The name of the listener class')
			->addOption('event', 'e', InputOption::VALUE_OPTIONAL, 'The event class being listened for')
			->addOption('queued', null, InputOption::VALUE_NONE, 'Indicates the event listener should be queued');
	}

	protected function getStub()
	{
		if ($this->option('queued')) {
			return $this->option('event')
				? $this->resolveStubPath('listener-queued.stub')
				: $this->resolveStubPath('listener-queued-duck.stub');
		}

		return $this->option('event')
			? $this->resolveStubPath('listener.stub')
			: $this->resolveStubPath('listener-duck.stub');
	}

	protected function updateStubContent()
	{
		$stub = $this->getStub();
		$namespace = $this->resolveNamespace();

		$replace = [
			'{{ class }}' => $this->getQualifyClassName(),
			'{{ namespace }}' => $namespace,
		];
		if ($this->option('event')) {
			$replace = array_merge($replace, $this->replaceEvents());
		}
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

		$path = $this->resolvePath();
		$stubTemplate = $this->updateStubContent();
		$file = $path . DIRECTORY_SEPARATOR . $this->getQualifyClassName() . '.php';

		$this->writeFile($file, $stubTemplate);

		return true;
	}

	private function replaceEvents()
	{
		$event = ucfirst($this->option('event'));
		return [
			'{{ eventNamespace }}' => $this->resolveEventNameSpace() . '\\' . $event,
			"{{ event }}" => $event,
		];
	}

	private function resolveEventNameSpace()
	{
		return $this->getPackageNamespace() . '\\' . $this->getConfig('namespace.event');
	}
}