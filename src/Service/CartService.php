<?php
/**
 * Vstore - shopping cart plugin for e107.
 * Copyright (c) 2026 Kanonimpresor.
 *
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Vstore\Service;

use Vstore\Repository\CartRepositoryInterface;
use Vstore\Support\ItemVars;
use Vstore\Support\Result;

class CartService
{
	/** Largest quantity accepted in one request. */
	const MAX_QTY = 9999;

	/** @var CartRepositoryInterface */
	private $cart;

	/** @var InventoryService */
	private $inventory;

	public function __construct(CartRepositoryInterface $cart, InventoryService $inventory)
	{
		$this->cart      = $cart;
		$this->inventory = $inventory;
	}

	/**
	 * Add an item to the cart. A quantity already in the cart is increased, never above the stock.
	 * Codes: ITEM_UNAVAILABLE (the item is inactive, its lines are removed) and QTY_EXCEEDS (no stock left).
	 *
	 * @param string     $session  Cart id
	 * @param int        $userId
	 * @param int        $itemId
	 * @param array|bool $itemVars id => value, or the list form posted by the product page
	 * @param int        $qty
	 * @return Result
	 */
	public function add($session, $userId, $itemId, $itemVars = false, $qty = 1)
	{
		$qty      = max(1, min(self::MAX_QTY, (int) $qty));
		$itemVars = ItemVars::normalize($itemVars);
		$item     = $this->cart->getItem($itemId);

		if (!$item || !$item['item_active'])
		{
			$this->cart->deleteItemLines($session, $itemId);

			return Result::fail('ITEM_UNAVAILABLE');
		}

		$vars  = is_array($itemVars) ? ItemVars::toDb($itemVars) : null;
		$stock = $this->inventory->available($itemId, $itemVars);
		$line  = $this->cart->findLine($session, $itemId, $vars);

		if ($line)
		{
			$current = (int) $line['cart_qty'];

			if ($stock && $current < $stock && $this->cart->setQty($line['cart_id'], $session, min($current + $qty, $stock)))
			{
				return Result::ok();
			}

			return Result::fail('QTY_EXCEEDS');
		}

		$inserted = $this->cart->insertLine(array(
			'cart_id'             => 0,
			'cart_session'        => $session,
			'cart_e107_user'      => $userId,
			'cart_status'         => '',
			'cart_item'           => (int) $itemId,
			'cart_item_vars'      => $itemVars ? ItemVars::toDb($itemVars) : '',
			'cart_item_tax_class' => !empty($item['item_tax_class']) ? $item['item_tax_class'] : 'standard',
			'cart_qty'            => $stock > 0 ? min($qty, $stock) : $qty,
		));

		return $inserted ? Result::ok() : Result::fail('SAVE_FAIL');
	}

	/**
	 * Save the quantities posted by the cart form.
	 * Warnings: ITEM_NOT_FOUND (inactive item, line removed; param: name) and QTY_ADJUSTED (limited to the stock; param: name).
	 *
	 * @param string        $session Cart id
	 * @param array         $rows    cart_id => array(id, qty, vars)
	 * @param callable|null $varLabel fn(array $itemVars): string, text of the selected variation
	 * @return Result[]
	 */
	public function updateQuantities($session, array $rows, $varLabel = null)
	{
		$results = array();

		foreach ($rows as $cartId => $row)
		{
			$itemId = isset($row['id']) ? (int) $row['id'] : 0;
			$qty    = isset($row['qty']) ? (int) $row['qty'] : 0;

			if ($qty <= 0 || $itemId <= 0)
			{
				continue;
			}

			$vars = !empty($row['vars']) ? ItemVars::toArray($row['vars']) : null;
			$item = $this->cart->getItem($itemId);

			if (!$item || !$item['item_active'])
			{
				$this->cart->deleteLine($cartId, $session);
				$results[] = Result::warn('ITEM_NOT_FOUND', array($item ? $item['item_name'] : ''));

				continue;
			}

			$stock = $this->inventory->available($itemId, $vars ? $vars : false);

			if ($qty > $stock && $stock >= 0)
			{
				$qty  = $stock;
				$name = $item['item_name'] . ($vars && $varLabel ? call_user_func($varLabel, $vars) : '');

				$results[] = Result::warn('QTY_ADJUSTED', array($name));
			}

			$this->cart->setQty($cartId, $session, $qty);
		}

		return $results;
	}

	/** @param array $cartIds cart_id => anything */
	public function remove($session, array $cartIds)
	{
		foreach ($cartIds as $cartId => $unused)
		{
			$this->cart->deleteLine($cartId, $session);
		}
	}
}
