<?php

namespace Vstore\Service;

use Vstore\Repository\ItemRepositoryInterface;
use Vstore\Support\ItemVars;

class InventoryService
{
	/** Stock reported for items without inventory tracking (stored as a negative number). */
	const UNLIMITED = 9999999;

	/** @var ItemRepositoryInterface */
	private $items;

	/** @var callable|null fn(string $message): void */
	private $logger;

	public function __construct(ItemRepositoryInterface $items, $logger = null)
	{
		$this->items  = $items;
		$this->logger = $logger;
	}

	/**
	 * Current stock of an item, or of one of its variation combinations.
	 *
	 * @param int        $itemId
	 * @param array|bool $itemVars id => value, or the list form posted by the product form
	 * @return int
	 */
	public function available($itemId, $itemVars = false)
	{
		$vars = ItemVars::normalize($itemVars);
		$data = $this->items->getInventory((int) $itemId);

		if (!$vars)
		{
			$qty = $data ? $data['item_inventory'] : 0;

			return $qty < 0 ? self::UNLIMITED : $qty;
		}

		if ($data === null)
		{
			$this->log('Item not found!');

			return 0;
		}

		$keys      = array_values($vars);
		$inventory = $data['item_vars_inventory'];

		if (count($keys) === 1)
		{
			$qty = (int) (isset($inventory[$keys[0]]) ? $inventory[$keys[0]] : 0);
		}
		elseif (count($keys) === 2)
		{
			$qty = (int) (isset($inventory[$keys[0]][$keys[1]]) ? $inventory[$keys[0]][$keys[1]] : 0);
		}
		else
		{
			$this->log('Invalid number of item_vars!');

			return 0;
		}

		return $qty < 0 ? self::UNLIMITED : $qty;
	}

	/**
	 * Reduce the stock for the rows of an order (quantity, id, name and optional item_vars).
	 * A stock of -1 means unlimited and is left untouched.
	 *
	 * @param array $rows order_items
	 */
	public function reduce(array $rows)
	{
		foreach ($rows as $row)
		{
			if (empty($row['quantity']) || empty($row['id']) || empty($row['name']))
			{
				continue;
			}

			$id       = (int) $row['id'];
			$reduceBy = (int) $row['quantity'];
			$data     = $this->items->getInventory($id);
			$current  = $data ? $data['item_inventory'] : 0;
			$vars     = null;

			if (!empty($row['item_vars']))
			{
				$vars = $this->reduceVars($data ? $data['item_vars_inventory'] : array(), (array) ItemVars::toArray($row['item_vars']), $reduceBy);
			}

			if ($current > 0 || !empty($vars))
			{
				$reduceTo = $current == -1 ? -1 : ($reduceBy > $current ? 0 : $current - $reduceBy);
				$saved    = $this->items->saveInventory($id, $reduceTo, empty($vars) ? null : $vars);

				$this->log(($saved ? 'Updated' : 'Was UNABLE to update') . " item_inventory of {$row['name']} ({$id}) to {$reduceTo}");
			}
			else
			{
				$this->log("Unlimited item not reduced: {$row['name']} ({$id})");
			}
		}
	}

	/** Reduce the tracked quantity of one variation combination (one or two levels deep). */
	private function reduceVars(array $inventory, array $vars, $reduceBy)
	{
		$keys = array_values($vars);

		if (count($keys) === 1)
		{
			$qty = (int) (isset($inventory[$keys[0]]) ? $inventory[$keys[0]] : -1);

			if ($qty > 0)
			{
				$inventory[$keys[0]] = $reduceBy > $qty ? 0 : $qty - $reduceBy;
			}
		}
		elseif (count($keys) === 2)
		{
			$qty = (int) (isset($inventory[$keys[0]][$keys[1]]) ? $inventory[$keys[0]][$keys[1]] : -1);

			if ($qty > 0)
			{
				$inventory[$keys[0]][$keys[1]] = $reduceBy > $qty ? 0 : $qty - $reduceBy;
			}
		}

		return $inventory;
	}

	/**
	 * Give the stock of a cancelled order back (the counterpart of reduce()). Unlimited stock (-1) is left untouched.
	 *
	 * @param array $rows order_items
	 */
	public function restore(array $rows)
	{
		foreach ($rows as $row)
		{
			if (empty($row['quantity']) || empty($row['id']) || empty($row['name']))
			{
				continue;
			}

			$id      = (int) $row['id'];
			$qty     = (int) $row['quantity'];
			$data    = $this->items->getInventory($id);
			$current = $data ? (int) $data['item_inventory'] : -1;
			$vars    = null;

			if (!empty($row['item_vars']) && $data)
			{
				$vars = $this->restoreVars((array) $data['item_vars_inventory'], (array) ItemVars::toArray($row['item_vars']), $qty);
			}

			if ($current >= 0 || !empty($vars))
			{
				$restoreTo = $current >= 0 ? $current + $qty : $current;
				$saved     = $this->items->saveInventory($id, $restoreTo, empty($vars) ? null : $vars);

				$this->log(($saved ? 'Restored' : 'Was UNABLE to restore') . " item_inventory of {$row['name']} ({$id}) to {$restoreTo}");
			}
		}
	}

	/** Give back the tracked quantity of one variation combination (one or two levels deep). */
	private function restoreVars(array $inventory, array $vars, $qty)
	{
		$keys = array_values($vars);

		if (count($keys) === 1 && isset($inventory[$keys[0]]) && (int) $inventory[$keys[0]] >= 0)
		{
			$inventory[$keys[0]] = (int) $inventory[$keys[0]] + $qty;
		}
		elseif (count($keys) === 2 && isset($inventory[$keys[0]][$keys[1]]) && (int) $inventory[$keys[0]][$keys[1]] >= 0)
		{
			$inventory[$keys[0]][$keys[1]] = (int) $inventory[$keys[0]][$keys[1]] + $qty;
		}

		return $inventory;
	}

	private function log($message)
	{
		if ($this->logger)
		{
			call_user_func($this->logger, $message);
		}
	}
}
