<?php

namespace Pragnesh\LaravelPackageHelper\Command;

use Symfony\Component\Filesystem\Filesystem;
use Pragnesh\LaravelPackageHelper\Helpers\Arr;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Pragnesh\LaravelPackageHelper\Exceptions\MethodNotFoundException;

abstract class BaseCommand extends Command
{
	protected InputInterface $input;

	protected SymfonyStyle $output;

	protected array $config = [];

	private string $package_namespace = '';

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

	abstract protected function getStub();
	abstract protected function handle();

	public function option($key = null)
	{
		if (is_null($key)) {
			return $this->input->getOptions();
		}

		return $this->input->getOption($key);
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
		$this->input = $input;
		$this->output = new SymfonyStyle($input, $output);

		if (!$this->isConfigExist() && $this->getType() != 'config') {
			$this->output->error(['Configuration file not found.', 'Please run `./vendor/bin/larapack config:install` command']);
			return Command::FAILURE;
		}

		if (method_exists($this, 'handle')) {
			return (int) $this->handle();
		} else {
			throw new MethodNotFoundException('Method not found');
		}
		return (int) false;
	}

	protected function isReservedName(string $name): bool
	{
		$name = strtolower($name);

		return in_array($name, $this->reservedNames);
	}

	protected function getNameInput()
	{
		return $this->input->getArgument('name');
	}

	protected function resolveStubPath($type)
	{
		$type = strtolower($type);
		return file_get_contents(__DIR__ . "/stubs/$type");
	}

	protected function resolveNamespace(string $type = '')
	{
		if (empty($type)) {
			$type = $this->getType();
		}

		if ($this->getConfig('namespace.' . $type)) {
			return $this->getPackageNamespace() . '\\' . $this->getConfig('namespace.' . $type);
		}

		return $this->getPackageNamespace();
	}

	protected function resolvePath(string $type = '')
	{
		if (empty($type)) {
			$type = $this->getType();
		}

		if ($this->getConfig('paths.' . $type)) {
			return $this->getConfig('paths.' . $type);
		}

		return 'src';
	}

	protected function loadConfig()
	{
		$this->config = require("config/larapack.php");
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

	protected function isConfigExist()
	{
		$file = "config/larapack.php";
		if ($this->files->exists($file)) {
			$this->loadConfig();
			$this->setPackageNamespace();
			return true;
		}
		return false;
	}

	protected function setType($type)
	{
		$this->type = $type;
	}

	protected function getType()
	{
		return $this->type;
	}

	protected function writeFile($file, $stubTemplate)
	{
		if ($this->files->exists($file)) {
			if ($this->output->confirm('File already exist, would you like to overwrite it?', false)) {
				$this->files->dumpFile($file, $stubTemplate);
				$this->output->success('File Updated: ' . $file);
			} else {
				$this->output->warning('did nothing');
			}
		} else {
			$this->files->dumpFile($file, $stubTemplate);
			$this->output->success('File Generated: ' . $file);
		}
	}

	protected function getQualifyClassName()
	{
		$class = $this->getNameInput();
		$type = $this->getType();

		$replace = [
			$type => '',
			ucfirst($type) => '',
		];
		$class = strtr($class, $replace);
		return ucfirst($class) . ucfirst($type);
	}

	protected function getQualifyModelName(string $model): string
	{
		$class = $model;

		$replace = [
			'Factory' => '',
			'Controller' => '',
			'Seeder' => '',
			'Policy' => ''
		];
		$class = strtr($class, $replace);
		return ucfirst($class);
	}

	protected function getModelPath()
	{
		return $this->getConfig('paths.model');
	}

	protected function getModelNamespace()
	{
		return $this->getConfig('namespace.model');
	}
}