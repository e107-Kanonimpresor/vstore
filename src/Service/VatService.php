<?php

namespace Vstore\Service;

/**
 * VAT ID checks. The validator is any object with validateVatNumber(string): bool
 * (Ibericode\Vat\Validator by default) so VIES can be replaced in tests.
 */
class VatService
{
	/** @var object|null */
	private $validator;

	/** @var callable|null fn(string $message): void */
	private $logger;

	/** @var array|null get => fn(string $key): ?array, set => fn(string $key, array $result): void */
	private $store;

	public function __construct($validator = null, $logger = null)
	{
		$this->validator = $validator;
		$this->logger    = $logger;
	}

	/** Remember VIES answers (definitive ones only) so the cart does not ask the service on every page. */
	public function setStore($get, $set)
	{
		$this->store = array('get' => $get, 'set' => $set);
	}

	/**
	 * Validate a VAT ID against VIES.
	 *
	 * @param string $vatId
	 * @param string $countryCode Two-letter country code
	 * @param bool   $checkEnabled Preference tax_check_vat
	 * @return array valid (bool), name, address, error
	 */
	public function validate($vatId, $countryCode, $checkEnabled)
	{
		$result = array('valid' => false, 'name' => '', 'address' => '', 'error' => '');

		if (!$checkEnabled)
		{
			$result['valid'] = true;
			$result['error'] = 'VAT validation disabled in settings';

			return $result;
		}

		$vatId       = strtoupper(str_replace(' ', '', $vatId));
		$countryCode = strtoupper($countryCode);

		if (strpos($vatId, $countryCode) === 0)
		{
			$vatId = substr($vatId, 2);
		}

		$key = $countryCode . $vatId;

		if ($this->store && ($known = call_user_func($this->store['get'], $key)) !== null)
		{
			return $known;
		}

		try
		{
			if ($this->validator()->validateVatNumber($countryCode . $vatId))
			{
				$result['valid'] = true;
			}
			else
			{
				$result['error'] = 'Invalid VAT ID';
			}

			if ($this->store)
			{
				call_user_func($this->store['set'], $key, $result);
			}
		}
		catch (\Exception $e)
		{
			// VIES unavailable: accept the number rather than block the order
			$result['valid'] = true;
			$result['error'] = 'VIES service unavailable: ' . $e->getMessage();

			if ($this->logger)
			{
				call_user_func($this->logger, $result['error']);
			}
		}

		return $result;
	}

	/**
	 * Checkout form check: the VAT ID must match the country and, for EU countries, exist.
	 * A customer of the shop's own country has no reverse charge, so VIES is not asked: only the national number is checked.
	 *
	 * @param string $businessCountry Country of the shop (preference tax_business_country)
	 * @return bool true when empty, when checking is disabled or when the number is valid
	 */
	public function isAcceptable($vatId, $country, $checkEnabled, $businessCountry = '')
	{
		if (trim($vatId) === '')
		{
			return true;
		}

		if (trim($country) === '')
		{
			return false;
		}

		if (!$checkEnabled)
		{
			return true;
		}

		$country    = trim(strtoupper($country));

		if ($businessCountry !== '' && $country === trim(strtoupper($businessCountry)))
		{
			return \Vstore\Support\TaxNumber::isValid($country, $vatId);
		}

		$vatCountry = trim(strtoupper(substr($vatId, 0, 2)));

		if ($vatCountry !== $country)
		{
			return false;
		}

		if (!(new \Ibericode\Vat\Countries())->isCountryCodeInEU($vatCountry))
		{
			return true;
		}

		return (bool) $this->validator()->validateVatNumber($vatId);
	}

	private function validator()
	{
		if ($this->validator === null)
		{
			$this->validator = new \Ibericode\Vat\Validator();
		}

		return $this->validator;
	}
}
