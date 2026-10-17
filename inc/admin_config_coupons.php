<?php
/**
 * Vstore - shopping cart plugin for e107.
 * Based on e107inc/vstore (CaMerOn, Achim Ennenbach, e107 Inc). Modified by Kanonimpresor, 2025-2026.
 *
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.html
 */
e107::lan('vstore', 'admin_coupons', true);

/**
 * Adminarea module coupons
 */
class vstore_coupons_ui extends e_admin_ui
{
	use vstore_admin_common;


		protected $pluginTitle		= 'Vstore';
		protected $pluginName		= 'vstore';
	//	protected $eventName		= 'vstore-vstore_items_vars'; // remove comment to enable event triggers in admin.
		protected $table			= 'vstore_coupons';
		protected $pid				= 'coupon_id';
		protected $perPage			= 10;
		protected $batchDelete		= true;
		protected $batchExport      = true;
		protected $batchCopy		= true;

	//	protected $sortField		= 'somefield_order';
	//	protected $sortParent      = 'somefield_parent';
	//	protected $treePrefix      = 'somefield_title';

		protected $tabs				= array(LAN_GENERAL, LAN_VSTORE_ADMIN_COUP_TAB_RESTRICTIONS, LAN_VSTORE_ADMIN_COUP_TAB_LIMITS); // Use 'tab'=>0  OR 'tab'=>1 in the $fields below to enable.

	//	protected $listQry      	= "SELECT * FROM `#tableName` WHERE field != '' "; // Example Custom Query. LEFT JOINS allowed. Should be without any Order or Limit.

		protected $listOrder		= 'coupon_id DESC';

		protected $fields 		= 	array (  'checkboxes' =>   array ( 'title' => '', 'type' => null, 'data' => null, 'width' => '5%', 'thclass' => 'center', 'forced' => '1', 'class' => 'center', 'toggle' => 'e-multiselect',  ),
		  'coupon_id'         	=>   array ( 'title' => LAN_ID, 'data' => 'int', 'width' => '5%', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		  'coupon_active' 		=>   array ( 'title' => LAN_ACTIVE, 'tab' => 0, 'type'=>'boolean', 'data' => 'int', 'inline'=>true, 'width' => '5%', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		  'coupon_code'       	=>   array ( 'title' => LAN_VSTORE_ADMIN_COUP_CODE, 'tab' => 0, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'inline' => false, 'help' => LAN_VSTORE_ADMIN_COUP_CODE_H, 'readParms' => '', 'writeParms'  => array('placeholder' => LAN_VSTORE_ADMIN_COUP_CODE_PH, 'size'=>'xxlarge', 'required' => 1, 'pattern'=>'[A-Z0-9]*'), 'class' => 'left', 'thclass' => 'left',  ),
		  'coupon_type'     	=>   array ( 'title' => LAN_VSTORE_ADMIN_COUP_TYPE, 'tab' => 0, 'type' => 'dropdown', 'data' => 'str', 'width' => 'auto', 'inline' => false, 'help' => LAN_VSTORE_ADMIN_COUP_TYPE_H, 'readParms' => '', 'writeParms'  => array('%' => LAN_VSTORE_ADMIN_COUP_TYPE_PERCENT, 'F' => LAN_VSTORE_ADMIN_COUP_TYPE_FIXED/* cart', 'I' => 'Fixed item'*/), 'class' => 'left', 'thclass' => 'left',  ),
		  'coupon_amount'     	=>   array ( 'title' => LAN_VSTORE_ADMIN_COUP_AMOUNT, 'tab' => 0, 'type' => 'method', 'data' => 'float', 'width' => 'auto', 'inline' => false, 'help' => LAN_VSTORE_ADMIN_COUP_AMOUNT_H, 'readParms' => '', 'writeParms'  => '', 'class' => 'left', 'thclass' => 'left',  ),
		  'coupon_start'     	=>   array ( 'title' => LAN_VSTORE_ADMIN_COUP_START, 'tab' => 1, 'type' => 'datestamp', 'data' => 'int', 'inline' => false, 'help' => LAN_VSTORE_ADMIN_COUP_START_H, 'readParms' => '', 'writeParms'  => array('type'=>'datetime'), 'class' => 'left', 'thclass' => 'left',  ),
		  'coupon_end'     		=>   array ( 'title' => LAN_VSTORE_ADMIN_COUP_END, 'tab' => 1, 'type' => 'datestamp', 'data' => 'int', 'width' => 'auto', 'inline' => false, 'help' => LAN_VSTORE_ADMIN_COUP_END_H, 'readParms' => '', 'writeParms'  => array('type'=>'datetime'), 'class' => 'left', 'thclass' => 'left',  ),
	//	  'coupon_items'     	=>   array ( 'title' => 'Items', 'tab' => 1, 'type' => 'method', 'data' => 'str', 'width' => 'auto', 'inline' => false, 'help' => 'Items this coupon will make use of.', 'readParms' => '', 'writeParms'  => '', 'class' => 'left', 'thclass' => 'left',  ),
	//	  'coupon_items_ex'    	=>   array ( 'title' => 'Exclude items', 'tab' => 1, 'type' => 'method', 'data' => 'str', 'width' => 'auto', 'inline' => false, 'help' => 'Items this coupon will never make use of', 'readParms' => '', 'writeParms'  => '', 'class' => 'left', 'thclass' => 'left',  ),
	// todo Add a field to each item. "Eligible for coupons"
		  'coupon_cats'     	=>   array ( 'title' => LAN_VSTORE_ADMIN_COUP_CATS, 'tab' => 1, 'type' => 'method', 'data' => 'str', 'width' => 'auto', 'inline' => false, 'help' => LAN_VSTORE_ADMIN_COUP_CATS_H, 'readParms' => '', 'writeParms'  => '', 'class' => 'left', 'thclass' => 'left',  ),
		  'coupon_cats_ex'     	=>   array ( 'title' => LAN_VSTORE_ADMIN_COUP_CATS_EX, 'tab' => 1, 'type' => 'method', 'data' => 'str', 'width' => 'auto', 'inline' => false, 'help' => LAN_VSTORE_ADMIN_COUP_CATS_EX_H, 'readParms' => '', 'writeParms'  => '', 'class' => 'left', 'thclass' => 'left',  ),
		  'coupon_limit_coupon'	=>   array ( 'title' => LAN_VSTORE_ADMIN_COUP_LIMIT_COUPON, 'tab' => 2, 'type' => 'method', 'data' => 'str', 'width' => 'auto', 'inline' => false, 'help' => LAN_VSTORE_ADMIN_COUP_LIMIT_COUPON_H, 'readParms' => '', 'writeParms'  => '', 'class' => 'left', 'thclass' => 'left',  ),
		  'coupon_limit_user'	=>   array ( 'title' => LAN_VSTORE_ADMIN_COUP_LIMIT_USER, 'tab' => 2, 'type' => 'method', 'data' => 'str', 'width' => 'auto', 'inline' => false, 'help' => LAN_VSTORE_ADMIN_COUP_LIMIT_USER_H, 'readParms' => '', 'writeParms'  => '', 'class' => 'left', 'thclass' => 'left',  ),
		  'coupon_limit_item'	=>   array ( 'title' => LAN_VSTORE_ADMIN_COUP_LIMIT_ITEM, 'tab' => 2, 'type' => 'method', 'data' => 'str', 'width' => 'auto', 'inline' => false, 'help' => LAN_VSTORE_ADMIN_COUP_LIMIT_ITEM_H, 'readParms' => '', 'writeParms'  => '', 'class' => 'left', 'thclass' => 'left',  ),
	
		  'options'             =>   array ( 'title' => LAN_OPTIONS, 'type' => null, 'data' => null, 'width' => '10%', 'thclass' => 'center last', 'class' => 'center last', 'forced' => '1',  ),
		);

		protected $fieldpref = array('coupon_active', 'coupon_code', 'coupon_operator', 'coupon_value', 'coupon_start', 'coupon_end');


		protected $prefs = array(
		);

		public function init()
		{
			// Set drop-down values (if any).

		}


		// ------- Customize Create --------

		public function beforeCreate($new_data,$old_data)
		{
			if (trim($new_data['coupon_code']) == '') 
			{
				e107::getMessage()->addError(LAN_VSTORE_ADMIN_COUP_ERR_INVALID);
				return false;
			}
			$new_data['coupon_code'] = strtoupper(str_replace(' ', '-', trim($new_data['coupon_code'])));

			if(e107::getDb()->select('vstore_coupons', 'coupon_id', 'coupon_code = "'.e107::getDb()->escape($new_data['coupon_code']).'"'))
			{
				e107::getMessage()->addError(LAN_VSTORE_ADMIN_COUP_ERR_EXISTS);
				return false;
			}

		//	$new_data['coupon_items'] = implode(',', $new_data['coupon_items']);
		//	$new_data['coupon_items_ex'] = implode(',', $new_data['coupon_items_ex']);
			$new_data['coupon_cats'] = implode(',', $new_data['coupon_cats']);
			$new_data['coupon_cats_ex'] = implode(',', $new_data['coupon_cats_ex']);

			return $new_data;
		}

		public function afterCreate($new_data, $old_data, $id)
		{
			// do something
		}

		public function onCreateError($new_data, $old_data)
		{
			// do something
		}


		// ------- Customize Update --------

		public function beforeUpdate($new_data, $old_data, $id)
		{
			if (array_key_exists('coupon_code', $new_data))
			{
				if (trim($new_data['coupon_code']) == '') 
				{
					e107::getMessage()->addError(LAN_VSTORE_ADMIN_COUP_ERR_INVALID);
					return false;
				}
				$new_data['coupon_code'] = strtoupper(str_replace(' ', '-', trim($new_data['coupon_code'])));

				if(e107::getDb()->select('vstore_coupons', 'coupon_id', 'coupon_code = "'.e107::getDb()->escape($new_data['coupon_code']).'" AND coupon_id != '.(int) $old_data['coupon_id']))
				{
					e107::getMessage()->addError(LAN_VSTORE_ADMIN_COUP_ERR_EXISTS);
					return false;
				}
			}

		//	var_dump($new_data);

		//	$new_data['coupon_items'] = implode(',', $new_data['coupon_items']);
		//	$new_data['coupon_items_ex'] = implode(',', $new_data['coupon_items_ex']);
			$new_data['coupon_cats'] = implode(',', $new_data['coupon_cats']);
			$new_data['coupon_cats_ex'] = implode(',', $new_data['coupon_cats_ex']);

			return $new_data;
		}

		public function afterUpdate($new_data, $old_data, $id)
		{
			// do something
		}

		public function onUpdateError($new_data, $old_data, $id)
		{
			// do something
		}
}

class vstore_coupons_form_ui extends e_admin_form_ui
{
	/**
	 * Create a number field with step, min, max and required attributes
	 *
	 * @param string $name
	 * @param double $val
	 * @param double $default
	 * @param double $min
	 * @param double $max
	 * @param integer $decimals
	 * @param boolean $required
	 * @return string
	 */
	function number_field($name, $val, $default=0, $min=0, $max=null, $decimals=0, $required=false)
	{
		$options = '';

		$options .= " step='" . ($decimals > 0 ? '0.' . str_repeat('0', ($decimals - 1)) : '') . "1'";
		if (isset($min))
		{
			$options .= " min='" . $min . "'";
		}
		if (isset($max))
		{
			$options .= " max='" . $min . "'";
		}
		if (vartrue($required))
		{
			$options .= " required='required'";
		}

		if (!is_numeric($val))
		{
			$val = $default;
		}

		$text = $this->text($name, $val, 10);

		$text = str_replace("type='text'", "type='number'", $text);
		if ($options != '')
		{
			$text = str_replace('/>', $options.'/>', $text);
		}

		return $text;
	}

	function coupon_amount($curVal, $mode)
	{
		return $this->number_field(__FUNCTION__, $curVal, 0, 0, null, 2, true);
	}

	function coupon_limit_coupon($curVal, $mode)
	{
		return $this->number_field(__FUNCTION__, $curVal, -1, -1, null, 0, true);
	}

	function coupon_limit_item($curVal, $mode)
	{
		return $this->number_field(__FUNCTION__, $curVal, -1, -1, null, 0, true);
	}

	function coupon_limit_user($curVal, $mode)
	{
		return $this->number_field(__FUNCTION__, $curVal, -1, -1, null, 0, true);
	}

	/**
	 * Itemss to include (will override cats that are in coupon_items_ex)
	 *
	 * @param [type] $curVal
	 * @param [type] $mode
	 * @return void
	 */
	function coupon_items($curVal, $mode)
	{
		switch($mode)
		{
			case 'read': // List Page
				return $curVal;
			break;
			
			case 'write': // Edit Page
				$opt_array = array();
				if($data = e107::getDb()->retrieve('SELECT item_id, item_name FROM #vstore_items ORDER BY item_name', true))
				{
					foreach($data as $k=>$v)
					{
						$key = $v['item_id'];
						$opt_array[$key] = $v['item_name'];
					}
				}

				$text = $this->select('coupon_items', $opt_array, $curVal, array('multiple'=>1));
				return $text; 		
			break;
			
			case 'filter':
			case 'batch':
				return  null;
			break;
		}
	}

	/**
	 * Items to exclude
	 *
	 * @param [type] $curVal
	 * @param [type] $mode
	 * @return void
	 */
	function coupon_items_ex($curVal, $mode)
	{
		switch($mode)
		{
			case 'read': // List Page
				return $curVal;
			break;
			
			case 'write': // Edit Page
				$opt_array = array();
				if($data = e107::getDb()->retrieve('SELECT item_id, item_name FROM #vstore_items ORDER BY item_name', true))
				{
					foreach($data as $k=>$v)
					{
						$key = $v['item_id'];
						$opt_array[$key] = $v['item_name'];
					}
				}

				$text = $this->select('coupon_items_ex', $opt_array, $curVal, array('multiple'=>1));
				return $text; 		
			break;
			
			case 'filter':
			case 'batch':
				return  null;
			break;
		}
	}

	/**
	 * Categories to include (will override cats that are in coupon_cats_ex)
	 *
	 * @param [type] $curVal
	 * @param [type] $mode
	 * @return void
	 */
	function coupon_cats($curVal, $mode)
	{
		switch($mode)
		{
			case 'read': // List Page
				return $curVal;
			break;
			
			case 'write': // Edit Page
				$opt_array = array();
				if($data = e107::getDb()->retrieve('SELECT cat_id, cat_name FROM #vstore_cat ORDER BY cat_name', true))
				{
					foreach($data as $k=>$v)
					{
						$key = $v['cat_id'];
						$opt_array[$key] = $v['cat_name'];
					}
				}

				$text = $this->select('coupon_cats', $opt_array, $curVal, array('multiple'=>1));
				return $text; 		
			break;
			
			case 'filter':
			case 'batch':
				return  null;
			break;
		}
	}

	/**
	 * Categories to exclude
	 *
	 * @param [type] $curVal
	 * @param [type] $mode
	 * @return void
	 */
	function coupon_cats_ex($curVal, $mode)
	{
		switch($mode)
		{
			case 'read': // List Page
				return $curVal;
			break;
			
			case 'write': // Edit Page
				$opt_array = array();
				if($data = e107::getDb()->retrieve('SELECT cat_id, cat_name FROM #vstore_cat ORDER BY cat_name', true))
				{
					foreach($data as $k=>$v)
					{
						$key = $v['cat_id'];
						$opt_array[$key] = $v['cat_name'];
					}
				}

				$text = $this->select('coupon_cats_ex', $opt_array, $curVal, array('multiple'=>1));
				return $text; 		
			break;
			
			case 'filter':
			case 'batch':
				return  null;
			break;
		}
	}
}

