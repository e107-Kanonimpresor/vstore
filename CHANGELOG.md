# Changelog

All notable changes to the Vstore e-commerce plugin will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.0.0] - 2026-10-17

### Added
- **About tab** (`main/about`) with metadata read from `plugin.xml`, credits and links.
- **User Guide tab** (`main/guide`) with eight sections (quick start, catalog, coupons, tax, shipping, payments, orders, troubleshooting).
- **Sidebar help widget** on every admin tab with version badge, contextual tip, documentation, support and donate links.
- Lazy-loaded language files `*_admin_about.php` and `*_admin_help.php` in English, Spanish and Portuguese.
- `inc/vstore_admin_common.php` trait shared by all admin controllers.
- `DEV_NOTES.md` and `docs/architecture/USER_GUIDE_PATTERN.md` (adapted to Vstore).
- **Quantity field on the product page** (`{ITEM_QTY}` shortcode, added to the default product template). `vstore.js` sends it as `qty` and `vstore::addToCart()` accepts a quantity; it is limited to the stock and a quantity already in the cart is increased, never above the stock. Themes that override `vstore_template.php` must add `{ITEM_QTY}` before `{ITEM_ADDTOCART}`.
- **Mollie payment methods MB WAY and Multibanco** (Portugal), with their icons (`images/mbway.svg`, `images/multibanco.svg`, simplified marks that can be replaced by the official ones). Select them in the Mollie tab of the gateways page; they are offered only when enabled in the Mollie website profile.
- **Customer phone and email on the invoice and the order detail**, and **phone sent to Mollie** as `billingAddress.phone` (E.164, `src/Support/PhoneNumber.php`, `src/Payment/MollieGateway.php`, `src/Payment/MolliePurchaseRequest.php`). **MB WAY requires a Portuguese mobile number** at checkout (`LAN_VSTORE_MSG_MBWAY_PHONE`, EN/ES/PT; the phone field becomes required when MB WAY is selected).
- **Mollie webhook** (`vstore/checkout/webhook/`, `Vstore::handleMollieWebhook()`): when a payment that was pending at return (MB WAY not yet approved, Multibanco reference) changes, the waiting order is updated from the real Mollie status (paid: Processing, payment complete, customer email, userclass; canceled/expired/failed: Cancelled). The notification URL is sent only when the shop is reachable from the internet; a paid payment without order is written to the admin log.
- **"Check payment in Mollie" button** on the admin order page (Mollie orders that are New with an incomplete payment): reads the real payment status and applies the same update as the webhook. Useful on localhost, where Mollie cannot send notifications, and as a fallback in production (`Vstore::syncMolliePayment()`).

### Changed
- **Admin i18n (Phase 2)**: all admin controllers (`inc/admin_config_*.php`) read titles, help texts, tab names, placeholders and messages from per-area language files (`<Lang>_admin_<area>.php`, array format with automatic English fallback). Areas: catalog (categories and variations), customer, coupons, email, invoice, gateways, pref, products, orders, statistics and a shared `admin_common` file.
- Order statuses, email types, currencies and weight units are translated through `languages/<Lang>/<Lang>_global.php`. `vstore::getStatus()`, `getEmailTypes()`, `getCurrencies()` and `weightUnits()` return translated labels.
- Invoice defaults (title, subject, finishing phrase, footer blocks) are language specific.
- Fixed typos in admin texts ("Payed" is now "Paid", "onvoices", "dashoard", "es defined").
- `plugin.xml`: version 2.0.0, Kanonimpresor author and copyright, default values for `amount_format`, `show_outofstock`, `tax_calculate`, `tax_check_vat` and `menu_item_count`.
- README (EN/ES/PT): unified badges, author block and repository links.
- **Storefront i18n (Phase 3)**: shop, cart and menu templates, customer dashboard, email and invoice templates, shortcodes, hooks (`e_cron`, `e_search`, `e_sitelink`, `e_dashboard`, `e_header`), menus, PDF and order classes and the system messages of `vstore.class.php` read from `<Lang>_front_shop`, `_front_dashboard`, `_front_mail`, `_front_msg` and `_hooks` (array format, English fallback). Templates use `{LAN=...}`.
- Default email templates are rewritten around language constants and remain editable in the admin.
- **Business logic layer (Phase 4, first part)**: new `src/` (namespace `Vstore\`) with `TaxService`, `VatService`, `CouponService`, `ShippingService`, `OrderCouponUsageRepository`, `Catalog` (statuses, currencies, gateways, Mollie methods, field lists) and `Result`. `vstore.class.php` keeps every public method as a delegator, so themes and plugins are not affected. Services no longer print messages.
- `tests/run.php`: 242 regression checks (coupon, tax/VAT, shipping, item variations, inventory, userclass, invoice numbering, payment gateways, invoice, message translation, cart, checkout totals and validation, order references and customer records; no database or network needed).
- Business layer, second part: `InventoryService`, `ItemVarTypeService`, `UserclassService`, `InvoiceNumberService`, `ItemVars` and the `ItemRepository`, `ItemVarRepository` and `OrderRepository` classes. Stock reduction, stock lookup, item variations, customer userclasses and invoice numbers now live in `src/`; `vstore.class.php` keeps the same public methods as delegators.
- Business layer, third part: `PaymentService` (gateway creation from the preferences), `InvoiceService`, `ResultTranslator`. `vstore_order` and `vstore_dashboard` no longer extend `vstore`. New static helpers `vstore::createGateway()` and `vstore::pathToInvoicePdf()`.
- Business layer, fourth part: `CartService` (add, change quantities, remove), `CheckoutService` (cart totals with coupon, tax per rate and shipping, and validation of the billing and shipping forms), `OrderService` (order reference, additional fields, customer record) and the `CartRepository`, `CouponRepository` and `CustomerRepository` classes. `prepareCheckoutData()`, `addToCart()`, `updateCart()`, `validateCustomerData()`, `getOrderRef()` and `saveCustomer()` in `vstore.class.php` now delegate to them.
- **E-mails redesigned**: new layout `templates/vstore_email_layout_template.php` (table based, inline styles, single small `<style>` block for resets and the mobile breakpoint, `lang`, viewport and `<title>`, logo or site name, footer) replaces the e107 default wrapper; the bodies in `vstore_email_template.php` use cards for merchant/customer, a highlighted order reference, a clean order lines table (`{ORDER_ITEMS: email}`, template key `items`) and a callout for the bank transfer instructions. Valid HTML (no `<table>` inside `<p>`, no `<tdclass>` typo, no Bootstrap classes). A theme can override both files. **E-mail templates already saved in Vstore > E-mail templates keep the old markup: use "Reset template" (or empty the field) to adopt the new design.**
- **PDF invoices no longer need the e107 PDF plugin**: Vstore now installs TCPDF 6.11 (`tecnickcom/tcpdf`, supports PHP 7.4 to 8.4) with Composer and builds the document in `Vstore\Pdf\InvoicePdf` (`src/Pdf/InvoicePdf.php`). The old `inc/vstore_pdf.class.php` and its global constants, `ob_start()` hack and dependency on the legacy plugin (TCPDF 6.3 with a modified core) are gone. The page label is translated (`LAN_VSTORE_PDF_PAGE`), the text stops above the footer block, and the notice shown when the library is missing now says to run `composer install`. Run `composer install` when deploying; the e107 PDF plugin can be uninstalled.
- Invoice template: address columns, item table widths and borders were tuned for TCPDF (cell padding, a separate code column). Templates saved in the admin keep their own markup.
- **Euro-only Mollie methods (MB WAY, Multibanco, iDEAL, Bancontact, bank transfer, direct debit, gift card...) are no longer offered at checkout when the shop currency is not EUR**; Mollie answered "The currency is not supported by this payment method". The Gateways admin tab explains why they are hidden (`Catalog::supportsCurrency()`).

### Fixed
- Checkout offered Mollie payment methods that are not enabled in the Mollie website profile, which ended in "The payment method is not enabled in your website profile". The checkout now asks Mollie for the enabled methods (cached for 10 minutes) and hides the others; if Mollie cannot be reached every method stays available.
- Checkout with a payment gateway: exceptions thrown by Omnipay (for example Stripe asking for a card source) were not caught because the `Exception` alias of `vstore.class.php` pointed to the VAT library, which ended in a fatal error. They are now caught; customers get a generic translated message and the shop administrator sees the technical one (also written to the e107 log).
- A gateway with empty credentials (Mollie key of the active mode, PayPal user/password/signature, Stripe key, ...) is no longer called; a translated "not fully configured" message lists the missing settings to administrators. A gateway that could not be created no longer ends in a fatal error.
- Security: the cart id (a cookie) was concatenated into SQL, the cart reset deleted `cart_id=<cookie>` (another visitor's line could be removed), the cart form could change or delete lines of any cart by id, the coupon code and the page offsets (`frm`, `page`) were not escaped or cast. The cart id is now validated, cart queries are scoped to the visitor's cart and escaped, and the coupon is looked up through `CouponRepository`.
- Checkout: shipping tax no longer divides by zero when every item in the cart is free.
- Additional checkout fields use the caption of the current language when a required one is empty.
- Refunds: the refund date was never stored when an order was refunded from the admin buttons (only a code path that called a method that does not exist set it). `vstore_order::setOrderStatus('R')` now stores `order_refund_date`, and the admin form uses `vstore_order::refundOrder()`.
- Customer dashboard: the invoice could not be opened. The order list, the order detail and the downloads list now link to the invoice (PDF when PDF invoices are enabled) for orders in a status that allows an invoice; the detail page has an invoice button.
- Cart: the quantities were not saved by "Re-Calculate" while a coupon was active, because the coupon field (pre-filled with the active code) is in the same form and stopped the update.
- Downloads: a product whose download file is missing on the server sent the customer silently to the home page. Now a translated message is shown and the admin gets a warning when saving a product whose download file cannot be served (`vstore::resolveDownloadPath()`).
- Payment gateway loading did not catch Omnipay exceptions (the `Exception` alias pointed to the VAT library), so a missing gateway class ended in a fatal error.
- Refunds from the admin could not read the Mollie API keys because the gateway settings were only prepared by the storefront class.
- A gateway configuration error no longer dumps every gateway setting (including keys) into the error log.
- The gateway configuration warning is translated (`LAN_VSTORE_MSG_GW_PARAM`).
- Intra-EU B2C tax rate: `getTaxRate()` built `Ibericode\Vat\Rates` without its required argument, which raised an `ArgumentCountError` at checkout for customers in another EU country. Rates are now cached on disk (12 h) instead of being requested remotely on every call.
- Email selection on order status change no longer depends on the translated status label (`vstore::getStatusSlug()`).
- `e_sitelink.php` used a hardcoded `e107_` table prefix.
- `vstore_menu.php` used an undefined `$vstore_prefs` variable.
- Dashboard chart showed the Paid and Open labels swapped.
- Duplicated merchants/address block in the default email template.
- **MB WAY / Multibanco return showed "Generic error"**: a payment still pending at return created no order and showed a generic error next to the success box, and a paid payment showed the generic text as its success message. Pending payments now register an order waiting for its payment (status New, payment incomplete) with an explanatory message; paid, canceled, expired and failed payments have their own messages.
- **Security review before production**: customer data is escaped everywhere it is printed (admin orders and customers, address, order and confirmation shortcodes); gateway credentials are masked in the admin log after saving the gateways page (including older entries); order actions from the admin area need the plugin permission; SQL values are escaped or cast; `composer update` fixed 15 advisories (Guzzle, psr7, symfony/http-foundation). Run `composer install` on the production server.
- **Mollie orders are created before the customer pays** (as New with an incomplete payment), so a payment made without returning to the shop, or still pending, always has its order; the webhook, the return page or the "Check payment" button then update it. Stock is given back (`InventoryService::restore()`) when the payment fails, is cancelled or expires. The paid customer now gets the "processing" status email instead of the "order received" one.
- **Additional checkout fields were printed as raw HTML text** after the output-escaping pass; they render as form fields again. **Invoice preview** in the admin area no longer fails with `initPrefs()` on `vstore_order`, and saving an order in the admin area now loads the order before regenerating the invoice and only emails the customer when the status changed.
- **Additional checkout fields showed the required mark (*) even when they were optional**: only fields marked as required get it now.
- **Consumer NIF with the VIES check enabled**: a customer from the shop's own country is no longer checked in VIES (there is no reverse charge for domestic sales); only the national number is validated (Portuguese NIF check digit). Customers from other EU countries are still validated in VIES. The error message tells private customers to leave the field empty.
- **Cart and checkout totals with destination VAT**: product prices are net and the VAT of the customer's country is added on top; the "business" net view is now used only when the reverse charge really applies (VAT ID of another EU country accepted by VIES), the customer's VAT ID is passed to the tax calculation (it never was), net amounts are no longer derived by taking tax out of net prices, VIES answers are cached for an hour and the shop-country comparison ignores letter case.
- **New preference "Charge the VAT of the shop country to EU consumers"** (`tax_origin_vat`, off by default): for sellers below the EU 10,000 EUR distance-selling threshold. Off keeps the destination-country VAT (OSS rule) for consumers of other EU countries; the reverse charge for businesses with a VIES-valid VAT ID is not affected.
- **Additional checkout fields were empty when reopening the Preferences page** (and the next save erased them): the field is now declared as an array. Texts typed in one admin language no longer erase the other languages, and the frontend falls back to English or any available language when the current one has no text. The preferences page also applies its own pre-save cleanup now (it was ignored), which removes the stray `%ROW%` shipping row.
- **Order lines showed 0.00 in e-mails and on the order summary** for customers with a valid EU VAT ID (reverse charge): the unit price and line total were read from `net_price`/`item_total_net`, which are not stored in saved orders. Prices are net, so the same value is shown to every customer.
- **PDF invoice without billing and shipping address**: the default invoice template left the "Due by" cell without `</td>`, TCPDF dropped the two address columns and printed warnings. Templates saved from the admin may have the same defect: use "Reset template" in Invoice Settings.
- **The PDF invoice was not attached to the order e-mails**: the file was passed with the key `attach` but the e107 mailer reads `attachment`.

### Removed
- Empty `renderHelp()` stubs in the coupons and variations controllers.
- Root-level release notes and guides moved to `docs/`.
- **Gateways Stripe, Braintree, CoinPayments and StarkPay** and their Composer packages (`omnipay/stripe`, `omnipay/braintree` dev branch, `financialplugins/omnipay-coinpayments`, `starkpay/omnipay`): the checkout redirects to the gateway and has no card form (Stripe.js / client nonce), so they could not take payments. Cards are covered by Mollie. Retired gateways still active in saved preferences are ignored at checkout and the Gateways admin tab shows a notice. `php-http/message-factory` (abandoned) stays as a dependency of `omnipay/common` 3.4 (last release for PHP 7.4). Run `composer install --no-dev` when deploying.

## [1.1.0] - 2025-11-07

### Added
- **Documentation**: Enhanced installation instructions in README (EN/ES/PT)
  - Added prominent warning about required `composer install` step
  - Clarified that `vendor/` directory is not included in repository
  - Added troubleshooting section for common installation errors

### Fixed

#### Critical Bug Fixes
- **Namespace Update**: Updated `ibericode/vat` from v1.x to v2.x compatibility
  - Changed namespace from `DvK\Vat\*` to `Ibericode\Vat\*` (14 references updated)
  - Updated API methods:
    - `Countries::inEurope()` → `Countries::isCountryCodeInEU()`
    - `Rates::country()` → `Rates::getRateForCountry()`
    - `Validator::validate()` → `Validator::validateVatNumber()`
  - Refactored `getTaxClass()` method to work without deprecated `all()` method

#### Coupon System
- **Date Validation**: Fixed coupon date validation with automatic format conversion
  - Added automatic conversion from datetime strings to Unix timestamps
  - Improved error messages to show exact availability dates
  - Fixed issue where coupons with valid dates were being rejected
  
- **Cart Preservation**: Fixed critical bug where cart was emptied when applying coupons
  - Added validation to skip `updateCart()` when qty ≤ 0
  - Protected against empty `cartQty` submissions during coupon application
  - Prevented cart items from being set to zero quantity

#### Checkout Process
- **Shipping Data**: Fixed "No shipping data set!" error during checkout
  - Automatically copy billing address to shipping when `shipping_type = 0`
  - Ensures shipping data is always present before order creation
  - Improved data flow between customer and shipping forms

#### PHP 8.x Compatibility
- **Undefined Array Keys**: Fixed multiple warnings across the codebase
  - Added `!empty()` checks for `is_business` and `is_local` variables (9 functions in shortcodes)
  - Fixed `add_field0-3` undefined array key warnings in customer forms
  - Fixed `filter_options` warning in orders admin (line 81)
  - Fixed `notes` undefined key in shipping data display (line 585)

- **PDF Generation**: Fixed foreach() errors with string values
  - Added type checking before iterating over `$text` variable
  - Handles both array and string input types gracefully

- **Output Buffer**: Fixed "ob_*() level 2 at end instead of (1)" error
  - Properly closes output buffer after PDF generation when saving to disk
  - Prevents buffer leak during invoice creation on order completion
  - Buffer opened by vstore_pdf.class.php is now correctly cleaned up

#### Admin Panel
- **Orders Management**: Corrected array key access errors
  - Added validation for `$_GET['filter_options']` before comparison
  - Added null checking for order notes display

### Changed

#### Tax Calculation
- **Debug Logging**: Cleaned up tax calculation debug output
  - Removed category parameter from `e107::getDebug()->log()` calls (9 instances)
  - Prevents "Undefined category VSTORE_TAX" warnings in e107 debug system

#### Code Quality
- **Error Handling**: Improved defensive programming practices
  - Added extensive input validation throughout checkout flow
  - Better null/empty checks before array access
  - Type checking before operations on variables

### Technical Details

#### Files Modified
- `vstore.class.php`: 
  - Lines 3917-3945: Coupon date validation and conversion
  - Lines 2508-2520: Cart update protection (qty ≤ 0 skip)
  - Lines 1210-1228: Shipping data auto-copy for billing address reuse
  - Lines 4160-4230: Tax class calculation refactor
  
- `shortcodes/batch/vstore_shortcodes.php`:
  - Lines 427-435, 477-485, 512-520, 547-555, 582-590, 632-640, 672-680, 707-715, 831-839: Added `!empty()` checks
  - Line 1164: Fixed `add_field` array access

- `inc/vstore_pdf.class.php`:
  - Lines 189-200: Type validation before foreach

- `inc/admin_config_orders.php`:
  - Line 81: Filter options validation
  - Line 583: Notes array key check

#### Dependencies
- **ibericode/vat**: Now compatible with v2.0.9+
- **PHP**: Minimum version 7.4, tested with PHP 8.x
- **e107**: Compatible with v2.3.0+

### Development Notes

#### Debug Mode
- Added conditional debug constant in `e_header.php`
- To enable debug: Uncomment `define('e_DEBUG_VSTORE', true);`
- Debug logs available when constant is defined

#### Testing Performed
- ✅ Coupon application with various date formats
- ✅ Full checkout flow (billing + shipping)
- ✅ Cart quantity updates
- ✅ Order creation with coupons applied
- ✅ Inventory deduction
- ✅ Tax calculation (EU intra-community scenarios)
- ✅ Admin panel order viewing/editing

### Known Issues
- PDF generation shows warnings with TCPDF on PHP 8.x (non-critical)
- Email sending requires SMTP configuration on localhost
- Some TCPDF warnings remain (upstream library issue)

### Migration Guide

#### For Existing Installations
1. **Backup your database** before updating
2. Update via e107 plugin manager or replace files manually
3. No database schema changes required
4. Clear e107 cache: Admin → System → Clear Cache
5. Test coupon functionality with existing coupons
6. Verify checkout flow completes successfully

#### For Developers
- If extending vstore, update any custom code using `DvK\Vat` namespace
- Review tax calculation if you've modified `getTaxClass()` method
- Check any custom shortcodes that access `is_business`/`is_local` variables

---

## [Previous Versions]

For changes in versions prior to this update, please refer to the Git commit history or contact the maintainers.

---

**Contributors**: 
- Bug fixes and compatibility updates: November 2025

**Special Thanks**:
- e107 community for testing and feedback
- ibericode/vat library maintainers for VAT validation functionality
