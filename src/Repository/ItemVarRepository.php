<?php
/**
 * Vstore - shopping cart plugin for e107.
 * Copyright (c) 2026 Kanonimpresor.
 *
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Vstore\Repository;

class ItemVarRepository implements ItemVarRepositoryInterface
{
	public function all()
	{
		$rows = \e107::getDb()->retrieve('vstore_items_vars', 'item_var_id, item_var_compulsory', null, true);

		return is_array($rows) ? $rows : array();
	}
}
