# Vstore v2.0.0 - Translations, Mollie MB WAY, EU VAT, built-in PDF invoices

Release date: 2026-10-17 · Requires e107 2.3.2+, PHP 7.4 – 8.4 · Tested with PHP 7.4, 8.2 and 8.4 (242 automated checks)

## Install / upgrade

1. Replace the `e107_plugins/vstore` folder (or extract the release package, which already contains `vendor/`).
2. If you installed from the source repository, run in the plugin folder:
   ```bash
   composer install --no-dev --optimize-autoloader
   ```
3. Open **Admin → Plugins → Vstore → Update** if e107 offers it.
4. The e107 **PDF plugin is no longer needed** (TCPDF now ships with Vstore). You can uninstall it.
5. Review these after upgrading:
   - **E-mail templates** saved in *Vstore → E-mail templates* keep the old markup: press *Reset template* on each tab to get the new design.
   - **Invoice template** saved in *Invoice Settings* may need *Reset template* too (an unclosed `</td>` made TCPDF drop the address columns).
   - **Stripe, Braintree, CoinPayments and StarkPay** were removed. Use Mollie (cards and local methods), PayPal or bank transfer. Their saved settings are ignored.
   - **Mollie MB WAY, Multibanco, iDEAL, Bancontact, bank transfer, direct debit and gift cards only accept EUR.** They are hidden at checkout when the shop currency is not EUR.

## Highlights

### Multilingual (English, Spanish, Portuguese)
- Every admin screen, storefront template, e-mail, invoice and system message now reads from language files (with English fallback). Order statuses, currencies and units are translated.
- Texts typed in one admin language no longer erase the other languages.

### Payments
- **Mollie MB WAY and Multibanco**, customer phone sent to Mollie (E.164), Portuguese mobile number required for MB WAY.
- **Mollie webhook** and a *Check payment in Mollie* button: orders are created before the customer pays, so a payment that is still pending or made without returning to the shop always has its order; stock is given back when the payment fails or expires.
- Only the Mollie methods enabled in your Mollie profile are offered; euro-only methods are hidden for other currencies.
- Gateways without credentials are never called, and gateway exceptions no longer end in a fatal error.

### EU VAT
- Prices are net and the VAT of the customer's country is added on top; reverse charge only for businesses with a VIES-valid VAT ID.
- New option to charge the shop-country VAT to EU consumers (sellers below the 10,000 EUR threshold).
- Portuguese NIF check digit, VIES answers cached, domestic customers are not checked against VIES.

### E-mails and invoices
- **New e-mail design**: table layout with inline styles, mobile breakpoint, cards for merchant and customer, clean order table, callout for bank transfer instructions. Themes can override `vstore_email_layout_template.php` and `vstore_email_template.php`.
- **PDF invoices with TCPDF 6.11 installed by Composer** (`Vstore\Pdf\InvoicePdf`), attached to the order e-mail, downloadable from the customer dashboard and the admin.
- Fixed: invoice attached to e-mails, address columns missing in the PDF, order lines at 0.00 for reverse-charge customers.

### Admin
- New **About** and **User Guide** tabs, contextual help sidebar, translated tabs and help texts.
- Gateways tab explains hidden methods (currency) and ignored retired gateways.
- New [user manual](https://github.com/e107-Kanonimpresor/vstore/blob/main/docs/user-manual.html) (Spanish) with all sections.

### Under the hood
- New business layer in `src/` (namespace `Vstore\`): tax, VAT, coupons, shipping, inventory, cart, checkout, orders, payments, invoices. `vstore.class.php` keeps the same public methods, so themes and plugins keep working.
- Security review: output escaping, SQL escaping/casting, cart scoped to the visitor, credentials masked in logs, admin actions need the plugin permission, 15 Composer advisories fixed (`composer audit`: none).
- Dependencies: `league/omnipay`, `omnipay/paypal`, `omnipay/mollie`, `ibericode/vat`, `tecnickcom/tcpdf`. The abandoned `php-http/message-factory` is kept because `omnipay/common` 3.4 (last release for PHP 7.4) requires it.

Full list of changes: [CHANGELOG.md](https://github.com/e107-Kanonimpresor/vstore/blob/main/CHANGELOG.md).

## Known limitations
- The user manual is in Spanish only.
- Manual cancellation of an order from the admin does not give the stock back (payments that fail or expire do).
- The saved credentials of removed gateways stay in the database preferences.
