# Changelog

All notable changes to the Vstore e-commerce plugin will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

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
