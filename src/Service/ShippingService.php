<?php

namespace Vstore\Service;

class ShippingService
{
	/**
	 * Shipping cost of a cart.
	 *
	 * @param array $items Cart rows with item_shipping, item_price, item_weight and cart_qty
	 * @param array $pref  Plugin preferences (shipping, shipping_method, shipping_limit, shipping_unit, shipping_data)
	 * @return float
	 */
	public function calculate(array $items, array $pref)
	{
		if (empty($pref['shipping']))
		{
			return 0.0;
		}

		$method   = isset($pref['shipping_method']) ? $pref['shipping_method'] : '';
		$shipping = 0.0;
		$subtotal = 0.0;
		$weight   = 0.0;

		foreach ($items as $item)
		{
			$qty = isset($item['cart_qty']) ? $item['cart_qty'] : 0;

			if ($method === 'sum_unique')
			{
				$shipping += (float) $item['item_shipping'];
			}
			else
			{
				$shipping += (float) ($item['item_shipping'] * $qty);
			}

			$subtotal += (float) ($item['item_price'] * $qty);
			$weight   += (float) ($item['item_weight'] * $qty);
		}

		if ($method === 'tiered' && !empty($pref['shipping_limit']) && !empty($pref['shipping_data']))
		{
			$shipping = $this->tieredCost($pref, $shipping, $subtotal, $weight);
		}

		return $shipping;
	}

	private function tieredCost(array $pref, $shipping, $subtotal, $weight)
	{
		$data = $pref['shipping_data'];
		unset($data['%ROW%']);

		$val = (isset($pref['shipping_unit']) && $pref['shipping_unit'] === 'weight') ? $weight : $subtotal;

		foreach ($data as $tier)
		{
			if ($val <= (float) $tier['unit'])
			{
				$cost = (float) $tier['cost'];

				if ($pref['shipping_limit'] === 'limit')
				{
					return $cost > $shipping ? $shipping : $cost;
				}

				return $cost;
			}
		}

		return 0.0;
	}
}
