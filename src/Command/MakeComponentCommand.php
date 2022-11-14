<?php

namespace Pragnesh\LaravelPackageHelper\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Pragnesh\LaravelPackageHelper\Command\BaseCommand;
use Pragnesh\LaravelPackageHelper\Helpers\Str;

#[AsCommand(name: 'make:component', description: 'Create a new view component class')]
class MakeComponentCommand extends BaseCommand
{
	protected string $type = 'component';
	private string $className = '';
	private array $dirpart = [];

	protected function configure(): void
	{
		$this
			->addArgument('name', InputArgument::REQUIRED, 'The name of the class')
			->addOption('inline', null, InputOption::VALUE_NONE, 'Create a component that renders an inline view')
			->addOption('view', null, InputOption::VALUE_NONE, 'Create an anonymous component with only a view');
	}

	protected function getStub()
	{
		return $this->resolveStubPath('view-component.stub');
	}

	protected function updateStubContent()
	{
		$stub = $this->getStub();
		$namespace = $this->resolveNamespace();

		if (!empty($this->dirpart)) {
			$namespace = rtrim($namespace . '\\' . join('\\', $this->dirpart), '\\');
		}

		$replace = [
			'{{ class }}' => $this->className,
			'{{ namespace }}' => $namespace,
		];

		if ($this->option('inline')) {
			$replace['{{ view }}'] = "<<<'blade'\n<div>\n    <!-- Tomorrow never comes until it\'s too late -->\n</div>\nblade";
		} else {
			$replace['{{ view }}'] = 'view(\'components.' . $this->getView() . '\')';
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

		$name = str_replace('\\', '/', $this->getNameInput());
		$collect = explode('/', $name);
		foreach ($collect as $part) {
			$this->dirpart[] = ucfirst($part);
		}
		$this->className = array_pop($this->dirpart);

		if ($this->option('view')) {
			$this->writeView();

			$this->output->success('View Generated: ');
			return true;
		}

		if (!$this->option('inline')) {
			$this->writeView();
		}

		$path = rtrim($this->resolvePath() . '/' . join('/', $this->dirpart), '/');
		$stubTemplate = $this->updateStubContent();
		$file = $path . DIRECTORY_SEPARATOR . $this->className . '.php';

		$this->writeFile($file, $stubTemplate);

		return true;
	}

	/**
	 * Write the view for the component.
	 *
	 * @param  callable|null  $onSuccess
	 * @return void
	 */
	protected function writeView($onSuccess = null)
	{
		$path = $this->resolvePath('view') . '/' . str_replace('.', '/', 'components.' . $this->getView()) . '.blade.php';

		if (!$this->files->exists(dirname($path))) {
			$this->files->mkdir(dirname($path), 0777, true, true);
		}

		if ($this->files->exists($path)) {
			$this->output->error('View already exists.');
			return;
		}

		file_put_contents(
			$path,
			'<div>
    <!-- Tomorrow never comes until it\'s too late -->
</div>'
		);
	}

	/**
	 * Get the view name relative to the components directory.
	 *
	 * @return string view
	 */
	protected function getView()
	{
		$name = str_replace('\\', '/', $this->getNameInput());
		$collect = explode('/', $name);
		$viewName = [];
		foreach ($collect as $part) {
			$viewName[] = Str::kebab($part);
		}
		return join('.', $viewName);
	}
}