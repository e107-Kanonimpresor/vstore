<?php
/**
 * Vstore - shopping cart plugin for e107.
 * Based on e107inc/vstore (CaMerOn, Achim Ennenbach, e107 Inc). Modified by Kanonimpresor, 2025-2026.
 *
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.html
 */
e107::lan('vstore', 'admin_customer', true);

/**
 * Adminarea module customers
 */
class vstore_customer_ui extends e_admin_ui
{
	use vstore_admin_common;

			
		protected $pluginTitle		= 'Vstore';
		protected $pluginName		= 'vstore';
		protected $table			= 'vstore_customer';
		protected $pid				= 'cust_id';
		protected $perPage			= 10; 
		protected $batchDelete		= true;
	//	protected $batchCopy		= true;		
	//	protected $sortField		= 'somefield_order';
	//	protected $orderStep		= 10;
		protected $tabs			= array(LAN_VSTORE_ADMIN_CUST_TAB_BILLING, LAN_VSTORE_ADMIN_CUST_TAB_SHIPPING, LAN_VSTORE_ADMIN_CUST_TAB_ADDITIONAL, LAN_VSTORE_ADMIN_CUST_TAB_NOTES); // Use 'tab'=>0  OR 'tab'=>1 in the $fields below to enable. 
		
	//	protected $listQry      	= "SELECT * FROM #tableName WHERE field != '' "; // Example Custom Query. LEFT JOINS allowed. Should be without any Order or Limit.
	
		protected $listOrder		= 'cust_id DESC';
	
		protected $fields 		= array (  'checkboxes' =>   array ( 'title' => '', 'type' => null, 'data' => null, 'width' => '5%', 'thclass' => 'center', 'forced' => '1', 'class' => 'center', 'toggle' => 'e-multiselect',  ),
		  'cust_id' =>   array ( 'title' => LAN_ID, 'tab' => 0, 'data' => 'int', 'width' => '5%', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		  'cust_refcode' =>   array ( 'title' => LAN_VSTORE_ADMIN_CUST_REFCODE, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_e107_user' =>   array ( 'title' => LAN_VSTORE_ADMIN_C_USER, 'tab' => 0, 'type' => 'method', 'data' => 'int', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center', 'readonly'=>!true ),
		  'cust_datestamp' =>   array ( 'title' => LAN_VSTORE_ADMIN_CUST_LASTUPDATE, 'tab' => 0, 'type' => 'datestamp', 'data' => 'int', 'width' => 'auto', 'filter' => true, 'readonly' => true, 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		  'cust_title' =>   array ( 'title' => LAN_TITLE, 'tab' => 0, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'inline' => true, 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		  'cust_firstname' =>   array ( 'title' => LAN_VSTORE_ADMIN_C_FIRSTNAME, 'tab' => 0, 'type' => 'method', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_lastname' =>   array ( 'title' => LAN_VSTORE_ADMIN_C_LASTNAME, 'tab' => 0, 'type' => 'method', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_company' =>   array ( 'title' => LAN_VSTORE_ADMIN_C_COMPANY, 'tab' => 0, 'type' => 'method', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_vat_id' =>   array ( 'title' => LAN_VSTORE_ADMIN_C_VAT_ID, 'tab' => 0, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'help' => LAN_VSTORE_ADMIN_CUST_VATID_H, 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_taxcode' =>   array ( 'title' => LAN_VSTORE_ADMIN_C_TAXCODE, 'tab' => 0, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_address' =>   array ( 'title' => LAN_VSTORE_ADMIN_C_ADDRESS, 'tab' => 0, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_city' =>   array ( 'title' => LAN_VSTORE_ADMIN_C_CITY, 'tab' => 0, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_state' =>   array ( 'title' => LAN_VSTORE_ADMIN_C_STATE, 'tab' => 0, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_zip' =>   array ( 'title' => LAN_VSTORE_ADMIN_C_ZIP, 'tab' => 0, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_country' =>   array ( 'title' => LAN_VSTORE_ADMIN_C_COUNTRY, 'tab' => 0, 'type' => 'method', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_email' =>   array ( 'title' => LAN_EMAIL, 'tab' => 0, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_phone' =>   array ( 'title' => LAN_VSTORE_ADMIN_C_PHONE, 'tab' => 0, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_fax' =>   array ( 'title' => LAN_VSTORE_ADMIN_C_FAX, 'tab' => 0, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_use_shipping' =>   array ( 'title' => LAN_VSTORE_ADMIN_CUST_USE_SHIPPING, 'tab' => 1, 'type' => 'boolean', 'data' => 'int', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center'),
		  'cust_shipping' =>   array ( 'title' => LAN_VSTORE_ADMIN_CUST_LAST_SHIPPING, 'tab' => 1, 'type' => 'method', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center', 'readonly'=>true),
		  'cust_additional_fields' =>   array ( 'title' => LAN_VSTORE_ADMIN_CUST_ADDITIONAL, 'tab' => 2, 'type' => 'method', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center', 'readonly'=>true),
		  'cust_notes' =>   array ( 'title' => LAN_VSTORE_ADMIN_CUST_NOTES, 'tab' => 3, 'type' => 'bbarea', 'data' => 'str', 'width' => 'auto', 'help' => LAN_VSTORE_ADMIN_CUST_NOTES_H, 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'options' =>   array ( 'title' => LAN_OPTIONS, 'type' => null, 'data' => null, 'width' => '10%', 'thclass' => 'center last', 'class' => 'center last', 'forced' => '1',  ),
		);		
		
		protected $fieldpref = array('cust_refcode', 'cust_e107_user', 'cust_firstname', 'cust_lastname');
		

	
	/*		
		public function customPage()
		{
			$ns = e107::getRender();
			$text = 'Hello World!';
			$ns->tablerender('Hello',$text);	
			
		}
	*/
			
}
				


class vstore_customer_form_ui extends e_admin_form_ui
{


	
	// Custom Method/Function 
	function cust_e107_user($curVal, $mode)
	{
		switch($mode)
		{
			case 'read': // List Page
				return $curVal;
			break;
			
			case 'write': // Edit Page
				$u = e107::getDb()->retrieve('user', 'user_name, user_loginname', 'user_id='.intval($curVal));
				$text = intval($curVal) . ') <span title="'.LAN_VSTORE_ADMIN_CUST_LOGINNAME.'">' . htmlspecialchars((string) varset($u['user_loginname']), ENT_QUOTES, 'UTF-8') . '</span> <span title="'.LAN_VSTORE_ADMIN_CUST_USERNAME.'">('. htmlspecialchars((string) varset($u['user_name']), ENT_QUOTES, 'UTF-8') . ')</span>';
				return $text;
			break;
				
			case 'filter':
			case 'batch':
				return  null;
			break;
		}

		return null;

	}

	
	// Custom Method/Function 
	function cust_firstname($curVal,$mode)
	{
		switch($mode)
		{
			case 'read': // List Page
				return htmlspecialchars((string) $curVal, ENT_QUOTES, 'UTF-8');
			break;
			
			case 'write': // Edit Page
				return $this->text('cust_firstname',$curVal);
			break;
			
			case 'filter':
			case 'batch':
				return  null;
			break;
		}

		return null;
	}

	
	// Custom Method/Function 
	function cust_lastname($curVal,$mode)
	{
		switch($mode)
		{
			case 'read': // List Page
				return htmlspecialchars((string) $curVal, ENT_QUOTES, 'UTF-8');
			break;
			
			case 'write': // Edit Page
				return $this->text('cust_lastname',$curVal);
			break;
			
			case 'filter':
			case 'batch':
				return  null;
			break;
		}

		return null;
	}

	
	// Custom Method/Function 
	function cust_company($curVal,$mode)
	{
		switch($mode)
		{
			case 'read': // List Page
				return htmlspecialchars((string) $curVal, ENT_QUOTES, 'UTF-8');
			break;
			
			case 'write': // Edit Page
				return $this->text('cust_company',$curVal);
			break;
			
			case 'filter':
			case 'batch':
				return  null;
			break;
		}

		return null;
	}

	
	// Custom Method/Function 
	function cust_country($curVal,$mode)
	{
		switch($mode)
		{
			case 'read': // List Page

				return $this->getCountry($curVal);
			break;
			
			case 'write': // Edit Page
				return $this->country('cust_country',$curVal);

			break;
			
			case 'filter':
			case 'batch':
				return  null;
			break;
		}

		return null;
	}

	function cust_shipping($curVal, $mode)
	{

		$val = e107::unserialize($curVal);

		if (count($val) == 0) return LAN_VSTORE_ADMIN_CUST_NO_SHIPPING;

$h = function ($key) use ($val)
                {
                        return htmlspecialchars(is_scalar(varset($val[$key])) ? (string) varset($val[$key]) : '', ENT_QUOTES, 'UTF-8');
                };

                return $h('firstname') . ' ' . $h('lastname').'<br />'
                .$h('company').'<br />'
                .$h('address').'<br />'
                .$h('city') . ', ' . $h('state') . ' ' . $h('zip').'<br />'
                .(empty($val['country']) ? '' : $this->getCountry($val['country']) . '<br />')
                .$h('phone').'<br />'
                .LAN_VSTORE_ADMIN_CUST_SHIP_NOTES.'<br />'
                .$h('notes');

	}

	function cust_additional_fields($curVal,$mode)
	{
		
		$val = e107::unserialize($curVal);

		if (count($val) == 0) return LAN_VSTORE_ADMIN_CUST_NO_ADDITIONAL;

		$text = '';
		foreach ($val as $k => $v) {
                    $text .= sprintf('%s (%s): %s<br/>', htmlspecialchars((string) $v['caption'], ENT_QUOTES, 'UTF-8'), htmlspecialchars((string) $k, ENT_QUOTES, 'UTF-8'), htmlspecialchars((string) $v['value'], ENT_QUOTES, 'UTF-8'));
		}

		return $text;

	}
	
}		
