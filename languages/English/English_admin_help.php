<?php
if (!defined('e107_INIT')) { exit; }

// START
define('LAN_VSTORE_GUIDE_START_TAB',    'Quick start');
define('LAN_VSTORE_GUIDE_START_TITLE',  'Open your store in six steps');
define('LAN_VSTORE_GUIDE_START_INTRO',  'Follow these steps in order. Each one links to a tab of this admin area.');
define('LAN_VSTORE_GUIDE_START_S1',     'Open <strong>Preferences</strong> and set the currency, price display and weight unit in the General tab.');
define('LAN_VSTORE_GUIDE_START_S2',     'Create at least one category in <strong>Categories</strong>.');
define('LAN_VSTORE_GUIDE_START_S3',     'Add your products in <strong>Products</strong>. Use a unique code for each product.');
define('LAN_VSTORE_GUIDE_START_S4',     'Configure <strong>Shipping</strong> and <strong>Tax</strong> in the Preferences tabs, if your store needs them.');
define('LAN_VSTORE_GUIDE_START_S5',     'Enter the credentials of at least one gateway in <strong>Payment Gateways</strong> and activate it.');
define('LAN_VSTORE_GUIDE_START_S6',     'Place a test order, then follow it in <strong>Sales</strong>.');

// CATALOG
define('LAN_VSTORE_GUIDE_CATALOG_TAB',    'Catalog');
define('LAN_VSTORE_GUIDE_CATALOG_TITLE',  'Categories, products and variations');
define('LAN_VSTORE_GUIDE_CATALOG_INTRO',  'The catalog is built from three related elements.');
define('LAN_VSTORE_GUIDE_CATALOG_CAT_H',  'Categories');
define('LAN_VSTORE_GUIDE_CATALOG_CAT_T',  'Categories organise the shop and can be nested. Restrict a category to a user class to hide it from other visitors.');
define('LAN_VSTORE_GUIDE_CATALOG_PROD_H', 'Products');
define('LAN_VSTORE_GUIDE_CATALOG_PROD_T', 'Each product has a unique code (uppercase letters, digits and hyphens), a price, an inventory and a tax class. Use <code>-1</code> as inventory for unlimited stock. Images and videos are added in their own tab.');
define('LAN_VSTORE_GUIDE_CATALOG_VAR_H',  'Variations');
define('LAN_VSTORE_GUIDE_CATALOG_VAR_T',  'Create a variation group (for example sizes) once, then assign up to two groups to each product. A variation can change the price by a fixed amount or a percentage and can track its own inventory.');

// COUPONS
define('LAN_VSTORE_GUIDE_COUPONS_TAB',    'Coupons');
define('LAN_VSTORE_GUIDE_COUPONS_TITLE',  'Discount coupons');
define('LAN_VSTORE_GUIDE_COUPONS_INTRO',  'Coupons are entered by customers in the cart.');
define('LAN_VSTORE_GUIDE_COUPONS_TYPES_H','Discount types');
define('LAN_VSTORE_GUIDE_COUPONS_TYPES_T','A percentage coupon discounts a share of each eligible item. A fixed coupon never discounts more than the price of the item.');
define('LAN_VSTORE_GUIDE_COUPONS_RULES_H','Restrictions');
define('LAN_VSTORE_GUIDE_COUPONS_RULES_T','Set the start and end dates and include or exclude categories in the Restrictions tab. Excluded categories always win over included ones.');
define('LAN_VSTORE_GUIDE_COUPONS_LIMITS_H','Limits');
define('LAN_VSTORE_GUIDE_COUPONS_LIMITS_T','Limit how many times a coupon can be used in total and per user in the Limits tab. Use <code>-1</code> for no limit.');

// TAX
define('LAN_VSTORE_GUIDE_TAX_TAB',        'Tax and VAT');
define('LAN_VSTORE_GUIDE_TAX_TITLE',      'Tax calculation');
define('LAN_VSTORE_GUIDE_TAX_INTRO',      'Tax is calculated at checkout from the billing country entered by the customer.');
define('LAN_VSTORE_GUIDE_TAX_SETUP_H',    'Setup');
define('LAN_VSTORE_GUIDE_TAX_SETUP_T',    'In Preferences, open the Tax tab, enable tax calculation and select your business country. Enable the online VAT ID check if you sell to EU businesses.');
define('LAN_VSTORE_GUIDE_TAX_CLASSES_H',  'Tax classes');
define('LAN_VSTORE_GUIDE_TAX_CLASSES_T',  'Enter tax values as decimals, for example <code>0.19</code> for 19%. Assign a tax class to each product in the Advanced tab.');
define('LAN_VSTORE_GUIDE_TAX_SCEN_H',     'What the customer pays');
define('LAN_VSTORE_GUIDE_TAX_SC1',        'Customer in your country: your local rate applies.');
define('LAN_VSTORE_GUIDE_TAX_SC2',        'Consumer in another EU country: the rate of the destination country applies.');
define('LAN_VSTORE_GUIDE_TAX_SC3',        'EU business with a valid VAT ID: 0% (reverse charge).');
define('LAN_VSTORE_GUIDE_TAX_SC4',        'Customer outside the EU: 0% (export).');

// SHIPPING
define('LAN_VSTORE_GUIDE_SHIP_TAB',       'Shipping');
define('LAN_VSTORE_GUIDE_SHIP_TITLE',     'Shipping costs');
define('LAN_VSTORE_GUIDE_SHIP_INTRO',     'Enable shipping in the Shipping tab of Preferences, then choose a calculation method.');
define('LAN_VSTORE_GUIDE_SHIP_M1',        '<strong>Sum of all items</strong>: adds the shipping cost of every item in the cart.');
define('LAN_VSTORE_GUIDE_SHIP_M2',        '<strong>Sum of unique items</strong>: adds the shipping cost of each different product only once.');
define('LAN_VSTORE_GUIDE_SHIP_M3',        '<strong>Tiered</strong>: costs depend on thresholds of the order price or weight. Start with the lowest threshold and make the last one higher than your largest order.');

// GATEWAYS
define('LAN_VSTORE_GUIDE_GATE_TAB',       'Payments');
define('LAN_VSTORE_GUIDE_GATE_TITLE',     'Payment gateways');
define('LAN_VSTORE_GUIDE_GATE_INTRO',     'Each gateway has its own tab with its credentials and an activation switch.');
define('LAN_VSTORE_GUIDE_GATE_G1',        '<strong>PayPal</strong> (standard and REST): requires API credentials from your PayPal account.');
define('LAN_VSTORE_GUIDE_GATE_G2',        '<strong>Mollie</strong>: requires an API key and lets you choose the payment methods offered at checkout.');
define('LAN_VSTORE_GUIDE_GATE_G3',        '<strong>Bank transfer</strong>: the order stays pending until you mark it as paid.');
define('LAN_VSTORE_GUIDE_GATE_NOTE',      'Always test a gateway in sandbox mode before going live.');

// ORDERS
define('LAN_VSTORE_GUIDE_ORDERS_TAB',     'Orders');
define('LAN_VSTORE_GUIDE_ORDERS_TITLE',   'Orders, emails and invoices');
define('LAN_VSTORE_GUIDE_ORDERS_INTRO',   'Everything related to a sale is managed from the Sales tab.');
define('LAN_VSTORE_GUIDE_ORDERS_ST_H',    'Order status');
define('LAN_VSTORE_GUIDE_ORDERS_ST_T',    'An order moves through New, Processing, On Hold, Completed, Cancelled and Refunded. The status changes update inventory and send the matching email.');
define('LAN_VSTORE_GUIDE_ORDERS_EM_H',    'Email templates');
define('LAN_VSTORE_GUIDE_ORDERS_EM_T',    'There is one template for each status change. Use the reset button to restore the original text.');
define('LAN_VSTORE_GUIDE_ORDERS_INV_H',   'Invoices');
define('LAN_VSTORE_GUIDE_ORDERS_INV_T',   'PDF invoices are created by Vstore itself (TCPDF). Define the number prefix, the next number and the footer in Invoice Settings and check the result in the preview tab.');

// TROUBLESHOOTING
define('LAN_VSTORE_GUIDE_TROUBLE_TAB',    'Troubleshooting');
define('LAN_VSTORE_GUIDE_TROUBLE_TITLE',  'Common problems');
define('LAN_VSTORE_GUIDE_TROUBLE_INTRO',  'Check these points before asking for support.');
define('LAN_VSTORE_GUIDE_TROUBLE_Q1',     'A fatal error mentions a missing class or autoload file');
define('LAN_VSTORE_GUIDE_TROUBLE_A1',     'Run <code>composer install --no-dev --optimize-autoloader</code> inside the plugin folder. The <code>vendor</code> directory is not included in the repository.');
define('LAN_VSTORE_GUIDE_TROUBLE_Q2',     'Invoices are not created as PDF');
define('LAN_VSTORE_GUIDE_TROUBLE_A2',     'Run "composer install" in e107_plugins/vstore and enable PDF invoices in Invoice Settings.');
define('LAN_VSTORE_GUIDE_TROUBLE_Q3',     'The wrong tax rate is applied');
define('LAN_VSTORE_GUIDE_TROUBLE_A3',     'Check the business country, the tax class of the product and the country entered in the billing form.');
define('LAN_VSTORE_GUIDE_TROUBLE_Q4',     'I need more information about what is happening');
define('LAN_VSTORE_GUIDE_TROUBLE_A4',     'Uncomment the <code>e_DEBUG_VSTORE</code> line in <code>e_header.php</code> to enable the debug log, and remove it again in production.');
