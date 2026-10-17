<?php
/**
 * Vstore - shopping cart plugin for e107.
 * Copyright (c) 2026 Kanonimpresor.
 *
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Vstore\Support;

/**
 * Phone numbers in the international E.164 format (+351912345678).
 */
final class PhoneNumber
{
	/** ISO 3166-1 alpha-2 => country calling code, for the countries the shop is used in. */
	const CALLING_CODES = array(
		'AD' => '376', 'AT' => '43', 'AO' => '244', 'BE' => '32', 'BG' => '359', 'BR' => '55', 'CA' => '1', 'CH' => '41',
		'CV' => '238', 'CY' => '357', 'CZ' => '420', 'DE' => '49', 'DK' => '45', 'EE' => '372', 'ES' => '34', 'FI' => '358',
		'FR' => '33', 'GB' => '44', 'GR' => '30', 'HR' => '385', 'HU' => '36', 'IE' => '353', 'IS' => '354', 'IT' => '39',
		'LI' => '423', 'LT' => '370', 'LU' => '352', 'LV' => '371', 'MC' => '377', 'MT' => '356', 'MZ' => '258', 'NL' => '31',
		'NO' => '47', 'PL' => '48', 'PT' => '351', 'RO' => '40', 'SE' => '46', 'SI' => '386', 'SK' => '421', 'ST' => '239',
		'US' => '1',
	);

	/**
	 * @param string $phone   As typed by the customer: +351 912 345 678, 00351912345678 or 912345678
	 * @param string $country ISO country code of the customer, used when the number has no country code
	 * @return string|null E.164 number, null when it cannot be told safely
	 */
	public static function toE164($phone, $country = '')
	{
		$phone = trim((string) $phone);

		if ($phone === '')
		{
			return null;
		}

		$digits = preg_replace('/\D+/', '', $phone);

		if (strpos($phone, '+') === 0)
		{
			// already international
		}
		elseif (strpos($digits, '00') === 0)
		{
			$digits = substr($digits, 2);
		}
		else
		{
			$country = strtoupper(trim((string) $country));

			if (!isset(self::CALLING_CODES[$country]))
			{
				return null;
			}

			$national = ($country === 'IT') ? $digits : ltrim($digits, '0');
			$digits   = self::CALLING_CODES[$country] . $national;
		}

		return (strlen($digits) >= 8 && strlen($digits) <= 15 && $digits[0] !== '0') ? '+' . $digits : null;
	}

	/** Portuguese mobile numbers (the ones MB WAY works with): +351 9 followed by 8 digits. */
	public static function isPortugueseMobile($e164)
	{
		return (bool) preg_match('/^\+3519\d{8}$/', (string) $e164);
	}
}
