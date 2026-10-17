<?php
/**
 * Vstore - shopping cart plugin for e107.
 * Based on e107inc/vstore (CaMerOn, Achim Ennenbach, e107 Inc). Modified by Kanonimpresor, 2025-2026.
 *
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.html
 */
	/**
	 * Vstore - e-mail layout (document, container, header and footer).
	 *
	 * Table based, inline styles and a single small <style> block (resets and the mobile breakpoint),
	 * so it renders the same in Gmail, Outlook, Apple Mail and mobile clients.
	 * The body of each e-mail type lives in vstore_email_template.php and replaces {BODY}.
	 * A theme can override this file in <theme>/templates/vstore/vstore_email_layout_template.php.
	 */

	if (!defined('e107_INIT')) { exit; }

	$_tp       = e107::getParser();
	$_siteName = htmlspecialchars((string) (defined('SITENAME') ? SITENAME : e107::pref('core', 'sitename')), ENT_QUOTES, 'UTF-8');
	$_siteUrl  = htmlspecialchars((string) (defined('SITEURL') ? SITEURL : e_HTTP), ENT_QUOTES, 'UTF-8');
	$_lang     = htmlspecialchars((string) (defined('CORE_LC') ? CORE_LC : 'en'), ENT_QUOTES, 'UTF-8');
	$_logo     = trim((string) e107::pref('core', 'sitebutton'));
	$_font     = '-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica,Arial,sans-serif';

	if ($_logo !== '')
	{
		$_logo = htmlspecialchars($_tp->replaceConstants($_logo, 'full'), ENT_QUOTES, 'UTF-8');
		$_brand = "<a href='{$_siteUrl}' style='text-decoration:none;'><img src='{$_logo}' alt='{$_siteName}' height='40' style='display:block;height:40px;max-height:40px;width:auto;border:0;' /></a>";
	}
	else
	{
		$_brand = "<a href='{$_siteUrl}' style='font-family:{$_font};font-size:20px;line-height:28px;font-weight:700;color:#111827;text-decoration:none;'>{$_siteName}</a>";
	}

	$VSTORE_EMAIL_LAYOUT_TEMPLATE = array();

	$VSTORE_EMAIL_LAYOUT_TEMPLATE['default']['subject'] = '{SUBJECT}';

	$VSTORE_EMAIL_LAYOUT_TEMPLATE['default']['header'] = "<!DOCTYPE html>
<html lang='{$_lang}' xmlns='http://www.w3.org/1999/xhtml'>
<head>
<meta charset='utf-8' />
<meta name='viewport' content='width=device-width, initial-scale=1' />
<meta http-equiv='X-UA-Compatible' content='IE=edge' />
<meta name='x-apple-disable-message-reformatting' />
<meta name='format-detection' content='telephone=no,address=no,email=no,date=no,url=no' />
<meta name='color-scheme' content='light' />
<meta name='supported-color-schemes' content='light' />
<title>{SUBJECT}</title>
<style type='text/css'>
body, table, td, a { -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; }
table, td { mso-table-lspace:0pt; mso-table-rspace:0pt; }
img { -ms-interpolation-mode:bicubic; }
body { margin:0 !important; padding:0 !important; width:100% !important; }
a { color:#2563eb; }
@media only screen and (max-width:620px) {
	.vs-container { width:100% !important; }
	.vs-pad { padding:24px 16px !important; }
	.vs-col { display:block !important; width:100% !important; box-sizing:border-box; }
	.vs-spacer { display:block !important; width:100% !important; height:12px !important; }
}
</style>
</head>
<body style='margin:0;padding:0;background-color:#f3f4f6;'>
<table role='presentation' width='100%' cellpadding='0' cellspacing='0' border='0' style='width:100%;background-color:#f3f4f6;'>
<tr>
<td align='center' style='padding:24px 12px;'>
<table role='presentation' class='vs-container' width='600' cellpadding='0' cellspacing='0' border='0' style='width:600px;max-width:600px;'>
<tr>
<td align='left' style='padding:0 4px 16px;'>{$_brand}</td>
</tr>
<tr>
<td class='vs-pad' align='left' style='padding:32px;background-color:#ffffff;border:1px solid #e5e7eb;border-radius:8px;font-family:{$_font};font-size:15px;line-height:24px;color:#374151;'>
";

	$VSTORE_EMAIL_LAYOUT_TEMPLATE['default']['body'] = '{BODY}';

	$VSTORE_EMAIL_LAYOUT_TEMPLATE['default']['footer'] = "
</td>
</tr>
<tr>
<td align='center' style='padding:20px 8px 0;font-family:{$_font};font-size:12px;line-height:18px;color:#6b7280;'>
<a href='{$_siteUrl}' style='color:#6b7280;text-decoration:underline;'>{$_siteName}</a>
</td>
</tr>
</table>
</td>
</tr>
</table>
</body>
</html>";

	unset($_tp, $_siteName, $_siteUrl, $_lang, $_logo, $_font, $_brand);
