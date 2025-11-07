# Vstore E-Commerce Plugin - AI Coding Instructions

## Overview
Vstore is an e107 v2.x CMS plugin providing shopping cart functionality with multiple payment gateways (PayPal, Mollie, Stripe, Braintree). Built on the e107 plugin architecture with PHP 7.4+ and Bootstrap templates.

## Core Architecture

### E107 Plugin Structure
- **Entry point**: `vstore.php` - initializes singleton via `e107::getSingleton('vstore', e_PLUGIN.'vstore/vstore.class.php')`
- **Main class**: `vstore.class.php` - handles cart operations, checkout flow, payment processing
- **Admin dispatcher**: `admin_config.php` - routes to mode-specific controllers in `inc/admin_config_*.php`
- **Database schema**: `vstore_sql.php` - defines 7 tables (cart, orders, items, categories, customers, coupons, item_vars)

### Key E107 Patterns
```php
// Singleton pattern for plugin class
$vstore = e107::getSingleton('vstore', e_PLUGIN.'vstore/vstore.class.php');

// Database access (multiple instances for nested queries)
$sql = e107::getDb();      // Primary DB
$sql2 = e107::getDb('sql2'); // Secondary instance

// Preferences/settings
$prefs = e107::pref('vstore');

// Template rendering
$template = e107::getTemplate('vstore', 'vstore', 'customer');
$text = e107::getParser()->parseTemplate($template['main'], true, $this->sc);

// Shortcode parser (for templates)
$this->sc = e107::getScParser()->getScObject('vstore_shortcodes', 'vstore', false);

// Session management (namespaced)
e107::getSession('vstore')->set('_data', $_data);
$data = e107::getSession('vstore')->get('_data');

// URL generation with SEF (Search Engine Friendly) support
$url = e107::url('vstore', 'category', $categoryData);
```

### Admin UI Pattern
All admin screens extend `e_admin_ui` and `e_admin_form_ui`:
- **Controller class**: `vstore_*_ui extends e_admin_ui` - defines fields, list config, actions
- **Form class**: `vstore_*_form_ui extends e_admin_form_ui` - custom field rendering, validation
- Located in `inc/admin_config_*.php` files

### Template System
Templates in `templates/` use associative arrays with `start`, `item`, `end` keys:
```php
$VSTORE_TEMPLATE['list']['start'] = '<div class="row">';
$VSTORE_TEMPLATE['list']['item'] = '{ITEM_NAME} {ITEM_PRICE}...';
$VSTORE_TEMPLATE['list']['end'] = '</div>';
```
Shortcodes like `{ITEM_NAME}`, `{ITEM_PRICE}` are parsed via `vstore_shortcodes` class in `shortcodes/batch/`.

## Critical Developer Knowledge

### Payment Gateway Integration
Uses Omnipay 3.x (composer dependencies in `vendor/`):
1. Gateways configured via `loadGateway()` method in `vstore.class.php`
2. Flow: `processGateway('init')` → redirect → `processGateway('return')`
3. Transaction data stored in session at `$_SESSION['vstore']['_data']`
4. Mollie payments prefixed with `mollie_` (e.g., `mollie_ideal`)

**AJAX request handling**: Check `e_AJAX_REQUEST` first in `process_ajax()` before normal flow

### Database Naming Conventions
- Tables: `vstore_*` (e.g., `vstore_items`, `vstore_orders`)
- Fields: `{table_prefix}_{field}` (e.g., `item_id`, `order_status`)
- JSON fields use `e107::serialize($data, 'json')` / `e107::unserialize($data)`

### Inventory Management
Item variations (`item_vars`) stored as serialized arrays:
- Compulsory vs optional variations affect inventory checks
- Track types in static `$itemVarsTypes` array
- Methods: `getItemVarProperties()`, `getItemVars()`, `checkInventory()`

### Language Files
Multi-language support via `languages/{Language}/{Language}_*.php`:
- `English_admin.php` - Admin area constants
- `English_front.php` - Frontend constants  
- `English_global.php` - Shared constants
- Use constants like `LAN_VSTORE_001` in code
- Load with `e107::lan('vstore', true, true);`

### SEF URLs (`e_url.php`)
Defines regex patterns for clean URLs:
```php
'product' => [
    'regex' => '^{alias}/([^\/]*)/([\d]*)/(.*)',
    'redirect' => '{e_PLUGIN}vstore/vstore.php?item=$2',
    'sef' => '{alias}/{cat_sef}/{item_id}/{item_sef}'
]
```

### Order Status Flow
- **N**ew → **P**rocessing → **C**ompleted
- Alternative paths: **H**old, **X**cancelled, **R**efunded
- Update via `vstore_order::setOrderStatus($status)` (triggers emails)

## Development Workflows

### Testing Payment Gateways
1. Set `define('e_DEBUG_VSTORE', true);` in `e_header.php`
2. Check session data logged to debug: `e107::getDebug()->log(e107::getSession('vstore')->getData())`
3. Gateway test credentials stored in `pref['gateway_{name}']`

### Adding New Admin Section
1. Add mode to `$modes` array in `admin_config.php`
2. Create `inc/admin_config_{name}.php` with two classes
3. Add menu entry to `$adminMenu` with icon (FontAwesome)
4. Update `$adminMenuAliases` for edit/view actions

### Modifying Templates
1. Edit `templates/vstore_template.php` arrays
2. Add shortcodes to `shortcodes/batch/vstore_shortcodes.php`
3. Test with `{SETIMAGE: w=380&h=200&crop=1}` for responsive images

### Database Schema Changes
1. Update `vstore_sql.php` with new fields
2. Add migration logic in `vstore_setup.php::upgrade_required()`
3. Version check via `e107::getDb()->field('table', 'field_name')`

## Integration Points

### Dependency: PDF Plugin
Required for invoice generation (not included):
- Repo: https://github.com/e107inc/pdf
- Used in `vstore_pdf.class.php` for `invoiceToPdf()`
- Invoices stored in e107 Media datastore

### External Packages (Composer)
- `league/omnipay`: Payment gateway abstraction
- `omnipay/*`: Gateway-specific drivers (paypal, mollie, stripe, braintree)
- `ibericode/vat`: EU VAT validation
- Run `composer install` in plugin root (vendors committed to repo)

### E107 CMS Hooks
- `e_header.php`: CSS/JS injection per page
- `e_shortcode.php`: Site-wide shortcode batch class
- `e_sitelink.php`: Menu integration (cart icon)
- `e_dashboard.php`: User dashboard widgets
- `e_search.php`: Product search integration
- `e_cron.php`: Scheduled tasks (if implemented)

## Common Pitfalls

1. **Session namespace**: Always use `e107::getSession('vstore')` not `$_SESSION['vstore']` directly
2. **Multiple DB instances**: Use `getDb('sql2')` for nested queries inside `while($sql->fetch())`
3. **Breadcrumbs**: Call `e107::breadcrumb($array)` not manual rendering (sets magic `{---BREADCRUMB---}`)
4. **Template changes**: Clear e107 cache (Admin → System → Clear Cache)
5. **Permissions**: Check `getperms('P')` for admin access, `USER` constant for logged-in users
6. **AJAX responses**: Use `e107::getJshelper()->sendTextResponse()` and `exit;`
7. **Price calculations**: Always use `decimal(10,2)` in DB, cast to `(float)` in calculations

## Sistema de Cupones (Coupons)

### Estructura de Datos
```php
// Tabla: vstore_coupons
coupon_id          # ID único
coupon_active      # bool: activo/inactivo
coupon_code        # string: código único (MAYÚSCULAS)
coupon_type        # '%' = porcentaje, 'F' = fijo
coupon_amount      # decimal: valor del descuento
coupon_start       # timestamp: fecha de inicio
coupon_end         # timestamp: fecha de expiración
coupon_items       # CSV: IDs de productos incluidos
coupon_items_ex    # CSV: IDs de productos excluidos
coupon_cats        # CSV: IDs de categorías incluidas
coupon_cats_ex     # CSV: IDs de categorías excluidas
coupon_limit_coupon # int: límite global de uso (-1 = ilimitado)
coupon_limit_user  # int: límite por usuario (-1 = ilimitado)
coupon_limit_item  # int: límite de items aplicables (-1 = todos)
```

### Flujo de Validación
```php
// Ubicación: vstore.class.php:3136-3950
1. Verificar coupon_active === 1
2. Validar fecha: time() >= coupon_start && time() <= coupon_end
3. Verificar límite global: COUNT(orders) < coupon_limit_coupon
4. Verificar límite por usuario: COUNT(orders WHERE user_id=X) < coupon_limit_user
5. Verificar exclusiones de categorías: item_cat NOT IN coupon_cats_ex
6. Verificar inclusiones de categorías: item_cat IN coupon_cats (si no vacío)
7. Verificar exclusiones de items: item_id NOT IN coupon_items_ex
8. Verificar inclusiones de items: item_id IN coupon_items (si no vacío)
9. Calcular descuento: 
   - Tipo '%': (item_price * qty) * (coupon_amount / 100)
   - Tipo 'F': coupon_amount * qty
10. Aplicar descuento (siempre negativo: <= 0)
```

### Problemas Conocidos (⚠️ Requieren corrección)
- **Race condition** en verificación de límites (no usa transacciones)
- **Validación incompleta** en algunos flujos de checkout
- **Interacción con IVA** no está claramente definida (antes/después impuestos)
- **Cupones fijos** pueden resultar en descuento mayor al precio del item

### Métodos Clave
```php
calcCouponAmount($coupon, $item)  // Línea 3836: Calcula monto de descuento
getCouponUsageCount($code)        // Cuenta usos globales
getCouponUsageCountByUser($code, $user_id) // Cuenta usos por usuario
```

## Sistema de IVA/Impuestos (Tax)

### Clases de Impuestos (Tax Classes)
```php
// Ubicación: inc/admin_config_pref.php:26-30
'none'         => 0%    // Sin impuestos
'reduced'      => X%    // Tasa reducida (ej: 10% España)
'reduced1'     => X%    // Tasa reducida 1 (Austria, Luxemburgo)
'reduced2'     => X%    // Tasa reducida 2 (Austria, Luxemburgo)
'super_reduced'=> X%    // Tasa súper reducida (ej: 4% España)
'standard'     => X%    // Tasa estándar (ej: 21% España)
'parking'      => X%    // Tasa parking (solo algunos países UE)
```

### Flujo de Cálculo de IVA
```php
// Ubicación: vstore.class.php:4044-4238
getTaxRate($tax_class, $customer_country, $pref)

1. Si tax_calculate === false → return 0.0
2. Si tax_class === 'none' → return 0.0
3. Si customer_country === business_country:
   → Aplicar tasa local configurada en tax_classes
4. Si business en UE && customer fuera UE:
   → return 0.0 (sin IVA)
5. Si business en UE && customer en UE:
   a. Mapear tax_class al país destino (getTaxClass)
   b. Consultar librería DvK\Vat\Rates
   c. return tasa / 100.0
6. Si business fuera UE:
   → return 0.0
```

### Validación de VAT ID (Intracomunitario)
```php
// Usa librería ibericode/vat
validateVATID($vat_id, $country_code)

1. Si tax_check_vat === false → aceptar sin validar
2. Consultar VIES (servicio UE): validator->validate($vat_number)
3. Si válido → obtener nombre empresa y dirección
4. Si error servicio VIES → aceptar VAT ID (failsafe)
```

### Escenarios de Cálculo
| Negocio | Cliente | VAT ID | IVA Aplicado |
|---------|---------|--------|--------------|
| ES | ES | - | 21% (tasa local) |
| ES | FR | No | 20% (tasa FR) |
| ES | DE | Sí (válido) | 0% (reverse charge) |
| ES | US | - | 0% (fuera UE) |

### Problemas Conocidos (⚠️ Requieren corrección)
- **Geolocalización por IP** no confiable (usar país del formulario)
- **Variable estática** `$customerCountry` causa bugs en múltiples llamadas
- **Mapeo de clases** hardcoded, debería ser configurable
- **Shipping tax** asume mismo % que productos
- **División por cero** posible si subtotal neto es 0

### Métodos Clave
```php
getTaxRate($tax_class, $country)     // Línea 4044: Obtener tasa de impuesto
getTaxClass($tax_class, $country)    // Línea 4160: Mapear clase al país
calcTaxAmount($price, $tax_rate)     // Calcular monto de impuesto
calcNetPrice($gross, $tax_rate)      // Calcular precio neto (sin IVA)
validateVATID($vat_id, $country)     // Validar VAT ID contra VIES
```

## Sistema de Templates y Shortcodes

### Estructura de Templates
```php
// Ubicación: templates/vstore_template.php
$VSTORE_TEMPLATE = array(
    'section' => array(
        'start' => '<div class="wrapper">',
        'item'  => '<div>{SHORTCODE}</div>',
        'end'   => '</div>'
    )
);
```

### Secciones Disponibles
- `cat` - Listado de categorías
- `list` - Listado de productos
- `menu` - Menú de carrito
- `cart` - Vista del carrito
- `checkout` - Proceso de checkout
- `customer` - Formulario de datos de facturación
- `shipping` - Formulario de datos de envío
- `orderconfirm` - Confirmación de orden
- `invoice` - Factura

### Shortcodes Principales
```php
// Ubicación: shortcodes/batch/vstore_shortcodes.php

// Productos
{ITEM_NAME}           - Nombre del producto
{ITEM_PRICE}          - Precio formateado
{ITEM_DESCRIPTION}    - Descripción corta
{ITEM_PIC}            - Imagen principal
{ITEM_URL}            - URL del producto
{ITEM_ADDTOCART}      - Botón agregar al carrito
{ITEM_VARS}           - Selector de variaciones

// Categorías
{CAT_NAME}            - Nombre de categoría
{CAT_DESCRIPTION}     - Descripción
{CAT_URL}             - URL de categoría
{CAT_PIC}             - Imagen de categoría
{CAT_INFO}            - Información extendida

// Carrito
{CART_TOTAL}          - Total del carrito
{CART_SUBTOTAL}       - Subtotal (sin envío)
{CART_SHIPPING}       - Costo de envío
{CART_TAX}            - Total impuestos
{CART_COUPON}         - Descuento cupón
{CART_ITEMS}          - Listado de items
{CART_QTY}            - Cantidad total items
{CART_CONTINUESHOP}   - Botón continuar comprando

// Imágenes responsivas
{SETIMAGE: w=380&h=200&crop=1}  - Define dimensiones
```

### Crear Shortcode Personalizado
```php
// En shortcodes/batch/vstore_shortcodes.php
class vstore_shortcodes extends e_shortcode
{
    // Ejemplo: {ITEM_DISCOUNT_BADGE}
    function sc_item_discount_badge()
    {
        $item = $this->var['item'];
        
        // Lógica para calcular descuento
        $original_price = $item['item_price'];
        $current_price = $item['item_price_discounted'];
        
        if($original_price > $current_price) {
            $discount_pct = round((($original_price - $current_price) / $original_price) * 100);
            return '<span class="badge bg-danger">-' . $discount_pct . '%</span>';
        }
        
        return '';
    }
}
```

## Debugging y Testing

### Activar Debug Mode
```php
// En e107_config.php o e_header.php
define('e_DEBUG_VSTORE', true);

// Logs visibles en footer
e107::getDebug()->log(e107::getSession('vstore')->getData());
e107::getDebug()->log('Cupón aplicado: ' . $coupon_code, 'VSTORE_COUPON');
e107::getDebug()->log('IVA calculado: ' . $tax_rate, 'VSTORE_TAX');
```

### Logging Personalizado
```php
// En vstore.class.php
if(deftrue('e_DEBUG_VSTORE')) {
    e107::getDebug()->log([
        'customer_country' => $customer_country,
        'tax_rate' => $tax_rate,
        'business_country' => $pref['tax_business_country']
    ], 'VSTORE_TAX_CALC');
}
```

### Testing de Cupones
```php
// Casos de prueba manuales
1. Cupón con fecha futura → No debe aplicarse
2. Cupón expirado → Error "no disponible"
3. Límite de uso alcanzado → Error "límite excedido"
4. Producto en categoría excluida → Sin descuento
5. Descuento > precio item → Máximo = precio item
6. Cupón porcentual > 100% → Validar límite
```

### Testing de IVA
```php
// Casos de prueba manuales
1. Cliente mismo país → IVA local (ej: 21%)
2. Cliente UE sin VAT → IVA país destino
3. Cliente UE con VAT válido → 0% (reverse charge)
4. Cliente fuera UE → 0%
5. Cambiar país en formulario → Recalcular IVA
6. VAT ID inválido → Error de validación
```

### Herramientas de Testing
```php
// Simular datos de sesión
e107::getSession('vstore')->set('_data', [
    'customer_country' => 'DE',
    'vat_id' => 'DE123456789',
    'coupon_code' => 'TEST2025'
]);

// Limpiar sesión
e107::getSession('vstore')->clear('_data');

// Verificar estado del carrito
$cart = e107::getSession('vstore')->get('cart');
var_dump($cart);
```

## Panel de Usuario (Dashboard)

### Ubicación
`inc/vstore_dashboard.class.php`

### Funcionalidades
```php
// Áreas del dashboard
'dashboard'  => Resumen general
'orders'     => Historial de órdenes
'downloads'  => Productos digitales descargables
'addresses'  => Direcciones guardadas
'profile'    => Datos del cliente

// URLs SEF
/vstore/my/orders
/vstore/my/downloads  
/vstore/my/addresses
/vstore/my/orders/view/{order_id}
```

### Permisos
```php
// Solo usuarios autenticados
if(!USER) {
    e107::redirect(e_LOGIN);
}

// Verificar propiedad de orden
if($order['order_e107_user'] != USERID && !ADMIN) {
    e107::getMessage()->addError('No autorizado');
    return;
}
```

## File References
- Cart flow: `vstore.class.php::cartView()` → `process()` → `updateCart()`
- Checkout: `vstore.class.php::checkoutView()` → `renderCustomerForm()` → `renderShippingForm()` → `confirmOrderView()`
- Coupon validation: `vstore.class.php::calcCouponAmount()` (línea 3836)
- Tax calculation: `vstore.class.php::getTaxRate()` (línea 4044)
- Orders admin: `inc/admin_config_orders.php` (view/edit orders)
- Product admin: `inc/admin_config_items.php` (CRUD operations)
- Coupon admin: `inc/admin_config_coupons.php` (coupon management)
- Gateway config: `inc/admin_config_gateways.php` (API credentials)
- Invoice generation: `inc/vstore_pdf.class.php` + `templates/vstore_invoice_template.php`
- User dashboard: `inc/vstore_dashboard.class.php` (customer panel)
- Shortcodes: `shortcodes/batch/vstore_shortcodes.php` (template parsers)
- Templates: `templates/vstore_template.php` (HTML structures)
