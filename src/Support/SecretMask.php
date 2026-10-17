<?php
/**
 * Vstore - shopping cart plugin for e107.
 * Copyright (c) 2026 Kanonimpresor.
 *
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Vstore\Support;

/**
 * Hides gateway credentials in the text e107 keeps in its admin log after a settings change.
 */
final class SecretMask
{
	const MASK = '********';

	/** Same pattern as a SQL LIKE condition to find the log rows worth cleaning. */
	const KEYWORDS = array('api_key', 'apikey', 'password', 'secret', 'signature', 'privatekey', 'private_key');

	public static function maskLogText($text)
	{
		$pattern = '/(\[[A-Za-z0-9_]*(?:' . implode('|', array_map('preg_quote', self::KEYWORDS)) . ')[A-Za-z0-9_]*\] =&gt; )(?!' . preg_quote(self::MASK, '/') . ')(.+?)(?=\[!br!\])/i';

		return preg_replace($pattern, '$1' . self::MASK, (string) $text);
	}
}
