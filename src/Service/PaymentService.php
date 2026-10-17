<?php
/**
 * Vstore - shopping cart plugin for e107.
 * Copyright (c) 2026 Kanonimpresor.
 *
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Vstore\Service;

use Vstore\Support\Catalog;
use Vstore\Support\Result;

/**
 * Builds the Omnipay gateway for a payment method from the plugin preferences.
 */
class PaymentService
{
	/** Settings a gateway cannot work without, by lower-case Omnipay gateway name. */
	const REQUIRED_PREFS = array(
		'paypal_express' => array('username', 'password', 'signature'),
		'paypal_pro'     => array('username', 'password', 'signature'),
		'paypal_rest'    => array('clientId', 'secret'),
		'stripe'         => array('apiKey'),
		'braintree'      => array('merchantId', 'publicKey', 'privateKey'),
	);

	/** @var callable fn(string $className): object, normally Omnipay::create */
	private $factory;

	/** @var callable|null fn(string $message): void */
	private $logger;

	public function __construct($factory, $logger = null)
	{
		$this->factory = $factory;
		$this->logger  = $logger;
	}

	/**
	 * Result codes: GW_CONFIG, GW_LOAD and GW_CREDENTIALS (errors, no gateway), GW_PARAM (warning, gateway returned).
	 * GW_CREDENTIALS means required keys are empty; the detail lists them.
	 * The value holds gateway (object|null), mode (set to 'return' for bank transfers),
	 * bank_details (text to show for bank transfers) and detail (technical reason of a failure).
	 *
	 * @param string $name Gateway key: mollie, bank_transfer, paypal, paypal_rest or an Omnipay package key
	 * @param array  $pref Plugin preferences
	 * @return Result
	 */
	public function load($name, array $pref)
	{
		$gateways = array_replace(Catalog::GATEWAYS, isset($pref['gateways']) && is_array($pref['gateways']) ? $pref['gateways'] : array());
		$value    = array('gateway' => null, 'mode' => '', 'bank_details' => '', 'detail' => '');

		if ($name === 'bank_transfer')
		{
			$value['mode']         = 'return';
			$value['bank_details'] = isset($gateways['bank_transfer']['details']) ? (string) $gateways['bank_transfer']['details'] : '';

			return Result::ok($value);
		}

		if ($name === 'mollie')
		{
			$cfg     = $this->prefsFor('mollie', $pref);
			$keyName = !empty($cfg['testmode']) ? 'api_key_test' : 'api_key_live';

			if (empty($cfg[$keyName]))
			{
				$value['detail'] = ' (mollie_' . $keyName . ')';

				return Result::fail('GW_CREDENTIALS', array($name), $value);
			}

			$gateway = call_user_func($this->factory, 'Vstore\\Payment\\MollieGateway');

			if (!empty($cfg['testmode']))
			{
				$gateway->setApiKey(isset($cfg['api_key_test']) ? $cfg['api_key_test'] : '');
				$gateway->setTestMode(true);
			}
			else
			{
				$gateway->setApiKey(isset($cfg['api_key_live']) ? $cfg['api_key_live'] : '');
			}

			$value['gateway'] = $gateway;

			return Result::ok($value);
		}

		if (empty($gateways[$name]['name']))
		{
			$this->log("Missing prefs for gateway {$name}");

			return Result::fail('GW_CONFIG', array(), $value);
		}

		$missing = $this->missingPrefs($gateways[$name]);

		if ($missing)
		{
			$value['detail'] = ' (' . implode(', ', $missing) . ')';

			return Result::fail('GW_CREDENTIALS', array($name), $value);
		}

		try
		{
			$gateway = call_user_func($this->factory, $gateways[$name]['name']);
		}
		catch (\Exception $e)
		{
			$value['detail'] = $e->getMessage();

			return Result::fail('GW_LOAD', array($name), $value);
		}

		$value['gateway'] = $gateway;
		$result           = Result::ok($value);

		if (empty($gateways[$name]['prefs']))
		{
			$this->log("Gateway {$name} has no prefs");

			return $result;
		}

		foreach ($gateways[$name]['prefs'] as $key => $param)
		{
			try
			{
				$gateway->{'set' . ucfirst($key)}($param);
			}
			catch (\Exception $e)
			{
				$value['detail'] = $e->getMessage();
				$result          = Result::warn('GW_PARAM', array(), $value);
			}
		}

		return $result;
	}

	/** Required settings of the gateway that are empty. */
	private function missingPrefs(array $gateway)
	{
		$key = strtolower($gateway['name']);

		if (!isset(self::REQUIRED_PREFS[$key]))
		{
			return array();
		}

		$prefs   = isset($gateway['prefs']) && is_array($gateway['prefs']) ? $gateway['prefs'] : array();
		$missing = array();

		foreach (self::REQUIRED_PREFS[$key] as $pref)
		{
			if (!isset($prefs[$pref]) || trim((string) $prefs[$pref]) === '')
			{
				$missing[] = $pref;
			}
		}

		return $missing;
	}

	/** Gateway settings: the nested array plus the flat keys such as mollie_api_key_live. */
	private function prefsFor($name, array $pref)
	{
		$result = isset($pref[$name]) && is_array($pref[$name]) ? $pref[$name] : array();

		foreach ($pref as $key => $value)
		{
			if (strpos($key, $name . '_') === 0)
			{
				$result[substr($key, strlen($name) + 1)] = $value;
			}
		}

		return $result;
	}

	private function log($message)
	{
		if ($this->logger)
		{
			call_user_func($this->logger, $message);
		}
	}
}
