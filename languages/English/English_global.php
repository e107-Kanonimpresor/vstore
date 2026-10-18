<?php
	/**
	 * e107 website system
	 *
	 * Copyright (C) 2008-2016 e107 Inc (e107.org)
	 * Released under the terms and conditions of the
	 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
	 *
	 */

define("LAN_PLUGIN_VSTORE_NAME", "Shop");

// Order status
define("LAN_VSTORE_STATUS_N", "New");
define("LAN_VSTORE_STATUS_P", "Processing");
define("LAN_VSTORE_STATUS_H", "On Hold");
define("LAN_VSTORE_STATUS_C", "Completed");
define("LAN_VSTORE_STATUS_X", "Cancelled");
define("LAN_VSTORE_STATUS_R", "Refunded");
define("LAN_VSTORE_STATUS_Q", "Quote request");
define("LAN_VSTORE_STATUS_M", "Migrated to app");
define("LAN_VSTORE_STATUS_A", "Quote approved");
define("LAN_VSTORE_STATUS_F", "Invoiced");

// Email types
define("LAN_VSTORE_EMAILTYPE_DEFAULT", "Order confirmation");
define("LAN_VSTORE_EMAILTYPE_COMPLETED", "Order completed");
define("LAN_VSTORE_EMAILTYPE_CANCELLED", "Order cancelled");
define("LAN_VSTORE_EMAILTYPE_REFUNDED", "Order refunded");
define("LAN_VSTORE_EMAILTYPE_QUOTE", "Quote request");

// Weight units
define("LAN_VSTORE_WEIGHT_G", "Grams");
define("LAN_VSTORE_WEIGHT_KG", "Kilograms");
define("LAN_VSTORE_WEIGHT_LB", "Pounds");
define("LAN_VSTORE_WEIGHT_OZ", "Ounces");
define("LAN_VSTORE_WEIGHT_CARAT", "Carats");

// Currencies
define("LAN_VSTORE_CURRENCY_USD", "US Dollars");
define("LAN_VSTORE_CURRENCY_CAN", "Canadian Dollars");
define("LAN_VSTORE_CURRENCY_EUR", "Euros");
define("LAN_VSTORE_CURRENCY_GBP", "GB Pounds");
define("LAN_VSTORE_CURRENCY_BTC", "Bitcoin");
