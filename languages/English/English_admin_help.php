<?php
if (!defined('e107_INIT')) { exit; }

// Plain text only (inline <code>/<strong> allowed). The HTML structure lives in templates/vstore_guide_template.php.

// OVERVIEW
define('LAN_VSTORE_GUIDE_OVERVIEW_TAB',    'Overview');
define('LAN_VSTORE_GUIDE_OVERVIEW_TITLE',  'Vstore at a glance');
define('LAN_VSTORE_GUIDE_OVERVIEW_INTRO',  'Vstore turns your e107 website into an online store: catalog, shopping cart, checkout, payments, invoices and a customer area. Products without a price are handled as quote requests. This guide explains each part of the admin area.');
define('LAN_VSTORE_GUIDE_OVERVIEW_FLOW1',  'Preferences');
define('LAN_VSTORE_GUIDE_OVERVIEW_FLOW2',  'Catalog');
define('LAN_VSTORE_GUIDE_OVERVIEW_FLOW3',  'Payments');
define('LAN_VSTORE_GUIDE_OVERVIEW_FLOW4',  'Test order');
define('LAN_VSTORE_GUIDE_OVERVIEW_FLOW5',  'Sales');
define('LAN_VSTORE_GUIDE_OVERVIEW_FEAT_H', 'What Vstore does');
define('LAN_VSTORE_GUIDE_OVERVIEW_F1_T',   'Catalog');
define('LAN_VSTORE_GUIDE_OVERVIEW_F1_D',   'Nested categories, products, variations, a picture gallery and downloadable files.');
define('LAN_VSTORE_GUIDE_OVERVIEW_F2_T',   'Quote requests');
define('LAN_VSTORE_GUIDE_OVERVIEW_F2_D',   'A product without price is requested instead of bought, with its own workflow.');
define('LAN_VSTORE_GUIDE_OVERVIEW_F3_T',   'Mixed cart');
define('LAN_VSTORE_GUIDE_OVERVIEW_F3_D',   'Products to buy and products to quote share one cart, each block with its own button.');
define('LAN_VSTORE_GUIDE_OVERVIEW_F4_T',   'Tax and EU VAT');
define('LAN_VSTORE_GUIDE_OVERVIEW_F4_D',   'Rates per country and tax class, reverse charge with a valid VAT ID.');
define('LAN_VSTORE_GUIDE_OVERVIEW_F5_T',   'Payments');
define('LAN_VSTORE_GUIDE_OVERVIEW_F5_D',   'PayPal, Mollie and bank transfer, each one switched on separately.');
define('LAN_VSTORE_GUIDE_OVERVIEW_F6_T',   'PDF invoices');
define('LAN_VSTORE_GUIDE_OVERVIEW_F6_D',   'Created by Vstore itself with its own numbering and footer.');
define('LAN_VSTORE_GUIDE_OVERVIEW_F7_T',   'Customer area');
define('LAN_VSTORE_GUIDE_OVERVIEW_F7_D',   'Orders, downloads, open quotes and a chart of the last six months.');
define('LAN_VSTORE_GUIDE_OVERVIEW_F8_T',   'Statistics');
define('LAN_VSTORE_GUIDE_OVERVIEW_F8_D',   'Sales boxes plus a counter of the quote requests waiting for an answer.');
define('LAN_VSTORE_GUIDE_OVERVIEW_WHERE_H','Where to find things');
define('LAN_VSTORE_GUIDE_OVERVIEW_W1_N',   'Preferences');
define('LAN_VSTORE_GUIDE_OVERVIEW_W1_D',   'Currency, shipping, tax, checkout and display settings.');
define('LAN_VSTORE_GUIDE_OVERVIEW_W2_N',   'Categories and Products');
define('LAN_VSTORE_GUIDE_OVERVIEW_W2_D',   'The catalog, including images, prices, inventory and variations.');
define('LAN_VSTORE_GUIDE_OVERVIEW_W3_N',   'Coupons');
define('LAN_VSTORE_GUIDE_OVERVIEW_W3_D',   'Discount codes with dates, categories and usage limits.');
define('LAN_VSTORE_GUIDE_OVERVIEW_W4_N',   'Payment Gateways');
define('LAN_VSTORE_GUIDE_OVERVIEW_W4_D',   'Credentials and activation of each payment method.');
define('LAN_VSTORE_GUIDE_OVERVIEW_W5_N',   'Sales');
define('LAN_VSTORE_GUIDE_OVERVIEW_W5_D',   'Orders and quote requests, with their status, invoice and history.');
define('LAN_VSTORE_GUIDE_OVERVIEW_W6_N',   'Email Templates and Invoice Settings');
define('LAN_VSTORE_GUIDE_OVERVIEW_W6_D',   'The messages sent to customers and the look of the PDF invoice.');
define('LAN_VSTORE_GUIDE_OVERVIEW_TIP',    'The sidebar of every admin page shows a short tip for the page you are on, and links to the documentation and the issue tracker.');

// QUICK START
define('LAN_VSTORE_GUIDE_START_TAB',       'Quick start');
define('LAN_VSTORE_GUIDE_START_TITLE',     'Open your store in six steps');
define('LAN_VSTORE_GUIDE_START_INTRO',     'Follow these steps in order. Each one points to a menu entry of this admin area.');
define('LAN_VSTORE_GUIDE_START_S1_T',      'Set the basics');
define('LAN_VSTORE_GUIDE_START_S1_D',      'Open <strong>Preferences</strong> and choose the currency, the price display and the weight unit in the General tab.');
define('LAN_VSTORE_GUIDE_START_S2_T',      'Create categories');
define('LAN_VSTORE_GUIDE_START_S2_D',      'Add at least one category in <strong>Categories</strong>. A picture makes the category tile of the store look better.');
define('LAN_VSTORE_GUIDE_START_S3_T',      'Add products');
define('LAN_VSTORE_GUIDE_START_S3_D',      'Create your products in <strong>Products</strong> with a unique code, price, inventory and pictures. Leave the price empty to offer a quote.');
define('LAN_VSTORE_GUIDE_START_S4_T',      'Shipping and tax');
define('LAN_VSTORE_GUIDE_START_S4_D',      'Configure <strong>Shipping</strong> and <strong>Tax</strong> in the Preferences tabs, if your store needs them.');
define('LAN_VSTORE_GUIDE_START_S5_T',      'Activate a payment method');
define('LAN_VSTORE_GUIDE_START_S5_D',      'Enter the credentials of at least one gateway in <strong>Payment Gateways</strong> and switch it on. Use sandbox mode first.');
define('LAN_VSTORE_GUIDE_START_S6_T',      'Place a test order');
define('LAN_VSTORE_GUIDE_START_S6_D',      'Buy a product from the store, then follow the order in <strong>Sales</strong> and check the e-mails and the invoice.');
define('LAN_VSTORE_GUIDE_START_NOTE',      'The store links (shop and cart) are created in the site navigation when the plugin is installed. If visitors cannot see them, review who may see them in the site links.');

// CATALOG
define('LAN_VSTORE_GUIDE_CATALOG_TAB',     'Catalog');
define('LAN_VSTORE_GUIDE_CATALOG_TITLE',   'Categories, products and variations');
define('LAN_VSTORE_GUIDE_CATALOG_INTRO',   'The catalog is built from three related elements.');
define('LAN_VSTORE_GUIDE_CATALOG_CAT_H',   'Categories');
define('LAN_VSTORE_GUIDE_CATALOG_CAT_T',   'Categories organise the shop and can be nested. The store shows the root categories as tiles with picture and product count. Restrict a category to a user class to hide it from other visitors.');
define('LAN_VSTORE_GUIDE_CATALOG_PROD_H',  'Products');
define('LAN_VSTORE_GUIDE_CATALOG_PROD_T',  'Each product has a unique code (uppercase letters, digits and hyphens), a price, an inventory and a tax class. Use <code>-1</code> as inventory for unlimited stock.');
define('LAN_VSTORE_GUIDE_CATALOG_VAR_H',   'Variations');
define('LAN_VSTORE_GUIDE_CATALOG_VAR_T',   'Create a variation group (for example sizes) once, then assign up to two groups to each product. A variation can change the price by a fixed amount or a percentage and can track its own inventory.');
define('LAN_VSTORE_GUIDE_CATALOG_IMG_H',   'Images and gallery');
define('LAN_VSTORE_GUIDE_CATALOG_IMG_T',   'Add the pictures of a product in its Images tab.');
define('LAN_VSTORE_GUIDE_CATALOG_IMG_L1',  'The first picture is the main picture; up to twelve more appear as thumbnails under it.');
define('LAN_VSTORE_GUIDE_CATALOG_IMG_L2',  'A click on the picture opens it in a full-size viewer. Visitors move with the arrows or the keyboard and close it with <code>Esc</code>.');
define('LAN_VSTORE_GUIDE_CATALOG_IMG_L3',  'Upload pictures at least 1200 px wide: the viewer asks for a 1600 px version, created and cached by e107.');
define('LAN_VSTORE_GUIDE_CATALOG_PRICE_H', 'Price field');
define('LAN_VSTORE_GUIDE_CATALOG_PRICE_T', 'The price decides how the product is offered.');
define('LAN_VSTORE_GUIDE_CATALOG_P1_N',    'Price above 0');
define('LAN_VSTORE_GUIDE_CATALOG_P1_D',    'Normal product: "Add to cart" button, goes through the checkout and the payment.');
define('LAN_VSTORE_GUIDE_CATALOG_P2_N',    'Price 0');
define('LAN_VSTORE_GUIDE_CATALOG_P2_D',    'Free product, for example a download. It is still bought through the cart.');
define('LAN_VSTORE_GUIDE_CATALOG_P3_N',    'Price empty');
define('LAN_VSTORE_GUIDE_CATALOG_P3_D',    'Quote product: the store shows "Price on request" and a "Quote" button. See the Quotes tab.');

// QUOTES
define('LAN_VSTORE_GUIDE_QUOTES_TAB',      'Quotes');
define('LAN_VSTORE_GUIDE_QUOTES_TITLE',    'Quote requests');
define('LAN_VSTORE_GUIDE_QUOTES_INTRO',    'Sell products that need a custom price: the customer asks, you answer with a quote. Quote requests are saved as orders with their own workflow.');
define('LAN_VSTORE_GUIDE_QUOTES_HOW_H',    'How it works');
define('LAN_VSTORE_GUIDE_QUOTES_E1_T',     '1. The customer asks');
define('LAN_VSTORE_GUIDE_QUOTES_E1_D',     'Adds products without price to the cart and presses <strong>Request quote</strong>. The checkout has the same steps as a purchase but shows no prices, tax or payment method.');
define('LAN_VSTORE_GUIDE_QUOTES_E2_T',     '2. You review it');
define('LAN_VSTORE_GUIDE_QUOTES_E2_D',     'The request arrives in <strong>Sales</strong> (filter <em>Quotes</em>) and in the e-mail of the shop. Use the buttons of the order page to move it through the workflow.');
define('LAN_VSTORE_GUIDE_QUOTES_E3_T',     '3. You invoice it');
define('LAN_VSTORE_GUIDE_QUOTES_E3_D',     'The quote is handed to your external billing application, which issues the invoice. Mark the request as <em>Invoiced</em> when it is done.');
define('LAN_VSTORE_GUIDE_QUOTES_MIX_H',    'Buying and quoting in the same cart');
define('LAN_VSTORE_GUIDE_QUOTES_MIX_T',     'The cart shows two blocks: <strong>Products to buy</strong> (prices, coupon, totals and the Checkout button) and <strong>Products to quote</strong> (no prices and a Request quote button). Each button processes only its own lines; the other lines stay in the cart for the next step.');
define('LAN_VSTORE_GUIDE_QUOTES_RULES_H',  'Rules to remember');
define('LAN_VSTORE_GUIDE_QUOTES_R1',       'A quote never gets an invoice number, so it does not use the certified invoice sequence.');
define('LAN_VSTORE_GUIDE_QUOTES_R2',       'A quote does not change the inventory and is not counted as an order in the statistics.');
define('LAN_VSTORE_GUIDE_QUOTES_R3',       'One e-mail is sent when the request is created, to the customer and as a copy to the shop. Later status changes send nothing.');
define('LAN_VSTORE_GUIDE_QUOTES_R4',       'The customer can see the request and cancel it from the customer area. A cancelled request can be reopened as a quote request from the order page.');
define('LAN_VSTORE_GUIDE_QUOTES_R5',       'The statistics page shows a red counter with the requests still waiting; it links to the filtered list.');
define('LAN_VSTORE_GUIDE_QUOTES_NOTE',     'The text of the quote e-mail is edited in Email Templates, in the Quote request tab.');

// COUPONS
define('LAN_VSTORE_GUIDE_COUPONS_TAB',     'Coupons');
define('LAN_VSTORE_GUIDE_COUPONS_TITLE',   'Discount coupons');
define('LAN_VSTORE_GUIDE_COUPONS_INTRO',   'Coupons are entered by customers in the cart.');
define('LAN_VSTORE_GUIDE_COUPONS_TYPES_H', 'Discount types');
define('LAN_VSTORE_GUIDE_COUPONS_TYPES_T', 'A percentage coupon discounts a share of each eligible item. A fixed coupon never discounts more than the price of the item.');
define('LAN_VSTORE_GUIDE_COUPONS_RULES_H', 'Restrictions');
define('LAN_VSTORE_GUIDE_COUPONS_RULES_T', 'Set the start and end dates and include or exclude categories in the Restrictions tab. Excluded categories always win over included ones.');
define('LAN_VSTORE_GUIDE_COUPONS_LIMITS_H','Limits');
define('LAN_VSTORE_GUIDE_COUPONS_LIMITS_T','Limit how many times a coupon can be used in total and per user in the Limits tab. Use <code>-1</code> for no limit.');
define('LAN_VSTORE_GUIDE_COUPONS_NOTE',    'Coupons apply only to the products to buy. Products to quote have no price and are never discounted.');

// TAX
define('LAN_VSTORE_GUIDE_TAX_TAB',         'Tax and VAT');
define('LAN_VSTORE_GUIDE_TAX_TITLE',       'Tax calculation');
define('LAN_VSTORE_GUIDE_TAX_INTRO',       'Tax is calculated at checkout from the billing country entered by the customer.');
define('LAN_VSTORE_GUIDE_TAX_SETUP_H',     'Setup');
define('LAN_VSTORE_GUIDE_TAX_SETUP_T',     'In Preferences, open the Tax tab, enable tax calculation and select your business country. Enable the online VAT ID check if you sell to EU businesses.');
define('LAN_VSTORE_GUIDE_TAX_CLASSES_H',   'Tax classes');
define('LAN_VSTORE_GUIDE_TAX_CLASSES_T',   'Enter tax values as decimals, for example <code>0.19</code> for 19%. Assign a tax class to each product in the Advanced tab.');
define('LAN_VSTORE_GUIDE_TAX_SCEN_H',      'What the customer pays');
define('LAN_VSTORE_GUIDE_TAX_COL_WHO',     'Customer');
define('LAN_VSTORE_GUIDE_TAX_COL_RATE',    'Tax applied');
define('LAN_VSTORE_GUIDE_TAX_SC1_W',       'In your country');
define('LAN_VSTORE_GUIDE_TAX_SC1_R',       'Your local rate.');
define('LAN_VSTORE_GUIDE_TAX_SC2_W',       'Consumer in another EU country');
define('LAN_VSTORE_GUIDE_TAX_SC2_R',       'The rate of the destination country.');
define('LAN_VSTORE_GUIDE_TAX_SC3_W',       'EU business with a valid VAT ID');
define('LAN_VSTORE_GUIDE_TAX_SC3_R',       '0% (reverse charge).');
define('LAN_VSTORE_GUIDE_TAX_SC4_W',       'Outside the EU');
define('LAN_VSTORE_GUIDE_TAX_SC4_R',       '0% (export).');
define('LAN_VSTORE_GUIDE_TAX_NOTE',        'Prices are entered without tax. Quote requests show no tax at all; the tax is calculated later in your billing application.');

// SHIPPING
define('LAN_VSTORE_GUIDE_SHIP_TAB',        'Shipping');
define('LAN_VSTORE_GUIDE_SHIP_TITLE',      'Shipping costs');
define('LAN_VSTORE_GUIDE_SHIP_INTRO',      'Enable shipping in the Shipping tab of Preferences, then choose a calculation method.');
define('LAN_VSTORE_GUIDE_SHIP_M1_T',       'Sum of all items');
define('LAN_VSTORE_GUIDE_SHIP_M1_D',       'Adds the shipping cost of every item in the cart.');
define('LAN_VSTORE_GUIDE_SHIP_M2_T',       'Sum of unique items');
define('LAN_VSTORE_GUIDE_SHIP_M2_D',       'Adds the shipping cost of each different product only once.');
define('LAN_VSTORE_GUIDE_SHIP_M3_T',       'Tiered');
define('LAN_VSTORE_GUIDE_SHIP_M3_D',       'Costs depend on thresholds of the order price or weight. Start with the lowest threshold and make the last one higher than your largest order.');
define('LAN_VSTORE_GUIDE_SHIP_NOTE',       'Shipping is added only to the products to buy. A quote request shows no shipping cost.');

// GATEWAYS
define('LAN_VSTORE_GUIDE_GATE_TAB',        'Payments');
define('LAN_VSTORE_GUIDE_GATE_TITLE',      'Payment gateways');
define('LAN_VSTORE_GUIDE_GATE_INTRO',      'Each gateway has its own tab with its credentials and an activation switch.');
define('LAN_VSTORE_GUIDE_GATE_G1_T',       'PayPal');
define('LAN_VSTORE_GUIDE_GATE_G1_D',       'Standard and REST. Requires API credentials from your PayPal account.');
define('LAN_VSTORE_GUIDE_GATE_G2_T',       'Mollie');
define('LAN_VSTORE_GUIDE_GATE_G2_D',       'Requires an API key and lets you choose the payment methods offered at checkout.');
define('LAN_VSTORE_GUIDE_GATE_G3_T',       'Bank transfer');
define('LAN_VSTORE_GUIDE_GATE_G3_D',       'The order stays pending until you mark it as paid.');
define('LAN_VSTORE_GUIDE_GATE_NOTE',       'Always test a gateway in sandbox mode before going live.');

// ORDERS
define('LAN_VSTORE_GUIDE_ORDERS_TAB',      'Orders');
define('LAN_VSTORE_GUIDE_ORDERS_TITLE',    'Orders, emails and invoices');
define('LAN_VSTORE_GUIDE_ORDERS_INTRO',    'Everything related to a sale is managed from the Sales menu entry.');
define('LAN_VSTORE_GUIDE_ORDERS_ST_H',     'Order status');
define('LAN_VSTORE_GUIDE_ORDERS_ST_T',     'Changing the status updates the inventory and sends the matching e-mail. Quote requests follow their own flow.');
define('LAN_VSTORE_GUIDE_ORDERS_SN',       'The order was placed and is waiting for the payment.');
define('LAN_VSTORE_GUIDE_ORDERS_SP',       'The payment arrived and the order is being prepared.');
define('LAN_VSTORE_GUIDE_ORDERS_SH',       'The order is paused, for example waiting for stock.');
define('LAN_VSTORE_GUIDE_ORDERS_SC',       'The order was fulfilled and the completed e-mail is sent to the customer.');
define('LAN_VSTORE_GUIDE_ORDERS_SX',       'The order was cancelled and the cancellation e-mail is sent.');
define('LAN_VSTORE_GUIDE_ORDERS_SR',       'The payment was returned to the customer.');
define('LAN_VSTORE_GUIDE_ORDERS_SQ',       'A quote request was received and waits for an answer.');
define('LAN_VSTORE_GUIDE_ORDERS_SM',       'The request was handed over to the external billing application.');
define('LAN_VSTORE_GUIDE_ORDERS_SA',       'The customer accepted the quote.');
define('LAN_VSTORE_GUIDE_ORDERS_SF',       'The quote was invoiced in the external application.');
define('LAN_VSTORE_GUIDE_ORDERS_EM_H',     'Email templates');
define('LAN_VSTORE_GUIDE_ORDERS_EM_T',     'There is one template for each status change and one for quote requests. Use the reset button to restore the original text.');
define('LAN_VSTORE_GUIDE_ORDERS_INV_H',    'Invoices');
define('LAN_VSTORE_GUIDE_ORDERS_INV_T',    'PDF invoices are created by Vstore itself (TCPDF). Define the number prefix, the next number and the footer in Invoice Settings and check the result in the preview tab.');
define('LAN_VSTORE_GUIDE_ORDERS_STAT_H',   'Statistics');
define('LAN_VSTORE_GUIDE_ORDERS_STAT_T',   'The first page of the admin area shows the sales boxes and the number of new quote requests.');
define('LAN_VSTORE_GUIDE_ORDERS_DASH_H',   'What the customer sees');
define('LAN_VSTORE_GUIDE_ORDERS_DASH_T',   'In the customer area the buyer finds a summary, a chart of the last six months, the order list with status badges, the order details, the downloads and the open quote requests.');

// APPEARANCE
define('LAN_VSTORE_GUIDE_LOOK_TAB',        'Appearance');
define('LAN_VSTORE_GUIDE_LOOK_TITLE',      'Adapting the store to your theme');
define('LAN_VSTORE_GUIDE_LOOK_INTRO',      'The store is designed for Bootstrap 5 themes and takes its colours from the active theme. You can fine tune it without touching the plugin.');
define('LAN_VSTORE_GUIDE_LOOK_CSS_H',      'Colours and shapes');
define('LAN_VSTORE_GUIDE_LOOK_CSS_T',      'The stylesheet <code>vstore.css</code> reads the Bootstrap colours and exposes a few variables. Override them in the CSS of your theme:');
define('LAN_VSTORE_GUIDE_LOOK_TPL_H',      'Templates');
define('LAN_VSTORE_GUIDE_LOOK_TPL_T',      'Every part of the store can be replaced from your theme. Copy only the part you want to change to this location:');
define('LAN_VSTORE_GUIDE_LOOK_MENU_H',     'Menus');
define('LAN_VSTORE_GUIDE_LOOK_MENU1',      '<strong>Vstore categories</strong>: the root categories as tiles with picture and product count.');
define('LAN_VSTORE_GUIDE_LOOK_MENU2',      '<strong>Vstore products</strong>: the latest products of one category or of all categories.');
define('LAN_VSTORE_GUIDE_LOOK_MENU3',      '<strong>Cart drop-down</strong>: shown in the navigation, with a variant for quote requests.');
define('LAN_VSTORE_GUIDE_LOOK_NOTE',       'Activate the menus in the Menu manager and assign them to the areas of your layout.');

// TROUBLESHOOTING
define('LAN_VSTORE_GUIDE_TROUBLE_TAB',     'Troubleshooting');
define('LAN_VSTORE_GUIDE_TROUBLE_TITLE',   'Common problems');
define('LAN_VSTORE_GUIDE_TROUBLE_INTRO',   'Check these points before asking for support.');
define('LAN_VSTORE_GUIDE_TROUBLE_Q1',      'A fatal error mentions a missing class or autoload file');
define('LAN_VSTORE_GUIDE_TROUBLE_A1',      'Run <code>composer install --no-dev --optimize-autoloader</code> inside the plugin folder. The <code>vendor</code> directory is not included in the repository.');
define('LAN_VSTORE_GUIDE_TROUBLE_Q2',      'Invoices are not created as PDF');
define('LAN_VSTORE_GUIDE_TROUBLE_A2',      'Run <code>composer install</code> in <code>e107_plugins/vstore</code> and enable PDF invoices in Invoice Settings.');
define('LAN_VSTORE_GUIDE_TROUBLE_Q3',      'The wrong tax rate is applied');
define('LAN_VSTORE_GUIDE_TROUBLE_A3',      'Check the business country, the tax class of the product and the country entered in the billing form.');
define('LAN_VSTORE_GUIDE_TROUBLE_Q4',      'I need more information about what is happening');
define('LAN_VSTORE_GUIDE_TROUBLE_A4',      'Uncomment the <code>e_DEBUG_VSTORE</code> line in <code>e_header.php</code> to enable the debug log, and remove it again in production.');
define('LAN_VSTORE_GUIDE_TROUBLE_Q5',      'Visitors do not see the shop or cart links');
define('LAN_VSTORE_GUIDE_TROUBLE_A5',      'The links are created for members by default. Change the visibility of the Vstore links in the site links of the admin area.');
define('LAN_VSTORE_GUIDE_TROUBLE_Q6',      'The store looks different from my theme or the styles are not applied');
define('LAN_VSTORE_GUIDE_TROUBLE_A6',      'The design needs a Bootstrap 5 theme. After an update clear the browser cache and the e107 cache so the new <code>vstore.css</code> and <code>vstore.js</code> are loaded.');

// CREDITS
define('LAN_VSTORE_GUIDE_CREDITS_TAB',              'Credits');
define('LAN_VSTORE_GUIDE_CREDITS_TITLE',            'Credits and acknowledgements');
define('LAN_VSTORE_GUIDE_CREDITS_INTRO',            'Vstore would not exist without the work of its original authors, the e107 community and the open source libraries listed below.');
define('LAN_VSTORE_GUIDE_CREDITS_AUTHOR_H',         'Authors');
define('LAN_VSTORE_GUIDE_CREDITS_AUTHOR_T',         'Vstore was created by CaMerOn and Achim Ennenbach (e107 Inc). This version is maintained and extended by Martin Costa (Kanonimpresor). See the <strong>About</strong> tab for contact details and support links.');
define('LAN_VSTORE_GUIDE_CREDITS_TECH_H',           'Technologies used');
define('LAN_VSTORE_GUIDE_CREDITS_TECH_E107',        'Admin UI framework, plugin system, parser, shortcodes and templates.');
define('LAN_VSTORE_GUIDE_CREDITS_TECH_OMNIPAY',     'Payment abstraction library behind the PayPal and Mollie gateways.');
define('LAN_VSTORE_GUIDE_CREDITS_TECH_TCPDF',       'Creates the PDF invoices without external services.');
define('LAN_VSTORE_GUIDE_CREDITS_TECH_VAT',         'Checks EU VAT numbers and provides the VAT rates per country.');
define('LAN_VSTORE_GUIDE_CREDITS_TECH_BS',          'The store uses Bootstrap 5; this admin area uses the Bootstrap 3 of the e107 admin theme.');
define('LAN_VSTORE_GUIDE_CREDITS_TECH_FA',          'Icon set used across the admin area and the store.');
define('LAN_VSTORE_GUIDE_CREDITS_THANKS_H',         'Special thanks');
define('LAN_VSTORE_GUIDE_CREDITS_THANKS_ORIG',      'CaMerOn, Achim Ennenbach and the e107 Inc team, for the original Vstore plugin this version is based on.');
define('LAN_VSTORE_GUIDE_CREDITS_THANKS_E107',      'The e107 community, for testing, ideas and feedback.');
define('LAN_VSTORE_GUIDE_CREDITS_THANKS_TRANS',     'Everyone who helps to keep the English, Spanish and Portuguese translations up to date.');
define('LAN_VSTORE_GUIDE_CREDITS_LICENSE',          'Vstore is free software released under the GNU General Public License v3.0. You may use, study, share and improve it under the terms of that licence.');
define('LAN_VSTORE_GUIDE_CREDITS_INVOLVED',         'Found a bug or have an idea? Open an issue in the project repository from the Support button of the About tab.');

// APPEARANCE: showcase menu
define('LAN_VSTORE_GUIDE_LOOK_MENU4',               '<strong>Vstore showcase</strong>: product cards like the product pages, for any menu area, with optional category pills.');
define('LAN_VSTORE_GUIDE_LOOK_SHOW_H',              'Showcase menu options');
define('LAN_VSTORE_GUIDE_LOOK_SHOW_T',              'Add <em>vstore_showcase_menu</em> to a menu area in the Menu Manager and press its configure button.');
define('LAN_VSTORE_GUIDE_LOOK_SHOW_O1_N',           'Category');
define('LAN_VSTORE_GUIDE_LOOK_SHOW_O1_D',           '<em>All categories</em> shows products of any category (random by default); pick a category to show only its products.');
define('LAN_VSTORE_GUIDE_LOOK_SHOW_O2_N',           'Number of products');
define('LAN_VSTORE_GUIDE_LOOK_SHOW_O2_D',           'From 1 to 24. With pills, every pill shows this many products.');
define('LAN_VSTORE_GUIDE_LOOK_SHOW_O3_N',           'Products per row');
define('LAN_VSTORE_GUIDE_LOOK_SHOW_O3_D',           'One to four columns, or automatic. Choose what fits the menu area of your theme: the grid uses fewer columns by itself when the area is narrow.');
define('LAN_VSTORE_GUIDE_LOOK_SHOW_O4_N',           'Order');
define('LAN_VSTORE_GUIDE_LOOK_SHOW_O4_D',           'Random, newest, name, price or the manual order of the catalog.');
define('LAN_VSTORE_GUIDE_LOOK_SHOW_O5_N',           'Category pills');
define('LAN_VSTORE_GUIDE_LOOK_SHOW_O5_D',           'Pills above the products that filter them in place, pills that open the category page, or none.');
