# Vstore Plugin for e107

#### (Choose your language / Elija su idioma / Escolha o seu idioma)

[![Language-English](https://img.shields.io/badge/Language-English-blue)](README.md) 
[![Language-Português](https://img.shields.io/badge/Language-Português-green)](README.pt-PT.md) 
[![Language-Español](https://img.shields.io/badge/Language-Español-red)](README.es-ES.md) 

---

[![e107](https://img.shields.io/badge/e107-v2.3.0+-blue.svg)](https://e107.org)
[![PHP](https://img.shields.io/badge/PHP-7.4%2B%20|%208.x-purple.svg)](https://php.net)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-3.x%20|%204.x%20|%205.x-7952B3.svg)](https://getbootstrap.com)
[![License](https://img.shields.io/badge/License-GPL--3.0-green.svg)](LICENSE)
[![Join the chat at https://gitter.im/e107inc/vstore](https://badges.gitter.im/e107inc/vstore.svg)](https://gitter.im/e107inc/vstore?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

---

## 📋 Description

**Vstore** is a comprehensive e-commerce solution for [e107 v2.x](https://e107.org) Bootstrap CMS. Built with flexibility and ease of use in mind, it provides everything you need to run a professional online store directly from your e107 website.

Whether you're selling physical products, digital downloads, or services, Vstore integrates seamlessly with your e107 site, offering powerful features like multiple payment gateways, advanced tax calculations, coupon management, and inventory tracking.

---

## ✨ Key Features

### 🛒 Shopping Cart & Checkout
- **Dynamic Shopping Cart**: Real-time cart updates with AJAX functionality
- **Guest Checkout**: Allow purchases without user registration
- **Multi-step Checkout**: Clean, intuitive checkout flow
- **Order History**: Customer dashboard with order tracking
- **Digital Downloads**: Support for downloadable products
- **Product Variations**: Size, color, and custom attributes for products

### 💳 Payment Processing
- **Multiple Payment Gateways**:
  - PayPal (Express Checkout & Standard)
  - Stripe (Credit/Debit Cards)
  - Mollie (iDEAL, Bancontact, and 20+ methods)
  - Braintree (PayPal & Credit Cards)
  - CoinPayments (Cryptocurrency)
  - Bank Transfer (Manual)
- **Secure Transactions**: PCI-compliant payment processing
- **Order Status Tracking**: New, Processing, Completed, Refunded, etc.

### 💰 Pricing & Discounts
- **Coupon System**:
  - Percentage or fixed amount discounts
  - Date range restrictions
  - Category/product inclusion/exclusion
  - Usage limits (global and per-user)
  - Minimum order requirements
- **Tax Calculation**:
  - EU VAT support with VIES validation
  - Multiple tax rates (standard, reduced, super-reduced)
  - B2B reverse charge mechanism
  - Country-specific tax classes
  - Automatic tax calculation based on location

### 📦 Product Management
- **Unlimited Products**: No restrictions on catalog size
- **Categories & Subcategories**: Organized product hierarchy
- **Product Variations**: Manage size, color, and custom attributes
- **Inventory Tracking**: Stock management with low-stock alerts
- **Image Galleries**: Multiple images per product with zoom
- **SEO-Friendly URLs**: Clean URLs for better search rankings
- **Related Products**: Cross-selling recommendations

### 🚚 Shipping & Fulfillment
- **Flexible Shipping Rates**:
  - Flat rate shipping
  - Weight-based calculation
  - Free shipping options
  - Per-product shipping costs
- **Shipping Address Management**: Separate billing and shipping addresses
- **Order Notes**: Customer notes and admin internal notes

### 📊 Analytics & Reporting
- **Sales Statistics**: Revenue tracking and trends
- **Order Reports**: Detailed order information
- **Product Performance**: Best-sellers and inventory reports
- **Customer Insights**: Purchase history and behavior

### 🎨 Customization
- **Template System**: Fully customizable HTML templates
- **Shortcodes**: Extensive shortcode library for custom layouts
- **Custom CSS**: Add your own styling
- **Responsive Design**: Mobile-friendly out of the box
- **Multi-language Support**: Translation-ready with LAN files

### 🔧 Admin Features
- **Intuitive Dashboard**: Manage orders, products, and customers
- **Bulk Actions**: Edit multiple items simultaneously
- **Email Templates**: Customizable order confirmation emails
- **Invoice Generation**: Automatic PDF invoices (requires PDF plugin)
- **User Permissions**: Role-based access control
- **Search Integration**: Products searchable via e107 search

---

## 🚀 Installation

### Requirements
- **e107 CMS**: v2.3.0 or higher ([Download](https://github.com/e107inc/e107))
- **PHP**: 7.4 or higher (PHP 8.x compatible)
- **PHP Extensions**:
  - SOAP (for VAT validation)
  - cURL (for payment gateways)
  - JSON
  - GD or Imagick (for image processing)
- **Database**: MySQL 5.7+ / MariaDB 10.2+
- **Optional**: [PDF Plugin](https://github.com/e107inc/pdf) for invoice generation

### Installation Steps

1. **Download the Plugin**:
   ```bash
   cd e107_plugins
   git clone https://github.com/Kanonimpresor/vstore.git
   ```

2. **Install Dependencies** (if installing from source):
   ```bash
   cd vstore
   composer install
   ```

3. **Install via e107 Admin**:
   - Navigate to: **Admin → Plugins → Manage Plugins**
   - Find "Vstore" in the list
   - Click **Install**

4. **Configure Settings**:
   - Go to: **Admin → Vstore → Settings**
   - Configure:
     - Store information (name, address, tax ID)
     - Tax settings (enable VAT, tax rates)
     - Payment gateways (API credentials)
     - Shipping options
     - Email templates

5. **Create Products**:
   - Navigate to: **Admin → Vstore → Products**
   - Add your first product with images and pricing

6. **Test Checkout**:
   - Add a product to cart
   - Complete a test order (use sandbox/test mode for gateways)

---

## 📖 Documentation

### Quick Start Guides
- [Creating Your First Product](wiki/creating-products.md)
- [Setting Up Payment Gateways](wiki/payment-gateways.md)
- [Configuring Tax Settings](wiki/tax-configuration.md)
- [Managing Coupons](wiki/coupons.md)
- [Customizing Templates](wiki/templates.md)

### Advanced Topics
- [Invoice Generation](wiki/invoice.md)
- [Custom Shortcodes](wiki/shortcodes.md)
- [Database Schema](wiki/database.md)
- [API Integration](wiki/api.md)
- [Security Best Practices](wiki/security.md)

### Developer Resources
- [Plugin Architecture](wiki/architecture.md)
- [Extending Vstore](wiki/extending.md)
- [Payment Gateway Development](wiki/gateway-development.md)
- [Coding Standards](wiki/coding-standards.md)

---

## 🔧 Configuration

### Tax Settings (EU VAT Example)

```php
// Admin → Vstore → Settings → Tax
Business Country: PT (Portugal)
Tax Calculation: Enabled
Check VAT ID: Enabled

Tax Classes:
- Standard: 23% (Portugal)
- Reduced: 13%
- Super Reduced: 6%

// Automatic EU VAT handling:
// - PT customer (B2C): 23% VAT
// - ES customer (B2C): Spanish VAT rate
// - DE customer with valid VAT ID (B2B): 0% (reverse charge)
// - US customer: 0% (outside EU)
```

### Payment Gateway Configuration

#### PayPal
```php
Mode: Sandbox / Live
Client ID: YOUR_PAYPAL_CLIENT_ID
Client Secret: YOUR_PAYPAL_SECRET
```

#### Stripe
```php
Mode: Test / Live
Publishable Key: pk_test_...
Secret Key: sk_test_...
```

#### Mollie
```php
API Key: test_... / live_...
Enabled Methods: iDEAL, Credit Card, Bancontact
```

---

## 🛠️ Usage Examples

### Display Product List
```php
// In any e107 page
{VSTORE_LIST: cat=1&limit=12&orderby=price}
```

### Show Shopping Cart Icon
```php
// In menu or header
{LAN_PLUGIN_VSTORE_CARTICON}
```

### Custom Product Display
```php
// Create custom template in templates/vstore_template.php
$VSTORE_TEMPLATE['list']['item'] = '
<div class="product-card">
    {ITEM_PIC: w=300&h=300&crop=1}
    <h3>{ITEM_NAME}</h3>
    <p class="price">{ITEM_PRICE}</p>
    {ITEM_ADDTOCART}
</div>
';
```

---

## 🤝 Contributing

We welcome contributions from the community! Here's how you can help:

### Reporting Bugs
- Check [existing issues](https://github.com/Kanonimpresor/vstore/issues)
- Create a new issue with:
  - Clear description of the problem
  - Steps to reproduce
  - e107 version, PHP version, and plugin version
  - Any error messages or screenshots

### Feature Requests
- Open an issue with the `enhancement` label
- Describe the feature and use case
- Explain why it would benefit other users

### Pull Requests
1. Fork the repository
2. Create a feature branch: `git checkout -b feature/amazing-feature`
3. Commit your changes: `git commit -m 'Add amazing feature'`
4. Push to the branch: `git push origin feature/amazing-feature`
5. Open a Pull Request

### Testing
- Testing of all features is greatly appreciated
- Test on different PHP versions (7.4, 8.0, 8.1, 8.2)
- Test payment gateways in sandbox mode
- Report compatibility issues

### Localization
- Translations should occur after features are stable
- Language files located in: `languages/[Language]/`
- Follow e107 LAN file standards
- Test with RTL languages if applicable

---

## 📝 Changelog

See [CHANGELOG.md](CHANGELOG.md) for detailed list of changes, bug fixes, and new features.

**Latest Updates (November 2025)**:
- ✅ Fixed compatibility with ibericode/vat v2.x
- ✅ Resolved cart emptying when applying coupons
- ✅ Fixed shipping data validation in checkout
- ✅ PHP 8.x compatibility improvements
- ✅ Enhanced error handling and validation

---

## 🐛 Known Issues

### Current Limitations
- **PDF Generation**: Some warnings with TCPDF on PHP 8.x (non-critical)
- **Email on Localhost**: Requires SMTP configuration for email sending
- **Cache**: Clear e107 cache after template changes

### Workarounds
- PDF warnings can be suppressed or disable PDF generation
- Use mail service like Mailtrap or Gmail SMTP for testing
- Always clear cache: Admin → System → Clear Cache

---

## 📄 License

This project is licensed under the **GNU General Public License v3.0** - see the [LICENSE](LICENSE) file for details.

### What this means:
- ✅ Free to use for commercial and non-commercial purposes
- ✅ Modify and distribute the code
- ✅ Must disclose source code of modifications
- ✅ Must use the same GPL-3.0 license for derivatives

---

## 🙏 Credits & Acknowledgments

### Maintainers
- Original Developer: [e107 Community](https://e107.org)
- Current Maintainer: [Kanonimpresor](https://github.com/Kanonimpresor)

### Dependencies
- [e107 CMS](https://github.com/e107inc/e107) - Content Management System
- [Omnipay](https://omnipay.thephpleague.com/) - Payment processing abstraction
- [ibericode/vat](https://github.com/ibericode/vat) - EU VAT validation
- [TCPDF](https://tcpdf.org/) - PDF generation

### Special Thanks
- e107 community for continuous testing and feedback
- Payment gateway providers for sandbox environments
- All contributors who submit bug reports and pull requests

---

## 📞 Support & Community

### Getting Help
- **Documentation**: Check the [Wiki](wiki/) first
- **Community Chat**: [Gitter Channel](https://gitter.im/e107inc/vstore)
- **e107 Forums**: [e107.org Forums](https://e107.org/forum)
- **GitHub Issues**: [Report bugs](https://github.com/Kanonimpresor/vstore/issues)

### Stay Updated
- **GitHub**: Star and watch this repository
- **e107 News**: [e107.org](https://e107.org)
- **Social Media**: Follow e107 CMS updates

---

## 🗺️ Roadmap

### Planned Features
- [ ] More payment gateways (Authorize.net, Square, etc.)
- [ ] Advanced analytics dashboard with charts
- [ ] Product reviews and ratings
- [ ] Wishlist functionality
- [ ] Abandoned cart recovery
- [ ] Multi-vendor marketplace support
- [ ] Subscription/recurring payments
- [ ] Gift cards and vouchers

### In Progress
- [x] PHP 8.x full compatibility
- [x] Enhanced coupon system
- [ ] Complete localization (multiple languages)
- [ ] Mobile app integration

---

## 📊 Statistics

- **Active Installations**: Growing e107 community
- **Supported Languages**: English, Portuguese, Spanish (more coming)
- **Payment Gateways**: 6+ integrated
- **Average Rating**: ⭐⭐⭐⭐⭐ (Community feedback)

---

<div align="center">

**Made with ❤️ for the e107 CMS Community**

[Report Bug](https://github.com/Kanonimpresor/vstore/issues) · [Request Feature](https://github.com/Kanonimpresor/vstore/issues) · [Documentation](wiki/)

</div> 

