<?php

namespace Vstore\Repository;

class ItemRepository implements ItemRepositoryInterface
{
	public function getInventory($itemId)
	{
		$row = \e107::getDb()->retrieve('vstore_items', 'item_inventory, item_vars_inventory', 'item_id = ' . (int) $itemId);

		if (!$row)
		{
			return null;
		}

		$vars = \e107::unserialize($row['item_vars_inventory']);

		return array(
			'item_inventory'      => (int) $row['item_inventory'],
			'item_vars_inventory' => is_array($vars) ? $vars : array(),
		);
	}

	public function saveInventory($itemId, $inventory, $varsInventory = null)
	{
		$data = array('item_inventory' => (int) $inventory);

		if ($varsInventory !== null)
		{
			$data['item_vars_inventory'] = \e107::serialize($varsInventory, 'json');
		}

		return (bool) \e107::getDb()->update('vstore_items', array('data' => $data, 'WHERE' => 'item_id = ' . (int) $itemId));
	}

	public function getUserclass($itemId)
	{
		return (int) \e107::getDb()->retrieve('vstore_items', 'item_userclass', 'item_id=' . (int) $itemId);
	}
}
