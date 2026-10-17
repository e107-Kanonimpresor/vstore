<?php
if (!defined('e107_INIT')) { exit; }

return array(
	// Categories
	'LAN_VSTORE_ADMIN_CAT_NAME_H'        => 'The category name shown in the shop.',
	'LAN_VSTORE_ADMIN_CAT_DESCRIPTION_H' => 'A short text shown below the category name (maximum 220 characters).',
	'LAN_VSTORE_ADMIN_CAT_SEF_H'         => 'URL-friendly name. Leave it empty to generate it from the title.',
	'LAN_VSTORE_ADMIN_CAT_PARENT_H'      => 'Choose a parent category to create a sub-category.',
	'LAN_VSTORE_ADMIN_CAT_IMAGE_H'       => 'Image shown on the category page.',
	'LAN_VSTORE_ADMIN_CAT_VISIBILITY_H'  => 'Who should this category be visible to?',
	'LAN_VSTORE_ADMIN_CAT_ORDER_H'       => 'Position among the other categories.',

	// Product variations
	'LAN_VSTORE_ADMIN_VAR_NAME_H'        => 'Enter a name for this category of variations.',
	'LAN_VSTORE_ADMIN_VAR_NAME_PH'       => 'Variation name. eg. Colors',
	'LAN_VSTORE_ADMIN_VAR_ATTR'          => 'Price Modification',
	'LAN_VSTORE_ADMIN_VAR_ATTR_H'        => 'Enter a name for each variation. Optionally increase or decrease the price of this product variation by a fixed amount (+/-). Or, adjust the price as a percentage (%) of the original price.',
	'LAN_VSTORE_ADMIN_VAR_ATTR_EX1'      => 'eg. Red',
	'LAN_VSTORE_ADMIN_VAR_ATTR_EX2'      => 'eg. Blue',
	'LAN_VSTORE_ADMIN_VAR_TRACK'         => 'Track inventory',
	'LAN_VSTORE_ADMIN_VAR_TRACK_H'       => 'When enabled, the inventory of this product variation will be tracked separately.',
);
