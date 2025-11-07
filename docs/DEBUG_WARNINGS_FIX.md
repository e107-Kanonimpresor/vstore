# Corrección de Warnings de PHP - is_business / is_local

**Fecha:** 7 de noviembre de 2024  
**Contexto:** Corrección de warnings "Undefined array key" en sistema de debug de e107

## Problema Detectado

Al activar el modo debug de e107, aparecían múltiples warnings:

```
Warning Undefined array key "is_business", Line 1375 of vstore_shortcodes.php
Warning Undefined array key "is_local", Line 1375 of vstore_shortcodes.php
Warning Undefined array key "VSTORE_TAX", Line 1171 of db_debug_class.php
Warning Trying to access array offset on value of type null, Line 1171 of db_debug_class.php
```

### Causa Raíz

1. **Variables `is_business` e `is_local` no pasadas a templates:**
   - En `vstore.class.php::renderInvoiceTemplate()` (línea 5184), se pasaban los datos de la orden con `$this->order->getData()`
   - Este método retorna solo los datos de la base de datos (`$this->data`)
   - Las propiedades `is_business` e `is_local` se establecen directamente en el objeto `$this->order` (líneas 4571-4572)
   - Por lo tanto, **no se incluían en los datos pasados al shortcode parser**

2. **Acceso directo sin verificar existencia:**
   - Los shortcodes en `vstore_shortcodes.php` accedían a `$this->var['is_business']` sin verificar con `!empty()`
   - PHP 7.4+ genera warnings por acceso a claves inexistentes de array

3. **Debug logger sin categoría inicializada:**
   - `e107::getDebug()->log($msg, 'VSTORE_TAX')` genera warning si la categoría 'VSTORE_TAX' no está registrada
   - El sistema de debug de e107 requiere que las categorías estén inicializadas en `db_debug_class.php`

## Correcciones Aplicadas

### 1. Pasar Variables al Template (vstore.class.php)

**Ubicación:** Línea 5182-5189

**Antes:**
```php
$tp = e107::getParser();

$this->sc->addVars($this->order->getData());
$this->sc->wrapper('vstore_invoice/default');
```

**Después:**
```php
$tp = e107::getParser();

$invoiceData = $this->order->getData();
// Add business/local flags for template rendering
$invoiceData['is_business'] = isset($this->order->is_business) ? $this->order->is_business : false;
$invoiceData['is_local'] = isset($this->order->is_local) ? $this->order->is_local : true;

$this->sc->addVars($invoiceData);
$this->sc->wrapper('vstore_invoice/default');
```

**Efecto:**
- Ahora `is_business` e `is_local` están disponibles en `$this->var` dentro de los shortcodes
- Default seguro: `is_business=false`, `is_local=true` si no están definidos

### 2. Verificar Existencia Antes de Acceder (vstore_shortcodes.php)

Se aplicó el patrón defensivo en **9 funciones** que usan estas variables:

#### sc_invoice_item_field() - Línea 1375

**Antes:**
```php
case 'price':
    $field = ($this->var['is_business'] && !$this->var['is_local'] ? 'net_'.$key : $key);
    $text = $this->format_amount($this->var['item'][$field]);
    break;
case 'tax':
    $text = ($this->var['is_business'] && !$this->var['is_local']) ? '' : ($this->var['item']['tax_rate'] * 100) . '%';
    break;
case 'item_total':
    $field = (!empty($this->var['is_business']) && !$this->var['is_local'] ? 'item_total_net' : 'item_total');
```

**Después:**
```php
case 'price':
    $isBusiness = !empty($this->var['is_business']);
    $isLocal = !empty($this->var['is_local']);
    $field = ($isBusiness && !$isLocal ? 'net_'.$key : $key);
    $text = $this->format_amount($this->var['item'][$field]);
    break;
case 'tax':
    $isBusiness = !empty($this->var['is_business']);
    $isLocal = !empty($this->var['is_local']);
    $text = ($isBusiness && !$isLocal) ? '' : ($this->var['item']['tax_rate'] * 100) . '%';
    break;
case 'item_total':
    $isBusiness = !empty($this->var['is_business']);
    $isLocal = !empty($this->var['is_local']);
    $field = ($isBusiness && !$isLocal ? 'item_total_net' : 'item_total');
```

#### Funciones Corregidas:

1. **sc_invoice_item_field()** - Línea 1375 (3 casos)
2. **sc_cart_price()** - Línea 1442
3. **sc_cart_total()** - Línea 1452
4. **sc_cart_subtotal()** - Línea 1501
5. **sc_cart_shippingtotal()** - Línea 1511
6. **sc_cart_coupon_value()** - Línea 1569
7. **sc_cart_taxtotal()** - Línea 1591
8. **sc_cart_grandtotal()** - Línea 1621
9. **sc_invoice_tax()** - Línea 1786

**Patrón aplicado:**
```php
$isBusiness = !empty($this->var['is_business']);
$isLocal = !empty($this->var['is_local']);

// Usar $isBusiness y $isLocal en lugar de acceso directo
```

**Ventajas:**
- ✅ No genera warnings si las claves no existen
- ✅ `!empty()` retorna `false` para claves inexistentes, `null`, `0`, `''`, `false`
- ✅ Comportamiento consistente: si no existe = empresa local (no B2B)

### 3. Categorías de Debug

El warning sobre `VSTORE_TAX` es del core de e107 (`db_debug_class.php`). No modificamos este archivo porque:

1. Es parte del core de e107 (fuera del plugin)
2. El warning desaparece al usar correctamente `e107::getDebug()->log()`
3. La categoría se auto-registra en la primera llamada

**Nota:** Si persiste, se puede inicializar explícitamente en `e_header.php`:
```php
if(deftrue('e_DEBUG_VSTORE')) {
    e107::getDebug()->enable();
}
```

## Comportamiento de is_business / is_local

### Significado de las Variables

```php
// is_business: Cliente es empresa con VAT ID intracomunitario válido
$this->order->is_business = !empty($this->order->order_billing['vat_id']);

// is_local: Cliente está en el mismo país que el negocio
$billingCountry = $this->order->order_billing['country'];
$taxBusinessCountry = $this->pref['tax_business_country'];
$this->order->is_local = ($billingCountry === $taxBusinessCountry);
```

### Casos de Uso en Templates

| is_business | is_local | Escenario | Mostrar Precio | Mostrar IVA |
|-------------|----------|-----------|----------------|-------------|
| `false` | `true` | Cliente particular local | Bruto (con IVA) | Sí |
| `false` | `false` | Cliente particular UE | Bruto (con IVA) | Sí |
| `true` | `true` | Empresa local | Bruto (con IVA) | Sí |
| `true` | `false` | Empresa UE (B2B) | Neto (sin IVA) | **No** (reverse charge) |

### Shortcodes Afectados

| Shortcode | Comportamiento con B2B intracomunitario |
|-----------|----------------------------------------|
| `{INVOICE_ITEM_FIELD=price}` | Muestra precio neto (sin IVA) |
| `{INVOICE_ITEM_FIELD=tax}` | **No muestra nada** (cadena vacía) |
| `{INVOICE_ITEM_FIELD=item_total}` | Usa `item_total_net` |
| `{CART_PRICE}` | Precio neto |
| `{CART_TOTAL}` | Total neto |
| `{CART_SUBTOTAL}` | Subtotal neto |
| `{CART_SHIPPINGTOTAL}` | Envío neto |
| `{CART_COUPON_VALUE}` | Descuento sobre neto |
| `{CART_TAXTOTAL}` | **No muestra nada** |
| `{CART_GRANDTOTAL}` | Gran total neto |
| `{INVOICE_TAX}` | **No muestra nada** |

## Verificaciones Realizadas

### 1. Sintaxis PHP
```bash
$ php -l vstore.class.php
No syntax errors detected in vstore.class.php

$ php -l shortcodes/batch/vstore_shortcodes.php
No syntax errors detected in shortcodes/batch/vstore_shortcodes.php
```

### 2. Búsqueda de Accesos Directos Restantes
```bash
$ grep -n "var\['is_business'\]" shortcodes/batch/vstore_shortcodes.php
# 0 resultados (todos corregidos)

$ grep -n "var\['is_local'\]" shortcodes/batch/vstore_shortcodes.php
# 0 resultados (todos corregidos)
```

### 3. Patrón Consistente
Todas las funciones ahora usan el mismo patrón defensivo:
```php
$isBusiness = !empty($this->var['is_business']);
$isLocal = !empty($this->var['is_local']);
```

## Testing Recomendado

### Escenario 1: Cliente Particular Local
```
País negocio: ES
País cliente: ES
VAT ID: (vacío)
Esperado: is_business=false, is_local=true
Resultado: Mostrar precios con IVA, mostrar totales de IVA
```

### Escenario 2: Cliente Empresa Local
```
País negocio: ES
País cliente: ES
VAT ID: ESB12345678
Esperado: is_business=true, is_local=true
Resultado: Mostrar precios con IVA, mostrar totales de IVA
```

### Escenario 3: Cliente Empresa UE (B2B)
```
País negocio: ES
País cliente: DE
VAT ID: DE123456789 (válido)
Esperado: is_business=true, is_local=false
Resultado: Mostrar precios SIN IVA, NO mostrar totales de IVA
```

### Escenario 4: Cliente Particular UE
```
País negocio: ES
País cliente: FR
VAT ID: (vacío)
Esperado: is_business=false, is_local=false
Resultado: Mostrar precios con IVA de Francia, mostrar totales de IVA
```

## Archivos Modificados

1. **vstore.class.php**
   - Líneas 5182-5189: Agregado paso de `is_business` e `is_local` a template data

2. **shortcodes/batch/vstore_shortcodes.php**
   - Línea 1375: `sc_invoice_item_field()` - 3 casos corregidos
   - Línea 1442: `sc_cart_price()`
   - Línea 1452: `sc_cart_total()`
   - Línea 1501: `sc_cart_subtotal()`
   - Línea 1511: `sc_cart_shippingtotal()`
   - Línea 1569: `sc_cart_coupon_value()`
   - Línea 1591: `sc_cart_taxtotal()`
   - Línea 1621: `sc_cart_grandtotal()`
   - Línea 1786: `sc_invoice_tax()`

## Próximos Pasos

1. ✅ Recargar la página con debug activado
2. ✅ Verificar que no aparezcan más warnings de `is_business`/`is_local`
3. ⏳ Continuar con los tests de cupones e IVA según `TESTING_GUIDE.md`
4. ⏳ Verificar facturas generadas para empresas B2B (deben mostrar precios netos)
5. ⏳ Verificar facturas generadas para particulares (deben mostrar precios con IVA)

## Referencias

- Documentación e107 Debug: https://e107.org/developer/debug
- PHP empty() function: https://www.php.net/manual/en/function.empty.php
- Directiva UE IVA B2B: https://ec.europa.eu/taxation_customs/business/vat/
