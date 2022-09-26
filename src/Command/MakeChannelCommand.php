<?php

namespace Pragnesh\LaravelPackageHelper\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Pragnesh\LaravelPackageHelper\Command\BaseCommand;

#[AsCommand(name: 'make:channel', description: 'Create a new channel class')]
class MakeChannelCommand extends BaseCommand
{
	protected string $type = 'channel';

	protected function configure(): void
	{
		$this
			->addArgument('name', InputArgument::REQUIRED, 'The name of the channel class');
	}

	protected function getStub()
	{
		return $this->resolveStubPath('channel.stub');
	}

	protected function updateStubContent()
	{
		$stub = $this->getStub();
		$namespace = $this->resolveNamespace();

		$replace = [
			'{{ class }}' => $this->getQualifyClassName(),
			'{{ namespace }}' => $namespace,
			'{{ namespacedUserModel }}' => 'App\Models\User',
			'{{ userModel }}' => 'User'
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

		$path = $this->resolvePath();
		$stubTemplate = $this->updateStubContent();
		$file = $path . DIRECTORY_SEPARATOR . $this->getNameInput() . '.php';

		$this->writeFile($file, $stubTemplate);

		return true;
	}
}