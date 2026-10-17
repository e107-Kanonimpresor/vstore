<?php
if (!defined('e107_INIT')) { exit; }

return array(
	'LAN_VSTORE_ADMIN_COUP_TAB_RESTRICTIONS' => 'Restrictions',
	'LAN_VSTORE_ADMIN_COUP_TAB_LIMITS'       => 'Limits',

	'LAN_VSTORE_ADMIN_COUP_CODE'             => 'Coupon code',
	'LAN_VSTORE_ADMIN_COUP_CODE_H'           => 'Enter a unique code for this coupon. Use only uppercase letters and numbers. (no spaces)',
	'LAN_VSTORE_ADMIN_COUP_CODE_PH'          => 'eg. SPECIAL2021',
	'LAN_VSTORE_ADMIN_COUP_TYPE'             => 'Discount type',
	'LAN_VSTORE_ADMIN_COUP_TYPE_H'           => 'What kind of discount type will be used for this discount',
	'LAN_VSTORE_ADMIN_COUP_TYPE_PERCENT'     => 'Percentage',
	'LAN_VSTORE_ADMIN_COUP_TYPE_FIXED'       => 'Fixed',
	'LAN_VSTORE_ADMIN_COUP_AMOUNT'           => 'Discount amount',
	'LAN_VSTORE_ADMIN_COUP_AMOUNT_H'         => 'Define the discount amount',
	'LAN_VSTORE_ADMIN_COUP_START'            => 'Start date',
	'LAN_VSTORE_ADMIN_COUP_START_H'          => 'When should the coupon become available?',
	'LAN_VSTORE_ADMIN_COUP_END'              => 'End date',
	'LAN_VSTORE_ADMIN_COUP_END_H'            => 'When will the coupon become unavailable?',
	'LAN_VSTORE_ADMIN_COUP_CATS'             => 'Categories',
	'LAN_VSTORE_ADMIN_COUP_CATS_H'           => 'Categories this coupon will be assigned to',
	'LAN_VSTORE_ADMIN_COUP_CATS_EX'          => 'Exclude categories',
	'LAN_VSTORE_ADMIN_COUP_CATS_EX_H'        => 'Categories this coupon will never be assigned to',
	'LAN_VSTORE_ADMIN_COUP_LIMIT_COUPON'     => 'Usage limit per coupon',
	'LAN_VSTORE_ADMIN_COUP_LIMIT_COUPON_H'   => 'How many times this coupon can be used before it is void. Enter -1 for unlimited usage.',
	'LAN_VSTORE_ADMIN_COUP_LIMIT_USER'       => 'Usage limit per user',
	'LAN_VSTORE_ADMIN_COUP_LIMIT_USER_H'     => 'How many times this coupon can be used by an individual user. Enter -1 for unlimited usage.',
	'LAN_VSTORE_ADMIN_COUP_LIMIT_ITEM'       => 'Limit usage to X items',
	'LAN_VSTORE_ADMIN_COUP_LIMIT_ITEM_H'     => 'The max number of individual items this coupon can apply to when using product discounts. Enter -1 to apply to all qualifying items in cart.',

	'LAN_VSTORE_ADMIN_COUP_ERR_INVALID'      => 'Invalid coupon code!',
	'LAN_VSTORE_ADMIN_COUP_ERR_EXISTS'       => 'Coupon code already exists!',
);
