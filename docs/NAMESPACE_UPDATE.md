# Actualización de Namespace - ibericode/vat 2.0.9

**Fecha:** 7 de noviembre de 2024  
**Contexto:** Corrección del error fatal "Class 'DvK\Vat\Countries' not found" tras actualizar Composer

## Problema

Tras actualizar la librería `ibericode/vat` de la versión 1.x a 2.0.9 mediante Composer, el namespace cambió de `DvK\Vat` a `Ibericode\Vat`, causando un error fatal al intentar ejecutar los tests.

## Cambios Realizados

### 1. Actualización de Namespaces (14 referencias)

| Línea | Namespace Antiguo | Namespace Nuevo |
|-------|-------------------|-----------------|
| 22 | `use DvK\Vat\Rates\Exceptions\Exception` | `use Ibericode\Vat\Exception` |
| 4043 | `new DvK\Vat\Countries()` | `new \Ibericode\Vat\Countries()` |
| 4081-4084 | N/A | N/A |
| 4131 | `new DvK\Vat\Rates\Rates()` | `new \Ibericode\Vat\Rates()` |
| 4197 | `new DvK\Vat\Rates\Rates()` | `new \Ibericode\Vat\Rates()` |
| 4315 | `new DvK\Vat\Countries()` | `new \Ibericode\Vat\Countries()` |
| 4323 | `new DvK\Vat\Validator()` | `new \Ibericode\Vat\Validator()` |
| 5322 | `new \DvK\Vat\Validator()` | `new \Ibericode\Vat\Validator()` |

### 2. Actualización de Métodos API

#### Countries Class

```php
// ANTES
$countries->inEurope($countryCode)

// AHORA
$countries->isCountryCodeInEU($countryCode)
```

**Ubicaciones actualizadas:**
- Línea 4081-4084 en `getTaxRate()`
- Línea 4317 en `validateVat()`

#### Rates Class

```php
// ANTES
$rates = new DvK\Vat\Rates\Rates();
$rate = $rates->country($country, $level);

// AHORA
$rates = new \Ibericode\Vat\Rates($storagePath, $refreshInterval);
$rate = $rates->getRateForCountry($country, $level);
```

**Ubicaciones actualizadas:**
- Línea 4131 en `getTaxRate()`

**Nota:** El constructor de `Rates` ahora requiere `$storagePath` (string) y opcionalmente `$refreshInterval` (int, default 12*3600). Para lookups únicos sin caché, usar `new \Ibericode\Vat\Rates('', 0)`.

#### Validator Class

```php
// ANTES
$validator->validate($vatNumber)
$details = $validator->validateDetails($vatNumber)

// AHORA
$validator->validateVatNumber($vatNumber)
// validateDetails() ya no existe
```

**Ubicaciones actualizadas:**
- Línea 4325 en `validateVat()`
- Línea 5324 en `validateVATID()`

**Cambio importante:** El método `validateDetails()` fue eliminado en la versión 2.x. Ahora solo se puede validar el formato y existencia del VAT ID, pero no obtener detalles de la empresa (nombre, dirección).

### 3. Refactorización del Método `getTaxClass()`

El método `getTaxClass()` fue completamente refactorizado porque:

1. **Problema:** Intentaba usar `$rates->all()` que ya no existe en la nueva versión
2. **Solución:** Usar `getRateForCountry()` con try-catch para verificar existencia de tax classes

**Código antiguo (líneas 4169-4243):**
```php
$rates = new DvK\Vat\Rates\Rates();
$map = $rates->all(); // ❌ No existe en v2.x

// Recorría manualmente el array de períodos y tasas
$periods = $map[$country]['periods'];
$tax_classes = array_keys($periods[0]['rates']);
```

**Código nuevo (líneas 4169-4230):**
```php
$rates = new \Ibericode\Vat\Rates('', 0);

try {
    // Si no lanza excepción, la clase existe
    $rates->getRateForCountry($country, $tax_class);
    return $tax_class;
} catch(\Exception $ex) {
    // Probar alternativas
    foreach($map_classes[$tax_class] as $alternative) {
        try {
            $rates->getRateForCountry($country, $alternative);
            return $alternative;
        } catch(\Exception $ex2) {
            continue;
        }
    }
    return 'standard'; // Fallback
}
```

**Ventajas:**
- ✅ No depende de estructura interna de datos
- ✅ Usa API pública documentada
- ✅ Más eficiente (no carga todas las tasas de todos los países)
- ✅ Manejo de errores explícito

### 4. Validación VAT ID - Pérdida de Funcionalidad

**Antes (v1.x):**
```php
$validator = new DvK\Vat\Validator();
$isValid = $validator->validate($vatId);
$details = $validator->validateDetails($vatId);

// Se podía obtener:
$details->getName()    // Nombre de la empresa
$details->getAddress() // Dirección
```

**Ahora (v2.x):**
```php
$validator = new \Ibericode\Vat\Validator();
$isValid = $validator->validateVatNumber($vatId);

// Solo retorna bool, no hay método para obtener detalles
```

**Impacto:** El método `validateVATID()` (línea 5290) ya no puede retornar el nombre y dirección de la empresa en el array de resultado. Quedó así:

```php
$result = array(
    'valid' => false,
    'error' => '',
    'name' => '',    // ⚠️ Siempre vacío ahora
    'address' => ''  // ⚠️ Siempre vacío ahora
);
```

**Posible solución futura:** Implementar llamada directa a VIES SOAP API para obtener detalles de empresa.

## Verificaciones Realizadas

### 1. Sintaxis PHP
```bash
$ php -l vstore.class.php
No syntax errors detected in vstore.class.php
```

### 2. Búsqueda de Referencias
```bash
$ grep -r "DvK\\Vat" --include="*.php" .
# 0 matches en código PHP
```

Solo quedan referencias en documentación (markdown files), que son históricas y no requieren actualización.

### 3. Métodos Disponibles

#### Countries.php
```php
public function __construct()
public function isCountryCodeInEU(string $countryCode): bool
public function getCountryCodesInEU(): array
```

#### Rates.php
```php
public function __construct(string $storagePath, int $refreshInterval = 12 * 3600, Client $client = null)
public function getRateForCountry(string $countryCode, string $level = self::RATE_STANDARD): float
public function getRateForCountryOnDate(string $countryCode, DateTimeInterface $datetime, string $level = self::RATE_STANDARD): float
```

#### Validator.php
```php
public function __construct(Vies\Client $client = null)
public function validateCountryCode(string $countryCode): bool
public function validateIpAddress(string $ipAddress): bool
public function validateVatNumberFormat(string $vatNumber): bool
public function validateVatNumber(string $vatNumber): bool
```

## Testing Pendiente

Ahora que los namespaces están corregidos, se pueden ejecutar los 14 casos de prueba documentados en `TESTING_GUIDE.md`:

### Casos Críticos para IVA:
1. Cliente mismo país → IVA local (21%)
2. Cliente UE sin VAT → IVA país destino
3. Cliente UE con VAT válido → 0% (reverse charge)
4. Cliente fuera UE → 0%
5. Cambiar país en formulario → Recalcular IVA
6. VAT ID inválido → Error de validación

### Casos Críticos para Cupones:
7. Cupón con fecha futura → No aplicable
8. Cupón expirado → Error
9. Límite de uso alcanzado → Error
10. Producto excluido → Sin descuento
11. Descuento > precio → Máximo = precio
12. Cupón porcentual > 100% → Validar límite

## Archivos Modificados

- `vstore.class.php` (5354 líneas)
  - Líneas modificadas: 22, 4043, 4081-4084, 4131, 4134, 4169-4230, 4315, 4317, 4323, 4325, 5322, 5324

## Próximos Pasos

1. **Ejecutar tests completos** según `TESTING_GUIDE.md`
2. **Activar debug mode:** `define('e_DEBUG_VSTORE', true);` en `e_header.php`
3. **Verificar logs:** `e107::getDebug()->log()` en footer
4. **Considerar restaurar funcionalidad de detalles VAT** mediante llamada directa a VIES API

## Referencias

- Librería: https://github.com/ibericode/vat
- Documentación v2.x: https://github.com/ibericode/vat/blob/master/README.md
- VIES API: https://ec.europa.eu/taxation_customs/vies/
