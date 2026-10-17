<?php

namespace Vstore\Service;

use Vstore\Support\Result;

class TaxService
{
	/** Substitutes for tax classes a country may not define (e.g. Germany has no reduced2). */
	const CLASS_FALLBACKS = array(
		'reduced'       => array('reduced2', 'reduced1', 'super_reduced'),
		'reduced1'      => array('reduced', 'super_reduced', 'reduced2'),
		'reduced2'      => array('reduced', 'reduced1', 'super_reduced'),
		'super_reduced' => array('reduced', 'reduced1', 'super_reduced'),
	);

	/** @var object object with getRateForCountry(string $country, string $level): float (percent) */
	private $rates;

	/** @var VatService */
	private $vat;

	/** @var callable|null fn(string $message): void */
	private $logger;

	public function __construct($rates, VatService $vat, $logger = null)
	{
		$this->rates  = $rates;
		$this->vat    = $vat;
		$this->logger = $logger;
	}

	/**
	 * Tax rate as decimal (0.21 for 21%). Result code TAX_CLASS (error) when the rate cannot be resolved.
	 *
	 * @param string $taxClass        none, reduced, reduced1, reduced2, super_reduced, standard, parking
	 * @param string $customerCountry ISO 3166-1 alpha-2 from the billing form (never from IP)
	 * @param array  $pref            Plugin preferences; tax_classes must already be unserialized
	 * @param string $vatId           Customer VAT ID, enables reverse charge inside the EU
	 * @return Result
	 */
	public function rate($taxClass, $customerCountry, array $pref, $vatId = '')
	{
		if (empty($pref['tax_calculate']))
		{
			return Result::ok(0.0);
		}

		$taxClass = strtolower($taxClass ?: 'standard');

		if ($taxClass === 'none')
		{
			return Result::ok(0.0);
		}

		$business = strtoupper(isset($pref['tax_business_country']) ? $pref['tax_business_country'] : '');
		$customer = strtoupper($customerCountry ?: $business);

		if ($customer === $business)
		{
			return Result::ok($this->localRate($taxClass, isset($pref['tax_classes']) ? $pref['tax_classes'] : null));
		}

		$countries = new \Ibericode\Vat\Countries();

		if (!$countries->isCountryCodeInEU($business) || !$countries->isCountryCodeInEU($customer))
		{
			$this->log('Business or customer outside the EU, no tax');

			return Result::ok(0.0);
		}

		if ($this->isReverseCharge($customer, $pref, $vatId))
		{
			$this->log('Reverse charge B2B, vat_id ' . $vatId);

			return Result::ok(0.0);
		}

		if (!empty($pref['tax_origin_vat']))
		{
			// Below the EU distance-selling threshold the shop may charge the tax of its own country to consumers
			$this->log('Origin VAT for an EU consumer');

			return Result::ok($this->localRate($taxClass, isset($pref['tax_classes']) ? $pref['tax_classes'] : null));
		}

		$mapped = $this->mapClass($taxClass, $customer);

		try
		{
			return Result::ok($this->rates->getRateForCountry($customer, $mapped) / 100.0);
		}
		catch (\Exception $e)
		{
			$this->log('Tax rate exception: ' . $e->getMessage());

			return Result::fail('TAX_CLASS', array(), 0.0);
		}
	}

	/**
	 * Sale to a business of another EU country with a VAT ID that VIES accepts: no tax is charged (reverse charge).
	 *
	 * @param string $customerCountry ISO 3166-1 alpha-2, empty = shop country
	 * @param array  $pref            Plugin preferences
	 * @param string $vatId           Customer VAT ID
	 * @return bool
	 */
	public function isReverseCharge($customerCountry, array $pref, $vatId)
	{
		$vatId = trim((string) $vatId);

		if ($vatId === '' || empty($pref['tax_calculate']) || empty($pref['tax_check_vat']))
		{
			return false;
		}

		$business = strtoupper(isset($pref['tax_business_country']) ? $pref['tax_business_country'] : '');
		$customer = strtoupper($customerCountry ?: $business);

		if ($customer === $business)
		{
			return false;
		}

		$countries = new \Ibericode\Vat\Countries();

		if (!$countries->isCountryCodeInEU($business) || !$countries->isCountryCodeInEU($customer))
		{
			return false;
		}

		return (bool) $this->vat->validate($vatId, $customer, true)['valid'];
	}

	/** Net price of a gross price. */
	public function netPrice($gross, $rate)
	{
		return round($gross / (1 + $rate), 2);
	}

	/** Tax amount of a net price. */
	public function taxAmount($net, $rate)
	{
		return round($net * $rate, 2);
	}

	/** Same country as the shop: rate of the class defined in the preferences. */
	private function localRate($taxClass, $classes)
	{
		if (!is_array($classes))
		{
			$this->log('tax_classes is not an array');

			return 0.0;
		}

		foreach ($classes as $row)
		{
			if (isset($row['name']) && $row['name'] === $taxClass)
			{
				return (float) $row['value'];
			}
		}

		return 0.0;
	}

	/** The requested class when the country defines it, otherwise the nearest alternative or standard. */
	public function mapClass($taxClass, $country)
	{
		$candidates = array_merge(array($taxClass), isset(self::CLASS_FALLBACKS[$taxClass]) ? self::CLASS_FALLBACKS[$taxClass] : array('standard'));

		foreach ($candidates as $candidate)
		{
			try
			{
				$this->rates->getRateForCountry($country, $candidate);

				return $candidate;
			}
			catch (\Exception $e)
			{
				continue;
			}
		}

		return 'standard';
	}

	private function log($message)
	{
		if ($this->logger)
		{
			call_user_func($this->logger, '[VSTORE_TAX] ' . $message);
		}
	}
}
