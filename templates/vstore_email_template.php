<?php
	/**
	 * e107 website system
	 *
	 * Copyright (C) 2008-2017 e107 Inc (e107.org)
	 * Released under the terms and conditions of the
	 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
	 *
	 * E-mail bodies. The surrounding document (header, footer, mobile rules) is vstore_email_layout_template.php.
	 * Only table markup and inline styles are used, because mail clients ignore most external or class based CSS.
	 */

	e107::lan('vstore', 'front_shop', true);
	e107::lan('vstore', 'front_mail', true);

	$VSTORE_EMAIL_TEMPLATE = array();

	// The texts come from the language files so the default templates are readable in the admin editor.
	$_l = array(
		'hello'     => LAN_VSTORE_MAIL_HELLO,
		'thanks'    => LAN_VSTORE_MAIL_THANKS,
		'ref'       => LAN_VSTORE_MAIL_REF,
		'merchant'  => LAN_VSTORE_MAIL_MERCHANT,
		'customer'  => LAN_VSTORE_MAIL_CUSTOMER,
		'billing'   => LAN_VSTORE_FR_BILLING_ADDRESS,
		'shipping'  => LAN_VSTORE_FR_SHIPPING_ADDRESS,
		'regards'   => LAN_VSTORE_MAIL_REGARDS,
		'completed' => LAN_VSTORE_MAIL_COMPLETED,
		'cancelled' => LAN_VSTORE_MAIL_CANCELLED,
		'refund'    => LAN_VSTORE_MAIL_CANCELLED_REFUND,
		'refunded'  => LAN_VSTORE_MAIL_REFUNDED,
	);

	// Inline style presets.
	$_p     = "margin:0 0 16px;font-size:15px;line-height:24px;color:#374151;";
	$_label = "margin:0 0 8px;font-size:12px;line-height:16px;font-weight:700;letter-spacing:0.05em;text-transform:uppercase;color:#6b7280;";
	$_card  = "padding:16px;background-color:#f9fafb;border:1px solid #e5e7eb;border-radius:6px;font-size:14px;line-height:21px;color:#374151;";

	$_greeting = "<p style='{$_p}'>{$_l['hello']} {BILLING: firstname} {BILLING: lastname},</p>";
	$_closing  = "<p style='margin:24px 0 0;font-size:15px;line-height:24px;color:#374151;'>{$_l['regards']}<br />{SENDER_NAME}</p>";

	// Merchant/customer address cards shared by the confirmation and completed e-mails.
	$_addr = "<table role='presentation' width='100%' cellpadding='0' cellspacing='0' border='0' style='margin:24px 0;'>
<tr>
<td class='vs-col' width='50%' valign='top' style='{$_card}'>
<p style='{$_label}'>{$_l['merchant']}</p>
{ORDER_MERCHANT_INFO}
</td>
<td class='vs-spacer' width='16' style='width:16px;font-size:0;line-height:0;'>&nbsp;</td>
<td class='vs-col' width='50%' valign='top' style='{$_card}'>
<p style='{$_label}'>{$_l['customer']}</p>
<p style='margin:0 0 4px;font-size:13px;line-height:18px;font-weight:700;color:#111827;'>{$_l['billing']}</p>
<p style='margin:0 0 12px;'>{BILLING: firstname} {BILLING: lastname}<br />{BILLING: company}{BILLING: address}<br />{BILLING: city} {BILLING: state} {BILLING: zip}<br />{BILLING: country}</p>
<p style='margin:0 0 4px;font-size:13px;line-height:18px;font-weight:700;color:#111827;'>{$_l['shipping']}</p>
<p style='margin:0;'>{SHIPPING: firstname} {SHIPPING: lastname}<br />{SHIPPING: company}{SHIPPING: address}<br />{SHIPPING: city} {SHIPPING: state} {SHIPPING: zip}<br />{SHIPPING: country}</p>
</td>
</tr>
</table>";

	$VSTORE_EMAIL_TEMPLATE['default'] = "{$_greeting}
<p style='{$_p}'>{$_l['thanks']}</p>
<table role='presentation' width='100%' cellpadding='0' cellspacing='0' border='0' style='margin:0 0 8px;'>
<tr>
<td style='padding:12px 16px;background-color:#eff6ff;border-left:4px solid #2563eb;border-radius:4px;'>
<p style='margin:0;font-size:12px;line-height:16px;font-weight:700;letter-spacing:0.05em;text-transform:uppercase;color:#1e40af;'>{$_l['ref']}</p>
<p style='margin:4px 0 0;font-size:20px;line-height:28px;font-weight:700;color:#111827;'>#{REF}</p>
</td>
</tr>
</table>
{$_addr}
{ORDER_ITEMS: email}
{ORDER_PAYMENT_INSTRUCTIONS}
{$_closing}";

	$VSTORE_EMAIL_TEMPLATE['completed'] = "{$_greeting}
<p style='{$_p}'>{$_l['completed']}</p>
{$_addr}
{ORDER_ITEMS: email}
{$_closing}";

	$VSTORE_EMAIL_TEMPLATE['cancelled'] = "{$_greeting}
<p style='{$_p}'>{$_l['cancelled']}</p>
<p style='{$_p}'>{$_l['refund']}</p>
{$_closing}";

	$VSTORE_EMAIL_TEMPLATE['refunded'] = "{$_greeting}
<p style='{$_p}'>{$_l['refunded']}</p>
{$_closing}";

	// Order lines (used by {ORDER_ITEMS: email}); the coupon and tax rows come from {ORDER_COUPON} and {ORDER_TAX}.
	$_th = "padding:10px 8px;border-bottom:2px solid #e5e7eb;font-size:12px;line-height:16px;font-weight:700;letter-spacing:0.05em;text-transform:uppercase;color:#6b7280;";
	$_td = "padding:12px 8px;border-bottom:1px solid #e5e7eb;font-size:14px;line-height:21px;color:#374151;";
	$_tf = "padding:6px 8px;font-size:14px;line-height:21px;color:#374151;";

	$VSTORE_EMAIL_TEMPLATE['items']['header'] = "<table width='100%' cellpadding='0' cellspacing='0' border='0' style='margin:24px 0;border-collapse:collapse;'>
<thead>
<tr>
<th scope='col' align='left' style='{$_th}'>{LAN=LAN_VSTORE_FR_DESCRIPTION}</th>
<th scope='col' align='right' style='{$_th}'>{LAN=LAN_VSTORE_FR_UNIT_PRICE}</th>
<th scope='col' align='right' style='{$_th}'>{LAN=LAN_VSTORE_FR_QTY}</th>
<th scope='col' align='right' style='{$_th}'>{LAN=LAN_VSTORE_FR_AMOUNT}</th>
</tr>
</thead>
<tbody>
";

	$VSTORE_EMAIL_TEMPLATE['items']['row'] = "<tr>
<td align='left' valign='top' style='{$_td}'>{CART_DATA: name}</td>
<td align='right' valign='top' style='{$_td}white-space:nowrap;'>{CART_DATA: price}</td>
<td align='right' valign='top' style='{$_td}'>{CART_DATA: quantity}</td>
<td align='right' valign='top' style='{$_td}white-space:nowrap;'>{CART_DATA: item_total}</td>
</tr>
";

	$VSTORE_EMAIL_TEMPLATE['items']['footer'] = "<tr>
<td colspan='3' align='right' style='{$_tf}padding-top:12px;'>{LAN=LAN_VSTORE_FR_SUBTOTAL}</td>
<td align='right' style='{$_tf}padding-top:12px;white-space:nowrap;'>{CART_DATA: sub_total}</td>
</tr>
<tr>
<td colspan='3' align='right' style='{$_tf}'>{LAN=LAN_VSTORE_FR_SHIPPING}</td>
<td align='right' style='{$_tf}white-space:nowrap;'>{CART_DATA: shipping_total}</td>
</tr>
{ORDER_COUPON}
{ORDER_TAX}
<tr>
<td colspan='3' align='right' style='padding:12px 8px;border-top:2px solid #111827;font-size:16px;line-height:24px;font-weight:700;color:#111827;'>{LAN=LAN_VSTORE_FR_TOTAL}</td>
<td align='right' style='padding:12px 8px;border-top:2px solid #111827;font-size:16px;line-height:24px;font-weight:700;color:#111827;white-space:nowrap;'>{CART_DATA: grand_total}</td>
</tr>
</tbody>
</table>
";

	$VSTORE_EMAIL_TEMPLATE['items']['coupon'] = "<tr>
<td colspan='3' align='right' style='{$_tf}'>{LAN=LAN_VSTORE_FR_COUPON}: <b>{CART_DATA: coupon}</b></td>
<td align='right' style='{$_tf}white-space:nowrap;'>{CART_DATA: coupon_amount}</td>
</tr>
";

	$VSTORE_EMAIL_TEMPLATE['items']['tax'] = "<tr>
<td colspan='2' align='right' style='{$_tf}'>" . LAN_VSTORE_FR_TAX . "</td>
<td align='right' style='{$_tf}'>[x]</td>
<td align='right' style='{$_tf}white-space:nowrap;'>[y]</td>
</tr>
";

	// Optional lines are only printed when they have a value.
	$VSTORE_EMAIL_WRAPPER['default']['BILLING: company']           = '{---}<br />';
	$VSTORE_EMAIL_WRAPPER['default']['SHIPPING: company']          = '{---}<br />';
	$VSTORE_EMAIL_WRAPPER['default']['ORDER_PAYMENT_INSTRUCTIONS'] = "<table role='presentation' width='100%' cellpadding='0' cellspacing='0' border='0' style='margin:8px 0 0;'>
<tr>
<td style='padding:16px;background-color:#f9fafb;border:1px solid #e5e7eb;border-radius:6px;font-size:14px;line-height:21px;color:#374151;'>{---}</td>
</tr>
</table>";

	unset($_l, $_p, $_label, $_card, $_greeting, $_closing, $_addr, $_th, $_td, $_tf);
