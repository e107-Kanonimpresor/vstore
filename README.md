# Vstore Plugin for e107

#### (Choose your language / Elija su idioma / Escolha o seu idioma)

[![Language-English](https://img.shields.io/badge/Language-English-blue)](README.md) 
[![Language-Português](https://img.shields.io/badge/Language-Português-green)](README.pt-PT.md) 
[![Language-Español](https://img.shields.io/badge/Language-Español-red)](README.es-ES.md) 

---

[![Version](https://img.shields.io/badge/version-2.1.0-blue)](https://github.com/e107-Kanonimpresor/vstore/releases/tag/v2.1.0)
![e107](https://img.shields.io/badge/e107-2.3.2%2B-green)
[![PHP](https://img.shields.io/badge/PHP-7.4%20to%208.4-purple.svg)](https://php.net)
![Bootstrap](https://img.shields.io/badge/Bootstrap-3%20%7C%205-purple)
![License](https://img.shields.io/badge/license-GPL--3.0-orange)

**Author:** [Kanonimpresor](https://marketingdeperformance.online) — Marketing de Performance  
**Licence:** GPL-3.0  
**Based on:** [e107inc/vstore](https://github.com/e107inc/vstore) (CaMerOn, Achim Ennenbach, e107 Inc). Modified by Kanonimpresor, 2025-2026.

---

## 📋 Description

**Vstore** is an e-commerce plugin for [e107 v2.x](https://e107.org) Bootstrap CMS. It lets you run an online store directly from your e107 website: physical products, digital downloads or services, with several payment methods, EU VAT handling, coupons, stock control, PDF invoices and customer e-mails, in English, Spanish and Portuguese.

---

## ✨ Key Features

### 🛒 Shopping Cart & Checkout
- **Shopping cart** with AJAX add-to-cart and a quantity field on the product page
- **Guest checkout**: customers can buy as a guest, log in or sign up
- **Checkout** with billing and shipping addresses, optional additional fields and phone number
- **Customer dashboard**: order history, order details, downloads and invoices
- **Digital downloads**: files are available once the payment is received
- **Product variations**: up to two variation groups per product (size, colour...) with price changes and their own stock

### 💳 Payment Processing
- **Payment gateways** (through [Omnipay](https://omnipay.thephpleague.com/)):
  - **PayPal** (standard and REST)
  - **Mollie**: credit cards, PayPal, **MB WAY**, **Multibanco**, iDEAL, Bancontact, bank transfer, direct debit, gift cards and more. Only the methods enabled in your Mollie profile are offered
  - **Bank transfer** (manual): the order waits until you mark it as paid
- **Hosted payment pages**: card data never touches your server
- **Mollie webhook**: orders are created before the customer pays, so a payment that is still pending (MB WAY, Multibanco) or made without returning to the shop never gets lost
- **Refunds** from the order page for gateways that support them (Mollie, PayPal)
- **Order statuses**: New, Processing, On hold, Completed, Cancelled, Refunded

> Euro-only Mollie methods (MB WAY, Multibanco, iDEAL, Bancontact, bank transfer, direct debit, gift cards) are hidden at checkout when the shop currency is not EUR.

### 💰 Pricing & Discounts
- **Coupons**:
  - Percentage or fixed-amount discounts
  - Start and end dates
  - Include/exclude categories and products
  - Usage limits (total, per user and per item)
- **Tax calculation**:
  - Net prices, with the VAT of the customer's country added on top
  - EU VAT with **VIES** validation of VAT IDs (and Portuguese NIF check digit)
  - **Reverse charge** (0%) for businesses of another EU country with a valid VAT ID
  - Optional switch to charge the shop-country VAT to EU consumers (sellers below the EU 10,000 EUR threshold)
  - Tax classes: standard, reduced, super-reduced, parking and more

### 📦 Product Management
- **Unlimited products** and nested **categories**, restricted to user classes if needed
- **Stock control** per product and per variation (`-1` for unlimited)
- **Image galleries** with zoom, product videos and downloadable files
- **Related products**
- **Bootstrap 5 storefront**: category tiles, product cards, category bar under the breadcrumb, redesigned menus and cart, responsive and themable with CSS variables (2.1.0)
- **Quote mode** (2.1.0): a product with an empty price shows a "Quote" button; purchases and quote products share the cart (each kind is checked out on its own, the quote lists only products and quantities) and the request is saved as a quote (Quote request → Migrated to app → Approved → Invoiced) and e-mailed to the customer and the shop
- **SEO-friendly URLs**
- **Search integration** with the e107 search

### 🚚 Shipping
- **Shipping methods**: sum of all items, sum of unique items, or tiered by order price or weight
- **Per-product shipping costs**
- **Separate billing and shipping addresses**; customer notes and internal admin notes

### 📊 Statistics
- **Dashboard** with revenue, orders of the month and a custom-period chart, also shown on the e107 control panel

### 🎨 Customization
- **Templates** for the shop, cart, customer area, e-mails and invoice; a theme can override them in `<theme>/templates/vstore/`
- **Shortcodes** for custom layouts
- **Custom CSS** from the admin area
- **Responsive** Bootstrap markup (Bootstrap 3 and 5 themes)
- **Fully translatable**: admin, storefront, e-mails, invoices and messages come from language files (English, Spanish, Portuguese; other languages fall back to English)

### 🔧 Admin Features
- **Admin area** for sales, products, categories, variations, coupons, customers, gateways, invoice and e-mail settings
- **User Guide** and **About** tabs and a contextual help sidebar
- **E-mail templates** per order status (confirmation, completed, cancelled, refunded), mobile-friendly, with a reset button
- **PDF invoices** created with TCPDF (installed by Composer): number prefix, next number, footer blocks, preview; attached to the e-mail and available to the customer
- **Permissions** for admin actions

---

## 🆕 What's new in v2.1.0

- **Quote mode**: products without a price are requested as a quote, in the same cart as the products you sell.
- **Redesigned storefront** for Bootstrap 5: category tiles, product cards, category bar, menus, cart, checkout with an order summary, and a customer dashboard with figures and a chart.
- **Add-to-cart feedback**: the product flies to the cart icon, the counter pulses and a message offers the cart.
- **Admin**: new *quotes* box in the statistics and a *Quotes* filter in the orders.

## What was new in v2.0.0

- **Fully translatable** admin area and storefront (English, Spanish, Portuguese).
- **Mollie MB WAY and Multibanco**, phone number sent to Mollie, Mollie **webhook** and a *Check payment in Mollie* button.
- **EU VAT rewritten**: net prices, VIES, reverse charge, optional origin-country VAT.
- **New e-mail design** and **built-in PDF invoices** (TCPDF 6.11 by Composer; the e107 *pdf* plugin is no longer needed).
- **Business logic layer** in `src/` (namespace `Vstore\`) with 242 automated checks (`php tests/run.php`). `vstore.class.php` keeps its public methods, so themes and plugins keep working.
- **Security review**: output escaping, SQL escaping, cart scoped to the visitor, credentials masked in logs, `composer audit` clean.
- **Removed** Stripe, Braintree, CoinPayments and StarkPay (they had no working checkout). Cards are covered by Mollie.
- New **user manual** ([docs/user-manual.html](docs/user-manual.html)) and **release notes** ([docs/RELEASE_NOTES_v2.0.0.md](docs/RELEASE_NOTES_v2.0.0.md)).

### Upgrading from 1.x
1. Replace the plugin folder (or `git pull`) and run `composer install --no-dev --optimize-autoloader`.
2. In *Vstore → E-mail templates* and *Invoice Settings*, press **Reset template** if you had saved your own templates, to get the new design and the fixed invoice layout.
3. Uninstall the e107 *pdf* plugin if you no longer use it elsewhere.
4. For MB WAY, Multibanco and the other euro-only Mollie methods, set the shop currency to **EUR**.

---

## 🚀 Installation

### Requirements
- **e107 CMS**: v2.3.2 or higher ([Download](https://github.com/e107inc/e107))
- **PHP**: 7.4 to 8.4
- **PHP extensions**: SOAP (VIES VAT validation), cURL (payment gateways), JSON, mbstring, GD (images and the invoice logo)
- **Database**: MySQL 5.7+ / MariaDB 10.2+
- **Composer** (only when installing from the source repository)

### Installation steps

1. **Get the plugin**
   - **Release package (recommended)**: download `vstore-2.1.0.zip` from the [Releases page](https://github.com/e107-Kanonimpresor/vstore/releases) and extract it into `e107_plugins/` (it already contains `vendor/`).
   - **From source**:
     ```bash
     cd e107_plugins
     git clone https://github.com/e107-Kanonimpresor/vstore.git
     cd vstore
     composer install --no-dev --optimize-autoloader
     ```

   > **⚠️ IMPORTANT**: the source repository does not contain the `vendor/` folder. Without `composer install` you will see `Failed opening required 'vendor/autoload.php'`.

2. **Install in e107**: *Admin → Plugins → Manage Plugins → Vstore → Install*.

3. **Configure**: *Admin → Vstore → Preferences*: currency, price format, weight unit, store address, shipping, tax, e-mails and checkout. The **User Guide** tab walks you through it.

4. **Payment gateways**: *Admin → Vstore → Payment Gateways*. Enter the credentials of at least one gateway and activate it. Test it in sandbox/test mode first.

5. **Create categories and products**: *Admin → Vstore → Categories / Products*.

6. **Test the checkout** with a test order and follow it in *Sales*.

---

## 📖 Documentation

- [User manual](docs/user-manual.html) (Spanish)
- [Release notes v2.0.0](docs/RELEASE_NOTES_v2.0.0.md) and [CHANGELOG](CHANGELOG.md)
- [Invoices](wiki/invoice.md)
- [Testing guide](docs/TESTING_GUIDE.md)
- [Development notes](DEV_NOTES.md) and [update plan](docs/PLAN_ACTUALIZACION_V2.md)

---

## 🔧 Configuration

### Tax settings (EU VAT example)

```
Admin → Vstore → Preferences → Tax
Business country:     PT (Portugal)
Tax calculation:      Enabled
Check VAT ID (VIES):  Enabled
Tax classes:          decimal values, e.g. 0.23 for 23%

Prices are net; the VAT of the destination country is added:
- PT customer:                               23% (your local rate)
- ES consumer:                               Spanish rate
- DE business with a VIES-valid VAT ID:      0% (reverse charge)
- US customer:                               0% (outside the EU)
```

### Payment gateways

```
PayPal   → mode (Sandbox / Live) and API credentials
Mollie   → API key (test_... / live_...) and the payment methods to offer
Transfer → the bank details shown to the customer
```

---

## 🛠️ Customizing

Templates live in `templates/`. To change one without touching the plugin, copy it to your theme as `<theme>/templates/vstore/<name>_template.php`:

```php
// <theme>/templates/vstore/vstore_template.php
$VSTORE_TEMPLATE['list']['item'] = '
<div class="product-card">
    {ITEM_PIC: w=300&h=300&crop=1}
    <h3>{ITEM_NAME}</h3>
    <p class="price">{ITEM_PRICE}</p>
    {ITEM_QTY}
    {ITEM_ADDTOCART}
</div>
';
```

E-mails use `vstore_email_layout_template.php` (document) and `vstore_email_template.php` (bodies); the invoice uses `vstore_invoice_template.php`.

---

## 🤝 Contributing

### Reporting bugs
- Check the [existing issues](https://github.com/e107-Kanonimpresor/vstore/issues)
- Open a new issue with: a clear description, steps to reproduce, e107, PHP and plugin versions, and any error message or screenshot

### Feature requests
- Open an issue with the `enhancement` label and describe the use case

### Pull requests
1. Fork the repository
2. Create a branch: `git checkout -b feature/amazing-feature`
3. Run the tests: `php tests/run.php`
4. Commit and push your branch, then open a Pull Request

### Testing
- Test on several PHP versions (7.4 to 8.4)
- Test payment gateways in sandbox mode
- Report compatibility issues

### Localization
- Language files are in `languages/<Language>/` (`<Language>_<area>.php`, array format, English fallback)
- Constants use the `LAN_VSTORE_*` prefix

---

## 📝 Changelog

See [CHANGELOG.md](CHANGELOG.md) for the complete list of changes.

**v2.1.0 (2026-10-18)**: quote mode, Bootstrap 5 storefront redesign, redesigned checkout and customer dashboard, add-to-cart effect. See the [CHANGELOG](CHANGELOG.md).

**v2.0.0 (2026-10-17)**: translations, Mollie MB WAY/Multibanco and webhook, EU VAT rewrite, new e-mails, built-in PDF invoices, business layer with tests, security review. See the [release notes](docs/RELEASE_NOTES_v2.0.0.md).

---

## 🐛 Known Issues

- Cancelling an order manually from the admin area does not give the stock back (payments that fail or expire do).
- The saved credentials of the removed gateways stay in the database preferences; they are ignored.
- The user manual is available in Spanish only.
- On localhost, Mollie cannot send the webhook: use the *Check payment in Mollie* button.
- E-mail on localhost needs an SMTP server (for example smtp4dev or Mailtrap).
- Clear the e107 cache after changing templates.
- PDF invoices need `vendor/tecnickcom/tcpdf` (run `composer install`).

---

## 📄 License

This project is licensed under the **GNU General Public License v3.0** - see the [LICENSE](LICENSE) file.

- ✅ Free to use for commercial and non-commercial purposes
- ✅ You may modify and distribute the code
- ✅ Modifications must be published under the same GPL-3.0 licence

---

## 🙏 Credits & Acknowledgments

### Maintainers
- Original developers: CaMerOn, Achim Ennenbach and the [e107 Community](https://e107.org)
- Current maintainer: [Kanonimpresor](https://marketingdeperformance.online) — Marketing de Performance ([GitHub](https://github.com/e107-Kanonimpresor))

### Dependencies
- [e107 CMS](https://github.com/e107inc/e107) - Content Management System
- [Omnipay](https://omnipay.thephpleague.com/) with its PayPal and Mollie drivers - payment abstraction
- [ibericode/vat](https://github.com/ibericode/vat) - EU VAT rates and VIES validation
- [TCPDF](https://tcpdf.org/) - PDF invoices

### Special Thanks
- The e107 community for testing and feedback
- Payment providers for their sandbox environments
- Everyone who reports bugs and sends pull requests

---

## 📞 Support & Community

- **Documentation**: [user manual](docs/user-manual.html) and the *User Guide* tab in the admin area
- **GitHub Issues**: [report bugs](https://github.com/e107-Kanonimpresor/vstore/issues)
- **e107 community**: [Q&A - Plugins](https://github.com/e107inc/e107/discussions/categories/q-a-plugins) and the [e107.org forums](https://e107.org/forum)

---

## 🗺️ Roadmap

### ✅ Delivered in 2.0.0
- [x] PHP 8.x full compatibility (7.4 to 8.4)
- [x] Enhanced coupon system
- [x] Complete localization (English, Spanish, Portuguese)
- [x] Mollie MB WAY and Multibanco, built-in PDF invoices, EU VAT rewrite, new e-mail design

### 🚧 In Progress
- [x] Product **quote** mode (2.1.0): leave the price empty and the product shows a "Quote / Presupuesto / Orçamento" button instead of "Add to cart"; quotes are saved in the admin area for a later hand-over to a certified billing app
- [ ] More e-mail improvements
- [ ] More languages (community translations)
- [ ] Mobile app integration

### Planned Features
- [ ] More payment gateways (Authorize.net, Square, etc.)
- [ ] Advanced analytics dashboard with charts
- [ ] Product reviews and ratings
- [ ] Wishlist functionality
- [ ] Abandoned cart recovery
- [ ] Multi-vendor marketplace support
- [ ] Subscription/recurring payments
- [ ] Gift cards and vouchers

---

## 📊 At a glance

- **Languages**: English, Portuguese, Spanish
- **Payment methods**: PayPal, Mollie (cards and local methods), bank transfer
- **Tests**: 242 automated checks (`php tests/run.php`), PHP 7.4, 8.2 and 8.4

---

<div align="center">

**Made with ❤️ for the e107 CMS Community**

[Report Bug](https://github.com/e107-Kanonimpresor/vstore/issues) · [Request Feature](https://github.com/e107-Kanonimpresor/vstore/issues) · [Releases](https://github.com/e107-Kanonimpresor/vstore/releases)

Copyright (c) 2026 Kanonimpresor / Marketing de Performance  
Plugin developed for e107 CMS — https://marketingdeperformance.online

</div>
