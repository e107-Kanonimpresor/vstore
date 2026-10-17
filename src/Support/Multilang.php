<?php

namespace Vstore\Support;

/**
 * Texts the admin area stores per language (language name => text).
 */
final class Multilang
{
	/**
	 * Text for a language; when it has none, the first one that exists in another language.
	 *
	 * @param array|string|null $values
	 * @param string            $language e107 language name, e.g. Spanish
	 * @param string            $default  returned when no language has a text
	 * @return string
	 */
	public static function pick($values, $language, $default = '')
	{
		if (!is_array($values))
		{
			return is_scalar($values) && (string) $values !== '' ? (string) $values : $default;
		}

		if (isset($values[$language]) && is_scalar($values[$language]) && (string) $values[$language] !== '')
		{
			return (string) $values[$language];
		}

		foreach ($values as $text)
		{
			if (is_scalar($text) && (string) $text !== '')
			{
				return (string) $text;
			}
		}

		return $default;
	}

	/**
	 * Saving the additional checkout fields posts only the texts of the language the admin is using:
	 * keep the texts the other languages already have.
	 *
	 * @param array $old Fields as stored
	 * @param array $new Fields as posted
	 * @return array
	 */
	public static function mergeFieldTexts(array $old, array $new)
	{
		foreach ($new as $i => $field)
		{
			foreach (array('caption', 'placeholder') as $key)
			{
				$before = isset($old[$i][$key]) && is_array($old[$i][$key]) ? $old[$i][$key] : array();
				$posted = isset($field[$key]) && is_array($field[$key]) ? $field[$key] : array();

				$new[$i][$key] = array_merge($before, $posted);
			}
		}

		return $new;
	}
}
