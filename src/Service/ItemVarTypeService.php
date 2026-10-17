<?php
/**
 * Vstore - shopping cart plugin for e107.
 * Copyright (c) 2026 Kanonimpresor.
 *
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Vstore\Service;

use Vstore\Repository\ItemVarRepositoryInterface;

/**
 * Item variations are either "compulsory" (type 1, tracked in the inventory) or optional (type 0).
 */
class ItemVarTypeService
{
	/** @var ItemVarRepositoryInterface */
	private $repository;

	/** @var array|null type => list of item_var_id */
	private $types;

	public function __construct(ItemVarRepositoryInterface $repository)
	{
		$this->repository = $repository;
	}

	/** True when the variation id tracks inventory. */
	public function isTracking($varId)
	{
		foreach ($this->types() as $type => $ids)
		{
			if (in_array($varId, $ids))
			{
				return $type == 1;
			}
		}

		return false;
	}

	/**
	 * Keep only the variation ids of the given type.
	 *
	 * @param string|array $current Comma separated string or list of ids
	 * @param int          $type    0 non-tracking, 1 tracking
	 * @param bool         $asArray
	 * @return array|string
	 */
	public function filter($current, $type = 0, $asArray = false)
	{
		$list = array_filter(is_array($current) ? $current : explode(',', $current));
		$types = $this->types();

		if (!$list || !isset($types[$type]))
		{
			return $asArray ? array() : '';
		}

		$result = array();

		foreach ($types[$type] as $id)
		{
			if (in_array($id, $list))
			{
				$result[] = $id;
			}
		}

		return $asArray ? $result : implode(',', $result);
	}

	private function types()
	{
		if ($this->types === null)
		{
			$this->types = array();

			foreach ($this->repository->all() as $row)
			{
				$this->types[$row['item_var_compulsory']][] = $row['item_var_id'];
			}
		}

		return $this->types;
	}
}
