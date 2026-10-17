<?php

namespace Vstore\Service;

use Vstore\Support\Result;

class CheckoutService
{
	/** @var TaxService */
	private $tax;

	/** @var CouponService */
	private $coupons;

	/** @var ShippingService */
	private $shipping;

	/** @var VatService */
	private $vat;

	public function __construct(TaxService $tax, CouponService $coupons, ShippingService $shipping, VatService $vat)
	{
		$this->tax      = $tax;
		$this->coupons  = $coupons;
		$this->shipping = $shipping;
		$this->vat      = $vat;
	}

	/**
	 * Price the cart: coupon, tax per rate, shipping and totals.
	 *
	 * @param array      $items     Cart rows. item_price must already include the variation price. Needs cart_qty,
	 *                              cart_item_tax_class, item_id, item_cat, item_name, item_shipping and item_weight.
	 * @param array|null $coupon    Row of vstore_coupons, null for none
	 * @param array      $context   country (customer country for the tax, '' = shop country), vat_id,
	 *                              is_business, is_local, user_id
	 * @param array      $pref      Plugin preferences
	 * @return array items (rows with tax and net values), totals and messages (Result list)
	 */
	public function calculate(array $items, $coupon, array $context, array $pref)
	{
		$country    = isset($context['country']) ? (string) $context['country'] : '';
		$vatId      = isset($context['vat_id']) ? (string) $context['vat_id'] : '';
		$isBusiness = !empty($context['is_business']);
		$isLocal    = !empty($context['is_local']);
		$userId     = isset($context['user_id']) ? (int) $context['user_id'] : 0;

		$messages   = array();
		$rows       = array();
		$couponData = array(
			'code'       => is_array($coupon) ? strtoupper(trim($coupon['coupon_code'])) : '',
			'amount'     => 0.0,
			'amount_net' => 0.0,
		);
		$subTotal    = 0;
		$subTotalNet = 0;
		$netTotal    = array();
		$taxTotal    = array();

		foreach ($items as $row)
		{
			$price     = $row['item_price'];
			$itemTotal = $price * $row['cart_qty'];

			$couponResult = is_array($coupon) ? $this->coupons->calculate($coupon, $row, $userId) : Result::ok(0.0);
			$rateResult   = $this->tax->rate($row['cart_item_tax_class'], $country, $pref, $vatId);

			foreach (array($couponResult, $rateResult) as $result)
			{
				if ($result->hasMessage())
				{
					$messages[] = $result;
				}
			}

			$couponAmount = (float) $couponResult->value();
			$rate         = (float) $rateResult->value();

			$couponData['amount'] += $couponAmount;

			$row['is_business']    = $isBusiness;
			$row['is_local']       = $isLocal;
			$row['tax_rate']       = $rate;
			$row['tax_amount']     = $this->tax->taxAmount($itemTotal, $rate);
			$row['item_price_net'] = $price;
			$row['item_total']     = $itemTotal;
			$row['item_total_net'] = $itemTotal;

			$key = '' . $rate;

			if (!isset($taxTotal[$key]))
			{
				$taxTotal[$key] = 0;
			}

			if (!isset($netTotal[$key]))
			{
				$netTotal[$key] = 0;
			}

			$taxTotal[$key]           += $this->tax->taxAmount($couponAmount, $rate);
			$couponData['amount_net'] += $couponAmount;

			$netTotal[$key] += $row['item_total_net'];
			$taxTotal[$key] += $row['tax_amount'];

			$subTotal    += $itemTotal;
			$subTotalNet += $row['item_total_net'];

			$rows[] = $row;
		}

		$shippingTotal = $this->shipping->calculate($rows, $pref);
		$shippingNet   = (float) $shippingTotal;
		$sum           = array_sum($netTotal);

		// The shipping tax is shared between the tax rates in proportion to their net values
		foreach ($netTotal as $rate => $value)
		{
			$gross = $sum > 0 ? ($value / $sum) * $shippingTotal : $shippingTotal / count($netTotal);

			$taxTotal['' . $rate] += $this->tax->taxAmount($gross, $rate);
		}

		$grandTotal = $subTotal + $shippingTotal + $couponData['amount'];

		foreach ($taxTotal as $amount)
		{
			$grandTotal += $amount;
		}

		$grandNet = $subTotalNet + $shippingNet + $couponData['amount_net'];

		return array(
			'items'    => $rows,
			'totals'   => array(
				'is_business'        => $isBusiness,
				'is_local'           => $isLocal,
				'cart_taxTotal'      => $taxTotal,
				'cart_subTotal'      => $subTotal,
				'cart_shippingTotal' => $shippingTotal,
				'cart_grandTotal'    => $grandTotal,
				'cart_subNet'        => round($subTotalNet, 2),
				'cart_shippingNet'   => $shippingNet,
				'cart_grandNet'      => round($grandNet, 2),
				'cart_coupon'        => $couponData,
			),
			'messages' => $messages,
		);
	}

	/**
	 * Validate and filter the billing or shipping data of the checkout form.
	 *
	 * Codes: CUST_INVALID, INVALID_TYPE, FIELD_REQUIRED, EMAIL_INVALID and VAT_INVALID. For FIELD_REQUIRED the value
	 * holds the field key and, for additional fields, its caption.
	 *
	 * @param mixed  $data        Posted data
	 * @param string $type        billing or shipping
	 * @param array  $fieldLists  billing => field names, shipping => field names
	 * @param array  $addFields   Additional fields: index => array(active, type, required, caption)
	 * @param bool   $checkVat    Preference tax_check_vat
	 * @param string $businessCountry Preference tax_business_country: its customers are not checked in VIES
	 * @return Result value: the filtered data
	 */
	public function validateCustomer($data, $type, array $fieldLists, array $addFields, $checkVat, $businessCountry = '')
	{
		if (empty($data) || !is_array($data))
		{
			return Result::fail('CUST_INVALID');
		}

		if (!isset($fieldLists[$type]))
		{
			return Result::fail('INVALID_TYPE');
		}

		$result = array();

		foreach ($fieldLists[$type] as $field)
		{
			if (substr($field, 0, 9) === 'add_field')
			{
				continue;
			}

			$value          = isset($data[$field]) && is_scalar($data[$field]) ? trim(strip_tags((string) $data[$field])) : '';
			$result[$field] = $value;

			switch ($field)
			{
				case 'firstname':
				case 'lastname':
				case 'address':
				case 'city':
				case 'zip':
				case 'country':
				case 'email':
					if ($value === '')
					{
						return Result::fail('FIELD_REQUIRED', array($field), array('field' => $field, 'caption' => null));
					}

					if ($field === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL))
					{
						return Result::fail('EMAIL_INVALID');
					}
					break;

				case 'vat_id':
					$result[$field] = strtoupper($value);

					if ($value !== '' && !$this->vat->isAcceptable($result[$field], isset($data['country']) ? (string) $data['country'] : '', $checkVat, $businessCountry))
					{
						return Result::fail('VAT_INVALID');
					}
					break;

				case 'additional_fields':
					foreach ($addFields as $i => $addField)
					{
						if (empty($addField['active']))
						{
							continue;
						}

						$name  = 'add_field' . $i;
						$raw   = isset($data[$name]) ? $data[$name] : '';
						$isText = isset($addField['type']) && $addField['type'] === 'text';

						$result[$name] = $isText ? trim(strip_tags(is_scalar($raw) ? (string) $raw : '')) : ($raw ? '1' : '');

						if (!empty($addField['required']) && $result[$name] === '')
						{
							return Result::fail('FIELD_REQUIRED', array($name), array('field' => $name, 'caption' => isset($addField['caption']) ? $addField['caption'] : $name));
						}
					}
					break;
			}
		}

		return Result::ok($result);
	}
}
