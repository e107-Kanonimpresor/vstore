<?php
	/**
	 * e107 website system
	 *
	 * Copyright (C) 2008-2017 e107 Inc (e107.org)
	 * Released under the terms and conditions of the
	 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
	 *
	 */

	e107::lan('vstore', 'front_shop', true);
	e107::lan('vstore', 'front_mail', true);

	$VSTORE_INVOICE_TEMPLATE = array();
	/**
	*  vstore Invoice template for use to be converted to pdf.

	ATTENTION!!!
	Do mainly use standard html elements as not all html5 tags
	or css is supported by TCPDF (the PDF library of Vstore)!
	You need to test ALL changes to the templates 
	BEFORE using in a production environment!
	
	*/

/* WRAPPERS */

	$VSTORE_INVOICE_WRAPPER['default']['ORDER_MERCHANT_INFO: line'] = '<span style="font-size:0.2cm;">{---}</span><br />';
	$VSTORE_INVOICE_WRAPPER['default']['BILLING: company'] = '{---}<br />';
	$VSTORE_INVOICE_WRAPPER['default']['BILLING: email'] = '<br />{---}';
	$VSTORE_INVOICE_WRAPPER['default']['BILLING: phone'] = '<br />{---}';
	$VSTORE_INVOICE_WRAPPER['default']['SHIPPING: company'] = '{---}<br />';
	$VSTORE_INVOICE_WRAPPER['default']['INVOICE_DATA: subject'] = '{---}<br />';

/* TEMPLATE */

	$VSTORE_INVOICE_TEMPLATE['default'] = '

<table style="width: 100%; margin-bottom:.5cm" cellpadding="3">

	<tr>
		<td style="height:0.5cm" colspan="3">
			<!-- use the height to adjust the height of the addressfield  -->
		</td>
	</tr>
	<tr>
		<td style="width: 33.3%; vertical-align:top">
			<h5>{LAN=LAN_VSTORE_INV_BILLING}</h5>
			{ORDER_MERCHANT_INFO: line}
			{BILLING: firstname} {BILLING: lastname}<br />
			{BILLING: company}
			{BILLING: address}<br />
			{BILLING: city} &nbsp;{BILLING: state} &nbsp;{BILLING: zip}<br />
			{BILLING: country}
			{BILLING: email}
			{BILLING: phone}
		</td>
		
		<td style="width: 33.3%; vertical-align:top">
			<h5>{LAN=LAN_VSTORE_FR_SHIPPING}</h5>
			{SHIPPING: firstname} {SHIPPING: lastname}<br />
			{SHIPPING: company}
			{SHIPPING: address}<br />
			{SHIPPING: city} &nbsp;{SHIPPING: state} &nbsp;{SHIPPING: zip}<br />
			{SHIPPING: country}		
		</td>

		<td style="width: 33.3%; vertical-align:top">
			<h5>{INVOICE_DATA: info_title}</h5>
			<table style="width: 100%;">
			<tr>
				<td>{LAN=LAN_VSTORE_INV_ORDER_NR}</td><td class="text-right">{ORDER_DATA: order_ref}</td>
			</tr>
			<tr>
				<td>{LAN=LAN_VSTORE_INV_ORDER_DATE}</td><td class="text-right">{ORDER_DATA: order_date}</td>
			</tr>
			<tr>
				<td>{LAN=LAN_VSTORE_INV_PAYMENT_METHOD}</td><td class="text-right">{ORDER_DATA: order_gateway}</td>
			</tr>
			<tr>
				<td>{LAN=LAN_VSTORE_INV_DUE_BY}</td><td class="text-right">{INVOICE_DATA: payment_deadline}</td>
			</tr>
			</table>	
		</td>
	</tr>

</table>
<table style="width: 100%;">
	<tbody>
	<tr>
		<td colspan="3">
			{INVOICE_ITEMS}
		</td>
	</tr>

	<tr>
		<td colspan="3">
			{INVOICE_DATA: hint}
		</td>
	</tr>

	<tr>
		<td colspan="3">
			{INVOICE_DATA: finish_phrase}
		</td>
	</tr>
	</tbody>
</table>

';
	


$VSTORE_INVOICE_TEMPLATE['footer'] = '
<table style="height: 3cm; width: 100%;">
<tbody>
	<tr>
		<td style="vertical-align:top;font-size:0.25cm;width:25%;">
			{INVOICE_DATA: footer0}
		</td>
		<td style="vertical-align:top;font-size:0.25cm;width:25%;">
			{INVOICE_DATA: footer1}
		</td>
		<td style="vertical-align:top;font-size:0.25cm;width:25%;">
			{INVOICE_DATA: footer2}
		</td>
		<td style="vertical-align:top;font-size:0.25cm;width:25%;">
			{INVOICE_DATA: footer3}
		</td>
	</tr>
</tbody>
</table>
';

/**
 * Order items list
 * Used in emails and on order summary and confirmation
 */		

$VSTORE_INVOICE_TEMPLATE['invoice_items']['header'] = '
<h2>{INVOICE_DATA: title}</h2>
<table cellpadding="4" style="width: 100%;">
<tr style="background-color:#f2f2f2;">
	<th style="width: 14%; text-align:left; border-bottom:1px solid #999999;"><b>{LAN=LAN_VSTORE_INV_NO}</b></th>
	<th style="width: 38%; text-align:left; border-bottom:1px solid #999999;"><b>{LAN=LAN_VSTORE_FR_PRODUCT}</b></th>

	<th style="width: 16%; text-align:right; border-bottom:1px solid #999999;"><b>{LAN=LAN_VSTORE_FR_UNIT_PRICE}</b></th>
	<th style="width: 10%; text-align:right; border-bottom:1px solid #999999;"><b>{LAN=LAN_VSTORE_FR_QTY}</b></th>
	<th style="width: 22%; text-align:right; border-bottom:1px solid #999999;"><b>{LAN=LAN_VSTORE_FR_AMOUNT}</b></th>
</tr>
';

$VSTORE_INVOICE_TEMPLATE['invoice_items']['row'] = '
<tr>
	<td style="text-align:left; border-bottom:1px solid #dddddd;">{CART_DATA: code}</td>
	<td style="text-align:left; border-bottom:1px solid #dddddd;">{CART_DATA: name}</td>
	<td style="text-align:right; border-bottom:1px solid #dddddd;">{CART_DATA: price}</td>
	<td style="text-align:right; border-bottom:1px solid #dddddd;">{CART_DATA: quantity}</td>
	<td style="text-align:right; border-bottom:1px solid #dddddd;">{CART_DATA: item_total}</td>
</tr>';

$VSTORE_INVOICE_TEMPLATE['invoice_items']['footer'] = '
<tr>
	<td></td>
	<td></td>
	<td colspan="2" style="text-align:right"><b>{LAN=LAN_VSTORE_FR_SUBTOTAL}</b></td>
	<td style="text-align:right;">{CART_DATA: sub_total}</td>
</tr>
<tr>
	<td></td>
	<td></td>
	<td colspan="2" style="text-align:right"><b>{LAN=LAN_VSTORE_FR_SHIPPING}</b></td>
	<td style="text-align:right;">{CART_DATA: shipping_total}</td>
</tr>
{INVOICE_COUPON}
{INVOICE_TAX}
<tr>
	<td></td>
	<td></td>
	<td colspan="2" style="border-top: 1px solid #cccccc; font-size: 1.5em; text-align:right"><b>{LAN=LAN_VSTORE_FR_TOTAL}</b></td>
	<td style="text-align:right; border-top: 1px solid #cccccc; font-size: 1.5em;"><b>{CART_DATA: grand_total}</b></td>
</tr>
</table>
';

$VSTORE_INVOICE_TEMPLATE['invoice_items']['coupon'] = '
<tr>
	<td></td>
	<td></td>
	<td>'.LAN_VSTORE_FR_COUPON.': <b>[x]</b></td>
	<td style="text-align:right;">[y]</td>
</tr>
';


$VSTORE_INVOICE_TEMPLATE['invoice_items']['tax'] = '
<tr>
	<td ></td>
	<td ></td>
	<td colspan="2" style="text-align:right;"><b>'.LAN_VSTORE_FR_TAX.' ([x])</b></td>
	<td style="text-align:right;">[y]</td>
</tr>
';


$VSTORE_INVOICE_TEMPLATE['display'] = '
<html lang="{LAN=LAN_VSTORE_INV_HTML_LANG}">
  <head>
    <title>{LAN=LAN_VSTORE_INV_TITLE}</title>
    <meta charset=utf-8>
    <style type="text/css">
body { padding:10px; background-color: #E1E1E1 }
table.table{ border-collapse:collapse; border-spacing:0; width:100%; }
.table-striped>tbody>tr:nth-child(2n+1)>td,.table-striped>tbody>tr:nth-child(2n+1)>th{ background-color:#F9F9F9; }
.table-bordered > thead>tr>th,.table-bordered>tbody>tr>th,.table-bordered>tfoot>tr>th,.table-bordered>thead>tr>td,.table-bordered>tbody>tr>td,.table-bordered>tfoot>tr>td{
border:1px solid #DDD; }
.table>thead>tr>th,.table>tbody>tr>th,.table>tfoot>tr>th,.table>thead>tr>td,.table>tbody>tr>td,.table>tfoot>tr>td{ padding:8px;
line-height:1.42857; vertical-align:top; border-top:1px solid #DDD;}											
.vstore-invoice-wrapper {  padding:10px; width: 93%; max-width:1000px; background-color: #FFFFFF; border-radius: 5px; font-family: helvetica,arial }
.vstore-invoice-table { color: rgba(0,0,0,0.9) !important; width: 100%; }
.vstore-invoice-header { }
.vstore-invoice-sitelogo { float: left; margin-right: 10px; }
.vstore-invoice-sitename { vertical-align: middle; line-height: 80px; font-size: 1.8em; }
.vstore-invoice-body { }
.vstore-invoice-footer { }
.vstore-invoice-wrapper,
.vstore-invoice-table,
.vstore-invoice-body .table th,
.vstore-invoice-body .table td { color: rgba(0,0,0,0.85) !important; }
.text-right { text-align: right } 
</style>
<body>
<div class="vstore-invoice-wrapper">
<table class="table vstore-invoice-table">
	<thead>
	<tr>
		<td class="vstore-invoice-header">
			<div class="vstore-invoice-sitelogo">{SITELOGO:h=80}</div>
			<div class="vstore-invoice-sitename">[sitename]</div>
		</td>
	</tr>
	</thead>
	<tbody>
	<tr>
		<td class="vstore-invoice-body">
			[body]
		</td>
	</tr>
	</tbody>
	<tfoot>
	<tr>
		<td class="vstore-invoice-footer">
			<br/>
			<br/>
			[footer]
		</td>
	</tr>
	</tfoot>
</table>
</div>
</body>
</html>
';
