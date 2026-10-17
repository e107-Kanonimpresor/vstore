<?php
/**
 * Vstore - shopping cart plugin for e107.
 * Copyright (c) 2026 Kanonimpresor.
 *
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Vstore\Support;

/**
 * National tax numbers, checked without asking any service.
 */
final class TaxNumber
{
	/** Prefixes a Portuguese NIF can start with (individuals, companies, public bodies, estates, non-residents...). */
	const PT_PREFIXES = array('1', '2', '3', '45', '5', '6', '70', '71', '72', '74', '75', '77', '78', '79', '8', '90', '91', '98', '99');

	/**
	 * Does the number follow the rules of its country? Countries without a rule here accept any number.
	 *
	 * @param string $country ISO 3166-1 alpha-2
	 * @param string $number  With or without the country prefix and separators
	 * @return bool
	 */
	public static function isValid($country, $number)
	{
		$country = strtoupper(trim((string) $country));
		$number  = strtoupper(preg_replace('/[\s.\-\/]/', '', (string) $number));

		if ($country !== '' && strpos($number, $country) === 0)
		{
			$number = substr($number, strlen($country));
		}

		return $country === 'PT' ? self::isPortugueseNif($number) : true;
	}

	/** Nine digits, a known first digit or pair and the mod 11 check digit. */
	public static function isPortugueseNif($nif)
	{
		if (!preg_match('/^\d{9}$/', (string) $nif))
		{
			return false;
		}

		$known = false;
		foreach (self::PT_PREFIXES as $prefix)
		{
			if (strpos($nif, $prefix) === 0)
			{
				$known = true;
				break;
			}
		}

		if (!$known)
		{
			return false;
		}

		$sum = 0;
		for ($i = 0; $i < 8; $i++)
		{
			$sum += (int) $nif[$i] * (9 - $i);
		}

		$check = 11 - ($sum % 11);

		return (int) $nif[8] === ($check >= 10 ? 0 : $check);
	}
}
