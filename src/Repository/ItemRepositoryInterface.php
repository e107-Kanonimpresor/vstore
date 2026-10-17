<?php

namespace Vstore\Repository;

interface ItemRepositoryInterface
{
	/**
	 * @return array|null item_inventory (int) and item_vars_inventory (decoded array), null when the item does not exist
	 */
	public function getInventory($itemId);

	/**
	 * @param int        $itemId
	 * @param int        $inventory
	 * @param array|null $varsInventory Stored only when not null
	 * @return bool
	 */
	public function saveInventory($itemId, $inventory, $varsInventory = null);

	/** Userclass granted by the item (0 when none). */
	public function getUserclass($itemId);
}
