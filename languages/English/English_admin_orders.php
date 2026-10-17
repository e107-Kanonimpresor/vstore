<?php
if (!defined('e107_INIT')) { exit; }

return array(
	'LAN_VSTORE_ADMIN_ORD_TAB_LOG'         => 'Log',

	'LAN_VSTORE_ADMIN_ORD_PAY_STATUS'      => 'Pay Status',
	'LAN_VSTORE_ADMIN_ORD_REFUND_DATE'     => 'Refund date',
	'LAN_VSTORE_ADMIN_ORD_INVOICE_NR'      => 'Invoice #',
	'LAN_VSTORE_ADMIN_ORD_BILLING_TO'      => 'Billing to',
	'LAN_VSTORE_ADMIN_ORD_SHIP_TO'         => 'Ship to',
	'LAN_VSTORE_ADMIN_ORD_TRANSID'         => 'TransID',
	'LAN_VSTORE_ADMIN_ORD_CURRENCY'        => 'Currency',
	'LAN_VSTORE_ADMIN_ORD_SESSION'         => 'Session',
	'LAN_VSTORE_ADMIN_ORD_RAWDATA'         => 'Raw data',

	'LAN_VSTORE_ADMIN_ORD_PAYSTATUS_INCOMPLETE' => 'Incomplete',
	'LAN_VSTORE_ADMIN_ORD_PAYSTATUS_COMPLETE'   => 'Complete',
	'LAN_VSTORE_ADMIN_ORD_PAYSTATUS_REFUNDED'   => 'Refunded',

	'LAN_VSTORE_ADMIN_ORD_FILTER_OPEN'     => 'Open',
	'LAN_VSTORE_ADMIN_ORD_INVOICE_TIP'     => 'Click to open/generate the invoice pdf',
	'LAN_VSTORE_ADMIN_ORD_INVOICE_FORCE'   => 'Check to force the creation of a new invoice pdf during save',

	'LAN_VSTORE_ADMIN_ORD_BTN_CANCEL'      => 'Cancel order',
	'LAN_VSTORE_ADMIN_ORD_BTN_HOLD'        => 'Hold order',
	'LAN_VSTORE_ADMIN_ORD_BTN_PROCESS'     => 'Process order',
	'LAN_VSTORE_ADMIN_ORD_BTN_CHECK_PAYMENT' => 'Check payment in Mollie',
	'LAN_VSTORE_ADMIN_ORD_BTN_REFUND'      => 'Refund order',
	'LAN_VSTORE_ADMIN_ORD_BTN_COMPLETE'    => 'Complete order',
	'LAN_VSTORE_ADMIN_ORD_CONFIRM_CANCEL'  => 'Do you really want to cancel this order?',
	'LAN_VSTORE_ADMIN_ORD_CONFIRM_HOLD'    => 'Do you really want to set the order on hold?',
	'LAN_VSTORE_ADMIN_ORD_CONFIRM_REFUND'  => 'Do you really want to refund this order?',

	'LAN_VSTORE_ADMIN_ORD_COL_NAME'        => 'Name',
	'LAN_VSTORE_ADMIN_ORD_COL_DESCRIPTION' => 'Description',
	'LAN_VSTORE_ADMIN_ORD_COL_QTY'         => 'Qty.',
	'LAN_VSTORE_ADMIN_ORD_COL_PRICE'       => 'Price',
	'LAN_VSTORE_ADMIN_ORD_COL_DATETIME'    => 'Date/Time',
	'LAN_VSTORE_ADMIN_ORD_COL_USER'        => 'User',
	'LAN_VSTORE_ADMIN_ORD_NA'              => 'n/a',
	'LAN_VSTORE_ADMIN_ORD_NO_BILLING'      => 'No billing address set',
	'LAN_VSTORE_ADMIN_ORD_NO_SHIPPING'     => 'No shipping address set',

	'LAN_VSTORE_ADMIN_ORD_MSG_USERCLASS'   => 'The userclasses, the customer has been assigned to during the purchase can not be removed automatically.<br/>Click <a href="[x]">here</a> to remove the following userclasses manually.<br/>[y]',
	'LAN_VSTORE_ADMIN_ORD_MSG_REFUND_FAIL' => "Order couldn't be refunded!",
	'LAN_VSTORE_ADMIN_ORD_MSG_INV_FAIL'    => "Invoice couldn't be created!",
	'LAN_VSTORE_ADMIN_ORD_MSG_INV_OK'      => 'Invoice successfully created!',
);
