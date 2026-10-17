<?php
/**
 * Vstore - shopping cart plugin for e107.
 * Copyright (c) 2026 Kanonimpresor.
 *
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Vstore\Repository;

/**
 * Coupon usage counted from #vstore_orders through the e107 database layer.
 */
class OrderCouponUsageRepository implements CouponUsageRepositoryInterface
{
	public function totalUsage($couponCode)
	{
		$sql = \e107::getDb();

		return (int) $sql->count(
			'vstore_orders',
			'(*)',
			sprintf('order_pay_coupon_code="%s"', $sql->escape($couponCode))
		);
	}

	public function userUsage($couponCode, $userId)
	{
		$sql = \e107::getDb();

		return (int) $sql->count(
			'vstore_orders',
			'(*)',
			sprintf('order_e107_user=%d AND order_pay_coupon_code="%s"', (int) $userId, $sql->escape($couponCode))
		);
	}
}
