<?php
	/**
	 * e107 website system
	 *
	 * Copyright (C) 2008-2017 e107 Inc (e107.org)
	 * Released under the terms and conditions of the
	 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
	 *
	 */

	$VSTORE_MENU_TEMPLATE = array();

	// Categories menu (menu area): picture, name and number of products
	$VSTORE_MENU_TEMPLATE['categories']['start'] = '
    <nav class="vstore vstore-catnav" aria-label="{LAN=LAN_VSTORE_FR_BROWSE_CATEGORIES}">
        <ul class="vstore-catnav-list">
    ';

	$VSTORE_MENU_TEMPLATE['categories']['end'] = '
        </ul>
    </nav>
    ';

	$VSTORE_MENU_TEMPLATE['categories']['item'] = '
		<li>
			<a href="{MENU_CAT:url}" class="vstore-catnav-link{MENU_CAT:active}">
				<span class="vstore-catnav-thumb">{MENU_CAT:image}</span>
				<span class="vstore-catnav-name">{MENU_CAT:name}</span>
				<span class="vstore-catnav-count">{MENU_CAT:badge}</span>
			</a>
		</li>
    ';

	// Category bar shown under the breadcrumb of the catalog pages: quick filter
	$VSTORE_MENU_TEMPLATE['bar']['start'] = '
    <nav class="vstore vstore-catbar" aria-label="{LAN=LAN_VSTORE_FR_BROWSE_CATEGORIES}">
        <ul class="vstore-catbar-list">
    ';

	$VSTORE_MENU_TEMPLATE['bar']['end'] = '
        </ul>
    </nav>
    ';

	$VSTORE_MENU_TEMPLATE['bar']['item'] = '
		<li>
			<a href="{MENU_CAT:url}" class="vstore-pill{MENU_CAT:active}">
				<span class="vstore-pill-name">{MENU_CAT:name}</span>
				<span class="vstore-pill-count">{MENU_CAT:badge}</span>
			</a>
		</li>
    ';

