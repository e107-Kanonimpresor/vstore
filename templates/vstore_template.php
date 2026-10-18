<?php
/**
 * Vstore - shopping cart plugin for e107.
 * Based on e107inc/vstore (CaMerOn, Achim Ennenbach, e107 Inc). Modified by Kanonimpresor, 2025-2026.
 *
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.html
 */

e107::lan('vstore', 'front_shop', true);

$VSTORE_TEMPLATE = array();


/**
 * Category list: image tiles with the number of products
 */
$VSTORE_TEMPLATE['cat']['start']   = '
	<div class="vstore vstore-grid vstore-grid-cats">
';

$VSTORE_TEMPLATE['cat']['item']   = '
		{SETIMAGE: w=640&h=420&crop=1}
		<article class="vstore-tile vstore-category-list">
			<div class="vstore-tile-media">{CAT_PIC: class=img-fluid}</div>
			<div class="vstore-tile-content">
				<span class="vstore-tile-count">{CAT_COUNT}</span>
				<h3 class="vstore-tile-title"><a href="{CAT_URL}">{CAT_NAME}</a></h3>
				<div class="vstore-tile-text cat-description">{CAT_DESCRIPTION}</div>
				<span class="vstore-tile-arrow" aria-hidden="true">&rarr;</span>
			</div>
		</article>
';

$VSTORE_TEMPLATE['cat']['end']   = '
	</div>
';


/**
 * Product list: cards with picture, badges, description, price and button
 */
$VSTORE_TEMPLATE['list']['start']   = '<div class="vstore vstore-listing"><div class="vstore-cat-info">{CAT_INFO}</div><div class="vstore-grid">';
$VSTORE_TEMPLATE['list']['item']    =  '
		{SETIMAGE: w=640&h=480&crop=1}
		<article class="vstore-card vstore-product-list{ITEM_STATE}">
			<a class="vstore-card-media" href="{ITEM_URL}" tabindex="-1" aria-hidden="true">
				{ITEM_PIC: class=img-fluid}
				<span class="vstore-badges">{ITEM_BADGES}</span>
			</a>
			<div class="vstore-card-body">
				<a class="vstore-card-kicker" href="{ITEM_BRAND_URL}">{ITEM_BRAND}</a>
				<h3 class="vstore-card-title"><a href="{ITEM_URL}">{ITEM_NAME}</a></h3>
				<div class="vstore-card-text item-description">{ITEM_DESCRIPTION: limit=120}</div>

				{ITEM_VARS}

				<div class="vstore-card-footer">
					<a class="vstore-price item-price" href="{ITEM_URL}">{ITEM_PRICE}</a>
					{ITEM_ADDTOCART: class=btn btn-sm btn-success vstore-add&class0=btn btn-sm btn-secondary disabled vstore-add}
				</div>
			</div>
		</article>
		';

$VSTORE_TEMPLATE['list']['end']         = '</div></div>';




/**
 * Product menu (menu area): compact cards
 */
$VSTORE_TEMPLATE['menu']['start'] =  '<div class="vstore vstore-mini-list">';

$VSTORE_TEMPLATE['menu']['item'] =  '
			{SETIMAGE: w=160&h=160&crop=1}
			<article class="vstore-mini vstore-product-list{ITEM_STATE}">
				<a class="vstore-mini-media" href="{ITEM_URL}" tabindex="-1" aria-hidden="true">{ITEM_PIC: class=img-fluid}</a>
				<div class="vstore-mini-body">
					<a class="vstore-mini-title" href="{ITEM_URL}">{ITEM_NAME}</a>
					<div class="vstore-mini-footer">
						<span class="vstore-price item-price">{ITEM_PRICE}</span>
						{ITEM_ADDTOCART: class=btn btn-sm btn-success vstore-add&class0=btn btn-sm btn-secondary disabled vstore-add}
					</div>
				</div>
			</article>';

$VSTORE_TEMPLATE['menu']['end'] =  '</div>';


/**
 * Product page
 */
$VSTORE_TEMPLATE['item']['main']        = '{SETIMAGE: w=900&h=720&crop=1}
											<div class="vstore vstore-product-view vstore-pv">
												<div class="vstore-pv-gallery">
													<div class="vstore-pv-main vstore-zoom">
														<span class="vstore-badges">{ITEM_BADGES}</span>
														{ITEM_PIC: item=0&link=1}
													</div>
													{ITEM_THUMBS}
												</div>
												<div class="vstore-pv-info">
													<a class="vstore-card-kicker" href="{ITEM_BRAND_URL}">{ITEM_BRAND}</a>
													<h2 class="vstore-pv-title">{ITEM_NAME}</h2>
													<div class="vstore-pv-lead">{ITEM_DESCRIPTION}</div>

													{ITEM_VARS}

													<div class="vstore-pv-buy">
														<div class="vstore-pv-price item-price">
															{ITEM_PRICE}
															<small class="vstore-pv-note">{LAN=LAN_VSTORE_FR_PRICE_NOTE}</small>
														</div>
														<div class="vstore-pv-actions">
															{ITEM_QTY}{ITEM_ADDTOCART: class=btn btn-lg btn-success vstore-add&class0=btn btn-lg btn-secondary disabled vstore-add}
														</div>
													</div>

													<ul class="vstore-pv-meta">
														<li><span>{LAN=LAN_VSTORE_FR_PRODUCT_CODE}</span> {ITEM_CODE}</li>
														<li><span>{LAN=LAN_VSTORE_FR_AVAILABILITY}</span> {ITEM_AVAILABILITY}</li>
														{ITEM_WEIGHT}
													</ul>
												</div>
											</div>';

$VSTORE_TEMPLATE['item']['details'] = '{ITEM_DETAILS}';
$VSTORE_TEMPLATE['item']['videos'] = '
					{ITEM_VIDEO=0}
					{ITEM_VIDEO=1}
					{ITEM_VIDEO=2}
					{ITEM_VIDEO=3}
			';


$VSTORE_TEMPLATE['item']['files']       = '{ITEM_FILES}';
$VSTORE_TEMPLATE['item']['reviews']     = '{ITEM_REVIEWS}';
$VSTORE_TEMPLATE['item']['related']     = '{ITEM_RELATED}';
$VSTORE_TEMPLATE['item']['howto']       = '{PREF_HOWTOORDER}';

$VSTORE_WRAPPER['item']['ITEM_DETAILS'] = "<p>{---}</p>";
$VSTORE_WRAPPER['item']['ITEM_FILES']   = "<p>{---}</p>";
$VSTORE_WRAPPER['item']['ITEM_REVIEWS'] = "<p>{---}</p>";
$VSTORE_WRAPPER['item']['ITEM_RELATED'] = "<p>{---}</p>";
$VSTORE_WRAPPER['item']['PREF_HOWTOORDER']     = "<p>{---}</p>";
$VSTORE_WRAPPER['item']['ITEM_VIDEO']   = "<div class='col-md-6'><p>{---}</p></div>";
$VSTORE_WRAPPER['item']['ITEM_WEIGHT']  = "<li>{---}</li>";


/**
 * Checkout: progress steps, page layout and order summary side panel
 * Tokens in [brackets] are replaced by vstore::checkoutPage() and vstore::checkoutAside()
 */
$VSTORE_TEMPLATE['checkout']['steps'] = '
<ol class="vstore-steps" aria-label="{LAN=LAN_VSTORE_FR_CHECKOUT}">
	<li class="vstore-step is-done"><a href="[cart_url]"><span class="vstore-step-num"><b>1</b><i class="fa fa-check" aria-hidden="true"></i></span><span class="vstore-step-name">{LAN=LAN_VSTORE_MSG_BC_CART}</span></a></li>
	<li class="vstore-step [step2]"><span class="vstore-step-num"><b>2</b><i class="fa fa-check" aria-hidden="true"></i></span><span class="vstore-step-name">{LAN=LAN_VSTORE_FR_STEP_DETAILS}</span></li>
	<li class="vstore-step [step3]"><span class="vstore-step-num"><b>3</b><i class="fa fa-check" aria-hidden="true"></i></span><span class="vstore-step-name">{LAN=LAN_VSTORE_FR_SUMMARY}</span></li>
</ol>
';

$VSTORE_TEMPLATE['checkout']['layout'] = '
<div class="vstore vstore-checkout">
	[steps]
	<div class="vstore-co-grid[grid_class]">
		<div class="vstore-co-main">[main]</div>
		[aside]
	</div>
</div>
';

$VSTORE_TEMPLATE['checkout']['aside_start'] = '
<aside class="vstore-co-aside vstore-summary">
	<h4 class="vstore-summary-title"><i class="fa fa-shopping-bag" aria-hidden="true"></i> {LAN=LAN_VSTORE_FR_SUMMARY} <small>[count]</small></h4>
	<ul class="vstore-summary-list">
';

$VSTORE_TEMPLATE['checkout']['aside_item'] = '
		<li class="vstore-summary-item">
			<span class="vstore-summary-pic">[pic]</span>
			<span class="vstore-summary-name">[name]<small>[vars]</small></span>
			<span class="vstore-summary-qty">&times;[qty]</span>
			<span class="vstore-summary-price">[total]</span>
		</li>
';

$VSTORE_TEMPLATE['checkout']['aside_item_quote'] = '
		<li class="vstore-summary-item">
			<span class="vstore-summary-pic">[pic]</span>
			<span class="vstore-summary-name">[name]<small>[vars]</small></span>
			<span class="vstore-summary-qty">&times;[qty]</span>
		</li>
';

$VSTORE_TEMPLATE['checkout']['aside_row'] = '
		<div class="vstore-summary-row[class]"><dt>[label]</dt><dd>[value]</dd></div>
';

$VSTORE_TEMPLATE['checkout']['aside_end'] = '
	</ul>
	<dl class="vstore-summary-totals">[rows]</dl>
</aside>
';

$VSTORE_TEMPLATE['checkout']['aside_end_quote'] = '
	</ul>
	<p class="vstore-summary-note"><i class="fa fa-info-circle" aria-hidden="true"></i> {LAN=LAN_VSTORE_QUOTE_CART_NOTE}</p>
</aside>
';


/**
 * Order summary and confirmation page
 */
$VSTORE_TEMPLATE['orderconfirm']['main'] = '
		<div class="vstore-confirm">
			<div class="vstore-confirm-cols">
				<div class="vstore-confirm-side">
					{CONFIRM_FIELD: billing_address}

					{CONFIRM_FIELD: shipping_address}

					<section class="vstore-panel">
						<h4 class="vstore-panel-title"><span class="vstore-panel-icon"><i class="fa fa-credit-card" aria-hidden="true"></i></span> {LAN=LAN_VSTORE_FR_PAYMENT_METHOD}</h4>
						<p class="vstore-pay">{ORDER_GATEWAY_ICON} {ORDER_GATEWAY_TITLE}</p>
					</section>
				</div>

				<div class="vstore-confirm-main">
					<section class="vstore-panel">
						<h4 class="vstore-panel-title"><span class="vstore-panel-icon"><i class="fa fa-shopping-bag" aria-hidden="true"></i></span> {LAN=LAN_VSTORE_FR_ITEMS}</h4>
						{CONFIRM_ITEMS}
					</section>

					<section class="vstore-panel">
						<h4 class="vstore-panel-title"><span class="vstore-panel-icon"><i class="fa fa-pencil" aria-hidden="true"></i></span> {LAN=LAN_VSTORE_FR_ORDER_NOTES}</h4>
						{SHIPPING_FIELD: ship_notes}
					</section>
				</div>
			</div>

			<div class="vstore-co-actions">
				<a class="btn btn-default btn-secondary vstore-btn-back-confirm" href="{ORDER_CHECKOUT_URL}">&laquo; {LAN=LAN_VSTORE_FR_BACK}</a>
				<button class="btn btn-primary btn-lg vstore-btn-buy-now" type="submit" name="mode" value="confirmed">{ORDER_GATEWAY_ICON: size=1x} {LAN=LAN_VSTORE_FR_BUY_NOW}</button>
			</div>
		</div>
		';

$VSTORE_TEMPLATE['orderconfirm']['billing'] = '
			<section class="vstore-panel vstore-address">
				<h4 class="vstore-panel-title"><span class="vstore-panel-icon"><i class="fa fa-id-card-o" aria-hidden="true"></i></span> {CONFIRM_FIELD: billing_title}</h4>

				<p class="vstore-address-name">{CONFIRM_FIELD: cust_title} {CONFIRM_FIELD: cust_firstname} {CONFIRM_FIELD: cust_lastname}</p>
				<p>{CONFIRM_FIELD: cust_company}</p>
				<p>{CONFIRM_FIELD: cust_vat_id}</p>
				<p>{CONFIRM_FIELD: cust_taxcode}</p>
				<p>{CONFIRM_FIELD: cust_address}</p>
				<p>{CONFIRM_FIELD: cust_city}, {CONFIRM_FIELD: cust_state} {CONFIRM_FIELD: cust_zip}</p>
				<p>{CONFIRM_FIELD: cust_country}</p>
			</section>
			';

$VSTORE_TEMPLATE['orderconfirm']['shipping'] = '
			<section class="vstore-panel vstore-address">
				<h4 class="vstore-panel-title"><span class="vstore-panel-icon"><i class="fa fa-truck" aria-hidden="true"></i></span> {LAN=LAN_VSTORE_FR_SHIPPING_ADDRESS}</h4>

				<p class="vstore-address-name">{CONFIRM_FIELD: ship_firstname} {CONFIRM_FIELD: ship_lastname}</p>
				<p>{CONFIRM_FIELD: ship_company}</p>
				<p>{CONFIRM_FIELD: ship_address}</p>
				<p>{CONFIRM_FIELD: ship_city}, {CONFIRM_FIELD: ship_state} {CONFIRM_FIELD: ship_zip}</p>
				<p>{CONFIRM_FIELD: ship_country}</p>
			</section>
			';

/**
 * Order items list
 * Used on the order summary and confirmation page
 */

$VSTORE_TEMPLATE['confirm_items']['header'] = '
<div class="vstore-table-wrap">
<table class="table vstore-table">
<colgroup>
	<col style="width:50%" />
	<col  />
	<col  />
	<col  />
</colgroup>
<thead>
<tr>
	<th>{LAN=LAN_VSTORE_FR_DESCRIPTION}</th>
	<th class="text-right text-end">{LAN=LAN_VSTORE_FR_UNIT_PRICE}</th>
	<th class="text-right text-end">{LAN=LAN_VSTORE_FR_QTY}</th>
	<th class="text-right text-end">{LAN=LAN_VSTORE_FR_AMOUNT}</th>
</tr>
</thead>
<tbody>
';

$VSTORE_TEMPLATE['confirm_items']['row'] = '
<tr>
	<td>{CONFIRM_DATA: name}</td>
	<td class="text-right text-end">{CONFIRM_DATA: price}</td>
	<td class="text-right text-end">{CONFIRM_DATA: quantity}</td>
	<td class="text-right text-end">{CONFIRM_DATA: item_total}</td>
</tr>';

$VSTORE_TEMPLATE['confirm_items']['footer'] = '
</tbody>
<tfoot>
<tr>
	<td colspan="3"><b>{LAN=LAN_VSTORE_FR_SUBTOTAL}</b></td>
	<td class="text-right text-end">{CONFIRM_DATA: sub_total}</td>
</tr>
<tr>
	<td colspan="3"><b>{LAN=LAN_VSTORE_FR_SHIPPING}</b></td>
	<td class="text-right text-end">{CONFIRM_DATA: shipping_total}</td>
</tr>
{CONFIRM_COUPON}
{CONFIRM_TAX}
<tr class="vstore-table-total">
	<td colspan="3"><b>{LAN=LAN_VSTORE_FR_TOTAL}</b></td>
	<td class="text-right text-end"><b>{CONFIRM_DATA: grand_total}</b></td>
</tr>
</tfoot>
</table>
</div>
';

$VSTORE_TEMPLATE['confirm_items']['coupon'] = '
<tr>
	<td colspan="3"><b>{LAN=LAN_VSTORE_FR_COUPON}:</b> {CONFIRM_DATA: coupon}</td>
	<td class="text-right text-end">{CONFIRM_DATA: coupon_amount}</td>
</tr>
';


$VSTORE_TEMPLATE['confirm_items']['tax'] = '
<tr>
	<td colspan="2"><b>'.LAN_VSTORE_FR_TAX.'</b></td>
	<td class="text-right text-end">[x]</td>
	<td class="text-right text-end">[y]</td>
</tr>
';

/**
 * Order items list
 * Used in the order details of the customer dashboard
 */

$VSTORE_TEMPLATE['order_items']['header'] = '
<div class="vstore-table-wrap">
<table class="table vstore-table">
<colgroup>
	<col style="width:50%" />
	<col  />
	<col  />
	<col  />
</colgroup>
<thead>
<tr>
	<th>{LAN=LAN_VSTORE_FR_DESCRIPTION}</th>
	<th class="text-right text-end">{LAN=LAN_VSTORE_FR_UNIT_PRICE}</th>
	<th class="text-right text-end">{LAN=LAN_VSTORE_FR_QTY}</th>
	<th class="text-right text-end">{LAN=LAN_VSTORE_FR_AMOUNT}</th>
</tr>
</thead>
<tbody>
';

$VSTORE_TEMPLATE['order_items']['row'] = '
<tr>
	<td>{CART_DATA: name}</td>
	<td class="text-right text-end">{CART_DATA: price}</td>
	<td class="text-right text-end">{CART_DATA: quantity}</td>
	<td class="text-right text-end">{CART_DATA: item_total}</td>
</tr>';

$VSTORE_TEMPLATE['order_items']['footer'] = '
</tbody>
<tfoot>
<tr>
	<td colspan="3"><b>{LAN=LAN_VSTORE_FR_SUBTOTAL}</b></td>
	<td class="text-right text-end">{CART_DATA: sub_total}</td>
</tr>
<tr>
	<td colspan="3"><b>{LAN=LAN_VSTORE_FR_SHIPPING}</b></td>
	<td class="text-right text-end">{CART_DATA: shipping_total}</td>
</tr>
{ORDER_COUPON}
{ORDER_TAX}
<tr class="vstore-table-total">
	<td colspan="3"><b>{LAN=LAN_VSTORE_FR_TOTAL}</b></td>
	<td class="text-right text-end"><b>{CART_DATA: grand_total}</b></td>
</tr>
</tfoot>
</table>
</div>
';

$VSTORE_TEMPLATE['order_items']['coupon'] = '
<tr>
	<td colspan="3" class="text-right text-end">{LAN=LAN_VSTORE_FR_COUPON}: <b>{CART_DATA: coupon}</b></td>
	<td class="text-right text-end">{CART_DATA: coupon_amount}</td>
</tr>
';


$VSTORE_TEMPLATE['order_items']['tax'] = '
<tr>
	<td colspan="2"><b>'.LAN_VSTORE_FR_TAX.'</b></td>
	<td class="text-right text-end">[x]</td>
	<td class="text-right text-end">[y]</td>
</tr>
';

/**
 * Shipping details form
 */
$VSTORE_TEMPLATE['shipping']['header'] = '
	<section class="vstore-panel">
	<h3 class="vstore-panel-title"><span class="vstore-panel-icon"><i class="fa fa-truck" aria-hidden="true"></i></span> {LAN=LAN_VSTORE_FR_SHIPPING_DETAILS}</h3>

	<div class="row g-3">
		<div class="col-12 col-sm-6">
			<div class="form-group">
				<label for="ship_firstname" class="form-label required">{LAN=LAN_VSTORE_FR_FIRSTNAME}</label>
				{SHIPPING_FIELD: ship_firstname}
			</div>
		</div>
		<div class="col-12 col-sm-6">
			<div class="form-group">
				<label for="ship_lastname" class="form-label required">{LAN=LAN_VSTORE_FR_LASTNAME}</label>
				{SHIPPING_FIELD: ship_lastname}
			</div>
		</div>

		<div class="col-12">
			<div class="form-group">
				<label for="ship_company" class="form-label">{LAN=LAN_VSTORE_FR_COMPANY}</label>
				{SHIPPING_FIELD: ship_company}
			</div>
		</div>

		<div class="col-12">
			<div class="form-group">
				<label for="ship_address" class="form-label required">{LAN=LAN_VSTORE_FR_ADDRESS}</label>
				{SHIPPING_FIELD: ship_address}
			</div>
		</div>

		<div class="col-12 col-sm-6">
			<div class="form-group">
				<label for="ship_city" class="form-label required">{LAN=LAN_VSTORE_FR_CITY}</label>
				{SHIPPING_FIELD: ship_city}
			</div>
		</div>
		<div class="col-12 col-sm-6">
			<div class="form-group">
				<label for="ship_state" class="form-label required">{LAN=LAN_VSTORE_FR_STATE}</label>
				{SHIPPING_FIELD: ship_state}
			</div>
		</div>

		<div class="col-12 col-sm-6">
			<div class="form-group">
				<label for="ship_zip" class="form-label required">{LAN=LAN_VSTORE_FR_ZIP}</label>
				{SHIPPING_FIELD: ship_zip}
			</div>
		</div>
		<div class="col-12 col-sm-6">
			<div class="form-group">
				<label for="ship_country" class="form-label required">{LAN=LAN_VSTORE_FR_COUNTRY}</label>
				{SHIPPING_FIELD: ship_country}
			</div>
		</div>

		<div class="col-12">
			<div class="form-group">
				<label for="ship_phone" class="form-label">{LAN=LAN_VSTORE_FR_PHONE}</label>
				{SHIPPING_FIELD: ship_phone}
			</div>
		</div>

		<div class="col-12">
			<p class="vstore-required"><span class="required"></span> {LAN=LAN_VSTORE_FR_REQUIRED}</p>
		</div>
	</div>
	</section>
	';



/**
 * Customer details form
 * @todo make VAT and Tax code optional by using wrappers and prefs.
 */
$VSTORE_TEMPLATE['customer']['header'] = '
	<section class="vstore-panel">
	<h3 class="vstore-panel-title"><span class="vstore-panel-icon"><i class="fa fa-id-card-o" aria-hidden="true"></i></span> {LAN=LAN_VSTORE_FR_BILLING_ADDRESS}</h3>

	<div class="row g-3">
		<div class="col-12 col-sm-6">
			<div class="form-group">
				<label for="cust_firstname" class="form-label required">{LAN=LAN_VSTORE_FR_FIRSTNAME}</label>
				{CUSTOMER_FIELD: cust_firstname}
			</div>
		</div>
		<div class="col-12 col-sm-6">
			<div class="form-group">
				<label for="cust_lastname" class="form-label required">{LAN=LAN_VSTORE_FR_LASTNAME}</label>
				{CUSTOMER_FIELD: cust_lastname}
			</div>
		</div>

		<div class="col-12">
			<div class="form-group">
				<label for="cust_company" class="form-label">{LAN=LAN_VSTORE_FR_COMPANY}</label>
				{CUSTOMER_FIELD: cust_company}
			</div>
		</div>

		<div class="col-12 col-sm-6">
			<div class="form-group">
				<label for="cust_vat_id" class="form-label">{LAN=LAN_VSTORE_FR_VAT_ID}</label>
				{CUSTOMER_FIELD: cust_vat_id}
			</div>
		</div>
		<div class="col-12 col-sm-6">
			<div class="form-group">
				<label for="cust_taxcode" class="form-label">{LAN=LAN_VSTORE_FR_TAXCODE}</label>
				{CUSTOMER_FIELD: cust_taxcode}
			</div>
		</div>

		<div class="col-12">
			<div class="form-group">
				<label for="cust_address" class="form-label required">{LAN=LAN_VSTORE_FR_ADDRESS}</label>
				{CUSTOMER_FIELD: cust_address}
			</div>
		</div>

		<div class="col-12 col-sm-6">
			<div class="form-group">
				<label for="cust_city" class="form-label required">{LAN=LAN_VSTORE_FR_CITY}</label>
				{CUSTOMER_FIELD: cust_city}
			</div>
		</div>
		<div class="col-12 col-sm-6">
			<div class="form-group">
				<label for="cust_state" class="form-label">{LAN=LAN_VSTORE_FR_STATE}</label>
				{CUSTOMER_FIELD: cust_state}
			</div>
		</div>

		<div class="col-12 col-sm-6">
			<div class="form-group">
				<label for="cust_zip" class="form-label required">{LAN=LAN_VSTORE_FR_ZIP}</label>
				{CUSTOMER_FIELD: cust_zip}
			</div>
		</div>
		<div class="col-12 col-sm-6">
			<div class="form-group">
				<label for="cust_country" class="form-label required">{LAN=LAN_VSTORE_FR_COUNTRY}</label>
				{CUSTOMER_FIELD: cust_country}
			</div>
		</div>

		<div class="col-12">
			<div class="form-group">
				<label for="cust_email" class="form-label required">{LAN=LAN_VSTORE_FR_EMAIL}</label>
				{CUSTOMER_FIELD: cust_email}
			</div>
		</div>

		<div class="col-12 col-sm-6">
			<div class="form-group">
				<label for="cust_phone" class="form-label">{LAN=LAN_VSTORE_FR_PHONE}</label>
				{CUSTOMER_FIELD: cust_phone}
			</div>
		</div>
		<div class="col-12 col-sm-6">
			<div class="form-group">
				<label for="cust_fax" class="form-label">{LAN=LAN_VSTORE_FR_FAX}</label>
				{CUSTOMER_FIELD: cust_fax}
			</div>
		</div>


	{CUSTOMER_FIELD: add_field0}

	{CUSTOMER_FIELD: add_field1}

	{CUSTOMER_FIELD: add_field2}

	{CUSTOMER_FIELD: add_field3}


		<div class="col-12">
			<p class="vstore-required"><span class="required"></span> {LAN=LAN_VSTORE_FR_REQUIRED}</p>
		</div>
	</div>
	</section>
';

$VSTORE_TEMPLATE['customer']['additional']['item'] = '

		<div class="col-12 col-sm-6">
			<div class="form-group">
				{CUSTOMER_ADD_LABEL}
				{CUSTOMER_ADD_FIELD}
			</div>
		</div>

';

$VSTORE_TEMPLATE['customer']['guest'] = '
	<div class="vstore-guest">
		<section class="vstore-guest-card">
			<span class="vstore-guest-icon"><i class="fa fa-user-plus" aria-hidden="true"></i></span>
			<h4>{LAN=LAN_VSTORE_FR_NEW_CUSTOMER}</h4>
			<p>{LAN=LAN_VSTORE_FR_NEW_CUSTOMER_TEXT}</p>
			<a class="btn btn-primary" id="register" href="{SITEURL}signup.php" title="{LAN=LAN_VSTORE_FR_SIGN_UP}">{LAN=LAN_VSTORE_FR_SIGN_UP}</a>
		</section>

		<section class="vstore-guest-card">
			<span class="vstore-guest-icon"><i class="fa fa-shopping-bag" aria-hidden="true"></i></span>
			<h4>{LAN=LAN_VSTORE_FR_GUEST}</h4>
			<p>{LAN=LAN_VSTORE_FR_GUEST_TEXT}</p>
			<button name="as_guest" class="btn btn-default btn-secondary" type="submit" value="guest">{LAN=LAN_VSTORE_FR_GUEST}</button>
		</section>

		<section class="vstore-guest-card">
			<span class="vstore-guest-icon"><i class="fa fa-sign-in" aria-hidden="true"></i></span>
			<h4>{LAN=LAN_VSTORE_FR_HAS_ACCOUNT}</h4>
			<p>{LAN=LAN_VSTORE_FR_LET_LOGIN}</p>
			<form method="post" onsubmit="hashLoginPassword(this);return true" accept-charset="UTF-8">
			<div class="form-group mb-2">
				<input type="text" name="username" id="username" class="form-control input-sm tbox login user" placeholder="{LAN=LAN_VSTORE_FR_USERNAME_PH}" value="" maxlength="100">
			</div>
			<div class="form-group mb-2">
				<input type="password" name="userpass" id="userpass" class="form-control input-sm tbox login pass" placeholder="{LAN=LAN_VSTORE_FR_PASSWORD_PH}" size="15" value="" maxlength="30">
			</div>
			<button name="userlogin" class="btn btn-default btn-secondary" type="submit">{LAN=LAN_VSTORE_FR_LOGIN}</button>
			</form>
		</section>
	</div>
';



/**
 * Nav menu shopping cart
 */
$VSTORE_TEMPLATE['navcart']['empty'] = '
		<div id="vstore-cart-dropdown-empty" class="vstore-navcart-empty">
			<span class="vstore-navcart-empty-icon" aria-hidden="true"><i class="fa fa-shopping-cart"></i></span>
			<p>{LAN=LAN_VSTORE_FR_CART_EMPTY}</p>
			<a class="btn btn-primary btn-sm" href="{CART_DATA: index_url}">{LAN=LAN_VSTORE_FR_START_SHOPPING}</a>
		</div>
		<div class="d-grid gap-2">
			<a class="btn btn-secondary btn-sm" href="{CART_DATA: dashboard_url}"><i class="fa fa-tachometer" aria-hidden="true"></i> {LAN=LAN_VSTORE_FR_MY_DASHBOARD}</a>
		</div>
';

$VSTORE_TEMPLATE['navcart']['header'] = '
		<div class="vstore-navcart-scroll">
        	<ul class="vstore-navcart-list list-unstyled">
';

$VSTORE_TEMPLATE['navcart']['item'] = '
				<li class="vstore-navcart-item">
					<div class="vstore-navcart-pic">{CART_DATA: pic}</div>
					<div class="vstore-navcart-info">{CART_DATA: name}
						<span class="vstore-navcart-qty">{CART_DATA: quantity} &times; {CART_DATA: price}</span>
					</div>
				</li>
			';

$VSTORE_TEMPLATE['navcart']['footer'] = '
			</ul>
			<input type="hidden" id="vstore-item-count" value="{CART_DATA: item_count}"/>
		</div>

		<div class="vstore-navcart-total">
			<span>{LAN=LAN_VSTORE_FR_SUBTOTAL_COLON}</span>
			<strong>{CART_DATA: grand_total}</strong>
		</div>

		<div class="d-grid gap-2">
			<a class="btn btn-primary" href="{CART_DATA: cart_url}"><i class="fa fa-shopping-cart" aria-hidden="true"></i> {LAN=LAN_VSTORE_FR_CHECKOUT}</a>
			<a class="btn btn-secondary btn-sm" href="{CART_DATA: dashboard_url}"><i class="fa fa-tachometer" aria-hidden="true"></i> {LAN=LAN_VSTORE_FR_MY_DASHBOARD}</a>
			<a class="btn btn-link btn-sm vstore-navcart-clear" href="#" onclick="vstoreCartReset()"><i class="fa fa-trash-o" aria-hidden="true"></i> {LAN=LAN_VSTORE_FR_CLEAR_CART}</a>
		</div>
';

$VSTORE_TEMPLATE['navcart']['start'] = '
	<div id="vstore-cart-dropdown" class="dropdown-menu vstore vstore-navcart">
';

$VSTORE_TEMPLATE['navcart']['end'] = '
	</div>
';


/**
 * Quote requests: confirmation, order details and the cart menu without prices
 */
$VSTORE_TEMPLATE['orderconfirm']['quote_main'] = '
		<div class="vstore-confirm">
			<div class="vstore-confirm-cols">
				<div class="vstore-confirm-side">
					{CONFIRM_FIELD: billing_address}

					{CONFIRM_FIELD: shipping_address}
				</div>

				<div class="vstore-confirm-main">
					<section class="vstore-panel">
						<h4 class="vstore-panel-title"><span class="vstore-panel-icon"><i class="fa fa-file-text-o" aria-hidden="true"></i></span> {LAN=LAN_VSTORE_QUOTE_TITLE}</h4>
						{CONFIRM_ITEMS}
						<p class="vstore-summary-note"><i class="fa fa-info-circle" aria-hidden="true"></i> {LAN=LAN_VSTORE_QUOTE_CART_NOTE}</p>
					</section>

					<section class="vstore-panel">
						<h4 class="vstore-panel-title"><span class="vstore-panel-icon"><i class="fa fa-pencil" aria-hidden="true"></i></span> {LAN=LAN_VSTORE_FR_ORDER_NOTES}</h4>
						{SHIPPING_FIELD: ship_notes}
					</section>
				</div>
			</div>

			<div class="vstore-co-actions">
				<a class="btn btn-default btn-secondary vstore-btn-back-confirm" href="{ORDER_CHECKOUT_URL}">&laquo; {LAN=LAN_VSTORE_FR_BACK}</a>
				<button class="btn btn-primary btn-lg vstore-btn-buy-now" type="submit" name="mode" value="confirmed">{LAN=LAN_VSTORE_QUOTE_SEND}</button>
			</div>
		</div>
		';

$VSTORE_TEMPLATE['confirm_items_quote']['header'] = '
<div class="vstore-table-wrap">
<table class="table vstore-table">
<colgroup>
	<col style="width:80%" />
	<col />
</colgroup>
<thead>
<tr>
	<th>{LAN=LAN_VSTORE_FR_DESCRIPTION}</th>
	<th class="text-right text-end">{LAN=LAN_VSTORE_FR_QTY}</th>
</tr>
</thead>
<tbody>
';

$VSTORE_TEMPLATE['confirm_items_quote']['row'] = '
<tr>
	<td>{CONFIRM_DATA: name}</td>
	<td class="text-right text-end">{CONFIRM_DATA: quantity}</td>
</tr>';

$VSTORE_TEMPLATE['confirm_items_quote']['footer'] = '
</tbody>
</table>
</div>
';

$VSTORE_TEMPLATE['order_items_quote']['header'] = $VSTORE_TEMPLATE['confirm_items_quote']['header'];

$VSTORE_TEMPLATE['order_items_quote']['row'] = '
<tr>
        <td>{CART_DATA: name}</td>
        <td class="text-right text-end">{CART_DATA: quantity}</td>
</tr>';

$VSTORE_TEMPLATE['order_items_quote']['footer'] = '
</tbody>
</table>
</div>
';

$VSTORE_TEMPLATE['navcart']['item_quote'] = '
				<li class="vstore-navcart-item">
					<div class="vstore-navcart-pic">{CART_DATA: pic}</div>
					<div class="vstore-navcart-info">{CART_DATA: name}
						<span class="vstore-navcart-qty">{CART_DATA: quantity} &times;</span>
					</div>
				</li>
			';

$VSTORE_TEMPLATE['navcart']['footer_quote'] = '
			</ul>
			<input type="hidden" id="vstore-item-count" value="{CART_DATA: item_count}"/>
		</div>

		<div class="d-grid gap-2">
			<a class="btn btn-primary" href="{CART_DATA: cart_url}"><i class="fa fa-file-text-o" aria-hidden="true"></i> {LAN=LAN_VSTORE_QUOTE_REQUEST}</a>
			<a class="btn btn-secondary btn-sm" href="{CART_DATA: dashboard_url}"><i class="fa fa-tachometer" aria-hidden="true"></i> {LAN=LAN_VSTORE_FR_MY_DASHBOARD}</a>
			<a class="btn btn-link btn-sm vstore-navcart-clear" href="#" onclick="vstoreCartReset()"><i class="fa fa-trash-o" aria-hidden="true"></i> {LAN=LAN_VSTORE_FR_CLEAR_CART}</a>
		</div>
';
