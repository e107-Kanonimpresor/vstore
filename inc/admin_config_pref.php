<?php
/**
 * Vstore - shopping cart plugin for e107.
 * Based on e107inc/vstore (CaMerOn, Achim Ennenbach, e107 Inc). Modified by Kanonimpresor, 2025-2026.
 *
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.html
 */
e107::lan('vstore', 'admin_pref', true);

/**
 * Adminarea module preferences
 */
class vstore_pref_ui extends e_admin_ui
{
	use vstore_admin_common;

			
		protected $pluginTitle		= 'Vstore';
		protected $pluginName		= 'vstore';
		// protected $table			= 'vstore_cart';
		// protected $pid				= 'cart_id';
		// protected $perPage			= 10; 
		// protected $batchDelete		= true;
	//	protected $batchCopy		= true;		
	//	protected $sortField		= 'somefield_order';
	//	protected $orderStep		= 10;
	//	protected $tabs			= array('Tabl 1','Tab 2'); // Use 'tab'=>0  OR 'tab'=>1 in the $fields below to enable. 
		
	//	protected $listQry      	= "SELECT * FROM #tableName WHERE field != '' "; // Example Custom Query. LEFT JOINS allowed. Should be without any Order or Limit.
	
		protected $listOrder		= 'cart_id DESC';
	
		// protected $fields 		= array ( );		
		
		// protected $fieldpref = array();

		public static $taxClassesDefault = array(
			0 => array('name'=>'none', 		'description'=>LAN_VSTORE_ADMIN_PREF_TAX_DEF_NONE, 		'value'=>'0.00'),
			1 => array('name'=>'reduced', 	'description'=>LAN_VSTORE_ADMIN_PREF_TAX_DEF_REDUCED, 	'value'=>'0.00'),
			2 => array('name'=>'standard', 	'description'=>LAN_VSTORE_ADMIN_PREF_TAX_DEF_STANDARD, 	'value'=>'0.00'),
		);
	
		// optional
		protected $preftabs = array(
			'general'  => LAN_GENERAL,
			'ship'     => LAN_VSTORE_ADMIN_PREF_TAB_SHIP,
			'tax'      => LAN_VSTORE_ADMIN_PREF_TAB_TAX,
			'email'    => LAN_VSTORE_ADMIN_PREF_TAB_EMAIL,
			'howto'    => LAN_VSTORE_ADMIN_PREF_TAB_HOWTO,
			'admin'    => LAN_VSTORE_ADMIN_PREF_TAB_ADMIN,
			'checkout' => LAN_VSTORE_ADMIN_PREF_TAB_CHECKOUT,
			'menu'     => LAN_VSTORE_ADMIN_PREF_TAB_MENU,
			'css'      => LAN_VSTORE_ADMIN_PREF_TAB_CSS,
		);


		protected $prefs = array(
			'caption'                   => array('title'=> LAN_VSTORE_ADMIN_PREF_CAPTION, 'tab'=>'general', 'type'=>'text', 'help'=>LAN_VSTORE_ADMIN_PREF_CAPTION_H,'writeParms'=>array('placeholder'=>'Vstore'),'multilan'=>true),
			'caption_categories'        => array('title'=> LAN_VSTORE_ADMIN_PREF_CAPTION_CAT, 'tab'=>'general', 'type'=>'text', 'writeParms'=>array('placeholder'=>LAN_VSTORE_ADMIN_PREF_CAPTION_CAT_PH),'multilan'=>true),
			'caption_outofstock'        => array('title'=> LAN_VSTORE_ADMIN_PREF_CAPTION_STOCK, 'tab'=>'general', 'type'=>'text', 'writeParms'=>array('placeholder'=>LAN_VSTORE_ADMIN_PREF_CAPTION_STOCK_PH),'multilan'=>true),

			'currency'		            => array('title'=> LAN_VSTORE_ADMIN_PREF_CURRENCY, 'tab'=>'general', 'type'=>'dropdown', 'data' => 'string','help'=>LAN_VSTORE_ADMIN_PREF_CURRENCY_H),
			'amount_format'	            => array('title'=> LAN_VSTORE_ADMIN_PREF_AMOUNT_FORMAT, 'tab'=>'general', 'type'=>'dropdown', 'data' => 'string','help'=>LAN_VSTORE_ADMIN_PREF_AMOUNT_FORMAT_H),
			'weight_unit'		        => array('title'=> LAN_VSTORE_ADMIN_PREF_WEIGHT_UNIT, 'tab'=>'general', 'type'=>'dropdown', 'data' => 'string','help'=>LAN_VSTORE_ADMIN_PREF_WEIGHT_UNIT_H),
			'customer_userclass'        => array('title'=> LAN_VSTORE_ADMIN_PREF_USERCLASS, 'tab'=>'general', 'type' => 'method', 'help' => LAN_VSTORE_ADMIN_PREF_USERCLASS_H),
			'show_outofstock'     		=> array('title'=> LAN_VSTORE_ADMIN_PREF_SHOW_STOCK, 'tab'=>'general', 'type' => 'bool', 'help' => LAN_VSTORE_ADMIN_PREF_SHOW_STOCK_H, 'writeParms' => array('enabled' => LAN_SHOW, 'disabled' => LAN_VSTORE_ADMIN_C_HIDE)),
			
			'shipping'		            => array('title'=> LAN_VSTORE_ADMIN_PREF_SHIPPING, 'tab'=>'ship', 'type'=>'bool', 'data' => 'int','help'=>LAN_VSTORE_ADMIN_PREF_SHIPPING_H, 'writeParms' => array('label' => 'yesno')),
			'shipping_method'	        => array('title'=> LAN_VSTORE_ADMIN_PREF_SHIP_METHOD, 'tab'=>'ship', 'type'=>'dropdown', 'data' => 'string', 'help'=>LAN_VSTORE_ADMIN_PREF_SHIP_METHOD_H, 'writeParms' => array('size'=>'large')),
			'shipping_unit'	        	=> array('title'=> LAN_VSTORE_ADMIN_PREF_SHIP_UNIT, 'tab'=>'ship', 'type'=>false, 'data' => 'string'),
			'shipping_limit'        	=> array('title'=> LAN_VSTORE_ADMIN_PREF_SHIP_LIMIT, 'tab'=>'ship', 'type'=>false, 'data' => 'string'),
			'shipping_data'				=> array('title'=> LAN_VSTORE_ADMIN_PREF_SHIP_DATA, 'tab'=>'ship', 'type'=>'method', 'data' => 'array', 'writeParms'=>array('trClass'=>'shipping-tiered-row'), 'help'=>LAN_VSTORE_ADMIN_PREF_SHIP_DATA_H ),

			'sender_name'               => array('title'=> LAN_VSTORE_ADMIN_PREF_SENDER_NAME, 'tab'=>'email', 'type'=>'text', 'writeParms'=>array('placeholder'=>LAN_VSTORE_ADMIN_PREF_SENDER_NAME_PH), 'help'=>LAN_VSTORE_ADMIN_PREF_SENDER_H,'multilan'=>false),
			'sender_email'              => array('title'=> LAN_EMAIL, 'tab'=>'email', 'type'=>'text', 'writeParms'=>array('placeholder'=>LAN_VSTORE_ADMIN_PREF_SENDER_EMAIL_PH), 'help'=>LAN_VSTORE_ADMIN_PREF_SENDER_H, 'multilan'=>false),
			'merchant_info'             => array('title'=> LAN_VSTORE_ADMIN_PREF_MERCHANT, 'tab'=>'email', 'type'=>'textarea', 'writeParms'=>array('placeholder'=>LAN_VSTORE_ADMIN_PREF_MERCHANT_PH), 'help'=>LAN_VSTORE_ADMIN_PREF_MERCHANT_H, 'multilan'=>false),
			
			'howtoorder'	            => array('title'=> LAN_VSTORE_ADMIN_PREF_HOWTO, 'tab'=>'howto', 'type'=>'bbarea', 'help'=>LAN_VSTORE_ADMIN_PREF_HOWTO_H),

			'admin_items_perpage'	    => array('title'=> LAN_VSTORE_ADMIN_PREF_ITEMS_PERPAGE, 'tab'=>'admin', 'type'=>'number', 'help'=>''),
			'admin_categories_perpage'	=> array('title'=> LAN_VSTORE_ADMIN_PREF_CATS_PERPAGE, 'tab'=>'admin', 'type'=>'number', 'help'=>''),

			'additional_fields'         => array('title'=>LAN_VSTORE_ADMIN_PREF_ADDITIONAL, 'tab'=>'checkout', 'type'=>'method', 'data'=>'array'),
			
			'custom_css'	            => array('title'=> LAN_VSTORE_ADMIN_PREF_CSS, 'tab'=>'css', 'type' => 'textarea', 'data' => 'str', 'width' => '100%', 'readParms' => array(), 'writeParms' => array('cols'=> 80, 'rows' => 10, 'size'=>'block-level'), 'help'=>LAN_VSTORE_ADMIN_PREF_CSS_H),

			'tax_calculate'	            => array('title'=> LAN_VSTORE_ADMIN_PREF_TAX_CALC, 'tab'=>'tax', 'type'=>'bool', 'data' => 'int','help'=>LAN_VSTORE_ADMIN_PREF_TAX_CALC_H, 'writeParms' => array('label' => 'yesno')),
			'tax_business_country'		=> array('title'=> LAN_VSTORE_ADMIN_PREF_TAX_COUNTRY, 'tab'=>'tax', 'type'=>'country', 'data' => 'string', 'help'=>LAN_VSTORE_ADMIN_PREF_TAX_COUNTRY_H, 'writeParms' => array()),
		//	'tax_include_in_price'		=> array('title'=> 'Include tax in display price', 'tab'=>'tax', 'type'=>'bool', 'data' => 'string', 'help'=>'When enabled, the price displayed will include tax.', 'writeParms' => array()),

			'tax_check_vat'	            => array('title'=> LAN_VSTORE_ADMIN_PREF_TAX_VAT, 'tab'=>'tax', 'type'=>'bool', 'data' => 'int','help'=>LAN_VSTORE_ADMIN_PREF_TAX_VAT_H, 'writeParms' => array('label' => 'yesno')),
			'tax_origin_vat'			    => array('title'=> LAN_VSTORE_ADMIN_PREF_TAX_ORIGIN, 'tab'=>'tax', 'type'=>'bool', 'data' => 'int','help'=>LAN_VSTORE_ADMIN_PREF_TAX_ORIGIN_H, 'writeParms' => array('label' => 'yesno')),
			'tax_classes'				=> array('title'=> LAN_VSTORE_ADMIN_PREF_TAX_CLASSES, 'tab'=>'tax', 'type'=>'method', 'data' => 'array', 'help'=>LAN_VSTORE_ADMIN_PREF_TAX_CLASSES_H, 'writeParms' => array()),
			
			'menu_cat'				    => array('title'=> LAN_VSTORE_ADMIN_PREF_MENU_CAT, 'tab'=>'menu', 'type'=>'dropdown', 'data' => 'int', 'help'=>'', 'writeParms' => array()),
			'menu_item_count'		    => array('title'=> LAN_VSTORE_ADMIN_PREF_MENU_COUNT, 'tab'=>'menu', 'type'=>'number', 'data' => 'int', 'help'=>LAN_VSTORE_ADMIN_PREF_MENU_COUNT_H, 'writeParms' => array('decimals' => 0,'default' => 2)),
		);



		// optional
		public function init()
		{



			//$this->prefs['currency']['writeParms'] = array('USD'=>'US Dollars', 'EUR'=>'Euros', 'CAN'=>'Canadian Dollars', 'GBP'=>'GB Pounds');

			$currencies = vstore::getCurrencies();
			foreach($currencies as $k => $v)
			{
				$this->prefs['currency']['writeParms'][$k] = $v['title'];
			}
			
			$this->prefs['amount_format']['writeParms'] = array('0'=>LAN_VSTORE_ADMIN_PREF_AMOUNT_BEFORE, '1'=>LAN_VSTORE_ADMIN_PREF_AMOUNT_BEHIND);

			$this->prefs['weight_unit']['writeParms'] = vstore::weightUnits();

			$this->prefs['shipping_method']['writeParms']['size'] = 'xxlarge';

			$this->prefs['shipping_method']['writeParms']['optArray'] = array(
				'sum_simple'	=> LAN_VSTORE_ADMIN_PREF_SHIP_SUM_SIMPLE,
				'sum_unique'	=> LAN_VSTORE_ADMIN_PREF_SHIP_SUM_UNIQUE,
				'tiered'		=> LAN_VSTORE_ADMIN_PREF_SHIP_TIERED,
			);

			// Get all active product categories 
			$this->prefs['menu_cat']['writeParms']['optArray'] = array();
			if ($data = e107::getDb()->retrieve('vstore_cat', 'cat_id,cat_name', 'ORDER BY cat_parent, cat_name', true))
			{
				foreach($data as $row)
				{
					$this->prefs['menu_cat']['writeParms']['optArray'][$row['cat_id']] = $row['cat_name'];
				}
			}

			if (!isset($this->prefs['show_outofstock'])) {
				// new pref... set default value
				$this->prefs['show_outofstock'] = 1;
			}

		}

		public function beforePrefsSave($new_data, $old_data)
		{
			// Fix the shipping data array
			if (isset($new_data['shipping_data']))
			{
				$sd = $new_data['shipping_data'];
				if (!is_array($sd))
				{
					$sd = e107::unserialize($sd);
				}
	
				unset($sd['%ROW%']);
	
				// Make sure that the array is correctly indexed
				$tmp = array();
				foreach ($sd as $key => $value) {
					$tmp[] = array('cost' => floatval($value['cost']), 'unit' => floatval($value['unit']));
				}

				$new_data['shipping_data'] = $tmp;
			}

			// Fix the tax_classes array
			if (isset($new_data['tax_classes']))
			{
				$tc = $new_data['tax_classes'];
				if (!is_array($tc))
				{
					$tc = e107::unserialize($tc);
				}

				if (count($tc) == 0)
				{
					$tc = self::$taxClassesDefault;
				}
				$defaultKeys = array_column(self::$taxClassesDefault, 'name');
				// Make sure that the array is correctly indexed
				$tmp = array();
				$used = array();
				foreach ($tc as $key => $value) {
					if (empty($value['name'])) 
					{
						continue;
					}
					if ($key < count($defaultKeys) && $value['name'] != $defaultKeys[$key])
					{
						$forceSave = true;
						$value['name'] = $defaultKeys[$key];
						e107::getMessage()->addWarning(LAN_VSTORE_ADMIN_PREF_TAX_ORDER_WARNING);
					}
					if (in_array($value['name'], $used))
					{
						continue;
					}
					$used[] = $value['name'];

					$tmp[] = array(
						'name' => strtolower(trim(strip_tags($value['name']))), 
						'description' => trim(strip_tags($value['description'])), 
						'value' => floatval($value['value']));
				}

				$new_data['tax_classes'] = $tmp;

			}

			// Saving in one language must not erase the texts typed in the others
			if (isset($new_data['additional_fields']))
			{
				$stored = (array) varset($old_data['additional_fields'], array());
				$fields = is_array($new_data['additional_fields']) ? $new_data['additional_fields'] : e107::unserialize($new_data['additional_fields']);

				foreach ($fields as $i => $field)
				{
					foreach (array('caption', 'placeholder') as $name)
					{
						$fields[$i][$name] = \Vstore\Support\Translatable::merge(varset($stored[$i][$name]), varset($field[$name]));
					}
				}

				$new_data['additional_fields'] = $fields;
			}

			return $new_data;			
			
		}

		
		// public function customPage()
		// {
		// 	$ns = e107::getRender();
		// 	$text = 'Hello World!';
		// 	$ns->tablerender('Hello',$text);	
			
		// }
	
			
}


class vstore_pref_form_ui extends e_admin_form_ui
{
	// private static $taxClassesDefault = array(
	// 	0 => array('name'=>'none', 		'description'=>'No tax', 		'value'=>'0.00'),
	// 	1 => array('name'=>'reduced', 	'description'=>'Reduced tax', 	'value'=>'0.00'),
	// 	2 => array('name'=>'standard', 	'description'=>'Standard tax', 	'value'=>'0.00'),
	// );

	public function init()
	{
			$shipMethod = !empty($_POST['shipping_method']) ? filter_var($_POST['shipping_method']) : e107::pref('vstore', 'shipping_method');

			if($shipMethod !== 'tiered')
			{
				e107::css('inline', "
				
					tr.shipping-tiered-row { display: none } 
				
				");
			}


		$d = e107::unserialize(e107::pref('vstore','shipping_data'));
		$max = 0;

		if(!empty($d))
		{
			$max = (int) max(array_keys($d));
		}

		$max++;

		$js = "
		var rowcount = $max;
		$(function(){
			$('.vstore-shipping-add').click(function(){
				rowcount++;
				var row = $('#vstore-shipping-data-template').html();
				
				row = row.replace(new RegExp('%ROW%', 'g'), rowcount);
				row = row.replace(new RegExp('xxx>', 'g'), 'td>');
				row = '<tr>' + row + '</tr>';
				var new_item = $(row).hide();
				$('#vstore-shipping-data').append(new_item);
				new_item.show('slow');

			});

			$('body').on('click', '.vstore-shipping-remove', function(){
				var rows = $('#vstore-shipping-data tr').length;
				if (rows > 2)
				{
					var row = $(this).parent().parent();
					row.hide('slow', function () {
						$(this).remove();
					});

				}
			});
			
			$('#shipping-method').on('change', function(){
			
				var opt = $('#shipping-method option:selected' ).val(); 
				if(opt === 'tiered')
				{
					$('.shipping-tiered-row' ).show('slow'); 
					
				}
				else
				{
					$('.shipping-tiered-row' ).hide('slow'); 
				}

			});
			
			
		});
		";
		e107::js('footer-inline', $js);

	}


	function shipping_data($curVal, $mode)
	{

		if(!empty($curVal) )
		{
			$curVal = e107::unserialize($curVal); // a BC precaution for anyone who still has a json pref.
		}
		elseif(!is_array($curVal))
		{
			$curVal = array();
		}

		//	'shipping_unit'	        	=> array('title'=> 'Value based on', 'tab'=>'ship', 'type'=>'dropdown', 'data' => 'string', 'help'=>'Define which value (subtotal or weight) will be used to calculate shipping costs.', 'writeParms' => array('money'=>'Cart subtotal', 'weight'=>'Cart total weight')),
// 			'shipping_limit'        	=> array('title'=> 'Cost are', 'tab'=>'ship', 'type'=>'dropdown', 'data' => 'string', 'help'=>'Define if the shipping cost are fixed to the spcified cost or limited to that value', 'writeParms' => array('fixed'=>'Fixed shipping costs', 'max'=>'Up to (max.) shipping costs')),
		$pref = e107::pref('vstore');

		$unitDropdown = $this->select('shipping_unit', ['price'=>LAN_VSTORE_ADMIN_PREF_SHIP_UNIT_PRICE, 'weight'=>LAN_VSTORE_ADMIN_PREF_SHIP_UNIT_WEIGHT], varset($pref['shipping_unit']), ['size'=>'block-level']);

		$limitDropdown = $this->select('shipping_limit', ['fixed'=>LAN_VSTORE_ADMIN_PREF_SHIP_LIMIT_FIXED, 'max'=>LAN_VSTORE_ADMIN_PREF_SHIP_LIMIT_MAX], varset($pref['shipping_limit']), ['size'=>'block-level']);


		$text = '
		<div>
		<table class="table table-bordered table-condensed" id="vstore-shipping-data" style="margin-bottom:5px; width: 800px">
		<colgroup>
			<col style="width: calc(50% - 25px)" />
			<col style="width: calc(50% - 25px)" />
			<col style="width: 50px" />
		</colgroup>
		<tr>
			<td>'.$unitDropdown.'</td>
			<td>'.$limitDropdown.'</td>
			<td> </td>
		</tr>
		';

		unset($curVal['%ROW%']);

		$i = 0;
		if (count($curVal) == 0)
		{
			$text .= '
			<tr>
				<td>'.$this->text('shipping_data['.$i.'][unit]', '0.00', 8, array('pattern' => '^(\d+(\.\d{1,2}){0,1})$', 'min' => '0', 'size'=>'block-level')).'</td>
				<td>'.$this->text('shipping_data['.$i.'][cost]', '0.00', 8, array('pattern' => '^(\d+(\.\d{1,2}){0,1})$', 'min' => '0', 'size'=>'block-level')).'</td>
				<td>&nbsp;</td>
			</tr>
			';
			
		}
		else
		{
			foreach ($curVal as $x => $val)
			{
				$text .= '
				<tr>
					<td>'.$this->text('shipping_data['.$i.'][unit]', number_format($val['unit'], 2, '.',''), 8, array('pattern' => '^(\d+(\.\d{1,2}){0,1})$', 'min' => '0', 'size'=>'block-level')).'</td>
					<td>'.$this->text('shipping_data['.$i.'][cost]', number_format($val['cost'], 2, '.',''), 8, array('pattern' => '^(\d+(\.\d{1,2}){0,1})$', 'min' => '0', 'size'=>'block-level')).'</td>
					<td>';

				$text .= ($i !== 0) ? '<button class="vstore-shipping-remove btn  btn-danger" type="button"><i class="fa fa-times"></i> </button>' : '&nbsp;';
				$text .= '</td>
				</tr>
				';
				$i++;
			}
		}
		
		$text .= '
		</table>
		</div>

		<button class="vstore-shipping-add btn btn-sm btn-primary" type="button"><i class="fa fa-plus"></i> '.LAN_ADD.'</button>
		';

		$text .= '
		<div id="vstore-shipping-data-template" style="display:none;">
			<xxx>'.$this->text('shipping_data[%ROW%][unit]', '0.00', 8, array('pattern' => '^(\d+(\.\d{1,2}){0,1})$', 'min' => '0', 'size'=>'block-level')).'</xxx>
			<xxx>'.$this->text('shipping_data[%ROW%][cost]', '0.00', 8, array('pattern' => '^(\d+(\.\d{1,2}){0,1})$', 'min' => '0', 'size'=>'block-level')).'</xxx>
			<xxx><button class="vstore-shipping-remove btn btn-danger" type="button"><i class="fa fa-times"></i> </button></xxx>
		</div>
		';



		return $text;

	}


	function additional_fields($curVal,$mode)
	{
		
		if(!is_array($curVal))
		{
			$curVal = e107::unserialize($curVal);
		}

		$tmp = range(0,3);

		$text = "<table class='table table-striped table-bordered'>
			<colgroup>
				<col style='width:100px' />
				<col style='width:auto' />
				<col style='width:auto' />
				<col style='width:10%' />
				<col style='width:100px' />
			</colgroup>";

			$opts = array('text'=>LAN_VSTORE_ADMIN_PREF_ADD_TEXTBOX,'checkbox'=> LAN_VSTORE_ADMIN_PREF_ADD_CHECKBOX);

		$text .= "
			<tr>
				<td>".LAN_ACTIVE."</td>
				<td>".LAN_CAPTION."</td>
				<td>".LAN_VSTORE_ADMIN_PREF_ADD_PLACEHOLDER."</td>
				<td>".LAN_VSTORE_ADMIN_PREF_ADD_FIELDTYPE."</td>
				<td>".LAN_VSTORE_ADMIN_PREF_ADD_REQUIRED."</td>
			</tr>
		";
			
		foreach($tmp as $i)
		{

			$activeVal       = !empty($curVal[$i]['active']) ? $curVal[$i]['active'] : null;
			$capVal          = !empty($curVal[$i]['caption'][e_LANGUAGE]) ? $curVal[$i]['caption'][e_LANGUAGE] : null;
			$placeholderVal  = !empty($curVal[$i]['placeholder'][e_LANGUAGE]) ? $curVal[$i]['placeholder'][e_LANGUAGE] : null;
			$reqVal          = !empty($curVal[$i]['required']) ? $curVal[$i]['required'] : null;
			$typeVal         = !empty($curVal[$i]['type']) ? $curVal[$i]['type'] : 'text';

			$post = '<small class="input-group-addon"><i class="fa fa-language"><!-- --></i></small>';

			$text .= "
				<tr>
					<td>".$this->flipswitch('additional_fields['.$i.'][active]', $activeVal, null, array('switch'=>'small', 'title' => LAN_ACTIVE))."</td>
					<td><span class='input-group'>".$this->text('additional_fields['.$i.'][caption]['.e_LANGUAGE.']', $capVal, 250, array('placeholder'=>\Vstore\Support\Translatable::pick(varset($curVal[$i]['caption']), e_LANGUAGE, LAN_CAPTION), 'size'=>'block-level')).$post."</span></td>
					<td><span class='input-group'>".$this->text('additional_fields['.$i.'][placeholder]['.e_LANGUAGE.']',$placeholderVal, 100, array('placeholder'=>\Vstore\Support\Translatable::pick(varset($curVal[$i]['placeholder']), e_LANGUAGE, LAN_VSTORE_ADMIN_PREF_ADD_PLACEHOLDER), 'size'=>'block-level')).$post."</span></td>
					<td>".$this->select('additional_fields['.$i.'][type]', $opts, $typeVal )."</td>
					<td>".$this->flipswitch('additional_fields['.$i.'][required]', $reqVal, null, array('switch'=>'small', 'title' => LAN_VSTORE_ADMIN_PREF_ADD_REQUIRED))."</td>
				</tr>
			";

		}

		$text .= "</table>";

		return $text;
	}


	function customer_userclass($curVal, $mode)
	{
		$items = e107::getUserClass()->getClassList('nobody,member,classes');
		$items = array('-1' => LAN_VSTORE_ADMIN_PREF_USERCLASS_PRODUCT) + $items;
		return $this->select('customer_userclass', $items, $curVal);
		
	}


	function tax_classes($curVal,$mode)
	{
		switch($mode)
		{
			case 'read': // List Page
				return $curVal;
			break;

			case 'write': // Edit Page

				if(!empty($curVal))
				{
					$cur = e107::unserialize($curVal); // a BC precaution for anyone who still has a json pref.
				}
				else
				{
					$cur = vstore_pref_ui::$taxClassesDefault;
				}

				foreach(vstore::getTaxClasses() as $v)
				{
					$tax_classes[$v] = $v; 
				}



				$text = '
					
					<div class="tax-classes-container">';

					foreach($cur as $i=>$v)
					{
						// Default class names are readonly!
						$readonly = in_array($v['name'], array('none', 'reduced', 'standard'));
						$text .= '	
							<div class="form-inline tax-classes-row" style="margin-bottom:5px">'.
							$this->select('tax_classes['.$i.'][name]', $tax_classes, $v['name'], array('id'=>null, 'size'=>'medium', 'placeholder'=>LAN_VSTORE_ADMIN_PREF_TAX_NAME, 'readonly' => $readonly)).
							" ".$this->text('tax_classes['.$i.'][description]', $v['description'], 150, array('id'=>null, 'size'=>'large', 'placeholder'=>LAN_VSTORE_ADMIN_PREF_TAX_DESCRIPTION)).
							" ".$this->text('tax_classes['.$i.'][value]', $v['value'], 6, array('id'=>null, 'size'=>'small', 'placeholder'=> LAN_VSTORE_ADMIN_PREF_TAX_VALUE, 'pattern' => '^0\.?[0-9]{0,4}$')).
							" ".$this->button('tax-remove', '1', 'action', "<i class='fa fa-times'></i> ", array('class'=>'btn btn-danger vstore-tax-remove'.($readonly ? ' hidden invisible' : '')))
							.'</div>';

					}

					$text .= '
					</div>
										
				';

				$text .= $this->button('clonetaxclass',1,'action', "<i class='fa fa-plus'></i> ".LAN_ADD, array('class'=>'btn btn-primary btn-sm'));

				e107::js('footer-inline', "
				
				
					var taxRowCount = $('.tax-classes-row').length;
					$('#clonetaxclass').on('click', function()
					{
						var row = $('.tax-classes-row:first').clone();
						//var rowCount = $('.tax-classes-row').length;
						taxRowCount++;
						
						//row.find('select').prop('readonly','');

						row.html(row.html().replace(/readonly=\"readonly\"/g,''));
						row.html(row.html().replace(new RegExp('value=\"[^\"]*\"', 'g'),'value=\"\"'));
						// fix empty option value
						row.html(row.html().replace(new RegExp('option value=\"\"', 'g'),'option'));
						row.html(row.html().replace(/\[0\]/g,'[' + taxRowCount + ']'));
						row.html(row.html().replace(/hidden/g,''));
						row.html(row.html().replace(/invisible/g,''));
					
						row.css('display', 'none');
						
						$('.tax-classes-container').append(row);
						row.show('slow');									
							
					});
				

					$('body').on('click', '.vstore-tax-remove', function(){
						var row = $(this).parent();
						
						row.hide('slow', function () {
							$(this).remove();
						});

					});
							
				");



				return $text;
			break;

			case 'filter':
			case 'batch':
				return  array();
			break;
		}
	}

}		


