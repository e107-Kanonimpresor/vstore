<?php

namespace Vstore\Repository;

interface CartRepositoryInterface
{
	/** @return array|null item_active, item_tax_class and item_name, null when the item does not exist */
	public function getItem($itemId);

	/**
	 * @param string      $session  Cart id (cookie)
	 * @param int         $itemId
	 * @param string|null $vars     Variation string ("id|value"), null to ignore the variations
	 * @return array|null cart_id and cart_qty of the line, null when the item is not in the cart
	 */
	public function findLine($session, $itemId, $vars = null);

	/** @return bool */
	public function insertLine(array $data);

	/** Only lines of the given cart can be changed. */
	public function setQty($cartId, $session, $qty);

	/** Delete one line of the given cart. */
	public function deleteLine($cartId, $session);

	/** Delete every line of an item in the given cart. */
	public function deleteItemLines($session, $itemId);
}
