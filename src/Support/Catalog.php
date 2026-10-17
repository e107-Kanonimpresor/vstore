<?php
/**
 * Vstore - shopping cart plugin for e107.
 * Copyright (c) 2026 Kanonimpresor.
 *
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Vstore\Support;

/**
 * Static reference data of the shop. Labels are English defaults;
 * the presentation layer translates them with LAN_VSTORE_* constants.
 */
final class Catalog
{
	/** ISO 4217 code => title and either a symbol or a fontawesome glyph. */
	const CURRENCIES = array(
		'USD' => array('title' => 'US Dollars', 'symbol' => '$'),
		'CAN' => array('title' => 'Canadian Dollars', 'symbol' => '$'),
		'EUR' => array('title' => 'Euros', 'symbol' => '€'),
		'GBP' => array('title' => 'GB Pounds', 'symbol' => '£'),
		'BTC' => array('title' => 'Bitcoin', 'glyph' => 'fa-btc'),
	);

	const GATEWAYS = array(
		'paypal'        => array('title' => 'Paypal', 'icon' => 'fa-paypal'),
		'paypal_rest'   => array('title' => 'Paypal', 'icon' => 'fa-paypal'),
		'mollie'        => array('title' => 'Mollie', 'icon' => 'fa-laptop'),
		'bank_transfer' => array('title' => 'Bank Transfer', 'icon' => 'fa-bank'),
	);

	/** Mollie methods, keyed with the mollie_ prefix; every entry needs title and icon. */
	const MOLLIE_PAYMENT_METHODS = array(
		'mollie_bancontact'     => array('title' => 'Bancontact', 'icon' => 'images/bancontact.svg'),
		'mollie_banktransfer'   => array('title' => 'SEPA Bank transfer', 'icon' => 'images/sepa.svg'),
		'mollie_belfius'        => array('title' => 'Belfius Direct Net', 'icon' => 'images/belfius.svg'),
		'mollie_creditcard'     => array('title' => 'Creditcard', 'icon' => 'images/amex.svg'),
		'mollie_directdebit'    => array('title' => 'SEPA Direct debit', 'icon' => 'images/sepa.svg'),
		'mollie_eps'            => array('title' => 'EPS', 'icon' => 'images/eps.svg'),
		'mollie_giftcard'       => array('title' => 'Gift Cards', 'icon' => 'images/giftcards.svg'),
		'mollie_giropay'        => array('title' => 'Giropay', 'icon' => 'images/giropay.svg'),
		'mollie_ideal'          => array('title' => 'iDeal', 'icon' => 'images/ideal.svg'),
		'mollie_inghomepay'     => array('title' => 'Ing Homepay', 'icon' => 'images/inghomepay.svg'),
		'mollie_kbc'            => array('title' => 'Kbc Payment Button', 'icon' => 'images/kbc.svg'),
		'mollie_klarnapaylater' => array('title' => 'Klarna Pay Later', 'icon' => 'images/klarnapaylater.svg'),
		'mollie_klarnasliceit'  => array('title' => 'Klarna Slice it', 'icon' => 'images/klarnasliceit.svg'),
		'mollie_mbway'          => array('title' => 'MB WAY', 'icon' => 'images/mbway.svg'),
		'mollie_multibanco'     => array('title' => 'Multibanco', 'icon' => 'images/multibanco.svg'),
		'mollie_paypal'         => array('title' => 'Paypal', 'icon' => 'images/paypal.svg'),
		'mollie_paysafecard'    => array('title' => 'Paysafecard', 'icon' => 'images/paysafecard.svg'),
		'mollie_sofort'         => array('title' => 'SOFORT Banking', 'icon' => 'images/sofort.svg'),
	);

	/** Order status code => English name (the lower-case name is the email template key). */
	const STATUS = array(
		'N' => 'New',
		'P' => 'Processing',
		'H' => 'On Hold',
		'C' => 'Completed',
		'X' => 'Cancelled',
		'R' => 'Refunded',
	);

	const EMAIL_TYPES = array(
		'default'   => 'Order confirmation',
		'completed' => 'Order completed',
		'cancelled' => 'Order cancelled',
		'refunded'  => 'Order refunded',
	);

	const SHIPPING_FIELDS = array(
		'firstname', 'lastname', 'phone', 'company', 'address', 'city', 'state', 'zip', 'country', 'notes',
	);

	/** Customer notes are internal and therefore not listed. */
	const CUSTOMER_FIELDS = array(
		'title', 'firstname', 'lastname', 'company', 'vat_id', 'taxcode', 'address', 'city', 'state', 'zip',
		'country', 'email', 'phone', 'fax', 'additional_fields',
	);

	const TAX_CLASSES = array('none', 'reduced', 'reduced1', 'reduced2', 'super_reduced', 'standard', 'parking');

	/** Untranslated lower-case status name used by logic such as email template selection. */
	public static function statusSlug($code)
	{
		return strtolower(self::STATUS[$code]);
	}

	/** Gateways that once shipped with Vstore but never had a working checkout: their saved settings are ignored. */
	const RETIRED_GATEWAYS = array('stripe', 'braintree', 'coinpayments', 'stark');

	public static function isRetiredGateway($name)
	{
		return in_array(strtolower((string) $name), self::RETIRED_GATEWAYS, true);
	}

	public static function isMollie($type)
	{
		return substr($type, 0, 6) === 'mollie';
	}

	/** Mollie methods that only accept euros (the others accept several currencies). */
	const MOLLIE_EUR_ONLY = array('ideal', 'bancontact', 'belfius', 'kbc', 'eps', 'mbway', 'multibanco', 'banktransfer', 'directdebit', 'giftcard');

	/** Can this payment method charge the given currency? Gateways other than Mollie are not restricted here. */
	public static function supportsCurrency($type, $currency)
	{
		if (!self::isMollie($type) || strtoupper((string) $currency) === 'EUR')
		{
			return true;
		}

		return !in_array(substr($type, 7), self::MOLLIE_EUR_ONLY, true);
	}
}
