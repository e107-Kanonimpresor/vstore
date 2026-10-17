<?php
/**
 * Vstore - shopping cart plugin for e107.
 * Copyright (c) 2026 Kanonimpresor.
 *
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Vstore\Service;

use Vstore\Repository\CouponUsageRepositoryInterface;
use Vstore\Support\Result;

class CouponService
{
	/** @var CouponUsageRepositoryInterface */
	private $usage;

	/** @var callable|null fn(string $message): void */
	private $logger;

	/** @var callable fn(): int */
	private $clock;

	public function __construct(CouponUsageRepositoryInterface $usage, $logger = null, $clock = null)
	{
		$this->usage  = $usage;
		$this->logger = $logger;
		$this->clock  = $clock ?: 'time';
	}

	/**
	 * Discount of a coupon for one cart item.
	 *
	 * The value is 0.0 or negative. Result codes: COUPON_NA, COUPON_NOT_YET (date), COUPON_EXPIRED (date),
	 * COUPON_LIMIT, COUPON_LIMIT_USER (errors, value 0.0) and COUPON_CAPPED (warning, discount still applied).
	 *
	 * @param array $coupon Row of vstore_coupons
	 * @param array $item   Needs item_id, item_cat, item_price, cart_qty, item_name
	 * @param int   $userId e107 user id, 0 for guests
	 * @return Result
	 */
	public function calculate(array $coupon, array $item, $userId = 0)
	{
		if (empty($coupon) || empty($item))
		{
			return Result::ok(0.0);
		}

		if (empty($coupon['coupon_active']))
		{
			return Result::fail('COUPON_NA', array(), 0.0);
		}

		$now   = (int) call_user_func($this->clock);
		$start = $this->toTimestamp(isset($coupon['coupon_start']) ? $coupon['coupon_start'] : 0);
		$end   = $this->toTimestamp(isset($coupon['coupon_end']) ? $coupon['coupon_end'] : 0);

		if ($start && $now < $start)
		{
			return Result::fail('COUPON_NOT_YET', array(date('Y-m-d H:i:s', $start)), 0.0);
		}

		if ($end && $now > $end)
		{
			return Result::fail('COUPON_EXPIRED', array(date('Y-m-d H:i:s', $end)), 0.0);
		}

		$code = isset($coupon['coupon_code']) ? $coupon['coupon_code'] : '';

		if (isset($coupon['coupon_limit_coupon']) && $coupon['coupon_limit_coupon'] > -1
			&& $this->usage->totalUsage($code) >= $coupon['coupon_limit_coupon'])
		{
			return Result::fail('COUPON_LIMIT', array(), 0.0);
		}

		if (isset($coupon['coupon_limit_user']) && $coupon['coupon_limit_user'] > -1 && $userId > 0
			&& $this->usage->userUsage($code, $userId) >= $coupon['coupon_limit_user'])
		{
			return Result::fail('COUPON_LIMIT_USER', array(), 0.0);
		}

		if (!$this->appliesTo($coupon, (int) $item['item_id'], (int) $item['item_cat']))
		{
			return Result::ok(0.0);
		}

		return $this->discount($coupon, $item, $code);
	}

	/** Exclusions win over inclusions; categories are checked before items. */
	private function appliesTo(array $coupon, $itemId, $itemCat)
	{
		$list = function ($key) use ($coupon)
		{
			return array_filter(explode(',', isset($coupon[$key]) ? (string) $coupon[$key] : ''));
		};

		$catsEx  = $list('coupon_cats_ex');
		$itemsEx = $list('coupon_items_ex');
		$cats    = $list('coupon_cats');
		$items   = $list('coupon_items');

		if ($catsEx && in_array($itemCat, $catsEx))
		{
			$this->log("Coupon excluded: item category {$itemCat} in exclusion list");
			return false;
		}

		if ($itemsEx && in_array($itemId, $itemsEx))
		{
			$this->log("Coupon excluded: item {$itemId} in exclusion list");
			return false;
		}

		if ($cats && !in_array($itemCat, $cats))
		{
			return false;
		}

		if ($items && !in_array($itemId, $items))
		{
			return false;
		}

		return true;
	}

	private function discount(array $coupon, array $item, $code)
	{
		$qty       = (int) $item['cart_qty'];
		$itemPrice = (float) $item['item_price'];
		$value     = (float) $coupon['coupon_amount'];
		$warning   = null;

		if ($coupon['coupon_type'] === '%')
		{
			$amount = -1 * ($itemPrice * $qty) * ($value / 100.0);
		}
		else
		{
			if ($value > $itemPrice)
			{
				$value   = $itemPrice;
				$warning = array(isset($item['item_name']) ? $item['item_name'] : '');
			}

			$amount = -1 * ($value * $qty);
		}

		if ($amount > 0)
		{
			$amount = 0.0;
		}

		$this->log(sprintf('coupon %s item %d qty %d discount %s', $code, $item['item_id'], $qty, $amount));

		return $warning === null ? Result::ok($amount) : Result::warn('COUPON_CAPPED', $warning, $amount);
	}

	/** Dates may be stored as unix timestamps or as datetime strings. */
	private function toTimestamp($value)
	{
		if (empty($value))
		{
			return 0;
		}

		return is_numeric($value) ? (int) $value : (int) strtotime($value);
	}

	private function log($message)
	{
		if ($this->logger)
		{
			call_user_func($this->logger, $message);
		}
	}
}
