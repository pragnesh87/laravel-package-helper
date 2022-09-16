<?php

namespace Pragnesh\LaravelPackageHelper\Helpers;

class Pluralizer
{
	/**
	 * Uncountable non-nouns word forms.
	 *
	 * Contains words supported by Doctrine/Inflector/Rules/English/Uninflected.php
	 *
	 * @var string[]
	 */
	public static $uncountable = [
		'cattle',
		'kin',
		'recommended',
		'related',
	];

	/**
	 * Get the plural form of an English word.
	 *
	 * @param  string  $value
	 * @param  int|array|\Countable  $count
	 * @return string
	 */
	public static function plural($value, $count = 2)
	{
		if (is_countable($count)) {
			$count = count($count);
		}

		if ((int) abs($count) === 1 || static::uncountable($value) || preg_match('/^(.*)[A-Za-z0-9\x{0080}-\x{FFFF}]$/u', $value) == 0) {
			return $value;
		}

		$plural = static::inflector()->pluralize($value);

		return static::matchCase($plural, $value);
	}

	/**
	 * Get the singular form of an English word.
	 *
	 * @param  string  $value
	 * @return string
	 */
	public static function singular($value)
	{
		return Inflector::singularize($value);
	}

	/**
	 * Attempt to match the case on two strings.
	 *
	 * @param  string  $value
	 * @param  string  $comparison
	 * @return string
	 */
	protected static function matchCase($value, $comparison)
	{
		$functions = ['mb_strtolower', 'mb_strtoupper', 'ucfirst', 'ucwords'];

		foreach ($functions as $function) {
			if ($function($comparison) === $comparison) {
				return $function($value);
			}
		}

		return $value;
	}

	/**
	 * Get the inflector instance.
	 *
	 * @return \Doctrine\Inflector\Inflector
	 */
	public static function inflector()
	{
		if (is_null(static::$inflector)) {
			static::$inflector = InflectorFactory::createForLanguage(static::$language)->build();
		}

		return static::$inflector;
	}

	/**
	 * Determine if the given value is uncountable.
	 *
	 * @param  string  $value
	 * @return bool
	 */
	protected static function uncountable($value)
	{
		return in_array(strtolower($value), static::$uncountable);
	}
}