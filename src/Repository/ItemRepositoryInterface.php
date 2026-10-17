<?php
/**
 * Vstore - shopping cart plugin for e107.
 * Copyright (c) 2026 Kanonimpresor.
 *
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.html
 */

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
