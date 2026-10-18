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
<div class="vstore vstore-dash">
    <div class="vstore-dash-hero">
        <span class="vstore-dash-avatar">{DASHBOARD: avatar}</span>
        <div>
            <h3 class="vstore-dash-hello">{DASHBOARD: hello}</h3>
            <p class="vstore-dash-sub">{DASHBOARD: subtitle}</p>
        </div>
    </div>

    {DASHBOARD: nav}

    <div class="vstore-dash-body">
';

$VSTORE_DASHBOARD_TEMPLATE['footer'] = '
    </div>
</div>
';

/**
 * Dashboard navigation
 */
$VSTORE_DASHBOARD_TEMPLATE['nav']['start'] = '
	<ul class="nav vstore-pillnav">';
$VSTORE_DASHBOARD_TEMPLATE['nav']['end'] = '
	</ul>
';
$VSTORE_DASHBOARD_TEMPLATE['nav']['item'] = '
		<li role="presentation" class="nav-item [active]"><a class="nav-link" href="[url]">[caption]</a></li>';


/**
 * Dashboard landing page: summary boxes, spending chart, latest orders and shortcuts
 * Tokens in [brackets] are replaced by the DASHBOARD shortcode.
 */
$VSTORE_DASHBOARD_TEMPLATE['dashboard'] = '
    <div class="vstore-kpis">{DASHBOARD: kpis}</div>

    <div class="row g-4 mb-4">
        <div class="col-lg-7">
            <section class="vstore-panel h-100 mb-0">
                <h4 class="vstore-panel-title"><span class="vstore-panel-icon"><i class="fa fa-bar-chart" aria-hidden="true"></i></span> {LAN=LAN_VSTORE_DB_CHART_TITLE}</h4>
                {DASHBOARD: chart}
            </section>
        </div>
        <div class="col-lg-5">
            <section class="vstore-panel h-100 mb-0">
                <h4 class="vstore-panel-title"><span class="vstore-panel-icon"><i class="fa fa-clock-o" aria-hidden="true"></i></span> {LAN=LAN_VSTORE_DB_RECENT_TITLE} <a class="vstore-panel-link" href="{DASHBOARD: orders_url}">{LAN=LAN_VSTORE_DB_VIEW_ALL} &raquo;</a></h4>
                {DASHBOARD: recent}
            </section>
        </div>
    </div>

    <div class="vstore-dash-quick">{DASHBOARD: quick}</div>
';

$VSTORE_DASHBOARD_TEMPLATE['kpi'] = '
        <a class="vstore-kpi" href="[url]">
            <span class="vstore-kpi-icon vstore-kpi-[tone]"><i class="fa [icon]" aria-hidden="true"></i></span>
            <span class="vstore-kpi-text">
                <span class="vstore-kpi-label">[label]</span>
                <span class="vstore-kpi-value">[value]</span>
            </span>
        </a>
';

$VSTORE_DASHBOARD_TEMPLATE['chart']['start'] = '
    <div class="vstore-chart" role="img" aria-label="{LAN=LAN_VSTORE_DB_CHART_TITLE}">';

$VSTORE_DASHBOARD_TEMPLATE['chart']['bar'] = '
        <div class="vstore-chart-col[empty]">
            <span class="vstore-chart-track"><span class="vstore-chart-bar" style="height:[pct]%" title="[label]"></span></span>
            <span class="vstore-chart-val">[value]</span>
            <span class="vstore-chart-label">[label]</span>
        </div>';

$VSTORE_DASHBOARD_TEMPLATE['chart']['end'] = '
    </div>
';

$VSTORE_DASHBOARD_TEMPLATE['chart']['empty'] = '
    <p class="vstore-empty"><i class="fa fa-bar-chart" aria-hidden="true"></i> {LAN=LAN_VSTORE_DB_CHART_EMPTY}</p>
';

$VSTORE_DASHBOARD_TEMPLATE['recent']['start'] = '
    <ul class="vstore-recent">';

$VSTORE_DASHBOARD_TEMPLATE['recent']['item'] = '
        <li>
            <a class="vstore-recent-item" href="[url]">
                <span class="vstore-recent-icon"><i class="fa [icon]" aria-hidden="true"></i></span>
                <span class="vstore-recent-body"><strong>[ref]</strong><small>[date]</small></span>
                <span class="vstore-recent-meta"><b>[total]</b>[status]</span>
            </a>
        </li>';

$VSTORE_DASHBOARD_TEMPLATE['recent']['end'] = '
    </ul>
';

$VSTORE_DASHBOARD_TEMPLATE['recent']['empty'] = '
    <p class="vstore-empty"><i class="fa fa-shopping-bag" aria-hidden="true"></i> {LAN=LAN_VSTORE_DB_MSG_NO_ORDERS}</p>
';

$VSTORE_DASHBOARD_TEMPLATE['quick'] = '
        <a class="btn btn-primary" href="[shop_url]"><i class="fa fa-shopping-cart" aria-hidden="true"></i> {LAN=LAN_VSTORE_DB_QUICK_SHOP}</a>
        <a class="btn btn-default btn-secondary" href="[address_url]"><i class="fa fa-map-marker" aria-hidden="true"></i> {LAN=LAN_VSTORE_DB_QUICK_ADDR}</a>
        <a class="btn btn-default btn-secondary" href="[account_url]"><i class="fa fa-user" aria-hidden="true"></i> {LAN=LAN_VSTORE_DB_QUICK_ACCOUNT}</a>
';


/**
 * List orders
 */
$VSTORE_DASHBOARD_TEMPLATE['order']['list']['header'] = '
    <section class="vstore-panel">
    <div class="vstore-table-wrap">
    <table class="table vstore-table vstore-orders">
    <thead>
    <tr>
        <th>{LAN=LAN_VSTORE_DB_ORDER_INFO}</th>
        <th>{LAN=LAN_VSTORE_FR_SHIPPING}</th>
        <th>{LAN=LAN_VSTORE_FR_ITEMS}</th>
        <th class="text-right text-end">{LAN=LAN_VSTORE_FR_TOTAL}</th>
        <th>{LAN=LAN_VSTORE_DB_STATUS}</th>
        <th>{LAN=LAN_VSTORE_DB_ACTIONS}</th>
    </tr>
    </thead>
    <tbody>
';


$VSTORE_DASHBOARD_TEMPLATE['order']['list']['footer'] = '
    </tbody>
    </table>
    </div>
    </section>
';


$VSTORE_DASHBOARD_TEMPLATE['order']['list']['item'] = '
    <tr>
        <td>
            <strong>{ORDER_DATA: order_ref}</strong><br />
            <small class="text-muted">{ORDER_DATA: order_date}</small><br />
            <small>{LAN=LAN_VSTORE_DB_INVOICE}: {ORDER_DATA: order_invoice_link}</small>
        </td>
        <td>{ORDER_DATA: order_shipping_full}</td>
        <td>{ORDER_DATA: order_items_short}</td>
        <td class="text-right text-end"><strong>{ORDER_DATA: order_pay_amount}</strong></td>
        <td>{ORDER_DATA: order_status_label}</td>
        <td>{ORDER_ACTIONS}</td>
    </tr>
';


/**
 * Order detail
 */
$VSTORE_DASHBOARD_TEMPLATE['order']['detail'] = '
    <div class="vstore-order-head">
        <h4>{LAN=LAN_VSTORE_DB_ORDER_DETAILS} {ORDER_DATA: order_ref}</h4>
        {ORDER_DATA: order_status_label}
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <section class="vstore-panel h-100 mb-0">
                <h5 class="vstore-panel-title"><span class="vstore-panel-icon"><i class="fa fa-info" aria-hidden="true"></i></span> {LAN=LAN_VSTORE_DB_ORDER}</h5>
                <p class="mb-1"><b>{LAN=LAN_VSTORE_DB_DATE}</b> {ORDER_DATA: order_date}</p>
                <p class="mb-1"><b>{LAN=LAN_VSTORE_DB_ORDER}</b> {ORDER_DATA: order_ref}</p>
                <p class="mb-1"><b>{LAN=LAN_VSTORE_DB_INVOICE}</b> {ORDER_DATA: order_invoice_link}</p>
                <p class="mb-1"><b>{LAN=LAN_VSTORE_DB_PAYMENT_METHOD}</b> {ORDER_DATA: order_gateway}</p>
                <p class="mb-0"><b>{LAN=LAN_VSTORE_DB_PAYMENT_COMPLETE}</b> {ORDER_DATA: order_pay_status}</p>
            </section>
        </div>

        <div class="col-md-4">
            <section class="vstore-panel h-100 mb-0">
                <h5 class="vstore-panel-title"><span class="vstore-panel-icon"><i class="fa fa-truck" aria-hidden="true"></i></span> {LAN=LAN_VSTORE_FR_SHIPPING_ADDRESS}</h5>
                {ORDER_DATA: order_shipping_full}
            </section>
        </div>

        <div class="col-md-4">
            <section class="vstore-panel h-100 mb-0">
                <h5 class="vstore-panel-title"><span class="vstore-panel-icon"><i class="fa fa-id-card-o" aria-hidden="true"></i></span> {LAN=LAN_VSTORE_FR_BILLING_ADDRESS}</h5>
                {ORDER_DATA: order_billing_full}
                <p class="mb-0 mt-2">{ORDER_DATA: order_billing_contact}</p>
            </section>
        </div>
    </div>

    <section class="vstore-panel">
        <h5 class="vstore-panel-title"><span class="vstore-panel-icon"><i class="fa fa-shopping-bag" aria-hidden="true"></i></span> {LAN=LAN_VSTORE_FR_ITEMS}</h5>
        {ORDER_ITEMS}
    </section>

    <section class="vstore-panel">
        <h5 class="vstore-panel-title"><span class="vstore-panel-icon"><i class="fa fa-history" aria-hidden="true"></i></span> {LAN=LAN_VSTORE_DB_LOG}</h5>
        {ORDER_DATA: order_log}
    </section>

    <div class="vstore-co-actions justify-content-center">
        {ORDER_ACTIONS: invoice}
        {ORDER_ACTIONS: cancel}
    </div>
';


/**
 * List downloads
 */
$VSTORE_DASHBOARD_TEMPLATE['download']['list']['header'] = '
    <section class="vstore-panel">
    <div class="vstore-table-wrap">
    <table class="table vstore-table">
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
    </div>
    </section>
';


$VSTORE_DASHBOARD_TEMPLATE['download']['list']['item'] = '
    <tr>
        <td>
            <strong>{ORDER_DATA: order_ref}</strong><br />
            <small class="text-muted">{ORDER_DATA: order_date}</small><br />
            <small>{LAN=LAN_VSTORE_DB_INVOICE}: {ORDER_DATA: order_invoice_link}</small>
        </td>
        <td>{ORDER_DATA: order_downloads}</td>
        <td>{ORDER_DATA: order_status_label}</td>
    </tr>
';


/**
 * Saved addresses
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
    