<?php
/**
 * Vstore - shopping cart plugin for e107.
 * Copyright (c) 2026 Kanonimpresor.
 *
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Vstore\Repository;

interface ItemVarRepositoryInterface
{
	/** @return array rows with item_var_id and item_var_compulsory (1 = tracks inventory) */
	public function all();
}
