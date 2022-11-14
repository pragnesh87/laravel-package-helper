<?php

namespace Pragnesh\LaravelPackageHelper\Helpers;

use Closure;
use Pragnesh\LaravelPackageHelper\Helpers\Inflector;

class Str
{
	/**
	 * The cache of snake-cased words.
	 *
	 * @var array
	 */
	protected static $snakeCache = [];

	/**
	 * The cache of studly-cased words.
	 *
	 * @var array
	 */
	protected static $studlyCache = [];

	/**
	 * Return the default value of the given value.
	 *
	 * @param  mixed  $value
	 * @return mixed
	 */
	public static function value($value, ...$args)
	{
		return $value instanceof Closure ? $value(...$args) : $value;
	}

	/**
	 * Convert a value to studly caps case.
	 *
	 * @param  string  $value
	 * @return string
	 */
	public static function studly($value)
	{
		$key = $value;

		if (isset(static::$studlyCache[$key])) {
			return static::$studlyCache[$key];
		}

		$words = explode(' ', static::replace(['-', '_'], ' ', $value));

		$studlyWords = array_map(fn ($word) => static::ucfirst($word), $words);

		return static::$studlyCache[$key] = implode($studlyWords);
	}

	/**
	 * Replace the given value in the given string.
	 *
	 * @param  string|string[]  $search
	 * @param  string|string[]  $replace
	 * @param  string|string[]  $subject
	 * @return string
	 */
	public static function replace($search, $replace, $subject)
	{
		return str_replace($search, $replace, $subject);
	}

	/**
	 * Make a string's first character uppercase.
	 *
	 * @param  string  $string
	 * @return string
	 */
	public static function ucfirst($string)
	{
		return static::upper(static::substr($string, 0, 1)) . static::substr($string, 1);
	}

	/**
	 * Returns the portion of the string specified by the start and length parameters.
	 *
	 * @param  string  $string
	 * @param  int  $start
	 * @param  int|null  $length
	 * @return string
	 */
	public static function substr($string, $start, $length = null)
	{
		return mb_substr($string, $start, $length, 'UTF-8');
	}

	/**
	 * Convert the given string to upper-case.
	 *
	 * @param  string  $value
	 * @return string
	 */
	public static function upper($value)
	{
		return mb_strtoupper($value, 'UTF-8');
	}

	/**
	 * Convert a string to snake case.
	 *
	 * @param  string  $value
	 * @param  string  $delimiter
	 * @return string
	 */
	public static function snake($value, $delimiter = '_')
	{
		$key = $value;

		if (isset(static::$snakeCache[$key][$delimiter])) {
			return static::$snakeCache[$key][$delimiter];
		}

		if (!ctype_lower($value)) {
			$value = preg_replace('/\s+/u', '', ucwords($value));

			$value = static::lower(preg_replace('/(.)(?=[A-Z])/u', '$1' . $delimiter, $value));
		}

		return static::$snakeCache[$key][$delimiter] = $value;
	}

	/**
	 * Convert the given string to lower-case.
	 *
	 * @param  string  $value
	 * @return string
	 */
	public static function lower($value)
	{
		return mb_strtolower($value, 'UTF-8');
	}

	/**
	 * Get the singular form of an English word.
	 *
	 * @param  string  $value
	 * @return string
	 */
	public static function singular($value)
	{
		return Pluralizer::singular($value);
	}

	/**
	 * Get the plural form of an English word.
	 *
	 * @param  string  $value
	 * @param  int|array|\Countable  $count
	 * @return string
	 */
	public static function plural($value, $count = 2)
	{
		return Inflector::pluralize($value);
	}

	/**
	 * Pluralize the last word of an English, studly caps case string.
	 *
	 * @param  string  $value
	 * @param  int|array|\Countable  $count
	 * @return string
	 */
	public static function pluralStudly($value, $count = 2)
	{
		$parts = preg_split('/(.)(?=[A-Z])/u', $value, -1, PREG_SPLIT_DELIM_CAPTURE);

		$lastWord = array_pop($parts);

		return implode('', $parts) . self::plural($lastWord, $count);
	}

	/**
	 * Convert a string to kebab case.
	 *
	 * @param  string  $value
	 * @return string
	 */
	public static function kebab($value)
	{
		return static::snake($value, '-');
	}
}