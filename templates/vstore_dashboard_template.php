<?php
/**
 * Vstore - shopping cart plugin for e107.
 * Based on e107inc/vstore (CaMerOn, Achim Ennenbach, e107 Inc). Modified by Kanonimpresor, 2025-2026.
 *
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.html
 */

e107::lan('vstore', 'front_shop', true);
e107::lan('vstore', 'front_dashboard', true);

$VSTORE_DASHBOARD_TEMPLATE = array();


$VSTORE_DASHBOARD_TEMPLATE['header'] = '
    <h3>{DASHBOARD: title}</h3>

    {DASHBOARD: nav}
    <br />
    <div>
';

$VSTORE_DASHBOARD_TEMPLATE['footer'] = '
    </div>
';

/**
 * Dashboard navigation
 */
$VSTORE_DASHBOARD_TEMPLATE['nav']['start'] = '
	<ul class="nav nav-tabs">';
$VSTORE_DASHBOARD_TEMPLATE['nav']['end'] = '
	</ul>
';
$VSTORE_DASHBOARD_TEMPLATE['nav']['item'] = '
		<li role="presentation" class="nav-item [active]"><a class="nav-link" href="[url]">[caption]</a></li>';


/**
 * Dashboard
 */
$VSTORE_DASHBOARD_TEMPLATE['dashboard'] = '
    <p>{LAN=LAN_VSTORE_DB_INTRO}</p>
';


/**
 * List orders
 */
$VSTORE_DASHBOARD_TEMPLATE['order']['list']['header'] = '
    <table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>{LAN=LAN_VSTORE_DB_ORDER_INFO}</th>
        <th>{LAN=LAN_VSTORE_FR_SHIPPING}</th>
        <th>{LAN=LAN_VSTORE_FR_ITEMS}</th>
        <th>{LAN=LAN_VSTORE_FR_TOTAL}</th>
        <th>{LAN=LAN_VSTORE_DB_STATUS}</th>
        <th>{LAN=LAN_VSTORE_DB_ACTIONS}</th>
    </tr>
    </thead>
    <tbody>
';


$VSTORE_DASHBOARD_TEMPLATE['order']['list']['footer'] = '
    </tbody>
    </table>
';


$VSTORE_DASHBOARD_TEMPLATE['order']['list']['item'] = '
    <tr>
        <td>
            {LAN=LAN_VSTORE_DB_DATE}: {ORDER_DATA: order_date}<br />
            {LAN=LAN_VSTORE_DB_ORDER}: {ORDER_DATA: order_ref}<br/>
            {LAN=LAN_VSTORE_DB_INVOICE}: {ORDER_DATA: order_invoice_link}
        </td>
        <td>{ORDER_DATA: order_shipping_full}</td>
        <td>{ORDER_DATA: order_items_short}</td>
        <td>{ORDER_DATA: order_pay_amount}</td>
        <td>{ORDER_DATA: order_status_label}</td>
        <td>{ORDER_ACTIONS}</td>
    </tr>
';


/**
 * Order detail
 */
$VSTORE_DASHBOARD_TEMPLATE['order']['detail'] = '
    <h4>{LAN=LAN_VSTORE_DB_ORDER_DETAILS} {ORDER_DATA: order_ref} <span class="pull-right float-right">{ORDER_DATA: order_status_label}</span></h4>
    <div class="clearfix">
        <div class="col-sm-4">
            <b>{LAN=LAN_VSTORE_DB_DATE}</b> {ORDER_DATA: order_date}<br />
            <b>{LAN=LAN_VSTORE_DB_ORDER}</b> {ORDER_DATA: order_ref}<br/>
            <b>{LAN=LAN_VSTORE_DB_INVOICE}</b> {ORDER_DATA: order_invoice_link}<br/>
            <b>{LAN=LAN_VSTORE_DB_PAYMENT_METHOD}</b> {ORDER_DATA: order_gateway}<br/>
            <b>{LAN=LAN_VSTORE_DB_PAYMENT_COMPLETE}</b> {ORDER_DATA: order_pay_status}<br/>
        </div>

        <div class="col-sm-4">
            <b>{LAN=LAN_VSTORE_FR_SHIPPING_ADDRESS}</b> <br />
            {ORDER_DATA: order_shipping_full}<br/>
        </div>

        <div class="col-sm-4">
            <b>{LAN=LAN_VSTORE_FR_BILLING_ADDRESS}</b> <br />
            {ORDER_DATA: order_billing_full}<br/>
            {ORDER_DATA: order_billing_contact}<br/>
        </div>
    </div>

    <br />

    <div>
        <b>{LAN=LAN_VSTORE_FR_ITEMS}</b>
        {ORDER_ITEMS}
    </div>

    <div>
        <b>{LAN=LAN_VSTORE_DB_LOG}</b>
        {ORDER_DATA: order_log}
    </div>

    <hr />

    <div class="text-center">
        {ORDER_ACTIONS: invoice}
        {ORDER_ACTIONS: cancel}
    </div>
';


/**
 * List downloads
 */
$VSTORE_DASHBOARD_TEMPLATE['download']['list']['header'] = '
    <table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>{LAN=LAN_VSTORE_DB_ORDER_INFO}</th>
        <th>{LAN=LAN_VSTORE_FR_ITEMS}</th>
        <th>{LAN=LAN_VSTORE_DB_STATUS}</th>
    </tr>
    </thead>
    <tbody>
';


$VSTORE_DASHBOARD_TEMPLATE['download']['list']['footer'] = '
    </tbody>
    </table>
';


$VSTORE_DASHBOARD_TEMPLATE['download']['list']['item'] = '
    <tr>
        <td>
            {LAN=LAN_VSTORE_DB_DATE}: {ORDER_DATA: order_date}<br />
            {LAN=LAN_VSTORE_DB_ORDER}: {ORDER_DATA: order_ref}<br/>
            {LAN=LAN_VSTORE_DB_INVOICE}: {ORDER_DATA: order_invoice_link}
        </td>
        <td>{ORDER_DATA: order_downloads}</td>
        <td>{ORDER_DATA: order_status_label}</td>
    </tr>
';


/**
 * List downloads
 */
$VSTORE_DASHBOARD_TEMPLATE['address']['view'] = '
	<div class="row">
	    <div class="col-12 col-xs-12 col-sm-6">
	    	<div class="card h-100">
		        <div class="card-body">
			        <h4 class="card_title">{LAN=LAN_VSTORE_DB_BILLING}</h4>
			
			        {DASHBOARD: billing_address}
			        
			    </div>
			     <div class="card-footer text-right text-end">
			        {DASHBOARD: edit_billing}
			     </div>
		     </div>
	    </div>
	
	    <div class="col-12 col-xs-12 col-sm-6">
	    <div class="card h-100">
	    		<div class="card-body">
		        <h4 class="card_title">{LAN=LAN_VSTORE_FR_SHIPPING}</h4>
		
		        {DASHBOARD: shipping_address}
			      
		        </div>
		          <div class="card-footer text-right text-end">
			        {DASHBOARD: edit_shipping}
			      </div>
	        </div>
	    </div>
    </div>

';


/**
 * Shipping details form
 */
$VSTORE_DASHBOARD_TEMPLATE['address']['edit']['shipping']['body'] = '
	<h4>{LAN=LAN_VSTORE_FR_SHIPPING_DETAILS}</h4>

	<div class="row g-3">
		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="ship_firstname" class="form-label required">{LAN=LAN_VSTORE_FR_FIRSTNAME}</label>
				{SHIPPING_FIELD: ship_firstname}
			</div>
		</div>
		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="ship_lastname" class="form-label required">{LAN=LAN_VSTORE_FR_LASTNAME}</label>
				{SHIPPING_FIELD: ship_lastname}
			</div>
		</div>

		<div class="col col-xs-12">
			<div class="form-group">
				<label for="ship_company" class="form-label">{LAN=LAN_VSTORE_FR_COMPANY}</label>
				{SHIPPING_FIELD: ship_company}
			</div>
		</div>

		<div class="col col-xs-12">
			<div class="form-group">
				<label for="ship_address" class="form-label required">{LAN=LAN_VSTORE_FR_ADDRESS}</label>
				{SHIPPING_FIELD: ship_address}
			</div>
		</div>

		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="ship_city" class="form-label required">{LAN=LAN_VSTORE_FR_CITY}</label>
				{SHIPPING_FIELD: ship_city}
			</div>
		</div>
		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="ship_state" class="form-label required">{LAN=LAN_VSTORE_FR_STATE}</label>
				{SHIPPING_FIELD: ship_state}
			</div>
		</div>

		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="ship_zip" class="form-label required">{LAN=LAN_VSTORE_FR_ZIP}</label>
				{SHIPPING_FIELD: ship_zip}
			</div>
		</div>
		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="ship_country" class="form-label required">{LAN=LAN_VSTORE_FR_COUNTRY}</label>
				{SHIPPING_FIELD: ship_country}
			</div>
		</div>

		<div class="col col-xs-12">
			<div class="form-group">
				<label for="ship_phone" class="form-label">{LAN=LAN_VSTORE_FR_PHONE}</label>
				{SHIPPING_FIELD: ship_phone}
			</div>
		</div>

		<div class="col-12 col-xs-12">
			<div class="form-group">
				<label class="required"></label> {LAN=LAN_VSTORE_FR_REQUIRED}
			</div>
		</div>
	</div>
    ';
    

/**
 * Customer details form
 */
$VSTORE_DASHBOARD_TEMPLATE['address']['edit']['billing']['body'] = '
	<h4>{LAN=LAN_VSTORE_FR_BILLING_ADDRESS}</h4>

	<div class="row g-3">
		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="cust_firstname" class="form-label required">{LAN=LAN_VSTORE_FR_FIRSTNAME}</label>
				{CUSTOMER_FIELD: cust_firstname}
			</div>
		</div>
		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="cust_lastname" class="form-label required">{LAN=LAN_VSTORE_FR_LASTNAME}</label>
				{CUSTOMER_FIELD: cust_lastname}
			</div>
		</div>

		<div class="col col-xs-12">
			<div class="form-group">
				<label for="cust_company" class="form-label">{LAN=LAN_VSTORE_FR_COMPANY}</label>
				{CUSTOMER_FIELD: cust_company}
			</div>
		</div>

		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="cust_vat_id" class="form-label">{LAN=LAN_VSTORE_FR_VAT_ID}</label>
				{CUSTOMER_FIELD: cust_vat_id}
			</div>
		</div>
		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="cust_taxcode" class="form-label">{LAN=LAN_VSTORE_FR_TAXCODE}</label>
				{CUSTOMER_FIELD: cust_taxcode}
			</div>
		</div>

		<div class="col col-xs-12">
			<div class="form-group">
				<label for="cust_address" class="form-label required">{LAN=LAN_VSTORE_FR_ADDRESS}</label>
				{CUSTOMER_FIELD: cust_address}
			</div>
		</div>

		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="cust_city" class="form-label required">{LAN=LAN_VSTORE_FR_CITY}</label>
				{CUSTOMER_FIELD: cust_city}
			</div>
		</div>
		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="cust_state" class="form-label">{LAN=LAN_VSTORE_FR_STATE}</label>
				{CUSTOMER_FIELD: cust_state}
			</div>
		</div>

		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="cust_zip" class="form-label required">{LAN=LAN_VSTORE_FR_ZIP}</label>
				{CUSTOMER_FIELD: cust_zip}
			</div>
		</div>
		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="cust_country" class="form-label required">{LAN=LAN_VSTORE_FR_COUNTRY}</label>
				{CUSTOMER_FIELD: cust_country}
			</div>
		</div>

		<div class="col-12 col-xs-12">
			<div class="form-group">
				<label for="cust_email" class="form-label required">{LAN=LAN_VSTORE_FR_EMAIL}</label>
				{CUSTOMER_FIELD: cust_email}
			</div>
		</div>

		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="cust_phone" class="form-label">{LAN=LAN_VSTORE_FR_PHONE}</label>
				{CUSTOMER_FIELD: cust_phone}
			</div>
		</div>
		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="cust_fax" class="form-label">{LAN=LAN_VSTORE_FR_FAX}</label>
				{CUSTOMER_FIELD: cust_fax}
			</div>
		</div>


	{CUSTOMER_FIELD: add_field0}

	{CUSTOMER_FIELD: add_field1}
	
	{CUSTOMER_FIELD: add_field2}
	
	{CUSTOMER_FIELD: add_field3}


		<div class="col-12 col-xs-12">
			<div class="form-group">
				<label class="required"></label> {LAN=LAN_VSTORE_FR_REQUIRED}
			</div>
		</div>
	</div>
';

$VSTORE_DASHBOARD_TEMPLATE['address']['edit']['billing']['additional']['item'] = '

		<div class="col-12 col-md-12">
			<div class="form-group">
				{CUSTOMER_ADD_LABEL}
				{CUSTOMER_ADD_FIELD}
			</div>
		</div>

';
    