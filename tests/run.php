<?php
/**
 * Business-layer regression tests (no e107, no database, no network).
 *
 *   php tests/run.php
 */

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/autoload.php';

use Vstore\Repository\CouponUsageRepositoryInterface;
use Vstore\Repository\CartRepositoryInterface;
use Vstore\Repository\CustomerRepositoryInterface;
use Vstore\Repository\ItemRepositoryInterface;
use Vstore\Repository\ItemVarRepositoryInterface;
use Vstore\Service\CartService;
use Vstore\Service\CheckoutService;
use Vstore\Service\CouponService;
use Vstore\Service\OrderService;
use Vstore\Service\InventoryService;
use Vstore\Service\InvoiceNumberService;
use Vstore\Service\InvoiceService;
use Vstore\Service\ItemVarTypeService;
use Vstore\Service\PaymentService;
use Vstore\Service\ShippingService;
use Vstore\Service\TaxService;
use Vstore\Support\TaxNumber;
use Vstore\Service\UserclassService;
use Vstore\Service\VatService;
use Vstore\Support\Catalog;
use Vstore\Support\ItemVars;
use Vstore\Support\MolliePayment;
use Vstore\Support\PhoneNumber;
use Vstore\Support\Result;
use Vstore\Support\ResultTranslator;

$failures = 0;
$total    = 0;

function check($name, $actual, $expected)
{
	global $failures, $total;
	$total++;

	if ($actual === $expected || (is_float($expected) && is_numeric($actual) && abs($actual - $expected) < 0.00001))
	{
		echo "  ok    $name\n";
		return;
	}

	$failures++;
	echo "  FAIL  $name\n        expected " . var_export($expected, true) . ', got ' . var_export($actual, true) . "\n";
}

class FakeUsage implements CouponUsageRepositoryInterface
{
	public $total = 0;
	public $user  = 0;

	public function totalUsage($couponCode)
	{
		return $this->total;
	}

	public function userUsage($couponCode, $userId)
	{
		return $this->user;
	}
}

class FakeRates
{
	private $table = array(
		'ES' => array('standard' => 21.0, 'reduced' => 10.0, 'super_reduced' => 4.0),
		'FR' => array('standard' => 20.0, 'reduced' => 5.5, 'reduced1' => 10.0),
		'DE' => array('standard' => 19.0, 'reduced' => 7.0),
	);

	public function getRateForCountry($country, $level = 'standard')
	{
		if (!isset($this->table[$country][$level]))
		{
			throw new \Exception("No rate for $country/$level");
		}

		return $this->table[$country][$level];
	}
}

class FakeVies
{
	public $valid  = true;
	public $throws = false;
	public $calls  = 0;

	public function validateVatNumber($number)
	{
		$this->calls++;
		if ($this->throws)
		{
			throw new \Exception('VIES down');
		}

		return $this->valid;
	}
}

// ---------------------------------------------------------------- coupons
echo "Coupons\n";

$now    = 1760000000;
$usage  = new FakeUsage();
$svc    = new CouponService($usage, null, function () use ($now) { return $now; });
$base   = array(
	'coupon_code' => 'SAVE10', 'coupon_active' => 1, 'coupon_type' => '%', 'coupon_amount' => 10,
	'coupon_start' => 0, 'coupon_end' => 0, 'coupon_limit_coupon' => -1, 'coupon_limit_user' => -1,
	'coupon_items' => '', 'coupon_items_ex' => '', 'coupon_cats' => '', 'coupon_cats_ex' => '',
);
$item   = array('item_id' => 7, 'item_cat' => 3, 'item_price' => 50.0, 'cart_qty' => 2, 'item_name' => 'Printer');

$r = $svc->calculate($base, $item);
check('percentage discount', $r->value(), -10.0);
check('percentage discount has no message', $r->hasMessage(), false);

$r = $svc->calculate(array('coupon_active' => 0) + $base, $item);
check('inactive coupon', array($r->code(), $r->isOk(), $r->value()), array('COUPON_NA', false, 0.0));

$r = $svc->calculate(array('coupon_start' => $now + 3600) + $base, $item);
check('future coupon', array($r->code(), $r->value()), array('COUPON_NOT_YET', 0.0));
check('future coupon date param', $r->params(), array(date('Y-m-d H:i:s', $now + 3600)));

$r = $svc->calculate(array('coupon_end' => $now - 3600) + $base, $item);
check('expired coupon', array($r->code(), $r->value()), array('COUPON_EXPIRED', 0.0));

$r = $svc->calculate(array('coupon_start' => '2020-01-01 00:00:00', 'coupon_end' => '2099-01-01 00:00:00') + $base, $item);
check('datetime strings are accepted', $r->value(), -10.0);

$usage->total = 5;
$r = $svc->calculate(array('coupon_limit_coupon' => 5) + $base, $item);
check('global limit reached', $r->code(), 'COUPON_LIMIT');
$r = $svc->calculate(array('coupon_limit_coupon' => 6) + $base, $item);
check('global limit not reached', $r->value(), -10.0);
check('limit 0 blocks the coupon', $svc->calculate(array('coupon_limit_coupon' => 0) + $base, $item)->code(), 'COUPON_LIMIT');

$usage->user = 2;
$r = $svc->calculate(array('coupon_limit_user' => 2) + $base, $item, 15);
check('user limit reached', $r->code(), 'COUPON_LIMIT_USER');
$r = $svc->calculate(array('coupon_limit_user' => 2) + $base, $item, 0);
check('user limit ignored for guests', $r->value(), -10.0);

check('excluded category', $svc->calculate(array('coupon_cats_ex' => '3,4') + $base, $item)->value(), 0.0);
check('excluded item', $svc->calculate(array('coupon_items_ex' => '7') + $base, $item)->value(), 0.0);
check('exclusion beats inclusion', $svc->calculate(array('coupon_cats' => '3', 'coupon_items_ex' => '7') + $base, $item)->value(), 0.0);
check('category not included', $svc->calculate(array('coupon_cats' => '9') + $base, $item)->value(), 0.0);
check('category included', $svc->calculate(array('coupon_cats' => '3') + $base, $item)->value(), -10.0);
check('item not included', $svc->calculate(array('coupon_items' => '1,2') + $base, $item)->value(), 0.0);

$r = $svc->calculate(array('coupon_type' => 'fixed', 'coupon_amount' => 5) + $base, $item);
check('fixed discount per unit', $r->value(), -10.0);
$r = $svc->calculate(array('coupon_type' => 'fixed', 'coupon_amount' => 80) + $base, $item);
check('fixed discount capped to item price', array($r->code(), $r->isOk(), $r->value(), $r->params()), array('COUPON_CAPPED', true, -100.0, array('Printer')));
check('empty coupon', $svc->calculate(array(), $item)->value(), 0.0);

// ------------------------------------------------------------------- tax
echo "Tax\n";

$vies = new FakeVies();
$tax  = new TaxService(new FakeRates(), new VatService($vies), null);
$pref = array(
	'tax_calculate' => 1, 'tax_business_country' => 'ES', 'tax_check_vat' => 1,
	'tax_classes' => array(array('name' => 'standard', 'value' => 0.21), array('name' => 'reduced', 'value' => 0.10)),
);

check('same country, standard', $tax->rate('standard', 'ES', $pref)->value(), 0.21);
check('same country, reduced', $tax->rate('reduced', 'es', $pref)->value(), 0.10);
check('same country, unknown class', $tax->rate('parking', 'ES', $pref)->value(), 0.0);
check('class none', $tax->rate('none', 'ES', $pref)->value(), 0.0);
check('tax disabled', $tax->rate('standard', 'ES', array('tax_calculate' => 0) + $pref)->value(), 0.0);
check('empty country falls back to business country', $tax->rate('standard', '', $pref)->value(), 0.21);
check('intra-EU B2C uses customer country rate', $tax->rate('standard', 'FR', $pref)->value(), 0.20);
check('intra-EU reduced class', $tax->rate('reduced', 'FR', $pref)->value(), 0.055);
check('missing class is substituted (reduced2 in FR -> reduced)', $tax->rate('reduced2', 'FR', $pref)->value(), 0.055);
check('missing class is substituted (reduced1 in DE -> reduced)', $tax->rate('reduced1', 'DE', $pref)->value(), 0.07);

$vies->valid = true;
check('reverse charge with valid VAT ID', $tax->rate('standard', 'DE', $pref, 'DE123456789')->value(), 0.0);
$vies->valid = false;
check('invalid VAT ID keeps customer country tax', $tax->rate('standard', 'DE', $pref, 'DE123456789')->value(), 0.19);
$vies->valid = true;
check('VAT ID ignored when checking is disabled', $tax->rate('standard', 'DE', array('tax_check_vat' => 0) + $pref, 'DE123456789')->value(), 0.19);
$vies->throws = true;
check('VIES outage accepts the VAT ID', $tax->rate('standard', 'DE', $pref, 'DE123456789')->value(), 0.0);
$vies->throws = false;

check('customer outside EU', $tax->rate('standard', 'US', $pref)->value(), 0.0);
check('business outside EU', $tax->rate('standard', 'GB', array('tax_business_country' => 'US') + $pref)->value(), 0.0);

$r = $tax->rate('standard', 'PL', $pref);
check('country without rates reports TAX_CLASS', array($r->code(), $r->isOk(), $r->value()), array('TAX_CLASS', false, 0.0));

check('net price', $tax->netPrice(120.0, 0.2), 100.0);
check('tax amount', $tax->taxAmount(100.0, 0.2), 20.0);

$originPref = array('tax_origin_vat' => 1) + $pref;
check('origin VAT: EU consumer pays the shop country rate', $tax->rate('standard', 'FR', $originPref)->value(), 0.21);
check('origin VAT: reduced class uses the shop rate too', $tax->rate('reduced', 'FR', $originPref)->value(), 0.10);
check('origin VAT: reverse charge still wins', $tax->isReverseCharge('DE', $originPref, 'DE123456789'), true);
check('origin VAT: customers outside the EU stay without tax', $tax->rate('standard', 'US', $originPref)->value(), 0.0);
check('destination VAT stays the default', $tax->rate('standard', 'FR', $pref)->value(), 0.20);

$vies->valid = true;
check('reverse charge: other EU country with a VAT ID', $tax->isReverseCharge('PT', $pref, 'PT501964843'), true);
$vies->valid = false;
check('no reverse charge when VIES refuses the VAT ID', $tax->isReverseCharge('PT', $pref, 'PT123456789'), false);
$vies->valid = true;
check('no reverse charge without a VAT ID', $tax->isReverseCharge('PT', $pref, ''), false);
check('no reverse charge inside the shop country', $tax->isReverseCharge('ES', $pref, 'ES12345678Z'), false);
check('no reverse charge with checking disabled', $tax->isReverseCharge('PT', array('tax_check_vat' => 0) + $pref, 'PT501964843'), false);
check('no reverse charge with tax disabled', $tax->isReverseCharge('PT', array('tax_calculate' => 0) + $pref, 'PT501964843'), false);
check('no reverse charge outside the EU', $tax->isReverseCharge('US', $pref, 'US123456789'), false);

$vies2 = new FakeVies();
$store = array();
$cached = new VatService($vies2);
$cached->setStore(function ($k) use (&$store) { return isset($store[$k]) ? $store[$k] : null; }, function ($k, $r) use (&$store) { $store[$k] = $r; });
$cached->validate('DE123456789', 'DE', true);
$cached->validate('de 123456789', 'DE', true);
check('a stored VIES answer is not asked again', $vies2->calls, 1);
$vies2->throws = true;
$cached->validate('FR99999999999', 'FR', true);
check('an outage is not stored', array_key_exists('FR99999999999', $store), false);

// ----------------------------------------------------------------- VAT ID
echo "VAT\n";

$vat = new VatService($vies);
check('validate disabled', $vat->validate('DE123', 'DE', false)['valid'], true);
check('validate strips duplicated country prefix', $vat->validate('de 123456789', 'DE', true)['valid'], true);
$vies->valid = false;
check('validate invalid', $vat->validate('DE123456789', 'DE', true), array('valid' => false, 'name' => '', 'address' => '', 'error' => 'Invalid VAT ID'));
$vies->valid = true;
check('empty VAT ID is acceptable', $vat->isAcceptable('', 'DE', true), true);
check('missing country is not acceptable', $vat->isAcceptable('DE123456789', '', true), false);
check('VAT ID country must match', $vat->isAcceptable('FR123456789', 'DE', true), false);
check('VAT ID accepted', $vat->isAcceptable('DE123456789', 'DE', true), true);
check('non EU VAT ID is not checked', $vat->isAcceptable('US123456789', 'US', true), true);
$vies->valid = false;
check('unknown EU VAT ID rejected', $vat->isAcceptable('DE123456789', 'DE', true), false);
check('checking disabled accepts', $vat->isAcceptable('DE123456789', 'DE', false), true);

$vies->valid = false;
check('own country NIF is not asked to VIES', $vat->isAcceptable('123456789', 'PT', true, 'PT'), true);
check('own country NIF with country prefix', $vat->isAcceptable('PT123456789', 'pt', true, 'pt'), true);
check('own country NIF with a wrong check digit', $vat->isAcceptable('123456780', 'PT', true, 'PT'), false);
check('own country NIF with an unknown first digit', $vat->isAcceptable('423456789', 'PT', true, 'PT'), false);
check('other EU country still needs VIES', $vat->isAcceptable('ES12345678Z', 'ES', true, 'PT'), false);
check('other country without a rule of its own is accepted when it is the shop country', $vat->isAcceptable('12345', 'DE', true, 'DE'), true);
$vies->valid = true;
check('other EU country accepted by VIES', $vat->isAcceptable('ES12345678Z', 'ES', true, 'PT'), true);

echo "Tax numbers\n";
check('valid Portuguese NIFs', array(TaxNumber::isValid('PT', '123456789'), TaxNumber::isValid('PT', 'PT 123 456 789'), TaxNumber::isValid('pt', '501 964 843')), array(true, true, true));
check('invalid Portuguese NIFs', array(TaxNumber::isValid('PT', '12345678'), TaxNumber::isValid('PT', '1234567890'), TaxNumber::isValid('PT', 'ABCDEFGHI'), TaxNumber::isValid('PT', '123456788')), array(false, false, false, false));
check('countries without a national rule', TaxNumber::isValid('FR', 'anything'), true);

// -------------------------------------------------------------- shipping
echo "Shipping\n";

$ship  = new ShippingService();
$cart  = array(
	array('item_shipping' => 5.0, 'item_price' => 20.0, 'item_weight' => 1.0, 'cart_qty' => 2),
	array('item_shipping' => 3.0, 'item_price' => 10.0, 'item_weight' => 0.5, 'cart_qty' => 1),
);
$tiers = array('%ROW%' => array(), array('unit' => 50, 'cost' => 4), array('unit' => 100, 'cost' => 8));

check('shipping disabled', $ship->calculate($cart, array('shipping' => 0)), 0.0);
check('sum per item and quantity', $ship->calculate($cart, array('shipping' => 1, 'shipping_method' => 'sum_simple')), 13.0);
check('sum_unique ignores quantity', $ship->calculate($cart, array('shipping' => 1, 'shipping_method' => 'sum_unique')), 8.0);
check('tiered by subtotal (50)', $ship->calculate($cart, array('shipping' => 1, 'shipping_method' => 'tiered', 'shipping_limit' => 'money', 'shipping_unit' => 'subtotal', 'shipping_data' => $tiers)), 4.0);
check('tiered by weight (2.5 <= 50)', $ship->calculate($cart, array('shipping' => 1, 'shipping_method' => 'tiered', 'shipping_limit' => 'money', 'shipping_unit' => 'weight', 'shipping_data' => $tiers)), 4.0);
check('tiered limited to the item sum', $ship->calculate($cart, array('shipping' => 1, 'shipping_method' => 'tiered', 'shipping_limit' => 'limit', 'shipping_unit' => 'subtotal', 'shipping_data' => array(array('unit' => 100, 'cost' => 50)))), 13.0);
check('tiered above the last tier is free', $ship->calculate($cart, array('shipping' => 1, 'shipping_method' => 'tiered', 'shipping_limit' => 'money', 'shipping_unit' => 'subtotal', 'shipping_data' => array(array('unit' => 10, 'cost' => 4)))), 0.0);

// ------------------------------------------------------------- item vars
echo "Item variations\n";

check('toDb', ItemVars::toDb(array(1 => 'black', 2 => 'XL')), '1,2|black,XL');
check('toDb of a non array', ItemVars::toDb('x'), '');
check('toArray', ItemVars::toArray('1,2|black,XL'), array(1 => 'black', 2 => 'XL'));
check('toArray of malformed strings', array(ItemVars::toArray(''), ItemVars::toArray('abc')), array(null, null));
check('normalize list form', ItemVars::normalize(array('2-XL', '1-black-dark')), array(1 => 'black-dark', 2 => 'XL'));
check('normalize keeps id => value sorted', ItemVars::normalize(array(3 => 'a', 1 => 'b')), array(1 => 'b', 3 => 'a'));
check('normalize of non arrays', ItemVars::normalize(false), false);

$varRepo = new class implements ItemVarRepositoryInterface {
	public $calls = 0;

	public function all()
	{
		$this->calls++;

		return array(
			array('item_var_id' => 1, 'item_var_compulsory' => 1),
			array('item_var_id' => 2, 'item_var_compulsory' => 0),
			array('item_var_id' => 3, 'item_var_compulsory' => 1),
		);
	}
};
$types = new ItemVarTypeService($varRepo);
check('tracking variation', array($types->isTracking(1), $types->isTracking(2), $types->isTracking(99)), array(true, false, false));
check('filter tracking as string', $types->filter('1,2,3', 1), '1,3');
check('filter non tracking as array', $types->filter(array(1, 2), 0, true), array(2));
check('filter with no match', array($types->filter('', 1), $types->filter('9', 1, true)), array('', array()));
check('variation types are loaded once', $varRepo->calls, 1);

// -------------------------------------------------------------- inventory
echo "Inventory\n";

class FakeItems implements ItemRepositoryInterface
{
	public $items  = array();
	public $saved  = array();
	public $uclass = array();

	public function getInventory($itemId)
	{
		return isset($this->items[$itemId]) ? $this->items[$itemId] : null;
	}

	public function saveInventory($itemId, $inventory, $varsInventory = null)
	{
		$this->saved[$itemId] = array($inventory, $varsInventory);

		return true;
	}

	public function getUserclass($itemId)
	{
		return isset($this->uclass[$itemId]) ? $this->uclass[$itemId] : 0;
	}
}

$items = new FakeItems();
$items->items = array(
	1 => array('item_inventory' => 10, 'item_vars_inventory' => array()),
	2 => array('item_inventory' => -1, 'item_vars_inventory' => array()),
	3 => array('item_inventory' => 5, 'item_vars_inventory' => array('red' => 4, 'blue' => 0)),
	4 => array('item_inventory' => 8, 'item_vars_inventory' => array('shirt' => array('M' => 6, 'L' => 2))),
	5 => array('item_inventory' => 0, 'item_vars_inventory' => array()),
);
$log = array();
$inv = new InventoryService($items, function ($m) use (&$log) { $log[] = $m; });

check('stock of a plain item', $inv->available(1), 10);
check('unlimited stock', $inv->available(2), InventoryService::UNLIMITED);
check('stock of unknown item', $inv->available(77), 0);
check('stock of a variation', $inv->available(3, array(7 => 'red')), 4);
check('stock of a two level variation', $inv->available(4, array(8 => 'shirt', 9 => 'M')), 6);
check('stock of an untracked variation', $inv->available(3, array(7 => 'green')), 0);
check('stock of variation of unknown item', $inv->available(77, array(7 => 'red')), 0);
check('stock of the list form', $inv->available(3, array('7-red')), 4);

$inv->reduce(array(array('id' => 1, 'quantity' => 3, 'name' => 'A')));
check('reduce plain item', $items->saved[1], array(7, null));
$inv->reduce(array(array('id' => 1, 'quantity' => 50, 'name' => 'A')));
check('stock never goes below zero', $items->saved[1], array(0, null));

$items->saved = array();
$inv->reduce(array(array('id' => 2, 'quantity' => 3, 'name' => 'B')));
check('unlimited item is not reduced', $items->saved, array());
$inv->reduce(array(array('id' => 5, 'quantity' => 1, 'name' => 'E')));
check('sold out item is not touched', $items->saved, array());

$inv->reduce(array(array('id' => 3, 'quantity' => 1, 'name' => 'C', 'item_vars' => '7|red')));
check('reduce variation and total', $items->saved[3], array(4, array('red' => 3, 'blue' => 0)));
$inv->reduce(array(array('id' => 4, 'quantity' => 9, 'name' => 'D', 'item_vars' => '8,9|shirt,M')));
check('reduce two level variation', $items->saved[4], array(0, array('shirt' => array('M' => 0, 'L' => 2))));
$items->saved = array();
$inv->reduce(array(array('id' => 1, 'quantity' => 0, 'name' => 'A'), array('id' => 0, 'quantity' => 1, 'name' => 'A'), array('id' => 1, 'quantity' => 1)));
check('incomplete rows are ignored', $items->saved, array());
check('inventory writes are logged', count($log) > 0, true);

$items->saved = array();
$inv->restore(array(array('id' => 1, 'quantity' => 3, 'name' => 'A')));
check('restore plain item', $items->saved[1], array(13, null));
$items->saved = array();
$inv->restore(array(array('id' => 2, 'quantity' => 3, 'name' => 'B'), array('id' => 77, 'quantity' => 1, 'name' => 'X')));
check('restore leaves unlimited and unknown items alone', $items->saved, array());
$inv->restore(array(array('id' => 3, 'quantity' => 1, 'name' => 'C', 'item_vars' => '7|red')));
check('restore variation and total', $items->saved[3], array(6, array('red' => 5, 'blue' => 0)));
$inv->restore(array(array('id' => 4, 'quantity' => 2, 'name' => 'D', 'item_vars' => '8,9|shirt,M')));
check('restore two level variation', $items->saved[4], array(10, array('shirt' => array('M' => 8, 'L' => 2))));

// ------------------------------------------------------------- userclass
echo "Userclass\n";

$items->uclass = array(1 => 5, 2 => 255, 3 => 5, 4 => 7, 5 => 0);
$uc = new UserclassService($items);
$order = array(array('id' => 1), array('id' => 2), array('id' => 3), array('id' => 4), array('id' => 5));

check('per product userclasses', $uc->classesFor($order, -1), array(5, 7));
check('per product without items', $uc->classesFor(array(), -1), array());
check('per product with invalid items', $uc->classesFor('x', -1), array());
check('global userclass', $uc->classesFor($order, 12), array(12));
check('global "No one" disables the class', $uc->classesFor($order, 255), array());

// -------------------------------------------------------- invoice numbers
echo "Invoice numbers\n";

$nr = new InvoiceNumberService();
check('next number after the last', $nr->next(41, 0), 42);
check('configured start ahead', $nr->next(41, 100), 100);
check('configured start behind', $nr->next(41, 10), 42);
check('first invoice', $nr->next(0, 0), 1);
check('format with prefix', $nr->format(42, 'INV-'), 'INV-000042');
check('format without prefix', $nr->format(7), '000007');
check('valid invoice states', array($nr->isValidState('c'), $nr->isValidState('N'), $nr->isValidState('X'), $nr->isValidState('R')), array(true, true, false, false));

// --------------------------------------------------------------- payment
echo "Payment gateways\n";

class FakeGateway
{
	public $calls = array();
	public $throwOn = null;

	public function __call($method, $args)
	{
		if ($method === $this->throwOn)
		{
			throw new \InvalidArgumentException('bad ' . $method);
		}

		$this->calls[$method] = $args[0];
	}
}

$created = array();
$gw      = new FakeGateway();
$factory = function ($class) use (&$created, $gw)
{
	$created[] = $class;

	if ($class === 'Broken')
	{
		throw new \RuntimeException('Class not found');
	}

	return $gw;
};
$pay = new PaymentService($factory);

$r = $pay->load('bank_transfer', array('gateways' => array('bank_transfer' => array('details' => 'IBAN ES00'))));
check('bank transfer needs no gateway', array($r->isOk(), $r->value()['gateway'], $r->value()['mode'], $r->value()['bank_details']), array(true, null, 'return', 'IBAN ES00'));
check('bank transfer without details', $pay->load('bank_transfer', array())->value()['bank_details'], '');
check('no factory call for bank transfer', $created, array());

$r = $pay->load('mollie', array('mollie_testmode' => 1, 'mollie_api_key_test' => 'test_x', 'mollie_api_key_live' => 'live_x'));
check('mollie test mode from flat keys', array($created, $gw->calls), array(array('Vstore\\Payment\\MollieGateway'), array('setApiKey' => 'test_x', 'setTestMode' => true)));
$gw->calls = array();
$pay->load('mollie', array('mollie' => array('api_key_live' => 'live_y')));
check('mollie live mode from nested keys', $gw->calls, array('setApiKey' => 'live_y'));
$gw->calls = array();
$r = $pay->load('mollie', array());
check('mollie without keys is not sent to the API', array($r->code(), $gw->calls), array('GW_CREDENTIALS', array()));

$prefs = array('gateways' => array('stripe' => array('name' => 'Stripe', 'prefs' => array('apiKey' => 'sk', 'testMode' => true))));
$gw->calls = array();
$r = $pay->load('stripe', $prefs);
check('generic gateway gets its prefs', array($r->isOk(), $gw->calls), array(true, array('setApiKey' => 'sk', 'setTestMode' => true)));

$r = $pay->load('unknown', $prefs);
check('gateway without configuration', array($r->code(), $r->isOk(), $r->value()['gateway']), array('GW_CONFIG', false, null));

$before = count($created);
$r = $pay->load('mollie', array('mollie_testmode' => 1, 'mollie_api_key_live' => 'live_only'));
check('mollie without the key of the active mode', array($r->code(), $r->params(), $r->value()['detail'], $r->value()['gateway'], count($created)), array('GW_CREDENTIALS', array('mollie'), ' (mollie_api_key_test)', null, $before));
$r = $pay->load('mollie', array('mollie_api_key_live' => ''));
check('mollie live without key', $r->code(), 'GW_CREDENTIALS');

$empty = array('gateways' => array('paypal' => array('name' => 'PayPal_Express', 'prefs' => array('username' => '', 'password' => 'x', 'signature' => '  ', 'testMode' => false))));
$r = $pay->load('paypal', $empty);
check('paypal without credentials lists what is missing', array($r->code(), $r->value()['detail'], $r->value()['gateway']), array('GW_CREDENTIALS', ' (username, signature)', null));
$r = $pay->load('stripe', array('gateways' => array('stripe' => array('name' => 'Stripe', 'prefs' => array('apiKey' => '')))));
check('stripe without api key', $r->code(), 'GW_CREDENTIALS');
$r = $pay->load('stark', array('gateways' => array('stark' => array('name' => 'Stark', 'prefs' => array('apiKey' => '')))));
check('gateways without a known requirement are still created', $r->isOk(), true);

$r = $pay->load('stripe', array('gateways' => array('stripe' => array('name' => 'Broken'))));
check('gateway class that cannot be created', array($r->code(), $r->params(), $r->value()['detail']), array('GW_LOAD', array('stripe'), 'Class not found'));

$gw->throwOn = 'setApiKey';
$r = $pay->load('stripe', $prefs);
check('invalid gateway param is a warning', array($r->code(), $r->isOk(), $r->severity(), is_object($r->value()['gateway'])), array('GW_PARAM', true, 'warning', true));
$gw->throwOn = null;

// ---------------------------------------------------------------- invoice
echo "Invoice\n";

$invoice = new InvoiceService(new InvoiceNumberService());
check('title uses the language title', $invoice->title(array('invoice_title' => array('Spanish' => 'Factura'), 'invoice_nr_prefix' => 'F-'), 'Spanish', 'Invoice', 12), 'Factura F-000012');
check('title falls back to the default', $invoice->title(array(), 'Spanish', 'Invoice', 12), 'Invoice 000012');

$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR;
file_put_contents($dir . 'vstore-test-invoice.pdf', '%PDF');
check('existing pdf', $invoice->existingPdf($dir, 'vstore-test-invoice'), $dir . 'vstore-test-invoice.pdf');
unlink($dir . 'vstore-test-invoice.pdf');
check('missing pdf', $invoice->existingPdf($dir, 'vstore-test-invoice'), '');

check('owner may see a completed order', $invoice->checkAccess(5, 'C', 5, false, 'Completed')->isOk(), true);
$r = $invoice->checkAccess(5, 'C', 6, false, 'Completed');
check('other user is denied', array($r->code(), $r->isOk()), array('ACCESS_DENIED', false));
check('admin may see other invoices', $invoice->checkAccess(5, 'C', 6, true, 'Completed')->isOk(), true);
$r = $invoice->checkAccess(5, 'X', 5, false, 'Cancelled');
check('cancelled order has no invoice', array($r->code(), $r->params()), array('INVOICE_STATE', array('Cancelled')));

// ------------------------------------------------------ result translator
echo "Result translator\n";

define('LAN_VSTORE_MSG_T_SPRINTF', 'Coupon %s expires on %s');
define('LAN_VSTORE_MSG_T_LANVARS', 'Order in status "[x]" and "[y]"');
define('LAN_VSTORE_MSG_T_PLAIN', '100% sure');

check('sprintf style', ResultTranslator::text(Result::fail('T_SPRINTF', array('X1', '2030-01-01'))), 'Coupon X1 expires on 2030-01-01');
check('lanVars style', ResultTranslator::text(Result::fail('T_LANVARS', array('New', 'Open'))), 'Order in status "New" and "Open"');
check('text with % and no params', ResultTranslator::text(Result::fail('T_PLAIN')), '100% sure');
check('unknown code falls back to the code', ResultTranslator::text(Result::fail('NOPE')), 'NOPE');

// --------------------------------------------------------------- cart
echo "Cart\n";

class FakeCart implements CartRepositoryInterface
{
	public $items = array(
		1 => array('item_active' => 1, 'item_tax_class' => 'reduced', 'item_name' => 'Shirt'),
		2 => array('item_active' => 0, 'item_tax_class' => 'standard', 'item_name' => 'Old item'),
	);
	public $lines = array();
	public $nextId = 1;

	public function getItem($itemId)
	{
		return isset($this->items[$itemId]) ? $this->items[$itemId] : null;
	}

	public function findLine($session, $itemId, $vars = null)
	{
		foreach ($this->lines as $id => $l)
		{
			if ($l['cart_session'] === $session && $l['cart_item'] == $itemId && ($vars === null || $l['cart_item_vars'] === $vars))
			{
				return array('cart_id' => $id, 'cart_qty' => $l['cart_qty']);
			}
		}

		return null;
	}

	public function insertLine(array $data)
	{
		$this->lines[$this->nextId++] = $data;

		return true;
	}

	public function setQty($cartId, $session, $qty)
	{
		if (!isset($this->lines[$cartId]) || $this->lines[$cartId]['cart_session'] !== $session)
		{
			return false;
		}

		$this->lines[$cartId]['cart_qty'] = $qty;

		return true;
	}

	public function deleteLine($cartId, $session)
	{
		if (isset($this->lines[$cartId]) && $this->lines[$cartId]['cart_session'] === $session)
		{
			unset($this->lines[$cartId]);

			return true;
		}

		return false;
	}

	public function deleteItemLines($session, $itemId)
	{
		foreach ($this->lines as $id => $l)
		{
			if ($l['cart_session'] === $session && $l['cart_item'] == $itemId)
			{
				unset($this->lines[$id]);
			}
		}

		return true;
	}
}

$cartRepo = new FakeCart();
$stock    = new FakeItems();
$stock->items = array(
	1 => array('item_inventory' => 10, 'item_vars_inventory' => array()),
	2 => array('item_inventory' => 5, 'item_vars_inventory' => array()),
	3 => array('item_inventory' => -1, 'item_vars_inventory' => array()),
);
$cartRepo->items[3] = array('item_active' => 1, 'item_tax_class' => '', 'item_name' => 'Unlimited');
$cart = new CartService($cartRepo, new InventoryService($stock));

check('add creates a line', $cart->add('S1', 7, 1, false, 3)->isOk(), true);
check('line data', array($cartRepo->lines[1]['cart_qty'], $cartRepo->lines[1]['cart_item_tax_class'], $cartRepo->lines[1]['cart_e107_user']), array(3, 'reduced', 7));
$cart->add('S1', 7, 1, false, 4);
check('add increases the line', $cartRepo->lines[1]['cart_qty'], 7);
$cart->add('S1', 7, 1, false, 50);
check('add never passes the stock', $cartRepo->lines[1]['cart_qty'], 10);
$r = $cart->add('S1', 7, 1);
check('line at the stock limit', array($r->code(), $r->isOk()), array('QTY_EXCEEDS', false));
$cart->add('S1', 7, 3, false, 99);
check('new line above the stock is limited (unlimited stock keeps the quantity)', array_values(array_column($cartRepo->lines, 'cart_qty')), array(10, 99));
check('empty tax class falls back to standard', $cartRepo->lines[2]['cart_item_tax_class'], 'standard');
$cart->add('S2', 0, 1, false, -4);
check('quantity below 1 counts as 1', $cartRepo->findLine('S2', 1)['cart_qty'], 1);
$r = $cart->add('S1', 7, 2, false, 1);
check('inactive item', array($r->code(), $r->isOk()), array('ITEM_UNAVAILABLE', false));
$cart->add('S1', 7, 99, false, 1);
check('unknown item', $cartRepo->findLine('S1', 99), null);

$cartRepo->lines = array();
$cartRepo->nextId = 1;
$cart->add('S1', 0, 1, array(5 => 'red'), 2);
check('variation is stored', $cartRepo->lines[1]['cart_item_vars'], '5|red');
$cart->add('S1', 0, 1, array(5 => 'blue'), 1);
check('another variation is another line', count($cartRepo->lines), 2);

$cartRepo->lines = array();
$cartRepo->nextId = 1;
$cart->add('S1', 0, 1, false, 2);
$cart->add('S1', 0, 3, false, 2);
$res = $cart->updateQuantities('S1', array(1 => array('id' => 1, 'qty' => 6, 'vars' => ''), 2 => array('id' => 3, 'qty' => 0, 'vars' => '')));
check('quantity update', array($cartRepo->lines[1]['cart_qty'], $cartRepo->lines[2]['cart_qty'], $res), array(6, 2, array()));
$res = $cart->updateQuantities('S1', array(1 => array('id' => 1, 'qty' => 500, 'vars' => '')));
check('quantity update is limited to the stock', array($cartRepo->lines[1]['cart_qty'], $res[0]->code(), $res[0]->params(), $res[0]->isOk()), array(10, 'QTY_ADJUSTED', array('Shirt'), true));
$res = $cart->updateQuantities('OTHER', array(1 => array('id' => 1, 'qty' => 2, 'vars' => '')));
check('lines of another cart are not touched', $cartRepo->lines[1]['cart_qty'], 10);
$cartRepo->items[1]['item_active'] = 0;
$res = $cart->updateQuantities('S1', array(1 => array('id' => 1, 'qty' => 2, 'vars' => '')));
check('inactive item is removed with a warning', array(isset($cartRepo->lines[1]), $res[0]->code(), $res[0]->params()), array(false, 'ITEM_NOT_FOUND', array('Shirt')));
$cart->remove('OTHER', array(2 => 1));
check('remove ignores other carts', isset($cartRepo->lines[2]), true);
$cart->remove('S1', array(2 => 1));
check('remove', isset($cartRepo->lines[2]), false);

// -------------------------------------------------------------- checkout
echo "Checkout\n";

$vies  = new FakeVies();
$taxS  = new TaxService(new FakeRates(), new VatService($vies));
$couS  = new CouponService(new FakeUsage(), null, function () { return 1760000000; });
$check = new CheckoutService($taxS, $couS, new ShippingService(), new VatService($vies));
$pref  = array(
	'tax_calculate' => 1, 'tax_business_country' => 'ES', 'tax_check_vat' => 0, 'shipping' => 1, 'shipping_method' => 'sum_simple',
	'tax_classes' => array(array('name' => 'standard', 'value' => 0.21), array('name' => 'reduced', 'value' => 0.10)),
);
$ctx   = array('country' => 'ES', 'vat_id' => '', 'is_business' => false, 'is_local' => true, 'user_id' => 0);
$rows  = array(
	array('item_id' => 1, 'item_cat' => 1, 'item_price' => 100.0, 'cart_qty' => 2, 'item_name' => 'A', 'cart_item_tax_class' => 'standard', 'item_shipping' => 5.0, 'item_weight' => 1),
	array('item_id' => 2, 'item_cat' => 1, 'item_price' => 50.0, 'cart_qty' => 1, 'item_name' => 'B', 'cart_item_tax_class' => 'reduced', 'item_shipping' => 0.0, 'item_weight' => 1),
);

$calc = $check->calculate($rows, null, $ctx, $pref);
$t    = $calc['totals'];
check('subtotal', $t['cart_subTotal'], 250.0);
check('shipping', $t['cart_shippingTotal'], 10.0);
check('tax per rate (21% of 200, 10% of 50)', array($t['cart_taxTotal']['0.21'] > 41.9, $t['cart_taxTotal']['0.1'] > 4.9), array(true, true));
check('grand total = items + shipping + tax', round($t['cart_grandTotal'], 2), round(250 + 10 + array_sum($t['cart_taxTotal']), 2));
check('shipping tax is shared by net value', round($t['cart_taxTotal']['0.21'], 2), round(42.0 + 0.21 * 10 * 200 / 250, 2));
check('rows are enriched', array($calc['items'][0]['tax_rate'], $calc['items'][0]['item_total'], $calc['items'][1]['tax_amount']), array(0.21, 200.0, 5.0));
check('no coupon', $t['cart_coupon'], array('code' => '', 'amount' => 0.0, 'amount_net' => 0.0));

$ptCtx = array('country' => 'PT', 'vat_id' => '', 'is_business' => false, 'is_local' => false, 'user_id' => 0);
$calc = $check->calculate($rows, null, array('country' => 'FR', 'is_local' => false) + $ctx, $pref);
check('prices are net: the destination tax is added on top', round($calc['totals']['cart_grandTotal'], 2), 304.46);
$calc = $check->calculate($rows, null, $ctx, $pref);
$t = $calc['totals'];
check('net values equal the prices, tax is not taken out of them', array($calc['items'][0]['item_price_net'], $t['cart_subNet'], $t['cart_shippingNet'], $t['cart_grandNet']), array(100.0, 250.0, 10.0, 260.0));

$couponRow = array(
	'coupon_code' => 'save10', 'coupon_active' => 1, 'coupon_type' => '%', 'coupon_amount' => 10, 'coupon_start' => 0, 'coupon_end' => 0,
	'coupon_limit_coupon' => -1, 'coupon_limit_user' => -1, 'coupon_items' => '', 'coupon_items_ex' => '', 'coupon_cats' => '', 'coupon_cats_ex' => '',
);
$calc = $check->calculate($rows, $couponRow, $ctx, $pref);
check('coupon discount and code', array($calc['totals']['cart_coupon']['code'], $calc['totals']['cart_coupon']['amount']), array('SAVE10', -25.0));
check('coupon lowers the tax', round($calc['totals']['cart_taxTotal']['0.21'], 2), round(0.21 * (200 - 20) + 0.21 * 10 * 200 / 250, 2));
$calc = $check->calculate($rows, array('coupon_active' => 0) + $couponRow, $ctx, $pref);
check('coupon rejection is reported once per item', array(count($calc['messages']), $calc['messages'][0]->code(), $calc['totals']['cart_coupon']['amount']), array(2, 'COUPON_NA', 0.0));

$calc = $check->calculate($rows, null, array('country' => 'US') + $ctx, $pref);
check('customer outside the EU pays no tax', array_sum($calc['totals']['cart_taxTotal']), 0.0);
$calc = $check->calculate($rows, null, $ctx, array('tax_calculate' => 0) + $pref);
check('tax disabled', array_sum($calc['totals']['cart_taxTotal']), 0.0);
$calc = $check->calculate($rows, null, array('country' => 'PL') + $ctx, $pref);
check('unknown rate reports TAX_CLASS', $calc['messages'][0]->code(), 'TAX_CLASS');
$free = array(array('item_id' => 1, 'item_cat' => 1, 'item_price' => 0.0, 'cart_qty' => 1, 'item_name' => 'Free', 'cart_item_tax_class' => 'standard', 'item_shipping' => 4.0, 'item_weight' => 0));
$calc = $check->calculate($free, null, $ctx, $pref);
check('free items with shipping do not divide by zero', $calc['totals']['cart_shippingTotal'], 4.0);

$fields = array('billing' => array('title', 'firstname', 'lastname', 'company', 'vat_id', 'address', 'city', 'zip', 'country', 'email', 'additional_fields'), 'shipping' => array('firstname', 'lastname', 'address', 'city', 'zip', 'country'));
$addF   = array(0 => array('active' => 1, 'type' => 'text', 'required' => 1, 'caption' => 'Reference'), 1 => array('active' => 1, 'type' => 'check', 'required' => 0, 'caption' => 'Newsletter'), 2 => array('active' => 0, 'type' => 'text', 'required' => 1, 'caption' => 'Off'));
$good   = array('firstname' => ' Ana ', 'lastname' => '<b>Ruiz</b>', 'address' => 'Calle 1', 'city' => 'Madrid', 'zip' => '28001', 'country' => 'ES', 'email' => 'ana@example.com', 'company' => '', 'vat_id' => '', 'add_field0' => 'PO-1', 'add_field1' => 'on');

$r = $check->validateCustomer($good, 'billing', $fields, $addF, false);
check('valid data is filtered', array($r->isOk(), $r->value()['firstname'], $r->value()['lastname'], $r->value()['add_field0'], $r->value()['add_field1']), array(true, 'Ana', 'Ruiz', 'PO-1', '1'));
check('inactive additional field is ignored', isset($r->value()['add_field2']), false);
check('invalid data', $check->validateCustomer(array(), 'billing', $fields, $addF, false)->code(), 'CUST_INVALID');
check('invalid type', $check->validateCustomer($good, 'other', $fields, $addF, false)->code(), 'INVALID_TYPE');
$r = $check->validateCustomer(array('city' => '') + $good, 'billing', $fields, $addF, false);
check('required field', array($r->code(), $r->value()['field'], $r->value()['caption']), array('FIELD_REQUIRED', 'city', null));
$r = $check->validateCustomer(array('email' => 'nope') + $good, 'billing', $fields, $addF, false);
check('email format', $r->code(), 'EMAIL_INVALID');
$r = $check->validateCustomer(array('add_field0' => '') + $good, 'billing', $fields, $addF, false);
check('required additional field has a caption', array($r->code(), $r->value()['caption']), array('FIELD_REQUIRED', 'Reference'));
$r = $check->validateCustomer(array('vat_id' => 'fr1') + $good, 'billing', $fields, $addF, true);
check('VAT ID of another country', $r->code(), 'VAT_INVALID');
$r = $check->validateCustomer(array('vat_id' => '123456789', 'country' => 'PT') + $good, 'billing', $fields, $addF, true, 'PT');
check('consumer NIF of the shop country passes with VIES checking on', $r->isOk(), true);
$r = $check->validateCustomer(array('vat_id' => '123456780', 'country' => 'PT') + $good, 'billing', $fields, $addF, true, 'PT');
check('wrong NIF of the shop country is refused', $r->code(), 'VAT_INVALID');
$r = $check->validateCustomer(array('vat_id' => 'es1234') + $good, 'billing', $fields, $addF, false);
check('VAT ID is upper cased', $r->value()['vat_id'], 'ES1234');
$missing = $good; unset($missing['lastname']);
check('missing keys are required, not warnings', $check->validateCustomer($missing, 'shipping', $fields, $addF, false)->value()['field'], 'lastname');

// ----------------------------------------------------------------- order
echo "Order\n";

$orders = new OrderService();
check('order reference', $orders->reference(42, 'Ana', 'Ruiz'), 'ANRU000042');
check('order reference of short names', $orders->reference(7, 'A', ''), 'A000007');

$split = $orders->splitAdditionalFields(
	array(0 => array('caption' => array('Spanish' => '<b>Referencia</b>'), 'type' => 'text'), 1 => array('caption' => array('Spanish' => 'Boletin'), 'type' => 'check')),
	array('firstname' => 'Ana', 'add_field0' => 'PO-1', 'add_field1' => '1'),
	'Spanish'
);
check('additional fields move out of the customer', array_keys($split['customer']), array('firstname'));
check('additional fields are described', $split['additional'], array('add_field0' => array('caption' => 'Referencia', 'value' => 'PO-1'), 'add_field1' => array('caption' => 'Boletin', 'value' => 'X')));

class FakeCustomers implements CustomerRepositoryInterface
{
	public $rows = array();
	public $refs = array();

	public function exists($userId)
	{
		return isset($this->rows[$userId]);
	}

	public function update($userId, array $data)
	{
		$this->rows[$userId] = $data;

		return true;
	}

	public function insert(array $data)
	{
		$this->rows[$data['cust_e107_user']] = $data;

		return 12;
	}

	public function setRefCode($userId, $refCode)
	{
		$this->refs[$userId] = $refCode;

		return true;
	}
}

$custRepo = new FakeCustomers();
$ok = $orders->saveCustomer($custRepo, 5, array('firstname' => 'Ana', 'lastname' => 'Ruiz'), array('city' => 'X'), true, 'paypal', 1000);
check('new customer', array($ok, $custRepo->rows[5]['cust_firstname'], $custRepo->rows[5]['cust_use_shipping'], $custRepo->rows[5]['cust_gateway'], $custRepo->refs[5]), array(true, 'Ana', 1, 'paypal', 'ANRU000012'));
$custRepo->refs = array();
$orders->saveCustomer($custRepo, 5, array('firstname' => 'Ana', 'lastname' => 'Ruiz'), array(), false, 'mollie', 2000);
check('existing customer is updated without a new reference', array($custRepo->rows[5]['cust_gateway'], $custRepo->rows[5]['cust_datestamp'], $custRepo->refs), array('mollie', 2000, array()));

// --------------------------------------------------------------- catalog
echo "Catalog\n";

check('status slug', Catalog::statusSlug('H'), 'on hold');
check('every status has an email slug', count(Catalog::STATUS), 6);
check('mollie detection', array(Catalog::isMollie('mollie_ideal'), Catalog::isMollie('paypal')), array(true, false));
check('retired gateways', array(Catalog::isRetiredGateway('stripe'), Catalog::isRetiredGateway('Braintree'), Catalog::isRetiredGateway('mollie'), Catalog::isRetiredGateway('paypal')), array(true, true, false, false));
check('euro-only Mollie methods', array(Catalog::supportsCurrency('mollie_mbway', 'EUR'), Catalog::supportsCurrency('mollie_mbway', 'USD'), Catalog::supportsCurrency('mollie_creditcard', 'USD'), Catalog::supportsCurrency('paypal', 'USD'), Catalog::supportsCurrency('mollie_multibanco', 'usd')), array(true, false, true, true, false));
$missingIcons = array();
foreach (Catalog::MOLLIE_PAYMENT_METHODS as $key => $method)
{
	if (!is_file(__DIR__ . '/../' . $method['icon']))
	{
		$missingIcons[] = $key;
	}
}
check('every Mollie method has its icon file', $missingIcons, array());
check('Portuguese Mollie methods are listed', array(isset(Catalog::MOLLIE_PAYMENT_METHODS['mollie_mbway']), isset(Catalog::MOLLIE_PAYMENT_METHODS['mollie_multibanco'])), array(true, true));
check('result defaults', array(Result::ok()->isOk(), Result::ok()->hasMessage()), array(true, false));

echo "Phone numbers and Mollie billing contact\n";
check('PT national number', PhoneNumber::toE164('912 345 678', 'PT'), '+351912345678');
check('international with plus', PhoneNumber::toE164('+351 912-345-678', 'ES'), '+351912345678');
check('international with 00', PhoneNumber::toE164('00351912345678', ''), '+351912345678');
check('trunk zero is removed', PhoneNumber::toE164('07911 123456', 'GB'), '+447911123456');
check('Spanish number', PhoneNumber::toE164('612345678', 'es'), '+34612345678');
check('no country and no prefix', PhoneNumber::toE164('912345678', ''), null);
check('empty / too short / letters', array(PhoneNumber::toE164('', 'PT'), PhoneNumber::toE164('123', 'PT'), PhoneNumber::toE164('abc', 'PT')), array(null, null, null));
check('Portuguese mobile', array(PhoneNumber::isPortugueseMobile('+351912345678'), PhoneNumber::isPortugueseMobile('+351212345678'), PhoneNumber::isPortugueseMobile('+34612345678'), PhoneNumber::isPortugueseMobile(null)), array(true, false, false, false));

$mollieRequest = function (array $params)
{
	$request = new \Vstore\Payment\MolliePurchaseRequest(new \Omnipay\Common\Http\Client(), \Symfony\Component\HttpFoundation\Request::create('/'));
	$request->initialize(array_merge(array('apiKey' => 'test_x', 'amount' => '10.00', 'currency' => 'EUR', 'description' => 'd', 'returnUrl' => 'https://example.com/r'), $params));

	return $request->getData();
};
$data = $mollieRequest(array('billingEmail' => 'a@b.pt', 'billingPhone' => '+351912345678'));
check('Mollie billingAddress with email and phone', array(isset($data['billingEmail']), $data['billingAddress']), array(false, array('email' => 'a@b.pt', 'phone' => '+351912345678')));
$data = $mollieRequest(array('billingEmail' => 'a@b.pt'));
check('Mollie billingAddress without phone', $data['billingAddress'], array('email' => 'a@b.pt'));
$data = $mollieRequest(array('billingPhone' => '+351912345678'));
check('Mollie billingAddress omitted without email', isset($data['billingAddress']), false);
$gw = new \Vstore\Payment\MollieGateway(new \Omnipay\Common\Http\Client(), \Symfony\Component\HttpFoundation\Request::create('/'));
check('Mollie gateway builds the extended request', get_class($gw->purchase()), 'Vstore\\Payment\\MolliePurchaseRequest');


echo "Mollie payment status\n";
check('outcomes', array(MolliePayment::outcome('paid'), MolliePayment::outcome('pending'), MolliePayment::outcome('open'), MolliePayment::outcome('canceled'), MolliePayment::outcome('expired'), MolliePayment::outcome(null)), array('paid', 'pending', 'open', 'failed', 'failed', 'unknown'));
check('paid payment confirms a waiting order', MolliePayment::orderChange('paid', 'N', 'incomplete'), array('order_status' => 'P', 'order_pay_status' => 'complete', 'outcome' => 'paid'));
check('expired payment cancels a waiting order', MolliePayment::orderChange('expired', 'N', 'incomplete')['order_status'], 'X');
check('pending payment changes nothing', MolliePayment::orderChange('pending', 'N', 'incomplete'), null);
check('paid orders are not touched again', array(MolliePayment::orderChange('paid', 'P', 'complete'), MolliePayment::orderChange('failed', 'P', 'complete'), MolliePayment::orderChange('paid', 'X', 'incomplete')), array(null, null, null));
check('webhook url must be public', array(MolliePayment::isPublicUrl('https://shop.example.pt/vstore/checkout/webhook/'), MolliePayment::isPublicUrl('http://localhost/vstore/x'), MolliePayment::isPublicUrl('http://vstore.test/x'), MolliePayment::isPublicUrl('http://192.168.1.5/x'), MolliePayment::isPublicUrl('https://8.8.8.8/x')), array(true, false, false, false, true));

echo "Secret mask\n";
$logText = 'Array[!br!]([!br!]    [mollie_testmode] =&gt; 0[!br!]    [mollie_api_key_live] =&gt; live_abc123[!br!]    [password] =&gt; p@ss[!br!]    [username] =&gt; user@x.pt[!br!])[!br!]';
$masked = \Vstore\Support\SecretMask::maskLogText($logText);
check('secrets are hidden', array(strpos($masked, 'live_abc123') === false, strpos($masked, 'p@ss') === false), array(true, true));
check('other values stay', array(strpos($masked, '[mollie_testmode] =&gt; 0') !== false, strpos($masked, 'user@x.pt') !== false), array(true, true));
check('masking twice changes nothing', \Vstore\Support\SecretMask::maskLogText($masked), $masked);

echo "Translatable texts\n";
use Vstore\Support\Translatable;
check('text of the language', Translatable::pick(array('Spanish' => 'Hola', 'Portuguese' => 'Ola'), 'Spanish'), 'Hola');
check('falls back to English', Translatable::pick(array('Spanish' => '', 'English' => 'Hello', 'Portuguese' => 'Ola'), 'Spanish'), 'Hello');
check('falls back to the first text', Translatable::pick(array('Portuguese' => 'Ola'), 'Spanish'), 'Ola');
check('plain string and default', array(Translatable::pick('Hi', 'Spanish'), Translatable::pick(array(), 'Spanish', 'x'), Translatable::pick(null, 'Spanish')), array('Hi', 'x', ''));
check('saving one language keeps the others', Translatable::merge(array('Portuguese' => 'Ola'), array('Spanish' => 'Hola')), array('Portuguese' => 'Ola', 'Spanish' => 'Hola'));
check('merge overwrites the language being saved', Translatable::merge(array('Spanish' => 'Viejo'), array('Spanish' => 'Nuevo')), array('Spanish' => 'Nuevo'));
check('merge with nothing stored', Translatable::merge('', array('Spanish' => 'Hola')), array('Spanish' => 'Hola'));

echo "PDF invoice\n";
use Vstore\Pdf\InvoicePdf;
check('entities decoded for the pdf', InvoicePdf::decodeEntities('It&#039;s &quot;A&quot; &#036;5'), 'It\'s "A" $5');
$pdfFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'vstore-test-' . uniqid() . '.pdf';
$pdf = new InvoicePdf(array('site_name' => 'Shop', 'site_tag' => 'Tag', 'footer_html' => '<b>Footer</b>', 'created_text' => 'Created: today'));
$pdf->build('<table cellpadding="3" style="width:100%"><tr><td>Impresora láser</td><td style="text-align:right">189,90 &euro;</td></tr></table>', array('title' => 'Invoice IN000001'));
$pdf->Output($pdfFile, 'F');
$pdfData = (string) @file_get_contents($pdfFile);
check('pdf file created', array(substr($pdfData, 0, 5), strlen($pdfData) > 1000, strpos($pdfData, '%%EOF') !== false), array('%PDF-', true, true));
@unlink($pdfFile);

echo "\n$total checks, $failures failed\n";
exit($failures ? 1 : 0);
