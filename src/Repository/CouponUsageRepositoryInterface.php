<?php

namespace Vstore\Repository;

interface CouponUsageRepositoryInterface
{
	/** Number of orders that used the coupon. */
	public function totalUsage($couponCode);

	/** Number of orders of the given user that used the coupon. */
	public function userUsage($couponCode, $userId);
}
