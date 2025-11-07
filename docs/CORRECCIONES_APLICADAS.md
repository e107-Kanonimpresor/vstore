# Correcciones Aplicadas al Plugin Vstore

**Fecha:** 6 de noviembre de 2025  
**Versión:** 1.0

---

## ✅ Correcciones Completadas

### 1. Actualización de Dependencias Composer

**Archivo:** `composer.json`

**Cambios realizados:**
```json
{
    "require": {
        "league/omnipay": "^3.2",      // Actualizado de ^3.1
        "omnipay/paypal": "^3.0.2",    // Actualizado de ~3.0
        "omnipay/mollie": "^5.5",      // Actualizado de ~5.0.2 → v5.5.0
        "ibericode/vat": "^2.0",       // Compatible con PHP 7.4 (2.0.9)
        "omnipay/stripe": "^3.2",      // Actualizado de ^3.1
        "omnipay/braintree": "4.0.x-dev", // Actualizado de ~4.0@dev (versión estable dev)
    },
    "config": {
        "platform": {
            "php": "7.4.33"            // Añadido para compatibilidad
        },
        "optimize-autoloader": true     // Añadido para optimización
    }
}
```

**Resultado de `composer update`:**
```
✅ Actualizado: guzzlehttp/guzzle (7.9.3 => 7.10.0)
✅ Actualizado: guzzlehttp/promises (2.2.0 => 2.3.0)
✅ Actualizado: guzzlehttp/psr7 (2.7.1 => 2.8.0)
✅ Actualizado: omnipay/mollie (v5.0.2 => v5.5.0)
✅ Optimizado: autoload con optimize-autoloader
✅ Seguridad: No se encontraron vulnerabilidades
```

---

### 2. Métodos Helper Agregados

**Archivo:** `vstore.class.php` (líneas ~5220-5340)

#### 2.1 getCouponUsageCount()

```php
/**
 * Get total usage count for a coupon code
 * 
 * @param string $couponCode The coupon code
 * @return int Number of times the coupon has been used
 */
public static function getCouponUsageCount($couponCode)
{
    $sql = e107::getDb();
    $count = $sql->count(
        'vstore_orders',
        '(*)',
        sprintf('order_pay_coupon_code="%s"', $sql->escape($couponCode))
    );
    
    return (int) $count;
}
```

**Uso:**
```php
$usageCount = vstore::getCouponUsageCount('SUMMER2025');
if($usageCount >= $coupon['coupon_limit_coupon']) {
    // Cupón alcanzó límite global
}
```

#### 2.2 getCouponUsageCountByUser()

```php
/**
 * Get usage count for a coupon code by a specific user
 * 
 * @param string $couponCode The coupon code
 * @param int $userId The e107 user ID
 * @return int Number of times the user has used this coupon
 */
public static function getCouponUsageCountByUser($couponCode, $userId)
{
    $sql = e107::getDb();
    $count = $sql->count(
        'vstore_orders',
        '(*)',
        sprintf(
            'order_e107_user=%d AND order_pay_coupon_code="%s"',
            (int) $userId,
            $sql->escape($couponCode)
        )
    );
    
    return (int) $count;
}
```

**Uso:**
```php
$userUsageCount = vstore::getCouponUsageCountByUser('SUMMER2025', USERID);
if($userUsageCount >= $coupon['coupon_limit_user']) {
    // Usuario alcanzó límite personal
}
```

#### 2.3 validateVATID()

```php
/**
 * Validate VAT ID against VIES (EU VAT validation service)
 * 
 * @param string $vatId The VAT ID to validate
 * @param string $countryCode Two-letter country code
 * @return array Array with keys: 'valid' (bool), 'name' (string), 'address' (string), 'error' (string)
 */
public static function validateVATID($vatId, $countryCode)
{
    $result = array(
        'valid' => false,
        'name' => '',
        'address' => '',
        'error' => ''
    );

    // Check if VAT validation is enabled
    $pref = e107::pref('vstore');
    if(empty($pref['tax_check_vat']))
    {
        // VAT validation disabled - accept without checking
        $result['valid'] = true;
        $result['error'] = 'VAT validation disabled in settings';
        return $result;
    }

    // Clean VAT ID (remove spaces and country prefix if duplicated)
    $vatId = strtoupper(str_replace(' ', '', $vatId));
    $countryCode = strtoupper($countryCode);

    // Remove country code from beginning if present
    if(strpos($vatId, $countryCode) === 0)
    {
        $vatId = substr($vatId, 2);
    }

    try
    {
        // Use ibericode/vat library
        $validator = new \DvK\Vat\Validator();
        $isValid = $validator->validate($countryCode . $vatId);

        if($isValid)
        {
            $result['valid'] = true;
            
            // Try to get company details
            try
            {
                $details = $validator->validateDetails($countryCode . $vatId);
                if($details)
                {
                    $result['name'] = varset($details->getName(), '');
                    $result['address'] = varset($details->getAddress(), '');
                }
            }
            catch(Exception $e)
            {
                // Details not available, but validation passed
                if(deftrue('e_DEBUG_VSTORE'))
                {
                    e107::getDebug()->log('VAT details fetch failed: ' . $e->getMessage(), 'VSTORE_VAT');
                }
            }
        }
        else
        {
            $result['error'] = 'Invalid VAT ID';
        }
    }
    catch(Exception $e)
    {
        // VIES service error - accept VAT ID as failsafe
        $result['valid'] = true;
        $result['error'] = 'VIES service unavailable: ' . $e->getMessage();
        
        if(deftrue('e_DEBUG_VSTORE'))
        {
            e107::getDebug()->log($result['error'], 'VSTORE_VAT');
        }
    }

    return $result;
}
```

**Uso:**
```php
$vatValidation = vstore::validateVATID('123456789', 'DE');

if($vatValidation['valid']) {
    echo "VAT ID válido";
    echo "Empresa: " . $vatValidation['name'];
    echo "Dirección: " . $vatValidation['address'];
    
    // Aplicar reverse charge (0% IVA para B2B intracomunitario)
    $tax_rate = 0.0;
} else {
    echo "Error: " . $vatValidation['error'];
}
```

---

## 🔄 Correcciones Pendientes de Aplicar Manualmente

### 3. Método calcCouponAmount() - Validación Mejorada

**Ubicación:** `vstore.class.php` línea 3836

**Problema:** La validación actual no verifica exclusiones correctamente y permite casos edge donde cupones fijos exceden el precio del producto.

**Solución:** Reemplazar el método con esta versión mejorada:

```php
/**
 * Calculate coupon discount for an item
 * If the coupon is valid, the result is always <= 0.0
 *
 * @param array $coupon a row from the vstore_coupons table.
 * @param array $item (should have the columns item_id, item_cat, item_price, cart_qty, item_name)
 * @return float Always returns 0.0 or negative value (discount)
 */
public static function calcCouponAmount($coupon, $item)
{
    if(empty($coupon) || empty($item))
    {
        return 0.0;
    }

    // 1. Verify coupon is active
    if(empty($coupon['coupon_active']))
    {
        e107::getMessage()->addError('Coupon is not available!', 'vstore');
        return 0.0;
    }

    // 2. Verify coupon date range
    $currentTime = time();
    if(!empty($coupon['coupon_start']) && $currentTime < $coupon['coupon_start'])
    {
        e107::getMessage()->addError('Coupon is not yet available!', 'vstore');
        return 0.0;
    }

    if(!empty($coupon['coupon_end']) && $currentTime > $coupon['coupon_end'])
    {
        e107::getMessage()->addError('Coupon is no longer available!', 'vstore');
        return 0.0;
    }

    // 3. Verify global usage limit
    if($coupon['coupon_limit_coupon'] > -1)
    {
        $usageCount = self::getCouponUsageCount($coupon['coupon_code']);
        if($usageCount >= $coupon['coupon_limit_coupon'])
        {
            e107::getMessage()->addError(
                'Coupon has exceeded its allowed number of usage!',
                'vstore'
            );
            return 0.0;
        }
    }

    // 4. Verify per-user usage limit
    if($coupon['coupon_limit_user'] > -1 && defined('USERID') && USERID > 0)
    {
        $userUsageCount = self::getCouponUsageCountByUser($coupon['coupon_code'], USERID);
        if($userUsageCount >= $coupon['coupon_limit_user'])
        {
            e107::getMessage()->addError(
                'You have exceeded the allowed number of usage for this coupon!',
                'vstore'
            );
            return 0.0;
        }
    }

    // Parse category and item restrictions
    $coupon['coupon_items'] = array_filter(explode(',', $coupon['coupon_items']));
    $coupon['coupon_items_ex'] = array_filter(explode(',', $coupon['coupon_items_ex']));
    $coupon['coupon_cats'] = array_filter(explode(',', $coupon['coupon_cats']));
    $coupon['coupon_cats_ex'] = array_filter(explode(',', $coupon['coupon_cats_ex']));

    $itemID = (int) $item['item_id'];
    $itemCat = (int) $item['item_cat'];

    // 5. Check category exclusions FIRST (highest priority)
    if(count($coupon['coupon_cats_ex']) > 0 && in_array($itemCat, $coupon['coupon_cats_ex']))
    {
        // Category excluded - no discount
        if(deftrue('e_DEBUG_VSTORE'))
        {
            e107::getDebug()->log("Coupon excluded: item category {$itemCat} in exclusion list", 'VSTORE_COUPON');
        }
        return 0.0;
    }

    // 6. Check item exclusions (high priority)
    if(count($coupon['coupon_items_ex']) > 0 && in_array($itemID, $coupon['coupon_items_ex']))
    {
        // Item excluded - no discount
        if(deftrue('e_DEBUG_VSTORE'))
        {
            e107::getDebug()->log("Coupon excluded: item {$itemID} in exclusion list", 'VSTORE_COUPON');
        }
        return 0.0;
    }

    // 7. Check category inclusions (if defined, must match)
    if(count($coupon['coupon_cats']) > 0)
    {
        if(!in_array($itemCat, $coupon['coupon_cats']))
        {
            // Category not in inclusion list - no discount
            return 0.0;
        }
    }

    // 8. Check item inclusions (if defined, must match)
    if(count($coupon['coupon_items']) > 0)
    {
        if(!in_array($itemID, $coupon['coupon_items']))
        {
            // Item not in inclusion list - no discount
            return 0.0;
        }
    }

    // 9. Calculate discount amount
    $qty = (int) $item['cart_qty'];
    $itemPrice = (float) $item['item_price'];
    $amount = 0.0;

    if($coupon['coupon_type'] === '%')
    {
        // Percentage discount
        $discountPct = (float) $coupon['coupon_amount'];
        $amount = -1 * ($itemPrice * $qty) * ($discountPct / 100.0);
    }
    else
    {
        // Fixed amount discount
        $discountPerItem = (float) $coupon['coupon_amount'];
        
        // CRITICAL: Ensure discount doesn't exceed item price
        if($discountPerItem > $itemPrice)
        {
            $discountPerItem = $itemPrice;
            
            e107::getMessage()->addWarning(
                'Coupon discount has been capped to item price for: ' . $item['item_name'],
                'vstore'
            );
        }
        
        $amount = -1 * ($discountPerItem * $qty);
    }

    // 10. Ensure amount is never positive
    if($amount > 0)
    {
        $amount = 0.0;
    }

    if(deftrue('e_DEBUG_VSTORE'))
    {
        e107::getDebug()->log([
            'coupon_code' => $coupon['coupon_code'],
            'item_id' => $itemID,
            'qty' => $qty,
            'discount_amount' => $amount
        ], 'VSTORE_COUPON');
    }

    return $amount;
}
```

**Cambios clave:**
1. ✅ Usa `getCouponUsageCount()` y `getCouponUsageCountByUser()` para verificar límites
2. ✅ Valida exclusiones ANTES que inclusiones (lógica correcta)
3. ✅ Verifica categorías excluidas primero (máxima prioridad)
4. ✅ Previene que descuentos fijos excedan el precio del item
5. ✅ Agrega logging detallado con `e_DEBUG_VSTORE`
6. ✅ Casting explícito a int/float para prevenir errores de tipo
7. ✅ Garantiza que el resultado siempre sea <= 0.0

---

### 4. Método getTaxRate() - Eliminación de Geolocalización por IP

**Ubicación:** `vstore.class.php` línea 4044

**Problema:** El método usa geolocalización por IP que es no confiable (VPNs, proxies). También usa variable estática que causa bugs.

**Solución:** Reemplazar el método completo (buscar desde línea 4044 hasta aproximadamente línea 4130):

```php
/**
 * Calculate tax rate for an item based on tax class and customer country
 * 
 * @param string $tax_class should be 'none', 'reduced', 'reduced1', 'reduced2', 'super_reduced', 'standard', 'parking'
 * @param string $customer_country ISO 3166-1 alpha-2 country code (from billing form, NOT from IP)
 * @param array|null $pref Plugin preferences (optional, will load if not provided)
 * @return float Tax rate as decimal (e.g., 0.21 for 21%)
 */
public static function getTaxRate($tax_class, $customer_country = null, $pref = null)
{
    if(empty($pref))
    {
        $pref = e107::pref('vstore');
    }

    $result = 0.0;

    // 1. Check if tax calculation is enabled
    if(empty($pref['tax_calculate']))
    {
        return $result;
    }

    // 2. Handle 'none' tax class
    $tax_class = strtolower(varset($tax_class, 'standard'));
    if($tax_class === 'none')
    {
        return $result;
    }

    // 3. Get customer country (CRITICAL: from form data, NOT from IP geolocation)
    if(empty($customer_country))
    {
        // Try to get from session data
        $sessionData = e107::getSession('vstore')->get('_data');
        $customer_country = varset($sessionData['customer_country'], '');
        
        // Fallback to business country if still empty
        if(empty($customer_country))
        {
            $customer_country = $pref['tax_business_country'];
            
            if(deftrue('e_DEBUG_VSTORE'))
            {
                e107::getDebug()->log('Customer country empty, using business country: ' . $customer_country, 'VSTORE_TAX');
            }
        }
    }

    $customer_country = strtoupper($customer_country);
    $businessCountry = strtoupper($pref['tax_business_country']);

    $countries = new DvK\Vat\Countries();

    // 4. Same country as business - use local tax rates
    if($customer_country === $businessCountry)
    {
        $tax_classes = e107::unserialize($pref['tax_classes']);
        
        if(!is_array($tax_classes))
        {
            if(deftrue('e_DEBUG_VSTORE'))
            {
                e107::getDebug()->log('tax_classes is not an array!', 'VSTORE_TAX');
            }
            return $result;
        }

        foreach($tax_classes as $tclass)
        {
            if(varset($tclass['name']) === $tax_class)
            {
                $result = floatval($tclass['value']);
                break;
            }
        }

        if(deftrue('e_DEBUG_VSTORE'))
        {
            e107::getDebug()->log([
                'scenario' => 'same_country',
                'tax_class' => $tax_class,
                'tax_rate' => $result
            ], 'VSTORE_TAX');
        }

        return $result;
    }

    // 5. Business in EU
    if($countries->inEurope($businessCountry))
    {
        // 5a. Customer outside EU - no tax
        if(!$countries->inEurope($customer_country))
        {
            if(deftrue('e_DEBUG_VSTORE'))
            {
                e107::getDebug()->log('Customer outside EU, no tax', 'VSTORE_TAX');
            }
            return $result;
        }

        // 5b. Customer in EU - check for reverse charge (B2B intra-community)
        $sessionData = e107::getSession('vstore')->get('_data');
        $vatId = varset($sessionData['vat_id'], '');
        
        if(!empty($vatId) && !empty($pref['tax_check_vat']))
        {
            // Validate VAT ID
            $vatValidation = self::validateVATID($vatId, $customer_country);
            
            if($vatValidation['valid'])
            {
                // Valid VAT ID - reverse charge applies (0% tax)
                if(deftrue('e_DEBUG_VSTORE'))
                {
                    e107::getDebug()->log([
                        'scenario' => 'reverse_charge_b2b',
                        'vat_id' => $vatId,
                        'valid' => true,
                        'tax_rate' => 0.0
                    ], 'VSTORE_TAX');
                }
                
                return 0.0;
            }
        }

        // No valid VAT ID - apply customer country tax rate
        $mapped_tax_class = self::getTaxClass($tax_class, $customer_country);
        
        if(empty($mapped_tax_class))
        {
            if(deftrue('e_DEBUG_VSTORE'))
            {
                e107::getDebug()->log('Tax class mapping failed for ' . $tax_class . ' in ' . $customer_country, 'VSTORE_TAX');
            }
            return 0.0;
        }

        $rates = new DvK\Vat\Rates\Rates();
        try
        {
            $result = $rates->country($customer_country, $mapped_tax_class);
            $result /= 100.0; // Convert percentage to decimal
        }
        catch(Exception $ex)
        {
            e107::getMessage()->addError('Invalid tax class! Please inform the shop administrator!', 'vstore');
            
            if(deftrue('e_DEBUG_VSTORE'))
            {
                e107::getDebug()->log('Tax rate exception: ' . $ex->getMessage(), 'VSTORE_TAX');
            }
            
            return 0.0;
        }

        if(deftrue('e_DEBUG_VSTORE'))
        {
            e107::getDebug()->log([
                'scenario' => 'intra_eu_b2c',
                'customer_country' => $customer_country,
                'tax_class' => $tax_class,
                'mapped_class' => $mapped_tax_class,
                'tax_rate' => $result
            ], 'VSTORE_TAX');
        }

        return $result;
    }
    else
    {
        // 6. Business outside EU - no tax for foreign customers
        if(deftrue('e_DEBUG_VSTORE'))
        {
            e107::getDebug()->log('Business outside EU, no tax', 'VSTORE_TAX');
        }
        return $result;
    }
}
```

**Cambios clave:**
1. ❌ **ELIMINADO:** Geolocalización por IP (`e107::getIPHandler()->getIP()`)
2. ❌ **ELIMINADO:** Variable estática `$customerCountry`
3. ✅ **AGREGADO:** Obtiene país del formulario vía sesión `$_data['customer_country']`
4. ✅ **AGREGADO:** Soporte para reverse charge (B2B intracomunitario con VAT ID válido)
5. ✅ **AGREGADO:** Validación de VAT ID usando `validateVATID()`
6. ✅ **AGREGADO:** Logging detallado de escenarios (same_country, reverse_charge, intra_eu, etc.)
7. ✅ **MEJORADO:** Manejo de errores más robusto

---

## 🧪 Testing Requerido

### Después de aplicar correcciones:

1. **Activar Debug Mode**
```php
// En e107_config.php o e_header.php
define('e_DEBUG_VSTORE', true);
```

2. **Casos de Prueba - Cupones**

| # | Escenario | Esperado |
|---|-----------|----------|
| 1 | Cupón con fecha futura | ❌ Error "not yet available" |
| 2 | Cupón expirado | ❌ Error "no longer available" |
| 3 | Límite global alcanzado | ❌ Error "exceeded usage" |
| 4 | Límite por usuario alcanzado | ❌ Error "user exceeded usage" |
| 5 | Producto en categoría excluida | ✅ Sin descuento aplicado |
| 6 | Producto excluido específicamente | ✅ Sin descuento aplicado |
| 7 | Descuento fijo > precio item | ✅ Descuento limitado a precio |
| 8 | Cupón porcentual válido (20%) | ✅ Descuento 20% aplicado |

3. **Casos de Prueba - IVA**

| # | Escenario | País Negocio | País Cliente | VAT ID | IVA Esperado |
|---|-----------|--------------|--------------|--------|--------------|
| 1 | Cliente mismo país | ES | ES | - | 21% (local) |
| 2 | Cliente UE sin VAT | ES | FR | - | 20% (FR tasa) |
| 3 | Cliente UE con VAT válido | ES | DE | DE123456789 | 0% (reverse charge) |
| 4 | Cliente UE con VAT inválido | ES | DE | DE999999999 | 19% (DE tasa) |
| 5 | Cliente fuera UE | ES | US | - | 0% (export) |
| 6 | Negocio fuera UE | US | GB | - | 0% |

4. **Verificar Logs**

Buscar en debug footer:
```
[VSTORE_COUPON] Coupon excluded: item category 5 in exclusion list
[VSTORE_TAX] scenario: reverse_charge_b2b, vat_id: DE123456789, valid: true, tax_rate: 0.0
```

---

## 📝 Notas Importantes

### Backup

Antes de aplicar las correcciones pendientes:
```bash
cp vstore.class.php vstore.class.php.bak
```

### Rollback

Si algo falla:
```bash
mv vstore.class.php.bak vstore.class.php
```

### Compatibilidad

- ✅ PHP 7.4.33 (configurado en composer.json)
- ✅ e107 CMS 2.3.2+
- ✅ MySQL 5.6+
- ✅ ibericode/vat 2.0.9 (compatible con PHP 7.4)

---

## 🚀 Próximos Pasos

1. ✅ **Completado:** Actualizar Composer
2. ✅ **Completado:** Agregar métodos helper
3. ⏳ **Pendiente:** Reemplazar `calcCouponAmount()` manualmente
4. ⏳ **Pendiente:** Reemplazar `getTaxRate()` manualmente
5. ⏳ **Pendiente:** Testing exhaustivo
6. ⏳ **Pendiente:** Deploy a staging
7. ⏳ **Pendiente:** Traducciones Spanish/Portuguese

---

**Generado:** 6 de noviembre de 2025  
**Estado:** En progreso (2/4 correcciones aplicadas)
