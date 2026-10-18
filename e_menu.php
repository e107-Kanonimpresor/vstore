<?php
/*
 * e107 website system
 *
 * Copyright (C) e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * Vstore - Menu Manager options of vstore_showcase_menu.php.
 *
 * Same mechanism as the FAQs plugin: the Menu Manager strips "_menu" from the menu name before calling
 * config(), so vstore_showcase_menu.php arrives here as 'vstore_showcase'.
*/

if (!defined('e107_INIT'))
{
	exit;
}

class vstore_menu
{
	/**
	 * @param string $menu menu name without the trailing "_menu"
	 * @return array admin UI field definitions
	 */
	public function config($menu = '')
	{
		e107::lan('vstore', 'admin_menu', true);

		// The product and category menus only have a caption
		if ($menu !== 'vstore_showcase')
		{
			return array(
				'caption' => array(
					'title'      => LAN_VSTORE_MENUCFG_CAPTION,
					'type'       => 'text',
					'writeParms' => array('size' => 'xxlarge'),
					'help'       => LAN_VSTORE_MENUCFG_CAPTION_H,
				),
			);
		}

		$fields = array();

		$fields['caption'] = array(
			'title'      => LAN_VSTORE_MENUCFG_CAPTION,
			'type'       => 'text',
			'writeParms' => array('size' => 'xxlarge'),
			'help'       => LAN_VSTORE_MENUCFG_CAPTION_H,
		);

		$fields['category'] = array(
			'title'      => LAN_VSTORE_MENUCFG_CATEGORY,
			'type'       => 'dropdown',
			'writeParms' => $this->categoryOptions(),
			'help'       => LAN_VSTORE_MENUCFG_CATEGORY_H,
		);

		$fields['limit'] = array(
			'title'      => LAN_VSTORE_MENUCFG_LIMIT,
			'type'       => 'number',
			'writeParms' => array('default' => 6, 'min' => 1, 'max' => 24),
			'help'       => LAN_VSTORE_MENUCFG_LIMIT_H,
		);

		$fields['columns'] = array(
			'title'      => LAN_VSTORE_MENUCFG_COLUMNS,
			'type'       => 'dropdown',
			'writeParms' => array(
				'1'    => LAN_VSTORE_MENUCFG_COL_1,
				'2'    => LAN_VSTORE_MENUCFG_COL_2,
				'3'    => LAN_VSTORE_MENUCFG_COL_3,
				'4'    => LAN_VSTORE_MENUCFG_COL_4,
				'auto' => LAN_VSTORE_MENUCFG_COL_AUTO,
			),
			'help'       => LAN_VSTORE_MENUCFG_COLUMNS_H,
		);

		$fields['order'] = array(
			'title'      => LAN_VSTORE_MENUCFG_ORDER,
			'type'       => 'dropdown',
			'writeParms' => array(
				'auto'       => LAN_VSTORE_MENUCFG_ORD_AUTO,
				'random'     => LAN_VSTORE_MENUCFG_ORD_RANDOM,
				'newest'     => LAN_VSTORE_MENUCFG_ORD_NEWEST,
				'name'       => LAN_VSTORE_MENUCFG_ORD_NAME,
				'price_asc'  => LAN_VSTORE_MENUCFG_ORD_PRICE_ASC,
				'price_desc' => LAN_VSTORE_MENUCFG_ORD_PRICE_DESC,
				'manual'     => LAN_VSTORE_MENUCFG_ORD_MANUAL,
			),
		);

		$fields['nav'] = array(
			'title'      => LAN_VSTORE_MENUCFG_NAV,
			'type'       => 'dropdown',
			'writeParms' => array(
				'pills' => LAN_VSTORE_MENUCFG_NAV_PILLS,
				'links' => LAN_VSTORE_MENUCFG_NAV_LINKS,
				'none'  => LAN_VSTORE_MENUCFG_NAV_NONE,
			),
			'help'       => LAN_VSTORE_MENUCFG_NAV_H,
		);

		return $fields;
	}

	/**
	 * Categories for the dropdown, subcategories indented under their parent.
	 *
	 * @return array
	 */
	private function categoryOptions()
	{
		$out = array('0' => LAN_VSTORE_MENUCFG_ALL);
		$tp = e107::getParser();

		$rows = (array) e107::getDb('vstore_menu_cfg')->retrieve(
			'SELECT cat_id, cat_parent, cat_name FROM #vstore_cat ORDER BY cat_order, cat_name',
			true
		);

		$children = array();

		foreach ($rows as $row)
		{
			$children[(int) $row['cat_parent']][] = $row;
		}

		$walk = function ($parent, $depth) use (&$walk, &$out, $children, $tp)
		{
			foreach (varset($children[$parent], array()) as $row)
			{
				$out[(int) $row['cat_id']] = str_repeat('- ', $depth) . $tp->toHTML($row['cat_name'], false, 'TITLE');
				$walk((int) $row['cat_id'], $depth + 1);
			}
		};

		$walk(0, 0);

		return $out;
	}
}
