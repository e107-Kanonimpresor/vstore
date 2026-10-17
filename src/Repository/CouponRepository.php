<?php

namespace Vstore\Repository;

class CouponRepository
{
	/** @return array|null row of vstore_coupons */
	public function findByCode($code)
	{
		$sql = \e107::getDb();
		$row = $sql->retrieve('vstore_coupons', '*', 'coupon_code = "' . $sql->escape(trim($code)) . '"');

		return $row ? $row : null;
	}
}
