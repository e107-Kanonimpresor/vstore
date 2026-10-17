<?php
if (!defined('e107_INIT')) { exit; }

// Texts of the e_cron, e_search, e_sitelink and e_dashboard addons.
return array(
	'LAN_VSTORE_HOOK_CRON_NAME'         => 'Vstore - Purge cart',
	'LAN_VSTORE_HOOK_CRON_DESC'         => "Delete cart entries that haven't been touched for 2 days",
	'LAN_VSTORE_HOOK_SEARCH_CATEGORY'   => 'Search in category',
	'LAN_VSTORE_HOOK_SITELINK_CATS'     => 'Vstore Categories',
	'LAN_VSTORE_HOOK_SITELINK_CART'     => 'Vstore Shopping Cart',
	'LAN_VSTORE_HOOK_DASH_CHART'        => 'Vstore Open/Paid orders',
	'LAN_VSTORE_HOOK_DASH_NO_ORDERS'    => 'No orders available for the last week.',
	'LAN_VSTORE_HOOK_DASH_OPEN'         => 'Open',
	'LAN_VSTORE_HOOK_DASH_PAID'         => 'Paid',
	'LAN_VSTORE_HOOK_DASH_OPEN_ORDERS'  => 'Open orders',
	'LAN_VSTORE_HOOK_DASH_NO_TRANS'     => 'No Transactions Found',
	'LAN_VSTORE_HOOK_DASH_DAY'          => 'Day',
	'LAN_VSTORE_HOOK_DASH_COMPLETE'     => 'Complete',
	'LAN_VSTORE_HOOK_DASH_TOTAL'        => 'Total',
	'LAN_VSTORE_HOOK_DASH_REVENUE'      => 'Monthly Revenue',
	'LAN_VSTORE_HOOK_DASH_SALES'        => 'Number of Sales',
);
