<?php

namespace Pragnesh\LaravelPackageHelper\Helpers;

use ArrayAccess;

class Arr
{
	/**
	 * Determine whether the given value is array accessible.
	 *
	 * @param  mixed  $value
	 * @return bool
	 */
	public static function accessible($value)
	{
		return is_array($value) || $value instanceof ArrayAccess;
	}

	/**
	 * Get an item from an array using "dot" notation.
	 *
	 * @param  \ArrayAccess|array  $array
	 * @param  string|int|null  $key
	 * @param  mixed  $default
	 * @return mixed
	 */
	public static function get($array, $key, $default = null)
	{
		if (!static::accessible($array)) {
			return value($default);
		}

		if (is_null($key)) {
			return $array;
		}

		if (static::exists($array, $key)) {
			return $array[$key];
		}

		if (!str_contains($key, '.')) {
			return $array[$key] ?? value($default);
		}

		foreach (explode('.', $key) as $segment) {
			if (static::accessible($array) && static::exists($array, $segment)) {
				$array = $array[$segment];
			} else {
				return value($default);
			}
		}

		return $array;
	}

	/**
	 * Determine if the given key exists in the provided array.
	 *
	 * @param  \ArrayAccess|array  $array
	 * @param  string|int  $key
	 * @return bool
	 */
	public static function exists($array, $key)
	{
		if ($array instanceof ArrayAccess) {
			return $array->offsetExists($key);
		}

		if (is_float($key)) {
			$key = (string) $key;
		}

		return array_key_exists($key, $array);
	}
}