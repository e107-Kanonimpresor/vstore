<?php

namespace Vstore\Repository;

interface ItemVarRepositoryInterface
{
	/** @return array rows with item_var_id and item_var_compulsory (1 = tracks inventory) */
	public function all();
}
