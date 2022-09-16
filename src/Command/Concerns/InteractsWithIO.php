<?php

namespace Pragnesh\LaravelPackageHelper\Command\Concerns;

use Symfony\Component\Console\Style\OutputStyle;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;

trait InteractsWithIO
{
	protected InputInterface $input;

	protected SymfonyStyle $output;
	/**
	 * Get the value of a command argument.
	 *
	 * @param  string|null  $key
	 * @return array|string|bool|null
	 */
	public function argument($key = null)
	{
		if (is_null($key)) {
			return $this->input->getArguments();
		}
		return $this->input->getArgument($key);
	}

	/**
	 * Determine if the given option is present.
	 *
	 * @param  string  $name
	 * @return bool
	 */
	public function hasOption($name)
	{
		return $this->input->hasOption($name);
	}

	/**
	 * Get the value of a command option.
	 *
	 * @param  string|null  $key
	 * @return string|array|bool|null
	 */
	public function option($key = null)
	{
		if (is_null($key)) {
			return $this->input->getOptions();
		}

		return $this->input->getOption($key);
	}

	/**
	 * Get all of the options passed to the command.
	 *
	 * @return array
	 */
	public function options()
	{
		return $this->option();
	}

	/**
	 * Set the input interface implementation.
	 *
	 * @param  \Symfony\Component\Console\Input\InputInterface  $input
	 * @return void
	 */
	public function setInput(InputInterface $input)
	{
		$this->input = $input;
	}

	/**
	 * Set the output interface implementation.
	 * @return void
	 */
	public function setOutput(OutputStyle $output)
	{
		$this->output = $output;
	}

	/**
	 * Get the output implementation.
	 *
	 * @return \Illuminate\Console\OutputStyle
	 */
	public function getOutput()
	{
		return $this->output;
	}
}