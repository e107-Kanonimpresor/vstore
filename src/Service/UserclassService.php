<?php
/**
 * Vstore - shopping cart plugin for e107.
 * Copyright (c) 2026 Kanonimpresor.
 *
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Vstore\Service;

use Vstore\Repository\ItemRepositoryInterface;

class UserclassService
{
	/** Class id meaning "No one" (inactive). */
	const NO_ONE = 255;

	/** Preference value meaning "use the userclass defined in each product". */
	const PER_PRODUCT = -1;

	/** @var ItemRepositoryInterface */
	private $items;

	public function __construct(ItemRepositoryInterface $items)
	{
		$this->items = $items;
	}

	/**
	 * Userclasses a customer receives for an order.
	 *
	 * @param array $orderItems order_items (each with id)
	 * @param mixed $global     Preference customer_userclass
	 * @return array list of class ids without duplicates
	 */
	public function classesFor($orderItems, $global)
	{
		if ($global == self::PER_PRODUCT)
		{
			$classes = array();

			if (is_array($orderItems))
			{
				foreach ($orderItems as $item)
				{
					$class = $this->items->getUserclass((int) $item['id']);

					if ($class > 0 && $class != self::NO_ONE)
					{
						$classes[] = $class;
					}
				}
			}

			return array_values(array_unique($classes));
		}

		return $global != self::NO_ONE ? array($global) : array();
	}
}
