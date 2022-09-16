<?php

namespace Pragnesh\LaravelPackageHelper\Command;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Pragnesh\LaravelPackageHelper\Exceptions\MethodNotFoundException;

abstract class DemoGeneratorCommand extends Command
{
	use Concerns\InteractsWithIO;
	/**
	 * Reserved names that cannot be used for generation.
	 *
	 * @var string[]
	 */
	protected $reservedNames = [
		'__halt_compiler',
		'abstract',
		'and',
		'array',
		'as',
		'break',
		'callable',
		'case',
		'catch',
		'class',
		'clone',
		'const',
		'continue',
		'declare',
		'default',
		'die',
		'do',
		'echo',
		'else',
		'elseif',
		'empty',
		'enddeclare',
		'endfor',
		'endforeach',
		'endif',
		'endswitch',
		'endwhile',
		'enum',
		'eval',
		'exit',
		'extends',
		'final',
		'finally',
		'fn',
		'for',
		'foreach',
		'function',
		'global',
		'goto',
		'if',
		'implements',
		'include',
		'include_once',
		'instanceof',
		'insteadof',
		'interface',
		'isset',
		'list',
		'match',
		'namespace',
		'new',
		'or',
		'print',
		'private',
		'protected',
		'public',
		'readonly',
		'require',
		'require_once',
		'return',
		'static',
		'switch',
		'throw',
		'trait',
		'try',
		'unset',
		'use',
		'var',
		'while',
		'xor',
		'yield',
		'__CLASS__',
		'__DIR__',
		'__FILE__',
		'__FUNCTION__',
		'__LINE__',
		'__METHOD__',
		'__NAMESPACE__',
		'__TRAIT__',
	];

	/**
	 * The filesystem instance.
	 *
	 * @var \Illuminate\Filesystem\Filesystem
	 */
	protected $files;

	/**
	 * Create a new controller creator command instance.
	 *
	 * @param  \Illuminate\Filesystem\Filesystem  $files
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->files = new Filesystem();
	}

	/**
	 * Get the stub file for the generator.
	 *
	 * @return string
	 */
	abstract protected function getStub();

	/**
	 * Checks whether the given name is reserved.
	 */
	protected function isReservedName(string $name): bool
	{
		$name = strtolower($name);

		return in_array($name, $this->reservedNames);
	}

	/**
	 * Execute the console command.
	 *
	 * @return bool|null
	 *
	 * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
	 */
	public function handle()
	{
		// First we need to ensure that the given name is not a reserved word within the PHP
		// language and that the class name will actually be valid. If it is not valid we
		// can error now and prevent from polluting the filesystem using invalid files.
		if ($this->isReservedName($this->getNameInput())) {
			$this->output->error('The name "' . $this->getNameInput() . '" is reserved by PHP.');
			return false;
		}

		$name = $this->getNameInput();
		$factory = $this->option();

		echo '<pre>';
		var_dump($name, $factory, $this->type);
		echo '</pre>';
		exit;

		/* $name = $this->qualifyClass($this->getNameInput());

		$path = $this->getPath($name);

		// Next, We will check to see if the class already exists. If it does, we don't want
		// to create the class and overwrite the user's code. So, we will bail out so the
		// code is untouched. Otherwise, we will continue generating this class' files.
		if ((!$this->hasOption('force') ||
				!$this->option('force')) &&
			$this->alreadyExists($this->getNameInput())
		) {
			$this->io->error($this->type . ' already exists.');

			return false;
		}

		// Next, we will generate the path to the location where this class' file should get
		// written. Then, we will build the class and make the proper replacements on the
		// stub files so that it gets the correctly formatted namespace and class name.
		$this->makeDirectory($path);

		$this->files->put($path, $this->sortImports($this->buildClass($name)));

		$info = $this->type;

		if (in_array(CreatesMatchingTest::class, class_uses_recursive($this))) {
			if ($this->handleTestCreation($path)) {
				$info .= ' and test';
			}
		}

		$this->io->info($info . ' created successfully.'); */
	}

	/**
	 * Get the desired class name from the input.
	 *
	 * @return string
	 */
	protected function getNameInput()
	{
		return trim($this->argument('name'));
	}

	protected function resolveStubPath(string $type)
	{
		$type = strtolower($type);
		return __DIR__ . "/stubs/$type";
	}

	protected function getStubContent(string $stubpath)
	{
		return file_get_contents($stubpath);
	}

	/**
	 * Execute the console command.
	 *
	 * @param  \Symfony\Component\Console\Input\InputInterface  $input
	 * @param  \Symfony\Component\Console\Output\OutputInterface  $output
	 * @return int
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$this->setInput($input);
		$outstyle = new SymfonyStyle($input, $output);
		$this->setOutput($outstyle);

		if (method_exists($this, 'handle')) {
			return (int) $this->handle();
		} else {
			throw new MethodNotFoundException('Method not found');
		}
		return (int) true;
	}
}