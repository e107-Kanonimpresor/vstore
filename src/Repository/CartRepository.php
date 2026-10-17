<?php

namespace Vstore\Repository;

class CartRepository implements CartRepositoryInterface
{
	public function getItem($itemId)
	{
		$row = \e107::getDb()->retrieve('vstore_items', 'item_active, item_tax_class, item_name', 'item_id=' . (int) $itemId);

		return $row ? $row : null;
	}

	public function findLine($session, $itemId, $vars = null)
	{
		$sql   = \e107::getDb();
		$where = 'cart_session = "' . $sql->escape($session) . '" AND cart_item = ' . (int) $itemId;

		if ($vars !== null)
		{
			$where .= ' AND cart_item_vars = "' . $sql->escape($vars) . '"';
		}

		$row = $sql->retrieve('vstore_cart', 'cart_id, cart_qty', $where . ' ORDER BY cart_id LIMIT 1');

		return $row ? $row : null;
	}

	public function insertLine(array $data)
	{
		return (bool) \e107::getDb()->insert('vstore_cart', $data);
	}

	public function setQty($cartId, $session, $qty)
	{
		$sql = \e107::getDb();

		return $sql->update(
			'vstore_cart',
			'cart_qty = ' . (int) $qty . ' WHERE cart_id = ' . (int) $cartId . ' AND cart_session = "' . $sql->escape($session) . '" LIMIT 1'
		) !== false;
	}

	public function deleteLine($cartId, $session)
	{
		$sql = \e107::getDb();

		return (bool) $sql->delete('vstore_cart', 'cart_id = ' . (int) $cartId . ' AND cart_session = "' . $sql->escape($session) . '" LIMIT 1');
	}

	public function deleteItemLines($session, $itemId)
	{
		$sql = \e107::getDb();

		return (bool) $sql->delete('vstore_cart', 'cart_session = "' . $sql->escape($session) . '" AND cart_item = ' . (int) $itemId);
	}
}
