<?php

namespace Vstore\Repository;

class ItemVarRepository implements ItemVarRepositoryInterface
{
	public function all()
	{
		$rows = \e107::getDb()->retrieve('vstore_items_vars', 'item_var_id, item_var_compulsory', null, true);

		return is_array($rows) ? $rows : array();
	}
}
