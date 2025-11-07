# Vstore v1.1.0 - PHP 8.x Compatibility & Critical Bug Fixes

**Release Date**: November 7, 2025  
**Compatibility**: e107 v2.3.0+ | PHP 7.4 - 8.x | Bootstrap 3/4/5

---

## 🚨 IMPORTANT: Installation Instructions

⚠️ **After downloading or cloning this release, you MUST run:**

```bash
cd vstore
composer install --no-dev
```

**Why?** The `vendor/` directory (containing dependencies) is not included in the repository to keep it lightweight. Without running `composer install`, you will see errors like:

```
Fatal error: Failed opening required 'vendor/autoload.php'
```

---

## 🎯 What's New in v1.1.0

This release focuses on **modernization**, **stability**, and **PHP 8.x compatibility**. All critical bugs reported in the previous version have been resolved.

### ✅ Major Fixes

#### 1. **PHP 8.x Full Compatibility**
- Fixed all "Undefined array key" warnings (11 locations)
- Added proper type checking and defensive programming patterns
- Tested on PHP 7.4, 8.0, 8.1, 8.2, and 8.3

#### 2. **ibericode/vat Library Update (v1.x → v2.x)**
- Updated namespace from `DvK\Vat\*` to `Ibericode\Vat\*` (14 references)
- Migrated deprecated API methods:
  - `Countries::inEurope()` → `Countries::isCountryCodeInEU()`
  - `Rates::country()` → `Rates::getRateForCountry()`
  - `Validator::validate()` → `Validator::validateVatNumber()`
- Refactored `getTaxClass()` to work without deprecated `all()` method

#### 3. **Coupon System Fixes**
- ✅ **Date Validation**: Automatic conversion from datetime strings to Unix timestamps
- ✅ **Cart Preservation**: Fixed critical bug where cart was emptied when applying coupons
- ✅ **Error Messages**: Improved error messages showing exact availability dates
- ✅ **Edge Cases**: Fixed issues with valid coupons being incorrectly rejected

#### 4. **Checkout Process Improvements**
- ✅ **Shipping Data**: Fixed "No shipping data set!" error
- ✅ **Auto-copy**: Billing address automatically copied to shipping when `shipping_type = 0`
- ✅ **Data Flow**: Improved validation between customer and shipping forms

#### 5. **Admin Panel Fixes**
- Fixed `filter_options` undefined array key warning (orders admin)
- Fixed `notes` undefined key in shipping data display
- Fixed `add_field0-3` warnings in customer forms
- Corrected array access in PDF generation functions

---

## 📝 Full Changelog

### Added
- **Documentation**: Enhanced installation instructions in README (EN/ES/PT)
- **Warnings**: Prominent notices about required `composer install` step
- **Troubleshooting**: Common installation error solutions

### Fixed
- **Namespace**: 14 references updated from `DvK\Vat` to `Ibericode\Vat`
- **Coupon dates**: Automatic timestamp conversion for validation
- **Cart preservation**: Prevent emptying cart during coupon application
- **Shipping data**: Auto-copy billing to shipping when needed
- **PHP 8.x warnings**: 11 undefined array key issues resolved
- **PDF generation**: Type checking for foreach() operations
- **Admin forms**: Array validation in orders and customer forms

### Changed
- **CHANGELOG**: Updated to version 1.1.0 format
- **README**: Installation section rewritten with clearer instructions
- **Dependencies**: Composer autoloader properly documented

---

## 🔧 Installation

### New Installation

1. **Download this release** (ZIP or clone)
2. **Extract to**: `e107_plugins/vstore/`
3. **Install dependencies**:
   ```bash
   cd e107_plugins/vstore
   composer install --no-dev
   ```
4. **Install via e107 Admin**:
   - Go to: **Admin → Plugins → Manage Plugins**
   - Find "Vstore" and click **Install**
5. **Configure**: Admin → Vstore → Settings

### Upgrade from Previous Version

1. **Backup your database** (critical!)
2. **Backup your current vstore folder**
3. **Replace files** with this release
4. **Run Composer**:
   ```bash
   cd e107_plugins/vstore
   composer install --no-dev
   ```
5. **Clear e107 cache**: Admin → System → Clear Cache
6. **Test thoroughly** before going live

---

## ⚙️ System Requirements

- **e107 CMS**: v2.3.0 or higher
- **PHP**: 7.4 - 8.3 (fully tested)
- **Composer**: v2.0+ (for dependency management)
- **PHP Extensions**:
  - SOAP (for VAT validation)
  - cURL (for payment gateways)
  - JSON
  - GD or Imagick (image processing)
- **Database**: MySQL 5.7+ / MariaDB 10.2+

---

## 🧪 Testing Status

✅ **PHP 7.4**: Fully tested  
✅ **PHP 8.0**: Fully tested  
✅ **PHP 8.1**: Fully tested  
✅ **PHP 8.2**: Fully tested  
✅ **PHP 8.3**: Fully tested  

✅ **Order Creation**: Verified (Order #NODA000001)  
✅ **Coupon Application**: Working correctly  
✅ **Tax Calculation**: EU VAT validation functional  
✅ **Payment Gateways**: PayPal, Stripe, Mollie tested  
✅ **Invoice Generation**: PDF creation working  

---

## 📚 Documentation

- **English**: [README.md](README.md)
- **Español**: [README.es-ES.md](README.es-ES.md)
- **Português**: [README.pt-PT.md](README.pt-PT.md)
- **Changelog**: [CHANGELOG.md](CHANGELOG.md)
- **Wiki**: [Full Documentation](wiki/Home.md)

---

## 🐛 Known Issues

None at this time. If you encounter any issues, please [open an issue](https://github.com/Kanonimpresor/vstore/issues) on GitHub.

---

## 💬 Support

- **Gitter Chat**: https://gitter.im/e107inc/vstore
- **e107 Forums**: https://e107.org/forum
- **GitHub Issues**: https://github.com/Kanonimpresor/vstore/issues

---

## 👥 Contributors

This release was made possible by:
- **CaMerOn** - Original author
- **Achim Ennenbach** - Core development
- **e107inc Team** - Framework support
- **Community contributors** - Bug reports and testing

---

## 📄 License

GPL-3.0 License - See [LICENSE](LICENSE) file for details

---

## 🙏 Acknowledgments

- **ibericode/vat** library for EU VAT validation
- **Omnipay** project for payment gateway abstraction
- **e107 CMS community** for continuous support

---

**Enjoy the new release! 🎉**

If this plugin helps your business, consider ⭐ starring the repository on GitHub!
