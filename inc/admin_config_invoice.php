<?php
e107::lan('vstore', 'admin_invoice', true);

/**
 * Adminarea module invoice settings
 */
class vstore_invoice_pref_ui extends e_admin_ui
{
	use vstore_admin_common;

			
		protected $pluginTitle		= 'Vstore';
		protected $pluginName		= 'vstore';


		// optional
		protected $preftabs = array('pref'=>LAN_PREFS, 'temp'=>LAN_TEMPLATE, 'prev'=>LAN_PREVIEW);


		protected $prefs = array(
			'invoice_create_pdf'   		=> array('title'=> LAN_VSTORE_ADMIN_INV_CREATE_PDF, 'tab'=>'pref', 'type'=>'boolean', 'data' => 'int', 'writeParms'=>array('default'=>'0'),'help' => LAN_VSTORE_ADMIN_INV_CREATE_PDF_H),
			'invoice_title'        		=> array('title'=> LAN_VSTORE_ADMIN_INV_TITLE, 'tab'=>'pref', 'type'=>'text', 'data' => 'str', 'writeParms'=>array('placeholder'=>LAN_VSTORE_ADMIN_INV_TITLE, 'default'=>LAN_VSTORE_ADMIN_INV_TITLE_DEF),'multilan'=>true, 'help' => LAN_VSTORE_ADMIN_INV_TITLE_H),
			'invoice_info_title'        => array('title'=> LAN_VSTORE_ADMIN_INV_INFO, 'tab'=>'pref', 'type'=>'text', 'data' => 'str', 'writeParms'=>array('placeholder'=>LAN_VSTORE_ADMIN_INV_INFO_PH, 'default'=>LAN_VSTORE_ADMIN_INV_INFO_DEF),'multilan'=>true, 'help' => LAN_VSTORE_ADMIN_INV_INFO_H),
			'invoice_subject'        	=> array('title'=> LAN_VSTORE_ADMIN_INV_SUBJECT, 'tab'=>'pref', 'type'=>'text', 'data' => 'str', 'writeParms'=>array('size'=>'block-level', 'placeholder'=>LAN_VSTORE_ADMIN_INV_SUBJECT, 'default'=>LAN_VSTORE_ADMIN_INV_SUBJECT_DEF),'multilan'=>true, 'help'=>LAN_VSTORE_ADMIN_INV_SUBJECT_H),
			'invoice_nr_prefix'  		=> array('title'=> LAN_VSTORE_ADMIN_INV_PREFIX, 'tab'=>'pref', 'type'=>'text', 'data' => 'str', 'writeParms'=>array('placeholder'=>'IN', 'default'=>'IN', 'maxlength' => '5'), 'help' => LAN_VSTORE_ADMIN_INV_PREFIX_H),
			'invoice_next_nr'  			=> array('title'=> LAN_VSTORE_ADMIN_INV_NEXT_NR, 'tab'=>'pref', 'type'=>'method', 'data' => 'int', 'writeParms'=>array('default'=>'1'), 'help' => LAN_VSTORE_ADMIN_INV_NEXT_NR_H),
			'invoice_date_format'		=> array('title'=> LAN_VSTORE_ADMIN_INV_DATE_FORMAT, 'tab'=>'pref', 'type'=>'dropdown', 'data' => 'str', 'writeParms'=>array('placeholder'=>LAN_VSTORE_ADMIN_INV_DATE_FORMAT_PH, /*'default'=>'%m/%d/%Y'*/), 'help' => LAN_VSTORE_ADMIN_INV_DATE_FORMAT_H),
			'invoice_payment_deadline'  => array('title'=> LAN_VSTORE_ADMIN_INV_DEADLINE, 'tab'=>'pref', 'type'=>'number', 'data' => 'int', 'writeParms'=>array('default'=>'7'), 'help' => LAN_VSTORE_ADMIN_INV_DEADLINE_H),
			'invoice_hint'        		=> array('title'=> LAN_VSTORE_ADMIN_INV_HINT, 'tab'=>'temp', 'type' =>'method', 'data' => 'str', 'help' => LAN_VSTORE_ADMIN_INV_HINT_H),
			'invoice_finish_phrase'		=> array('title'=> LAN_VSTORE_ADMIN_INV_FINISH, 'tab'=>'temp', 'type' =>'method', 'data' => 'str', 'help' => LAN_VSTORE_ADMIN_INV_FINISH_H, 'writeParms' => array('placeholder' => LAN_VSTORE_ADMIN_INV_FINISH_PH, 'default' => LAN_VSTORE_ADMIN_INV_FINISH_DEF)),
			'invoice_footer'        	=> array('title'=> LAN_VSTORE_ADMIN_INV_FOOTER, 'tab'=>'temp', 'type'=>'method', 'data' => 'json', 'writeParms'=>'', 'help' => LAN_VSTORE_ADMIN_INV_FOOTER_H),
			'invoice_preview'        	=> array('title'=> LAN_PREVIEW, 'tab'=>'prev', 'type'=>'method', 'data' => false, 'writeParms'=>array('nolabel'=>true), 'help' => ''),

			// 'invoice_template'         => array('title'=> "Invoice template", 'type'=>'method', 'tab' => 1, 'data' => 'str'),
		);



		// optional
		public function init()
		{

			if($this->getAction() === 'preview')
			{
				echo $this->previewPage();
				exit;
			}

			$dateFormats = array(
				 'dd M, yy',
				 'dd M, yyyy',
				 'dd MM, yy',
				 'dd MM, yyyy',
				 'MM dd, yyyy',
				 'yyyy-mm-dd',
				 '%m/%d/%Y'
			);

			$opts = [];
			$tp = e107::getParser();

			foreach($dateFormats as $format)
			{
				$opts[$format] = $tp->toDate(time(), $format);
			}

			$this->prefs['invoice_date_format']['writeParms']['optArray'] = $opts;


		}


		public function previewPage()
		{
			/** @var vstore $vc */
			$vc = e107::getSingleton('vstore', e_PLUGIN.'vstore/vstore.class.php');
			$vc->initPrefs();

			$country = e107::pref('vstore', 'tax_business_country');
			$rate = vstore::getTaxRate('standard', $country);
			$tax = ($rate * 125);
			$shipping = 5.00;
			$total = 125 + $tax + $shipping;


				$data = array(
					'order_id' => '1',
					'order_date' => '1615056941',
					'order_session' => '2edaa69d49daff8c93ae911215c97f22',
					'order_e107_user' => '1',
					'order_cust_id' => '0',
					'order_status' => 'N',
					'order_items' => '[
			    {
			        "id": "1",
			        "name": "ITEM1",
			        "price": "50.00",
			        "description": "Product One",
			        "quantity": "2",
			        "tax_rate": '.$rate.',
			        "file": "0",
			        "vars": "",
			        "item_vars": ""
			    },
			    {
			        "id": "2",
			        "name": "ITEM2",
			        "price": "25.00",
			        "description": "Product Two",
			        "quantity": "1",
			        "tax_rate": '.$rate.',
			        "file": "",
			        "vars": "",
			        "item_vars": ""
			    }
			]',
				'order_refcode' => 'JOSM000001',
				'order_invoice_nr' => '1',
				'order_billing' => '{
			    "firstname": "John",
			    "lastname": "Smith",
			    "company": "Company Inc.",
			    "address": "123 customer ave.",
			    "city": "My City",
			    "state": "State",
			    "zip": "54321",
			    "country": "'.$country.'",
			    "email": "customer1234@email.com",
			    "phone": "+351912345678",
			    "additional_fields": null
			}',
			'order_use_shipping' => '0',
			'order_shipping' => '{
			    "firstname": "Fred",
			    "lastname": "Jones",
			    "address": "333 shipping ave.",
			    "city": "Shipcity",
			    "state": "State",
			    "zip": "12345",
			    "country": "us"
			}',
			'order_pay_gateway' => 'bank_transfer',
			'order_pay_status' => 'refunded',
			'order_pay_transid' => NULL,
			'order_pay_amount' => $total,
			'order_pay_tax' => '{
			    "'.$rate.'": '.$tax.'
			}',
			'order_pay_shipping' => $shipping,
			'order_pay_currency' => 'USD',
			'order_pay_coupon_code' => '',
			'order_pay_coupon_amount' => '0.00',
			'order_pay_rawdata' => '{
			   "purchase": {
			        "purchase": null
			    },
			    "refund": {
			        "Refunded": "Order refunded on 2021-03-06 18:55:53 by e107-cli (1)"
			    }
			}',
				'order_refund_date' => NULL,
				'order_log' => ''
			);

			$vc->order->setData($data);

			$eml = array(
				'subject'		=> 'Test Subject',
				'body' 			=> $vc->renderInvoiceTemplate(),
				'template'		=> 'blank',
			);

			return e107::getEmail()->preview($eml);

		}

		/**
		 * User defined before pref saving logic
		 * @param $new_data
		 * @param $old_data
		 */
		public function beforePrefsSave($new_data, $old_data)
		{
			// Has the invoice_create_pdf setting be changed?
			if (vartrue($new_data['invoice_create_pdf'])
				&& !vstore::checkPdfPlugin(false)
			) {
				// TCPDF not available: reset setting
				$new_data['invoice_create_pdf'] = 0;
				e107::getMessage()->addInfo(LAN_VSTORE_ADMIN_INV_PDF_DISABLED);
			}
			return $new_data;
		}

		/**
		 * User defined before pref saving logic
		 */
		public function afterPrefsSave()
		{

		}

		
		// public function customPage()
		// {
		// 	$ns = e107::getRender();
		// 	$text = 'Hello World!';
		// 	$ns->tablerender('Hello',$text);	
			
		// }
	
			
}


class vstore_invoice_pref_form_ui extends e_admin_form_ui
{

	public function init()
	{
		if (empty($_POST)) {
			// Just opened the prefs page...
			// check if the TCPDF library is available
			vstore::checkPdfPlugin();
		}

        $js = "
		$(function(){
			$('.vstore-invoice-reset').click(function(){
				var template = decodeURIComponent($(this).data('template'));

				var id = 'invoice-template';
				$('#'+id).val(template);
			});
		});
		";
        e107::js('footer-inline', $js);
        
	}

	function invoice_preview($curVal, $mode)
	{
	//	$text = '<iframe src="'.e_SELF.'?mode=invoice&action=preview&iframe=1" style="border:0; width:800px;height: 95vh " ></iframe>';
		$text = "<iframe src='".e_SELF."?mode=invoice&action=preview&id=default' width='100%' height='700'>".LAN_VSTORE_ADMIN_INV_LOADING."</iframe>";

		return $text;
	}

	function invoice_template($curVal, $mode)
	{
		$orig_templates = e107::getTemplate('vstore', 'vstore_invoice');
		$orig_templates = $orig_templates['default'];

		$text = '
		<div class="row">
			<div class="form-group">
				<div class="text-right col-6 col-xs-6">
					'.$this->button('', '<span class="fa fa-undo"></span> '. LAN_VSTORE_ADMIN_INV_RESET, 'action', '', array('data-template' => rawurlencode($orig_templates), 'class' => 'vstore-invoice-reset pull-right btn-sm', 'title' => LAN_VSTORE_ADMIN_INV_RESET_H)).'
				</div>
			</div>
		</div>
		<div class="row">
			'.$this->textarea('invoice_template', $curVal, 5, 80, array('size' => 'block-level')).'
		</div>
		';

		return $text;
		 		
	}


	function invoice_hint($curVal, $mode)
	{
		return $this->textarea('invoice_hint['.e_LANGUAGE.']', (!empty($curVal[e_LANGUAGE]) ? $curVal[e_LANGUAGE] : ''), 5, 80, array('size' => 'block-level'));
	}

	function invoice_finish_phrase($curVal, $mode)
	{
		return $this->textarea('invoice_finish_phrase', (!empty($curVal) ? $curVal : ''), 6, 80, array('size' => 'block-level'));
	}


	function invoice_footer($curVal, $mode)
	{
		if (empty($curVal))
		{
			$curVal = array(
				0 => e107::pref('vstore', 'merchant_info'),
				1 => LAN_VSTORE_ADMIN_INV_FOOTER_DEF1,
				2 => LAN_VSTORE_ADMIN_INV_FOOTER_DEF2,
				3 => LAN_VSTORE_ADMIN_INV_FOOTER_DEF3
			);
		}

		if ($curVal && !is_array($curVal))
		{
			$curVal = e107::unserialize($curVal);
		}

		$tab = array();

		for($i=0; $i < 4; $i++)
		{
			$tab[$i] = array(
				'caption'   => sprintf(LAN_VSTORE_ADMIN_INV_FOOTER_N, $i),
				'text'      => $this->textarea('invoice_footer['.$i.']', $curVal[$i], 6, 80, array('size'=>'block-level'), 'small')
			);
		}

		return $this->tabs($tab);

	}



	function invoice_next_nr($curVal, $mode)
	{
		$next = vstore::getNextInvoiceNr();
		if ($curVal < $next)
		{
			$curVal = $next;
		}

		return $this->number('invoice_next_nr', $curVal);

	}

}		

