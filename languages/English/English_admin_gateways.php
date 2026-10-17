<?php
if (!defined('e107_INIT')) { exit; }

return array(
	'LAN_VSTORE_ADMIN_GATE_TAB_BANK'          => 'Bank Transfer',
	'LAN_VSTORE_ADMIN_GATE_MOLLIE_TEST'       => 'Mollie Testmode',
	'LAN_VSTORE_ADMIN_GATE_MOLLIE_TEST_H'     => 'Use Mollie Testmode',
	'LAN_VSTORE_ADMIN_GATE_MOLLIE_KEY_LIVE'   => 'Mollie Live API key',
	'LAN_VSTORE_ADMIN_GATE_MOLLIE_KEY_TEST'   => 'Mollie Test API key',
	'LAN_VSTORE_ADMIN_GATE_MOLLIE_KEYS_NOTE'  => 'Get your api keys <a href="https://www.mollie.com/dashboard/developers/api-keys">here</a>',
	'LAN_VSTORE_ADMIN_GATE_MOLLIE_METHODS'    => 'Mollie Payment methods',
	'LAN_VSTORE_ADMIN_GATE_MOLLIE_METHODS_NOTE' => 'Select at least 1 payment method.<br/>The payment method MUST BE enabled in your Mollie dashboard BEFORE you can use it with Vstore!<br/>Be aware that not all methods support all currencies!',
	'LAN_VSTORE_ADMIN_GATE_BANK_TITLE_DEF'    => 'Bank Transfer',
	'LAN_VSTORE_ADMIN_GATE_BANK_DETAILS'      => 'Bank Transfer',
	'LAN_VSTORE_ADMIN_GATE_BANK_DETAILS_PH'   => 'Bank Account Details',
	'LAN_VSTORE_ADMIN_GATE_CLASSNAME'         => 'Class name',
	'LAN_VSTORE_ADMIN_GATE_DEPRECATED_TITLE'  => 'Important',
	'LAN_VSTORE_ADMIN_GATE_RETIRED'           => 'These gateways are no longer supported and are never offered at checkout: [x]. Their saved settings are ignored. Use Mollie (cards and local methods), PayPal or bank transfer.',
	'LAN_VSTORE_ADMIN_GATE_MOLLIE_CURRENCY'   => 'The Mollie methods [x] only accept euros, so they are not offered at checkout while the shop currency is [y]. Change the currency to EUR in Preferences to offer them.',
	'LAN_VSTORE_ADMIN_GATE_DEPRECATED_TEXT'   => '<p>The way in which gateway preferences are stored has been changed.</p><p><b>These old preferences have now been deleted.</b></p><p>You may re-enter these values in the form below if you wish. </p>',
);
