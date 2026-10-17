<?php
/**
 * Vstore - shopping cart plugin for e107.
 * Copyright (c) 2026 Kanonimpresor.
 *
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Vstore\Support;

final class ResultTranslator
{
	/**
	 * Message of a Result in the current language (constant LAN_VSTORE_MSG_<CODE>).
	 * Placeholders may be sprintf() style (%s) or e107 lanVars style ([x], [y], [z]).
	 * Falls back to the code itself when the constant is not defined.
	 */
	public static function text(Result $result, $prefix = 'LAN_VSTORE_MSG_')
	{
		$name   = $prefix . $result->code();
		$text   = defined($name) ? constant($name) : $result->code();
		$params = array_values($result->params());

		if (!$params)
		{
			return $text;
		}

		if (strpos($text, '[x]') !== false)
		{
			foreach (array('x', 'y', 'z') as $i => $key)
			{
				if (isset($params[$i]))
				{
					$text = str_replace('[' . $key . ']', $params[$i], $text);
				}
			}

			return $text;
		}

		return vsprintf($text, $params);
	}
}
