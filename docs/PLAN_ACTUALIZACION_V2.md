# Plan de Actualización Vstore v2.0.0 — Estudio y Plan de Acción

**Fecha:** 2026-10-15
**Base:** `KANONIMPRESOR_PLUGIN_PACK_MIGRATION_PROMPT.md` + docs de la primera actualización (v1.1.0)
**Estado:** Propuesta — pendiente de aprobación antes de editar código (Phase 0 → 6 del prompt de migración)
**NS propuesto:** `VSTORE` (slug `vstore`)

---

## 1. Diagnóstico (estado real medido)

### 1.1 Qué se hizo en la primera actualización (v1.1.0)
Correcciones de bugs (cupones, IVA, VIES, carrito, envío, PHP 8), actualización Composer, 16 constantes ES/PT, documentación en `docs/`. **No se aplicó el pack de estandarización Kanonimpresor.**

### 1.2 Inventario vs. patrón canónico

| Elemento del pack | Estado actual |
|---|---|
| `plugin.xml` | `version="1.0"`, `date="2014-05-29"`, autor `e107 Inc`, copyright `GPL`. **Desincronizado** con CHANGELOG (1.1.0). Sin `lan`, sin `<pluginPrefs>` completo (faltan ~50 prefs que usa `admin_config_pref.php`) |
| Pestaña **About** | No existe |
| Pestaña **User Guide** | No existe (solo `docs/user-manual.html` externo) |
| `renderHelp()` sidebar | No existe en ninguno de los 12 controladores |
| `getPluginInfo()` / `_resolveLans()` | No existen |
| `templates/vstore_about_template.php`, `vstore_guide_template.php` | No existen |
| `shortcodes/batch/vstore_about_shortcodes.php`, `vstore_guide_shortcodes.php` | No existen (solo `vstore_shortcodes.php`, 1955 líneas) |
| `languages/<Lang>/<Lang>_admin_about.php`, `_admin_help.php` | No existen |
| `DEV_NOTES.md` | No existe |
| `docs/architecture/USER_GUIDE_PATTERN.md` | Existe pero es una **copia de sitedown_styles** (habla de `SS`, "Martin Costa"); hay que adaptarla |
| `docs/images/README.md` | No existe |
| README (EN/ES/PT) | Badges propios, autor e107 Inc/Gitter; sin bloque Kanonimpresor |
| `CHANGELOG.md` | Existe (hasta 1.1.0) |
| Repos de documentación sueltos en raíz (`UPLOAD_SUCCESS.md`, `RELEASE_NOTES_v1.1.0.md`, `GIT_UPDATE_GUIDE.md`, `QUICK_RELEASE_GUIDE.md`, `CREATE_RELEASE_GUIDE.md`, `GUIA_DESARROLLO_PLUGINS_E107.md`) | Ruido: consolidar en `docs/` |

### 1.3 Hardcodeo (i18n) — el mayor déficit

| Archivo de idioma | Constantes |
|---|---|
| `English_admin.php` / `Spanish_admin.php` / `Portuguese_admin.php` | 12 c/u (solo captions del menú) |
| `*_front.php` | 3 c/u |
| `*_global.php` | 1 c/u |
| **Total** | **16 constantes para ~18.000 líneas de PHP** |

- `templates/*.php` (6 archivos, ~1.600 líneas): **0 referencias `LAN_`**. Todo el texto del frontend, emails e invoice está en inglés literal.
- `inc/admin_config_*.php`: títulos de campos, `help`, `writeParms` de dropdowns, nombres de pestañas (`'Shipping'`, `'Tax'`, `'Restrictions'`, `'Billing'`...), mensajes (`addError('Invalid coupon code!')`), etiquetas de botones, HTML de estadísticas (`Monthly Revenue`, `Custom Chart`).
- `vstore.class.php`: arrays estáticos `$status`, `$emailTypes`, `$currencies` (títulos), `$gateways`, `$mollie_payment_methods`, captions (`"Product Brands"`, `"Out of Stock"`), mensajes de error de checkout/cupones/IVA (incluidos los añadidos en v1.1.0).
- `shortcodes/batch/vstore_shortcodes.php`: `'Cancel order'`, `'View details'`, `'Actions'`, `'VAT ID: '`, `'Taxcode: '`, `'Email: '`, `'Phone: '`, `'Fax: '`...
- `inc/vstore_dashboard.class.php`: `'My orders'`, `'My downloads'`, `'No order has been made yet.'`, mensaje de login con HTML.
- `inc/vstore_order.class.php`: `last_error` (`'Unable to load order!'`, `'Order not found!'`), log de pedidos.
- `e_dashboard.php`, `e_sitelink.php`, `e_cron.php`, `e_search.php`: `'Vstore Open/Payed orders'`, `'Open orders'`, `'Vstore Categories'`, `'Vstore - Purge cart'`, `'Search in category'`.
- `e_header.php` **define LANs de fallback** (`LAN_VSTORE_001..003`) duplicando los de `*_front.php`.
- `invoice` prefs con `default` en inglés (`INVOICE`, `Thanks for your business!`).
- Preferencias `multilan` ya existentes: correcto, se mantienen.

### 1.4 Lógica de negocio mezclada con presentación

| Problema | Evidencia |
|---|---|
| God class | `vstore.class.php` 5411 líneas, ~100 métodos: carrito, checkout, impuestos, cupones, envío, pasarelas, inventario, facturas, PDF, AJAX, render |
| Herencia para reutilizar utilidades | `vstore_order extends vstore`, `vstore_dashboard extends vstore` (acoplamiento por herencia) |
| Métodos que mezclan HTML + lógica | `render()` (~300 líneas), `checkoutView()`, `cartView()`, `renderCustomerForm()`, `confirmOrderView()`, `processGateway()` |
| Mensajes UI dentro de lógica | `calcCouponAmount()`, `getTaxRate()`, `validateVATID()` llaman a `e107::getMessage()->addError('...')` |
| Estado estático | `$itemVarsTypes`, `$customerCountry` (ya mitigado), arrays `static` |
| SQL por concatenación | `e_sitelink.php` usa `e107_vstore_cat` / `e107_vstore_items` **con prefijo hardcodeado** (rompe si el prefijo MySQL no es `e107_`); `e_dashboard.php` y `vstore_dashboard.class.php` concatenan SQL |
| Acceso directo a superglobales | `$_GET`/`$_POST` en `admin_config_*.php` (14 usos); `setGet/setPost` en clase |
| PDF acoplado | `vstore_pdf.class.php`: `define()` globales, `ob_start()` al incluir, `include_lan` del plugin `pdf` |
| Carga de `vendor/autoload.php` en el include de la clase | Falla fatal si falta `composer install` |
| Admin: `admin_config_cart.php` | Código muerto (modo comentado en dispatcher) |
| Admin: `$taxClassesDefault` y cabeceras de plantilla generadas | Comentarios "Generated e107 Plugin" y `$tabs` de ejemplo sin limpiar |

### 1.5 Ya resuelto (no repetir)
Cupones (fechas, límites, exclusiones), IVA (reverse charge, VIES, namespace `Ibericode\Vat`), PHP 7.4/8.x undefined keys, preservación de carrito, envío con facturación.

---

## 2. Arquitectura objetivo

```
e107_plugins/vstore/
├── plugin.xml                     v2.0.0, autor Kanonimpresor, prefs completos
├── admin_config.php               + 'main/about', 'main/guide', getPluginInfo(), _resolveLans(), renderHelp()
├── vstore.class.php               FACHADA delgada (compat. pública, delega a src/)
├── src/                           ← NUEVA capa de lógica de negocio (sin HTML, sin e107::getMessage)
│   ├── Service/
│   │   ├── CartService.php        updateCart, resetCart, getCartData, inventario
│   │   ├── CouponService.php      calcCouponAmount, usage counts, validación
│   │   ├── TaxService.php         getTaxRate, getTaxClass, calcNet, calcTaxAmount, VIES
│   │   ├── ShippingService.php    calcShippingCost, weightUnits
│   │   ├── CheckoutService.php    prepareCheckoutData, validateCustomerData
│   │   ├── OrderService.php       saveTransaction, getOrderRef, invoice nr
│   │   ├── PaymentService.php     loadGateway, processGateway
│   │   └── InvoiceService.php     renderInvoice, invoiceToPdf
│   ├── Repository/                Acceso a BD (#vstore_*) con escape/casting
│   ├── Support/
│   │   ├── Catalog.php            status, emailTypes, currencies, gateways, mollie (títulos vía LAN)
│   │   └── Result.php             {ok, code, params} → el controlador traduce con LAN
│   └── autoload (PSR-4 en composer.json, namespace Vstore\)
├── inc/                           controladores admin (solo UI) + vstore_order/dashboard/pdf sin `extends vstore`
├── templates/                     + about, guide; tokens {LAN_VSTORE_*}
├── shortcodes/batch/              vstore_shortcodes (presentación) + about + guide(stub)
├── languages/<Lang>/
│   ├── <Lang>_global.php          nombre, carrito
│   ├── <Lang>_front.php           catálogo, carrito, checkout, dashboard usuario, mensajes
│   ├── <Lang>_admin.php           menú, campos, pestañas, mensajes admin, widget (8 LAN)
│   ├── <Lang>_admin_about.php     lazy
│   ├── <Lang>_admin_help.php      lazy (guía)
│   └── <Lang>_email.php           asuntos y textos de email / invoice (lazy)
├── DEV_NOTES.md
├── CHANGELOG.md
└── docs/
    ├── architecture/USER_GUIDE_PATTERN.md   (adaptado a VSTORE)
    ├── architecture/BUSINESS_LOGIC_LAYER.md (nuevo: contrato de servicios)
    ├── i18n/LAN_MAP.md                      (mapa de constantes)
    └── images/README.md
```

**Principios (heredados del prompt, sección 9 y 11):** no renombrar LANs públicas existentes, no tocar esquema BD ni prefs, no romper API pública (`vstore::getCurrencySymbol`, `calcCouponAmount`, etc. permanecen como **delegadores** a `src/`), BS3 en admin / BS5 en front, sin `__call()` en batches, sin `e_help.php`, prefs bajo `e107::getPlugConfig('vstore')`.

**Convención LAN (nueva, compatible con las 16 existentes):**
- Admin: `LAN_VSTORE_ADMIN_<TAB>_<CAMPO>` (título) y `..._HELP` (ayuda). Existentes `LAN_VSTORE_ADMIN_000-011` se mantienen.
- Front: `LAN_VSTORE_<AREA>_<NNN>` / `LAN_VSTORE_<AREA>_<CLAVE>`.
- Mensajes de servicio: `LAN_VSTORE_MSG_<CODIGO>` con `sprintf`.
- Guía: `LAN_VSTORE_GUIDE_<SECCION>_<CLAVE>` (solo texto, `<code>/<strong>/<em>` permitidos).
- About: `LAN_PLUGIN_VSTORE_ABOUT_*`.

---

## 3. Matriz de trabajo por pestaña / área

Leyenda: **L** = extraer LAN (EN/ES/PT) · **H** = ayuda/tooltips · **B** = separar lógica de negocio · **G** = sección de la Guía · **V** = validación/seguridad · **T** = tests

### 3.1 Admin — menú principal

| Pestaña (modo/acción) | Archivo | Áreas nuevas / trabajo requerido | Tags |
|---|---|---|---|
| **Dashboard** (`statistics/custom`) | `admin_config_statistics.php` (526 l.) | LAN de paneles ("Monthly Revenue", "Orders this month", "Custom Chart", "No data…"); extraer consultas de totales a `Repository`; formato de moneda con `format_amount` (hoy `number_format` directo); nueva tarjeta "Estado del sistema" (composer/vendor, PDF plugin, VIES, pasarelas activas, cron) | L B G |
| **Sales / Orders** (`orders/list`, `edit`) | `admin_config_orders.php` (724 l.) | Pestañas `General / Details / Log` → LAN; estados (`vstore::getStatus`) vía LAN; `OrderService` para cambio de estado, reembolso, inventario y factura; acciones masivas con mensajes LAN; filtro `$_GET` saneado | L H B V G T |
| **Products** (`list/create/edit/grid`) | `admin_config_items.php` (843 l.) | 6 pestañas (`Basic`, `Images/Videos`, `Advanced`, `Variations`, `Reviews`, `Files`) → LAN; helptexts de código/peso/envío/clase fiscal/userclass; `InventoryService` para stock y variaciones; validación `item_code` único; ayuda contextual de imágenes/videos | L H B V G |
| **Product Variations** (`vars/*`) | `admin_config_item_vars.php` | Título "Price Modification", ayuda (+/-/%) a LAN; separar cálculo `getItemVarProperties` | L H B G |
| **Categories** (`cat/*`) | `admin_config_cat.php` | Pestañas Básico/Avanzado; ayudas vacías (`'help' => ''`) completar; `cat_sef` único | L H G |
| **Coupons** (`coupons/*`) | `admin_config_coupons.php` | Pestañas `General / Restrictions / Limits` → LAN; todos los `help`, placeholders, tipos (`Percentage`/`Fixed`); mensajes "Invalid coupon code!"; `CouponService` único punto de validación (mensajes por código); ayuda sobre orden descuento/IVA | L H B V G T |
| **Customers** (`customer/*`) | `admin_config_customer.php` | Pestañas `Billing / Shipping / Additional fields / Notes`; ~25 títulos de campo; ayudas VAT ID/Tax code; validación país↔VAT | L H G |
| **Preferences** (`main/prefs`) | `admin_config_pref.php` (555 l.) | 9 sub-pestañas: **General, Shipping, Tax, Emails, How to Order, Admin Area, Check-Out, Menu, Custom CSS** → LAN; ~45 prefs con title/help; `$taxClassesDefault` descripciones; sincronizar con `<pluginPrefs>` de `plugin.xml`; ver sección 3.3 | L H G V |
| **Email Templates** (`email/prefs`) | `admin_config_email_templates.php` | Un tab por tipo (`default/completed/cancelled/refunded`); botón "Reset template" y tooltip; asuntos de email por idioma (`_email.php`); lista de shortcodes disponibles | L H G |
| **Invoice Settings** (`invoice/prefs`) | `admin_config_invoice.php` | Pestañas `Prefs / Template / Preview`; defaults en inglés (`INVOICE`, finishing phrase) → LAN por idioma; ayuda de formato de fecha y numeración; `InvoiceService` | L H B G |
| **Payment Gateways** (`gateways/prefs`) | `admin_config_gateways.php` (254 l.) | Un tab por pasarela (PayPal, PayPal REST, Mollie, Stripe, Braintree, Bank transfer); títulos de credenciales, "Use Sandbox"; mensaje "Important" de dependencias; ayuda de cómo obtener claves; enmascarar secretos; botón "Probar conexión" (nuevo, opcional) | L H V G |
| **NEW — User Guide** (`main/guide`) | nuevo | 4 capas; secciones: Inicio rápido, Productos, Variaciones, Categorías, Cupones, Impuestos/IVA, Envíos, Pasarelas, Pedidos, Clientes, Emails, Facturas, Estadísticas, Solución de problemas (debug `e_DEBUG_VSTORE`), FAQ. Origen del contenido: `docs/user-manual.html` | G |
| **NEW — About** (`main/about`) | nuevo | `getPluginInfo()` desde `plugin.xml`, créditos **conservando autores originales** (CaMerOn, Achim Ennenbach, e107 Inc) + Kanonimpresor, botones Docs/Soporte/Donar/Changelog | — |
| **Sidebar Help** | todos los controladores | `renderHelp()` estándar (8 LAN) + ayuda contextual por pestaña | L |
| `admin_config_cart.php` | código muerto | Eliminar o reactivar (decisión: eliminar; el carrito se gestiona vía cron) | — |

### 3.2 Frontend y hooks

| Área | Archivo(s) | Trabajo | Tags |
|---|---|---|---|
| Catálogo / producto | `vstore.php`, `templates/vstore_template.php` (674 l.), shortcodes | Extraer textos a `*_front.php`; `Catalog` para estados/monedas; BS5 verificado | L B |
| Carrito / checkout (3 pasos) | `vstore.class.php` (`cartView`, `checkoutView`, `renderCustomerForm`, `shippingView`, `confirmOrderView`), `vstore_cart_template.php` | Extraer a `CartService`/`CheckoutService`; `Result` con códigos → mensajes LAN; separar validación de render | L B V T |
| Pasarelas (retorno) | `processGateway`, `loadGateway` | `PaymentService`; errores Mollie traducidos (`getMollieErrorMessage`) | L B V |
| Panel de cliente | `inc/vstore_dashboard.class.php`, `vstore_dashboard_template.php` (392 l.) | Desacoplar de `extends vstore`; textos LAN; consultas a `Repository`; URLs SEF | L B V |
| Emails | `vstore_email_template.php`, `vstore_order::emailCustomer` | Asuntos/pie por idioma (`_email.php`); separar construcción de envío | L B |
| Factura / PDF | `vstore_invoice_template.php`, `vstore_pdf.class.php` | Eliminar `define()` globales y `ob_start()` en include; LAN para etiquetas ("VAT ID", "Subtotal", "Total"); detección del plugin PDF con mensaje de ayuda | L B |
| Menús | `vstore_menu.php`, `vstore_cat_menu.php`, `vstore_menu_template.php` | LAN de captions; configuración vía prefs | L |
| `e_dashboard.php` | | LAN de paneles y gráficas; consultas a `Repository`; `SELECT *` por columnas explícitas | L B |
| `e_sitelink.php` | | **Bug**: tablas `e107_vstore_*` hardcodeadas → usar `#vstore_*`; LAN de nombres | L V |
| `e_cron.php` | | LAN de nombre/descripción; ejecutar `CartService::purgeAbandoned()` | L B |
| `e_search.php` / `e_url.php` | | LAN de etiquetas; sin cambios en rutas | L |
| `e_header.php` | | Quitar `define` de LAN de fallback (ya en `*_front.php`); mantener constante debug | L |
| `vstore_setup.php` | | Mensajes de upgrade; limpiar bloque INSERT comentado; `upgrade_required()` sin `include vstore.class.php` pesado | L B |
| `js/vstore.js` | | Verificar textos (sin literales detectados); pasar strings por `data-*` si aparecen | L |

### 3.3 Preferencias: sincronización pendiente
`plugin.xml` declara 5 prefs; `admin_config_pref.php` + gateways + invoice + email manejan **> 60**. Acción: inventario completo, definir valores por defecto en `<pluginPrefs>` (sin renombrar ni cambiar semántica) para que una instalación limpia sea coherente.

---

## 4. Plan por fases (ejecución)

Cada fase se entrega revisable y con `php -l` limpio; ninguna modifica BD ni prefs.

### Fase 0 — Inventario y línea base
1. Generar `docs/MIGRATION_PLAN.md` (resumen operativo de este documento).
2. Script de extracción de cadenas (PHP/AST o regex) → `docs/i18n/STRING_INVENTORY.csv` (archivo, línea, cadena, área, clave propuesta).
3. Capturar línea base: tamaños, `php -l`, `composer validate`, resultado de `docs/TESTING_GUIDE.md` (14 casos cupones/IVA).
4. Verificar `e107_tests/` para tests existentes del plugin; crear carpeta `tests/` del plugin con harness mínimo (PHPUnit) para servicios.
5. Confirmar versión de PHP/BS de los templates frontend.

### Fase 1 — Pack de estandarización (Kanonimpresor)
- `plugin.xml` (autor, copyright, `version=2.0.0`, `date`, `compatibility=2.3`, prefs completos).
- `getPluginInfo()`, `_resolveLans()`, `aboutPage()`, `guidePage()`, `renderHelp()`; entradas `main/about`, `main/guide`.
- Plantillas, batches (about real; guide stub), archivos `_admin_about` / `_admin_help` × EN/ES/PT.
- README ×3, `DEV_NOTES.md`, adaptar `USER_GUIDE_PATTERN.md`, `docs/images/README.md`, limpieza de MD sueltos en raíz → `docs/`.

### Fase 2 — Internacionalización total (admin)
Orden de menor a mayor riesgo: `cat` → `item_vars` → `customer` → `coupons` → `email` → `invoice` → `gateways` → `pref` → `items` → `orders` → `statistics`.
Por cada archivo: extraer títulos/help/tabs/mensajes → `<Lang>_admin.php`; ES y PT traducidos (no automáticos; revisión de longitudes de UI). Verificar que **ningún** literal EN queda (grep del checklist).

### Fase 3 — Internacionalización frontend y hooks
Plantillas (`vstore_template`, `cart`, `dashboard`, `email`, `invoice`, `menu`), shortcodes, `vstore_dashboard`, `vstore_order`, `e_*.php`, setup. Nuevo `<Lang>_email.php` lazy. Eliminar duplicados de `e_header.php`.

### Fase 4 — Separación de lógica de negocio (incremental, con fachada)
Orden recomendado (de más pura a más acoplada) y con test de regresión por servicio:
1. `Support/Catalog` (estados, monedas, gateways) — sin riesgo.
2. `TaxService` (+ VIES) → `CouponService` → `ShippingService` (funciones casi puras, ya corregidas en v1.1.0).
3. `Repository` (+ corregir `e_sitelink` y SQL concatenado).
4. `CartService` / `InventoryService`.
5. `OrderService` / `InvoiceService` (+ desacoplar `vstore_order`, `vstore_dashboard`, `vstore_pdf`).
6. `CheckoutService` / `PaymentService`; `render()` queda como orquestador fino.
En cada paso `vstore.class.php` mantiene el método original como delegador (`@deprecated`) → cero ruptura para temas/plugins externos.
Servicios devuelven `Result` (código + parámetros); la capa de presentación traduce con LAN. Prohibido `e107::getMessage()` y HTML dentro de `src/`.

### Fase 5 — Calidad, seguridad y compatibilidad
- Saneado de `$_GET/$_POST` (filtros y casting), SQL con `escape`/`int`, nonce/token en acciones admin y AJAX (`process_ajax`).
- Secretos de pasarelas enmascarados en admin; no volcarlos en logs.
- PHP 7.4–8.4: `php -l` multi-versión, revisión de `each`, claves indefinidas, null-safe.
- Composer: `composer audit`, revisión de `omnipay/braintree 4.0.x-dev` (rama dev), PSR-4 para `src/`.
- Pruebas: 8 escenarios cupones + 6 IVA (docs/TESTING_GUIDE.md) automatizados contra servicios; checkout manual E2E por pasarela en sandbox.

### Fase 6 — Documentación y release
- `CHANGELOG.md` v2.0.0 (Added/Changed/Deprecated), `docs/user-manual.html` regenerado desde la Guía, capturas.
- Etiquetado y release GitHub (`e107-Kanonimpresor/vstore`), "About" del repo (descripción, web, topics).
- Resumen para actualizar `.github/copilot-instructions.md` con la nueva arquitectura.

---

## 5. Criterios de aceptación (checklist de validación)

- [ ] `php -l` limpio en todos los `.php` (excl. `vendor/`).
- [ ] `grep "function __call" shortcodes/` → 0.
- [ ] 0 literales de UI en inglés en `inc/`, `templates/`, `shortcodes/`, `e_*.php` (excepto códigos técnicos).
- [ ] Mismo conjunto de constantes en `English`, `Spanish`, `Portuguese` (script de paridad).
- [ ] `plugin.xml` ↔ `$prefs` sincronizados; versión y fecha actualizadas juntas.
- [ ] `src/` sin `e107::getMessage`, sin HTML, sin `$_GET/$_POST`.
- [ ] `vstore.class.php` < 1.500 líneas (fachada + orquestación).
- [ ] 14 casos de test (cupones/IVA) en verde.
- [ ] Pestañas About y Guide operativas; sidebar con caption, versión, tagline, tip, 3 botones y "More info »".
- [ ] Instalación limpia y actualización desde v1.1.0 sin errores ni cambios de BD.

---

## 6. Riesgos y decisiones abiertas

| # | Tema | Recomendación |
|---|---|---|
| 1 | Versión objetivo | `2.0.0` (nueva arquitectura interna; API pública compatible) |
| 2 | Autoría | Unificar metadatos Kanonimpresor **y** mantener créditos de CaMerOn / Achim Ennenbach / e107 Inc en About y README (licencia original GPL; el pack usa GPL-3.0 → confirmar compatibilidad de relicenciado) |
| 3 | Plugin `pdf` externo | Mantener dependencia con aviso claro; evaluar integrar TCPDF por Composer en v2.1 |
| 4 | `omnipay/braintree` rama dev | Evaluar `^5` o retirar de `require` si no se usa |
| 5 | Cambios visuales BS | Mantener BS3 admin / BS5 front estrictamente |
| 6 | Refactor de `render()` | Mayor riesgo: hacerlo al final y con E2E manual por pasarela |
| 7 | CoinPayments / Stripe / Braintree | README las anuncia, pero `$gateways` solo lista PayPal, Mollie y Bank Transfer → alinear documentación o implementación |

---

## 7. Siguiente paso propuesto
Aprobar este plan y comenzar por **Fase 0 + Fase 1** (pack Kanonimpresor: `plugin.xml`, About, Guide, `renderHelp`, docs) porque es la base de LAN y navegación sobre la que se apoyan las fases 2-4.

---

## 8. Registro de avance

Estado: ✅ completada · 🟡 parcial · ⏳ pendiente

| Fase | Estado | Fecha |
|---|---|---|
| 0 — Inventario y línea base | 🟡 | 2026-10-15 |
| 1 — Pack Kanonimpresor | ✅ | 2026-10-15 |
| 2 — i18n admin | 🟡 | 2026-10-15 |
| 3 — i18n frontend y hooks | 🟡 | 2026-10-15 |
| 4 — Lógica de negocio | 🟡 | 2026-10-15 |
| 5 — Calidad y seguridad | 🟡 | 2026-10-16 |
| 6 — Documentación y release | ⏳ | |

### 8.1 Fase 0 — Inventario y línea base (2026-10-15)

**Hecho**
- Línea base: 45 archivos PHP (sin `vendor/`), `php -l` sin errores (PHP 8.2.26).
- `docs/i18n/STRING_INVENTORY.csv`: 987 cadenas candidatas (archivo, línea, tipo, texto) generadas con el tokenizador de PHP. Hay ruido (CSS, SQL, claves técnicas) que se depura en la Fase 2. Mayor concentración: `vstore.class.php` (258), `admin_config_pref.php` (85), `vstore_shortcodes.php` (79), `admin_config_items.php` (57), `admin_config_orders.php` (53), `admin_config_invoice.php` (52).
- Confirmado en el núcleo de e107: la página `main/about` se resuelve a `AboutPage()`, `renderHelp()` se invoca en el constructor de `e_admin_ui`, `e107::lan('vstore','admin_about',true)` carga `languages/English/English_admin_about.php`, y `e107::getTemplate('vstore','vstore_about')` usa `$VSTORE_ABOUT_TEMPLATE`.
- `MIGRATION_PLAN.md` no se crea aparte: este documento cumple ese papel.

**Pendiente de la fase**
- `composer validate` y reejecución de los 14 casos de `docs/TESTING_GUIDE.md`.
- Carpeta `tests/` con harness PHPUnit para servicios (se crea al empezar la Fase 4).
- Verificar versión de Bootstrap en las plantillas del frontend.

### 8.2 Fase 1 — Pack de estandarización Kanonimpresor (2026-10-15)

**Hecho**
- `plugin.xml`: versión `2.0.0`, fecha `2026-10-15`, autor y copyright Kanonimpresor, defaults en `<pluginPrefs>` (`amount_format`, `show_outofstock`, `tax_calculate`, `tax_check_vat`, `menu_item_count`; solo aplican en instalaciones nuevas). XML validado.
- `inc/vstore_admin_common.php`: trait con `getPluginInfo()` (lee `plugin.xml` directamente), `_resolveLans()`, `AboutPage()`, `GuidePage()` y `renderHelp()`. Aplicado a los 11 controladores de `inc/admin_config_*.php`.
- `admin_config.php`: carga del trait y entradas de menú `main/guide` y `main/about`.
- Plantillas `vstore_about_template.php` (cuerpo único) y `vstore_guide_template.php` (8 secciones: inicio rápido, catálogo, cupones, impuestos, envíos, pagos, pedidos, solución de problemas).
- Shortcodes: `vstore_about_shortcodes.php` (lógica real, valores escapados) y `vstore_guide_shortcodes.php` (stub; sin `__call()`).
- Idiomas EN/ES/PT: `_admin_about.php` (16 constantes), `_admin_help.php` (72) y `_admin.php` ampliado de 12 a 32 constantes (menú, 8 del widget y 11 consejos contextuales, uno por modo). Paridad verificada: mismos nombres en los tres idiomas. Los 70 tokens `{LAN_*}` de las plantillas están definidos y cada `{VSTORE_ABOUT_*}` tiene su método.
- `renderHelp()` vacíos de `admin_config_coupons.php` y `admin_config_item_vars.php` eliminados (anulaban el del trait).
- README ×3: badges unificados, bloque de autor, créditos con autores originales, enlaces a `e107-Kanonimpresor/vstore` y bloque de copyright.
- `CHANGELOG.md`: entrada `[2.0.0] - Unreleased`.
- `DEV_NOTES.md` creado y `docs/architecture/USER_GUIDE_PATTERN.md` reescrito para Vstore (ver incidencia).
- `php -l` sobre 56 archivos PHP: sin errores.

**Desviaciones respecto al prompt de migración**
1. El prompt coloca `getPluginInfo()`, `aboutPage()` y `renderHelp()` en una sola clase `e_admin_ui`. En Vstore hay 11 controladores en `inc/`, por lo que se centralizó en un trait.
2. `renderHelp()` devuelve `['caption' => ..., 'text' => ...]` y no un panel HTML: `sc_adminui_help()` del núcleo accede a `$tmp['caption']` y `$tmp['text']`.
3. Los métodos de página se llaman `AboutPage()` / `GuidePage()` (convención `{Acción}Page` de e107).
4. Se añadieron consejos contextuales por modo (`LAN_VSTORE_HELP_TIP_<MODO>`), no previstos en el prompt.

**Incidencia**
- `docs/architecture/USER_GUIDE_PATTERN.md` era un archivo **sin seguimiento en git** (copia de sitedown_styles, 350 líneas) y se eliminó con `rm` antes de comprobar que no estaba versionado. No es recuperable en local; el original sigue siendo el de sitedown_styles. Se creó la versión de Vstore.

**Pendiente de la fase**
- ~~Comprobación en navegador de las pestañas About/Guide y del widget~~ Verificado por el mantenedor: las pestañas funcionan.
- ~~Archivos sueltos de la raíz~~ Movidos a `docs/` (`GIT_UPDATE_GUIDE.md`, `QUICK_RELEASE_GUIDE.md`, `RELEASE_NOTES_v1.1.0.md` con `git mv`; `CREATE_RELEASE_GUIDE.md`, `GUIA_DESARROLLO_PLUGINS_E107.md`, `UPLOAD_SUCCESS.md` sin seguimiento).
- ~~Sección "What's new in v2.0.0" de los README~~ Redactada en los tres README (refleja solo lo ya entregado).
- `docs/images/` contiene capturas y un `README.md` copiados de sitedown_styles (sin seguimiento en git). Sustituirlos por capturas de Vstore (Fase 6).
- Crear/actualizar el repositorio y el "About" de GitHub (Fase 6).

### 8.3 Fase 2 — i18n del área de administración (2026-10-15)

**Hecho**
- Arquitectura: un archivo de idioma por área en formato array (`<Lang>_admin_<area>.php`), con respaldo automático a inglés para cualquier idioma sin traducción. Cada controlador carga el suyo al inicio del archivo (antes de declarar la clase). Palabras compartidas en `admin_common`, cargado por `admin_config.php`.
- Archivos nuevos (EN/ES/PT): `admin_common` (23 constantes), `admin_catalog` (categorías y variaciones, 15), `admin_customer` (17), `admin_coupons` (27), `admin_email` (26), `admin_invoice` (37), `admin_gateways` (14), `admin_pref` (79), `admin_products` (38), `admin_orders` (37), `admin_statistics` (20).
- Controladores actualizados: `cat`, `item_vars`, `customer`, `coupons`, `email_templates`, `invoice`, `gateways`, `pref`, `items`, `orders` y `statistics`. Ya no quedan títulos, ayudas, nombres de pestañas, placeholders, mensajes ni textos de JS en inglés fuera de bloques comentados.
- Ayudas completadas donde estaban vacías: categorías (nombre, descripción, SEF, padre, imagen, orden), caption de tienda, VAT ID y notas internas de clientes.
- `<Lang>_global.php`: estados de pedido, tipos de email, unidades de peso y monedas. `vstore::getStatus()`, `getEmailTypes()`, `getCurrencies()`, `getCurrencyTitle()` y `weightUnits()` devuelven texto traducido.
- Corrección de lógica descubierta al traducir: `vstore_order` seleccionaba la plantilla de email con `strtolower(getStatus())`. Con etiquetas traducidas se habría roto, por lo que se añadió `vstore::getStatusSlug()` (etiqueta en inglés para lógica).
- Erratas corregidas en los textos ("Payed" → "Paid", "onvoices", "dashoard", "es defined", "seam").
- Validación: `php -l` sin errores en todos los PHP; paridad de nombres de constantes entre EN/ES/PT en todos los archivos array y en `_admin`, `_admin_about`, `_admin_help`, `_global`, `_front`; ninguna constante `LAN_VSTORE_*` usada en código queda sin definir.

**Pendiente de la fase**
- Comprobación visual en el navegador de cada pestaña en ES y PT (listas, edición, filtros, botones de pedido, JS de confirmación). No ejecutada.
- Textos de `vstore.class.php` que se muestran en el admin (por ejemplo `refundPurchase`, mensajes de `process_ajax`, log de pedidos `addToOrderLog`): Fase 3/4.
- `e_dashboard.php` (gráficos y panel de últimos pedidos, usados en la pestaña Dashboard): Fase 3.
- Títulos autogenerados de parámetros de pasarelas Omnipay (se generan desde nombres de parámetros en inglés) y nombres comerciales (PayPal, Mollie, métodos Mollie): se mantienen.
- `inc/admin_config_cart.php` (código muerto) y bloques comentados con textos en inglés (`admin_config_gateways.php`, `admin_config_email_templates.php`): decidir su eliminación en la Fase 5.
- Corrección al plan: la tabla de riesgos indica que Stripe, Braintree y CoinPayments no están en `$gateways`. Es inexacto: `admin_config_gateways.php` los construye dinámicamente desde los paquetes Omnipay instalados (`getGatewayPackageList()`). El riesgo real es que la Guía de Usuario solo describe PayPal, Mollie y transferencia; se ampliará en la Fase 6.

### 8.4 Fase 3 — i18n del frontend y de los hooks (2026-10-15)

**Hecho**
- Archivos de idioma nuevos (EN/ES/PT, formato array con respaldo a inglés): `front_shop` (tienda y carrito, `LAN_VSTORE_FR_*`), `front_dashboard` (`LAN_VSTORE_DB_*`), `front_mail` (emails, factura y log de pedido: `LAN_VSTORE_MAIL_*`, `_INV_*`, `_ORD_*`), `front_msg` (mensajes del sistema `LAN_VSTORE_MSG_*` y textos de shortcodes `LAN_VSTORE_SC_*`) y `hooks` (`LAN_VSTORE_HOOK_*`). `front` pasa a formato array y `global` incorpora estados, tipos de email, unidades de peso y monedas.
- Hooks traducidos: `e_cron`, `e_search`, `e_sitelink`, `e_dashboard`, `e_header`, `vstore_menu.php`, `vstore_cat_menu.php`.
- Plantillas traducidas (`{LAN=...}`): carrito, tienda, menú, panel del cliente, factura y email (esta última reescrita con constantes y sigue siendo editable desde el admin).
- Clases: `vstore_dashboard`, `vstore_order`, `vstore_pdf`; `vstore.class.php` (mensajes de usuario, cupones, validación de cliente, descargas, factura/PDF, pasarelas, pestañas de producto, migas de pan, textos del checkout) y `shortcodes/batch/vstore_shortcodes.php` (direcciones, acciones de pedido, estado de pago, descargas, precios, peso, placeholders de formularios, botones del carrito, cupón).
- Los mensajes de depuración (`addDebug`, `trigger_error`) permanecen en inglés a propósito.
- Validación: `php -l` sin errores en todo el plugin; paridad de constantes entre EN/ES/PT en todos los archivos; 647 constantes `LAN_VSTORE_*` usadas en el código, todas definidas (702 definidas en total).

**Errores corregidos al traducir**
1. `e_sitelink.php` usaba el prefijo de tabla `e107_` fijo.
2. `vstore_menu.php` usaba una variable no definida (`$vstore_prefs`).
3. Etiquetas Pagado/Abierto intercambiadas en el gráfico de `e_dashboard.php`.
4. "Payed" → "Paid" y corrección de `for` en etiquetas de formularios.
5. Bloque duplicado de comerciantes/dirección en la plantilla de email por defecto.
6. `vstore::getStatusSlug()` evita que las etiquetas traducidas rompan la lógica (ver Fase 2).

**Pendiente de la fase**
- Comprobación en navegador (ES y PT): catálogo, producto, carrito, checkout, pago, panel del cliente, emails y PDF. No ejecutada.
- Plantillas sobrescritas por el tema (`override`) conservan su texto en inglés.
- Textos estáticos que se mantienen: nombres de pasarelas y métodos Mollie, títulos autogenerados de Omnipay.
- Para la Fase 5: `vstore.class.php` construye filtros SQL con `sprintf('coupon_code="%s"', ...)` a partir de datos de usuario (cupón); revisar el saneado o pasar a consultas escapadas.

### 8.5 Fase 4 — Lógica de negocio, primera parte (2026-10-15)

**Hecho**
- `src/` con autoload PSR-4 propio (`src/autoload.php`, independiente de `vendor/`) y compatible con PHP 7.4 (plataforma de `composer.json`).
- `Support/Catalog` (estados, monedas, pasarelas, métodos Mollie, campos, clases de impuesto) y `Support/Result` (código + parámetros; el código equivale a `LAN_VSTORE_MSG_<CODIGO>`). Las propiedades estáticas de `vstore` ahora se inicializan desde las constantes de `Catalog`.
- `Service/TaxService`, `VatService`, `CouponService`, `ShippingService` y `Repository/OrderCouponUsageRepository`. Dependencias inyectables: tasas de IVA, validador VIES, repositorio de uso de cupones, reloj y logger; los servicios no usan e107, HTML, mensajes ni superglobales.
- `vstore.class.php` (de 5411 a ~4810 líneas) conserva `calcCouponAmount`, `getTaxRate`, `calcTaxAmount`, `calcShippingCost`, `validateVATID`, `getCouponUsageCount*` como delegadores con la misma firma; `addResultMessage()` traduce el `Result`. `getTaxClass()` se eliminó (era privado) y su lógica está en `TaxService::mapClass()`.
- `tests/run.php`: 61 comprobaciones sin base de datos ni red (8 escenarios de cupones y 6 de IVA de `TESTING_GUIDE.md` más VAT ID, envío tarifado y catálogo). Resultado: 61/61.
- Prueba de humo de la fachada cargando e107 por CLI: envío, impuestos, cupón, mensaje de cupón inactivo, estados y ruta intra-UE (ES→FR 20 %, DE `reduced2`→`reduced` 7 %).

**Error corregido**
- `getTaxRate()` creaba `new \Ibericode\Vat\Rates()` sin el argumento obligatorio: cualquier cliente de otro país de la UE sin VAT ID provocaba un `ArgumentCountError` en el checkout. Además `getTaxClass()` usaba `new Rates('', 0)`, que consulta el servicio remoto en cada llamada. Ahora las tasas se guardan en `e_CACHE/vstore_vat_rates.cache` (12 h).

**Pendiente de la fase** (orden del plan)
- `Repository` general y SQL por concatenación (`e_sitelink`, `e_dashboard`, `vstore_dashboard`).
- `CartService` / inventario, `OrderService` / `InvoiceService`, `CheckoutService` / `PaymentService` y `render()` como orquestador.
- Quitar `extends vstore` de `vstore_order` y `vstore_dashboard`.
- Si no hay red y la caché de tasas está vacía, el cálculo de IVA intra-UE devuelve el error `TAX_CLASS`; valorar una tabla de respaldo.

### 8.6 Fase 4 — Lógica de negocio, segunda parte (2026-10-15)

**Hecho**
- `Support/ItemVars` (formato `id1,id2|valor1,valor2` y normalización de variaciones).
- `Service/InventoryService` (`available()` y `reduce()`), `ItemVarTypeService` (variaciones que controlan stock, con caché por petición), `UserclassService` (clases de usuario que recibe el cliente) e `InvoiceNumberService` (siguiente número, formato con prefijo y estados que admiten factura).
- `Repository/ItemRepository`, `ItemVarRepository` y `OrderRepository` (con interfaces para los dos primeros), de modo que los servicios se prueban con repositorios falsos.
- `vstore.class.php` delega `getItemInventory`, `updateInventory`, `isInventoryTrackingVar`, `filterItemVarsByType`, `item_vars_toDB/toArray`, `fixItemVarArray`, `setCustomerUserclass`, `getCustomerUserclass`, `getNextInvoiceNr`, `formatInvoiceNr`, `getOrderIdFromInvoiceNr` y `validInvoiceOrderState`. Misma firma pública; se eliminó la propiedad estática `$itemVarsTypes`.
- `tests/run.php`: 101 comprobaciones (40 nuevas: variaciones, inventario con una y dos variaciones, clases de usuario, numeración de facturas). Resultado 101/101.
- Prueba de humo con la base de datos real: stock, ilimitado, producto inexistente, siguiente factura, prefijo, reducción de 2 unidades en el producto 6 y restauración.

**Diferencias de comportamiento (intencionadas)**
- `reduce()` no escribe `item_vars_inventory` cuando el inventario de variaciones está vacío (antes guardaba `[]`) y ya no puede fallar con `array_values(null)` si `item_vars` es incorrecto.
- `getCustomerUserclass()` con clases por producto hace una consulta por producto (antes también) y devuelve cada clase una sola vez.
- `getOrderIdFromInvoiceNr()` devuelve `0` en lugar de `false` si la factura no existe.

**Pendiente de la fase**
- `vstore_order` y `vstore_dashboard` siguen heredando de `vstore`: usan `loadGateway()`, `pathToInvoicePdf()` y propiedades (`pref`, `get`, `sc`, `perPage`). Hay que extraer antes `PaymentService` e `InvoiceService`.
- `CartService` (`updateCart`, `addToCart`, `prepareCheckoutData`), `OrderService` (`saveTransaction`), `CheckoutService` (`validateCustomerData`) y `render()` como orquestador.
- SQL por concatenación de `e_sitelink`, `e_dashboard` y `vstore_dashboard`.

### 8.7 Fase 4 — Lógica de negocio, tercera parte (2026-10-15)

**Hecho**
- `Service/PaymentService`: crea la pasarela (Mollie, transferencia bancaria o paquete Omnipay) solo a partir de las preferencias; la fábrica de Omnipay se inyecta. Devuelve `Result` con los códigos `GW_CONFIG`, `GW_LOAD` y `GW_PARAM`.
- `Service/InvoiceService`: título de factura con número y prefijo, localización del PDF existente y control de acceso/estado (`ACCESS_DENIED`, `INVOICE_STATE`).
- `Support/ResultTranslator`: traduce un `Result` (admite `%s` y `[x]`); `addResultMessage()` y `renderInvoice()` lo usan.
- `vstore::createGateway()` (estático, sin efectos sobre el estado de la tienda) y `vstore::pathToInvoicePdf()` (ahora estático). `loadGateway()` sigue existiendo y solo añade el cambio de modo.
- **`vstore_order` y `vstore_dashboard` ya no heredan de `vstore`.** `vstore_order` llama a `vstore::` para etiquetas traducidas y a los servicios para el resto; `vstore_dashboard` declara sus propias propiedades (`sc`, `get`, `pref`, `perPage`).
- Nueva constante de idioma `LAN_VSTORE_MSG_GW_PARAM` (EN/ES/PT). Antes ese mensaje estaba en inglés dentro del código.
- `tests/run.php`: 123 comprobaciones (22 nuevas: pasarelas, factura, traductor). Resultado 123/123.
- Prueba de humo con e107 real: Mollie en modo test con clave, transferencia sin detalles, pasarela inexistente, ruta de la factura PDF del pedido 1, `vstore_order` y `vstore_dashboard` (`instanceof vstore` = false).

**Errores corregidos al extraer**
1. En `vstore.class.php` `Exception` es `Ibericode\Vat\Exception` (alias `use`), por lo que `loadGateway()` no capturaba las excepciones de Omnipay y una clase de pasarela inexistente producía un error fatal. Ahora se captura `\Exception`.
2. El reembolso desde el admin (`vstore_order::refundOrder`) llamaba a `loadGateway()` sin haber ejecutado `initPrefs()`, de modo que las claves de Mollie (`mollie_api_key_*`) y el arreglo `gateways` no estaban disponibles. `PaymentService` los obtiene directamente de las preferencias.
3. En caso de error de configuración el código anterior volcaba con `print_r` todo el arreglo de pasarelas (con sus claves) al registro de errores; ahora solo se registra el nombre de la pasarela.

**Pendiente de la fase**
- `CartService` (`updateCart`, `addToCart`, `prepareCheckoutData`), `OrderService` (`saveTransaction`, `saveCustomer`), `CheckoutService` (`validateCustomerData`, `renderCustomerForm`) y `render()` como orquestador.
- `invoiceToPdf()` y `downloadInvoicePdf()` siguen en la fachada (dependen de TCPDF y de cabeceras HTTP).
- SQL por concatenación de `e_sitelink`, `e_dashboard` y `vstore_dashboard`.
- Comprobar en el navegador: reembolso desde el admin con Mollie, descarga de factura PDF y panel del cliente.

### 8.8 Correcciones tras las primeras pruebas (2026-10-15)

| Hallazgo | Causa | Corrección |
|---|---|---|
| `Undefined constant LAN_VSTORE_003` | `English_front.php` pasó a formato array y e107 ya no lo carga solo | `vstore.class.php` y `vstore_shortcodes.php` lo cargan con `e107::lan('vstore', false, true)` |
| Alerta "El cupón no está disponible" sin cupón | `(array) ''` no es vacío al delegar en `CouponService` | La fachada devuelve `0.0` si el cupón o el artículo no son arrays |
| Cupón y subtotal ausentes en el pedido del cliente | `{ORDER_COUPON}` solo leía `cart_coupon`; `order_items` llegaba sin deserializar | `getCouponData()` (carrito o pedido) y deserialización en `sub_total` |
| `{LAN=LAN_VSTORE_FR_TAX}` visible | Las filas de impuesto/cupón pasan por `lanVars()`, que no resuelve `{LAN=}` | Constantes concatenadas en esas filas |
| Error cURL 60 con Mollie | `phpForApache.ini` sin `curl.cainfo` / `openssl.cafile` | Activados en el php.ini de Apache (entorno local) |
| El recálculo del carrito vuelve a 1 unidad | El campo del cupón (con el código activo) se envía en el mismo formulario y `process()` omitía la actualización si venía relleno | Se actualizan siempre las cantidades enviadas |
| La descarga no funciona (vídeo asociado) | El gestor de medios de e107 guardó `media_url` truncado en el primer carácter no ASCII del nombre del archivo; el valor guardado no apunta a ningún archivo y e107 redirige al inicio sin avisar | Mensaje traducido al cliente (`LAN_VSTORE_MSG_DL_NOT_FOUND`), aviso en el admin al guardar el producto (`LAN_VSTORE_ADMIN_PROD_DOWNLOAD_MISSING`) y `vstore::resolveDownloadPath()`. El archivo debe renombrarse sin acentos y volver a importarse |
| Reembolso sin fecha en el admin | `order_refund_date` solo la asignaba una rama que llamaba a `vstore::refundPurchase()`, un método que no existe; el reembolso por los botones (`refundOrder()` → `setOrderStatus('R')`) no la guardaba | `setOrderStatus('R')` guarda la fecha; la rama del formulario usa `refundOrder()` y conserva el registro. Pedido 2 completado con la fecha de su registro (2026-10-15 22:55:44) |
| Factura PDF inaccesible en el panel del cliente | Las plantillas del panel solo mostraban el número de factura sin enlace | `{ORDER_DATA: order_invoice_link}` (lista, detalle y descargas), entrada "Factura" en el menú de acciones y botón `{ORDER_ACTIONS: invoice}` en el detalle; solo para estados N, P y C |
| El error cURL 60 persiste tras activar el certificado | Apache carga PHP 8.2.30 (`LoadModule` en `httpd.conf`) y se editó el `phpForApache.ini` de 8.2.26 | Directivas activadas en `php8.2.30/phpForApache.ini` (enlazado como `apache/…/bin/php.ini`). Hay que repetirlo si se cambia de versión de PHP |
| Mollie "Invalid Authorization header", PayPal "You do not have permissions" | Las claves de Mollie (modo de prueba) y las credenciales de PayPal Express están vacías en las preferencias | Configuración pendiente del administrador. El código ya no llama a una pasarela con credenciales vacías: muestra "no está configurada" y, a los administradores, los campos que faltan |
| Stripe: fatal `The source parameter is required` | La excepción de Omnipay no se capturaba (alias de `Exception`) y el checkout no incluye formulario de tarjeta ni envía `stripeToken` | La excepción se captura y se registra. Stripe con tarjeta requiere un formulario con Stripe.js que no está implementado |

**Verificación:** prueba de humo con e107 real (cantidad 3 con el campo del cupón relleno, tope por stock, `cartQty` vacío, rutas de descarga válidas e inválidas, ruta fuera de los directorios permitidos) y `php tests/run.php` 123/123. Falta repetirlo en el navegador.

**Mejora pedida tras las pruebas: cantidad en la ficha de producto.** Nuevo shortcode `{ITEM_QTY}` (campo numérico con `max` igual al stock cuando el stock no se controla por variación; no se muestra si está agotado), enviado por `js/vstore.js` como `qty`. `addToCart($id, $itemvars, $qty)`: cantidad entre 1 y 9999, limitada al stock, suma a la línea existente y devuelve `false` con aviso si la línea ya está en el máximo. También se tradujo el texto fijo "In Stock" de `{ITEM_AVAILABILITY}`. Las plantillas sobrescritas por un tema deben añadir `{ITEM_QTY}` antes de `{ITEM_ADDTOCART}`. Prueba de humo: línea nueva, suma, valor por defecto, tope por stock, valores no numéricos o negativos; HTML del shortcode con stock limitado, ilimitado, por variación y agotado.

**Mejora pedida antes de pasar a producción: teléfono y correo del cliente.** (1) La factura (`vstore_invoice_template.php`, con envoltorios que ocultan la línea si el dato está vacío) y el detalle del pedido del cliente (`{ORDER_DATA: order_billing_contact}`, nueva clave de `sc_order_data`, valores escapados) muestran el correo y el teléfono de facturación; la vista previa de la factura del administrador incluye un teléfono de ejemplo. (2) Mollie recibe `billingAddress` con `email` y `phone` en formato E.164: `Support/PhoneNumber::toE164()` normaliza lo escrito por el cliente (`+351…`, `00351…` o nacional con el país de facturación; si no se puede asegurar el número no se envía) y las nuevas clases `Payment/MollieGateway` y `Payment/MolliePurchaseRequest` amplían la pasarela de Omnipay, que sólo admitía el `billingEmail` obsoleto. `PaymentService` crea ahora la subclase. (3) Al elegir MB WAY, el teléfono es obligatorio: `required` activado desde `vstore.js` y validación en servidor (`LAN_VSTORE_MSG_MBWAY_PHONE`, EN/ES/PT) que exige un móvil portugués (`+3519` + 8 dígitos). Campo teléfono ampliado a 20 caracteres. Pruebas: `php tests/run.php` 190/190 (normalización de números, móvil portugués, `billingAddress` con y sin teléfono o correo, creación real con `Omnipay::create`). Falta verificarlo en el navegador y con una clave real de Mollie.

**Fallo en el primer pago real con MB WAY ("Erro genérico" junto al aviso de éxito).** Diagnóstico con la API de Mollie (solo lectura) y la base de datos: el pago de 1,11 EUR figuraba como `paid` en Mollie pero no existía ningún pedido, y los de Multibanco/MB WAY anteriores seguían `pending`. Causa: al volver al sitio el pago aún estaba pendiente (el cliente aprueba en la app después), `isSuccessful()` era falso, no se creaba pedido y se mostraba `LAN_VSTORE_MSG_GENERIC_ERROR` (el aviso de éxito era el de "continuar comprando"); además, con un pago pagado el mensaje de éxito salía de interpretar el JSON de Mollie y también era "Erro genérico". Corrección: `Support/MolliePayment` clasifica el estado; un pago pendiente registra un pedido Nuevo con pago incompleto y un mensaje claro; pagado, cancelado, caducado y fallido tienen su texto. Nuevo webhook (`e_url.php` ruta `webhook`, `vstore.php`, `vstore::handleMollieWebhook()`, `OrderRepository::orderIdFromMollieTransaction()`): consulta el estado real a Mollie (el `id` recibido no se usa como dato fiable) y pasa el pedido a Procesando/pagado o a Cancelado, con registro del pedido, correo al cliente y clase de usuario. La URL de notificación solo se envía si el sitio es accesible desde internet (`MolliePayment::isPublicUrl`), porque Mollie rechaza URLs locales. Limitaciones: si el cliente nunca vuelve al sitio no se crea el pedido (queda una entrada en el registro de administración), y los pedidos cancelados por webhook no devuelven el stock, igual que la cancelación manual. Pruebas: `php tests/run.php` 196/196 y humo con e107 (ruta del webhook y manejador con el pago real de 1,11 EUR, que no tiene pedido: se registra el aviso). Falta comprobar en producción con un pago real.

**Botón "Comprobar pago en Mollie".** El pago MB WAY de prueba en localhost quedó pagado en Mollie con el pedido en "incompleto" porque Mollie no puede avisar a `localhost` (el pago se creó con `webhookUrl` vacío). `handleMollieWebhook()` se apoya ahora en `syncMolliePayment($paymentId)` (estados `invalid`, `error`, `no_order`, `unchanged`, `updated`), que también usa la nueva acción `checkpayment` de la petición AJAX de pedidos (solo administradores) y el botón del pedido en el administrador (visible en pedidos Mollie Nuevos con pago incompleto). Textos EN/ES/PT. Verificado con e107 real: pedido ya pagado (`unchanged`), pago pagado sin pedido (`no_order`), id inexistente (`error`) e id inválido (`invalid`). No se ha probado el cambio efectivo `updated` con un pedido pendiente ni el clic del botón en el navegador; la regla de cambio está cubierta por `tests/run.php`.

### 8.9 Fase 4 — Lógica de negocio, cuarta parte (2026-10-16)

**Hecho**
- `Service/CartService` (`add`, `updateQuantities`, `remove`) con `Repository/CartRepository` (+ interfaz). Todas las operaciones sobre líneas quedan limitadas al carrito del visitante.
- `Service/CheckoutService`: `calculate()` (cupón, impuesto por tasa, envío y totales; devuelve también los mensajes) y `validateCustomer()` (datos de facturación y envío). `Service/OrderService`: referencia de pedido, campos adicionales y registro del cliente (`Repository/CustomerRepository` + interfaz). `Repository/CouponRepository`: búsqueda del cupón con el código escapado.
- `vstore.class.php` (4092 líneas; eran 5411): `prepareCheckoutData()`, `addToCart()`, `updateCart()`, `validateCustomerData()`, `getOrderRef()` y `saveCustomer()` delegan. La fachada conserva solo lo que depende de e107: lectura del cupón enviado, retirada de artículos inactivos, precio de las variaciones y mensajes. Se eliminaron `calcNetPrice()`, `checkVAT_ID()`, `fixItemVarArray()` y `getTaxClass()` (privados, sin uso).
- `tests/run.php`: 171 comprobaciones (48 nuevas: carrito con repositorio falso, totales con cupón/impuestos/envío, validación, referencias y cliente). 171/171.
- Prueba de humo con e107 real (filas de carrito temporales, ya borradas): alta y cambio de cantidad con tope por stock, línea de otro carrito intacta, totales con `CUPONDEMO` (subtotal 447, cupón −44,70, envío 8, IVA 23 % = 94,37, total 504,67, cifras coherentes), nueva tasación de datos de checkout, intento de inyección en el cupón, validación de cliente y referencia.

**Errores de seguridad y lógica corregidos** (se adelantan de la Fase 5)
1. `cart_session` se concatenaba en SQL sin escapar y el valor viene de una cookie: inyección SQL. El identificador solo se acepta si cumple `^[A-Za-z0-9_,-]{8,128}$`; si no, se genera uno nuevo. Las consultas del carrito escapan el valor.
2. `resetCart()` borraba `cart_id=<cookie>`: comparaba un número con el texto de la cookie y podía borrar la línea de otro visitante. Ahora borra por `cart_session`.
3. El formulario del carrito modificaba o eliminaba líneas de cualquier carrito conociendo su `cart_id`. Ahora exige que la línea pertenezca al carrito actual.
4. El código del cupón enviado por el usuario no se escapaba (se cerró el punto abierto en 8.4).
5. `frm` (desplazamiento de las categorías) y `page` (panel del cliente) llegaban sin convertir a entero a `LIMIT`.
6. División por cero en el IVA del envío cuando todos los artículos del carrito valen 0.
7. Al validar un campo adicional obligatorio vacío, el aviso mostraba `Array` en lugar de la etiqueta del idioma actual.

**Pendiente de la fase**
- `render()` como orquestador y las vistas del checkout (`checkoutView`, `shippingView`, `confirmOrderView`).
- `processGateway()` y `saveTransaction()` (orquestan sesión, correo, PDF y pasarela).
- `invoiceToPdf()` y `downloadInvoicePdf()`.
- SQL por concatenación de `e_sitelink`, `e_dashboard` y `vstore_dashboard` (los dos últimos con valores ya convertidos a entero).
- Comprobar en el navegador: carrito (añadir, cambiar cantidad, eliminar), cupón, checkout completo y panel del cliente.

### 8.10 Fase 5 — Seguridad y compatibilidad, primera parte (2026-10-16)

Auditoría previa a producción.

| Hallazgo | Corrección |
|---|---|
| Datos del cliente (dirección, notas, campos adicionales, usuario) impresos sin escapar en el administrador de pedidos y de clientes, y en los shortcodes de dirección, pedido y confirmación (el alta solo aplicaba `strip_tags`) | `htmlspecialchars` en `format_address()`, en la rama por defecto de `sc_order_data`, en `sc_confirm_field`, en `order_items_short`/`order_downloads`/`order_log` y en `admin_config_orders.php` y `admin_config_customer.php` (helpers `esc()`/`formatContact()`). Se quitó además un `echo json_last_error_msg()` de depuración en `order_pay_rawdata` |
| El registro de administración guardaba en claro las claves de Mollie y la contraseña de PayPal al guardar las pasarelas | `Support/SecretMask` y `afterPrefsSave()` de la página de pasarelas enmascaran los valores (`api_key`, `password`, `secret`, `signature`…) en las filas del registro, también las antiguas, la próxima vez que se guarde la página. La copia `vstore_Backup` de las preferencias en `e107_core` la hace el núcleo y sigue conteniendo las claves |
| Acciones AJAX de pedidos (cancelar, reembolsar, completar, comprobar pago) permitidas a cualquier administrador | Exigen el permiso `P`, el mismo de la administración del plugin. El token CSRF de los POST lo valida el núcleo (`e_session::check()` en `class2.php`) |
| SQL concatenado con valores no convertidos | `escape()` en el código de cupón y casts o filtro `[^0-9,]` en listas de variaciones, clases de usuario, capítulos y fechas de estadísticas (`admin_config_coupons`, `_items`, `_orders`, `_statistics`, `vstore_shortcodes`) |
| `composer audit`: 15 avisos (Guzzle 7.10.0, psr7 2.8.0, symfony/http-foundation 5.4.48) | `composer update` dentro de las restricciones: Guzzle 7.15.5, psr7 2.13.1, http-foundation 5.4.54 y dependencias. Quedan 0 avisos y un paquete abandonado (`php-http/message-factory`, dependencia de omnipay/common). En producción hay que ejecutar `composer install` con el `composer.lock` nuevo |
| Compatibilidad PHP | `php -l` de los 140 archivos del plugin (sin `vendor/`) limpio en 7.4, 8.0, 8.1, 8.3 y 8.4; `tests/run.php` 199/199 en 7.4, 8.2 y 8.4 |

Pendiente de la Fase 5: revisar el resto de salidas de plantillas y correos de tema (el paquete `omnipay/braintree` se retiró en §8.17), la documentación de pasarelas y comprobar en el navegador las pantallas de administración modificadas.

### 8.11 Pedido creado antes de pagar con Mollie (2026-10-16)

Hasta ahora el pedido de un pago Mollie se creaba al volver el cliente a la tienda; si pagaba y cerraba la ventana (o el pago quedaba pendiente) existía un pago sin pedido. Ahora, en `processGateway('init')`, cuando Mollie devuelve la redirección se crea el pedido (Nuevo, pago incompleto, con el id del pago) antes de enviar al cliente a pagar: `saveTransaction(..., $quiet = true)` no muestra mensaje ni envía el correo de confirmación, y ahora devuelve `false` si no se pudo guardar (en ese caso no se redirige).

- **Vuelta a la tienda:** si ya existe el pedido del pago, `finishMollieReturn()` lo actualiza con `syncMolliePayment()` y informa: pagado (pedido Procesando, pago completo, correo de estado "procesando" con la factura, vaciado del carrito), pendiente (aviso y carrito vaciado) o cancelado, caducado o fallido (el pedido pasa a Cancelado y el carrito se conserva). Si no existe pedido se mantiene el camino anterior.
- **Stock:** se descuenta al crear el pedido, como siempre; `InventoryService::restore()` lo devuelve cuando el pago falla, se cancela o caduca (webhook, vuelta a la tienda o botón de comprobar pago). La cancelación manual de un pedido sigue sin devolver stock.
- **Sin webhook** (localhost o aviso no entregado) los pedidos de pagos abandonados quedan Nuevos con pago incompleto hasta pulsar "Comprobar pago en Mollie". El pago confirmado ya no depende de que el cliente vuelva.
- **Cambio visible:** el cliente que paga en el momento recibe el correo del estado "procesando" y no el de "pedido recibido".

Pruebas: `php tests/run.php` 203/203 en PHP 7.4, 8.2 y 8.4 (restauración de stock plana, por variación y en dos niveles, ilimitado y desconocido) y humo con e107 (pedido temporal ligado a un pago Multibanco pendiente real: `unchanged`, sin cambios). Falta probar en el navegador el pago completo, cancelar en Mollie y el pago sin volver a la tienda.

### 8.12 Correcciones: campos adicionales y vista previa de factura (2026-10-16)

| Síntoma | Causa | Corrección |
|---|---|---|
| Los campos adicionales del checkout aparecían como texto con las etiquetas HTML a la vista | El escapado de la Fase 5 (§8.10) también se aplicó al shortcode `{CUSTOMER_FIELD: add_fieldN}`, cuyo valor es HTML que genera `renderCustomerForm()` a partir de la plantilla `customer/additional/item` (los valores ya los escapa el formulario) | Se devuelve sin escapar, con un comentario que lo explica. Comprobado con e107 real; la configuración de los campos se guardaba bien en las preferencias |
| Vista previa de factura: `Call to undefined method vstore_order::initPrefs()` | Desde la Fase 4 `vstore_order` ya no extiende `vstore`, y la vista previa seguía llamando a `initPrefs()` y a `renderInvoiceTemplate()` sobre él | `previewPage()` usa la fachada `vstore` (`initPrefs()`, `order->setData()`, `renderInvoiceTemplate()`). Comprobado con datos de ejemplo: la plantilla se genera y muestra correo y teléfono |
| Al guardar un pedido en el administrador, la regeneración de la factura y el correo llamaban a métodos con firma antigua sobre un pedido sin cargar | Mismo origen: `afterUpdate()` de `admin_config_orders.php` | Carga el pedido (`order->load($id)`), llama a `renderInvoice(true)` y solo envía el correo si cambió el estado del pedido (antes se enviaba en cada guardado) |

Lección: al escapar la salida de un shortcode hay que comprobar si su valor es HTML generado por el propio plugin.

### 8.13 Correcciones: campos adicionales vacíos en el administrador (2026-10-16)

| Síntoma | Causa | Corrección |
|---|---|---|
| Al reabrir Preferencias, los 4 campos adicionales aparecían vacíos (y el siguiente guardado los borraba) | `additional_fields` no declaraba `'data' => 'array'`: el modelo de preferencias de e107 pasa los valores sin tipo por `toForm()`, que convierte cada fila en el texto `Array` (comprobado en CLI: `["Array","Array","Array","Array"]`) | `'data' => 'array'` en la definición del campo (como `tax_classes` y `shipping_data`) y el método de dibujo acepta también un valor serializado |
| Guardar en otro idioma del administrador borraba las leyendas y marcadores escritos en los demás idiomas | Solo se envía el texto del idioma activo y se guardaba la matriz completa | `beforePrefsSave()` fusiona con lo guardado (`Support/Translatable::merge()`); en el formulario, si el idioma activo está vacío se muestra el texto de otro idioma como sugerencia |
| En el frontend, el panel del cliente y el pedido, un campo sin texto en el idioma activo salía sin leyenda | Se leía solo `caption[e_LANGUAGE]` | `Translatable::pick()`: idioma activo, luego inglés, luego el primero con texto (`renderCustomerForm()`, validación, `vstore_dashboard`, `OrderService::splitAdditionalFields()`) |
| `beforePrefsSave()` de la página de preferencias no devolvía nada, así que e107 ignoraba sus correcciones | Faltaba `return $new_data;` | Ahora las aplica: se elimina la fila de plantilla `%ROW%` que se estaba guardando en `shipping_data` (sigue en las preferencias actuales hasta el próximo guardado) y se normalizan las clases de impuesto. Comprobado con datos de ejemplo |

Pruebas: `php tests/run.php` 210/210 en PHP 7.4, 8.2 y 8.4; humo con e107 (fusión de idiomas, fila `%ROW%`, clases de impuesto y dibujo del formulario con y sin valor). Falta repetirlo en el navegador.

### 8.14 NIF de consumidor con la verificación VIES activa (2026-10-16)

Prueba del mantenedor: con "Verificar NIF/IVA intracomunitario" activo, un NIF de empresa funcionaba (B2B), pero el NIF de un consumidor final portugués (que no está en VIES) se rechazaba; con la verificación desactivada funcionaba. Causa: `VatService::isAcceptable()` exigía el prefijo del país y la consulta a VIES para cualquier cliente de un país de la UE, también del propio país de la tienda, donde no hay inversión del sujeto pasivo y VIES no aporta nada. Corrección: `isAcceptable()` y `CheckoutService::validateCustomer()` reciben el país del negocio (`tax_business_country`); si el cliente es de ese país no se consulta VIES y solo se comprueba el número nacional con `Support/TaxNumber` (NIF portugués: 9 dígitos, primer dígito o pareja conocida y dígito de control módulo 11; con o sin prefijo `PT`; otros países sin regla propia se aceptan). Los clientes de otro país de la UE siguen validándose en VIES, que es lo que activa la inversión del sujeto pasivo, y los de fuera de la UE no se comprueban. El mensaje de error ahora indica que los particulares pueden dejar el campo vacío. Pruebas: `php tests/run.php` 222/222 en PHP 7.4, 8.2 y 8.4. Falta repetirlo en el navegador con un NIF de consumidor, uno de empresa y uno inválido.

### 8.15 Carrito con IVA de destino y NIF de otro país (2026-10-16)

Caso del mantenedor: tienda en España, cliente en Portugal, verificación VIES activa. El checkout mostraba precio unitario 154,39 €, importe 379,80 €, envío 7,72 €, IVA 23 % 89,54 € y total 387,52 €, cifras que no cuadran entre sí. Comprobado con e107 real y las preferencias actuales: el precio del producto (189,90 €) es **neto**, como define `docs/TESTING_GUIDE.md` (100 € + 21 % = 121 €), y el IVA del país del cliente se suma aparte.

| Problema | Causa | Corrección |
|---|---|---|
| Vista "neta" de empresa con IVA cobrado | `is_business` era "el cliente tiene NIF/IVA" (también el guardado de una compra anterior) y se mostraban importes `*_net` aunque el IVA se cobraba | `is_business` es ahora la inversión del sujeto pasivo realmente aplicada (`TaxService::isReverseCharge()`: otro país de la UE, NIF/IVA que VIES acepta, cálculo y verificación activos). En pedidos ya guardados, `vstore::isReverseChargeOrder()` (NIF de otro país y sin impuesto) |
| Los importes `*_net` restaban el IVA a precios que ya eran netos (154,39 y 7,72) | `CheckoutService` aplicaba `netPrice()` a precio, envío y cupón | Los netos son los importes sin IVA: `item_price_net` = precio, `cart_shippingNet` = envío, `amount_net` del cupón = descuento |
| La inversión del sujeto pasivo nunca se aplicaba | El NIF se leía de `_data['vat_id']`, que no se guarda en ningún sitio | `prepareCheckoutData()` y `getTaxRate()` usan el NIF/IVA de los datos del cliente |
| Consulta a VIES en cada render del carrito | Sin caché | `VatService::setStore()`: respuestas definitivas guardadas una hora en la caché de e107 (las caídas del servicio no se guardan); servicio compartido durante la petición |
| Comparación del país del negocio sensible a mayúsculas | `'pt' === 'PT'` en carrito, factura, correo y menú | Comparación sin distinguir mayúsculas; el menú del carrito toma los indicadores del cálculo |

Resultado con los datos reales (2 impresoras de 189,90 € netos, envío 9,50 €): Portugal 23 %: IVA 89,54 € y total 478,84 €; España 21 %: IVA 81,76 € y total 471,06 €; Francia 20 %: IVA 77,86 € y total 467,16 €. Pruebas: `php tests/run.php` 233/233 en PHP 7.4, 8.2 y 8.4. Falta repetirlo en el navegador (consumidor sin NIF, empresa con NIF válido en VIES, NIF inválido) y revisar la nota de inversión del sujeto pasivo en la factura.

### 8.16 IVA de origen o de destino en ventas a consumidores de la UE (2026-10-16)

Pregunta del mantenedor: con la verificación de NIF/VIES activa el IVA desaparece (inversión del sujeto pasivo, correcto), pero con un consumidor final de otro país de la UE se aplicaba siempre el IVA del país de destino; si la tienda vende desde España, ¿no debería aplicarse el de origen? Análisis de las reglas de la UE:

| Cliente | Regla | Estado |
|---|---|---|
| Mismo país de la tienda | IVA local | Ya era así |
| Empresa de otro país de la UE con NIF/IVA válido en VIES | Inversión del sujeto pasivo, IVA 0 | Ya era así (corregido en §8.15) |
| Consumidor de otro país de la UE | Desde el 1-7-2021, IVA del país de destino (régimen OSS). Excepción: si las ventas a distancia en toda la UE no superan 10.000 EUR al año, el vendedor puede aplicar el IVA de su país | Antes solo destino; ahora configurable |
| Fuera de la UE | Exportación, sin IVA | Ya era así |

Cambio: nueva preferencia `tax_origin_vat` (pestaña Impuestos, desactivada por defecto para no alterar las instalaciones existentes). Activada, `TaxService::rate()` aplica la tasa de la clase de impuesto del país de la tienda a los consumidores de la UE; la inversión del sujeto pasivo sigue teniendo prioridad y los clientes de fuera de la UE siguen sin IVA. Con los datos reales (2 impresoras de 189,90 € netos) y la opción activada, un cliente de Portugal paga IVA 21 % (79,76 €) en lugar del 23 %. Pruebas: `php tests/run.php` 238/238 en PHP 7.4, 8.2 y 8.4. El mantenedor debe confirmar con su asesor si está por debajo del umbral y registrado o no en el OSS; el plugin no controla el importe acumulado de ventas.

### 8.17 Pasarelas Stripe/Braintree/CoinPayments/StarkPay retiradas y dependencias (2026-10-16)

Decisión sobre lo anunciado en el README y las dependencias de Composer:

| Elemento | Decisión | Motivo |
|---|---|---|
| Stripe con tarjeta | Retirado | El checkout redirige a la pasarela; no existe formulario de tarjeta (Stripe.js) ni se envía `stripeToken`. Implementarlo exige un flujo nuevo, PCI SAQ-A y pruebas propias. Mollie ya cubre tarjetas en producción |
| `omnipay/braintree` (`4.0.x-dev`, rama de desarrollo) | Retirado | Misma carencia (nonce del cliente) y dependencia inestable |
| CoinPayments y StarkPay | Retirados | Sin pruebas ni uso; paquetes de terceros poco mantenidos |
| `php-http/message-factory` (abandonado) | Se mantiene | Lo exige `omnipay/common` 3.4.0, última versión compatible con PHP 7.4 (plataforma del plugin). Desaparecería al subir la plataforma a PHP 8 y usar `omnipay/common` 3.5+. `composer audit`: 0 vulnerabilidades |

Cambios:
- `composer.json`: solo `league/omnipay`, `omnipay/paypal`, `omnipay/mollie`, `ibericode/vat` y `psr/http-factory`. `vendor/omnipay` contiene `common`, `mollie` y `paypal`; se retiró también `braintree/braintree_php`. El `composer.lock` está actualizado.
- `Catalog::RETIRED_GATEWAYS` y `Catalog::isRetiredGateway()`. `vstore::initPrefs()` ignora esas pasarelas aunque sigan activas en las preferencias guardadas (`gateways/*`), de modo que no se ofrecen en el checkout.
- Administrador de pasarelas: aviso informativo (`LAN_VSTORE_ADMIN_GATE_RETIRED`, EN/ES/PT) si las preferencias guardadas contienen alguna; se eliminaron sus iconos por defecto.
- README (EN/ES/PT), `.github/copilot-instructions.md` y manual de usuario alineados: pasarelas PayPal, Mollie (tarjetas, iDEAL, Bancontact, MB WAY, Multibanco, etc.) y transferencia bancaria.
- Las credenciales antiguas siguen guardadas en las preferencias (solo se ignoran).
- Despliegue: en producción hay que ejecutar `composer install --no-dev` con el nuevo `composer.lock`.
- Alternativa futura si se necesita Stripe: Stripe Checkout (redirección) con el driver Omnipay Stripe `CheckoutGateway`, que encaja con la arquitectura de redirección actual.

### 8.18 Correos electrónicos con estructura moderna (2026-10-17)

El correo capturado en smtp4dev (`http://localhost:5000/`) mostraba los problemas de la plantilla heredada: envoltorio por defecto de e107 con CSS de Bootstrap en la cabecera, `<table>` dentro de `<p>`, el error `</tdclass>` en la fila de artículos, `<h4>` en celdas, sin `lang` ni viewport, y estilos que los clientes de correo ignoran.

| Elemento | Cambio |
|---|---|
| Envoltorio | `templates/vstore_email_layout_template.php`: tablas con `role="presentation"`, estilos en línea, contenedor de 600 px, regla móvil (columnas apiladas), `lang`, `viewport`, `color-scheme`, `<title>`, logotipo o nombre del sitio y pie. `compileEmail()` lo usa mediante `template => array(plugin, name, key)` |
| Cuerpos | `vstore_email_template.php`: saludo, referencia destacada, tarjetas comerciante/cliente, líneas del pedido y recuadro de instrucciones de pago (sin recuadro si el pedido no es transferencia). Los textos siguen en `LAN_VSTORE_MAIL_*` y `LAN_VSTORE_FR_*` |
| Líneas del pedido | `{ORDER_ITEMS: email}` usa la clave `items` de `vstore_email_template.php`; `{ORDER_COUPON}` y `{ORDER_TAX}` siguen la plantilla activa. El resto de pantallas conserva `order_items` |
| Error corregido | Con inversión del sujeto pasivo, precio y total de línea salían 0,00 porque se leían `net_price` e `item_total_net`, que no existen en los pedidos guardados. Los precios son netos y se muestran igual para todos |
| Plantillas guardadas | Las plantillas guardadas desde el administrador sustituyen a las del archivo. En la instalación de pruebas se vaciaron las cuatro (copia en `/tmp/email_templates_pref_backup.json`); en otras instalaciones hay que usar "Restablecer plantilla" |

Pruebas: los cuatro tipos (`default`, `completed`, `cancelled`, `refunded`) se enviaron al pedido NODA000015 y se revisaron en smtp4dev; el HTML se validó con libxml sin avisos. Pendiente: comprobar visualmente en smtp4dev (escritorio y ancho móvil) y en un cliente real (Gmail/Outlook) antes de publicar. Texto plano: el alternativo generado por e107 une los párrafos sin salto de línea; es cosmético y queda como mejora.

### 8.19 PDF de facturas con librería propia (2026-10-17)

Situación: Vstore extendía `TCPDF` tomándolo del plugin `pdf` de e107 (repositorio `e107inc/pdf`, marcado "legacy"; trae TCPDF 6.3 con un parche propio, fuentes y un archivo de idioma). Del plugin solo se usaban la librería, las fuentes y la cadena "Page". La clase `inc/vstore_pdf.class.php` definía constantes globales y abría `ob_start()` al incluirse.

Alternativas valoradas:

| Opción | Resultado |
|---|---|
| Seguir con el plugin `pdf` de e107 | Descartada: obsoleto, sin versión nueva, hay que instalarlo aparte y se comprobaba con `e107::isInstalled('pdf')` |
| Repositorio `Jimako-e107-plugins/e107-old-plugins` | Sin utilidad: copia de seguridad de plugins de e107 v1 (archivado), no incluye ningún generador de PDF |
| TCPDF 7.x | Descartada: exige PHP 8.2 y es una reescritura (`tc-lib-pdf`) con otra API; la plataforma del plugin es PHP 7.4 |
| dompdf 3.x / mPDF 8.x | Posibles (PHP 7.1+/7.0+), mejor CSS, pero obligan a rehacer plantilla de factura, cabecera y pie, y a validar de nuevo las plantillas que los usuarios guardaron para TCPDF |
| **TCPDF 6.11 por Composer** | Elegida: misma API que ya usaba la plantilla, mantenida, PHP 7.1+, fuentes incluidas, sin plugin externo |

Cambios:
- `composer.json`: `tecnickcom/tcpdf ^6.11` (lock actualizado, `composer audit` sin avisos de seguridad). Ocupa unos 27 MB en `vendor/`, casi todo fuentes.
- `src/Pdf/InvoicePdf.php`: cabecera (nombre, lema, logo, línea), pie (bloques del comerciante, página, fecha) y `build()`. No llama a e107: logo, textos y etiquetas llegan por parámetros. Compatible con PHP 7.4. `vstore::invoiceToPdf()` lo usa; `vstore::checkPdfPlugin()` ahora comprueba que exista la clase `TCPDF`.
- Eliminados `inc/vstore_pdf.class.php`, las constantes globales (`K_PATH_MAIN`, `PDFLOGO`, ...), `ob_start()` y el `ob_end_clean()` de la fachada, que vaciaba un búfer ajeno.
- Textos: `LAN_VSTORE_PDF_PAGE` (EN/ES/PT), aviso `LAN_VSTORE_MSG_PDF_PLUGIN` reescrito ("ejecuta composer install"), ayudas del administrador, README (EN/ES/PT), wiki, manual y `copilot-instructions.md`.
- Error de plantilla encontrado al comparar el PDF: la fila "Due by" de `vstore_invoice_template.php` no cerraba `</td>`; TCPDF descartaba las columnas de facturación y envío (ocurría también con el plugin antiguo) y lanzaba `Undefined array key "trids"`. Corregido. Además se ajustaron separación de columnas y anchos de la tabla de artículos.
- Límites conocidos: el parche `filePathModify` del plugin antiguo (imágenes con URL absoluta del sitio dentro de la factura) no existe en TCPDF estándar; el logo de cabecera usa ruta de disco y no se ve afectado. Las plantillas de factura guardadas en el administrador no se tocan.

Pruebas: `php tests/run.php` 241/241 en PHP 7.4, 8.2 y 8.4 (incluye generar un PDF con acentos y euro). Factura del pedido NODA000015 generada y revisada visualmente. Pendiente: comprobar en el navegador el botón de factura del pedido, el adjunto del correo y la descarga por el cliente. Queda abierta la mejora estética del documento (más allá de lo anterior).

### 8.20 Adjunto de la factura y moneda con métodos Mollie (2026-10-17)

Pruebas del mantenedor tras §8.19: el botón de factura y la descarga del cliente funcionan; el adjunto del correo no, y un pedido con MB WAY falló con "The currency is not supported by this payment method".

| Problema | Causa | Corrección |
|---|---|---|
| Correo sin la factura | `compileEmail()` pasaba el fichero con la clave `attach`; el mailer de e107 lee `attachment`. Error heredado | Clave `attachment`. Verificado: correo de prueba con 1 adjunto en smtp4dev |
| Error de moneda con MB WAY | La moneda de la tienda estaba en USD y MB WAY, Multibanco, iDEAL, Bancontact, transferencia, domiciliación y tarjeta regalo de Mollie solo admiten euros | `Catalog::supportsCurrency()` (lista `MOLLIE_EUR_ONLY`); `initPrefs()` deja de ofrecer esos métodos si la moneda no es EUR. La pestaña Pasarelas avisa de qué métodos se ocultan y por qué. Para cobrar con MB WAY, moneda EUR en Preferencias |

Pruebas: `php tests/run.php` 242/242 en PHP 7.4, 8.2 y 8.4. Con USD, los métodos activos son transferencia, PayPal, tarjeta, PayPal de Mollie y paysafecard.

### 8.21 Modo presupuesto: venta o presupuesto (2026-10-17)

Requisito del mantenedor: el plugin tiene dos posibilidades, venta o presupuesto. Un producto con precio se vende; con el precio vacío muestra el botón "Presupuesto / Quote / Orçamento". El presupuesto sigue el mismo proceso que una compra (carrito) pero sin valores, solo la lista de material pedido; el mismo envío de correo sirve al cliente y a la tienda; y debe quedar un registro en el administrador porque la Autoridad Tributaria portuguesa no acepta facturas creadas en plataformas no certificadas. Flujo de trabajo: pedido online → migrar el presupuesto a la app → el cliente aprueba → se emite una factura certificada (fuera de Vstore).

Decisiones:

| Tema | Decisión |
|---|---|
| Qué es un presupuesto | Producto con `item_price` vacío (`NULL`), no 0 (0 es un producto gratuito). El campo del administrador guarda `NULL` en `afterCreate`/`afterUpdate` (el campo numérico guardaría 0) |
| Mezclar venta y presupuesto | Sí (cambio posterior, ver §8.23): el carrito tiene dos bloques y cada botón tramita solo sus líneas. No se guarda precio de presupuesto en ningún sitio |
| Proceso del cliente | El de siempre: carrito, datos y direcciones, resumen. Sin precios, totales, cupón, envío, impuestos ni método de pago. Pasarela interna `quote` |
| Registro | Tabla `vstore_orders` sin cambios de esquema: estados `Q` (solicitado), `M` (migrado a la app), `A` (aprobado), `F` (facturado) y `X`. `order_pay_gateway = quote`, `order_pay_status = none`, importes 0 |
| Facturas | Los presupuestos no reciben número de factura (no se consume la secuencia que debe ser continua y certificada), no generan PDF, no mueven el stock ni cuentan en las estadísticas de ventas |
| Correo | Nuevo tipo `quote` (plantilla editable): una sola plantilla, al cliente con copia obligatoria a la tienda. Los cambios de estado posteriores no envían correo; la app externa se ocupa del contacto con el cliente |
| Administrador | Botones para avanzar el estado, filtro "Presupuestos" y estados traducidos (EN/ES/PT) |

Pendiente (siguiente desarrollo): la función que entrega el presupuesto a la app externa (el estado `M` ya existe como punto de enganche) y la vuelta de aprobación/facturación (`A`, `F`). Hasta entonces el administrador cambia esos estados a mano.

Pruebas: `php tests/run.php` 248/248 en PHP 7.4, 8.2 y 8.4 (precio vacío frente a 0, estados y la regla venta/presupuesto del carrito). Flujo real por línea de comandos con un producto de prueba y la base de datos: carrito de presupuesto, rechazo de mezcla, resumen sin precios, pedido `Q` sin número de factura, correo con copia a la tienda y shortcodes del producto, el pedido y el detalle. Pendiente de comprobar en el navegador: edición del precio vacío en el administrador (incluida la edición en línea), la lista de productos, el desplegable del carrito y el panel del cliente.

### 8.22 Integración con Cegid Primavera (diseño inicial, 2026-10-17)

El presupuesto guardado en Vstore (estados `Q`, `M`, `A`, `F`) se entregará a Cegid Primavera ERP v10 mediante su Web API (REST, OAuth2, instalada en el servidor del cliente), que emitirá la factura certificada. El informe de viabilidad de KreativeKey confirma que es posible pero no trae rutas ni campos, por lo que se parte con información incompleta. Diseño, tareas por fases (descubrimiento, conector con vista previa, migración, aprobación/facturación, producción), riesgos y preguntas abiertas en [INTEGRACION_PRIMAVERA.md](INTEGRACION_PRIMAVERA.md). Siguiente paso: completar la Fase 0 (licencia Web API, entorno de pruebas, documentación de los servicios de ventas y peticiones de ejemplo). Punto abierto a decidir: si las facturas de ventas online también deben salir de Primavera, ya que la factura PDF de Vstore no es certificada.

### 8.23 Carrito mixto y KPI de presupuestos (2026-10-17)

Motivo: la tienda vende productos y a la vez pide presupuestos detallados (por ejemplo, de un servicio); un cliente que ya había enviado un presupuesto no podía añadir productos para comprar. Se retira la regla "un carrito es de uno u otro".

| Tema | Decisión |
|---|---|
| Carrito | Un solo carrito con dos bloques (`Productos para comprar` y `Productos para presupuesto`). La plantilla del carrito no cambia: se pinta con las partes `start/item/end` de cada tipo |
| Tramitación | Con los dos tipos, el botón de cada bloque envía `cartScope=sale|quote`; `startCheckout()` calcula solo esas líneas, las deja en la sesión y redirige al pago. Con un solo tipo sigue el enlace de siempre |
| Qué sale del carrito | Al guardar el pedido o el presupuesto solo se borran las líneas de ese tipo (`resetCart($kind)`); si no queda ninguna se vacía el carrito entero. `?reset=1` sigue vaciándolo todo |
| Cálculo | `prepareCheckoutData(..., $only)`: `sale` o `quote` filtra las líneas; sin argumento (menú del carrito) cuenta todas. El cupón solo se aplica a la compra |
| Administrador | Quinta tarjeta en las estadísticas con el número de presupuestos nuevos (estado `Q`), en rojo si hay alguno y con enlace a la lista filtrada por ese estado. Los cinco recuadros pasan a una fila flexible |

Aplicaciones para ver un presupuesto: mientras la entrega a la app externa no exista, la vista de trabajo es la lista de Ventas con el filtro `Presupuestos` (o el estado `Q` desde la tarjeta) y el detalle del pedido con la lista de material y los datos de contacto; el correo de aviso llega a la tienda con cada solicitud. Cuando esté la integración con Primavera, el presupuesto se consultará y valorará allí, donde nace la factura certificada.

Pruebas: `php tests/run.php` 248/248. Flujo real por línea de comandos y en el navegador: carrito con un producto de cada tipo (dos bloques y dos botones), checkout de presupuesto sin método de pago, checkout de compra con método de pago, y solo salen del carrito las líneas del presupuesto enviado.

### 8.24 Rediseño de la tienda: catálogo, producto, carrito y menús (2026-10-18)

Motivo: el catálogo, el carrito, las categorías y los menús seguían con el aspecto de Bootstrap 3 sobre un tema Bootstrap 5, sin estilo en el menú de categorías. Se mantiene la convención e107: el plugin se pinta con el tema activo y toda plantilla se puede sobrescribir en `<tema>/templates/vstore/`.

| Zona | Decisión |
|---|---|
| CSS | `vstore.css` nuevo, acotado bajo `.vstore`. Usa las variables `--bs-*` del tema y expone `--vs-*` (radio, superficie, sombra, acento) para ajustar el aspecto sin tocar el archivo |
| Catálogo | Raíz: mosaicos de categoría con imagen, recuento y descripción. Categoría: tarjetas de producto con imagen, insignias (presupuesto, sin stock), precio y botón |
| Barra de categorías | Bajo el breadcrumb en catálogo, categoría y producto: píldoras con recuento y la categoría actual resaltada; en móvil se desliza lateralmente |
| Producto | Galería con miniaturas, caja de precio con cantidad y botón, ficha con código, disponibilidad y peso, pestañas de detalle |
| Menús | Categorías: categorías raíz con imagen y recuento (`storeCategoryNav()`). Productos: una categoría o «Todas las categorías (últimos productos)» |
| Carrito | Filas como tarjetas, desplegable `navcart` y su variante de presupuesto con el mismo estilo; en móvil cada fila pasa a tarjeta y la tabla no desborda |
| Errores corregidos | `storeCategoryNav()` leía el resultado con `fetch()` mientras `url()` y `toHTML()` lanzaban consultas en la misma conexión y solo salía una categoría: ahora lee todas las filas antes del bucle |

Pruebas: `php tests/run.php` 248/248. Navegador (integrado, tema Bootstrap 5): catálogo, categoría, presupuestos, producto, carrito mixto y desplegable; a 1078, 768 y 390 px sin desbordamiento horizontal; menús de categorías y productos en una columna lateral de prueba (archivo temporal ya eliminado).

### 8.25 Rediseño del checkout y del panel del cliente (2026-10-18)

Motivo: pedido del mantenedor de llevar el estilo de la tienda al checkout y a la pantalla principal del panel del cliente, que solo mostraba un texto de bienvenida; se pidió también datos o un gráfico de compras.

| Zona | Decisión |
|---|---|
| Pasos | Indicador de tres pasos (carrito, tus datos, resumen) sobre todas las páginas del checkout (`checkoutPage()`), con el paso actual resaltado y los anteriores marcados |
| Datos | Formularios de facturación y envío en paneles; las pasarelas de pago son fichas seleccionables; la dirección de envío distinta sigue siendo opcional |
| Resumen lateral | `checkoutAside()`: productos con imagen y cantidad, subtotal, envío, cupón, impuestos y total (solo productos y cantidades en un presupuesto); fijo al desplazarse en escritorio y bajo el formulario en móvil. No se muestra en la elección entre invitado, registro y acceso, que necesita todo el ancho |
| Revisión | Paneles de facturación, envío, método de pago, artículos y notas; botón principal grande |
| Panel del cliente | Cabecera con avatar y saludo, navegación en píldoras y portada con cuatro cifras (pedidos, total gastado, en curso, presupuestos abiertos), gráfico de barras CSS de los últimos seis meses, últimos cinco pedidos y accesos rápidos. Sin librerías de gráficos ni consultas pesadas (cuatro consultas) |
| Cifras | Los presupuestos no cuentan como compras ni como gasto; el gasto solo suma pedidos con pago completo y estado distinto de cancelado o reembolsado |
| Enlaces a pedidos | Los presupuestos y los pedidos sin número de factura no se podían abrir desde el panel porque el enlace usaba el número de factura. Ahora el identificador es el número de factura o, sin él, el id del pedido más 1.000.000.000 |
| Estados | Insignias `vstore-badge` en lugar de las etiquetas `label` de Bootstrap 3 |

Pruebas: `php tests/run.php` 248/248 (las funciones nuevas dependen de e107 y no entran en esa batería). Navegador (tema Bootstrap 5): elección de cliente, formulario con pasarelas, revisión del pedido, a 1078 y 390 px sin desbordamiento. Panel renderizado por línea de comandos con el usuario 1 y comprobado en el navegador: portada, lista de pedidos y detalle de un presupuesto. Pendiente: comprobar con una sesión real el avatar y la cancelación de un presupuesto, y la ficha de descargas.

### 8.26 Efecto al añadir al carrito y versión 2.1.0 (2026-10-18)

Motivo: al añadir un producto solo cambiaba el número del contador y mucha gente no lo notaba. Ahora la imagen del producto vuela al icono del carrito, el contador cambia al llegar y el icono late, el botón muestra una marca un momento y aparece un aviso con enlace al carrito (`role="status"`). Con `prefers-reduced-motion` no hay movimiento. Si la pestaña está oculta y el navegador pausa la animación, un temporizador de respaldo actualiza el contador. Se publica como versión 2.1.0 (Presupuestos, rediseño de la tienda, checkout y panel, efecto del carrito).

Nota de instalación: `plugin.xml` crea el enlace de la tienda y el del carrito con `perm="member"`, de modo que los visitantes sin sesión no los ven aunque el checkout admita compra como invitado. Se deja como está por compatibilidad; cada web puede cambiarlo en Administración, Enlaces del sitio.
