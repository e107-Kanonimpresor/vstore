<?php
/*
 * e107 website system
 *
 * Copyright (C) 2008-2016 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * e107 Plugin - Vstore product showcase menu
 *
 * Product cards (same look as the product pages) for any menu area, with optional category pills.
 * Options are set in the Menu Manager (see e_menu.php): caption, category, limit, columns, order, nav.
*/

if (!defined('e107_INIT'))
{
	exit;
}

require_once(e_PLUGIN . 'vstore/vstore.class.php');

// renderMenu() hands the saved options over in $parm
global $menu_pref;
$vstoreMenuParms = Vstore\Support\ShowcaseOptions::normalize(
	Vstore\Support\ShowcaseOptions::parse(isset($parm) ? $parm : (isset($menu_pref) ? $menu_pref : null))
);

$vstoreShowcase = new vstore;
$text = $vstoreShowcase->showcase($vstoreMenuParms);

if ($text === '')
{
	return;
}

e107::lan('vstore', false, true);

$caption = $vstoreMenuParms['caption'] === ''
	? LAN_VSTORE_MENU_SHOWCASE
	: e107::getParser()->toHTML($vstoreMenuParms['caption'], false, 'TITLE');

e107::getRender()->tablerender($caption, $text, 'vstore-showcase-menu');
