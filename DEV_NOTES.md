# DEV_NOTES — Vstore

Architectural memory for maintainers. Plan and progress: `docs/PLAN_ACTUALIZACION_V2.md`.

## 1. Identity

| Item | Value |
|---|---|
| Slug / NS | `vstore` / `VSTORE` |
| Maintainer | Kanonimpresor — Marketing de Performance (`https://marketingdeperformance.online`) |
| Original authors | CaMerOn, Achim Ennenbach, e107 Inc (credits kept in About tab and README) |
| Licence | GPL-3.0 (`LICENSE`) |
| Admin UI | Bootstrap 3 (e107 admin). Frontend: Bootstrap 5. Never mix them |

## 2. Layout

- `admin_config.php`: `e_admin_dispatcher` with 11 modes (`main`, `email`, `invoice`, `gateways`, `orders`, `cat`, `products`, `vars`, `coupons`, `statistics`, `customer`). Each controller lives in `inc/admin_config_*.php`.
- `inc/vstore_admin_common.php`: trait shared by every controller (About, Guide, sidebar help). See `docs/architecture/USER_GUIDE_PATTERN.md`.
- `vstore.class.php`: facade. Public static methods (`calcCouponAmount`, `getTaxRate`, `calcShippingCost`, `validateVATID`...) delegate to `src/`; the rest of the monolith is being split in Phase 4 of the plan.
- `src/` (namespace `Vstore\`, PHP 7.4 compatible, loaded by `src/autoload.php`): `Service/` (Tax, Vat, Coupon, Shipping, Inventory, ItemVarType, Userclass, InvoiceNumber, Invoice, Payment, Cart, Checkout, Order), `Repository/` (coupon usage, coupons, items, item variations, orders, cart, customers), `Support/` (`Catalog` reference data, `ItemVars`, `Result`, `ResultTranslator`). No HTML, no `e107::getMessage()`, no superglobals inside `src/`.
- `tests/run.php`: dependency-free regression tests for `src/` (`php tests/run.php`).
- `inc/admin_config_cart.php`: dead code (mode commented out in the dispatcher).

## 3. Decisions

1. **No `e_help.php`.** Help is `renderHelp()` on the controller, returning `['caption', 'text']`.
2. **No `__call()` in shortcode batches.** e107 uses `method_exists()` (`shortcode_handler.php`).
3. **`{LAN_*}` tokens need a pre-pass.** `parseTemplate()` ignores them; use `_resolveLans()`.
4. **Lazy language files** for tab-specific text: `<Lang>_admin_about.php`, `<Lang>_admin_help.php`.
5. **Preferences** live under `e107::pref('vstore')`. `plugin.xml` `<pluginPrefs>` only seeds defaults at install and must stay in sync with `admin_config_pref.php`.
6. **Do not rename public `LAN_VSTORE_*` constants** or database columns.
7. **`vendor/` is not in git.** Run `composer install --no-dev --optimize-autoloader` after cloning.
8. **Single metadata source:** `plugin.xml`. Bump `version` and `date` together.
9. **Admin language files are per area** and use the array format (`return array('LAN_X' => '...')`). e107 fills missing keys from English automatically, which `define()` files do not get. Each controller calls `e107::lan('vstore', 'admin_<area>', true)` at the top of its file, before the class declaration, because `$fields` and `$prefs` are evaluated at class load. Shared words live in `admin_common` (loaded by `admin_config.php`). Naming: `LAN_VSTORE_ADMIN_<AREA>_<FIELD>` and `..._H` for help text.
10. **Never compare translated labels.** `vstore::getStatus()` returns translated text; use `vstore::getStatusSlug()` for logic.
11. Shared labels used by front and admin (statuses, email types, currencies, weight units) are in `<Lang>_global.php`; the static helpers load it with `e107::lan('vstore', 'global', true)`.
13. **Services return `Vstore\Support\Result`**, never messages. The code maps to `LAN_VSTORE_MSG_<CODE>` and `params()` are the `sprintf` arguments; the facade shows it with `addResultMessage()`. Dependencies (VIES validator, VAT rates, usage repository, clock, logger) are injected so the services run without e107.
12. **Frontend language files** (array format, loaded with `e107::lan('vstore', '<name>', true)` at the top of each template/hook/class): `front_shop` (`LAN_VSTORE_FR_*`, templates), `front_dashboard` (`LAN_VSTORE_DB_*`), `front_mail` (`LAN_VSTORE_MAIL_*`, `_INV_*`, `_ORD_*`), `front_msg` (`LAN_VSTORE_MSG_*` system messages, `LAN_VSTORE_SC_*` shortcodes), `hooks` (`LAN_VSTORE_HOOK_*`). Templates use `{LAN=LAN_VSTORE_...}`; PHP uses the constants. Debug-only messages (`addDebug`, `trigger_error`) stay in English.

## 3.1 Footguns found

- A method defined in a controller overrides the trait method silently. Coupons and variations had empty `renderHelp()` stubs that hid the trait; they were removed.
- `plugin.xml` data cached by e107 can be stale after editing; `getPluginInfo()` parses the file directly.

## 4. Validation

```bash
find . -name '*.php' -not -path './vendor/*' -exec php -l {} \;
php tests/run.php
grep -rn "function __call" shortcodes/
```

Language parity (same constant names in English, Spanish, Portuguese) must hold for `_admin`, `_admin_about` and `_admin_help`.
