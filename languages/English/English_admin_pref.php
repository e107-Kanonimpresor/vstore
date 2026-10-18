<?php
if (!defined('e107_INIT')) { exit; }

return array(
	// Tabs
	'LAN_VSTORE_ADMIN_PREF_TAB_SHIP'      => 'Shipping',
	'LAN_VSTORE_ADMIN_PREF_TAB_TAX'       => 'Tax',
	'LAN_VSTORE_ADMIN_PREF_TAB_EMAIL'     => 'Emails',
	'LAN_VSTORE_ADMIN_PREF_TAB_HOWTO'     => 'How to Order',
	'LAN_VSTORE_ADMIN_PREF_TAB_ADMIN'     => 'Admin Area',
	'LAN_VSTORE_ADMIN_PREF_TAB_CHECKOUT'  => 'Check-Out',
	'LAN_VSTORE_ADMIN_PREF_TAB_MENU'      => 'Menu',
	'LAN_VSTORE_ADMIN_PREF_TAB_CSS'       => 'Custom CSS',

	// General
	'LAN_VSTORE_ADMIN_PREF_CAPTION'            => 'Store Caption',
	'LAN_VSTORE_ADMIN_PREF_CAPTION_H'          => 'The title shown at the top of the shop pages.',
	'LAN_VSTORE_ADMIN_PREF_CAPTION_CAT'        => 'Category Caption',
	'LAN_VSTORE_ADMIN_PREF_CAPTION_CAT_PH'     => 'Product Brands',
	'LAN_VSTORE_ADMIN_PREF_CAPTION_STOCK'      => 'Out-of-Stock Caption',
	'LAN_VSTORE_ADMIN_PREF_CAPTION_STOCK_PH'   => 'Out of Stock',
	'LAN_VSTORE_ADMIN_PREF_CURRENCY'           => 'Currency',
	'LAN_VSTORE_ADMIN_PREF_CURRENCY_H'         => 'Select a currency.',
	'LAN_VSTORE_ADMIN_PREF_AMOUNT_FORMAT'      => 'Price display',
	'LAN_VSTORE_ADMIN_PREF_AMOUNT_FORMAT_H'    => 'Select a format to be used for displaying item prices.',
	'LAN_VSTORE_ADMIN_PREF_AMOUNT_BEFORE'      => 'Currency before number',
	'LAN_VSTORE_ADMIN_PREF_AMOUNT_BEHIND'      => 'Currency behind number',
	'LAN_VSTORE_ADMIN_PREF_WEIGHT_UNIT'        => 'Weight unit',
	'LAN_VSTORE_ADMIN_PREF_WEIGHT_UNIT_H'      => 'Select a unit of weight.',
	'LAN_VSTORE_ADMIN_PREF_USERCLASS'          => 'Assign userclass',
	'LAN_VSTORE_ADMIN_PREF_USERCLASS_H'        => 'Assign userclass to the customer after completing their purchase.',
	'LAN_VSTORE_ADMIN_PREF_USERCLASS_PRODUCT'  => 'As defined in product',
	'LAN_VSTORE_ADMIN_PREF_SHOW_STOCK'         => 'Show/hide out-of-stock products',
	'LAN_VSTORE_ADMIN_PREF_SHOW_STOCK_H'       => 'Show or hide "Out-of-stock" products in product listings',

	// Shipping
	'LAN_VSTORE_ADMIN_PREF_SHIPPING'           => 'Calculate Shipping',
	'LAN_VSTORE_ADMIN_PREF_SHIPPING_H'         => 'Including shipping calculation at checkout.',
	'LAN_VSTORE_ADMIN_PREF_SHIP_METHOD'        => 'Calculation method',
	'LAN_VSTORE_ADMIN_PREF_SHIP_METHOD_H'      => 'Define a method to calculate the shipping cost.',
	'LAN_VSTORE_ADMIN_PREF_SHIP_SUM_SIMPLE'    => 'Sum of the shipping cost of all items',
	'LAN_VSTORE_ADMIN_PREF_SHIP_SUM_UNIQUE'    => 'Sum of the shipping cost of only unique items',
	'LAN_VSTORE_ADMIN_PREF_SHIP_TIERED'        => 'A tiered system based on price or weight',
	'LAN_VSTORE_ADMIN_PREF_SHIP_UNIT'          => 'Value based on',
	'LAN_VSTORE_ADMIN_PREF_SHIP_LIMIT'         => 'Costs are',
	'LAN_VSTORE_ADMIN_PREF_SHIP_DATA'          => 'Tiered shipping costs',
	'LAN_VSTORE_ADMIN_PREF_SHIP_DATA_H'        => 'Enter thresholds in the first column to set or limit shipping cost based on total order price or weight. Start with the lowest threshold and add more until your last threshold is higher than the maximum price/weight of a typical order. Setting the last threshold too low could result in no shipping cost at all.',
	'LAN_VSTORE_ADMIN_PREF_SHIP_UNIT_PRICE'    => 'When total price of order is below &mdash;',
	'LAN_VSTORE_ADMIN_PREF_SHIP_UNIT_WEIGHT'   => 'When total weight of order is below &mdash;',
	'LAN_VSTORE_ADMIN_PREF_SHIP_LIMIT_FIXED'   => 'Set shipping cost to &mdash;',
	'LAN_VSTORE_ADMIN_PREF_SHIP_LIMIT_MAX'     => 'Limit shipping cost to no more than &mdash;',

	// Emails
	'LAN_VSTORE_ADMIN_PREF_SENDER_NAME'        => 'Sender Name',
	'LAN_VSTORE_ADMIN_PREF_SENDER_NAME_PH'     => 'Sales Department',
	'LAN_VSTORE_ADMIN_PREF_SENDER_H'           => 'Leave blank to use system default',
	'LAN_VSTORE_ADMIN_PREF_SENDER_EMAIL_PH'    => 'orders@mysite.com',
	'LAN_VSTORE_ADMIN_PREF_MERCHANT'           => 'Merchant Name/Address',
	'LAN_VSTORE_ADMIN_PREF_MERCHANT_PH'        => 'My Store Inc. etc.',
	'LAN_VSTORE_ADMIN_PREF_MERCHANT_H'         => 'Will be displayed on customer email.',

	// How to order
	'LAN_VSTORE_ADMIN_PREF_HOWTO'              => 'How to order',
	'LAN_VSTORE_ADMIN_PREF_HOWTO_H'            => 'Enter how-to-order info.',

	// Admin area
	'LAN_VSTORE_ADMIN_PREF_ITEMS_PERPAGE'      => 'Products per page',
	'LAN_VSTORE_ADMIN_PREF_CATS_PERPAGE'       => 'Categories per page',

	// Check-out
	'LAN_VSTORE_ADMIN_PREF_ADDITIONAL'         => 'Additional Fields',
	'LAN_VSTORE_ADMIN_PREF_ADD_PLACEHOLDER'    => 'Placeholder',
	'LAN_VSTORE_ADMIN_PREF_ADD_FIELDTYPE'      => 'Field type',
	'LAN_VSTORE_ADMIN_PREF_ADD_REQUIRED'       => 'Required',
	'LAN_VSTORE_ADMIN_PREF_ADD_TEXTBOX'        => 'Text Box',
	'LAN_VSTORE_ADMIN_PREF_ADD_CHECKBOX'       => 'Check box',

	// Custom CSS
	'LAN_VSTORE_ADMIN_PREF_CSS'                => 'Custom CSS',
	'LAN_VSTORE_ADMIN_PREF_CSS_H'              => 'Use this field to enter any Vstore related custom css, without the need to edit any source files.',

	// Tax
	'LAN_VSTORE_ADMIN_PREF_TAX_CALC'           => 'Calculate tax',
	'LAN_VSTORE_ADMIN_PREF_TAX_CALC_H'         => 'Enable to activate tax calculation.',
	'LAN_VSTORE_ADMIN_PREF_TAX_COUNTRY'        => 'Business country',
	'LAN_VSTORE_ADMIN_PREF_TAX_COUNTRY_H'      => 'The country where the business is located.',
	'LAN_VSTORE_ADMIN_PREF_TAX_VAT'            => 'Check VAT id online (EU only!)',
	'LAN_VSTORE_ADMIN_PREF_TAX_VAT_H'          => 'Enable to activate online VAT id checking. (EU only!)',
	'LAN_VSTORE_ADMIN_PREF_TAX_ORIGIN'         => 'Charge the VAT of the shop country to EU consumers',
	'LAN_VSTORE_ADMIN_PREF_TAX_ORIGIN_H'       => 'Since July 2021 the VAT of the customer country applies to sales to consumers in other EU countries (OSS). Sellers whose EU-wide distance sales stay below 10,000 EUR a year may charge the VAT of their own country instead. Enable it only if that is your case; ask your accountant.',
	'LAN_VSTORE_ADMIN_PREF_TAX_CLASSES'        => 'Tax classes',
	'LAN_VSTORE_ADMIN_PREF_TAX_CLASSES_H'      => 'The tax classes and default tax value to use with the products.<br />Enter tax value as decimal number. eg. 0.19 for 19%',
	'LAN_VSTORE_ADMIN_PREF_TAX_NAME'           => 'Name',
	'LAN_VSTORE_ADMIN_PREF_TAX_DESCRIPTION'    => 'Description',
	'LAN_VSTORE_ADMIN_PREF_TAX_VALUE'          => 'Tax',
	'LAN_VSTORE_ADMIN_PREF_TAX_DEF_NONE'       => 'No tax',
	'LAN_VSTORE_ADMIN_PREF_TAX_DEF_REDUCED'    => 'Reduced tax',
	'LAN_VSTORE_ADMIN_PREF_TAX_DEF_STANDARD'   => 'Standard tax',
	'LAN_VSTORE_ADMIN_PREF_TAX_ORDER_WARNING'  => 'Tax classes seem not to be in order!<br>The first 3 must be "none", "reduced", "standard"!<br/>Add your country specific classes after them.',

	// Menu
	'LAN_VSTORE_ADMIN_PREF_MENU_CAT'           => 'Product category',
	'LAN_VSTORE_ADMIN_PREF_MENU_ALL'           => 'All categories (latest products)',
	'LAN_VSTORE_ADMIN_PREF_MENU_COUNT'         => 'Product limit',
	'LAN_VSTORE_ADMIN_PREF_MENU_COUNT_H'       => 'Enter the number of products to display in the menu.',
);
