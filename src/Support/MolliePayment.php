<?php

namespace Vstore\Support;

/**
 * What a Mollie payment status means for the shop, kept apart from Omnipay and e107 so it can be tested.
 */
final class MolliePayment
{
	const PAID    = 'paid';
	const PENDING = 'pending';
	const OPEN    = 'open';
	const FAILED  = 'failed';
	const UNKNOWN = 'unknown';

	/** @param string|null $status Mollie payment status */
	public static function outcome($status)
	{
		switch ((string) $status)
		{
			case 'paid':
				return self::PAID;

			case 'pending':
			case 'authorized':
				return self::PENDING;

			case 'open':
			case 'created':
				return self::OPEN;

			case 'failed':
			case 'canceled':
			case 'expired':
				return self::FAILED;
		}

		return self::UNKNOWN;
	}

	/**
	 * Change a Mollie status notification makes to an order.
	 *
	 * @return array|null order_status, order_pay_status and outcome; null when the order must stay as it is
	 */
	public static function orderChange($status, $orderStatus, $payStatus)
	{
		// Only orders still waiting for their payment are touched
		if ($payStatus !== 'incomplete' || !in_array($orderStatus, array('N', 'H'), true))
		{
			return null;
		}

		$outcome = self::outcome($status);

		if ($outcome === self::PAID)
		{
			return array('order_status' => 'P', 'order_pay_status' => 'complete', 'outcome' => $outcome);
		}

		if ($outcome === self::FAILED)
		{
			return array('order_status' => 'X', 'order_pay_status' => 'incomplete', 'outcome' => $outcome);
		}

		return null;
	}

	/** Mollie refuses webhook URLs it cannot reach, so localhost, private addresses and local domains are skipped. */
	public static function isPublicUrl($url)
	{
		$host = strtolower((string) parse_url((string) $url, PHP_URL_HOST));

		if ($host === '' || strpos($host, '.') === false)
		{
			return false;
		}

		if (preg_match('/\.(local|localhost|test|invalid|example|lan|internal)$/', $host))
		{
			return false;
		}

		if (filter_var($host, FILTER_VALIDATE_IP))
		{
			return (bool) filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
		}

		return true;
	}
}
