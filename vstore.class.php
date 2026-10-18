<?php
/**
 * e107 website system
 *
 * Copyright (C) 2008-2013 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * Vstore shopping cart plugin
 *
 * @author CaMerOn <cameron@e107.org>
 * @author Achim Ennenbach <achim@simsync.de>
 * @copyright 2019 e107inc
 */


e107::js('vstore', 'js/vstore.js');

require_once('vendor/autoload.php');
require_once(__DIR__ . '/src/autoload.php');

use Omnipay\Omnipay;
use Ibericode\Vat\Exception;
use Vstore\Pdf\InvoicePdf;
use Vstore\Repository\CartRepository;
use Vstore\Repository\CouponRepository;
use Vstore\Repository\CustomerRepository;
use Vstore\Repository\ItemRepository;
use Vstore\Repository\ItemVarRepository;
use Vstore\Repository\OrderCouponUsageRepository;
use Vstore\Repository\OrderRepository;
use Vstore\Service\CartService;
use Vstore\Service\CheckoutService;
use Vstore\Service\CouponService;
use Vstore\Service\InventoryService;
use Vstore\Service\InvoiceNumberService;
use Vstore\Service\InvoiceService;
use Vstore\Service\ItemVarTypeService;
use Vstore\Service\OrderService;
use Vstore\Service\PaymentService;
use Vstore\Service\ShippingService;
use Vstore\Service\TaxService;
use Vstore\Service\UserclassService;
use Vstore\Service\VatService;
use Vstore\Support\Catalog;
use Vstore\Support\ItemVars;
use Vstore\Support\MolliePayment;
use Vstore\Support\PhoneNumber;
use Vstore\Support\Result;
use Vstore\Support\ResultTranslator;
use Vstore\Support\Translatable;

e107::lan('vstore', 'global', true);
e107::lan('vstore', false, true);
e107::lan('vstore', 'front_shop', true);
e107::lan('vstore', 'front_msg', true);
e107::lan('vstore', 'front_mail', true);

class vstore
{

	protected $cartId = null;
	protected $sc;
	protected $perPage = 9;
	protected $from = 0;
	protected $categories = array(); // all categories;
	protected $categorySEF = array();
	protected $item = array(); // current item.
	protected $captionBase = "Vstore";
	protected $captionCategories = "Product Brands";
	protected $captionOutOfStock = "Out of Stock";
	protected $get = array();
	protected $post = array();
	protected $categoriesTotal = 0;
	protected $action = array();
	protected $pref = array();
//	protected $parentData = array();
	protected $currency = 'USD';

	/** @var vstore_order */
	public $order;
	private $html_invoice;

	/**
	 * Array with the available currencies
	 * 'key' is the 3-char ISO 4217 code of the currency
	 *    'title' is the name of the currency
	 *    'symbol' is the currency sign (usually only 1 letter)
	 *    'glyph' If no symbol is available, use the 'glyph' key to define a fontawesome or glyphicon symbol
	 *    Use only 'symbol' of 'glyph', not both!
	 *
	 * @var array
	 */
	protected static $currencies = Catalog::CURRENCIES;

	/**
	 * Array with the available gateways and their corresponding icons
	 *
	 * @var array The available gateways
	 */
	protected static $gateways = Catalog::GATEWAYS;

	/**
	 * Array with the payment methods of Mollie
	 *
	 * 'key' The payment method prefixed with mollie_
	 *     'title' The name of the payment method
	 *     'icon'  The icon of the payment methods (usually an svg in the images folder)
	 *
	 * All values are required when adding a new payment method!
	 *
	 * @var array The available payment methods of the Mollie gateway
	 */
	protected static $mollie_payment_methods = Catalog::MOLLIE_PAYMENT_METHODS;

	protected static $status = Catalog::STATUS;

	/**
	 * @var array Array with email types
	 */
	protected static $emailTypes = Catalog::EMAIL_TYPES;

	/**
	 * @var array Array with shipping fieldnames
	 */
	protected static $shippingFields = Catalog::SHIPPING_FIELDS;

	/**
	 * @var array Array with customer fieldnames
	 */
	protected static $customerFields = Catalog::CUSTOMER_FIELDS;

	/**
	 * @var array Array with official tay classes
	 */
	protected static $official_tax_classes = Catalog::TAX_CLASSES;

	/**
	 * @var array Item variation types are cached by Vstore\Service\ItemVarTypeService
	 */
	private static $itemVarTypeService = null;

	/** @var VatService|null Shared so the VIES answers are remembered within the request */
	private static $vatService = null;


	public function __construct()
	{
		$sql = e107::getDb();

		$this->cartId = $this->getCartId();

		/** @var vstore_shortcodes sc */
		$this->sc = e107::getScParser()->getScObject('vstore_shortcodes', 'vstore', false);

		$this->get = varset($_GET);
		$this->post = varset($_POST);

		$this->pref = e107::pref('vstore');

		$this->initPrefs();

	}

	public static function getGatewayPackageList()
	{

		$composerData = include(e_PLUGIN . 'vstore/vendor/composer/autoload_psr4.php');

		unset(
			$composerData['Omnipay\Common\\'],
			$composerData['Omnipay\PayPal\\'],
			$composerData['Omnipay\Mollie\\'],
		);

		$list = [];
		foreach($composerData as $package => $path)
		{
			if(strpos($package, 'Omnipay\\') === 0)
			{
				$tmp = explode('\\', $package);
				if(isset($tmp[1]))
				{
					$key = strtolower($tmp[1]);
					$list[$key] = ($tmp[1]);
				}
			}
		}


		return $list;

	}


	public function init()
	{

		// print_a($this->get);
		if(!empty($this->get['catsef']))
		{
			$sef = $this->get['catsef'];
			$this->get['cat'] = vartrue($this->categorySEF[$sef], 0);
		}

		// Check for ajax requests and process them first
		$this->process_ajax();

		// In case this is not an ajax request continue with processing
		$this->process();
	}

	/**
	 * Get status string from key or (if key is empty) complete status array
	 *
	 * @param string $key
	 * @return array/string
	 */
	public static function getStatus($key = null)
	{
		e107::lan('vstore', 'global', true);

		$list = array();
		foreach(self::$status as $code => $label)
		{
			$list[$code] = defset('LAN_VSTORE_STATUS_' . $code, $label);
		}

		if(!empty($key))
		{
			return $list[$key];
		}

		return $list;
	}

	/**
	 * Untranslated lower-case status name, used as key for logic such as email template selection
	 *
	 * @param string $key Status code (N, P, H, C, X, R)
	 * @return string
	 */
	public static function getStatusSlug($key)
	{
		return Catalog::statusSlug($key);
	}

	/**
	 * Get email type string from key or (if key is empty) complete email type array
	 *
	 * @param string $key
	 * @return array/string
	 */
	public static function getEmailTypes($type = null)
	{
		e107::lan('vstore', 'global', true);

		$list = array();
		foreach(self::$emailTypes as $key => $label)
		{
			$list[$key] = defset('LAN_VSTORE_EMAILTYPE_' . strtoupper($key), $label);
		}

		if(!empty($type))
		{
			return $list[$type];
		}

		return $list;
	}

	/**
	 * Return the official tax classes array
	 *
	 * @return array
	 */
	public static function getTaxClasses()
	{

		return self::$official_tax_classes;
	}

	/**
	 * Return the shippingFields array
	 *
	 * @return array
	 */
	public static function getShippingFields()
	{

		return self::$shippingFields;
	}

	/**
	 * Return the customerFields array
	 *
	 * @return array
	 */
	public static function getCustomerFields()
	{

		return self::$customerFields;
	}

	public static function getCurrencies()
	{
		e107::lan('vstore', 'global', true);

		$list = self::$currencies;
		foreach($list as $code => $row)
		{
			if(isset($row['title']))
			{
				$list[$code]['title'] = defset('LAN_VSTORE_CURRENCY_' . $code, $row['title']);
			}
		}

		return $list;
	}

	public static function getCurrencyTitle($currency)
	{
		$list = self::getCurrencies();

		return vartrue($list[$currency]['title']);
	}

	public static function getCurrencySymbol($currency, $size = '1x')
	{

		$size = vartrue($size, '1x');
		if(isset(self::$currencies[$currency]['symbol']))
		{
			if(preg_match('/[0-9.]+x/', $size))
			{
				// convert '1x' sizes to '1em'
				$size = floatval($size) . 'em';
			}

			return '<span style="font-size: ' . $size . ';">' . self::$currencies[$currency]['symbol'] . '</span>';
		}
		elseif(isset(self::$currencies[$currency]['glyph']))
		{
			return e107::getParser()->toGlyph(self::$currencies[$currency]['glyph'], array('size' => $size));
		}

		return '';
	}

	/**
	 * Return the $mollie_payment_methods or a single entry
	 *
	 * @param string $method Name of the payment method or null
	 *
	 * @return array|string
	 */
	public static function getMolliePaymentMethods($method = null)
	{

		if(!empty($method))
		{
			return self::$mollie_payment_methods[$method];
		}

		return self::$mollie_payment_methods;
	}


	/**
	 * Return the icon for the given gateway
	 *
	 * @param string $type Payment method
	 * @param string $size 5x (1x, 2x, 3x, 4x, 5x)
	 *
	 * @return string Icon of the payment method
	 */
	public static function getMolliePaymentMethodIcon($type = '', $size = '5x')
	{

		$size = vartrue($size, '5x');
		if(preg_match('/^[0-9.]+x$/', $size))
		{
			// convert '1x' sizes to '1em'
			$size = (float) $size . 'em';
		}
		$size = intval($size);
		$size = vartrue($size, 1) . 'em';
		$text = (!empty(self::$mollie_payment_methods[$type])
			? e_PLUGIN_ABS . 'vstore/' . self::$mollie_payment_methods[$type]['icon']
			: '');

		return e107::getParser()->toImage($text, array(
			'style' => "width: " . $size . "; height: " . $size . ";",
			'class' => 'vstore-mollie-payment-icon img-circle'));
	}

	/**
	 * Return the title for the given gateway
	 *
	 * @param string $type Payment method
	 *
	 * @return string Title of the payment method
	 */
	public static function getMolliePaymentMethodTitle($type = '')
	{

		return !empty(self::$mollie_payment_methods[$type]) ? self::$mollie_payment_methods[$type]['title'] : '';
	}

	/**
	 * Return if gateway type is Mollie
	 *
	 * @param string $type
	 *
	 * @return bool true if is mollie gateway
	 */
	public static function isMollie($type = '')
	{

		return Catalog::isMollie($type);
	}

	/**
	 * Parse and return Mollie Error message
	 *
	 * @param $str    JSON string
	 *
	 * @return string Error message
	 */
	private function getMollieErrorMessage($str)
	{

		if($json = e107::unserialize($str))
		{
			if(vartrue($json['status']) == 'canceled')
			{
				return LAN_VSTORE_MSG_MOLLIE_CANCELED;
			}
			elseif(vartrue($json['status']) == 'failed')
			{
				return LAN_VSTORE_MSG_MOLLIE_FAILED;
			}
			elseif(vartrue($json['status']) == 'expired')
			{
				return LAN_VSTORE_MSG_MOLLIE_EXPIRED;
			}
			elseif(vartrue($json['detail']))
			{
				return $json['detail'];
			}

			return LAN_VSTORE_MSG_GENERIC_ERROR;
		}

		return $str;
	}

	/**
	 * Handle & process all ajax requests
	 *
	 * @return void
	 */
	private function process_ajax()
	{

		if(e_AJAX_REQUEST)
		{
			$js = e107::getJshelper();
			$js->_reset();
			// Process only ajax requests
			if($this->get['add'])
			{
				// Add item to cart
				$itemid = $this->get['add'];
				$itemvars = $this->get['itemvar'];
				if(!$this->addToCart($itemid, $itemvars, varset($this->get['qty'], 1)))
				{
					$msg = e107::getMessage()->render('vstore');
					ob_clean();
					$js->sendTextResponse($msg);
					exit;
				}
				else
				{
					include_once 'e_sitelink.php';
					$sl = new vstore_sitelink();
					$msg = $sl->storeCart();
				}
				ob_clean();
				$js->sendTextResponse('ok ' . $msg);
				exit;
			}

			if(!empty($this->get['reset']))
			{
				// Reset cart
				$this->resetCart();
				include_once 'e_sitelink.php';
				$sl = new vstore_sitelink();
				$msg = $sl->storeCart();
				ob_clean();
				$js->sendTextResponse('ok ' . $msg);
				exit;
			}


			if(!empty($this->get['refresh']))
			{
				// Refresh cart menu
				include_once 'e_sitelink.php';
				$sl = new vstore_sitelink();
				$msg = $sl->storeCart();
				ob_clean();
				$js->sendTextResponse('ok ' . $msg);
				exit;
			}

			// Order processing
			if(isset($this->post['order']) && intval($this->post['id']) > 0 && getperms('P'))
			{
				$this->order->load($this->post['id']);
				if($this->post['order'] === 'checkpayment')
				{
					// Ask Mollie for the payment of the order, access only for Admins!
					$sync = $this->syncMolliePayment((string) $this->order->order_pay_transid);

					if($sync['state'] === 'updated')
					{
						$text = $sync['outcome'] === MolliePayment::PAID ? LAN_VSTORE_MSG_PAYMENT_CHECK_PAID : LAN_VSTORE_MSG_PAYMENT_CHECK_FAILED;
					}
					elseif($sync['state'] === 'unchanged' || $sync['state'] === 'no_order')
					{
						$text = sprintf(LAN_VSTORE_MSG_PAYMENT_CHECK_UNCHANGED, $sync['status']);
					}
					else
					{
						$text = LAN_VSTORE_MSG_PAYMENT_CHECK_ERROR;
					}

					ob_clean();
					$js->sendTextResponse($text);
					exit;
				}

				if($this->post['order'] === 'refund')
				{
					// Refund a payment, $order_refund contains the orderId, access only for Admins!
					$status = 'R';
					$result = $this->order->refundOrder();
				}
				elseif($this->post['order'] === 'complete')
				{
					// Complete an order, $order_complete contains the orderId, access only for Admins!
					$status = 'C';
					$result = $this->order->setOrderStatus($status);
				}
				elseif($this->post['order'] === 'cancel')
				{
					// Cancel an order, $order_cancel contains the orderId, access only for Admins!
					$status = 'X';
					$result = $this->order->setOrderStatus($status);
				}
				elseif($this->post['order'] === 'process')
				{
					// Cancel an order, $order_cancel contains the orderId, access only for Admins!
					$status = 'P';
					$result = $this->order->setOrderStatus($status);
				}
				elseif($this->post['order'] === 'hold')
				{
					// Hold an order, $order_cancel contains the orderId, access only for Admins!
					$status = 'H';
					$result = $this->order->setOrderStatus($status);
				}
				elseif(in_array($this->post['order'], array('qmigrated', 'qapproved', 'qinvoiced', 'qreopen'), true))
				{
					// Quote workflow, access only for Admins!
					$quoteSteps = array('qmigrated' => 'M', 'qapproved' => 'A', 'qinvoiced' => 'F', 'qreopen' => 'Q');
					$status = $quoteSteps[$this->post['order']];
					$result = $this->order->setOrderStatus($status);
				}
				else
				{
					// In case that none of the above has handled the ajax request
					// (which shouldn't happen) just exit
					exit;
				}

				ob_clean();
				// send out the results to the browser
				// will be used in a javascript alert() box
				if($result === true)
				{
					// all went well
					$js->sendTextResponse(
						EMESSLAN_TITLE_SUCCESS . "\n" .
						e107::getParser()->lanVars(
							LAN_VSTORE_MSG_ORDER_UPDATED,
							self::getStatus($status)
						)
					);
				}
				else
				{
					// some error occured
					$js->sendTextResponse(
						($this->order->getLastError()
							? $this->order->getLastError()
							: EMESSLAN_TITLE_ERROR . "\n" . e107::getParser()->lanVars(
								LAN_VSTORE_MSG_ORDER_UPDATE_FAIL,
								self::getStatus($status)
							)
						)
					);
				}
			}

			// In case that none of the above has handled the ajax request
			// (which shouldn't happen) just exit
			exit;
		}
	}


	/**
	 * Handle & process all non-ajax requests
	 *
	 * @return void
	 */
	private function process()
	{

		if(!empty($this->get['reset']))
		{
			$this->resetCart();
		}

		if(varset($this->post['mode']) == 'confirmed')
		{
			$this->setMode($this->post['mode']);
			if(empty($this->getGatewayType(true)))
			{
				e107::getMessage()->addError(LAN_VSTORE_MSG_NO_PAYMENT, 'vstore');

				return;
			}
			elseif(empty($this->getCheckoutData()))
			{
				e107::getMessage()->addError(LAN_VSTORE_MSG_NO_ITEMS, 'vstore');

				return;
			}
			elseif(empty(vstore::getCustomerData(true)))
			{
				e107::getMessage()->addError(LAN_VSTORE_MSG_NO_CUSTOMER, 'vstore');

				return;
			}
			elseif(empty($this->getShippingData(true)))
			{
				e107::getMessage()->addError(LAN_VSTORE_MSG_NO_SHIPPING, 'vstore');

				return;
			}
			else
			{
				if(!empty(trim($this->post['ship']['notes'])))
				{
					// validate/filter order notes
					$tmp = $this->getShippingData(true);
					$tmp['notes'] = trim(strip_tags($this->post['ship']['notes']));
					$this->setShippingData($tmp);
				}

				$this->processGateway(); // init

				return;
			}
		}

		if(varset($this->get['mode']) == 'return')
		{
			$this->processGateway('return');

			return;
		}


		// Quantities and the coupon field share one form; the coupon field keeps the active code,
		// so it must not stop the quantities from being saved.
		if(!empty($this->post['cartQty']) && is_array($this->post['cartQty']))
		{
			$this->updateCart('modify', $this->post['cartQty']);
		}

		if(varset($this->post['cartRemove']))
		{
			$this->updateCart('remove', $this->post['cartRemove']);
		}

		// A cart with products and quote products starts the checkout of one kind, the other stays in the cart
		if(in_array(varset($this->post['cartScope']), array('sale', 'quote'), true))
		{
			$this->startCheckout($this->post['cartScope']);
		}

		if(!empty($this->get['add']))
		{
			if(!e_AJAX_REQUEST)
			{
				$this->addToCart($this->get['add'], $this->get['itemvar'], varset($this->get['qty'], 1));
			}
		}

		// Cancel order
		if(isset($this->post['cancel_order']) && intval($this->post['cancel_order']) > 0 && USER)
		{
			$check = e107::getDb()->retrieve(
				'vstore_orders',
				'*',
				'order_id=' . intval($this->post['cancel_order']) . ' AND order_e107_user = ' . USERID
			);
			if($check)
			{
				$log = e107::unserialize($check['order_log']);
				$log[] = array(
					'datestamp' => time(),
					'user_id'   => USERID,
					'user_name' => USERNAME,
					'text'      => LAN_VSTORE_MSG_LOG_CANCELLED
				);

				$update = array(
					'data'  => array(
						'order_status' => 'X',
						'order_log'    => e107::serialize($log, 'json')
					),
					'WHERE' => 'order_id=' . intval($this->post['cancel_order'])
				);

				e107::getDb()->update('vstore_orders', $update);

				$vc = e107::getSingleton('vstore_order', e_PLUGIN.'vstore/inc/vstore_order.class.php');
				$vc->emailCustomerOnStatusChange($check['order_id']);

				e107::redirect(e107::url('vstore', 'dashboard', array('dash' => 'orders')));
				exit;
			}
		}

		// Save address(es)
		if(isset($this->post['edit_address']) && intval($this->post['edit_address']) > 0 && USER)
		{
			$check = e107::getDb()->retrieve('vstore_customer', '*', 'cust_e107_user = ' . USERID);
			if($check)
			{
				$save = true;
				if(intval($this->post['edit_address']) === 1 && !empty($this->post['cust']['firstname']))
				{
					// Billing address
					$fields = $this->pref['additional_fields'];
					$add = array();
					foreach($fields as $key => $value)
					{
						if(isset($this->post['cust']['add_field' . $key]))
						{
							$add['add_field' . $key] = array(
								'caption' => strip_tags(Translatable::pick(varset($value['caption']), e_LANGUAGE)),
								'value'   => ($value['type'] == 'text'
									? $this->post['cust']['add_field' . $key]
									: ($this->post['cust']['add_field' . $key] ? 'X' : '-'))
							);
							unset($this->post['cust']['add_field' . $key]);
						}
					}
					$this->post['cust']['additional_fields'] = e107::serialize($add, 'json');

					foreach($this->getCustomerFields() as $k)
					{
						if(isset($this->post['cust'][$k]))
						{
							$update['data']['cust_' . $k] = $this->post['cust'][$k];
						}
					}
				}
				elseif(intval($this->post['edit_address']) === 2 && !empty($this->post['ship']['firstname']))
				{
					// Shipping address
					$data = array();
					foreach($this->getShippingFields() as $k)
					{
						if(isset($this->post['ship'][$k]))
						{
							$data[$k] = $this->post['ship'][$k];
						}
					}
					$update['data']['cust_shipping'] = e107::serialize($data, 'json');
				}
				else
				{
					$save = false;
					e107::getMessage()->addError(LAN_VSTORE_MSG_SAVE_FAIL, 'vstore', true);
				}

				if($save)
				{
					$update['WHERE'] = 'cust_e107_user = ' . USERID;
					$result = e107::getDb()->update('vstore_customer', $update);
					e107::getMessage()->addSuccess(LAN_VSTORE_MSG_SAVE_OK, 'vstore', true);
					e107::redirect(e107::url('vstore', 'dashboard', array('dash' => 'addresses')));
					exit;
				}
			}
		}
	}

	/**
	 * Render a form in case the current user is not logged in
	 * for him to decide if he wants to buy as guest, create a
	 * new user account or to login with an existing user account
	 *
	 * @return string
	 */
	private function renderGuestForm()
	{

		$tp = e107::getParser();

		$template = e107::getTemplate('vstore', 'vstore', 'customer');

		return $tp->parseTemplate($template['guest'], true, $this->sc);

	}


	/**
	 * Render customer (billing) information form
	 *
	 * @return string the form
	 */
	private function renderCustomerForm()
	{

		$frm = e107::getForm();
		$tp = e107::getParser();
		$field = '';

		if(!isset($this->post['cust']['firstname']))
		{
			// load saved shipping data and assign to variables
			$data = vstore::getCustomerData();
			$fields = $this->getCustomerFields();
			$prefix = (isset($data['cust_firstname']) ? 'cust_' : '');
			foreach($fields as $field)
			{
				if($field != 'additional_fields')
				{
					$this->post['cust'][$field] = varset($data[$prefix . $field], null);
				}
			}
		}

		$template = e107::getTemplate('vstore', 'vstore', 'customer');

		/**
		 * Additional checkout fields
		 * Start
		 */
		$addFieldActive = 0;
		foreach($this->pref['additional_fields'] as $k => $v)
		{
			// Check if additional fields are enabled
			if(vartrue($v['active'], false))
			{
				$addFieldActive++;
			}
		}

		if($addFieldActive > 0)
		{
			// If any additional fields are enabled
			// add active fields to form
			foreach($this->pref['additional_fields'] as $k => $v)
			{
				if(vartrue($v['active'], false))
				{
					$fieldid = 'add_field' . $k;
					$fieldname = 'cust[' . $fieldid . ']';
					if(isset($this->post['cust'][$fieldid]))
					{
						$fieldvalue = $this->post['cust'][$fieldid];
					}
					else
					{
						$fieldvalue = $this->post['cust']['additional_fields']['value'][$fieldid];
					}
					if($v['type'] == 'text')
					{
						// Textboxes
						$field = $frm->text(
							$fieldname,
							$fieldvalue,
							100,
							array(
								'placeholder' => Translatable::pick(varset($v['placeholder']), e_LANGUAGE),
								'required'    => ($v['required'] ? 1 : 0)
							)
						);
					}
					elseif($v['type'] == 'checkbox')
					{
						// Checkboxes
						$field = '<div class="form-control">' .
							$frm->checkbox(
								$fieldname,
								1,
								0,
								array('required' => ($v['required'] ? 1 : 0))
							);
						if(vartrue($v['placeholder']))
						{
							$field .= ' <label for="' .
								$frm->name2id($fieldname) . '-1" class="text-muted">&nbsp;' .
								$tp->toHTML(Translatable::pick($v['placeholder'], e_LANGUAGE)) . '</label>';
						}
						$field .= '</div>';
					}

					$this->sc->addVars(array(
						'fieldname'     => $fieldname,
						'fieldcaption'  => $tp->toHTML(Translatable::pick(varset($v['caption']), e_LANGUAGE, 'Additional field ' . $k)),
						'field'         => $field,
						'fieldcount'    => $addFieldActive,
						'fieldrequired' => $v['required']
					));

					$this->post['cust']['add'][$fieldid] = $tp->parseTemplate(
						$template['additional']['item'],
						true,
						$this->sc
					);
				}
			}
		}

		$this->sc->setVars($this->post);

		return $tp->parseTemplate($template['header'], true, $this->sc);

		/**
		 * Additional checkout fields
		 * End
		 */

		// if (!USER) {
		//     $text .= e107::getParser()->parseTemplate($template['guest'], true, $this->sc);
		// }


	}


	/**
	 * Render customer shipping information form
	 *
	 * @return string the form
	 */
	private function renderShippingForm()
	{

		$tp = e107::getParser();
		if(!isset($this->post['ship']['firstname']))
		{
			$prefix = '';
			// load saved shipping data and assign to variables
			$data = $this->getShippingData();
			if(empty($data) || empty($data['firstname']))
			{
				$data = vstore::getCustomerData(true);
				$prefix = isset($data['cust_firstname']) ? 'cust_' : '';
			}
			$fields = $this->getShippingFields();
			foreach($fields as $field)
			{
				$this->post['ship'][$field] = varset($data[$prefix . $field], null);
			}
		}

		$template = e107::getTemplate('vstore', 'vstore', 'shipping');

		$this->sc->setVars($this->post);

		return $tp->parseTemplate($template['header'], true, $this->sc);

	}

	/**
	 * Render the confirm order page to review a summary of the order before confirming the order
	 *
	 * @return string
	 */
	private function renderConfirmOrder()
	{
		$cust = vstore::getCustomerData(true);
		$ship = $this->getShippingData(true);
		$data = $this->prepareCheckoutData($this->getCheckoutData(), true);

		$template = e107::getTemplate('vstore', 'vstore', 'orderconfirm');

		$data['cust'] = $cust;
		$data['ship'] = $ship;
		$data['order_pay_gateway'] = $this->getGatewayType(true);

		$isQuote = !empty($data['is_quote']);
		if($isQuote)
		{
			$data['order_pay_gateway'] = Catalog::QUOTE_GATEWAY;
		}

		if(!empty($ship['address']))
		{
			$data['order_use_shipping']  = 1;

		}

		$this->sc->setVars($data);

		$data['billing_address'] = e107::getParser()->parseTemplate($template['billing'], true, $this->sc);

		if($data['order_use_shipping'] == 1)
		{
			$data['shipping_address'] = e107::getParser()->parseTemplate($template['shipping'], true, $this->sc);
		}

		$this->sc->setVars($data);

		// A theme without the quote_main part keeps the standard confirmation
		return e107::getParser()->parseTemplate(($isQuote && isset($template['quote_main'])) ? $template['quote_main'] : $template['main'], true, $this->sc);
	}


	private function getMode()
	{

		return vartrue($this->get['mode']);
	}

	private function setMode($mode)
	{

		$this->get['mode'] = $mode;
	}

	/**
	 * Render the vstore pages
	 *
	 * @return string
	 */
	public function render()
	{

		$ns = e107::getRender();

		if(!empty($this->get['download']))
		{
			if(!$this->downloadFile($this->get['download']))
			{
				// $bread = $this->setBreadcrumb();
				$this->setBreadcrumb();
				$msg = e107::getMessage()->render('vstore');

				// $ns->tablerender($this->captionBase, $bread . $msg, 'vstore-download-failed');
				$ns->tablerender($this->captionBase, $msg, 'vstre-download-failed');

				return null;
			}
			else
			{
				// Not needed but ...
				// $bread = $this->setBreadcrumb();
				$this->setBreadcrumb();
				$msg = e107::getMessage()->addSuccess(LAN_VSTORE_MSG_FILE_DOWNLOADED)->render('vstore');

				// $ns->tablerender($this->captionBase, $bread . $msg, 'vstore-download-done');
				$ns->tablerender($this->captionBase, $msg, 'vstore-download-done');

				return null;
			}
		}

		if($this->getMode() == 'return')
		{
			// print_a($this->post);
			// $bread = $this->setBreadcrumb();
			$this->setBreadcrumb();
			$text = $this->checkoutComplete();
			$msg = e107::getMessage()->render('vstore');

			// $ns->tablerender($this->captionBase, $bread . $msg . $text, 'vstore-cart-complete');
			$ns->tablerender($this->captionBase, $msg . $text, 'vstore-cart-complete');

			return null;
		}


		if($this->getMode() == 'checkout')
		{
			$text = '';

			// Validate posted data
			if($this->post['mode'] == 'shipping' || $this->post['mode'] == 'confirm')
			{
				if(!empty($this->post['cust']['firstname']))
				{
					// validate billing data
					$result = $this->validateCustomerData($this->post['cust']); // billing.
					if(!$result)
					{
						// Something wrong. Stay at the billing address page
						$text .= e107::getMessage()->render('vstore');
						$this->post['mode'] = 'customer';
					}
					else
					{
						$this->post['cust'] = $result;
					}
				}
				elseif(!empty($this->post['ship']['firstname']))
				{
					// Validate shipping data
					$result = $this->validateCustomerData($this->post['ship'], 'shipping');
					if(!$result)
					{
						// Something wrong. Stay at the shipping address page
						$text .= e107::getMessage()->render('vstore');
						$this->post['mode'] = 'shipping';
					}
					else
					{
						$this->post['ship'] = $result;
					}
				}
			}

			// Render pages
			if($this->post['mode'] == 'shipping')
			{
				// Shipping data form
				// $bread = $this->setBreadcrumb();
				$this->setBreadcrumb();

				if(!empty($this->post['cust']['firstname']))
				{
					$this->setCustomerData($this->post['cust']);
					$this->setGatewayType($this->post['gateway']);
					$text .= $this->shippingView();
				}
				else
				{
					$text .= e107::getMessage()->addError(LAN_VSTORE_MSG_BILLING_MISSING, 'vstore')->render('vstore');
				}

				// $ns->tablerender($this->captionBase, $bread . $text, 'vstore-cart-list');
				$ns->tablerender($this->captionBase, $this->checkoutPage(2, $text), 'vstore-cart-list');

				return null;
			}
		elseif($this->post['mode'] == 'confirm')
		{
			// Confirm order form
			$this->setShippingType(0);
			$this->setShippingData(null);
			if(!empty($this->post['ship']['firstname']))
			{
				$this->setShippingType($this->post['order_use_shipping']);
				$this->setShippingData($this->post['ship']);
			}

			if(!empty($this->post['cust']['firstname']))
			{
				$this->setCustomerData($this->post['cust']);
				$this->setGatewayType($this->post['gateway']);
				
				// If shipping type is 0 (use billing address), copy customer data to shipping
				if(!$this->getShippingType() && empty($this->getShippingData(true)))
				{
					$customerData = vstore::getCustomerData(true);
					if(!empty($customerData))
					{
						$this->setShippingData($customerData);
					}
				}
			}				if(empty(vstore::getCustomerData(true)))
				{
					$text .= e107::getMessage()->addError(LAN_VSTORE_MSG_BILLING_MISSING, 'vstore')->render('vstore');
				}
				elseif(vartrue($this->post['order_use_shipping']) && empty($this->getShippingData(true)))
				{
					$text .= e107::getMessage()->addError(LAN_VSTORE_MSG_SHIPPING_MISSING, 'vstore')->render('vstore');
				}
				elseif(empty($this->getCheckoutData()))
				{
					$text .= e107::getMessage()->addError(LAN_VSTORE_MSG_NO_ITEMS, 'vstore')->render('vstore');
				}
				else
				{
					// Order confirmation
					$text .= $this->confirmOrderView();
				}

				// $bread = $this->setBreadcrumb();
				$this->setBreadcrumb();
				// $ns->tablerender($this->captionBase, $bread . $text, 'vstore-cart-list');
				$ns->tablerender($this->captionBase, $this->checkoutPage(3, $text, false), 'vstore-cart-list');

				return null;
			}
			else
			{
				// Customer Data Form
				// $bread = $this->setBreadcrumb();
				$this->setBreadcrumb();

				if(empty($this->getCheckoutData()))
				{
					$text .= e107::getMessage()->addError(LAN_VSTORE_MSG_NO_ITEMS, 'vstore')->render('vstore');
				}
				else
				{
					$text .= $this->checkoutView();
				}
				// $ns->tablerender($this->captionBase, $bread . $text, 'vstore-cart-list');
				// The choice between guest, sign up and login is wide: no order summary beside it
				$ns->tablerender($this->captionBase, $this->checkoutPage(2, $text, USER || isset($_POST['as_guest'])), 'vstore-cart-list');

				return null;
			}
		}

		if($this->getMode() == 'confirmed')
		{
			// Order confirmation
			$msg = e107::getMessage()->render('vstore');

			if($msg)
			{
				// $bread = $this->setBreadcrumb();
				$this->setBreadcrumb();
				// $ns->tablerender($this->captionBase, $bread . $msg, 'vstore-cart-list');
				$ns->tablerender($this->captionBase, $msg, 'vstore-cart-list');
			}

			return null;
		}


		if((int)varset($this->get['invoice']) > 0)
		{
			// Display invoice
			$this->order->loadByInvoiceNr($this->get['invoice']);
			$data = $this->renderInvoice();
			$text = '';
			if($data)
			{
				if(vartrue($this->pref['invoice_create_pdf']))
				{
					// if invoice is correctly rendered, convert to pdf
					$this->invoiceToPdf($data, !false);
					$local_pdf = $this->pathToInvoicePdf($this->get['invoice'], $data['userid']);
					$this->downloadInvoicePdf($local_pdf);
				}
				else
				{
					$text = $data;
				}
			}

			$msg = e107::getMessage()->render('vstore');
			if(!empty($msg) || !empty($text))
			{
				// $bread = $this->setBreadcrumb();
				$this->setBreadcrumb();
				// $ns->tablerender($this->captionBase, $bread.$msg.$text, 'vstore-invoice');
				$ns->tablerender($this->captionBase, $msg . $text, 'vstore-invoice');
			}

			return null;
		}


		if($this->getMode() == 'dashboard')
		{
			// render dashboard
			include_once 'inc/vstore_dashboard.class.php';
			$dashboard = new vstore_dashboard();
			$text = $dashboard->render();

			// $bread = $this->setBreadcrumb();
			$this->setBreadcrumb();
			$msg = e107::getMessage()->render('vstore');
			// $ns->tablerender($this->captionBase, $bread . $msg . $text, 'vstore-dashboard');
			$ns->tablerender($this->captionBase, $msg . $text, 'vstore-dashboard');

			return null;
		}


		if($this->getMode() == 'cart')
		{
			// print_a($this->post);
			// $bread = $this->setBreadcrumb();
			$this->setBreadcrumb();
			$text = $this->cartView();
			$msg = e107::getMessage()->render('vstore');
			// $ns->tablerender($this->captionBase, $bread . $msg . $text, 'vstore-cart-list');
			$ns->tablerender($this->captionBase, $msg . $text, 'vstore-cart-list');

			return null;
		}


		if(!empty($this->get['item']))
		{
			$text = $this->productView($this->get['item']);
			// $bread = $this->setBreadcrumb();
			$this->setBreadcrumb();
			$msg = e107::getMessage()->render('vstore');
			// $ns->tablerender($this->captionBase, $bread . $msg . $text, 'vstore-product-view');
			$ns->tablerender($this->captionBase, $this->categoryBar() . $msg . $text, 'vstore-product-view');

			return null;
		}


		if($this->get['cat'])
		{
			if($subCategoryText = $this->categoryList($this->get['cat']))
			{
				$subCategoryText .= "<hr />";
			}

			$text = $this->productList($this->get['cat'], true);
			// $bread = $this->setBreadcrumb();
			$this->setBreadcrumb();
			$msg = e107::getMessage()->render('vstore');
			// $ns->tablerender($this->captionBase, $bread . $msg . $subCategoryText . $text, 'vstore-product-list');
			$ns->tablerender($this->captionBase, $this->categoryBar() . $msg . $subCategoryText . $text, 'vstore-product-list');
		}
		else
		{
			// No category set, render root category
			$text = $this->categoryList(0, true);
			// $bread = $this->setBreadcrumb();
			$this->setBreadcrumb();
			$msg = e107::getMessage()->render('vstore');
			// $ns->tablerender($this->captionBase, $bread . $msg . $text, 'vstore-category-list');
			$ns->tablerender($this->captionBase, $this->categoryBar() . $msg . $text, 'vstore-category-list');
		}
	}


	/**
	 * Custom function to calculate breadcrumb for the current page.
	 *
	 * @return void
	 */
	private function setBreadcrumb()
	{

		// $frm = e107::getForm();
		$array = array();

		$array[] = array('url' => e107::url('vstore', 'index'), 'text' => $this->captionBase);

		if(!isset($this->get['mode']))
		{
			if(!empty($this->get['download']))
			{
				$array[] = array('url' => e107::url('vstore', 'index'), 'text' => LAN_DOWNLOAD);
			}
			elseif(!empty($this->get['invoice']))
			{
				$array[] = array('url' => e107::url('vstore', 'index'), 'text' => LAN_VSTORE_MSG_BC_INVOICE);
				$array[] = array(
					'url'  => e107::url('vstore', 'invoice', array('order_invoice_nr' => $this->get['invoice'])),
					'text' => self::formatInvoiceNr($this->get['invoice'])
				);
			}
			else
			{
				$array[] = array('url' => e107::url('vstore', 'index'), 'text' => $this->captionCategories);
			}
		}

		if(!empty($this->get['cat']) || !empty($this->get['item']))
		{
			$c = varset($this->get['cat'],0);
			$cp = varset($this->categories[$c]['cat_parent']);

			if(!empty($cp))
			{
				$pid = $this->categories[$cp]['cat_id'];
				$url = e107::url('vstore', 'category', $this->categories[$pid]);
				$array[] = array('url' => $url, 'text' => $this->categories[$pid]['cat_name']);
			}

			$id = !empty($this->get['item']) ? (int) $this->item['item_cat'] : (int) $this->get['cat'];
			$url = !empty($this->get['item']) ? e107::url('vstore', 'category', $this->categories[$id]) : null;
			$array[] = array('url' => $url, 'text' => $this->categories[$id]['cat_name']);
		}

		if(!empty($this->get['item']))
		{
			$array[] = array('url' => null, 'text' => $this->item['item_name']);
		}

		if(!empty($this->get['add']) || varset($this->get['mode']) === 'cart')
		{
			$array[] = array('url' => null, 'text' => LAN_VSTORE_MSG_BC_CART);
		}

		if(varset($this->get['mode']) === 'checkout')
		{
			$array[] = array('url' => e107::url('vstore', 'cart'), 'text' => LAN_VSTORE_MSG_BC_CART);
			$array[] = array('url' => null, 'text' => LAN_VSTORE_FR_CHECKOUT);
		}

		if(varset($this->get['mode']) === 'dashboard')
		{
			if(!empty(trim($this->get['area'])))
			{
				include_once 'inc/vstore_dashboard.class.php';
				$dashboard = new vstore_dashboard();
				$array[] = array(
					'url'  => e107::url('vstore', 'dashboard', array('dash' => $this->get['area'])),
					'text' => $dashboard->getArea()
				);
				if(!empty(trim($this->get['action'])))
				{
					$array[] = array(
						'url'  => e107::url('vstore', 'dashboard_action', array(
							'dash'   => $this->get['area'],
							'action' => $this->get['action'],
							'id'     => $dashboard->getId()
						)),
						'text' => $dashboard->getAction()
					);
				}
			}
			else
			{
				$array[] = array(
					'url'  => e107::url('vstore', 'dashboard', array('dash' => 'dashboard')),
					'text' => LAN_VSTORE_FR_MY_DASHBOARD
				);
			}
		}


		if(ADMIN)
		{
			// print_a($this->categories);
			// print_a($this->item);
			//	$last = end($array);
		//	 print_a($last);
			/* @todo add caonical by taking the last breadcrumb and using e107::link  */
		}
		// return $frm->breadcrumb($array);

		// assign values to the Magic Shortcode:  {---BREADCRUMB---}



		e107::breadcrumb($array);
	}


	/**
	 * Return the active payment gateway information
	 *
	 * @return array
	 */
	public function getActiveGateways()
	{
		return $this->active;
	}


	/**
	 * Remove the Mollie payment methods that are not enabled in the Mollie website profile.
	 * Offering them ends in "The payment method is not enabled in your website profile".
	 * When Mollie cannot be asked, every method stays available.
	 *
	 * @param array $active payment method => icon
	 * @return array
	 */
	private function hideDisabledMollieMethods(array $active)
	{
		$mollie = array_filter(array_keys($active), array('Vstore\Support\Catalog', 'isMollie'));

		if(!$mollie)
		{
			return $active;
		}

		$enabled = $this->getMollieEnabledMethods();

		if($enabled === null)
		{
			return $active;
		}

		foreach($mollie as $key)
		{
			if(!in_array(substr($key, 7), $enabled, true))
			{
				unset($active[$key]);
			}
		}

		return $active;
	}

	/**
	 * Payment methods enabled in the Mollie website profile, kept in the cache for 10 minutes.
	 *
	 * @return array|null ids such as paypal or banktransfer, null when Mollie could not be asked
	 */
	private function getMollieEnabledMethods()
	{
		$mode = !empty($this->pref['mollie_testmode']) ? 'test' : 'live';
		$key = (string) varset($this->pref['mollie_api_key_' . $mode], '');
		$tag = 'vstore_mollie_methods_' . $mode . '_' . substr(md5($key), 0, 12);

		$cache = e107::getCache();
		if($cached = $cache->retrieve($tag, 10, true))
		{
			$list = json_decode($cached, true);

			if(is_array($list))
			{
				return $list;
			}
		}

		list($gateway) = self::createGateway('mollie', $this->pref);

		if(!is_object($gateway))
		{
			return null;
		}

		try
		{
			$response = $gateway->fetchPaymentMethods(array())->send();

			if(!$response->isSuccessful())
			{
				return null;
			}

			$list = array();
			foreach($response->getPaymentMethods() as $method)
			{
				$list[] = $method->getId();
			}
		}
		catch(\Exception $e)
		{
			e107::getMessage()->addDebug('Mollie payment methods: ' . $e->getMessage(), 'vstore');

			return null;
		}

		$cache->set($tag, json_encode($list), true);

		return $list;
	}

	/**
	 * Wrap a checkout page with the progress steps and, next to it, the summary of what is being ordered.
	 *
	 * @param int    $step      2 = details and address, 3 = review
	 * @param string $main      html of the page
	 * @param bool   $withAside show the order summary beside the form
	 * @return string
	 */
	private function checkoutPage($step, $main, $withAside = true)
	{
		$tp = e107::getParser();
		$template = e107::getTemplate('vstore', 'vstore', 'checkout');

		$steps = $tp->parseTemplate(strtr($template['steps'], array(
			'[cart_url]' => e107::url('vstore', 'cart', 'sef'),
			'[step2]'    => $step >= 3 ? 'is-done' : 'is-active',
			'[step3]'    => $step >= 3 ? 'is-active' : '',
		)), true, $this->sc);

		$aside = $withAside ? $this->checkoutAside() : '';

		return strtr($template['layout'], array(
			'[steps]'      => $steps,
			'[grid_class]' => $aside === '' ? '' : ' has-aside',
			'[main]'       => $main,
			'[aside]'      => $aside,
		));
	}

	/**
	 * Summary of the products and totals of the checkout, shown beside the forms.
	 *
	 * @return string
	 */
	private function checkoutAside()
	{
		$data = $this->getCheckoutData();

		if(empty($data['items']))
		{
			return '';
		}

		$tp = e107::getParser();
		$template = e107::getTemplate('vstore', 'vstore', 'checkout');
		$isQuote = !empty($data['is_quote']);
		$totals = (array) varset($data['totals']);
		$net = !empty($totals['is_business']) && empty($totals['is_local']);

		$count = 0;
		$list = '';
		foreach($data['items'] as $row)
		{
			$count += (int) $row['cart_qty'];

			$images = e107::unserialize(varset($row['item_pic']));
			$pic = !empty($images[0]['path'])
				? $tp->toImage($images[0]['path'], array('w' => 96, 'h' => 96, 'crop' => 1, 'alt' => ''))
				: '<span class="vstore-noimg" aria-hidden="true"><i class="fa fa-cube"></i></span>';

			$list .= strtr($template[$isQuote ? 'aside_item_quote' : 'aside_item'], array(
				'[pic]'   => $pic,
				'[name]'  => $tp->toHTML($row['item_name'], false, 'TITLE'),
				'[vars]'  => !empty($row['itemvarstring']) ? ' ' . strip_tags($row['itemvarstring']) : '',
				'[qty]'   => (int) $row['cart_qty'],
				'[total]' => $isQuote ? '' : $this->sc->format_amount(varset($row['item_total'])),
			));
		}

		$html = $tp->parseTemplate(strtr($template['aside_start'], array('[count]' => sprintf(LAN_VSTORE_FR_ITEM_COUNT, $count))), true, $this->sc) . $list;

		if($isQuote)
		{
			return $html . $tp->parseTemplate($template['aside_end_quote'], true, $this->sc);
		}

		$row = function($label, $value, $class = '') use ($template)
		{
			return strtr($template['aside_row'], array('[label]' => $label, '[value]' => $value, '[class]' => $class));
		};

		$rows = $row(LAN_VSTORE_FR_SUBTOTAL, $this->sc->format_amount($net ? $totals['cart_subNet'] : $totals['cart_subTotal']));
		$rows .= $row(LAN_VSTORE_FR_SHIPPING, $this->sc->format_amount($net ? $totals['cart_shippingNet'] : $totals['cart_shippingTotal']));

		$coupon = (array) varset($totals['cart_coupon']);
		if(!empty($coupon['code']) && !empty($coupon['amount']))
		{
			$rows .= $row(LAN_VSTORE_FR_COUPON . ' ' . $tp->toHTML($coupon['code'], false, 'TITLE'), $this->sc->format_amount($net ? $coupon['amount_net'] : $coupon['amount']), ' is-discount');
		}

		if(!$net)
		{
			$tax = 0;
			foreach((array) varset($totals['cart_taxTotal']) as $rate => $amount)
			{
				if($tp->toNumber($rate) > 0)
				{
					$tax += $amount;
				}
			}

			if($tax > 0)
			{
				$rows .= $row(LAN_VSTORE_FR_TAX, $this->sc->format_amount($tax));
			}
		}

		$rows .= $row(LAN_VSTORE_FR_TOTAL, $this->sc->format_amount($net ? $totals['cart_grandNet'] : $totals['cart_grandTotal']), ' is-total');

		return $html . strtr($template['aside_end'], array('[rows]' => $rows));
	}

	/**
	 * Render checkout complete message
	 *
	 * @return string
	 */
	private function checkoutComplete()
	{

		e107::getMessage()->addSuccess('<br/>' . plugin_vstore_vstore_shortcodes::sc_cart_continueshop(), 'vstore');
		$text = e107::getMessage()->render('vstore');

		if(!empty($this->html_invoice))
		{
			$text .= $this->html_invoice;
		}

		// $text .= "<div class='alert-block'>" . plugin_vstore_vstore_shortcodes::sc_cart_continueshop() . "</div>";

		return $text;
	}


	/**
	 * Render checkout page to enter the customers shipping information
	 * @todo make template.
	 * @return string
	 */
	private function checkoutView()
	{

		$checkout = $this->getCheckoutData();
		$isQuote = !empty($checkout['is_quote']);

		$active = $isQuote ? array() : $this->getActiveGateways();

		$curGateway = $isQuote ? '' : $this->getGatewayType();

		if(!USER && !isset($_POST['as_guest']))
		{

			$text = e107::getForm()->open(
				'gateway-select',
				'post',
				e107::url('vstore', 'checkout', 'sef'),
				array('class' => 'form')
			);
			$text .= $this->renderGuestForm();
			$text .= e107::getForm()->close();


			return $text;
		}


		if(!$isQuote)
		{
			$active = $this->hideDisabledMollieMethods($active);

			if(empty($active))
			{
				return LAN_VSTORE_MSG_NO_PAY_OPTIONS;
			}
		}

		$text = e107::getForm()->open(
			'gateway-select',
			'post',
			e107::url('vstore', 'checkout', 'sef'),
			array('class' => 'form')
		);

		$text .= $this->renderCustomerForm();
		$text .= "<div class='vstore-toggle'><i class='fa fa-truck' aria-hidden='true'></i> <a id='shipping-view-toggle' class='e-expandit' href='#shipping-view'>".LAN_VSTORE_MSG_ADD_SHIPPING."</a></div>";
		$text .= "<div id='shipping-view' style='display:none'>";
		$text .= $this->renderShippingForm();
		$text .= "</div>";

		if($isQuote)
		{
			// No payment: the request is saved as a quote
			$text .= "<input type='hidden' name='gateway' value='" . Catalog::QUOTE_GATEWAY . "'><p class='vstore-summary-note'><i class='fa fa-info-circle' aria-hidden='true'></i> " . LAN_VSTORE_QUOTE_CART_NOTE . "</p>";
		}
		else
		{
			$text .= "<section class='vstore-panel'><h3 class='vstore-panel-title'><span class='vstore-panel-icon'><i class='fa fa-credit-card' aria-hidden='true'></i></span> ".LAN_VSTORE_MSG_SELECT_PAYMENT."</h3><div class='vstore-gateway-list row g-3'>";
		}

		if(count($active) == 1 && empty($curGateway))
		{
			$curGateway = array_keys($active)[0];
		}
		foreach($active as $gateway => $icon)
		{
			$text .= "
                        <div class='col-6 col-md-4 d-grid'>
                            <label class='btn btn-default btn-light btn-secondary btn-block btn-" . $gateway . " " . ($curGateway == $gateway ? 'active' : '') . " vstore-gateway text-center'>
                                <input type='radio' name='gateway' value='" . $gateway . "' style='display:none;' class='vstore-gateway-radio' required " . ($curGateway == $gateway ? 'checked' : '') . ">
                                " . $icon . "
                                <h4>" . (self::isMollie($gateway) ? $this->getMolliePaymentMethodTitle($gateway) : $this->getGatewayTitle($gateway)) . "</h4>
                            </label>
                        </div>";
		}

		$text .= $isQuote ? '' : "</div></section>";

		/*

					$text .= '<br/>
					<div class="row">
						<div class="col-md-12">
							<div class="alert alert-info">
							<button class="btn btn-default btn-secondary vstore-btn-add-shipping" type="submit" name="mode" value="shipping"><i class="fa fa-truck" aria-hidden="true"></i> Enter shipping address</button>
							<span class="help-text">Use this button to use or enter a separate shipping address.</span>
							</div>
						</div>
					</div>';
					*/
		$text .= '
            <div class="vstore-co-actions">
                <a class="btn btn-default btn-secondary vstore-btn-back-confirm" href="' . e107::url('vstore', 'cart', 'sef') . '">&laquo; ' . LAN_VSTORE_FR_BACK . '</a>
                <button class="btn btn-primary btn-lg vstore-btn-buy-now" type="submit" name="mode" value="confirm">' . LAN_VSTORE_MSG_CONTINUE . ' &raquo;</button>
            </div>';

		$text .= e107::getForm()->close();

		/**
		 * Only make shipping fields 'required' when they are visible.
		 */
		e107::js('footer-inline', "
		
		$(document).ready(function()
		{
		     $('#shipping-view-toggle.e-expandit').on('click', function() {
		         var opened = $(this).hasClass('open'); // ie. we're closing it. 
		        
		            var fields = ['firstname', 'lastname', 'address', 'state', 'city', 'zip', 'country'];
					fields.forEach(function(item)
					{
						$('#ship-' + item).attr('required', true); 					
					});
		        
		         if(opened === false)
		         {
		            fields.forEach(function(item)
					{
						$('#ship-' + item).attr('required', true); 
					});
		        
		         }
		         else
		         {
		             fields.forEach(function(item)
					{
						$('#ship-' + item).attr('required', false); 
					});
		         }
		            
		     });
		
});	
");





		return $text;


	}


	/**
	 * Render shipping address page to enter the customers shipping information
	 *
	 * @return string
	 */
	private function shippingView()
	{

		$active = $this->getActiveGateways();
		$curGateway = $this->getGatewayType(true);
		$checkout = $this->getCheckoutData();
		if(!empty($active) || !empty($checkout['is_quote']))
		{
			$text = e107::getForm()->open(
				'gateway-select',
				'post',
				e107::url('vstore', 'checkout', 'sef'),
				array('class' => 'form')
			);

			$text .= $this->renderShippingForm();

			$text .= '
            <div class="vstore-co-actions">
                <input type="hidden" name="order_use_shipping" value="1">
                <a class="btn btn-default btn-secondary vstore-btn-back-confirm" href="' . e107::url('vstore', 'checkout', 'sef') . '">&laquo; ' . LAN_VSTORE_FR_BACK . '</a>
                <button class="btn btn-primary btn-lg vstore-btn-buy-now" type="submit" name="mode" value="confirm">' . LAN_VSTORE_MSG_CONTINUE . ' &raquo;</button>
            </div>';

			$text .= e107::getForm()->close();


			return $text;
		}

		return LAN_VSTORE_MSG_NO_PAY_OPTIONS;
	}


	/**
	 * Render confirm order page
	 *
	 * @return string
	 */
	private function confirmOrderView()
	{

		$text = e107::getForm()->open('confirm-order', 'post', null, array('class' => 'form'));

		$text .= $this->renderConfirmOrder();

		$text .= e107::getForm()->close();

		return $text;
	}

	/**
	 * Process the payment via selected payment gateway
	 *
	 * @see http://stackoverflow.com/questions/20756067/omnipay-paypal-integration-with-laravel-4
	 * @see https://www.youtube.com/watch?v=EvfFN0-aBmI
	 * @param string $mode
	 * @return boolean
	 */
	public function processGateway($mode = 'init')
	{

		$type = $this->getGatewayType(true);

		e107::getDebug()->log("Processing Gateway: " . $type);

		if(empty($type))
		{
			e107::getMessage()->addError(LAN_VSTORE_MSG_INVALID_PAYMENT, 'vstore');
			trigger_error("Invalid payment type");  // debug only
			return false;
		}

		$paymentMethod = '';
		if($type === Catalog::QUOTE_GATEWAY)
		{
			return $this->processQuote();
		}

		if(self::isMollie($type))
		{
			$paymentMethod = substr($type, 7);
			$type = substr($type, 0, 6);
		}

		list($gateway, $message) = $this->loadGateway($type);

		if($type === 'bank_transfer')
		{
			$mode = 'halt';
		}
		elseif(!is_object($gateway))
		{
			e107::getMessage()->addError($message ?: LAN_VSTORE_MSG_PAY_ERROR, 'vstore');

			return false;
		}

		$cardInput = null;
		$data = $this->getCheckoutData();

		if(empty($data['items']))
		{
			e107::getMessage()->addError(LAN_VSTORE_MSG_CART_EMPTY_ERR, 'vstore');
			trigger_error("Shopping Cart Empty"); // debug only
			return false;
		}
		else
		{
			$items = $this->orderItemsFromCheckout($data);
		}

		if($mode === 'halt')
		{
			// eg. bank-transfer.
			$transID = null;
			$transData = null;
			$this->saveTransaction($transID, $transData, $items);
			$this->resetCart('sale');

			if(!empty($message))
			{
				e107::getMessage()->addSuccess($message, 'vstore');
			}

			e107::getSession('vstore')->clear('_data');
		//	unset($_SESSION['vstore']['_data']);

			// Forcethe browser window to refresh the cart menu
			e107::js('footer-inline', '$(function(){ vstoreCartRefresh(); });');

			return null;
		}
		elseif($mode === 'init')
		{
			$method = $gateway->supportsAuthorize() ? 'authorize' : 'purchase';

			$_data = array(
				'cancelUrl'      => e107::url('vstore', 'cancel', null, array('mode' => 'full')),
				'returnUrl'      => e107::url('vstore', 'return', null, array('mode' => 'full')),
				'amount'         => (float) $data['totals']['cart_grandTotal'],
				'shippingAmount' => (float) $data['totals']['cart_shippingTotal'],
				'currency'       => $data['currency'],
				'items'          => $items,
				'transactionId'  => $this->getCheckoutData('id'),
				'clientIp'       => USERIP,
				'description'    => 'Order date: ' . e107::getDate()->convert_date(time(), 'inputdate'), // required for Mollie
			);

			$tokenKey = $type.'Token';
			if(!empty($_POST[$tokenKey]))
			{
				$_data['token'] = $_POST[$tokenKey];
			}

			if($type == 'mollie')
			{
				$_data['paymentMethod'] = $paymentMethod;

				// Mollie reports later payment changes (MB WAY approval, Multibanco payment) to this URL; it must be reachable from the internet
				$webhookUrl = e107::url('vstore', 'webhook', null, array('mode' => 'full'));
				if(MolliePayment::isPublicUrl($webhookUrl))
				{
					$_data['notifyUrl'] = $webhookUrl;
				}

				// Contact data sent to Mollie as billingAddress (the phone only when it is a valid international number)
				$billing = (array) self::getCustomerData(true);
				if(!empty($billing['email']))
				{
					$_data['billingEmail'] = $billing['email'];
				}

				$phone = PhoneNumber::toE164(varset($billing['phone']), varset($billing['country']));
				if($phone !== null)
				{
					$_data['billingPhone'] = $phone;
				}
			}

			e107::getSession('vstore')->set('_data', $_data);
			// file_put_contents(__DIR__."/checkoutData.log", var_export($_data,true));
		}
		else // Mode 'return'.
		{

			$method = 'completePurchase';

			if($gateway->supportsAuthorize() && $gateway->supportsCompleteAuthorize())
			{
				// Workaround to make sure the payment is complete and not in pending state
				if($type !== 'paypal')
				{
					$method = 'completeAuthorize';
				}
			}

			// Get stored data.
			$_data = (array) e107::getSession('vstore')->get('_data');
			// Add PayerID, paymentId, token, etc...

			$_data = array_merge($_data, $this->get);
		}

		try
		{
			/** @var \Omnipay\Common\Message\AbstractResponse $response */
			$response = $gateway->$method($_data)->send();
		}
		catch(\Exception $e)
		{
			// Plugin classes import Ibericode\Vat\Exception as Exception, so the global one needs the backslash
			$message = $e->getMessage();
			if($type == 'mollie')
			{
				$message = $this->getMollieErrorMessage($message);
			}

			e107::getLog()->add('Vstore', 'Payment error (' . $type . '): ' . $e->getMessage(), E_LOG_WARNING);
			e107::getMessage()->addError(ADMIN ? $message : LAN_VSTORE_MSG_PAY_ERROR, 'vstore');

			return false;
		}

		$pending = false;
		$existingOrderId = 0;
		if($mode === 'return' && $type == 'mollie')
		{
			$paymentData = (array) $response->getData();
			$pending = MolliePayment::outcome(varset($paymentData['status'])) === MolliePayment::PENDING;
			$existingOrderId = (new OrderRepository())->orderIdFromMollieTransaction((string) $response->getTransactionReference());
		}

		if(!$pending && $response->isRedirect())
		{
			// Get transaction ID from the Authorize response.
			if($transID = $response->getTransactionReference())
			{
				// Store transaction ID for later use.
				e107::getSession('vstore')->set('_data/transactionReference', $transID);
			//	$_SESSION['vstore']['_data']['transactionReference'] = $transID;
			}

			if($type == 'mollie' && $mode === 'init' && $transID)
			{
				// The order exists before the customer pays, so a payment made without coming back is never lost
				if(!$this->saveTransaction($transID, (array) $response->getData(), $items, 'N', 'incomplete', true))
				{
					e107::getMessage()->addError(LAN_VSTORE_MSG_TRANS_FAIL, 'vstore');

					return false;
				}
			}

			// Redirect to offsite payment gateway.
			$response->redirect();
		}
		elseif($existingOrderId)
		{
			$this->finishMollieReturn($response);
		}
		elseif($response->isSuccessful() || $pending)
		{
			// A pending payment (MB WAY not approved yet, Multibanco reference) is registered as a new order waiting for its payment
			$order_status = $pending ? 'N' : 'P';
			$transData = $response->getData();
			$transID = $response->getTransactionReference();
			$message = $response->getMessage();
			if($type == 'mollie')
			{
				$message = $pending ? LAN_VSTORE_MSG_MOLLIE_PENDING : LAN_VSTORE_MSG_MOLLIE_PAID;
			}

			if($pending)
			{
				e107::getMessage()->addInfo($message, 'vstore');
			}
			else
			{
				e107::getMessage()->addSuccess($message, 'vstore');
			}

			$this->saveTransaction($transID, $transData, $items, $order_status, $pending ? 'incomplete' : ''); // Order payed > save as Processing
			$this->resetCart('sale');

			e107::getSession('vstore')->clear('_data');
		//	unset($_SESSION['vstore']['_data']);
		}
		else
		{
			$message = $response->getMessage();
			if($type == 'mollie')
			{
				$message = $this->getMollieErrorMessage($message);
			}
			e107::getMessage()->addError($message, 'vstore');
		}
	}

	/**
	 * Order lines (as stored in the order) from the checkout data.
	 *
	 * @param array $data checkout data with the items
	 * @return array
	 */
	private function orderItemsFromCheckout(array $data)
	{
		$items = array();

		foreach($data['items'] as $var)
		{
			$price = $var['item_price'];
			$itemvarstring = '';
			if(!empty($var['cart_item_vars']))
			{
				$itemprop = self::getItemVarProperties($var['cart_item_vars'], $var['item_price']);

				if($itemprop)
				{
					$itemvarstring = $itemprop['variation'];
				}
			}


			$items[] = array(
				'id'          => $var['item_id'],
				'name'        => $var['item_code'],
				'price'       => (float) $price,
				'description' => $var['item_name'],
				'quantity'    => (int) $var['cart_qty'],
				'tax_rate'    => $var['tax_rate'],
				'file'        => $var['item_download'],
				'vars'        => $itemvarstring,
				'item_vars'   => $var['cart_item_vars']
			);
		}

		return $items;
	}

	/**
	 * Save a quote request: no payment, no invoice number and no stock change. The customer and the shop get the same e-mail.
	 *
	 * @return bool|null false when the request could not be saved
	 */
	private function processQuote()
	{
		$data = $this->getCheckoutData();

		if(empty($data['items']) || empty($data['is_quote']))
		{
			e107::getMessage()->addError(LAN_VSTORE_MSG_CART_EMPTY_ERR, 'vstore');

			return false;
		}

		if(!$this->saveTransaction(null, null, $this->orderItemsFromCheckout($data), 'Q'))
		{
			return false;
		}

		$this->resetCart('quote');
		e107::getSession('vstore')->clear('_data');

		// Force the browser window to refresh the cart menu
		e107::js('footer-inline', '$(function(){ vstoreCartRefresh(); });');

		return null;
	}

	/**
	 * Mollie payment notification: updates the order of a payment that was still pending when the customer came back.
	 *
	 * @return void
	 */
	public function handleMollieWebhook()
	{
		$result = $this->syncMolliePayment((string) varset($_POST['id']));

		if($result['state'] === 'error')
		{
			// An error status makes Mollie send the notification again
			http_response_code(500);
		}
	}

	/**
	 * Ask Mollie for the real status of a payment and update the order that waits for it.
	 * The id is never trusted: the status always comes from the Mollie API with the shop's own key.
	 *
	 * @param string $paymentId Mollie payment id (tr_...)
	 * @return array state: invalid|error|no_order|unchanged|updated, status: Mollie status, outcome: MolliePayment outcome
	 */
	public function syncMolliePayment($paymentId)
	{
		$result = array('state' => 'invalid', 'status' => '', 'outcome' => MolliePayment::UNKNOWN);

		if(!preg_match('/^tr_[A-Za-z0-9]{4,40}$/', $paymentId))
		{
			return $result;
		}

		list($gateway) = self::createGateway('mollie', $this->pref);
		$result['state'] = 'error';

		if(!is_object($gateway))
		{
			return $result;
		}

		try
		{
			$response = $gateway->fetchTransaction(array('transactionReference' => $paymentId))->send();
			$payment = (array) $response->getData();
		}
		catch(\Exception $e)
		{
			e107::getLog()->add('Vstore', 'Mollie payment ' . $paymentId . ': ' . $e->getMessage(), E_LOG_WARNING);

			return $result;
		}

		$status = (string) varset($payment['status']);
		$result['status'] = $status;
		$result['outcome'] = MolliePayment::outcome($status);

		if($result['outcome'] === MolliePayment::UNKNOWN)
		{
			// Mollie answered with an error document instead of a payment
			return $result;
		}

		$orderId = (new OrderRepository())->orderIdFromMollieTransaction($paymentId);

		if(!$orderId)
		{
			if($result['outcome'] === MolliePayment::PAID)
			{
				e107::getLog()->add('Vstore', 'Mollie payment ' . $paymentId . ' is paid but no order has it (the customer did not return to the shop)', E_LOG_WARNING);
			}

			$result['state'] = 'no_order';

			return $result;
		}

		$this->order->clear();
		if(!$this->order->load($orderId))
		{
			$result['state'] = 'no_order';

			return $result;
		}

		$change = MolliePayment::orderChange($status, $this->order->order_status, $this->order->order_pay_status);
		$result['state'] = 'unchanged';

		if($change === null)
		{
			return $result;
		}

		$paid = $change['outcome'] === MolliePayment::PAID;

		$this->order->order_pay_status = $change['order_pay_status'];
		$this->order->setOrderLog($paid ? LAN_VSTORE_MSG_LOG_PAYMENT_PAID : LAN_VSTORE_MSG_LOG_PAYMENT_FAILED);
		$this->order->setOrderStatus($change['order_status'], array('purchase' => $payment));

		if($paid)
		{
			self::setCustomerUserclass($this->order->order_e107_user, (array) $this->order->order_items);
		}
		elseif($change['outcome'] === MolliePayment::FAILED)
		{
			// The stock was taken when the order was created
			self::inventoryService()->restore((array) $this->order->order_items);
		}

		$result['state'] = 'updated';

		return $result;
	}

	/**
	 * The customer came back from Mollie and the order of the payment already exists: bring it up to date and say what happened.
	 *
	 * @param \Omnipay\Common\Message\ResponseInterface $response completePurchase response
	 * @return void
	 */
	private function finishMollieReturn($response)
	{
		$payment = (array) $response->getData();
		$sync = $this->syncMolliePayment((string) $response->getTransactionReference());
		$mes = e107::getMessage();

		if($sync['outcome'] === MolliePayment::FAILED)
		{
			// Keep the cart for another try
			$mes->addError($this->getMollieErrorMessage(json_encode($payment)), 'vstore');

			return;
		}

		if($sync['outcome'] === MolliePayment::PAID && in_array($sync['state'], array('updated', 'unchanged'), true))
		{
			$mes->addSuccess(LAN_VSTORE_MSG_MOLLIE_PAID, 'vstore');
			$mes->addSuccess(sprintf(LAN_VSTORE_MSG_ORDER_COMPLETE, $this->order->order_refcode), 'vstore');

			$pdf_data = $this->renderInvoice();
			if(!$this->pref['invoice_create_pdf'] && !empty($pdf_data) && !is_array($pdf_data))
			{
				$this->html_invoice = $pdf_data;
			}
		}
		else
		{
			// Waiting for the customer or for the bank: the webhook or the admin check will finish the order
			$mes->addInfo(LAN_VSTORE_MSG_MOLLIE_PENDING, 'vstore');
		}

		$this->resetCart('sale');
		e107::getSession('vstore')->clear('_data');
	}

	/**
	 * Build a order reference number out of the order_id, first- & lastname
	 *
	 * @param int $id
	 * @param string $firstname
	 * @param string $lastname
	 * @return string
	 */
	public function getOrderRef($id, $firstname, $lastname)
	{
		return self::orderService()->reference($id, $firstname, $lastname);
	}


	/**
	 * Save the transaction to the database
	 *
	 * @param string $id transaction id
	 * @param array $transData transaction data
	 * @param array $items purchased item
	 * @param string $order_status status of the new order
	 * @param string $payStatus payment status of the new order, derived from the transaction data when empty
	 * @param bool $quiet true to skip the on-screen message and the confirmation email (the customer has not paid yet)
	 * @return bool|null false when the order could not be saved
	 */
	public function saveTransaction($id, $transData, $items, $order_status = 'N', $payStatus = '', $quiet = false)
	{

		$this->html_invoice = null;

		// A quote request has no payment, invoice number, stock change or invoice
		$isQuote = Catalog::isQuoteStatus($order_status);

		if(isset($transData['L_ERRORCODE0']) && intval($transData['L_ERRORCODE0']) == 11607)
		{
			// Duplicate REquest.
			return false;
		}

		$customerData = vstore::getCustomerData();

		$split = self::orderService()->splitAdditionalFields((array) $this->pref['additional_fields'], (array) $customerData, e_LANGUAGE);
		$customerData = $split['customer'];
		$customerData['additional_fields'] = e107::serialize($split['additional'], 'json');


		if(!$this->getShippingType())
		{
			$this->setShippingData($customerData);
		}
		$shippingData = $this->getShippingData();

		$cartData = $this->getCheckoutData();

		$this->order->clear();

		$this->order->order_date = time();
		$this->order->order_session = $cartData['id'];
		$this->order->order_e107_user = USERID;
		$this->order->order_cust_id = '';
		$this->order->order_status = varset($order_status, 'N'); // New
		$this->order->order_items = $items;

		$this->order->order_use_shipping = $this->getShippingType();
		$this->order->order_billing = $customerData;
		$this->order->order_shipping = $shippingData;

		$this->order->order_pay_gateway = $isQuote ? Catalog::QUOTE_GATEWAY : $this->getGatewayType(true);
		$this->order->order_pay_status = $isQuote ? 'none' : ($payStatus !== '' ? $payStatus : (empty($transData) ? 'incomplete' : 'complete'));
		$this->order->order_pay_transid = $id;
		$this->order->order_pay_amount = $cartData['totals']['cart_grandTotal'];
		$this->order->order_pay_currency = $cartData['currency'];
		$this->order->order_pay_tax = $cartData['totals']['cart_taxTotal'];
		$this->order->order_pay_shipping = $cartData['totals']['cart_shippingTotal'];
		$this->order->order_pay_coupon_code = $cartData['totals']['cart_coupon']['code'];
		$this->order->order_pay_coupon_amount = $cartData['totals']['cart_coupon']['amount'];
		$this->order->order_pay_rawdata = array('purchase' => $transData);
		if(!$isQuote)
		{
			$this->order->setInvoiceNr();
		}
		$this->order->setOrderLog($isQuote ? LAN_VSTORE_MSG_LOG_QUOTE_CREATED : ($this->order->order_pay_status === 'complete' ? LAN_VSTORE_MSG_LOG_CREATED_PAID : ($payStatus === 'incomplete' && !empty($transData) ? LAN_VSTORE_MSG_LOG_CREATED_PENDING : LAN_VSTORE_MSG_LOG_CREATED)));

		$mes = e107::getMessage();
		$saved = true;
		if($this->order->save())
		{
			// Order saved, update inventory before doing any "secondary" work...
			if(!$isQuote)
			{
				$this->updateInventory($this->order->order_items);
			}

			if(USER && !$this->saveCustomer(
					$customerData,
					$shippingData,
					$this->getShippingType(),
					$isQuote ? '' : $this->getGatewayType(true)
				))
			{
				$mes->addError(LAN_VSTORE_MSG_CUSTOMER_FAIL, 'vstore');
			}

			// Set order ref code
			if(!$this->order->setOrderRef() || !$this->order->save())
			{
				$mes->addDebug('Unable to update order ref code!' . $this->order->getLastError(), 'vstore');
			}

			// Render the in
			$pdf_data = $isQuote ? false : $this->renderInvoice();
			$pdf_file = '';
			if($this->pref['invoice_create_pdf'] && is_array($pdf_data))
			{
				$this->invoiceToPdf($pdf_data);
				$pdf_file = $this->pathToInvoicePdf($this->order->order_invoice_nr, $pdf_data['userid']);
			}

			if($isQuote)
			{
				$mes->addSuccess(sprintf(LAN_VSTORE_MSG_QUOTE_SENT, $this->order->order_refcode), 'vstore');

				$this->order->emailCustomer('quote');
			}
			elseif(!$quiet)
			{
				$mes->addSuccess(sprintf(LAN_VSTORE_MSG_ORDER_COMPLETE, $this->order->order_refcode), 'vstore');

				$this->order->emailCustomer('default', $pdf_file);
			}

			if(!empty($transData) && $payStatus !== 'incomplete')
			{
				$this->setCustomerUserclass(USERID, $items);
			}
		}
		else
		{
			$mes->addError(LAN_VSTORE_MSG_TRANS_FAIL);
			$this->order->emailCustomer('error');
			$saved = false;
		}
		if(!$this->pref['invoice_create_pdf'] && !empty($pdf_data) && !is_array($pdf_data))
		{
			$this->html_invoice = $pdf_data;
		}

		return $saved;
	}

	private function saveCustomer($customerData, $shippingData, $use_shipping, $gateway)
	{
		return self::orderService()->saveCustomer(
			new CustomerRepository(),
			USERID,
			(array) $customerData,
			(array) $shippingData,
			$use_shipping,
			$gateway,
			time()
		);
	}

	/**
	 * Add userclass to customer
	 *
	 * @param int $userid Userid of the customer
	 * @param array $items Array of order_items
	 * @return void
	 */
	public static function setCustomerUserclass($userid, $items)
	{
		$classes = self::userclassService()->classesFor($items, e107::pref('vstore', 'customer_userclass'));

		if($classes)
		{
			$usr = e107::getSystemUser($userid);
			foreach($classes as $class)
			{
				$usr->addClass($class);
			}
		}
	}

	/**
	 * Return the userclasses that will be added to customer
	 *
	 * @param array $items array of order_items
	 * @return bool/string false, if no userclass, otherwise comma-separated list of userclasses
	 */
	public static function getCustomerUserclass($items)
	{
		$classes = self::userclassService()->classesFor($items, e107::pref('vstore', 'customer_userclass'));

		return $classes ? implode(',', $classes) : false;
	}

	/**
	 * Update the items inventory based on the given json string
	 *
	 * @param string|array $json json formatted string or array containing the data
	 * @return void
	 */
	private function updateInventory($json)
	{
		$rows = is_array($json) ? $json : e107::unserialize($json);

		self::inventoryService()->reduce(is_array($rows) ? $rows : array());
	}
/*
	public static function getGateways()
	{
		return self::$gateways;
	}*/

	/**
	 * Return the icon for the given gateway
	 *
	 * @param string $type gateway name
	 * @param string $size default 5x (2x, 3x, 4x, 5x)
	 * @return string
	 */
	public static function getGatewayIcon($type = '', $size = '5x')
	{

		if(self::isMollie($type))
		{
			return self::getMolliePaymentMethodIcon($type, $size);
		}

		if($type === Catalog::QUOTE_GATEWAY)
		{
			return e107::getParser()->toGlyph('fa-file-text-o', array('size' => $size));
		}

		$text = !empty(self::$gateways[$type]) ? self::$gateways[$type]['icon'] : '';

		return e107::getParser()->toIcon($text, array('size' => $size, 'fw'=>true));
	}

	/**
	 * Return the title/name of the given gateway
	 *
	 * @param string $type
	 * @return string
	 */
	public static function getGatewayTitle($type)
	{

		if(self::isMollie($type))
		{
			return self::getMolliePaymentMethodTitle($type);
		}

		if($type === Catalog::QUOTE_GATEWAY)
		{
			return LAN_VSTORE_QUOTE_TITLE;
		}

		return self::$gateways[$type]['title'];
	}


	/**
	 * Return the type of the current gateway
	 *
	 * @param string $type
	 * @return string
	 */
	private function getGatewayType($forceSession = false)
	{

		if(!$gateways = e107::getSession('vstore')->get('gateway'))
		{
			trigger_error("No gateway selected!");
		}

		if(isset($gateways['type']) || $forceSession)
		{
			return $gateways['type'];
		}

		return e107::getDb()->retrieve('vstore_customer', 'cust_gateway', 'cust_e107_user=' . USERID);
	}


	/**
	 * Set the type of the current gateway
	 *
	 * @param string $type
	 * @return void
	 */
	public function setGatewayType($type = '')
	{

		e107::getSession('vstore')->set('gateway/type', strtolower($type));
		//$_SESSION['vstore']['gateway']['type'] = $type;
	}


	/**
	 * Set the number of items per page
	 *
	 * @param int $num
	 * @return void
	 */
	public function setPerPage($num)
	{

		$this->perPage = intval($num);
	}

	/**
	 * Update the cart
	 *
	 * @param string $type (modify, remove)
	 * @param array $array of the ids and item used for modify or remove
	 * @return void
	 */
	protected function updateCart($type = 'modify', $array = array())
	{
		$service = self::cartService();

		if($type == 'modify')
		{
			$results = $service->updateQuantities($this->cartId, (array) $array, function ($vars)
			{
				$prop = vstore::getItemVarProperties($vars, 0);

				return $prop ? $prop['variation'] : '';
			});

			foreach($results as $result)
			{
				self::addResultMessage($result);
			}
		}

		if($type == 'remove')
		{
			$service->remove($this->cartId, (array) $array);
		}

		return null;
	}


	/**
	 * Reset the cart
	 * Remove all items from the cart, or only the lines of one kind once their order is saved
	 *
	 * @param string|null $kind 'sale' or 'quote' keeps the lines of the other kind, null empties the cart
	 * @return void
	 */
	protected function resetCart($kind = null)
	{

		// Delete the cart of this visitor from the database
		$sql = e107::getDb();

		if($kind !== null && !empty($this->cartId))
		{
			foreach((array) $this->getCartData() as $row)
			{
				if((Catalog::isQuotePrice($row['item_price']) ? 'quote' : 'sale') === $kind)
				{
					$sql->delete('vstore_cart', 'cart_id=' . (int) $row['cart_id']);
				}
			}

			if($this->getCartData())
			{
				return null;
			}
		}

		if(!empty($this->cartId))
		{
			$sql->delete('vstore_cart', 'cart_session = "' . $sql->escape($this->cartId) . '"');
		}
		$_COOKIE["cartId"] = false;
		cookie("cartId", null, time() - 3600);
		$this->cartId = null;
		e107::getDebug()->log("Destroying CartID");

		return null;
	}


	/**
	 * Return the current cart id
	 *
	 * @return string
	 */
	protected function getCartId()
	{

		// The id comes from a cookie: only accept what this class generates
		if(!empty($_COOKIE["cartId"]) && preg_match('/^[A-Za-z0-9_,-]{8,128}$/', $_COOKIE["cartId"]))
		{
			return $_COOKIE["cartId"];
		}
		else // There is no cookie set. We will set the cookie and return the value of the users session ID
		{
			e107::getDebug()->log("Renewing CartID");
			$value = md5(session_id() . time());

			cookie("cartId", $value, time() + ((3600 * 24) * 2));

			return $value;
		}
	}

	/**
	 * Manually set the card id. - Mostly for unit testing.
	 * @param $id
	 */
	public function setCartId($id)
	{
		$this->cartId = $id;
	}

	/**
	 * Category bar for the catalog pages (shown under the breadcrumb): "All" plus the top level categories,
	 * with the one being browsed highlighted.
	 *
	 * @return string empty when there are fewer than two categories to choose from
	 */
	public function categoryBar()
	{
		if(!class_exists('vstore_sitelink'))
		{
			include_once e_PLUGIN . 'vstore/e_sitelink.php';
		}

		$nav = (new vstore_sitelink())->storeCategoryNav();

		if(count($nav) < 2)
		{
			return '';
		}

		$current = 0;

		if(!empty($this->get['item']) && !empty($this->item['item_cat']))
		{
			$current = (int) $this->item['item_cat'];
		}
		elseif(!empty($this->get['cat']))
		{
			$current = (int) $this->get['cat'];
		}

		if($current && !empty($this->categories[$current]['cat_parent']))
		{
			$current = (int) $this->categories[$current]['cat_parent'];
		}

		array_unshift($nav, array(
			'link_id'    => 0,
			'link_name'  => LAN_VSTORE_FR_ALL_CATEGORIES,
			'link_url'   => e107::url('vstore', 'index'),
			'link_badge' => array_sum(array_column($nav, 'link_badge')),
		));

		$tp = e107::getParser();
		$template = e107::getTemplate('vstore', 'vstore_menu', 'bar');

		$text = $tp->parseTemplate($template['start'], true, $this->sc);

		foreach($nav as $item)
		{
			$item['link_active'] = ($item['link_id'] === $current);
			$this->sc->setVars($item);
			$text .= $tp->parseTemplate($template['item'], true, $this->sc);
		}

		return $text . $tp->parseTemplate($template['end'], true, $this->sc);
	}

	/**
	 * Render the list of categories
	 *
	 * @param integer $parent 0 = root categories
	 * @param boolean $np true = render nextprev control; false = dont't render nextprev
	 * @return string
	 */
	public function categoryList($parent = 0, $np = false)
	{

		$this->from = max(0, (int) varset($this->get['frm'], 0));
		$parent = (int) $parent;

		$query = 'SELECT *
        FROM #vstore_cat
        WHERE cat_class IN (' . USERCLASS_LIST . ') AND cat_parent = ' . $parent . '
        ORDER BY cat_order
        LIMIT ' . $this->from . "," . $this->perPage;
		if((!$data = e107::getDb()->retrieve($query, true)) && intval($parent) == 0)
		{
			return e107::getMessage()->addInfo(LAN_VSTORE_MSG_NO_CATEGORIES, 'vstore')->render('vstore');
		}
		elseif(!$data)
		{
			return '';
		}

		$tp = e107::getParser();

		$template = e107::getTemplate('vstore', 'vstore', 'cat');

		$text = $tp->parseTemplate($template['start'], true, $this->sc);

		$this->sc->setCategories($this->categories);

		foreach($data as $row)
		{
			$this->sc->setVars($row);
			$text .= $tp->parseTemplate($template['item'], true, $this->sc);
		}

		$text .= $tp->parseTemplate($template['end'], true, $this->sc);

		if($np === true)
		{
			$nextprev = array(
				'tmpl'    => 'bootstrap',
				'total'   => $this->categoriesTotal,
				'amount'  => intval($this->perPage),
				'current' => $this->from,
				'url'     => e107::url('vstore', 'base', null, array(
					'query' => array('frm' => '--FROM--')
				))
			);

			global $nextprev_parms;

			$nextprev_parms = http_build_query($nextprev, false);

			$text .= $tp->parseTemplate("{NEXTPREV: " . $nextprev_parms . "}");
		}

		return $text;
	}


	/**
	 * Render the list of products
	 *
	 * @param integer $category selected category id, 0 for all categories (latest products first)
	 * @param boolean $np render nextpref yes/no
	 * @param string $templateID name of the template to use
	 * @param int $item_count number of items to show (used for menu)
	 * @return string
	 */
	public function productList($category = 1, $np = false, $templateID = 'list', $item_count = null)
	{

		if(!empty($item_count))
		{
			$this->from = 0;
			$this->perPage = $item_count;
		}

		// Check if out-of-stock products should be displayed or not (default: show)
		$hide_outofstock = '';
		if(!varset($this->pref['show_outofstock'], true))
		{
			$hide_outofstock = ' AND item_inventory != 0';
		}

		$category = (int) $category;
		$catFilter = $category > 0 ? ' AND item_cat = ' . $category : '';
		$order = $category > 0 ? 'item_order' : 'item_id DESC';

		if(!$data = e107::getDb()->retrieve(
			'SELECT SQL_CALC_FOUND_ROWS *, cat_class
            FROM #vstore_items
            LEFT JOIN #vstore_cat ON (item_cat = cat_id)
            WHERE cat_class IN (' . USERCLASS_LIST . ') AND item_active=1' . $catFilter .
			$hide_outofstock . '
            ORDER BY ' . $order . ' LIMIT ' . $this->from . ',' . $this->perPage,
			true
		))
		{
			return e107::getMessage()->addInfo(LAN_VSTORE_MSG_NO_PRODUCTS, 'vstore')->render('vstore');
		}

		$count = e107::getDb()->foundRows();

		$categoryRow = $category > 0 ? $this->categories[$category] : array();

		$tp = e107::getParser();
		$this->sc->setVars($categoryRow);
		$template = e107::getTemplate('vstore', 'vstore', $templateID);

		// e107::getDebug()->log($this->sc);

		$text = $tp->parseTemplate($template['start'], true, $this->sc);

		foreach($data as $row)
		{
			$id = $row['item_cat'];
			$row['cat_id'] = $row['item_cat'];
			$row['cat_sef'] = $this->categories[$id]['cat_sef'];
			$row['item_sef'] = eHelper::title2sef($row['item_name'], 'dashl');

			$this->sc->setVars($row);
			$text .= $tp->parseTemplate($template['item'], true, $this->sc);
		}

		$text .= $tp->parseTemplate($template['end'], true, $this->sc);

		if($np === true)
		{
			// Check if SEF urls are deactivated for vstore
			$sefActive = e107::getPref('e_url_list');

			$nextprev = array(
				'tmpl'    => 'bootstrap',
				'total'   => $count,
				'amount'  => intval($this->perPage),
				'current' => $this->from,
				'url'     => e107::url('vstore', 'category', $row, array(
					'legacy' => empty($sefActive['vstore']),
					'query'  => array('frm' => '--FROM--')
				))
			);

			global $nextprev_parms;

			$nextprev_parms = http_build_query($nextprev, false);

			$text .= $tp->parseTemplate("{NEXTPREV: " . $nextprev_parms . "}");
		}


		return $text;
	}


	/**
	 * Product showcase for the menu areas (vstore_showcase_menu.php): product cards of the catalog list,
	 * optionally with category pills that switch between one panel of cards per category.
	 *
	 * @param array $o options, see Vstore\Support\ShowcaseOptions::normalize()
	 * @return string empty when there is nothing to show
	 */
	public function showcase(array $o)
	{
		static $instance = 0;

		$tp = e107::getParser();
		$root = (int) $o['category'];

		if($root > 0 && empty($this->categories[$root]))
		{
			$root = 0;
		}

		$cardTemplate = e107::getTemplate('vstore', 'vstore', 'list');
		$tpl = e107::getTemplate('vstore', 'vstore_menu', 'showcase');

		if(empty($tpl['panel_start']) || empty($cardTemplate['item']))
		{
			$VSTORE_MENU_TEMPLATE = array();
			include e_PLUGIN . 'vstore/templates/vstore_menu_template.php';
			$tpl = $VSTORE_MENU_TEMPLATE['showcase'];
		}

		$all = $this->showcaseCards($this->showcaseCategoryIds($root), $o, $root === 0, $cardTemplate['item']);

		if($all['html'] === '')
		{
			return '';
		}

		$groups = array();

		if($o['nav'] !== 'none')
		{
			if(!class_exists('vstore_sitelink'))
			{
				include_once e_PLUGIN . 'vstore/e_sitelink.php';
			}

			$groups = (new vstore_sitelink())->storeCategoryNav($root);
		}

		$parse = function ($html, $vars = array()) use ($tp)
		{
			$map = array();

			foreach($vars as $k => $v)
			{
				$map['[' . $k . ']'] = $v;
			}

			return $tp->parseTemplate(strtr($html, $map), true, $this->sc);
		};

		$filter = ($o['nav'] === 'pills');
		$text = $parse($tpl['start'], array('id' => 'vstore-showcase-' . (++$instance), 'cols' => $o['columns'] ?: 'auto'));

		if(count($groups) > 1)
		{
			$text .= $parse($tpl['nav_start']);
			$pills = array_merge(array(array(
				'link_id'    => 0,
				'link_name'  => LAN_VSTORE_FR_ALL_CATEGORIES,
				'link_url'   => $root ? e107::url('vstore', 'category', $this->categories[$root]) : e107::url('vstore', 'index'),
				'link_badge' => $all['total'],
			)), $groups);

			foreach($pills as $pill)
			{
				$text .= $parse($tpl['nav_item'], array(
					'url'    => $pill['link_url'],
					'name'   => $pill['link_name'],
					'count'  => (int) $pill['link_badge'],
					'active' => $pill['link_id'] === 0 ? ' active' : '',
					'attr'   => $filter ? ' data-vs-cat="' . (int) $pill['link_id'] . '"' : '',
				));
			}

			$text .= $parse($tpl['nav_end']);
		}
		else
		{
			$groups = array();
			$filter = false;
		}

		$text .= $parse($tpl['panel_start'], array('id' => 0, 'hidden' => '')) . $all['html'] . $parse($tpl['panel_end']);

		if($filter)
		{
			foreach($groups as $group)
			{
				$cards = $this->showcaseCards($this->showcaseCategoryIds((int) $group['link_id']), $o, false, $cardTemplate['item']);

				$text .= $parse($tpl['panel_start'], array('id' => (int) $group['link_id'], 'hidden' => ' hidden'))
					. $cards['html'] . $parse($tpl['panel_end']);
			}
		}

		return $text . $parse($tpl['end']);
	}


	/**
	 * A category and its subcategories.
	 *
	 * @param int $catId 0 = every category (no filter)
	 * @return int[]
	 */
	private function showcaseCategoryIds($catId)
	{
		if($catId < 1)
		{
			return array();
		}

		$ids = array($catId);

		foreach($this->categories as $id => $cat)
		{
			if((int) $cat['cat_parent'] === $catId)
			{
				$ids[] = (int) $id;
			}
		}

		return $ids;
	}


	/**
	 * Product cards of one showcase panel.
	 *
	 * @param int[] $catIds empty = every category
	 * @param array $o showcase options
	 * @param bool $allCategories true when the panel is not limited to one category (changes the automatic order)
	 * @param string $itemTemplate card template
	 * @return array html and total (all matching products, not only the cards shown)
	 */
	private function showcaseCards(array $catIds, array $o, $allCategories, $itemTemplate)
	{
		$sql = e107::getDb();
		$tp = e107::getParser();

		$filter = $catIds ? ' AND i.item_cat IN (' . implode(',', array_map('intval', $catIds)) . ')' : '';
		$stock = varset($this->pref['show_outofstock'], true) ? '' : ' AND i.item_inventory != 0';

		$rows = $sql->retrieve(
			'SELECT SQL_CALC_FOUND_ROWS i.*, c.cat_class
			FROM #vstore_items i
			LEFT JOIN #vstore_cat c ON (i.item_cat = c.cat_id)
			WHERE c.cat_class IN (' . USERCLASS_LIST . ') AND i.item_active = 1' . $filter . $stock . '
			ORDER BY ' . Vstore\Support\ShowcaseOptions::orderSql($o['order'], $allCategories) . '
			LIMIT ' . (int) $o['limit'],
			true
		);

		if(empty($rows))
		{
			return array('html' => '', 'total' => 0);
		}

		$total = (int) $sql->foundRows();
		$html = '';

		foreach($rows as $row)
		{
			$row['cat_id'] = $row['item_cat'];
			$row['cat_sef'] = varset($this->categories[$row['item_cat']]['cat_sef']);
			$row['item_sef'] = eHelper::title2sef($row['item_name'], 'dashl');

			$this->sc->setVars($row);
			$html .= $tp->parseTemplate($itemTemplate, true, $this->sc);
		}

		return array('html' => $html, 'total' => $total);
	}


	/**
	 * Render a single product/item
	 *
	 * @param integer $id item_id
	 * @return string
	 */
	protected function productView($id = 0)
	{

		if(!$row = e107::getDb()->retrieve(
			'SELECT * FROM #vstore_items WHERE item_active=1 AND item_id = ' . intval($id) . '  LIMIT 1',
			true
		))
		{
			e107::getMessage()->addInfo(LAN_VSTORE_MSG_NO_PRODUCTS, 'vstore');

			return null;
		}

		$this->item = $row[0];

		$tp = e107::getParser();
		$frm = e107::getForm();

		$catid = $this->item['item_cat'];
		$data = array_merge($row[0], $this->categories[$catid]);

		// print_a($data);

		$this->sc->setVars($data);
		$this->sc->wrapper('vstore/item');

		$tmpl = e107::getTemplate('vstore');


		$text = $tmpl['item']['main'];

		$tabData = array();

		if(!empty($data['item_details']))
		{
			$tabData['details'] = array('caption' => LAN_VSTORE_MSG_TAB_DETAILS, 'text' => $tmpl['item']['details']);
		}

		if($media = e107::unserialize($data['item_pic']))
		{
			foreach($media as $v)
			{
				if($tp->isVideo($v['path']))
				{
					$tabData['videos'] = array('caption' => LAN_VSTORE_MSG_TAB_VIDEOS, 'text' => $tmpl['item']['videos']);
					break;
				}
			}
		}

		if(!empty($data['item_reviews']))
		{
			$tabData['reviews'] = array('caption' => LAN_VSTORE_MSG_TAB_REVIEWS, 'text' => $tmpl['item']['reviews']);
		}


		if(!empty($data['item_related']))
		{
			$tmp = e107::unserialize($data['item_related']);
			if(!empty($tmp['src']))
			{
				$tabData['related'] = array(
					'caption' => varset($tmp['caption'], 'Related'),
					'text'    => $tmpl['item']['related']
				);
			}
		}

		if(!empty($data['item_files']))
		{
			$tmp = e107::unserialize($data['item_files']);
			if(!empty($tmp[0]['path']))
			{
				$tabData['files'] = array('caption' => LAN_VSTORE_MSG_TAB_FILES, 'text' => $tmpl['item']['files']);
			}
		}

		if(!empty($this->pref['howtoorder']))
		{
			$tabData['howto'] = array('caption' => LAN_VSTORE_MSG_TAB_HOWTO, 'text' => $tmpl['item']['howto']);
		}

		if(!empty($tabData))
		{
			$text .= $frm->tabs($tabData);
		}

		return $tp->parseTemplate($text, true, $this->sc);

	}


	/**
	 * Add an item to the cart
	 * if the item is already on the list the quantity is increased, never above the stock
	 *
	 * @param int $id item_id
	 * @param array $itemvars array of item variations
	 * @param int $qty quantity to add (at least 1)
	 * @return bool true on success
	 */
	public function addToCart($id, $itemvars = false, $qty = 1)
	{
		$result = self::cartService()->add($this->cartId, USERID, $id, $itemvars, $qty);

		if(!$result->isOk())
		{
			e107::getMessage()->addWarning(ResultTranslator::text($result), 'vstore');
		}

		return $result->isOk();
	}

	/**
	 * Format the item variation array for use in the db field
	 *
	 * @param array $itemvarsarray
	 * @return string
	 */
	public static function item_vars_toDB($itemvarsarray)
	{
		return ItemVars::toDb($itemvarsarray);
	}

	/**
	 * Format the item variation string to an array
	 *
	 * @param string $itemvarsstring
	 * @return array
	 */
	public static function item_vars_toArray($itemvarsstring)
	{
		return ItemVars::toArray($itemvarsstring);
	}

	/**
	 * Get the current inventory of the given item / itemvars combination
	 *
	 * @param int $itemid
	 * @param array/boolean $itemvars
	 * @return int
	 */
	public function getItemInventory($itemid, $itemvars = false)
	{
		return self::inventoryService()->available($itemid, $itemvars);
	}

	/**
	 * Fetch the cart data
	 *
	 * @return array
	 */
	public function getCartData()
	{

		return e107::getDb()->retrieve('SELECT c.*, i.*, cat.cat_name, cat.cat_sef
        FROM `#vstore_cart` AS c
        LEFT JOIN `#vstore_items` as i ON (c.cart_item = i.item_id)
        LEFT JOIN `#vstore_cat` as cat ON (i.item_cat = cat.cat_id)
        WHERE c.cart_session = "' . e107::getDb()->escape($this->cartId) . '" AND c.cart_status ="" ', true);
	}


	/**
	 * Split the cart lines into the kinds that exist: products for purchase and quote products.
	 *
	 * @param array $data cart lines (getCartData())
	 * @return string[] 'sale' and/or 'quote'
	 */
	private function cartKinds(array $data)
	{
		$kinds = array();

		foreach($data as $row)
		{
			$kinds[Catalog::isQuotePrice($row['item_price']) ? 'quote' : 'sale'] = true;
		}

		return array_values(array_intersect(array('sale', 'quote'), array_keys($kinds)));
	}

	/**
	 * Price one kind of cart lines. In a cart with both kinds the totals carry cart_scoped
	 * so the checkout button posts the kind that has to be processed.
	 *
	 * @param array  $data  cart lines
	 * @param string $kind  'sale' or 'quote'
	 * @param bool   $mixed true when the cart has both kinds
	 * @return array|null checkout data, null when there is nothing of that kind
	 */
	private function checkoutDataFor(array $data, $kind, $mixed)
	{
		$checkout = $this->prepareCheckoutData($data, false, true, $kind);

		if(!is_array($checkout))
		{
			return null;
		}

		$checkout['totals']['cart_scoped'] = $mixed;

		return $checkout;
	}

	/**
	 * Start the checkout of the purchase lines or of the quote lines of the cart and go to the checkout page.
	 *
	 * @param string $kind 'sale' or 'quote'
	 * @return void
	 */
	private function startCheckout($kind)
	{
		$data = $this->getCartData();

		if(!$data)
		{
			return;
		}

		$checkout = $this->checkoutDataFor($data, $kind, false);

		if($checkout)
		{
			$this->setCheckoutData($checkout);
			e107::redirect(e107::url('vstore', 'checkout'));
			exit;
		}
	}

	/**
	 * Render the cart
	 *
	 * @return string
	 */
	protected function cartView()
	{

		if(!$data = $this->getCartData())
		{
			return e107::getMessage()->addInfo(LAN_VSTORE_FR_CART_EMPTY, 'vstore')->render('vstore');
		}

		$kinds = $this->cartKinds($data);
		$mixed = count($kinds) > 1;
		$sections = array();

		foreach($kinds as $kind)
		{
			if($checkout = $this->checkoutDataFor($data, $kind, $mixed))
			{
				$sections[$kind] = $checkout;
			}
		}

		if(!$sections)
		{
			return e107::getMessage()->addInfo(LAN_VSTORE_FR_CART_EMPTY, 'vstore')->render('vstore');
		}

		$tp = e107::getParser();
		$frm = e107::getForm();
		$template = e107::getTemplate('vstore', 'vstore_cart');

		$text = '<div class="vstore vstore-cart-page">' . $frm->open('cart', 'post', e107::url('vstore', 'cart'));

		$text .= e107::getMessage()->render('vstore');

		$text .= '<div class="row">
                <div class="col-sm-12 col-md-12">';

		foreach($sections as $kind => $checkoutData)
		{
			// A quote request uses the quote_* parts of the template (no prices); a theme without them keeps the standard ones
			$isQuote = ($kind === 'quote');
			$part = function($key) use ($template, $isQuote)
			{
				return ($isQuote && isset($template['quote_' . $key])) ? $template['quote_' . $key] : $template[$key];
			};

			if($mixed)
			{
				$text .= '<h4 class="vstore-cart-section vstore-cart-section-' . $kind . '">' . ($isQuote ? LAN_VSTORE_FR_CART_QUOTE_LINES : LAN_VSTORE_FR_CART_SALE_LINES) . '</h4>';
			}

			$text .= $tp->parseTemplate($part('start'), true, $this->sc);

			foreach($checkoutData['items'] as $row)
			{
				$this->sc->setVars($row);
				$text .= $tp->parseTemplate($part('item'), true, $this->sc);
			}

			$this->sc->setVars($checkoutData['totals']);

			$text .= $tp->parseTemplate($part('end'), true, $this->sc);

			if($mixed && $kind === 'sale')
			{
				$text .= '<hr />';
			}
		}

		$text .= '</div></div>';

		$text .= $frm->close() . '</div>';

		// A direct visit to the checkout page uses the purchase lines, or the quote lines when there is nothing to buy
		$this->setCheckoutData(isset($sections['sale']) ? $sections['sale'] : $sections['quote']);

		return $text;
	}


	/**
	 * Prepare the checkout data
	 * calc item price, coupon reduction, tax, totals
	 *
	 * @param array $data item list or the checkoutdata array
	 * @param boolean $isCheckoutData true if $data is of type checkout data
	 * @param boolean $fromSitelink true when called for the cart menu (an empty cart returns null)
	 * @param string|null $only 'sale' or 'quote' keeps only that kind of lines, null keeps all of them
	 * @return array
	 */
	public function prepareCheckoutData($data, $isCheckoutData = false, $fromSitelink = false, $only = null)
	{
		$sql = e107::getDb();
		$cust = (array) vstore::getCustomerData();
		$vatId = trim((string) varset($cust['vat_id']));

		$taxCountry = varset($this->pref['tax_business_country']);

		$country = !empty($cust['country']) ? $cust['country'] : $taxCountry;

		$isLocal = (strtoupper($country) === strtoupper($taxCountry));

		// A posted (even empty) coupon field replaces the saved coupon, otherwise the saved one is reused
		$couponCode = '';
		$hasCoupon = false;
		$posted = isset($this->post['cart_coupon_code']) ? trim($this->post['cart_coupon_code']) : null;

		if($only === 'quote')
		{
			// Quote lines take no coupon
			$couponCode = '';
		}
		elseif(!$isCheckoutData && !empty($posted))
		{
			$this->post['cart_coupon_code'] = $posted;
			$couponCode = $posted;
		}
		elseif($isCheckoutData || $posted === null)
		{
			if($isCheckoutData)
			{
				$couponCode = trim($data['coupon']['code']);
			}
			else
			{
				$chk = $this->getCheckoutData();
				$couponCode = !empty($chk['coupon']['code']) ? trim($chk['coupon']['code']) : '';
				unset($chk);
			}
		}

		$coupon = null;
		if($couponCode !== '')
		{
			$hasCoupon = true;
			$coupon = (new CouponRepository())->findByCode($couponCode);

			if(deftrue('e_DEBUG_VSTORE'))
			{
				e107::getDebug()->log('[VSTORE_COUPON] Retrieved: ' . print_r($coupon, true));
			}
		}

		if(!$coupon && $hasCoupon)
		{
			e107::getMessage()->addError(LAN_VSTORE_MSG_COUPON_INVALID, 'vstore');
		}

		$items = $isCheckoutData ? $data['items'] : $data;
		$id = ($isCheckoutData ? $data['id'] : $this->getCartId());
		unset($data);

		// Drop inactive items and add the price of the selected variations
		$rows = array();
		foreach($items as $row)
		{
			if(!$this->isItemActive($row['cart_item']))
			{
				static $warned = array();
				if(!isset($warned[$row['cart_id']]))
				{
					$warned[$row['cart_id']] = true;
					e107::getMessage()->addWarning(sprintf(LAN_VSTORE_MSG_ITEM_REMOVED, $row['item_name']), 'vstore');
				}
				$sql->delete('vstore_cart', 'cart_id=' . (int) $row['cart_id'] . ' AND cart_item=' . (int) $row['cart_item']);
				continue;
			}

			$row['itemvarstring'] = '';

			// A product without a price is a quote request line: no price, shipping or tax (checkout rows keep the flag)
			if(!isset($row['is_quote']))
			{
				$row['is_quote'] = Catalog::isQuotePrice($row['item_price']);
			}

			if($row['is_quote'])
			{
				$row['item_price'] = 0;
				$row['item_shipping'] = 0;
			}

			if(!empty($row['cart_item_vars']))
			{
				$varinfo = self::getItemVarProperties($row['cart_item_vars'], $row['item_price']);
				if($varinfo)
				{
					if(!$isCheckoutData && !$row['is_quote'])
					{
						$row['item_price'] += $varinfo['price'];
					}
					$row['itemvarstring'] = $varinfo['variation'];
				}
			}

			$rows[] = $row;
		}

		// Purchase and quote lines share the cart: the checkout works with one kind at a time
		if($only !== null)
		{
			$wantQuote = ($only === 'quote');

			$rows = array_values(array_filter($rows, function($row) use ($wantQuote)
			{
				return !empty($row['is_quote']) === $wantQuote;
			}));
		}

		// Only a cart made of quote lines only is a quote request
		$isQuote = !empty($rows);
		foreach($rows as $row)
		{
			if(empty($row['is_quote']))
			{
				$isQuote = false;
				break;
			}
		}

		if(count($rows) == 0)
		{
			return ($fromSitelink
				? null
				: e107::getMessage()->addInfo(LAN_VSTORE_FR_CART_EMPTY, 'vstore')->render('vstore'));
		}

		$session = e107::getSession('vstore')->get('_data');
		$pref = $this->pref;
		if(isset($pref['tax_classes']) && !is_array($pref['tax_classes']))
		{
			$pref['tax_classes'] = e107::unserialize($pref['tax_classes']);
		}

		$custCountry = !empty($cust['country']) ? $cust['country'] : varset($session['customer_country'], '');

		if($isQuote)
		{
			// No coupon, shipping, tax or VAT lookup for a quote request
			$coupon = null;
			$vatId = '';
			$pref['shipping'] = 0;
		}

		$calc = self::checkoutService()->calculate($rows, $coupon ?: null, array(
			'country'     => $custCountry,
			'vat_id'      => $vatId,
			// The net view is only for sales without tax: a VAT ID of another EU country that VIES accepts
			'is_business' => !$isQuote && self::taxService()->isReverseCharge($custCountry, $pref, $vatId),
			'is_local'    => $isLocal,
			'user_id'     => USERID,
		), $pref);

		foreach($calc['messages'] as $message)
		{
			self::addResultMessage($message);
		}

		$calc['totals']['is_quote'] = $isQuote;

		return array(
			'id'       => $id,
			'is_quote' => $isQuote,
			'coupon'   => $calc['totals']['cart_coupon'],
			'items'    => $calc['items'],
			'totals'   => $calc['totals'],
		);
	}

	/**
	 * Store checkout data in session variable
	 *
	 * @param array $data data to store in session
	 * @return void
	 */
	public function setCheckoutData($data = array())
	{

		$ret = $data;
		$ret['currency'] = $this->currency;

		e107::getSession('vstore')->set('checkout', $ret);
	}


	/**
	 * Store shipping data in session variable
	 *
	 * @param array $data data to store
	 * @return void
	 */
	private function setShippingData($data = array())
	{

		$fields = self::getShippingFields();
		$ret = array();
		foreach($fields as $fld)
		{
			if(!isset($data[$fld]))
			{
				continue;
			}

			$ret[$fld] = trim(strip_tags($data[$fld]));
		}

		e107::getSession('vstore')->set('shipping', $ret);
	}

	/**
	 * Return the shipping data from the session variable
	 *
	 * @return array
	 */
	private function getShippingData($forceSession = false)
	{
		$shipping = e107::getSession('vstore')->get('shipping');
		if(!empty($shipping) || $forceSession)
		{
			return $shipping;
		}

		return e107::unserialize(e107::getDb()->retrieve(
			'vstore_customer',
			'cust_shipping',
			'cust_e107_user=' . USERID
		));
	}


	/**
	 * Set the selected shipping option
	 *
	 * @param int 1 = use shipping address; 0 = use customer address
	 * @return void
	 */
	private function setShippingType($type)
	{
		e107::getSession('vstore')->set('shipping_type', (vartrue($type) ? 1 : 0));
	//	$_SESSION['vstore']['shipping_type'] = (vartrue($type) ? 1 : 0);
	}


	/**
	 * Return the selected shipping option
	 *
	 * @return int 1 = use shipping address; 0 = use customer address
	 */
	private function getShippingType()
	{
		$type = e107::getSession('vstore')->get('shipping_type');
		return ($type ? 1 : 0);
	}


	/**
	 * Store customer data in session variable
	 *
	 * @param array $data data to store
	 * @return void
	 */
	public function setCustomerData($data = array())
	{

		$fields = self::getCustomerFields();
		$ret = array();
		foreach($fields as $fld)
		{
			if(!isset($data[$fld]))
			{
				continue;
			}

			$ret[$fld] = trim(strip_tags($data[$fld]));
		}

		e107::getSession('vstore')->set('customer', $ret);
	}


	/**
	 * Return the customer data from the database if session is empty
	 *
	 * @param bool $forceSession
	 * @return array
	 */
	public static function getCustomerData($forceSession = false)
	{

		$customer = e107::getSession('vstore')->get('customer');

		if(!empty($customer) || $forceSession)
		{
			return $customer;
		}
		$row = e107::getDb()->retrieve('vstore_customer', '*', 'cust_e107_user=' . USERID);
		$result = false;
		if($row)
		{
			$result = array();
			foreach($row as $k => $v)
			{
				$result[substr($k, 5)] = $v;
			}
		}

		return $result;
	}


	/**
	 * Return the checkoutdata from the session variable
	 *
	 * @param int $id
	 * @return array
	 */
	public function getCheckoutData($id = null)
	{
		$tmp = e107::getSession('vstore')->get('checkout');

		if(!empty($id))
		{
			return $tmp[$id];
		}

		return $tmp;
	}

	/**
	 * Resolve the path stored for a downloadable item ({e_MEDIA_FILE}... or a plain path)
	 * to a readable file in a directory e107 allows to serve.
	 *
	 * @param string $path
	 * @return string|false canonical path or false when the file cannot be served
	 */
	public static function resolveDownloadPath($path)
	{
		if(!is_string($path) || $path === '')
		{
			return false;
		}

		$file = e107::getFile()->resolveSendPath(e107::getParser()->replaceConstants($path));

		return ($file !== false && is_file($file) && is_readable($file)) ? $file : false;
	}

	/**
	 * Process a download request of a downloadable item
	 *
	 * @param int $item_id
	 * @return bool false on error
	 */
	private function downloadFile($item_id = null)
	{

		if($item_id == null || intval($item_id) <= 0)
		{
			e107::getMessage()->addDebug('Download id "' . intval($item_id) .
				'" to download missing or invalid!', 'vstore');

			return false;
		}

		if(USERID === 0)
		{
			return false;
		}

		if(!$this->hasItemPurchased($item_id))
		{
			return false;
		}

		$filepath = e107::getDb()->retrieve('vstore_items', 'item_download', 'item_id=' . intval($item_id));

		if(varset($filepath))
		{
			if(self::resolveDownloadPath($filepath) === false)
			{
				e107::getMessage()->addError(sprintf(LAN_VSTORE_MSG_DL_NOT_FOUND, basename($filepath)), 'vstore');

				return false;
			}

			e107::getFile()->send($filepath);

			return true;
		}
		else
		{
			e107::getMessage()->addError(
				sprintf(LAN_VSTORE_MSG_DL_NO_FILE, intval($item_id)),
				'vstore'
			);

			return false;
		}
	}

	/**
	 * Check if the current user has purchased (and payed) given item_id
	 *
	 * @param int $item_id
	 * @return boolean
	 */
	private function hasItemPurchased($item_id)
	{

		if($item_id == null || intval($item_id) <= 0)
		{
			e107::getMessage()->addDebug('Download id "' . intval($item_id) . '" missing or invalid!', 'vstore');

			return false;
		}

		if(USERID === 0)
		{
			e107::getMessage()->addError(LAN_VSTORE_MSG_DL_LOGIN, 'vstore');

			return false;
		}
		$sql = e107::getDb();
		$orders = $sql->select(
			'vstore_orders',
			'*',
			'order_e107_user=' . USERID . ' AND order_items LIKE \'%"id": "' . intval($item_id) . '",%\'
            ORDER BY order_id DESC'
		);


		if(!$orders)
		{
			e107::getMessage()->addError(
				LAN_VSTORE_MSG_DL_NO_ORDER,
				'vstore'
			);

			return false;
		}

		$order_status = 'N';
		while($order = $sql->fetch())
		{
			$order_status = $order['order_status'];
			if($order['order_status'] == 'C')
			{
				// Status Completed = Payment OK, regardless of the orde_pay_status (e.g. in case of banktransfer)
				return true;
			}
			elseif($order['order_pay_status'] == 'complete' && $order['order_status'] == 'N')
			{
				// If order_status = New and pay_status = complete (e.g. in case of paypal payment)
				return true;
			}
		}
		// Order not completed or payment not complete + order_status = New
		e107::getMessage()->addError(
			sprintf(LAN_VSTORE_MSG_DL_STATE, vstore::getStatus($order_status)),
			'vstore'
		);

		return false;
	}

	/**
	 * Is the item (incl. the category of the item) active?
	 *
	 * @param int $itemid
	 * @return boolean true = active; false = inactive
	 */
	private function isItemActive($itemid)
	{

		if(intval($itemid) <= 0)
		{
			return false;
		}
		$sql = e107::getDb();

		if($sql->gen('SELECT item_id
        FROM `#vstore_items`
        LEFT JOIN `#vstore_cat` ON (item_cat = cat_id)
        WHERE item_active=1 AND cat_class IN (' . USERCLASS_LIST . ') AND item_id=' . intval($itemid)))
		{
			return true;
		}

		return false;
	}


	/**
	 * Return an array containing the variatons string and the pricemodified
	 *
	 * @param array $itemvars
	 * @param double $baseprice
	 * @return array [price => x.x, variation => yyy]
	 */
	public static function getItemVarProperties($itemvars, $baseprice)
	{

		if(empty($itemvars))
		{
			return false;
		}

		$baseprice = floatval($baseprice);

		if(is_string($itemvars))
		{
			$itemvars = self::item_vars_toArray($itemvars);
		}

		$result = array('price' => 0.0, 'variation' => array());

		$sql = e107::getDb();
		if($sql->select(
			'vstore_items_vars',
			'item_var_id, item_var_name, item_var_attributes',
			'FIND_IN_SET(item_var_id, "' . implode(',', array_keys($itemvars)) . '")'
		))
		{
			while($itemvar = $sql->fetch())
			{
				$attr = e107::unserialize($itemvar['item_var_attributes']);
				$text = $itemvar['item_var_name'];
				$value = $itemvars[$itemvar['item_var_id']];
				$operator = '';
				$op_val = 0.0;

				if(is_array($attr))
				{
					$frm = e107::getForm();
					foreach($attr as $row)
					{
						if($frm->name2id($row['name']) == $value)
						{
							$value = $row['name'];
							$operator = $row['operator'];
							$op_val = floatval($row['value']);
							break;
						}
					}
				}

				$result['variation'][] = "{$text}: {$value}";


				switch($operator)
				{
					case '%':
						$result['price'] += ($baseprice * $op_val / 100.0);
						break;
					case '+':
						$result['price'] += $op_val;
						break;
					case '-':
						$result['price'] -= $op_val;
						break;
				}
			}
		}

		$result['variation'] = implode(' / ', $result['variation']);

		return $result;
	}

	/**
	 * Returns the weight unit options or the label of the value entered.
	 * @param null $val
	 * @return array|string
	 */
	public static function weightUnits($val = null)
	{
		e107::lan('vstore', 'global', true);

		$opts = array(
			'g'     => defset('LAN_VSTORE_WEIGHT_G', 'Grams'),
			'kg'    => defset('LAN_VSTORE_WEIGHT_KG', 'Kilograms'),
			'lb'    => defset('LAN_VSTORE_WEIGHT_LB', 'Pounds'),
			'oz'    => defset('LAN_VSTORE_WEIGHT_OZ', 'Ounces'),
			'carat' => defset('LAN_VSTORE_WEIGHT_CARAT', 'Carats')
		);

		return !empty($val) ? varset($opts[$val]) : $opts;

	}


	/**
	 * calculate the shipping cost depending on the current cart items
	 *
	 * @param array $items
	 * @return double
	 */
	public static function calcShippingCost($items, $pref = null)
	{
		if(empty($pref))
		{
			$pref = e107::pref('vstore');
		}

		return (new ShippingService())->calculate($items, $pref);
	}

	/**
	 * Calculate coupon discount for an item
	 * If the coupon is valid, the result is always <= 0.0
	 *
	 * @param array $coupon a row from the vstore_coupons table.
	 * @param array $item (should have the columns item_id, item_cat, item_price, cart_qty, item_name)
	 * @return float Always returns 0.0 or negative value (discount)
	 */
	public static function calcCouponAmount($coupon, $item)
	{
		// '' or false (no coupon / unknown code) must not reach the service: (array) '' is not empty
		if(empty($coupon) || empty($item) || !is_array($coupon) || !is_array($item))
		{
			return 0.0;
		}

		$userId = (defined('USERID') && USERID > 0) ? (int) USERID : 0;
		$result = self::couponService()->calculate($coupon, $item, $userId);
		self::addResultMessage($result);

		return (float) $result->value();
	}

	/**
	 * Calculate tax rate for an item based on tax class and customer country
	 * 
	 * @param string $tax_class should be 'none', 'reduced', 'reduced1', 'reduced2', 'super_reduced', 'standard', 'parking'
	 * @param string $customer_country ISO 3166-1 alpha-2 country code (from billing form, NOT from IP)
	 * @param array|null $pref Plugin preferences (optional, will load if not provided)
	 * @return float Tax rate as decimal (e.g., 0.21 for 21%)
	 */
	public static function getTaxRate($tax_class, $customer_country = null, $pref = null)
	{
		if(empty($pref))
		{
			$pref = e107::pref('vstore');
		}

		$sessionData = e107::getSession('vstore')->get('_data');

		// The country comes from the billing form (session), never from the IP
		if(empty($customer_country))
		{
			$customer_country = varset($sessionData['customer_country'], '');
		}

		if(isset($pref['tax_classes']) && !is_array($pref['tax_classes']))
		{
			$pref['tax_classes'] = e107::unserialize($pref['tax_classes']);
		}

		$cust = (array) self::getCustomerData();
		$vatId = trim((string) varset($cust['vat_id'], varset($sessionData['vat_id'], '')));

		$result = self::taxService()->rate($tax_class, $customer_country, $pref, $vatId);
		self::addResultMessage($result);

		return (float) $result->value();
	}

	/** Debug logger for the services, or null when e_DEBUG_VSTORE is off. */
	private static function debugLogger($tag)
	{
		if(!deftrue('e_DEBUG_VSTORE'))
		{
			return null;
		}

		return function ($message) use ($tag)
		{
			e107::getDebug()->log($message, $tag);
		};
	}

	private static function couponService()
	{
		return new CouponService(new OrderCouponUsageRepository(), self::debugLogger('VSTORE_COUPON'));
	}

	private static function inventoryService()
	{
		return new InventoryService(new ItemRepository(), function ($message)
		{
			e107::getMessage()->addDebug($message, 'vstore');
		});
	}

	private static function userclassService()
	{
		return new UserclassService(new ItemRepository());
	}

	private static function cartService()
	{
		return new CartService(new CartRepository(), self::inventoryService());
	}

	private static function checkoutService()
	{
		return new CheckoutService(self::taxService(), self::couponService(), new ShippingService(), self::vatService());
	}

	private static function orderService()
	{
		return new OrderService();
	}

	private static function paymentService()
	{
		return new PaymentService(array(Omnipay::class, 'create'), self::debugLogger('VSTORE_PAYMENT'));
	}

	private static function invoiceService()
	{
		return new InvoiceService(new InvoiceNumberService());
	}

	/** Title of an invoice: shop title in the current language plus the formatted number. */
	private static function invoiceTitle($invoiceNr)
	{
		return self::invoiceService()->title((array) e107::pref('vstore'), e_LANGUAGE, LAN_VSTORE_INV_TITLE, $invoiceNr);
	}

	/** Kept for the whole request: the variation types are loaded once. */
	private static function itemVarTypes()
	{
		if(self::$itemVarTypeService === null)
		{
			self::$itemVarTypeService = new ItemVarTypeService(new ItemVarRepository());
		}

		return self::$itemVarTypeService;
	}

	private static function vatService()
	{
		if(self::$vatService === null)
		{
			self::$vatService = new VatService(null, self::debugLogger('VSTORE_VAT'));

			// VIES answers are kept for an hour: the cart is rendered again and again
			$memo = array();
			self::$vatService->setStore(
				function ($key) use (&$memo)
				{
					if(!isset($memo[$key]))
					{
						$cached = e107::getCache()->retrieve('vstore_vies_' . md5($key), 60, true);
						$memo[$key] = $cached ? json_decode($cached, true) : null;
					}

					return $memo[$key];
				},
				function ($key, $result) use (&$memo)
				{
					$memo[$key] = $result;
					e107::getCache()->set('vstore_vies_' . md5($key), json_encode($result), true);
				}
			);
		}

		return self::$vatService;
	}

	private static function taxService()
	{
		// Rates are cached on disk; without a file the library would query the remote service on every call
		$file = defined('e_CACHE') ? e_CACHE . 'vstore_vat_rates.cache' : '';

		return new TaxService(new \Ibericode\Vat\Rates($file, 12 * 3600), self::vatService(), self::debugLogger('VSTORE_TAX'));
	}

	/** Show the message of a service Result, translated with LAN_VSTORE_MSG_<CODE>. */
	private static function addResultMessage(Result $result)
	{
		if(!$result->hasMessage())
		{
			return;
		}

		$text = ResultTranslator::text($result);

		$mes = e107::getMessage();
		if($result->severity() === Result::WARNING)
		{
			$mes->addWarning($text, 'vstore');
		}
		else
		{
			$mes->addError($text, 'vstore');
		}
	}

	/**
	 * calc the tax amount of the item depending on the tax_rate for this item
	 *
	 * e.g.
	 * grossprice: 120€
	 * tax_rate: 0.2
	 * tax amount: 20€
	 *
	 * @param number $grossprice
	 * @param number $tax_rate
	 * @return number
	 */
	public static function calcTaxAmount($netPrice, $tax_rate)
	{
		return self::taxService()->taxAmount($netPrice, $tax_rate);
	}

	/**
	 * Validate and filter the customer data
	 *
	 * @param array $data
	 * @return bool/array false if data is invalid, otherwise the filtered data
	 */
	private function validateCustomerData($data, $type = 'billing')
	{
		$language = e_LANGUAGE;
		$addFields = array();
		foreach((array) varset($this->pref['additional_fields'], array()) as $i => $addField)
		{
			$addField['caption'] = is_array(varset($addField['caption'])) ? Translatable::pick($addField['caption'], $language) : varset($addField['caption'], '');
			$addFields[$i] = $addField;
		}

		$result = self::checkoutService()->validateCustomer(
			$data,
			$type,
			array('billing' => self::$customerFields, 'shipping' => self::$shippingFields),
			$addFields,
			!empty($this->pref['tax_check_vat']),
			(string) varset($this->pref['tax_business_country'])
		);

		if($result->isOk())
		{
			$validated = $result->value();

			// MB WAY sends the payment request to a Portuguese mobile number
			if($type === 'billing' && varset($this->post['gateway']) === 'mollie_mbway')
			{
				$phone = PhoneNumber::toE164(varset($validated['phone']), varset($validated['country']));

				if(!PhoneNumber::isPortugueseMobile($phone))
				{
					e107::getMessage()->addError(LAN_VSTORE_MSG_MBWAY_PHONE, 'vstore');

					return false;
				}
			}

			return $validated;
		}

		if($result->code() === 'FIELD_REQUIRED')
		{
			$field = $result->value();
			$label = $field['caption'] !== null ? $field['caption'] : defset('LAN_VSTORE_FR_' . strtoupper($field['field']), ucfirst($field['field']));
			e107::getMessage()->addError(sprintf(LAN_VSTORE_MSG_FIELD_REQUIRED, $label), 'vstore');
		}
		else
		{
			e107::getMessage()->addError(ResultTranslator::text($result), 'vstore');
		}

		return false;
	}

	/**
	 * fetch the next invoice nr to use
	 *
	 * @return int
	 */
	public static function getNextInvoiceNr()
	{
		return (new InvoiceNumberService())->next(
			(new OrderRepository())->lastInvoiceNr(),
			(int) e107::pref('vstore', 'invoice_next_nr')
		);
	}


	/**
	 * Return a formated invoice nr incl. prefix
	 *
	 * @param int $invoice_nr
	 * @return string
	 */
	public static function formatInvoiceNr($invoice_nr)
	{
		return (new InvoiceNumberService())->format($invoice_nr, (string) e107::pref('vstore', 'invoice_nr_prefix'));
	}


	/**
	 * fetch the corresponding order_id to a invoice_nr
	 *
	 * @param int $invoice_nr
	 * @return int
	 */
	public function getOrderIdFromInvoiceNr($invoice_nr)
	{
		return (new OrderRepository())->orderIdFromInvoiceNr($invoice_nr);
	}


	/**
	 * render the invoice by a given order_id
	 *
	 * @return boolean/array
	 */
	public function renderInvoice($forceUpdate = false)
	{

		if(!$this->order->isLoaded())
		{
			// Order ID missing or invalid
			e107::getMessage()->addDebug('No order loaded!', 'vstore');

			return false;
		}

		// check access (owner or admin) and status of the order: New, Complete or Processing
		$access = self::invoiceService()->checkAccess(
			$this->order->order_e107_user,
			$this->order->order_status,
			USERID,
			ADMIN,
			self::getStatus($this->order->order_status)
		);

		if(!$access->isOk())
		{
			e107::getMessage()->addError(ResultTranslator::text($access), 'vstore');

			return false;
		}

		// Check if the invoice should be created as pdf
		$pdf_invoice = vartrue($this->pref['invoice_create_pdf'], 0);
		if($pdf_invoice)
		{
			// Check if invoice already exists
			$local_pdf = $this->pathToInvoicePdf($this->order->order_invoice_nr, $this->order->order_e107_user);
			if($local_pdf != '' && !$forceUpdate)
			{
				$this->downloadInvoicePdf($local_pdf);

				return;
			}
			if($local_pdf != '')
			{
				// Delete old pdf, to make sure it WILL get recreated!
				@unlink($local_pdf);
			}
		}

		$taxBusinessCountry = '';
		$billingCountry = '';

		if(isset($this->order->order_billing['country']))
		{
			$billingCountry = $this->order->order_billing['country'];
		}

		if(isset($this->pref['tax_business_country']))
		{
			$taxBusinessCountry = $this->pref['tax_business_country'];
		}

		$this->order->is_business = self::isReverseChargeOrder($this->order->order_billing, $this->order->order_pay_tax);
		$this->order->is_local = (strtoupper(vartrue($billingCountry, $taxBusinessCountry)) === strtoupper($taxBusinessCountry));

		return $this->renderInvoiceTemplate($pdf_invoice);

	}

	/**
	 * Check if pdf creation is enabled and the TCPDF library (Composer package of Vstore) is available.
	 * The e107 pdf plugin is not needed any more.
	 *
	 * @param bool $checkPref (optional) true (default) checks first if the pdf creation is enabled
	 *
	 * @return bool true if invoices can be created as pdf
	 */
	public static function checkPdfPlugin($checkPref = true)
	{

		// Check if pdf invoices should be created
		$create_pdf = e107::pref('vstore', 'invoice_create_pdf', '0');
		if($checkPref && !vartrue($create_pdf))
		{
			return false;
		}

		if(!class_exists('TCPDF'))
		{
			e107::getMessage()->addWarning(LAN_VSTORE_MSG_PDF_PLUGIN);

			return false;
		}

		return true;
	}

	/**
	 * create a pdf invoice
	 *
	 * @param array $data
	 * @param boolean $saveToDisk
	 * @return void
	 */
	public function invoiceToPdf($data, $saveToDisk = true)
	{

		if(!self::checkPdfPlugin())
		{
			return;
		}

		$dir = '';

		if($saveToDisk)
		{
			// Make sure the path is absolute
			$dir = realpath(e107::getFile()->getUserDir($data['userid'], true));

			if($dir == false || trim($dir) == '')
			{
				e107::getLog()->add(
					'Vstore',
					'Unable to create invoice user folder: "' .
					e107::getFile()->getUserDir($data['userid']) . '"',
					E_LOG_WARNING
				);
				e107::getMessage()->addError(LAN_VSTORE_MSG_PDF_FOLDER, 'vstore');

				return;
			}
		}

		$tp = e107::getParser();
		$title = str_replace(array(':', '*', '?', '"', '<', '>', '|'), '-', InvoicePdf::decodeEntities($data['subject']));
		$file = e107::getForm()->name2id($title) . '.pdf';

		$pdf = new InvoicePdf(array(
			'logo'         => $data['logo'],
			'site_name'    => html_entity_decode(SITENAME, ENT_QUOTES, 'UTF-8'),
			'site_tag'     => html_entity_decode(defset('SITETAG', ''), ENT_QUOTES, 'UTF-8'),
			'footer_html'  => $data['footer'],
			'page_label'   => LAN_VSTORE_PDF_PAGE,
			'created_text' => LAN_VSTORE_PDF_CREATED . ' ' . e107::getDateConvert()->convert_date(time(), 'short'),
		));

		$pdf->build(
			InvoicePdf::decodeEntities($tp->toHTML($data['text'], true, 'BODY')),
			array(
				'creator'  => SITENAME,
				'author'   => varset($this->pref['sender_name'], 'Sales'),
				'title'    => $title,
				'subject'  => $title,
			)
		);

		if($saveToDisk)
		{
			$pdf->Output($dir . DIRECTORY_SEPARATOR . $file, 'F');

			return;
		}

		$pdf->Output($file, 'I');
	}

	/**
	 * Check if pdf of given invoice number already exists and return the fullpath incl. filename
	 *
	 * @param int $invoice_nr invoice nr used in the filename
	 * @param int $e107_user_id userid of the customer
	 * @return string empty string if file doesn't exists
	 */
	public static function pathToInvoicePdf($invoice_nr, $e107_user_id = null)
	{
		if(is_null($e107_user_id))
		{
			$e107_user_id = USERID;
		}

		return self::invoiceService()->existingPdf(
			e107::getFile()->getUserDir($e107_user_id),
			e107::getForm()->name2id(self::invoiceTitle($invoice_nr))
		);
	}


	/**
	 * Return the given pdf file as downloads
	 *
	 * @param string $local_pdf
	 * @return void
	 */
	public function downloadInvoicePdf($local_pdf)
	{

		if($local_pdf != '')
		{
			while(ob_end_clean())
			{
				;
			}
			header('Content-Description: File Transfer');
			if(headers_sent())
			{
			//	$this->Error('Some data has already been output to browser, can\'t send PDF file');
			}
			header('Cache-Control: private, must-revalidate, post-check=0, pre-check=0, max-age=1');
			header('Pragma: public');
			header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
			// force download dialog
			if(strpos(php_sapi_name(), 'cgi') === false)
			{
				header('Content-Type: application/force-download');
				header('Content-Type: application/octet-stream', false);
				header('Content-Type: application/download', false);
				header('Content-Type: application/pdf', false);
			}
			else
			{
				header('Content-Type: application/pdf');
			}
			// use the Content-Disposition header to supply a recommended filename
			header('Content-Disposition: attachment; filename="' . basename($local_pdf) . '"');
			header('Content-Transfer-Encoding: binary');

			header('Content-Length: ' . filesize($local_pdf));
			readfile($local_pdf);
			exit;
		}
		else
		{
			e107::getMessage()->addWarning(LAN_VSTORE_MSG_PDF_NOT_FOUND, 'vstore');
		}
	}

	/**
	 * Was the order sold without tax to a business of another country (a VAT ID and no tax charged)?
	 *
	 * @param array|string $billing order_billing
	 * @param array|string $payTax  order_pay_tax: rate => amount
	 * @return bool
	 */
	public static function isReverseChargeOrder($billing, $payTax)
	{
		$billing = is_array($billing) ? $billing : (array) e107::unserialize($billing);
		$business = strtoupper((string) e107::pref('vstore', 'tax_business_country'));
		$country = strtoupper((string) varset($billing['country'], $business));

		if(empty($billing['vat_id']) || $country === $business)
		{
			return false;
		}

		$tax = is_array($payTax) ? $payTax : (array) e107::unserialize($payTax);

		return abs(array_sum(array_map('floatval', $tax))) < 0.005;
	}

	/**
	 * Check if the current order status is valid for invoice creation
	 *
	 * @param string $order_status
	 * @return bool true, order is in a valid state (New, Complete, Processing); false otherwise
	 */
	public static function validInvoiceOrderState($order_status)
	{
		return (new InvoiceNumberService())->isValidState($order_status);
	}

	/** Dashboard ids from this value up are order ids: quotes and unpaid orders have no invoice number to be found by. */
	const ORDER_VIEW_OFFSET = 1000000000;

	/**
	 * Id used in the dashboard links of an order: its invoice number, or the order id plus ORDER_VIEW_OFFSET without one.
	 *
	 * @param array $order row of vstore_orders
	 * @return int
	 */
	public static function orderViewId(array $order)
	{
		$nr = (int) varset($order['order_invoice_nr']);

		return $nr > 0 ? $nr : self::ORDER_VIEW_OFFSET + (int) varset($order['order_id']);
	}

	/**
	 * SQL condition that finds an order of the dashboard by the id of orderViewId().
	 *
	 * @param int $id
	 * @return string
	 */
	public static function orderViewWhere($id)
	{
		$id = (int) $id;

		return ($id >= self::ORDER_VIEW_OFFSET ? 'order_id=' . ($id - self::ORDER_VIEW_OFFSET) : 'order_invoice_nr=' . $id) . ' AND order_e107_user=' . (int) USERID;
	}

	/**
	 * Check if a given item_var_id is used for inventory tracking
	 *
	 * @param $varId item_var_id
	 * @return bool true if inventory must be tracked, false otherwise
	 */
	public static function isInventoryTrackingVar($varId)
	{
		return self::itemVarTypes()->isTracking($varId);
	}


	/**
	 * Filter the given string/array $curVal by $type (0 =non-tracking; 1=tracking)
	 *
	 * @param string/array $curVal  value to filter against §itemVarsType
	 * @param int $type (0 =non-tracking; 1=tracking)
	 * @param bool $asArray true return array, otherwise comma-separated string
	 * @return array|string
	 */
	public static function filterItemVarsByType($curVal, $type = 0, $asArray = false)
	{
		return self::itemVarTypes()->filter($curVal, $type, $asArray);
	}


	/**
	 * Add a order log entry to the log array
	 *
	 * @param array|string $log The log as string or array
	 * @param string $title The title to use in the log string
	 * @param variant $oldVal The old value
	 * @param variant $newVal The new value
	 * @param bool $asArray Return as array or string (default)
	 *
	 * @return array|string
	 */
	public static function addToOrderLog($log, $title, $oldVal, $newVal, $asArray = false)
	{

		if(is_string($log))
		{
			$log = e107::unserialize($log);
		}
		if(!is_array($log))
		{
			$log = array();
		}

		$log[] = array(
			'datestamp' => time(),
			'user_id'   => USERID,
			'user_name' => USERNAME,
			'text'      => e107::getParser()->lanVars(LAN_VSTORE_ORD_LOG_CHANGED, array(
				'x' => $title,
				'y' => $oldVal,
				'z' => $newVal
			))
		);

		return $asArray ? $log : e107::serialize($log, 'json');
	}

	/**
	 * Mostly for unit testing.
	 * @param (array) $get
	 */
	function setGet($get)
	{
		$this->get = (array) $get;
	}

	/**
	 * Mostly for unit testing.
	 * @param (array) $post
	 */
	function setPost($post)
	{
		$this->post = (array) $post;
	}

	/**
	 * Set the prefs. - mostly used for unit testing.
	 * @param $pref
	 */
	public function setPrefs($pref)
	{
		$this->pref = (array) $pref;
	}

	public function getPrefs()
	{
		return $this->pref;
	}


	/**
	 * Compiles the prefs for usage within the class.
	 */
	public function initPrefs()
	{
		$active = array();


		// Load generic gateways into $gateways and set active icon status.
		if(!empty($this->pref['gateways']))
		{
			foreach($this->pref['gateways'] as $gate => $var)
			{
				if(Catalog::isRetiredGateway($gate))
				{
					continue;
				}

				self::$gateways[$gate] = $var;

				if(!empty($var['active']))
				{
					$active[$gate] = $this->getGatewayIcon($gate);
				}
			}
		}


		$this->order = e107::getSingleton('vstore_order', e_PLUGIN . 'vstore/inc/vstore_order.class.php');

		$this->currency = vartrue($this->pref['currency'], 'USD');
		$this->captionCategories = LAN_VSTORE_MSG_CAPTION_CATEGORIES;
		$this->captionOutOfStock = LAN_VSTORE_003;

		if(!empty($this->pref['caption']) && !empty($this->pref['caption'][e_LANGUAGE]))
		{
			$this->captionBase = $this->pref['caption'][e_LANGUAGE];
		}

		if(!empty($this->pref['additional_fields']))
		{
			foreach($this->pref['additional_fields'] as $k => $v)
			{
				if(vartrue($v['active'], false))
				{
					static::$customerFields[] = 'add_field' . $k;
				}
			}
		}

		if(!empty($this->pref['caption_categories']) && !empty($this->pref['caption_categories'][e_LANGUAGE]))
		{
			$this->captionCategories = $this->pref['caption_categories'][e_LANGUAGE];
			//e107::getDebug()->log("caption: ".$this->captionCategories);
		}

		if(!empty($this->pref['caption_outofstock']) && !empty($this->pref['caption_outofstock'][e_LANGUAGE]))
		{
			$this->captionOutOfStock = $this->pref['caption_outofstock'][e_LANGUAGE];
			$this->sc->captionOutOfStock = $this->captionOutOfStock;
		}


		if(deftrue('e_DEBUG_VSTORE'))
		{
		//	e107::getDebug()->log($this->pref);
		//	e107::getDebug()->log("CartID:" . $this->cartId);
		}
		// get all category data.
		$count = 0;
		$query = 'SELECT * FROM #vstore_cat WHERE cat_class IN (' . USERCLASS_LIST . ') ';
		if($data = e107::getDb()->retrieve($query, true))
		{
			foreach($data as $row)
			{
				$id = $row['cat_id'];
				$this->categories[$id] = $row;
				$sef = vartrue($row['cat_sef'], '--undefined--');
				$this->categorySEF[$sef] = $id;

				if(empty($row['cat_parent']))
				{
					$count++;
				}
			}
		}
		$this->categoriesTotal = $count;


		$tp = e107::getParser();
		foreach(self::$gateways as $k => $icon)
		{
			$key = $k . "_active";
			if(!empty($this->pref[$key]))
			{
				if(self::isMollie($k))
				{
					if(!empty($this->pref['mollie_payment_methods']))
					{
						$paymentMethods = array_keys($this->pref['mollie_payment_methods']);
						foreach($paymentMethods as $method)
						{
							$active[$method] = $this->getMolliePaymentMethodIcon($method);
						}
					}
				}
				else
				{
					$active[$k] = $this->getGatewayIcon($k);
				}

				// get gateway prefs. eg. paypal_password, paypal_active
				foreach($this->pref as $key => $v)
				{
					if(strpos($key, $k) === 0)
					{
						$newkey = substr($key, (strlen($k) + 1));
						$this->pref[$k][$newkey] = $v;
					}
				}
			}
		}


		if(deftrue('e_DEBUG_VSTORE') && getperms('0'))
		{
		//	e107::getDebug()->log($this->pref);
		}


		$currency = vartrue($this->pref['currency'], 'USD');

		// Mollie refuses euro-only methods in other currencies ("The currency is not supported by this payment method").
		foreach(array_keys($active) as $method)
		{
			if(!Catalog::supportsCurrency($method, $currency))
			{
				unset($active[$method]);
			}
		}

		$this->active = $active;
	}

	/**
	 * Load the Gateway class and set the api keys etc.
	 * @param string $name paypal | mollie | etc.
	 * @return array
	 */
	public function loadGateway($name)
	{
		list($gateway, $message, $mode) = self::createGateway($name, $this->pref);

		if($mode)
		{
			$this->setMode($mode);
		}

		return array($gateway, $message);
	}

	/**
	 * Create the gateway of a payment method without touching the state of the shop.
	 *
	 * @param string $name paypal | mollie | bank_transfer | etc.
	 * @param array|null $pref Plugin preferences (loaded when empty)
	 * @return array gateway object or null, message to show, mode the shop should switch to ('' = none)
	 */
	public static function createGateway($name, $pref = null)
	{
		if(empty($pref))
		{
			$pref = e107::pref('vstore');
		}

		$result = self::paymentService()->load($name, (array) $pref);
		$value = $result->value();
		$message = '';

		if($value['bank_details'] !== '')
		{
			$message = '<br />' . LAN_VSTORE_MSG_BANK_INSTRUCTIONS . '<br />' . e107::getParser()->toHTML($value['bank_details'], true);
		}

		if($result->hasMessage())
		{
			$message = ResultTranslator::text($result);
			if($value['detail'] !== '' && ADMIN)
			{
				$message .= $value['detail'];
			}

			if($result->code() !== 'GW_CREDENTIALS')
			{
				trigger_error($message . ' [' . $name . ']', $result->isOk() ? E_USER_WARNING : E_USER_NOTICE);
			}
		}

		return array($value['gateway'], $message, $value['mode']);
	}

	/**
	 * @param bool $pdf_invoice
	 * @return array|string
	 */
	public function renderInvoiceTemplate($pdf_invoice = false)
	{

		$template = e107::getTemplate('vstore', 'vstore_invoice');
		$invoice = varset($this->pref['invoice_template']);

		if(empty($invoice))
		{
			if(empty($template['default']))
			{
				// Template not found!
				e107::getMessage()->addDebug('Invoice template "default" not found!', 'vstore');
				trigger_error('Invoice template "default" not found!');
				return false;
			}

			$invoice = $template['default'];
		}


		$tp = e107::getParser();

		$invoiceData = $this->order->getData();
		// Add business/local flags for template rendering
		$invoiceData['is_business'] = isset($this->order->is_business) ? $this->order->is_business : false;
		$invoiceData['is_local'] = isset($this->order->is_local) ? $this->order->is_local : true;
		
		$this->sc->addVars($invoiceData);
		$this->sc->wrapper('vstore_invoice/default');


		$text = $tp->parseTemplate($invoice, true, $this->sc);
		$footer = $tp->parseTemplate($template['footer'], true, $this->sc);

		$logo = $this->sc->sc_invoice_logo('path');

		if(!empty($logo))
		{
			$logo = ($pdf_invoice ? e_ROOT : e_HTTP) . $logo;
		}

		if($pdf_invoice)
		{
			$result = array(
				'userid'  => $this->order->order_e107_user,
				'subject' => self::invoiceTitle($this->order->order_invoice_nr),
				'text'    => $text,
				'footer'  => $footer,
				'logo'    => $logo,
				'url'     => e107::url(
					'vstore',
					'invoice',
					array('order_invoice_nr' => $this->order->order_invoice_nr),
					array('mode' => 'full')
				)
			);
		}
		else
		{
			$result = e107::getParser()->lanVars(
				$template['display'],
				array(
					'sitename' => SITENAME,
					'body'     => $text,
					'footer'   => $footer
				)
			);
			$result = e107::getParser()->parseTemplate($result);
		}

		return $result;
	}

	/**
	 * Get total usage count for a coupon code
	 * 
	 * @param string $couponCode The coupon code
	 * @return int Number of times the coupon has been used
	 */
	public static function getCouponUsageCount($couponCode)
	{
		return (new OrderCouponUsageRepository())->totalUsage($couponCode);
	}

	/**
	 * Get usage count for a coupon code by a specific user
	 * 
	 * @param string $couponCode The coupon code
	 * @param int $userId The e107 user ID
	 * @return int Number of times the user has used this coupon
	 */
	public static function getCouponUsageCountByUser($couponCode, $userId)
	{
		return (new OrderCouponUsageRepository())->userUsage($couponCode, $userId);
	}

	/**
	 * Validate VAT ID against VIES (EU VAT validation service)
	 * 
	 * @param string $vatId The VAT ID to validate
	 * @param string $countryCode Two-letter country code
	 * @return array Array with keys: 'valid' (bool), 'name' (string), 'address' (string), 'error' (string)
	 */
	public static function validateVATID($vatId, $countryCode)
	{
		$pref = e107::pref('vstore');

		return self::vatService()->validate($vatId, $countryCode, !empty($pref['tax_check_vat']));
	}
}
