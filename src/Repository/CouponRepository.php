<?php
/**
 * Vstore - shopping cart plugin for e107.
 * Copyright (c) 2026 Kanonimpresor.
 *
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.html
 */

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
