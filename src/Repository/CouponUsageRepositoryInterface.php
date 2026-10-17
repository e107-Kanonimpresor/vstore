<?php
/**
 * Vstore - shopping cart plugin for e107.
 * Copyright (c) 2026 Kanonimpresor.
 *
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Vstore\Repository;

interface CouponUsageRepositoryInterface
{
	/** Number of orders that used the coupon. */
	public function totalUsage($couponCode);

	/** Number of orders of the given user that used the coupon. */
	public function userUsage($couponCode, $userId);
}
