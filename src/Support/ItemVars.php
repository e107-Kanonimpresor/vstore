<?php

namespace Vstore\Support;

/**
 * Item variation strings. The DB format is "id1,id2|value1,value2".
 */
final class ItemVars
{
	/** @return string */
	public static function toDb($vars)
	{
		if (!is_array($vars))
		{
			return '';
		}

		return implode(',', array_keys($vars)) . '|' . implode(',', array_values($vars));
	}

	/** @return array|null id => value, null when the string is empty or malformed */
	public static function toArray($string)
	{
		if (empty($string) || strpos($string, '|') === false)
		{
			return null;
		}

		list($keys, $values) = explode('|', $string);

		return array_combine(explode(',', $keys), explode(',', $values));
	}

	/**
	 * Accepts id => value or a list of "id-value" strings (as posted by the product form).
	 *
	 * @return array|false id => value sorted by id, false when $vars is not an array
	 */
	public static function normalize($vars)
	{
		if (!is_array($vars))
		{
			return false;
		}

		$result = array();

		if (array_key_exists(0, $vars))
		{
			foreach ($vars as $value)
			{
				list($id, $name) = explode('-', $value, 2);
				$result[$id] = $name;
			}
		}
		else
		{
			$result = $vars;
		}

		ksort($result);

		return $result;
	}
}
