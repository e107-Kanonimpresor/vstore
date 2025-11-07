# Plan de Trabajo - Plugin Vstore E107

**Fecha de creación:** 6 de noviembre de 2025  
**Versión actual:** 1.0  
**Estado:** En progreso

## Tabla de Contenidos

1. [Análisis de Arquitectura Actual](#análisis-de-arquitectura-actual)
2. [Problemas Identificados](#problemas-identificados)
3. [Plan de Actualización](#plan-de-actualización)
4. [Implementación MVC](#implementación-mvc)
5. [Sistema de Cupones](#sistema-de-cupones)
6. [Sistema de IVA/Impuestos](#sistema-de-ivaimpuestos)
7. [Traducciones](#traducciones)
8. [Manual de Usuario](#manual-de-usuario)
9. [Timeline](#timeline)

---

## Análisis de Arquitectura Actual

### Estructura de Archivos Principal

```
vstore/
├── vstore.php                 # Punto de entrada principal (frontend)
├── vstore.class.php           # Clase principal con lógica de negocio (5216 líneas - REFACTORIZAR)
├── admin_config.php           # Dispatcher del área de administración
├── vstore_setup.php           # Instalación y actualizaciones
├── vstore_sql.php             # Esquema de base de datos
├── inc/                       # Controladores del admin (patrón e_admin_ui)
│   ├── admin_config_*.php     # 12 archivos de configuración admin
│   ├── vstore_order.class.php # Gestión de órdenes
│   ├── vstore_pdf.class.php   # Generación de PDF (requiere plugin externo)
│   └── vstore_dashboard.class.php # Dashboard de usuario
├── templates/                 # Plantillas HTML con shortcodes
├── shortcodes/batch/          # Parsers de shortcodes
├── languages/                 # Archivos de traducción
├── vendor/                    # Dependencias Composer
└── js/                        # JavaScript y jQuery plugins
```

### Componentes Clave

#### 1. **vstore.class.php** - Monolito Principal (5216 líneas)
**Responsabilidades actuales:**
- Gestión del carrito de compras
- Proceso de checkout (3 pasos)
- Integración con pasarelas de pago (Omnipay)
- Cálculo de impuestos (IVA/VAT)
- Aplicación de cupones/descuentos
- Gestión de inventario
- Renderizado de vistas
- AJAX handlers
- Cálculo de envíos

**Problemas:**
- Violación del principio Single Responsibility
- Métodos privados dificultan testing
- Lógica de negocio mezclada con presentación
- Difícil mantenimiento y extensión

#### 2. **Sistema de Base de Datos** (7 tablas)
```sql
vstore_cart         # Carritos de compras (activos y abandonados)
vstore_orders       # Órdenes completadas
vstore_items        # Productos del catálogo
vstore_cat          # Categorías de productos
vstore_items_vars   # Variaciones de productos (tallas, colores, etc.)
vstore_coupons      # Cupones de descuento
vstore_customer     # Datos de clientes
```

#### 3. **Flujo de Checkout**
```
1. Agregar al carrito → AJAX: process_ajax()
2. Ver carrito → cartView()
3. Checkout (invitado o login) → checkoutView()
   ├── 3a. Datos de facturación → renderCustomerForm()
   ├── 3b. Datos de envío (opcional) → renderShippingForm()
   └── 3c. Confirmar orden → confirmOrderView()
4. Procesar pago → processGateway('init')
5. Retorno de pasarela → processGateway('return')
6. Guardar transacción → saveTransaction()
7. Enviar emails → vstore_order::emailCustomer()
```

#### 4. **Pasarelas de Pago (Omnipay 3.x)**
**Activas:**
- PayPal (Standard & REST)
- Mollie (14 métodos: iDEAL, Bancontact, Credit Card, etc.)
- Stripe
- Braintree
- Bank Transfer (manual)

**Flujo:**
```php
loadGateway($type) → $gateway->purchase($data) → redirect
→ return URL → $gateway->completePurchase() → saveTransaction()
```

---

## Problemas Identificados

### 1. **Sistema de Cupones - Bugs Críticos**

**Ubicación:** `vstore.class.php:3136-3950`

#### Problema 1: Validación de Cupones Incompleta
```php
// Línea 3845: Solo verifica coupon_active
if(empty($coupon['coupon_active'])) {
    // Error, pero continúa procesando en algunos casos
}
```

**Issues:**
- ❌ No valida fechas de inicio/fin correctamente en todos los flujos
- ❌ Límites de uso no se verifican antes de aplicar descuento
- ❌ No hay transacciones al verificar límites (race condition)
- ❌ Cupones pueden aplicarse a productos excluidos

#### Problema 2: Cálculo de Descuento Erróneo
```php
// Línea 3255: calcCouponAmount()
$coupon_amount = vstore::calcCouponAmount($coupon, $row);
$checkoutData['coupon']['amount'] += $coupon_amount;
```

**Issues:**
- ❌ Descuentos porcentuales no respetan límite de items (`coupon_limit_item`)
- ❌ No verifica exclusiones de categorías (`coupon_cats_ex`)
- ❌ Falta validación de inclusiones (`coupon_items`, `coupon_cats`)

#### Problema 3: Interacción con Impuestos
```php
// Línea 3286: Se resta impuesto del cupón
$taxTotal[$rateKey] += vstore::calcTaxAmount($coupon_amount, $row['tax_rate']);
```

**Issues:**
- ❌ El descuento se aplica ANTES de impuestos (debería ser configurable)
- ❌ No maneja correctamente cupones fijos vs porcentuales con IVA

### 2. **Sistema de IVA/Impuestos - Bugs Críticos**

**Ubicación:** `vstore.class.php:4044-4238`

#### Problema 1: Detección de País del Cliente
```php
// Línea 4070: Detección por IP
$customer_ip = e107::getIPHandler()->getIP();
$customerCountry = $countries->ip($customer_ip);
```

**Issues:**
- ❌ Geolocalización por IP no es confiable (VPN, proxies)
- ❌ No usa país de facturación del formulario
- ❌ Variable estática `$customerCountry` causa problemas en múltiples llamadas

#### Problema 2: Mapeo de Clases de Impuestos
```php
// Línea 4160: getTaxClass()
$map_classes = array(
    'reduced' => array('reduced2', 'reduced1', 'super_reduced'),
    // ...
);
```

**Issues:**
- ❌ Hardcoded, no permite configuración por admin
- ❌ No maneja clases personalizadas
- ❌ Falla silenciosamente si no encuentra clase equivalente

#### Problema 3: Cálculo de Envío con Impuestos
```php
// Línea 3310-3318: Shipping tax
foreach($netTotal as $tax_rate => $value) {
    $gross = $value * ($shippingTotal / $subTotalNet);
    $taxTotal['' . $tax_rate] += vstore::calcTaxAmount($gross, $tax_rate);
}
```

**Issues:**
- ❌ Asume que envío tiene mismo % de impuesto que productos
- ❌ No permite configurar tasa de impuesto separada para envío
- ❌ División por cero posible si `$subTotalNet` es 0

#### Problema 4: Empresas con VAT ID (B2B)
```php
// Línea 3147: Detección B2B
$isBusiness = !empty($cust['vat_id']);
```

**Issues:**
- ❌ No valida VAT ID contra VIES (solo si está activado)
- ❌ No aplica reverse charge para ventas intracomunitarias
- ❌ No distingue entre B2B doméstico e intracomunitario

### 3. **Dependencias Obsoletas**

```json
// composer.json
{
    "omnipay/paypal": "~3.0",      // Última: 3.0.2 (2020)
    "omnipay/mollie": "~5.0.2",    // Última: 5.6.0 (2023)
    "omnipay/stripe": "^3.1",      // Última: 3.2.0 (2021)
    "omnipay/braintree": "~4.0@dev" // Versión dev inestable
}
```

**Riesgos:**
- Vulnerabilidades de seguridad conocidas
- Incompatibilidad con nuevas APIs de pasarelas
- PHP 8.x compatibility issues

### 4. **Traducciones Incompletas**

```
languages/
├── English/      ✅ Completo
├── Spanish/      ⚠️  Solo estructura, sin traducciones
└── Portuguese/   ⚠️  Solo estructura, sin traducciones
```

**Archivos a traducir:**
- `Spanish_admin.php` (0% completado)
- `Spanish_front.php` (0% completado)
- `Spanish_global.php` (0% completado)
- `Portuguese_admin.php` (0% completado)
- `Portuguese_front.php` (0% completado)
- `Portuguese_global.php` (0% completado)

---

## Plan de Actualización

### Fase 1: Correcciones Críticas (Semana 1-2)

#### 1.1 Actualizar Dependencias Composer
```bash
# Actualizar a versiones seguras
composer require omnipay/paypal:^3.0.2
composer require omnipay/mollie:^5.6
composer require omnipay/stripe:^3.2
composer require omnipay/braintree:^5.0
composer update
```

#### 1.2 Corregir Sistema de Cupones

**Archivo:** `vstore.class.php:calcCouponAmount()`

```php
/**
 * Calcula el monto del descuento del cupón (CORREGIDO)
 * 
 * @param array $coupon Fila de la tabla vstore_coupons
 * @param array $item Item del carrito
 * @return float Monto del descuento (siempre <= 0)
 */
public static function calcCouponAmount($coupon, $item)
{
    if(empty($coupon) || empty($item)) {
        return 0.0;
    }

    // 1. Verificar que el cupón esté activo
    if(empty($coupon['coupon_active'])) {
        e107::getMessage()->addError('El cupón no está disponible', 'vstore');
        return 0.0;
    }

    // 2. Verificar fecha de inicio
    if(!empty($coupon['coupon_start']) && time() < $coupon['coupon_start']) {
        e107::getMessage()->addError('El cupón aún no está disponible', 'vstore');
        return 0.0;
    }

    // 3. Verificar fecha de expiración
    if(!empty($coupon['coupon_end']) && time() > $coupon['coupon_end']) {
        e107::getMessage()->addError('El cupón ha expirado', 'vstore');
        return 0.0;
    }

    // 4. Verificar límite global del cupón (CON TRANSACCIÓN)
    if($coupon['coupon_limit_coupon'] > -1) {
        $usage = self::getCouponUsageCount($coupon['coupon_code']);
        if($usage >= $coupon['coupon_limit_coupon']) {
            e107::getMessage()->addError(
                'El cupón ha alcanzado su límite de uso',
                'vstore'
            );
            return 0.0;
        }
    }

    // 5. Verificar límite por usuario
    if(USER && $coupon['coupon_limit_user'] > -1) {
        $usage = self::getCouponUsageCountByUser(
            $coupon['coupon_code'],
            USERID
        );
        if($usage >= $coupon['coupon_limit_user']) {
            e107::getMessage()->addError(
                'Has alcanzado el límite de uso para este cupón',
                'vstore'
            );
            return 0.0;
        }
    }

    // 6. Verificar categorías excluidas
    $coupon_cats_ex = array_filter(explode(',', $coupon['coupon_cats_ex']));
    if(!empty($coupon_cats_ex) && in_array($item['item_cat'], $coupon_cats_ex)) {
        // Item está en categoría excluida
        return 0.0;
    }

    // 7. Verificar categorías incluidas
    $coupon_cats = array_filter(explode(',', $coupon['coupon_cats']));
    if(!empty($coupon_cats) && !in_array($item['item_cat'], $coupon_cats)) {
        // Item no está en categorías permitidas
        return 0.0;
    }

    // 8. Verificar items excluidos
    $coupon_items_ex = array_filter(explode(',', $coupon['coupon_items_ex']));
    if(!empty($coupon_items_ex) && in_array($item['item_id'], $coupon_items_ex)) {
        // Item está excluido
        return 0.0;
    }

    // 9. Verificar items incluidos
    $coupon_items = array_filter(explode(',', $coupon['coupon_items']));
    if(!empty($coupon_items) && !in_array($item['item_id'], $coupon_items)) {
        // Item no está en items permitidos
        return 0.0;
    }

    // 10. Calcular descuento
    $item_total = $item['item_price'] * $item['cart_qty'];
    $discount = 0.0;

    if($coupon['coupon_type'] === '%') {
        // Descuento porcentual
        $discount = -1 * ($item_total * ($coupon['coupon_amount'] / 100));
    } elseif($coupon['coupon_type'] === 'F') {
        // Descuento fijo por item
        $discount = -1 * ($coupon['coupon_amount'] * $item['cart_qty']);
    }

    // Asegurar que el descuento no exceda el precio del item
    if(abs($discount) > $item_total) {
        $discount = -1 * $item_total;
    }

    return round($discount, 2);
}

/**
 * Obtiene el número de veces que se ha usado un cupón
 * 
 * @param string $coupon_code Código del cupón
 * @return int Número de usos
 */
private static function getCouponUsageCount($coupon_code)
{
    $result = e107::getDb()->retrieve(
        'vstore_orders',
        'COUNT(order_id) AS count_coupon',
        sprintf('order_pay_coupon_code="%s"', e107::getDb()->escape($coupon_code))
    );
    return intval($result['count_coupon']);
}

/**
 * Obtiene el número de veces que un usuario ha usado un cupón
 * 
 * @param string $coupon_code Código del cupón
 * @param int $user_id ID del usuario
 * @return int Número de usos
 */
private static function getCouponUsageCountByUser($coupon_code, $user_id)
{
    $result = e107::getDb()->retrieve(
        'vstore_orders',
        'COUNT(order_id) AS count_coupon',
        sprintf(
            'order_e107_user=%d AND order_pay_coupon_code="%s"',
            intval($user_id),
            e107::getDb()->escape($coupon_code)
        )
    );
    return intval($result['count_coupon']);
}
```

#### 1.3 Corregir Sistema de IVA

**Archivo:** `vstore.class.php:getTaxRate()`

```php
/**
 * Obtiene la tasa de impuesto según clase de impuesto y país del cliente (CORREGIDO)
 * 
 * @param string $tax_class Clase de impuesto: 'none', 'reduced', 'standard', etc.
 * @param string $customer_country Código ISO 3166-1 alpha-2 del país
 * @param array|null $pref Preferencias (opcional)
 * @return float Tasa de impuesto (ej: 0.19 para 19%)
 */
public static function getTaxRate($tax_class, $customer_country = null, $pref = null)
{
    if(empty($pref)) {
        $pref = e107::pref('vstore');
    }

    $result = 0.0;

    // Si el cálculo de impuestos está desactivado
    if(empty($pref['tax_calculate'])) {
        return $result;
    }

    // Si la clase de impuesto es 'none'
    if(varset($tax_class, 'standard') === 'none') {
        return $result;
    }

    $tax_class = strtolower($tax_class);
    $businessCountry = $pref['tax_business_country'];

    // CORRECCIÓN: Usar país del formulario en lugar de IP
    if(empty($customer_country)) {
        // Fallback: obtener de datos del cliente en sesión
        $custData = self::getCustomerData(true);
        $customer_country = varset($custData['country'], $businessCountry);
    }

    $countries = new DvK\Vat\Countries();

    // Cliente del mismo país que el negocio
    if($customer_country === $businessCountry) {
        $tax_classes = e107::unserialize($pref['tax_classes']);
        foreach($tax_classes as $tclass) {
            if($tclass['name'] === $tax_class) {
                $result = floatval($tclass['value']);
                break;
            }
        }
        return $result;
    }

    // Negocio en Europa
    if($countries->inEurope($businessCountry)) {
        // Cliente fuera de Europa: sin impuestos
        if(!$countries->inEurope($customer_country)) {
            return 0.0;
        }

        // Cliente en Europa: aplicar IVA del país destino
        $mapped_tax_class = self::getTaxClass($tax_class, $customer_country);
        if(empty($mapped_tax_class)) {
            e107::getMessage()->addWarning(
                'No se pudo determinar la clase de impuesto para este país',
                'vstore'
            );
            return 0.0;
        }

        try {
            $rates = new DvK\Vat\Rates\Rates();
            $result = $rates->country($customer_country, $mapped_tax_class);
            $result /= 100.0; // Convertir de porcentaje a decimal
        } catch(Exception $ex) {
            e107::getMessage()->addError(
                'Error al calcular impuestos: ' . $ex->getMessage(),
                'vstore'
            );
            trigger_error('Tax calculation error: ' . $ex->getMessage());
            return 0.0;
        }
    }
    // Cliente extranjero (fuera de EU): sin impuestos
    else {
        return 0.0;
    }

    return $result;
}

/**
 * Valida VAT ID contra base de datos VIES (UE)
 * 
 * @param string $vat_id Número de IVA
 * @param string $country_code Código de país
 * @return array ['valid' => bool, 'company_name' => string, 'address' => string]
 */
public static function validateVATID($vat_id, $country_code)
{
    $result = [
        'valid' => false,
        'company_name' => '',
        'address' => ''
    ];

    if(empty($vat_id) || empty($country_code)) {
        return $result;
    }

    // Verificar si está habilitada la validación de VAT
    $pref = e107::pref('vstore');
    if(empty($pref['tax_check_vat'])) {
        // Aceptar sin validar
        $result['valid'] = true;
        return $result;
    }

    try {
        $validator = new DvK\Vat\Validator();
        $vat_number = $country_code . $vat_id;
        
        if($validator->validate($vat_number)) {
            $details = $validator->getDetails();
            $result['valid'] = true;
            $result['company_name'] = $details->getName();
            $result['address'] = $details->getAddress();
        }
    } catch(Exception $ex) {
        e107::getMessage()->addWarning(
            'No se pudo validar el VAT ID: ' . $ex->getMessage(),
            'vstore'
        );
        // En caso de error del servicio VIES, aceptar el VAT ID
        $result['valid'] = true;
    }

    return $result;
}
```

### Fase 2: Implementación MVC (Semana 3-4)

#### Estructura Propuesta

```
vstore/
├── models/                    # Modelos de datos
│   ├── Cart.php              # Gestión del carrito
│   ├── Product.php           # Productos
│   ├── Order.php             # Órdenes
│   ├── Coupon.php            # Cupones
│   ├── Customer.php          # Clientes
│   └── Tax.php               # Cálculos de impuestos
│
├── controllers/              # Controladores
│   ├── CartController.php    # Lógica del carrito
│   ├── CheckoutController.php # Proceso de checkout
│   ├── ProductController.php # Catálogo de productos
│   ├── PaymentController.php # Pasarelas de pago
│   └── DashboardController.php # Panel de usuario
│
├── views/                    # Vistas (templates mejorados)
│   ├── cart/
│   ├── checkout/
│   ├── products/
│   └── dashboard/
│
├── services/                 # Servicios reutilizables
│   ├── PaymentService.php    # Integración Omnipay
│   ├── TaxService.php        # Cálculos de IVA
│   ├── CouponService.php     # Validación de cupones
│   ├── EmailService.php      # Envío de correos
│   └── InventoryService.php  # Gestión de inventario
│
└── helpers/                  # Utilidades
    ├── PriceHelper.php       # Formateo de precios
    ├── ValidationHelper.php  # Validaciones
    └── DateHelper.php        # Manejo de fechas
```

#### Ejemplo: Modelo Cart.php

```php
<?php
/**
 * Modelo de Carrito de Compras
 * 
 * Gestiona todas las operaciones relacionadas con el carrito
 */
class Cart
{
    /** @var int ID del carrito */
    private $cart_id;
    
    /** @var string ID de sesión */
    private $session_id;
    
    /** @var array Items del carrito */
    private $items = [];
    
    /** @var float Total del carrito */
    private $total = 0.0;
    
    /**
     * Constructor
     * 
     * @param string|null $session_id ID de sesión (null para crear nuevo)
     */
    public function __construct($session_id = null)
    {
        if($session_id) {
            $this->session_id = $session_id;
            $this->load();
        } else {
            $this->session_id = session_id();
        }
    }
    
    /**
     * Carga el carrito desde la base de datos
     * 
     * @return bool true si se cargó correctamente
     */
    private function load()
    {
        $sql = e107::getDb();
        
        if($sql->select('vstore_cart', '*', "cart_session='{$this->session_id}'")) {
            while($row = $sql->fetch()) {
                $this->items[] = [
                    'cart_id' => $row['cart_id'],
                    'item_id' => $row['cart_item'],
                    'qty' => $row['cart_qty'],
                    'vars' => e107::unserialize($row['cart_item_vars']),
                    'tax_class' => $row['cart_item_tax_class']
                ];
            }
            return true;
        }
        
        return false;
    }
    
    /**
     * Agrega un item al carrito
     * 
     * @param int $item_id ID del producto
     * @param int $qty Cantidad
     * @param array $vars Variaciones del producto
     * @return bool true si se agregó correctamente
     */
    public function addItem($item_id, $qty = 1, $vars = [])
    {
        // Validar que el producto exista y esté activo
        $product = Product::find($item_id);
        if(!$product || !$product->isActive()) {
            e107::getMessage()->addError('Producto no disponible', 'vstore');
            return false;
        }
        
        // Verificar inventario
        if(!$product->checkInventory($qty, $vars)) {
            e107::getMessage()->addError('Inventario insuficiente', 'vstore');
            return false;
        }
        
        // Verificar si el item ya existe en el carrito
        $existing_key = $this->findItem($item_id, $vars);
        
        if($existing_key !== false) {
            // Actualizar cantidad
            $this->items[$existing_key]['qty'] += $qty;
            $this->updateItemInDb($this->items[$existing_key]);
        } else {
            // Agregar nuevo item
            $item_data = [
                'item_id' => $item_id,
                'qty' => $qty,
                'vars' => $vars,
                'tax_class' => $product->getTaxClass()
            ];
            
            $cart_id = $this->insertItemInDb($item_data);
            $item_data['cart_id'] = $cart_id;
            $this->items[] = $item_data;
        }
        
        $this->calculateTotal();
        
        e107::getMessage()->addSuccess('Producto agregado al carrito', 'vstore');
        return true;
    }
    
    /**
     * Elimina un item del carrito
     * 
     * @param int $cart_id ID del item en el carrito
     * @return bool true si se eliminó correctamente
     */
    public function removeItem($cart_id)
    {
        $sql = e107::getDb();
        
        if($sql->delete('vstore_cart', "cart_id=" . intval($cart_id))) {
            // Remover del array local
            foreach($this->items as $key => $item) {
                if($item['cart_id'] == $cart_id) {
                    unset($this->items[$key]);
                    break;
                }
            }
            
            $this->calculateTotal();
            return true;
        }
        
        return false;
    }
    
    /**
     * Actualiza la cantidad de un item
     * 
     * @param int $cart_id ID del item en el carrito
     * @param int $new_qty Nueva cantidad
     * @return bool true si se actualizó correctamente
     */
    public function updateQuantity($cart_id, $new_qty)
    {
        if($new_qty <= 0) {
            return $this->removeItem($cart_id);
        }
        
        foreach($this->items as $key => $item) {
            if($item['cart_id'] == $cart_id) {
                // Verificar inventario
                $product = Product::find($item['item_id']);
                if(!$product->checkInventory($new_qty, $item['vars'])) {
                    e107::getMessage()->addError('Inventario insuficiente', 'vstore');
                    return false;
                }
                
                $this->items[$key]['qty'] = $new_qty;
                $this->updateItemInDb($this->items[$key]);
                $this->calculateTotal();
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Calcula el total del carrito
     * 
     * @return float Total del carrito
     */
    private function calculateTotal()
    {
        $this->total = 0.0;
        
        foreach($this->items as $item) {
            $product = Product::find($item['item_id']);
            $price = $product->getPrice($item['vars']);
            $this->total += $price * $item['qty'];
        }
        
        return $this->total;
    }
    
    /**
     * Obtiene todos los items del carrito
     * 
     * @return array Items del carrito
     */
    public function getItems()
    {
        return $this->items;
    }
    
    /**
     * Obtiene el total del carrito
     * 
     * @return float Total
     */
    public function getTotal()
    {
        return $this->total;
    }
    
    /**
     * Vacía el carrito completamente
     * 
     * @return bool true si se vació correctamente
     */
    public function clear()
    {
        $sql = e107::getDb();
        
        if($sql->delete('vstore_cart', "cart_session='{$this->session_id}'")) {
            $this->items = [];
            $this->total = 0.0;
            return true;
        }
        
        return false;
    }
    
    /**
     * Busca un item en el carrito
     * 
     * @param int $item_id ID del producto
     * @param array $vars Variaciones
     * @return int|false Índice del item o false si no existe
     */
    private function findItem($item_id, $vars)
    {
        foreach($this->items as $key => $item) {
            if($item['item_id'] == $item_id) {
                // Comparar variaciones
                if(serialize($item['vars']) === serialize($vars)) {
                    return $key;
                }
            }
        }
        
        return false;
    }
    
    /**
     * Inserta un item en la base de datos
     * 
     * @param array $item_data Datos del item
     * @return int ID del cart creado
     */
    private function insertItemInDb($item_data)
    {
        $sql = e107::getDb();
        
        $data = [
            'cart_session' => $this->session_id,
            'cart_e107_user' => USER ? USERID : null,
            'cart_status' => 'active',
            'cart_item' => $item_data['item_id'],
            'cart_item_vars' => e107::serialize($item_data['vars'], 'json'),
            'cart_item_tax_class' => $item_data['tax_class'],
            'cart_qty' => $item_data['qty']
        ];
        
        return $sql->insert('vstore_cart', $data);
    }
    
    /**
     * Actualiza un item en la base de datos
     * 
     * @param array $item Datos del item
     * @return bool true si se actualizó correctamente
     */
    private function updateItemInDb($item)
    {
        $sql = e107::getDb();
        
        $update = [
            'data' => [
                'cart_qty' => $item['qty']
            ],
            'WHERE' => 'cart_id=' . intval($item['cart_id'])
        ];
        
        return $sql->update('vstore_cart', $update);
    }
}
```

### Fase 3: Traducciones (Semana 5)

#### Archivos a Traducir

**Spanish_admin.php** (Área de administración)
**Spanish_front.php** (Frontend)
**Spanish_global.php** (Compartido)

**Portuguese_admin.php**
**Portuguese_front.php**
**Portuguese_global.php**

#### Proceso de Traducción

1. Extraer todas las constantes de los archivos English
2. Traducir usando herramienta profesional o servicio
3. Validar gramática y contexto técnico
4. Probar en interfaz para verificar longitudes de texto
5. Ajustar según feedback

### Fase 4: Manual de Usuario HTML (Semana 6)

#### Estructura del Manual

```html
docs/user-manual/
├── index.html              # Página principal
├── css/
│   └── manual.css         # Estilos personalizados
├── js/
│   └── manual.js          # Navegación y búsqueda
├── img/
│   └── screenshots/       # Capturas de pantalla
└── sections/
    ├── getting-started.html
    ├── products.html
    ├── categories.html
    ├── orders.html
    ├── coupons.html
    ├── taxes.html
    ├── payment-gateways.html
    ├── customers.html
    └── reports.html
```

---

## Timeline

```
SEMANA 1-2: Correcciones Críticas
├── Actualizar Composer dependencies
├── Corregir sistema de cupones
├── Corregir sistema de IVA
└── Testing exhaustivo

SEMANA 3-4: Implementación MVC
├── Crear estructura de directorios
├── Desarrollar modelos (Cart, Product, Order, etc.)
├── Desarrollar controladores
├── Migrar lógica de vstore.class.php
└── Testing de integración

SEMANA 5: Traducciones
├── Traducir Spanish_admin.php
├── Traducir Spanish_front.php
├── Traducir Spanish_global.php
├── Traducir Portuguese_admin.php
├── Traducir Portuguese_front.php
├── Traducir Portuguese_global.php
└── Validación y ajustes

SEMANA 6: Manual de Usuario
├── Diseñar estructura HTML
├── Crear secciones del manual
├── Tomar screenshots
├── Integrar en menú de admin
└── Revisión final

SEMANA 7: Testing y Documentación
├── Testing completo de todas las funcionalidades
├── Corrección de bugs encontrados
├── Actualizar copilot-instructions.md
└── Preparar changelog

SEMANA 8: Deploy y Capacitación
├── Deploy a producción
├── Monitoreo de errores
├── Capacitación a usuarios
└── Documentar procesos
```

---

## Notas Adicionales

### Herramientas de Debugging

```php
// Activar debugging de Vstore
define('e_DEBUG_VSTORE', true);

// Ver datos de sesión
e107::getDebug()->log(e107::getSession('vstore')->getData());

// Logging personalizado
e107::getDebug()->log('Cupón aplicado: ' . $coupon_code, 'VSTORE_COUPON');
```

### Testing Manual

1. **Cupones:**
   - Crear cupón con fecha futura → no debe aplicarse
   - Cupón expirado → error
   - Límite de uso alcanzado → error
   - Categorías excluidas → no descuento
   - Porcentaje > 100% → máximo = precio item

2. **IVA:**
   - Cliente mismo país → aplicar tasa local
   - Cliente UE → aplicar tasa destino
   - Cliente fuera UE → sin IVA
   - Empresa con VAT válido → reverse charge
   - VAT inválido → error

3. **Checkout:**
   - Como invitado
   - Como usuario registrado
   - Con dirección de envío diferente
   - Con cupón aplicado
   - Con productos de diferentes categorías

---

**Próximos pasos:** Comenzar con Fase 1 - Correcciones Críticas
