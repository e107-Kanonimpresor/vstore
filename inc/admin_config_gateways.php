<?php
e107::lan('vstore', 'admin_gateways', true);

/**
 * Adminarea module payment gateways
 */

use Omnipay\Omnipay;
use Omnipay\Common\Helper;
use Composer\Autoload\ClassLoader;

class vstore_gateways_ui extends e_admin_ui
{
	use vstore_admin_common;


	protected $pluginTitle = 'Vstore';
	protected $pluginName = 'vstore';
	// protected $eventName		= 'vstore_order'; // remove comment to enable event triggers in admin.
	// protected $table			= 'vstore_orders';
	// protected $pid				= 'order_id';
	// protected $perPage			= 10;
	// protected $batchDelete		= false;
	//	protected $batchCopy		= true;
	//	protected $sortField		= 'somefield_order';
	//	protected $orderStep		= 10;
	// protected $tabs				= array(LAN_GENERAL,'Details'); // Use 'tab'=>'paypal'  OR 'tab'=>'paypal_rest' in the $fields below to enable.

	//	protected $listQry      	= "SELECT o.*, SUM(c.cart_qty) as items FROM `#vstore_orders` AS o LEFT JOIN `#vstore_cart` AS  c ON o.order_session = c.cart_session  "; // Example Custom Query. LEFT JOINS allowed. Should be without any Order or Limit.

	// protected $listOrder		= 'order_id DESC';


	// protected $fields 		= array ( );

	// protected $fieldpref = array('order_id','order_ship_to', 'order_status', 'order_date', 'order_items', 'order_pay_transid','order_pay_amount','order_pay_status');

	// defines the tab order.
	protected $preftabs = array(
		'paypal'        => 'Paypal Express',
		'paypal_rest'   => 'Paypal REST',
		'paypal_pro'    => 'Paypal Pro',
		'mollie'        => 'Mollie',
		'bank_transfer' => LAN_VSTORE_ADMIN_GATE_TAB_BANK
	);


	protected $prefs = array(
	//	'gateways'          => array('title'=>'Gateways', 'type'=>'hidden', 'tab' => 'paypal', 'data'=>'str'),
	/*	'paypal_active'    => array('title' => LAN_ACTIVE, 'type' => 'boolean', 'tab' => 'paypal', 'data' => 'int', 'help' => ''),
		'paypal_testmode'  => array('title' => "Paypal Testmode", 'type' => 'boolean', 'tab' => 'paypal', 'data' => 'int', 'writeParms' => array(), 'help' => 'Use Paypal Sandbox'),
		'paypal_username'  => array('title' => "Paypal Username", 'type' => 'text', 'tab' => 'paypal', 'data' => 'str', 'writeParms' => array('size' => 'xxlarge'), 'help' => ''),
		'paypal_password'  => array('title' => "Paypal Password", 'type' => 'password', 'tab' => 'paypal', 'data' => 'str', 'help' => '', 'writeParms' => array('size' => 'xxlarge')),
		'paypal_signature' => array('title' => "Paypal Signature", 'type' => 'text', 'tab' => 'paypal', 'data' => 'str', 'help' => '', 'writeParms' => array('size' => 'xxlarge')),

		'paypal_rest_active'   => array('title' => LAN_ACTIVE, 'type' => 'boolean', 'tab' => 'paypal_rest', 'data' => 'int', 'help' => ''),
		'paypal_rest_testmode' => array('title' => "Paypal REST Testmode", 'type' => 'boolean', 'tab' => 'paypal_rest', 'data' => 'int', 'writeParms' => array(), 'help' => 'Use Paypal Sandbox'),
		'paypal_rest_clientId' => array('title' => "Paypal Client Id", 'type' => 'text', 'tab' => 'paypal_rest', 'data' => 'str', 'writeParms' => array('size' => 'xxlarge'), 'help' => ''),
		'paypal_rest_secret'   => array('title' => "Paypal Secret", 'type' => 'password', 'tab' => 'paypal_rest', 'data' => 'str', 'help' => '', 'writeParms' => array('size' => 'xxlarge')),
	*/
		//	'paypal_signature'      => array('title'=>"Paypal Signature", 'type'=>'text', 'tab'=>'paypal', 'data'=>'str', 'help'=>'', 'writeParms'=>array('size'=>'xxlarge')),

		'mollie_active'                 => array('title' => LAN_ACTIVE, 'type' => 'boolean', 'tab' => 'mollie', 'data' => 'int', 'help' => ''),
		'mollie_testmode'               => array('title' => LAN_VSTORE_ADMIN_GATE_MOLLIE_TEST, 'type' => 'boolean', 'tab' => 'mollie', 'data' => 'int', 'writeParms' => array(), 'help' => LAN_VSTORE_ADMIN_GATE_MOLLIE_TEST_H),
		'mollie_api_key_live'           => array('title' => LAN_VSTORE_ADMIN_GATE_MOLLIE_KEY_LIVE, 'type' => 'text', 'tab' => 'mollie', 'data' => 'str', 'note' => LAN_VSTORE_ADMIN_GATE_MOLLIE_KEYS_NOTE, 'writeParms' => array('size' => 'xxlarge')),
		'mollie_api_key_test'           => array('title' => LAN_VSTORE_ADMIN_GATE_MOLLIE_KEY_TEST, 'type' => 'text', 'tab' => 'mollie', 'data' => 'str', 'note' => '', 'help' => '', 'writeParms' => array('size' => 'xxlarge')),
		'mollie_payment_methods'        => array('title' => LAN_VSTORE_ADMIN_GATE_MOLLIE_METHODS, 'type' => 'checkboxes', 'tab' => 'mollie', 'data' => 'str', 'note' => LAN_VSTORE_ADMIN_GATE_MOLLIE_METHODS_NOTE, 'help' => '', 'writeParms' => array('__options' => array('multiple' => true, 'size' => 'xxlarge'))),

		'gateways/bank_transfer/active' => array('title' => LAN_ACTIVE, 'type' => 'boolean', 'tab' => 'bank_transfer', 'data' => 'int', 'help' => ''),
		'gateways/bank_transfer/title'  => array('title' => LAN_TITLE, 'type' => 'text', 'tab' => 'bank_transfer',  'data' => 'str', 'writeParms' => array('size'=>'xxlarge', 'default'=>LAN_VSTORE_ADMIN_GATE_BANK_TITLE_DEF)),
		'gateways/bank_transfer/icon'   => array('title' => LAN_ICON, 'type' => 'icon', 'tab' => 'bank_transfer', 'data' => 'str', 'help' => '', 'writeParms'=>array('default'=>'fa-bank.glyph')),
		'gateways/bank_transfer/details' => array('title' => LAN_VSTORE_ADMIN_GATE_BANK_DETAILS, 'type' => 'textarea', 'tab' => 'bank_transfer', 'data' => 'str', 'writeParms' => array('placeholder' => LAN_VSTORE_ADMIN_GATE_BANK_DETAILS_PH), 'help' => ''),

	);


	public function init()
	{

		//	unset($this->preftabs[3],$this->preftabs[4]); // Disable Amazon and Skrill for Now until they work. // TODO //FIXME


		$paymentMethods = vstore::getMolliePaymentMethods();
		foreach($paymentMethods as $k => $row)
		{
			//$this->prefs['mollie_payment_methods']['writeParms'][$k] = $row['title'];
			$this->prefs['mollie_payment_methods']['writeParms'][$k] = vstore::getMolliePaymentMethodIcon($k, '2x') . '  ' . $row['title'];
		}
		asort($this->prefs['mollie_payment_methods']['writeParms']);


		/** Automatically build the preferences from the Gateway files         * */


		$this->setGatewayFields();
		$this->checkDeprecatedPrefs();
		$this->warnRetiredGateways();
		$this->warnMollieCurrency();

		ksort($this->preftabs); // sort the tabs.

	}

	/** Settings saved for gateways that are no longer offered stay in the database but are never used. */
	private function warnRetiredGateways()
	{
		$saved = (array) e107::pref('vstore', 'gateways');
		$found = array();

		foreach(array_keys($saved) as $name)
		{
			if(\Vstore\Support\Catalog::isRetiredGateway($name))
			{
				$found[] = ucfirst($name);
			}
		}

		if($found)
		{
			e107::getMessage()->addInfo(e107::getParser()->lanVars(LAN_VSTORE_ADMIN_GATE_RETIRED, implode(', ', $found)));
		}
	}

	/** Euro-only Mollie methods are hidden at checkout in other currencies: tell the admin why. */
	private function warnMollieCurrency()
	{
		$currency = strtoupper((string) e107::pref('vstore', 'currency'));
		$methods  = (array) e107::pref('vstore', 'mollie_payment_methods');
		$hidden   = array();

		foreach(array_keys($methods) as $method)
		{
			if(!\Vstore\Support\Catalog::supportsCurrency($method, $currency))
			{
				$hidden[] = ucfirst(str_replace('_', ' ', substr($method, 7)));
			}
		}

		if($hidden)
		{
			e107::getMessage()->addInfo(e107::getParser()->lanVars(LAN_VSTORE_ADMIN_GATE_MOLLIE_CURRENCY, array('x' => implode(', ', $hidden), 'y' => $currency)));
		}
	}

	private function checkDeprecatedPrefs()
	{
		$cfg = e107::getPlugConfig('vstore');
		$list = $cfg->getPref();

		$gateways = array('paypal', 'paypal_rest', 'bank');

		$message = '';

		foreach($list as $pref => $val)
		{
			$tmp = explode("_",$pref);

			if(!in_array($tmp[0], $gateways))
			{
				continue;
			}

			$cfg->remove($pref);
			$message .= "<tr><td>".$pref."</td><td>".$val."</td></tr>";
		}


		if(empty($message))
		{
			return null;
		}

		$cfg->save(false,true,false);

		$text = LAN_VSTORE_ADMIN_GATE_DEPRECATED_TEXT."
		<table class='table table-striped table-bordered table-condensed' style='width:600px'>
		".$message."
		</table>";

		e107::getMessage()->setTitle(LAN_VSTORE_ADMIN_GATE_DEPRECATED_TITLE, E_MESSAGE_ERROR)->setIcon('fa-warning', E_MESSAGE_ERROR)->addError($text);

	}


	private function getAvailableGateways()
	{
		$path = e_PLUGIN . "vstore/vendor/";
		$list = [];

		$fixed = array(
			'paypal'        => array('omnipay/paypal/src/ExpressGateway.php','PayPal_Express', 'fa-paypal' ),
			'paypal_rest'   => array('omnipay/paypal/src/RestGateway.php', 'PayPal_Rest', 'fab-cc-paypal' ),
			'paypal_pro'   => array('omnipay/paypal/src/ProGateway.php', 'PayPal_Pro', 'fa-paypal' ),
		);

		// Load Paypal.
		foreach($fixed as $key => $var)
		{
			list($file, $class, $icon) = $var;

			require_once($path.$file);
			/** @var Omnipay\Omnipay $gt  */
			$gt     = Omnipay::create($class);
			$title   = $gt->getName();
			$parms  = $gt->getDefaultParameters();
			$name = $gt->getShortName(); // same as 'class'.


			$list[$key] = array('name' => $name, 'title' => $title, 'parms' => $parms, 'icon'=> $icon);
		}

		$packages = vstore::getGatewayPackageList();

		$defaultIcons = array();

		foreach($packages as $folder => $class)
		{

			/** @var Omnipay\Omnipay $gt  */
			$gt     = Omnipay::create($class);
			$title  = $gt->getName();
			$parms  = $gt->getDefaultParameters();
		//	$name   = $gt->getShortName();

			$list[$folder] = array('name' => $class, 'title' => str_replace('_', ' ', $title), 'parms' => $parms, 'icon'=>varset($defaultIcons[$folder]));
		}

		return $list;

	}



	function beforePrefsSave($new_data, $old_data)
	{
	//	e107::getMessage()->addDebug("<h4>Saving the following prefs:</h4><pre> ".var_export($new_data,true).'</pre>');

		return $new_data;
	}

	/** e107 writes the changed settings, keys included, to the admin log: hide them there. */
	function afterPrefsSave()
	{
		$sql = e107::getDb();
		$where = array();
		foreach(\Vstore\Support\SecretMask::KEYWORDS as $word)
		{
			$where[] = "dblog_remarks LIKE '%" . $sql->escape($word) . "%'";
		}

		$rows = $sql->retrieve('admin_log', 'dblog_id, dblog_remarks', implode(' OR ', $where), true);

		foreach((array) $rows as $row)
		{
			$masked = \Vstore\Support\SecretMask::maskLogText($row['dblog_remarks']);

			if($masked !== $row['dblog_remarks'])
			{
				$sql->update('admin_log', array('data' => array('dblog_remarks' => $masked), 'WHERE' => 'dblog_id=' . (int) $row['dblog_id']));
			}
		}

		return null;
	}

	/**
	 * Automatically loads and sets gateway parameter fields based on their their classes.
	 */
	private function setGatewayFields()
	{
		$extraGateways = $this->getAvailableGateways();

		unset($extraGateways['mollie']); // already defined.

		foreach($extraGateways as $plug => $gates)
		{
			$this->prefs['gateways/'.$plug.'/active'] = array('title' => LAN_ACTIVE, 'type' => 'bool', 'tab' => $plug, 'data' => 'int', 'writeParms' => array());
			$this->prefs['gateways/'.$plug.'/title'] = array('title' => LAN_TITLE, 'type' => 'text', 'tab' => $plug,  'data' => 'str', 'writeParms' => array('size'=>'xxlarge', 'default'=>varset($gates['title'])));
			$this->prefs['gateways/'.$plug.'/icon'] = array('title' => LAN_ICON, 'type' => 'icon', 'tab' => $plug, 'data' => 'str', 'writeParms' => array('default'=>varset($gates['icon'])));
			$this->prefs['gateways/'.$plug.'/name'] = array('title' => LAN_VSTORE_ADMIN_GATE_CLASSNAME, 'type' => 'hidden', 'tab' => $plug,  'data' => 'str', 'writeParms' => array('value'=>$gates['name']));

			foreach($gates['parms'] as $pref => $field)
			{
				$type = 'text';
				$data = 'str';
				$writeParms = array('size' => 'xxlarge');

				if(is_bool($field))
				{
					$type = 'bool';
					$data = 'bool';
					$writeParms = array();
				}
				elseif(is_array($field))
				{
					$type = 'dropdown';
					$writeParms['optArray'] = $field;
					$writeParms['useValues'] = true;
				}

				$title = preg_replace('/([A-Z])/', ' $1', ucfirst($pref));
				$title = str_replace('_', ' ', $title);
				$pref = Helper::camelCase($pref);
				$this->prefs['gateways/'.$plug.'/prefs/'.$pref] = array('title' => ltrim(ucwords($title)), 'type' => $type, 'tab' => $plug, 'data' => $data, 'writeParms' => $writeParms);

				$this->preftabs[$plug] = $gates['name'];
			}

		}
	}

}


class vstore_gateways_form_ui extends e_admin_form_ui
{


}

