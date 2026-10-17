<?php
/**
 * Vstore - shopping cart plugin for e107.
 * Copyright (c) 2026 Kanonimpresor.
 *
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Vstore\Support;

/**
 * A text kept in several languages (language name => text), such as the captions of the additional checkout fields.
 */
final class Translatable
{
	/**
	 * Text for a language; when it is empty, the English one, then the first one that has text.
	 *
	 * @param array|string|null $values
	 * @param string            $language e_LANGUAGE
	 * @param string            $default
	 * @return string
	 */
	public static function pick($values, $language, $default = '')
	{
		if (!is_array($values))
		{
			return is_scalar($values) && (string) $values !== '' ? (string) $values : $default;
		}

		foreach (array($language, 'English') as $key)
		{
			if (isset($values[$key]) && is_scalar($values[$key]) && trim((string) $values[$key]) !== '')
			{
				return (string) $values[$key];
			}
		}

		foreach ($values as $value)
		{
			if (is_scalar($value) && trim((string) $value) !== '')
			{
				return (string) $value;
			}
		}

		return $default;
	}

	/**
	 * Join the texts typed for the current language into the ones already stored, so saving in one language keeps the others.
	 *
	 * @param array $stored
	 * @param array $posted
	 * @return array
	 */
	public static function merge($stored, $posted)
	{
		$merged = is_array($stored) ? $stored : array();

		foreach ((array) $posted as $language => $text)
		{
			$merged[$language] = $text;
		}

		return $merged;
	}
}
