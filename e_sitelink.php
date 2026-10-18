<?php
/*
 * e107 website system
 *
 * Copyright (C) 2008-2009 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * Sitelinks configuration module - gsitemap
 *
 * $Source: /cvs_backup/e107_0.8/e107_plugins/faqs/e_sitelink.php,v $
 * $Revision$
 * $Date$
 * $Author$
 *
*/

if (!defined('e107_INIT')) { exit; }


class vstore_sitelink // include plugin-folder in the name.
{
	function config()
	{
		e107::lan('vstore', 'hooks', true);

		$links = array();
			
		$links[] = array(
			'name'			=> LAN_VSTORE_HOOK_SITELINK_CATS,
			'function'		=> "storeCategories"
		);

			$links[] = array(
			'name'			=> LAN_VSTORE_HOOK_SITELINK_CART,
			'function'		=> "storeCart"
		);
		
		
		return $links;
	}
	
	

	function storeCategories()
	{
		$sql = e107::getDb();
		$tp = e107::getParser();
		$sublinks = array();
		
		//$sql->select("vstore_cat","*","cat_id != '' ORDER BY cat_order,cat_name");
		$sql->gen('SELECT c.*, COUNT(i.item_id) AS items_count
			FROM #vstore_cat c
			LEFT JOIN #vstore_items i ON (c.cat_id = i.item_cat)
			WHERE i.item_inventory != 0 AND i.item_active = 1 AND c.cat_class IN (' . USERCLASS_LIST . ')
			GROUP BY c.cat_id
			ORDER BY cat_order');
		
		while($row = $sql->fetch())
		{
			$sublinks[] = array(
				'link_name'			=> $tp->toHTML($row['cat_name'], '', 'TITLE'),
				'link_url'			=> e107::url('vstore','category', $row),
				'link_description'	=> $tp->toHTML($row['cat_description'], '', 'DESCRIPTION'),
				'link_button'		=> '',
				'link_category'		=> '',
				'link_order'		=> '',
				'link_parent'		=> '',
				'link_open'			=> '',
				'link_class'		=> 0,
				'link_badge'		=> $row['items_count']
			);
		}
		
		return $sublinks;
	    
	}


	/**
	 * Top level categories with the number of products they hold (their subcategories included),
	 * for the category menu and the category bar of the catalog pages.
	 *
	 * @return array rows for {MENU_CAT}: link_id, link_name, link_url, link_image, link_badge
	 */
	function storeCategoryNav()
	{
		$sql = e107::getDb();
		$tp  = e107::getParser();
		$nav = array();

		// The same stock rule as the product list
		$stock = varset(e107::pref('vstore', 'show_outofstock'), true) ? '' : ' AND i.item_inventory != 0';

		$rows = (array) $sql->retrieve('SELECT c.*, (
				SELECT COUNT(*) FROM #vstore_items i
				WHERE i.item_active = 1' . $stock . '
				AND (i.item_cat = c.cat_id OR i.item_cat IN (SELECT s.cat_id FROM #vstore_cat s WHERE s.cat_parent = c.cat_id))
			) AS items_count
			FROM #vstore_cat c
			WHERE c.cat_parent = 0 AND c.cat_class IN (' . USERCLASS_LIST . ')
			ORDER BY c.cat_order, c.cat_name', true);

		// All rows are read first: url() and toHTML() run queries on the same connection
		foreach($rows as $row)
		{
			if(empty($row['items_count']))
			{
				continue;
			}

			$nav[] = array(
				'link_id'          => (int) $row['cat_id'],
				'link_name'        => $tp->toHTML($row['cat_name'], '', 'TITLE'),
				'link_url'         => e107::url('vstore', 'category', $row),
				'link_description' => $tp->toHTML($row['cat_description'], '', 'DESCRIPTION'),
				'link_image'       => $row['cat_image'],
				'link_badge'       => (int) $row['items_count'],
			);
		}

		return $nav;
	}



	function storeCart() // http://bootsnipp.com/snippets/33gmp
	{
		if (e_ADMIN_AREA) return;
		
		$vst = e107::getSingleton('vstore',e_PLUGIN.'vstore/vstore.class.php');
		$sc = e107::getScParser()->getScObject('vstore_shortcodes', 'vstore', false);

		$data = $vst->getCartData();
		$cust = $vst->getCustomerData();
		$data = $vst->prepareCheckoutData($data, false, true);

		$isBusiness = !empty($data['totals']['is_business']);
		$isLocal = !empty($data['totals']['is_local']);
                $isQuote = !empty($data['totals']['is_quote']);
		$tp = e107::getParser();
		$template = e107::getTemplate('vstore', 'vstore', 'navcart');

		//TODO Move into class.

		$text = '';
		if (!e_AJAX_REQUEST)
		{
			$text = $tp->parseTemplate($template['start'], true, $sc);
		}

		if(empty($data))
		{
			$text .= $tp->parseTemplate($template['empty'], true, $sc); 
			if (!e_AJAX_REQUEST) {
				$text .= $tp->parseTemplate($template['end'], true, $sc);
			}
			return $text;
		}


		$text .= $tp->parseTemplate($template['header'], true, $sc); 
		$total = 0;
		$itemcount = 0;


		foreach($data['items'] as $item)
		{
			$images = e107::unserialize($item['item_pic']);
			$img = !empty($images[0]['path'])
				? $tp->toImage($images[0]['path'], array('w' => 60, 'h' => 60, 'crop' => 1))
				: '<span class="vstore-noimg" aria-hidden="true">' . $tp->toGlyph('fa-cube') . '</span>';

			$itemcount += $item['cart_qty'];

			$sc->setVars(array(
				'item' => array(
					'pic' => $img,
					'price' => ($isBusiness && !$isLocal ? $item['item_price_net'] : $item['item_price']),
					'item_total' => ($isBusiness && !$isLocal ? $item['item_total_net'] : $item['item_total']),
					'name' => '<span class="vstore-navcart-name">'.$item['item_name'].'</span>'.($item['itemvarstring'] ? '<br/>'.$item['itemvarstring'] : ''),
					'quantity' => $item['cart_qty']					
				)
			));

$text .= $tp->parseTemplate((($isQuote || !empty($item['is_quote'])) && isset($template['item_quote'])) ? $template['item_quote'] : $template['item'], true, $sc);

			//$total += $subtotal;

		}


		$sc->setVars(array(
			'order_pay_amount' => ($isBusiness && !$isLocal ? $data['totals']['cart_subNet'] : $data['totals']['cart_subTotal']),
			'item_count' => $itemcount,
		));
$text .= $tp->parseTemplate(($isQuote && isset($template['footer_quote'])) ? $template['footer_quote'] : $template['footer'], true, $sc);
	
		if (!e_AJAX_REQUEST) {
			$text .= $tp->parseTemplate($template['end'], true, $sc);
		}		

		return $text;

	}

	
}
