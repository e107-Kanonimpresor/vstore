# 🏷️ Cómo Crear un Release en GitHub

## Paso a Paso

### 1. Ve a la página de Releases
https://github.com/e107-Kanonimpresor/vstore/releases

### 2. Haz clic en "Draft a new release"

### 3. Completa el formulario

**Choose a tag:**
```
v1.1.0
```

**Release title:**
```
v1.1.0 - PHP 8.x Compatibility & Bug Fixes
```

**Describe this release:**
```markdown
## 🎉 Major Update - PHP 8.x Compatibility

This release brings full PHP 8.x compatibility and fixes several critical bugs in the Vstore e-commerce plugin for e107 CMS.

### ✨ New Features
- Multi-language documentation (English, Spanish, Portuguese)
- Enhanced error messages for better debugging
- Improved user experience in checkout flow

### 🔧 Bug Fixes
- **Namespace Compatibility**: Updated ibericode/vat to v2.x (14 references)
- **Coupon System**: Fixed date validation and cart preservation
- **Checkout Flow**: Auto-copy billing address to shipping when needed
- **PHP 8.x**: Resolved undefined array key warnings (11 locations)
- **PDF Generation**: Type checking prevents foreach errors
- **Admin Panel**: Array access validation

### 📝 Documentation
- Comprehensive CHANGELOG.md
- README in 3 languages (EN, ES, PT)
- Git workflow guide for contributors

### 🔗 Full Changelog
See [CHANGELOG.md](https://github.com/e107-Kanonimpresor/vstore/blob/main/CHANGELOG.md) for complete details.

### 📦 Installation
1. Download the release ZIP
2. Extract to `e107_plugins/vstore/`
3. Run `composer install` in plugin directory
4. Install plugin from e107 admin panel

### ⚠️ Requirements
- e107 CMS v2.3.0+
- PHP 7.4+ (PHP 8.x recommended)
- MySQL 5.7+ / MariaDB 10.2+

### 🙏 Credits
Thanks to the e107 community for testing and feedback!

---

**Made with ❤️ for the e107 CMS Community**
```

### 4. Opciones adicionales

**Set as the latest release:** ✅ Marcar  
**Set as a pre-release:** ❌ No marcar (esta es una versión estable)

### 5. Haz clic en "Publish release"

---

## 📸 Resultado

Una vez publicado, el release:
- Aparecerá en la página principal del repositorio
- Creará automáticamente un archivo ZIP descargable
- Se mostrará en https://github.com/e107-Kanonimpresor/vstore/releases
- Los usuarios podrán descargar esta versión específica

---

## 🔄 Futuras Actualizaciones

Para futuros releases, usa versionado semántico:

- `v1.1.1` - Correcciones menores (bug fixes)
- `v1.2.0` - Nuevas características (features)
- `v2.0.0` - Cambios incompatibles (breaking changes)

---

## ℹ️ Notas

- El mensaje "main had recent pushes" desaparecerá automáticamente
- No afecta el funcionamiento del repositorio
- Es solo una sugerencia de GitHub para crear un PR
