# User Guide / About Pattern — Vstore (4-layer architecture)

> **Plugin:** `vstore` v2.x · **NS prefix:** `VSTORE` · **Maintainer:** Kanonimpresor — Marketing de Performance
> Adapted from the Kanonimpresor pack (`sitedown_styles` v2.2.0, `booking` v2.4.0).

## 1. Goals

1. No proxy shortcodes: a shortcode must carry logic, never just wrap a `LAN_*` constant.
2. Language files hold plain text (inline `<code>`, `<strong>`, `<em>` allowed), never layout.
3. Help/About strings are lazy-loaded, so they do not weigh on every admin request.

## 2. Layers and files

| Layer | File | Responsibility |
|---|---|---|
| 1 Controller | `inc/vstore_admin_common.php` (trait `vstore_admin_common`) | `AboutPage()`, `GuidePage()`, `renderHelp()`, `getPluginInfo()`, `_resolveLans()` |
| 2 Template | `templates/vstore_about_template.php`, `templates/vstore_guide_template.php` | HTML structure with `{LAN_...}` and `{VSTORE_ABOUT_*}` tokens |
| 3 Language | `languages/<Lang>/<Lang>_admin_about.php`, `<Lang>_admin_help.php` | Plain-text strings, loaded with `e107::lan('vstore', 'admin_about', true)` / `'admin_help'` |
| 4 Shortcodes | `shortcodes/batch/vstore_about_shortcodes.php`, `vstore_guide_shortcodes.php` | About: metadata rows and buttons. Guide: intentional stub |

The trait is `use`d by the eleven `e_admin_ui` controllers in `inc/admin_config_*.php` (all modes in `admin_config.php`). The About and Guide pages live in the `main` mode (`main/about`, `main/guide`), so e107 resolves them to `AboutPage()` and `GuidePage()`.

## 3. Rules

- **Never use `__call()` in a shortcode batch.** `e107_handlers/shortcode_handler.php` dispatches with `method_exists()`, which is `false` for magic methods.
- `parseTemplate()` only dispatches shortcodes. `{LAN_*}` tokens are replaced first by `_resolveLans($html, $prefix)`.
- Template variables follow e107 naming: id `vstore_about` → `$VSTORE_ABOUT_TEMPLATE`, id `vstore_guide` → `$VSTORE_GUIDE_TEMPLATE`.
- Guide: one template key per tab. The tab caption is `LAN_VSTORE_GUIDE_<KEY>_TAB` and the body tokens are `LAN_VSTORE_GUIDE_<KEY>_*`. Adding a section means adding a template key and its constants in the three languages.
- Guide texts must not contain curly braces (the parser would treat them as shortcodes).
- `getPluginInfo()` reads `plugin.xml` directly, so version and date are never duplicated in PHP.

## 4. Naming

| Prefix | Use |
|---|---|
| `LAN_PLUGIN_VSTORE_ABOUT_*` | About tab strings |
| `LAN_VSTORE_GUIDE_<SECTION>_*` | Guide strings |
| `LAN_VSTORE_HELP_*`, `LAN_VSTORE_BTN_*`, `LAN_VSTORE_ADMIN_ABOUT/GUIDE/HELP_MORE` | Sidebar widget and menu captions (`<Lang>_admin.php`) |
| `LAN_VSTORE_HELP_TIP_<MODE>` | Contextual sidebar tip per admin mode (`PREFS` for mode `main`) |

## 5. Sidebar help widget

`renderHelp()` is called by `e_admin_ui` when the controller is built and must return an **array** `['caption' => ..., 'text' => ...]` (e107 core wraps it with `tablerender()`). Returning a ready-made panel string, as in the generic migration prompt, breaks `sc_adminui_help()`.

Order of the content: version badge, tagline, contextual tip, Docs / Support / Donate buttons, "More info »" link to `main/about`.

## 6. Adding or changing content

1. Add the constants to the three language files (English, Spanish, Portuguese). A parity check must report the same constant names in all three.
2. Reference them in the template with `{LAN_...}`.
3. Run `php -l` on every touched file.
4. Open the Guide in the admin area for each language and check that no raw `{LAN_...}` token is visible.
