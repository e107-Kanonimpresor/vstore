<?php

namespace Vstore\Payment;

use Omnipay\Mollie\Message\Request\PurchaseRequest;

/**
 * Mollie purchase that also sends the customer's email and phone number as billingAddress
 * (the stock request only supports the deprecated billingEmail).
 */
class MolliePurchaseRequest extends PurchaseRequest
{
	public function getBillingPhone()
	{
		return $this->getParameter('billingPhone');
	}

	/** @param string $value Phone number in E.164 format */
	public function setBillingPhone($value)
	{
		return $this->setParameter('billingPhone', $value);
	}

	public function getData()
	{
		$data = parent::getData();

		unset($data['billingEmail']);

		$address = array();

		if ($email = $this->getBillingEmail())
		{
			$address['email'] = $email;
		}

		if ($phone = $this->getBillingPhone())
		{
			$address['phone'] = $phone;
		}

		// Mollie needs an email or a full postal address next to the phone number
		if (!empty($address['email']))
		{
			$data['billingAddress'] = $address;
		}

		return $data;
	}
}
