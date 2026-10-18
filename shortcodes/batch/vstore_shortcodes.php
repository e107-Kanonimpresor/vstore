<?php
/**
 * Vstore - shopping cart plugin for e107.
 * Based on e107inc/vstore (CaMerOn, Achim Ennenbach, e107 Inc). Modified by Kanonimpresor, 2025-2026.
 *
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.html
 */



	class plugin_vstore_vstore_shortcodes extends e_shortcode
	{

		protected $vpref = array();
		protected $videos = array();
		//protected $symbols = array();
		protected $curSymbol;
		protected $currency;
		protected $displayCurrency = false;
		protected $categories = array();
		public $captionOutOfStock; // 'Out of Stock';
		protected $halt = false;
		protected $tp;
		protected $locale_decimals;
		protected $locale_thousands;

		public function __construct()
		{

			e107::lan('vstore', false, true);
			e107::lan('vstore', 'front_shop', true);
			e107::lan('vstore', 'front_msg', true);
			e107::lan('vstore', 'front_dashboard', true);

			$this->captionOutOfStock = defset("LAN_VSTORE_003", "Out of Stock");
			$this->vpref             = e107::pref('vstore');
			$currency                = !empty($this->vpref['currency']) ? $this->vpref['currency'] : 'USD';
			$this->curSymbol         = vstore::getCurrencySymbol($currency);
			$this->currency          = ($this->displayCurrency === true) ? $currency : '';
			$this->tp                = e107::getParser();

			// get the locale settings for decimal point and thousands separator
			// will be used a lot by format_amount()
			$lc = localeconv();
			$this->locale_decimals = vartrue($lc['decimal_point'], '.');
			$this->locale_thousands = vartrue($lc['thousands_sep'], ',');
		}

		public function getCurrencySymbol()
		{
			return vstore::getCurrencySymbol($this->currency);
		}

		function format_amount($amount)
		{
			$format = (int) varset($this->vpref['amount_format'], 0);
			$amount = (float) $this->tp->toNumber($amount);
			// Format number according locale settings
			// depends on defined language
			$value = number_format($amount, 2, $this->locale_decimals, $this->locale_thousands);
			if ($format == 1) {
				return $value . '&nbsp;' . $this->curSymbol;
			}

			return $this->curSymbol . '&nbsp;' . $value;
		}

		function format_address($address, $extended = false)
		{
			if (empty($address)) return '';
			if (!is_array($address)) $address = e107::unserialize($address);
			if (empty($address)) return '';

			$h = array();
			foreach ($address as $k => $v)
			{
				$h[$k] = is_scalar($v) ? htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8') : '';
			}
			$address = $h;

			$text = varset($address['firstname']) . ' ' . varset($address['lastname']). '<br />' .
				(!empty($address['company']) ? $address['company'].'<br />' : '').
				$address['address'] .'<br />'.
				$address['city'] . ' ' . $address['zip'] .'<br />'.
				e107::getForm()->getCountry($address['country']);

			if ($extended)
			{
				$text .= '<br />' .
					(!empty($address['vat_id']) ? LAN_VSTORE_FR_VAT_ID . ': ' . $address['vat_id'] . '<br />' : '').
					(!empty($address['taxcode']) ? LAN_VSTORE_FR_TAXCODE . ': ' . $address['taxcode'] . '<br />' : '').
					(!empty($address['email']) ? LAN_VSTORE_FR_EMAIL . ': ' . $address['email'] . '<br />' : '').
					(!empty($address['phone']) ? LAN_VSTORE_FR_PHONE . ': ' . $address['phone'] . '<br />' : '').
					(!empty($address['fax']) ? LAN_VSTORE_FR_FAX . ': ' . $address['fax'] : '');
			}

			return $text;
		}

		/** Url of the invoice of the current order, or '' when its status does not allow an invoice. */
		protected function getInvoiceUrl()
		{
			if (empty($this->var['order_invoice_nr']) || !vstore::validInvoiceOrderState(varset($this->var['order_status'], '')))
			{
				return '';
			}

			return e107::url('vstore', 'invoice', array('order_invoice_nr' => $this->var['order_invoice_nr']));
		}

		function sc_order_actions($parm=null)
		{
			if (!USER) return '';
			$key = '';

			if (!empty($parm))
			{
				$key = array_keys($parm);
				if ($key) $key = strtolower($key[0]);
			}

			$cancellable = in_array($this->var['order_status'], array('N', 'P', 'H', 'Q'));
			$invoiceUrl = $this->getInvoiceUrl();

			if (!empty($key))
			{
				$text = '';

				if ($key == 'cancel' && $cancellable)
				{
					$text = sprintf('<a href="%s" class="btn btn-warning">%s</a>',
						e107::url('vstore', 'dashboard_action', array('dash' => 'orders', 'action' => 'cancel', 'id' => vstore::orderViewId($this->var))),
						LAN_VSTORE_DB_CANCEL);
				}
				elseif ($key == 'invoice' && $invoiceUrl)
				{
					$text = sprintf('<a href="%s" class="btn btn-primary" target="_blank" rel="noopener">%s %s</a>',
						$invoiceUrl,
						$this->tp->toGlyph('fa-file-pdf-o'),
						LAN_VSTORE_DB_INVOICE);
				}

				return $text;
			}

			$actions = array(
				sprintf('<a href="%s">%s</a>',
					e107::url('vstore', 'dashboard_action', array('dash' => 'orders', 'action' => 'view', 'id' => vstore::orderViewId($this->var))),
					LAN_VSTORE_DB_VIEW)
			);

			if ($invoiceUrl)
			{
				$actions[] = sprintf('<a href="%s" target="_blank" rel="noopener">%s</a>', $invoiceUrl, LAN_VSTORE_DB_INVOICE);
			}

			if ($cancellable)
			{
				$actions[] = sprintf('<a href="%s">%s</a>',
					e107::url('vstore', 'dashboard_action', array('dash' => 'orders', 'action' => 'cancel', 'id' => vstore::orderViewId($this->var))),
					LAN_VSTORE_DB_CANCEL);
			}

			return e107::getForm()->button('order_actions', $actions, 'dropdown', LAN_VSTORE_DB_ACTIONS, array('class' => 'btn-default btn-secondary'));
		}

		/**
		 * Alias for {ORDER_DATA: cust_xxxx }
		 * @param $parm
		 * @return string
		 */
		function sc_billing($parm)
		{
			$key = key($parm);
			return $this->sc_order_data(array('cust_'.$key => 1));
		}


		/**
		 * Alias for {ORDER_DATA: ship_xxxx }
		 * @param $parm
		 * @return string
		 */
		function sc_shipping($parm)
		{
			$key = key($parm);
			return $this->sc_order_data(array('ship_'.$key => 1));
		}

		/**
		 * Alias for {ORDER_DATA: order_ref}
		 * @param $parm
		 * @return string
		 */
		function sc_ref($parm)
		{
			$key = key($parm);
			return $this->sc_order_data(array('order_ref' => 1));
		}


		function sc_order_data($parm = null)
		{

			if(empty($parm))
			{
				return '';
			}

			$key = array_keys($parm);

			if($key)
			{
				$key = strtolower($key[0]);
			}

			$area = '';

			if(strpos($key, 'ship_') === 0 || strpos($key, 'cust_') === 0)
			{
				if(strpos($key, 'ship_') === 0)
				{
					$area = 'order_shipping';
				}
				elseif(strpos($key, 'cust_') === 0)
				{
					$area = 'order_billing';
				}

				if(is_string($this->var[$area]))
				{
					$this->var[$area] = e107::unserialize($this->var[$area]);
				}

				$key = substr($key, 5);
			}


			$frm = e107::getForm();
			$text = '';

			switch($key)
			{
				case 'order_invoice_nr':
					$text = empty($this->var[$key]) ? '-' : vstore::formatInvoiceNr($this->var[$key]);
					break;

				case 'order_invoice_link':
					$text = empty($this->var['order_invoice_nr']) ? '-' : vstore::formatInvoiceNr($this->var['order_invoice_nr']);
					if ($url = $this->getInvoiceUrl())
					{
						$text = '<a href="' . $url . '" target="_blank" rel="noopener">' . $text . ' ' . $this->tp->toGlyph('fa-file-pdf-o') . '</a>';
					}
					break;

				case 'order_date':
					$format = varset($this->vpref['invoice_date_format'], 'dd M, yyyy');
					$text = e107::getParser()->toDate($this->var[$key], $format);
					break;

				case 'country':
					$text = e107::getForm()->getCountry($this->var[$area][$key]);
					break;

				case 'order_gateway':
					$text = vstore::getGatewayTitle($this->var['order_pay_gateway']);
					break;

				case 'order_ref':
					$text = $this->var['order_refcode'];
					break;

				case 'order_pay_status':
					$text = $this->isQuoteOrder() ? '' : (($this->var['order_status'] == 'C' || $this->var['order_pay_status'] == 'complete') ? '<span class="vstore-badge vstore-badge-success">' . LAN_VSTORE_SC_PAID . '</span>' : '<span class="vstore-badge vstore-badge-warning">' . LAN_VSTORE_SC_OPEN . '</span>');
					break;

				case 'order_pay_amount':
					$text = $this->isQuoteOrder() ? '-' : $this->format_amount($this->var['order_pay_amount']);
					break;

				case 'order_status':
					$text = vstore::getStatus($this->var['order_status']);
					break;

				case 'order_status_label':
					$label_classes = array(
						'N' => 'primary',
						'P' => 'info',
						'H' => 'warning',
						'C' => 'success',
						'X' => 'danger',
						'R' => 'muted',
						'Q' => 'primary',
						'M' => 'info',
						'A' => 'success',
						'F' => 'muted',
					);
					$text = '<span class="vstore-badge vstore-badge-'.varset($label_classes[$this->var['order_status']], 'muted').'">' . vstore::getStatus($this->var['order_status']) .'</span>';
					break;

				case 'order_shipping_full':
					$text = $this->format_address($this->var['order_shipping']);
					break;

				case 'order_billing_full':
					$text = $this->format_address($this->var['order_billing']);
					break;

				case 'order_billing_contact':
					$billing = $this->var['order_billing'];
					if (!is_array($billing)) $billing = e107::unserialize($billing);

					$lines = array();
					if (!empty($billing['email'])) $lines[] = LAN_VSTORE_FR_EMAIL . ': ' . htmlspecialchars($billing['email'], ENT_QUOTES, 'UTF-8');
					if (!empty($billing['phone'])) $lines[] = LAN_VSTORE_FR_PHONE . ': ' . htmlspecialchars($billing['phone'], ENT_QUOTES, 'UTF-8');
					$text = implode('<br />', $lines);
					break;

				case 'order_items_short':
					$items = varset($this->var['order_items']);
					if (!is_array($items)) $items = e107::unserialize($items);

					$text = '';
					foreach($items as $item)
					{
						$text .= sprintf("%dx %s<br/>", $item['quantity'], htmlspecialchars((string) $item['description'], ENT_QUOTES, 'UTF-8'));
					}
					break;

				case 'order_log':
					$log = varset($this->var['order_log']);
					if (!is_array($log)) $log = e107::unserialize($log);

					$dt = e107::getDateConvert();
					$text = '<table class="table table-bordered table-striped">
				<tr>
					<th>'.LAN_DATE.'</th>
					<th>'.LAN_USER.'</th>
					<th>'.LAN_MESSAGE.'</th>
				</tr>
				';
					foreach($log as $item)
					{
						$text .= '<tr>
						<td>'.$dt->convert_date($item['datestamp'], 'short').'</td>
						<td>'.htmlspecialchars((string) $item['user_name'], ENT_QUOTES, 'UTF-8').'</td>
						<td>'.htmlspecialchars((string) $item['text'], ENT_QUOTES, 'UTF-8').'</td>
					<tr>
					';
					}
					$text .= '</table>';
					break;

				case 'order_downloads':
					$items = varset($this->var['order_items']);
					if (!is_array($items)) $items = e107::unserialize($items);

					$text = '';
					foreach($items as $item)
					{
						if ($item['id']>0 && varset($item['file']) && isset($this->var['order_status']))
						{
							if ($this->var['order_status'] === 'C' || ($this->var['order_status'] === 'N' && $this->var['order_pay_status'] == 'complete'))
							{
								$linktext = LAN_VSTORE_SC_DOWNLOAD;
							}
							else
							{
								$linktext = LAN_VSTORE_SC_DOWNLOAD_PENDING;
							}
							$text .= '' . sprintf('<div>%s<br/><a href="%s">%s</a></div><br/>',
									htmlspecialchars((string) $item['description'], ENT_QUOTES, 'UTF-8'),
									e107::url('vstore', 'download', array('item_id' => $item['id']), array('mode'=>'full')),
									$linktext
								);
						}
					}
					break;

				default:
					$value = ($area != '') ? varset($this->var[$area][$key]) : varset($this->var[$key]);
					$text = is_scalar($value) ? htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8') : '';
					break;
			}
			return $text;
		}

		function sc_order_date()
		{
			return $this->tp->toDate($this->var['order_date'], 'd MM, YYYY');
		}

		/** Template of the order lines: the inline-styled e-mail variant when set by {ORDER_ITEMS: email}. */
		protected $orderItemsTemplate = null;

		/** Is the current order a quote request (quote statuses or the quote gateway)? */
		protected function isQuoteOrder()
		{
			return \Vstore\Support\Catalog::isQuoteStatus(varset($this->var['order_status']))
				|| varset($this->var['order_pay_gateway']) === \Vstore\Support\Catalog::QUOTE_GATEWAY;
		}

		protected function getOrderItemsTemplate()
		{
			return $this->orderItemsTemplate ?: e107::getTemplate('vstore', 'vstore', 'order_items');
		}

		function sc_order_items($parm = null)
		{
			$items = $this->var['order_items'];
			if (!is_array($items))
			{
				$items = e107::unserialize($items);
			}

			$email = is_array($parm) && isset($parm['email']);
			$quote = $this->isQuoteOrder();

			if ($email)
			{
				$this->orderItemsTemplate = e107::getTemplate('vstore', 'vstore_email', $quote ? 'quote_items' : 'items');
			}
			elseif ($quote)
			{
				$this->orderItemsTemplate = e107::getTemplate('vstore', 'vstore', 'order_items_quote');
			}

			$template = $this->getOrderItemsTemplate();

			$text = $this->tp->parseTemplate($template['header'], true, $this);

			foreach($items as $key=>$item)
			{
				$desc = $item['description'];

				if (!empty($item['vars']))
				{
					$desc .= '<br/>' . $item['vars'];
				}
				if ($item['id'] > 0 && varset($item['file']) && isset($this->var['order_status']))
				{
					if ($this->var['order_status'] === 'C' || ($this->var['order_status'] === 'N' && $this->var['order_pay_status'] == 'complete'))
					{
						$linktext = LAN_VSTORE_SC_DOWNLOAD;
					}
					else
					{
						$linktext = LAN_VSTORE_SC_DOWNLOAD_PENDING;
					}
					$desc .= '<br/><a href="'.e107::url('vstore', 'download', array('item_id' => $item['id']), array('mode'=>'full')).'">'.$linktext.'</a>';
				}

				$item['code'] = $item['name'];
				$item['name'] = $desc;

				$item['item_total'] = ($item['price'] * $item['quantity']);

				$this->addVars(array('item' => $item));
				$text .= $this->tp->parseTemplate($template['row'], true, $this);
			}

			$text .= $this->tp->parseTemplate($template['footer'], true, $this);

			$this->orderItemsTemplate = null;

			return $text;


		}

		/**
		 * Coupon code and amount from cart data (cart_coupon) or from a saved order (order_pay_coupon_*).
		 *
		 * @return array code, amount
		 */
		protected function getCouponData()
		{
			if (!empty($this->var['cart_coupon']['code']))
			{
				return array('code' => $this->var['cart_coupon']['code'], 'amount' => varset($this->var['cart_coupon']['amount'], 0));
			}

			return array(
				'code'   => varset($this->var['order_pay_coupon_code'], ''),
				'amount' => varset($this->var['order_pay_coupon_amount'], 0),
			);
		}

		function sc_order_coupon()
		{
			$coupon = $this->getCouponData();

			if (empty($coupon['code']))
			{
				return '';
			}

			$template = $this->getOrderItemsTemplate();

			$text = $this->tp->parseTemplate($template['coupon'], true, $this);
			return $text;
		}


		function sc_order_tax($parm=null)
		{
			if ($this->var['is_business'] && !$this->var['is_local'])
			{
				return '';
			}
			if (!is_array($this->var['order_pay_tax']))
			{
				$this->var['order_pay_tax'] = e107::unserialize($this->var['order_pay_tax']);
			}
			$template = $this->getOrderItemsTemplate();
			$text = $x = $y = '';
			foreach($this->var['order_pay_tax'] as $tax_rate => $value)
			{
				if ($this->tp->toNumber($tax_rate) <= 0) continue;
				$x .= ($x != '' ? '<br />' : '').($this->tp->toNumber($tax_rate) * 100).'%';
				$y .= ($y != '' ? '<br />' : '').$this->format_amount($value);
			}

			if ($x != '')
			{
				$text .= $this->tp->lanVars($template['tax'], array('x' => $x, 'y' => $y));
			}

			return $text;
		}

		function sc_order_merchant_info($parm=null)
		{
			$info = e107::pref('vstore', 'merchant_info');

			if(empty($info))
			{
				return null;
			}

			if (vartrue($parm))
			{
				$parm = array_keys($parm);
				$parm = $parm[0];

				if ($parm == 'line')
				{
					if (stripos($info, '<br') !== false)
					{
						$info = str_ireplace(array('<br>', '<br/>', '<br />'), ', ', $info);
						$info = str_ireplace(array("\r\n", "\n"), '', $info);
					}
					else
					{
						$info = str_ireplace(array("\r\n", "\n"), ', ', $info);
					}
				}
			}

			return $this->tp->toHTML($info, true);

		}

		function sc_order_payment_instructions()
		{
			if($this->var['order_pay_gateway'] !== 'bank_transfer')
			{
				return null;
			}

			$bankTransfer = e107::pref('vstore','bank_transfer_details');

			return $this->tp->toHTML($bankTransfer,true);

		}

		function sc_order_gateway_title($parm=null)
		{
			$text = vstore::getGatewayTitle($this->var['order_pay_gateway']);
			return $text;
		}

		function sc_order_gateway_icon($parm=null)
		{
			if (!isset($parm['size']))
			{
				$parm['size'] = '2x';
			}

			$text = vstore::getGatewayIcon($this->var['order_pay_gateway'], $parm['size']);

			return $text;
		}

		function sc_sender_name()
		{
			$info = e107::pref('vstore', 'sender_name');

			if(empty($info))
			{
				return $this->tp->toHTML(e107::pref('core', 'siteadmin'), true);;
			}

			return $this->tp->toHTML($info, true);
		}


		function sc_order_checkout_url()
		{
			return e107::url('vstore', 'checkout', 'sef');
		}






		function setCategories($data)
		{
			$this->categories = $data;
		}

		function inStock()
		{
			$inStock = true;
			$itemvars = vstore::filterItemVarsByType(varset($this->var['item_vars']), 1, true);
			if(empty($itemvars)){
				$inStock = empty($this->var['item_inventory']) ? false : ($this->var['item_inventory'] != 0);
			}
			else
			{
				//$itemvars = explode(',', $this->var['item_vars']);
				$inv = e107::unserialize($this->var['item_vars_inventory']);
				if (empty($this->var['item_vars_inventory']))
				{
					$inStock = false;
				}
				elseif(count($itemvars) == 1)
				{
					$varX = array_keys($inv)[0];
					if ((int) $inv[$varX] == 0)
					{
						$inStock = false;
					}
				}
				elseif(count($itemvars) == 2)
				{
					$varX = array_keys($inv)[0];
					$varY = array_keys($inv[$varX])[0];
					if ((int) $inv[$varX][$varY] == 0)
					{
						$inStock = false;
					}
				}
			}
			return $inStock;
		}

		function sc_item_id($parm=null)
		{
			return $this->var['item_id'];
		}

		function sc_item_code($parm=null)
		{
			return $this->var['item_code'];
		}

		function sc_item_name($parm=null)
		{
			return $this->tp->toHTML($this->var['item_name'], true,'TITLE');
		}

		function sc_item_var_string($parm=null)
		{
			return $this->tp->toHTML($this->var['itemvarstring'], true,'BODY');
		}

		function sc_item_description($parm=null)
		{

			$text = $this->var['item_desc'];

			if(!empty($parm['limit']) && !empty($text))
			{
				$text = $this->tp->text_truncate($text,$parm['limit']);
			}

			return $this->tp->toHTML($text, false, 'BODY');
		}

		function sc_item_details($parm=null)
		{
			return $this->tp->toHTML($this->var['item_details'], true,'BODY');
		}


		function sc_item_vars($parm=null)
		{
			$itemid = (int) $this->var['item_id'];
			$stock = empty($this->var['item_vars_inventory'])
				? (isset($this->var['item_inventory']) ? $this->var['item_inventory'] : -1)
				: e107::unserialize($this->var['item_vars_inventory']);

			if (isset($this->var['item_vars']))
			{
				$vars = explode(',', $this->var['item_vars']);
				foreach($vars as $varid)
				{
					e107::js('settings', array('vstore' => array(
							'stock' => array("x{$itemid}-{$varid}" => vstore::isInventoryTrackingVar($varid)
								? $stock
								: (isset($this->var['item_inventory']) ? $this->var['item_inventory'] : -1)))
						)
					);
				}
			}

			$baseprice = $this->tp->toNumber($this->var['item_price']);
			$this->var['item_var_price'] = $baseprice;
			$isQuote = $this->isQuoteItem();

			if (isset($this->var['item_vars']))
			{
				$frm = e107::getForm();
				$sql = e107::getDb();

				if ($sql->select('vstore_items_vars', '*', 'FIND_IN_SET(item_var_id, "'.preg_replace('/[^0-9,]/', '', (string) $this->var['item_vars']).'")'))
				{
					$text = '
					<div id="vstore-item-vars-'.$itemid.'">';
					while($row = $sql->fetch())
					{
						$attributes = e107::unserialize($row['item_var_attributes']);
						$varid = (int) $row['item_var_id'];

						$select = $frm->select_open(
							'item_var[' . $itemid . '][' . $varid . ']',
							array('class' => 'vstore-item-var tbox select form-control', 'data-id' => $itemid, 'data-item' => $varid, 'data-name' => varset($row['item_var_name'], 'foo'), 'required' => vartrue($row['item_var_compulsory']))
						);

						$selected = true;
						foreach($attributes as $var)
						{
							$varname = $var['name'];
							if(!$isQuote && $this->tp->toNumber($var['value']) > 0.0)
							{
								switch($var['operator'])
								{
									case '%':
										if($selected)
										{
											$this->var['item_var_price'] *= ($this->tp->toNumber($var['value']) / 100.0);
										}
										$varname .= ' (+ ' . $this->tp->toNumber($var['value']) . '%)';
										break;
									case '+':
										if($selected)
										{
											$this->var['item_var_price'] += $this->tp->toNumber($var['value']);
										}
										$varname .= ' (+ ' . $this->format_amount($var['value']) . ')';
										break;
									case '-':
										if($selected)
										{
											$this->var['item_var_price'] -= $this->tp->toNumber($var['value']);
										}
										$varname .= ' (- ' . $this->format_amount($var['value']) . ')';
										break;
								}
							}

							$select .= $frm->option(
								$varname,
								$frm->name2id($var['name']),
								$selected,
								array('data-op' => $var['operator'], 'data-val' => $this->tp->toNumber($var['value']), 'data-id' => $varid, 'data-item' => $itemid)
							);
							$selected = false;

						}

						$select .= $frm->select_close();

						$text .= '
						<div>
							<label style="width: 100%;">' . $row['item_var_name'] . '
							' . $select . '
							</label>
							<!-- fix #92: currency symbol used with product variations --> 
							<span class="text-hide visually-hidden" id="vstore-currency-symbol">' . varset($this->vpref['amount_format'], 0) . $this->curSymbol . '</span>
						</div>';
					}

					$text .= '
					</div>';

					return $text;
				}
			}

			return ''; // No item_vars set
		}




		function sc_item_reviews($parm=null)
		{
			// print_a($this->var['item_reviews']);
			$rev = str_replace("\r","",$this->var['item_reviews']);

			$tmp = explode("\n\n",$rev);

			if(empty($tmp))
			{
				return null;
			}

			$text = '';

			foreach($tmp as $val)
			{
				list($review, $by) = explode("--",$val);
				$text .= "<blockquote>".$review."<small>".$by."</small></blockquote>";
			}

			return $text;
			//return $this->tp->toHTML($this->var['item_reviews'], true, 'BODY');
		}

		function sc_item_related($parm=null)
		{

			if(empty($this->var['item_related']))
			{
				return false;
			}
			$row = e107::unserialize($this->var['item_related']);

			//	return print_a($row, true);

			list($table, $chapter) = explode("|", $row['src']);

			$text = '';


			if($table == 'page_chapters')
			{
				if($chp = e107::getDb()->retrieve('page', '*', 'page_chapter ='.(int) $chapter.' AND page_class IN ('.USERCLASS_LIST.') ORDER BY page_order', true))
				{
					$sc = e107::getScBatch('page',null,'cpage');

					$text = "<ul>";
					foreach($chp as $row)
					{
						$sc->setVars($row);

						$text .= $this->tp->parseTemplate("<li>{CPAGELINK}</li>",true,$sc);


					}

					$text .= "</ul>";
				}

			}

			return $text;
		}

		function sc_item_brand($parm=null)
		{
			return $this->tp->toHTML($this->var['cat_name'], true,'TITLE');
		}

		function sc_item_brand_url($parm=null)
		{
			return e107::url('vstore', 'category', array('cat_sef' => $this->var['cat_sef']));
		}

		/** Paths of the pictures of the current product (videos and other files are left out). */
		protected function itemImagePaths()
		{
			$images = array();

			foreach((array) e107::unserialize($this->var['item_pic']) as $i)
			{
				if(!empty($i['path']) && $this->tp->isImage($i['path']))
				{
					$images[] = $i['path'];
				}
			}

			return $images;
		}

		function sc_item_pic($parm=null)
		{
			$index = (!empty($parm['item'])) ? (int) $parm['item'] : 0; // intval($parm);
			$images = $this->itemImagePaths();

			if(!isset($images[$index]))
			{
				// Only the main picture gets a placeholder, a missing thumbnail is simply left out
				return ($index === 0 && !isset($parm['placeholder'])) ? $this->noImage('fa-cube') : '';
			}

			$path = $images[$index];
			unset($parm['placeholder'], $parm['item']);
			$pre = "";
			$post = "";


			if(!empty($parm['link']))
			{
				// The link holds the large picture for the lightbox, data-standard the one shown on the page
				$link = $this->tp->thumbUrl($path, array('w' => 1600));
				unset($parm['link']);
				$pre = "<a href='".$link."' data-standard='".$this->tp->thumbUrl($path, $parm)."' aria-label='".LAN_VSTORE_FR_ENLARGE."'>";
				$post = "</a>";

			}

			return $pre. $this->tp->toImage($path,$parm) . $post;
		}

		/** Placeholder shown where a product or a category has no picture. */
		protected function noImage($icon)
		{
			return '<span class="vstore-noimg" aria-hidden="true">' . $this->tp->toGlyph($icon) . '</span>';
		}

		/**
		 * Thumbnail strip of the product page: only when the product has more than one picture.
		 * Each link holds the large picture (href) and the one shown on the page (data-standard); js/vstore.js swaps and enlarges them.
		 */
		function sc_item_thumbs($parm=null)
		{
			$images = $this->itemImagePaths();

			if(count($images) < 2)
			{
				return '';
			}

			$text = '<div class="vstore-pv-thumbs thumbnails">';

			foreach(array_slice($images, 0, 12) as $path)
			{
				$thumb = $this->tp->toImage($path, array('w' => 200, 'h' => 200, 'crop' => 1, 'class' => 'thumbnail img-fluid'));

				$text .= '<div class="vstore-pv-thumb"><a href="' . $this->tp->thumbUrl($path, array('w' => 1600)) . '" data-standard="' . $this->tp->thumbUrl($path, array()) . '" aria-label="' . LAN_VSTORE_FR_ENLARGE . '">' . $thumb . '</a></div>';
			}

			return $text . '</div>';
		}

		/** Badges for the product cards: quote, out of stock, low stock. */
		function sc_item_badges($parm=null)
		{
			$text = '';

			if($this->isQuoteItem())
			{
				$text .= '<span class="vstore-badge vstore-badge-quote">' . $this->tp->toGlyph('fa-file-text-o') . ' ' . LAN_VSTORE_QUOTE_BUTTON . '</span>';
			}

			if(!$this->inStock())
			{
				$text .= '<span class="vstore-badge vstore-badge-soldout">' . $this->captionOutOfStock . '</span>';
			}
			elseif((int) varset($this->var['item_inventory'], -1) > 0 && (int) $this->var['item_inventory'] <= 5 && !vstore::filterItemVarsByType(varset($this->var['item_vars']), 1, true))
			{
				$text .= '<span class="vstore-badge vstore-badge-low">' . sprintf(LAN_VSTORE_FR_LOW_STOCK, (int) $this->var['item_inventory']) . '</span>';
			}

			return $text;
		}

		/** CSS state classes of a product card. */
		function sc_item_state($parm=null)
		{
			return ($this->isQuoteItem() ? ' is-quote' : '') . ($this->inStock() ? '' : ' is-soldout');
		}

		function sc_item_video($parm=0)
		{
			$index = (int) $parm;
			$ival = e107::unserialize($this->var['item_pic']);

			$videos = array();
			foreach($ival as $i)
			{
				if(substr($i['path'],-8) == '.youtube')
				{
					$videos[] = $i['path'];
				}
			}

			$path = vartrue($videos[$index]);
			return $this->tp->toVideo($path);

		}






		// Categories

		function sc_cat_id($parm=null)
		{
			return $this->var['cat_id'];
		}

		function sc_cat_name($parm=null)
		{
			return $this->tp->toHTML($this->var['cat_name'], true,'TITLE');
		}

		function sc_cat_sef($parm=null)
		{
			return $this->tp->toHTML($this->var['cat_sef'], true,'TITLE');
		}

		function sc_cat_description($parm=null)
		{
			return $this->tp->toHTML($this->var['cat_description'], true, 'BODY');
		}

		function sc_cat_info($parm=null)
		{
			return $this->tp->toHTML($this->var['cat_info'], true,'BODY');
		}

		function sc_cat_image($parm=0)
		{
			return $this->tp->thumbUrl($this->var['cat_image']);
		}

		function sc_cat_pic($parm=null)
		{
			if(empty($this->var['cat_image']))
			{
				return $this->noImage('fa-folder-open');
			}

			return $this->tp->toImage($this->var['cat_image'],$parm);
		}

		/** Number of products of the category: "6 products", or the bare number with {CAT_COUNT: raw=1}. */
		function sc_cat_count($parm=null)
		{
			static $counts = null;

			if($counts === null)
			{
				$counts = array();
				$sql = e107::getDb();
				$sql->gen('SELECT item_cat, COUNT(*) AS items_count FROM #vstore_items WHERE item_active = 1 GROUP BY item_cat');

				while($row = $sql->fetch())
				{
					$counts[(int) $row['item_cat']] = (int) $row['items_count'];
				}
			}

			$n = varset($counts[(int) $this->var['cat_id']], 0);

			if(!empty($parm['raw']))
			{
				return $n;
			}

			return $n == 1 ? LAN_VSTORE_FR_N_PRODUCT_ONE : sprintf(LAN_VSTORE_FR_N_PRODUCTS, $n);
		}

		function sc_cat_url($parm=null)
		{

			$urlData    = $this->var;
			$route      = 'category';

			if($this->var['cat_parent'] != 0 )
			{
				$urlData['subcat_name'] = $this->var['cat_name'];
				$urlData['subcat_sef']  = $this->var['cat_sef'];
				$urlData['subcat_id']   = $this->var['cat_id'];

				$pid    = $this->var['cat_parent'];
				$parent = $this->categories[$pid];

				$urlData['cat_name']    = $parent['cat_name'];
				$urlData['cat_id']      = $parent['cat_id'];
				$urlData['cat_sef']     = $parent['cat_sef'];

				$route = 'subcategory';
			}

			//e107::getDebug()->log($urlData);

			return e107::url('vstore',$route, $urlData);
		}


		function sc_pref_howtoorder()
		{
			return $this->tp->toHTML($this->vpref['howtoorder'],true,'BODY');
		}

		/**
		 * Creates download links to the "attached" media files
		 * This are NOT the purchased files to download!
		 * Just "some" files which will be shown on the product page
		 *
		 * @param integer $parm
		 * @return string
		 */
		function sc_item_files($parm=0)
		{

			if(empty($this->var['item_files']))
			{
				return null;
			}

			$ival = e107::unserialize($this->var['item_files']);

			$id = array();

			foreach($ival as $i)
			{
				if(!empty($i['path']) && !empty($i['id']))
				{
					$id[] = (int) $i['id'];
				}
			}

			if(empty($id))
			{
				return null;
			}


			$qry = 'SELECT media_id,media_name FROM #core_media WHERE media_id IN ('.implode(',',$id).') ORDER BY media_name ';
			$files = e107::getDb()->retrieve($qry,true);

			$text = '<ul>';
			foreach($files as $i)
			{
				$bb = '[file='.$i['media_id'].']'.$i['media_name'].'[/file]';
				$text .= '<li>'.$this->tp->toHTML($bb, true).'</li>';
			}
			$text .= '</ul>';

			return $text;
		}


		function sc_item_price($parm=null)
		{
			$itemid = (int) $this->var['item_id'];

			if($this->isQuoteItem())
			{
				return ' <span class="vstore-item-quote-price vstore-item-quote-' . $itemid . '">' . LAN_VSTORE_QUOTE_PRICE . '</span>';
			}

			if(empty($this->var['item_price']))
			{
				$this->var['item_price'] = 0;
			}

			$baseprice = $price = $this->tp->toNumber($this->var['item_price']);

			// item_var_price only exists once {ITEM_VARS} has priced the selected variation; a menu or a card has none
			if (isset($this->var['item_var_price']))
			{
				$varprice = $this->tp->toNumber($this->var['item_var_price']);

				if ($varprice >= 0 && $varprice != $baseprice)
				{
					$price = $varprice;
				}
			}

			$tip = ' title="' . LAN_VSTORE_SC_EXCL_TAX . '"';
			if(!empty($this->vpref['tax_include_in_price']))
			{
				$custData = vstore::getCustomerData();
				if($rate = vstore::getTaxRate($this->var['item_tax_class'], varset($custData['country'],null), $this->vpref))
				{
					$tax = vstore::calcTaxAmount($price, $rate);
					$price += $tax;
					$tip = " title='" . sprintf(LAN_VSTORE_SC_INCL_TAX, number_format($tax,2)) . "'";
				}
			}

			return ' <span class="vstore-item-price-'.$itemid.'"'.$tip.'>'.$this->format_amount($price).'</span><input type="hidden" class="vstore-item-baseprice-'.$itemid.'" value="'.$baseprice.'"/>';
		}

		function sc_item_weight($parm=null)
		{
			$weight = $this->var['item_weight'];
			if ($weight <= 0) return '';
			return LAN_VSTORE_SC_WEIGHT . ': ' . $weight . $this->vpref['weight_unit'];
		}


		/** Is the current product a quote product (empty price, not 0)? */
		protected function isQuoteItem()
		{
			return array_key_exists('item_price', $this->var) && \Vstore\Support\Catalog::isQuotePrice($this->var['item_price']);
		}

		function sc_item_addtocart($parm=null)
		{

			$isQuote = $this->isQuoteItem();
			$class = empty($parm['class']) ? ($isQuote ? 'btn btn-primary vstore-add' : 'btn btn-success vstore-add') : $parm['class'];
			$classo = empty($parm['class0']) ? 'btn btn-default btn-secondary disabled vstore-add' : $parm['class0'];

			if($isQuote)
			{
				$class = str_replace('btn-success', 'btn-primary', $class);
			}
			$itemid = ' data-vstore-item="'.varset($this->var['item_id'], 0).'"';

			if (!in_array('vstore-add', explode(' ', $class)))
			{
				$class .= ' vstore-add';
			}
			if (!in_array('vstore-add', explode(' ', $classo)))
			{
				$classo .= ' vstore-add';
			}
			$itemclass = ' vstore-add-item-'.varset($this->var['item_id'], 0);

			$class .= $itemclass;
			$classo .= $itemclass;

			$inStock = $this->inStock();

			if(!$inStock)
			{
				if(!empty($this->var['item_link'])) // external link - redirect to URL, info only. ie. catalog mode
				{
					return "<a href='".$this->var['item_link']."' target='_blank' class='".$class."'>".LAN_READ_MORE."</a>";
				}

				return "<a href='#' class='btn-out-of-stock ".$classo."' ".$itemid.">".$this->captionOutOfStock."</a>";
			}

			$label = LAN_VSTORE_001; // 'Add to cart';

			if($isQuote)
			{
				// data-vstore-label keeps the caption when js/vstore.js refreshes the button after a variation change
				return '<a class="'.$class.'" '.$itemid.' data-vstore-label="'.LAN_VSTORE_QUOTE_BUTTON.'" href="#">'.$this->tp->toGlyph('fa-file-text-o').' '.LAN_VSTORE_QUOTE_BUTTON.'</a>';
			}

			return '<a class="'.$class.'" '.$itemid.' href="#">'.$this->tp->toGlyph('fa-shopping-cart').' '.$label.'</a>';
		}


		function sc_item_status($parm=null)
		{
			if($this->var['item_inventory'] != 0)
			{
				return '<span class="text-success"><strong>'.defset('LAN_VSTORE_002','In Stock').'</strong></span>';
			}

			return '<span class="text-danger"><strong>'.defset('LAN_VSTORE_003', 'Out of stock').'</strong></span>'; // Out of stock
		}

		function sc_item_url($parm=null)
		{
			if(!empty($this->var['item_link']))
			{
			//	return $this->var['item_link'];
			}

			return e107::url('vstore','product', $this->var);
		}


		function sc_shipping_field($parm = null)
		{
			if (empty($parm)) return '';

			$key = array_keys($parm);
			if ($key) $key = $key[0];

			$frm = e107::getForm();
			$text = '';

			switch($key)
			{
				case 'ship_firstname':
					$text = $frm->text('ship[firstname]', $this->var['ship']['firstname'], 100, array('placeholder'=>LAN_VSTORE_FR_FIRSTNAME));
					break;
				case 'ship_lastname':
					$text = $frm->text('ship[lastname]', $this->var['ship']['lastname'], 100, array('placeholder'=>LAN_VSTORE_FR_LASTNAME));
					break;
				case 'ship_company':
					$text = $frm->text('ship[company]', $this->var['ship']['company'], 200, array('placeholder'=>LAN_VSTORE_FR_COMPANY));
					break;
				case 'ship_address':
					$text = $frm->text('ship[address]', $this->var['ship']['address'], 200, array('placeholder'=>LAN_VSTORE_FR_ADDRESS));
					break;
				case 'ship_city':
					$text = $frm->text('ship[city]', $this->var['ship']['city'], 100, array('placeholder'=>LAN_VSTORE_FR_CITY));
					break;
				case 'ship_state':
					$text = $frm->text('ship[state]', $this->var['ship']['state'], 100, array('placeholder'=>LAN_VSTORE_FR_STATE));
					break;
				case 'ship_zip':
					$text = $frm->text('ship[zip]', $this->var['ship']['zip'], 15, array('placeholder'=>LAN_VSTORE_FR_ZIP));
					break;
				case 'ship_country':
					$text = $frm->country('ship[country]', $this->var['ship']['country'], array('placeholder'=>LAN_VSTORE_SC_SELECT_COUNTRY));
					break;
				case 'ship_phone':
					$text = $frm->text('ship[phone]', $this->var['ship']['phone'], 15, array('placeholder'=>LAN_VSTORE_FR_PHONE, 'required'=>0));
					break;
				case 'ship_notes':
					$text = $frm->textarea('ship[notes]', $this->var['ship']['notes'], 4, null, array('placeholder'=>LAN_VSTORE_SC_NOTES_PH, 'required'=>0, 'size'=>'large'));
					break;
			}
			return $text;
		}



		function sc_customer_add_label($parm = null)
		{
			return '<label for="'.$this->var['fieldname'].'" class="'.(empty($this->var['fieldrequired']) ? '' : 'required').'">'.$this->var['fieldcaption'].'</label>';
		}

		function sc_customer_add_field($parm = null)
		{
			return $this->var['field'];
		}

		function sc_customer_field($parm = null)
		{
			if (empty($parm)) return '';

			$key = array_keys($parm);
			if ($key) $key = $key[0];

			$frm = e107::getForm();
			$text = '';

			switch($key)
			{
				case 'cust_firstname':
					$text = $frm->text('cust[firstname]', $this->var['cust']['firstname'], 100, array('placeholder'=>LAN_VSTORE_FR_FIRSTNAME, 'required'=>1));
					break;
				case 'cust_lastname':
					$text = $frm->text('cust[lastname]', $this->var['cust']['lastname'], 100, array('placeholder'=>LAN_VSTORE_FR_LASTNAME, 'required'=>1));
					break;
				case 'cust_company':
					$text = $frm->text('cust[company]', $this->var['cust']['company'], 200, array('placeholder'=>LAN_VSTORE_FR_COMPANY));
					break;
				case 'cust_vat_id':
					$text = $frm->text('cust[vat_id]', $this->var['cust']['vat_id'], 50, array('placeholder'=>LAN_VSTORE_FR_VAT_ID));
					break;
				case 'cust_taxcode':
					$text = $frm->text('cust[taxcode]', $this->var['cust']['taxcode'], 50, array('placeholder'=>LAN_VSTORE_FR_TAXCODE));
					break;
				case 'cust_address':
					$text = $frm->text('cust[address]', $this->var['cust']['address'], 200, array('placeholder'=>LAN_VSTORE_FR_ADDRESS, 'required'=>1));
					break;
				case 'cust_city':
					$text = $frm->text('cust[city]', $this->var['cust']['city'], 100, array('placeholder'=>LAN_VSTORE_FR_CITY, 'required'=>1));
					break;
				case 'cust_state':
					$text = $frm->text('cust[state]', $this->var['cust']['state'], 100, array('placeholder'=>LAN_VSTORE_FR_STATE, 'required'=>1));
					break;
				case 'cust_zip':
					$text = $frm->text('cust[zip]', $this->var['cust']['zip'], 15, array('placeholder'=>LAN_VSTORE_FR_ZIP, 'required'=>1));
					break;
				case 'cust_country':
					$text = $frm->country('cust[country]', $this->var['cust']['country'], array('placeholder'=>LAN_VSTORE_SC_SELECT_COUNTRY, 'required'=>1));
					break;
				case 'cust_email':
					$text = $frm->email('cust[email]', $this->var['cust']['email'], 100, array('placeholder'=>LAN_VSTORE_FR_EMAIL, 'required'=>1));
					break;
				case 'cust_phone':
					$text = $frm->text('cust[phone]', $this->var['cust']['phone'], 20, array('placeholder'=>LAN_VSTORE_FR_PHONE, 'required'=>0));
					break;
				case 'cust_fax':
					$text = $frm->text('cust[fax]', $this->var['cust']['fax'], 15, array('placeholder'=>LAN_VSTORE_FR_FAX, 'required'=>0));
					break;
				case 'add_field0':
				case 'add_field1':
				case 'add_field2':
				case 'add_field3':
					// HTML built by renderCustomerForm() from the field template: its values are already escaped by the form helper
					$text = isset($this->var['cust']['add'][$key]) ? $this->var['cust']['add'][$key] : '';
					break;
			}
			return $text;
		}


		function sc_confirm_field($parm = null)
		{
			if (empty($parm)) return '';

			$key = array_keys($parm);
			if ($key) $key = $key[0];

			$frm = e107::getForm();
			$text = '';

			switch($key)
			{

				case 'billing_address':
				case 'shipping_address':
					$text = varset($this->var[$key]);
					break;

				case 'billing_title':
					$text = (vartrue($this->var['order_use_shipping']) ? LAN_VSTORE_FR_BILLING_ADDRESS : LAN_VSTORE_SC_BILLING_SHIPPING);
					break;

				case 'ship_country':
				case 'cust_country':
					$area = substr($key, 0, 4);
					$key = substr($key, 5);
					if (varset($this->var[$area][$key]))
					{
						$text = e107::getForm()->getCountry($this->var[$area][$key]);
					}
					break;

				default:
					if (strpos($key, 'ship_') === 0)
					{
						$key = substr($key, 5);
						$value = varset($this->var['ship'][$key]);
					}
					elseif (strpos($key, 'cust_') === 0)
					{
						$key = substr($key, 5);
						$value = varset($this->var['cust'][$key]);
					}
					else
					{
						$value = varset($this->var[$key]);
					}
					$text = is_scalar($value) ? htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8') : '';

			}
			return $text;
		}

		function sc_confirm_data($parm = null)
		{
			if (empty($parm)) return '';

			$key = array_keys($parm);
			if ($key) $key = $key[0];

			$text = '';

			switch($key)
			{
				case 'name':
					$text = $this->var['item']['item_name'];
					if (vartrue($this->var['item']['itemvarstring']))
					{
						$text .= '<br />'.$this->var['item']['itemvarstring'];
					}
					break;

				case 'price':
					$key = 'item_'.$key.($this->var['item']['is_business'] && !$this->var['item']['is_local'] ? '_net' : '');
					$text = $this->format_amount($this->var['item'][$key]);
					break;

				case 'quantity':
					$text = $this->var['item']['cart_qty'];
					break;

				case 'item_total':
					$key .= ($this->var['item']['is_business'] && !$this->var['item']['is_local'] ? '_net' : '');
					$text = $this->format_amount($this->var['item'][$key]);
					break;

				case 'sub_total':
					$key = ($this->var['totals']['is_business'] && !$this->var['totals']['is_local'] ? 'cart_subNet' : 'cart_subTotal');
					$text = $this->format_amount($this->var['totals'][$key]);
					break;

				case 'shipping_total':
					$key = ($this->var['totals']['is_business'] && !$this->var['totals']['is_local'] ? 'cart_shippingNet' : 'cart_shippingTotal');
					$text = $this->format_amount($this->var['totals'][$key]);
					break;

				case 'grand_total':
					$key = ($this->var['totals']['is_business'] && !$this->var['totals']['is_local'] ? 'cart_grandNet' : 'cart_grandTotal');
					$text = $this->format_amount($this->var['totals'][$key]);
					break;

				case 'coupon':
					$text = $this->var['coupon']['code'];
					break;

				case 'coupon_amount':
					$key = ($this->var['totals']['is_business'] && !$this->var['totals']['is_local'] ? 'amount_net' : 'amount');
					$text = $this->format_amount($this->var['coupon'][$key]);
					break;

				default:
					$text = varset($this->var[$key]);
			}

			return $text;
		}


		function sc_confirm_items()
		{
			$items = $this->var['items'];
			if (!is_array($items))
			{
				$items = e107::unserialize($items);
			}


			$template = e107::getTemplate('vstore', 'vstore', !empty($this->var['is_quote']) ? 'confirm_items_quote' : 'confirm_items');

			$text = $this->tp->parseTemplate($template['header'], true, $this);

			foreach($items as $key=>$item)
			{
				$this->addVars(array('item' => $item));
				$text .= $this->tp->parseTemplate($template['row'], true, $this);
			}

			$text .= $this->tp->parseTemplate($template['footer'], true, $this);

			return $text;


		}


		function sc_confirm_tax($parm=null)
		{
			if ($this->var['is_business'] && !$this->var['is_local'])
			{
				return '';
			}

			$template = e107::getTemplate('vstore', 'vstore', 'confirm_items');

			$text = $x = $y = '';

			foreach($this->var['totals']['cart_taxTotal'] as $tax_rate => $value)
			{
				if ($this->tp->toNumber($tax_rate) <= 0) continue;
				$x .= ($x != '' ? '<br />' : '').($this->tp->toNumber($tax_rate) * 100).'%';
				$y .= ($y != '' ? '<br />' : '').$this->format_amount($value);
			}

			if ($x != '')
			{
				$text .= $this->tp->lanVars($template['tax'], array('x' => $x, 'y' => $y));
			}

			return $text;
		}


		function sc_confirm_coupon()
		{
			if (empty($this->var['coupon']['code']))
			{
				return '';
			}

			$template = e107::getTemplate('vstore', 'vstore', 'confirm_items');

			$text = $this->tp->parseTemplate($template['coupon'], true, $this);
			return $text;
		}


		function sc_cart_data($parm = null)
		{

			if (empty($parm)) return '';
			$key = array_keys($parm);
			if ($key) $key = $key[0];
			$text = '';
			switch($key)
			{
				case 'nr':
					$text = $this->var['item']['nr'];
					break;
				case 'code':
					$text = $this->var['item']['code'];
					break;
				case 'name':
					$text = $this->var['item']['name'];
					break;
				case 'price':
					// Item prices are stored net, so they are the same for consumers and reverse-charge businesses.
					$text = $this->format_amount($this->var['item']['price']);
					break;
				case 'quantity':
					$text = $this->var['item']['quantity'];
					break;
				case 'tax':
					$isBusiness = !empty($this->var['is_business']);
					$isLocal = !empty($this->var['is_local']);
					$text = ($isBusiness && !$isLocal) ? '' : ($this->var['item']['tax_rate'] * 100) . '%';
					break;
				case 'item_total':
					$text = $this->format_amount($this->var['item']['item_total']);
					break;
				case 'sub_total':
					$subTotal = 0;
					$orderItems = varset($this->var['order_items']);
					if(!empty($orderItems) && !is_array($orderItems))
					{
						$orderItems = e107::unserialize($orderItems);
					}
					if(!empty($orderItems))
					{
						foreach($orderItems as $item)
						{
							$subTotal += ((float) $item['price'] * (int) $item['quantity']);
						}
					}
					$text = $this->format_amount($subTotal);
				//	$text = $this->format_amount($this->var['order_pay_amount'] - $this->var['order_pay_shipping'] - varset($this->var['cart_coupon']['amount'],0));
					break;
				case 'shipping_total':
					$text = $this->format_amount($this->var['order_pay_shipping']);
					break;
				case 'grand_total':
					$text = $this->format_amount($this->var['order_pay_amount']);
					break;
				case 'item_count':
					$text = $this->var['item_count'];
					break;
				case 'pic':
				//	e107::getDebug()->log($this->var);
					$text = varset($this->var['item']['pic']);
					break;
				case 'index_url':
					$text = e107::url('vstore','index');
					break;
				case 'cart_url':
					$text = e107::url('vstore','cart');
					break;
				case 'dashboard_url':
					$text = e107::url('vstore','dashboard', array('dash' => 'dashboard'));
					break;
				case 'coupon':
					$text = $this->getCouponData()['code'];
					break;
				case 'coupon_amount':
					$text = $this->format_amount($this->getCouponData()['amount']);
					break;
			}

			return $text;
		}


		function sc_cart_price($parm=null)
		{
			$isBusiness = !empty($this->var['is_business']);
			$isLocal = !empty($this->var['is_local']);
			
			if ($isBusiness && !$isLocal)
			{
				return $this->format_amount($this->var['item_price_net']);
			}

			return $this->format_amount($this->var['item_price']);
		}

		function sc_cart_total($parm=null)
		{
			$isBusiness = !empty($this->var['is_business']);
			$isLocal = !empty($this->var['is_local']);
			
			if ($isBusiness && !$isLocal)
			{
				$total = $this->var['item_total_net'];
			}
			else
			{
				$total = $this->var['item_total'];
			}
			return $this->format_amount($total);
		}

		function sc_cart_qty($parm=null)
		{
			if($parm == 'edit')
			{
				$readonly = '';

				return '<input type="input" '.$readonly.' name="cartQty['.$this->var['cart_id'].'][qty]" class="form-control text-right cart-qty" id="cart-'.$this->var['cart_id'].'" value="'. (int) $this->var['cart_qty'] .'">';
			}


			return $this->var['cart_qty'];
		}

		function sc_cart_vars($parm=null)
		{
			$text = $this->var['cart_item_vars'];

			$text2 = '<input type="hidden" name="cartQty['.$this->var['cart_id'].'][id]" id="cart-id-'.$this->var['cart_id'].'" value="'.$this->var['cart_item'].'">';
			$text2 .='<input type="hidden" name="cartQty['.$this->var['cart_id'].'][vars]" id="cart-vars-'.$this->var['cart_id'].'" value="'.$text.'">';
			return $text2;
		}

		function sc_cart_removebutton($parm=null)
		{

			return '<button type="submit" name="cartRemove['.$this->var['cart_id'].']" class="btn btn-default btn-secondary vstore-cart-remove-item" title="' . LAN_VSTORE_SC_REMOVE . '">
			'.$this->tp->toGlyph('fa-trash').'</button>';

		}

		function sc_cart_subtotal($parm=null)
		{
			$isBusiness = !empty($this->var['is_business']);
			$isLocal = !empty($this->var['is_local']);
			
			if ($isBusiness && !$isLocal)
			{
				return $this->format_amount($this->var['cart_subNet']);
			}

			return $this->format_amount($this->var['cart_subTotal']);
		}

		function sc_cart_shippingtotal($parm=null)
		{
			$isBusiness = !empty($this->var['is_business']);
			$isLocal = !empty($this->var['is_local']);
			
			if ($isBusiness && !$isLocal)
			{
				return $this->format_amount($this->var['cart_shippingNet']);
			}

			return $this->format_amount($this->var['cart_shippingTotal']);
		}

		function sc_cart_checkout_button()
		{
			$isQuote = !empty($this->var['is_quote']);
			$label   = ($isQuote ? LAN_VSTORE_QUOTE_REQUEST : LAN_VSTORE_FR_CHECKOUT) . ' ' . $this->tp->toGlyph('fa-play');
			$recalc  = '<button style="display:none" type="submit" class="btn btn-warning cart-qty-submit">' . LAN_VSTORE_SC_RECALCULATE . '</button>';

			if(!empty($this->var['cart_scoped']))
			{
				// Purchase lines and quote lines share the cart: the button tells the checkout which ones to process.
				// The recalculate button comes first so that Enter in a quantity field recalculates.
				return $recalc . '<button type="submit" name="cartScope" value="' . ($isQuote ? 'quote' : 'sale') . '" class="btn btn-success cart-checkout">' . $label . '</button>';
			}

			return '<a href="' . e107::url('vstore', 'checkout') . '" id="cart-checkout" class="btn btn-success cart-checkout">' . $label . '</a>
		                        ' . str_replace('<button ', '<button id="cart-qty-submit" ', $recalc) . '

		';
		}

		static function sc_cart_continueshop()
		{

			$link = e107::url('vstore','index');

			return '
		<a href="'.$link.'" class="btn btn-default btn-secondary">
		'.e107::getParser()->toGlyph('fa-shopping-cart').' '.LAN_VSTORE_SC_CONTINUE_SHOPPING.'
		</a>';
		}

		function sc_cart_coupon()
		{
			$template = e107::getTemplate('vstore', 'vstore_cart');

			$text = $this->tp->parseTemplate($template['coupon'], true, $this);
			return $text;
		}

		function sc_cart_coupon_field()
		{
			$frm = e107::getForm();
			$text = '<div class="form-inline d-flex">';
			$text .= $frm->label(LAN_VSTORE_SC_COUPON_LABEL, 'cart_coupon_code');
			$text .= '&nbsp;' . $frm->text('cart_coupon_code', $this->var['cart_coupon']['code'], 50, array('placeholder' => LAN_VSTORE_SC_COUPON_PH, 'size' => 'large'));
			$text .= '</div>';
			return $text;
		}

		function sc_cart_coupon_value()
		{
			$isBusiness = !empty($this->var['is_business']);
			$isLocal = !empty($this->var['is_local']);
			
			if ($isBusiness && !$isLocal)
			{
				return $this->format_amount($this->var['cart_coupon']['amount_net']);
			}

			return $this->format_amount($this->var['cart_coupon']['amount']);
		}


		function sc_item_availability()
		{
			if (!$this->inStock())
			{
				return "<span class='label label-danger badge bg-danger vstore-item-avail-".$this->var['item_id']."'>".$this->captionOutOfStock."</span>";
			}

			return "<span class='label label-success badge bg-success vstore-item-avail-".$this->var['item_id']."'>".LAN_VSTORE_002."</span>";
		}

		/**
		 * Quantity field for the product page. It is read by js/vstore.js when the item is added to the cart.
		 * The maximum is only set when the stock is tracked for the whole item (not per variation).
		 */
		function sc_item_qty($parm=null)
		{
			if (!$this->inStock())
			{
				return '';
			}

			$itemId = (int) varset($this->var['item_id'], 0);
			$max = '';
			$stock = (int) varset($this->var['item_inventory'], -1);

			if ($stock > 0 && !vstore::filterItemVarsByType(varset($this->var['item_vars']), 1, true))
			{
				$max = ' max="' . $stock . '"';
			}

			return '<span class="vstore-item-qty-group d-inline-flex align-items-center me-2">'
				. '<label for="vstore-item-qty-' . $itemId . '" class="me-2 mb-0">' . LAN_VSTORE_FR_QUANTITY . '</label>'
				. '<input type="number" min="1"' . $max . ' value="1" step="1" id="vstore-item-qty-' . $itemId . '" '
				. 'class="form-control text-right text-end vstore-item-qty" style="width:5.5rem" data-vstore-item="' . $itemId . '">'
				. '</span>';
		}


		function sc_cart_taxtotal($parm=null)
		{
			$isBusiness = !empty($this->var['is_business']);
			$isLocal = !empty($this->var['is_local']);
			
			if ($isBusiness && !$isLocal)
			{
				return '';
			}
			$template = e107::getTemplate('vstore', 'vstore_cart');
			$text = $x = $y = '';
			foreach($this->var['cart_taxTotal'] as $tax_rate => $value)
			{
				if ($this->tp->toNumber($tax_rate) <= 0) continue;
				$x .= ($x != '' ? '<br />' : '').($this->tp->toNumber($tax_rate) * 100).'%';
				$y .= ($y != '' ? '<br />' : '').$this->format_amount($value);
			}

			if ($x != '')
			{
				$text .= $this->tp->lanVars($template['tax'], array('x' => $x, 'y' => $y));
			}

			return $text;
		}


		function sc_cart_grandtotal($parm=null)
		{
			$isBusiness = !empty($this->var['is_business']);
			$isLocal = !empty($this->var['is_local']);
			
			if ($isBusiness && !$isLocal)
			{
				return $this->format_amount( $this->var['cart_grandNet']);
			}

			return $this->format_amount( $this->var['cart_grandTotal']);
		}

		public function sc_cart_currency_symbol($parm=null)
		{
			return $this->curSymbol;
		}



		function sc_invoice_data($parm = null)
		{
			if (empty($parm)) return '';

			$key = array_keys($parm);
			if ($key) $key = $key[0];

			$frm = e107::getForm();
			$text = '';

			switch($key)
			{
				case 'footer0':
				case 'footer1':
				case 'footer2':
				case 'footer3':
					$i = (int) substr($key, -1);
					if (isset($this->vpref['invoice_footer']) && !is_array($this->vpref['invoice_footer']))
					{
						$this->vpref['invoice_footer'] = e107::unserialize($this->vpref['invoice_footer']);
					}
					// if (!empty($this->vpref['invoice_footer'][$i]['title'][e_LANGUAGE]))
					// {
					// 	$text = "<b>" . $this->tp->toHTML($this->vpref['invoice_footer'][$i]['title'][e_LANGUAGE], true) . "</b>";
					// }
					// $text .= $this->tp->toHTML($this->vpref['invoice_footer'][$i]['text'][e_LANGUAGE], true);
					if(!empty($this->vpref['invoice_footer'][$i]))
					{
						$text = $this->tp->toHTML($this->vpref['invoice_footer'][$i], true);
					}
					break;

				case 'title':
					if(!empty($this->vpref['invoice_title'][e_LANGUAGE]))
					{
						$text = $this->tp->toHTML($this->vpref['invoice_title'][e_LANGUAGE], true);
					}
					break;

				case 'info_title':
					if(!empty($this->vpref['invoice_info_title'][e_LANGUAGE]))
					{
						$text = $this->tp->toHTML($this->vpref['invoice_info_title'][e_LANGUAGE], true);
					}
					break;

				case 'subject':
					if(!empty($this->vpref['invoice_subject'][e_LANGUAGE]))
					{
						$text = $this->tp->parseTemplate($this->vpref['invoice_subject'][e_LANGUAGE], true, $this);
					}
					break;

				case 'hint':
					if(!empty($this->vpref['invoice_hint'][e_LANGUAGE]))
					{
						$text = $this->tp->toHTML($this->vpref['invoice_hint'][e_LANGUAGE], true);
						$text = ($text ? '<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>' : '') . $text;
					}
					break;

				case 'finish_phrase':
					if(!empty($this->vpref['invoice_finish_phrase']))
					{
						$text = $this->tp->toHTML($this->vpref['invoice_finish_phrase'], true);
						$text = ($text ? '<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>' : '') . $text;
					}
					break;

				case 'payment_deadline':

					$datestamp = $this->var['order_date'];

					if(!empty($this->vpref['invoice_payment_deadline']))
					{
						$datestamp += ((int) $this->vpref['invoice_payment_deadline'] * 24 * 60 * 60);
					}

					$format = varset($this->vpref['invoice_date_format'], 'dd M, yyyy');
					$text = e107::getParser()->toDate($datestamp, $format);
					break;
			}

			return $text;
		}

		function sc_invoice_items()
		{
			$items = $this->var['order_items'];

			$template = e107::getTemplate('vstore', 'vstore_invoice', 'invoice_items');

			$text = $this->tp->parseTemplate($template['header'], true, $this);

			foreach($items as $key=>$item)
			{
				$desc = $item['description'];

				if (!empty($item['vars']))
				{
					$desc .= '<br/>' . $item['vars'];
				}
				if ($item['id']>0 && varset($item['file']) && isset($this->var['order_status']))
				{
					if ($this->var['order_status'] === 'C' || ($this->var['order_status'] === 'N' && $this->var['order_pay_status'] == 'complete'))
					{
						$linktext = LAN_VSTORE_SC_DOWNLOAD;
					}
					else
					{
						$linktext = LAN_VSTORE_SC_DOWNLOAD_PENDING;
					}
					$desc .= '<br/><a href="'.e107::url('vstore', 'download', array('item_id' => $item['id']), array('mode'=>'full')).'">'.$linktext.'</a>';
				}

				$item['nr'] = ($key + 1);
				$item['code'] = $item['name'];
				$item['name'] = $desc;
				$item['item_total'] = $item['price'] * $item['quantity'];

				$this->addVars(array('item' => $item));
				$text .= $this->tp->parseTemplate($template['row'], true, $this);
			}

			$text .= $this->tp->parseTemplate($template['footer'], true, $this);

			return $text;

		}


		function sc_invoice_coupon()
		{
			if (empty($this->var['order_pay_coupon_code']))
			{
				return '';
			}

			$template = e107::getTemplate('vstore', 'vstore_invoice', 'invoice_items');
			$data = array('x' => $this->var['order_pay_coupon_code'], 'y' => $this->format_amount($this->var['order_pay_coupon_amount']));
			$text = $this->tp->lanVars($template['coupon'], $data);
			return $text;
		}


		function sc_invoice_tax($parm=null)
		{
			$isBusiness = !empty($this->var['is_business']);
			$isLocal = !empty($this->var['is_local']);
			
			if ($isBusiness && !$isLocal)
			{
				return '';
			}
			if (!is_array($this->var['order_pay_tax']))
			{
				$this->var['order_pay_tax'] = e107::unserialize($this->var['order_pay_tax']);
			}
			$template = e107::getTemplate('vstore', 'vstore_invoice', 'invoice_items');
			$text = $x = $y = '';
			foreach($this->var['order_pay_tax'] as $tax_rate => $value)
			{
				if ($this->tp->toNumber($tax_rate) <= 0) continue;
				$x .= ($x != '' ? '<br />' : '').($this->tp->toNumber($tax_rate) * 100).'%';
				$y .= ($y != '' ? '<br />' : '').$this->format_amount($value);
			}

			if ($x != '')
			{
				$text .= $this->tp->lanVars($template['tax'], array('x' => $x, 'y' => $y));
			}

			return $text;
		}

		function sc_invoice_logo($parm)
		{
			// Paths to image file, link are relative to site base

			$logopref = e107::getConfig()->get('sitelogo');
			$logop = $this->tp->replaceConstants($logopref);

			if(vartrue($logopref) && is_readable($logop))
			{
				$logo = $this->tp->replaceConstants($logopref,'abs');
				$path = $this->tp->replaceConstants($logopref);
			}
			elseif (isset($file) && $file && is_readable($file))
			{
				$logo = e_HTTP.$file;						// HTML path
				$path = e_BASE.$file;						// PHP path
			}
			else if (is_readable(THEME.'images/e_logo.png'))
			{
				$logo = THEME_ABS.'images/e_logo.png';		// HTML path
				$path = THEME.'images/e_logo.png';			// PHP path
			}
			else
			{
				$logo = '{e_IMAGE}logoHD.png';				// HTML path
				$path = e_IMAGE.'logoHD.png';					// PHP path
			}

			if ($parm === 'path')
			{
				return $path;
			}
			return '<image src="'.$path.'" style="max-width:150px;max-height: 150px;width:100%;height:auto;">';
		}


		/** Summary boxes of the customer dashboard (orders, spent, in progress, open quotes). */
		protected function dashboardKpis()
		{
			$stats = varset($this->var['stats'], array());
			$template = e107::getTemplate('vstore', 'vstore_dashboard');
			$url = e107::url('vstore', 'dashboard', array('dash' => 'orders'));

			$boxes = array(
				array('fa-shopping-bag', 'primary', LAN_VSTORE_DB_KPI_ORDERS, (int) varset($stats['orders'])),
				array('fa-money', 'success', LAN_VSTORE_DB_KPI_SPENT, $this->format_amount(varset($stats['spent'], 0))),
				array('fa-truck', 'info', LAN_VSTORE_DB_KPI_OPEN, (int) varset($stats['open'])),
				array('fa-file-text-o', 'warning', LAN_VSTORE_DB_KPI_QUOTES, (int) varset($stats['quotes'])),
			);

			$text = '';
			foreach($boxes as $box)
			{
				$text .= strtr($template['kpi'], array('[url]' => $url, '[icon]' => $box[0], '[tone]' => $box[1], '[label]' => $box[2], '[value]' => $box[3]));
			}

			return $text;
		}

		/** Bars with the spending of the last months of the customer. */
		protected function dashboardChart()
		{
			$stats = varset($this->var['stats'], array());
			$template = e107::getTemplate('vstore', 'vstore_dashboard');

			if(empty($stats['peak']))
			{
				return $this->tp->parseTemplate($template['chart']['empty'], true, $this);
			}

			$text = $this->tp->parseTemplate($template['chart']['start'], true, $this);
			foreach($stats['months'] as $month)
			{
				$text .= strtr($template['chart']['bar'], array(
					'[label]' => $month['label'],
					'[value]' => $month['value'] > 0 ? $this->format_amount($month['value']) : '&nbsp;',
					'[pct]'   => $month['pct'],
					'[empty]' => $month['value'] > 0 ? '' : ' is-empty',
				));
			}

			return $text . $template['chart']['end'];
		}

		/** The latest orders of the customer. */
		protected function dashboardRecent()
		{
			$stats = varset($this->var['stats'], array());
			$template = e107::getTemplate('vstore', 'vstore_dashboard');

			if(empty($stats['recent']))
			{
				return $this->tp->parseTemplate($template['recent']['empty'], true, $this);
			}

			$text = $template['recent']['start'];
			$saved = $this->var;

			foreach($stats['recent'] as $row)
			{
				$this->var = $row;

				$text .= strtr($template['recent']['item'], array(
					'[url]'    => e107::url('vstore', 'dashboard_action', array('dash' => 'orders', 'action' => 'view', 'id' => vstore::orderViewId($row))),
					'[icon]'   => $this->isQuoteOrder() ? 'fa-file-text-o' : 'fa-shopping-bag',
					'[ref]'    => $this->sc_order_data(array('order_ref' => 1)),
					'[date]'   => $this->sc_order_data(array('order_date' => 1)),
					'[total]'  => $this->sc_order_data(array('order_pay_amount' => 1)),
					'[status]' => $this->sc_order_data(array('order_status_label' => 1)),
				));
			}

			$this->var = $saved;

			return $text . $template['recent']['end'];
		}


		function sc_dashboard($parm = null)
		{
			if (empty($parm)) return '';

			$key = key($parm);
			if (empty($key)) return '';

			switch($key)
			{
				case 'title':
					$text = $this->var['nav'][$this->var['area']];
					break;

				case 'avatar':
					$text = $this->tp->toAvatar(null, array('w' => 64, 'h' => 64, 'crop' => 1, 'shape' => 'circle', 'alt' => ''));
					break;

				case 'hello':
					$text = sprintf(LAN_VSTORE_DB_HELLO, $this->tp->toHTML(deftrue('USERNAME'), false, 'TITLE'));
					break;

				case 'subtitle':
					$text = $this->var['area'] === 'dashboard' ? LAN_VSTORE_DB_INTRO : $this->var['nav'][$this->var['area']];
					break;

				case 'orders_url':
					$text = e107::url('vstore', 'dashboard', array('dash' => 'orders'));
					break;

				case 'kpis':
					$text = $this->dashboardKpis();
					break;

				case 'chart':
					$text = $this->dashboardChart();
					break;

				case 'recent':
					$text = $this->dashboardRecent();
					break;

				case 'quick':
					$template = e107::getTemplate('vstore', 'vstore_dashboard');
					$text = $this->tp->parseTemplate(strtr($template['quick'], array(
						'[shop_url]'    => e107::url('vstore', 'index'),
						'[address_url]' => e107::url('vstore', 'dashboard', array('dash' => 'addresses')),
						'[account_url]' => SITEURL . 'usersettings.php',
					)), true, $this);
					break;

				case 'nav':
					$template = e107::getTemplate('vstore', 'vstore_dashboard', 'nav');

					$text = $template['start'];
					$nav = $this->var['nav'];
					foreach($nav as $a => $caption)
					{
						$active = ($this->var['area'] == $a ? 'active': '') ;
						$text .= e107::getParser()->lanVars(
							$template['item'], 
							array(
								'active' => $active,
								'url' => e107::url('vstore', 'dashboard', array('dash' => $a)),
								'caption' => $caption
							)
						);
					}
					$text .= $template['end'];
					break;

				case 'shipping_address':
					$data = e107::unserialize($this->var['cust_shipping']);
					$text = $this->format_address($data, true);
					break;

				case 'billing_address':
					$data = array(
						'firstname' => $this->var['cust_firstname'],
						'lastname' => $this->var['cust_lastname'],
						'company' => $this->var['cust_company'],
						'address' => $this->var['cust_address'],
						'city' => $this->var['cust_city'],
						'zip' => $this->var['cust_zip'],
						'country' => $this->var['cust_country'],
						'vat_id' => $this->var['cust_vat_id'],
						'taxcode' => $this->var['cust_taxcode'],
						'email' => $this->var['cust_email'],
						'phone' => $this->var['cust_phone'],
						'fax' => $this->var['cust_fax']
					);
					$text = $this->format_address($data, true);
					break;

				case 'edit_billing':
				case 'edit_shipping':
					$text = '<a href="'.e107::url('vstore', 'dashboard_action', array('dash' => 'addresses', 'action' => 'edit', 'id' => ($key == 'edit_billing' ? 1 : 2))).'" class="btn btn-default btn-secondary">'.LAN_EDIT.'</a>';
					break;

			}

			return $text;
		}

		function sc_menu_cat($parm = null)
		{
			if (empty($parm)) return '';
			$key = key($parm);
			if (empty($key)) return '';

			$text = '';

			switch($key)
			{
				case 'active':
					if (isset($this->var['link_active'])) {
						$text = $this->var['link_active'] ? ' active ' : '';
					}
					elseif (varset($this->var['link_url'])) {
						if (substr(e_REQUEST_URI, 0, strlen($this->var['link_url'])) == $this->var['link_url']) {
							$text = ' active ';
						}
					}
					break;

				case 'image':
					$text = empty($this->var['link_image'])
						? $this->noImage('fa-folder-open')
						: $this->tp->toImage($this->var['link_image'], array('w' => 96, 'h' => 96, 'crop' => 1, 'alt' => ''));
					break;

				case 'url':
					$text = vartrue($this->var['link_url'], '#');
					break;

				case 'name':
					$text = vartrue($this->var['link_name']);
					break;

				case 'description':
					$text = vartrue($this->var['link_description']);
					break;

				case 'badge':
					$text = vartrue($this->var['link_badge']);
					break;
			}
			return $text;
		}

	}
