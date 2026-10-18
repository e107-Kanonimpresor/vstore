# Plugin Vstore para e107

#### (Elija su idioma / Choose your language / Escolha o seu idioma)

[![Language-English](https://img.shields.io/badge/Language-English-blue)](README.md) 
[![Language-Português](https://img.shields.io/badge/Language-Português-green)](README.pt-PT.md) 
[![Language-Español](https://img.shields.io/badge/Language-Español-red)](README.es-ES.md) 

---

[![Version](https://img.shields.io/badge/version-2.1.0-blue)](https://github.com/e107-Kanonimpresor/vstore/releases/tag/v2.1.0)
![e107](https://img.shields.io/badge/e107-2.3.2%2B-green)
[![PHP](https://img.shields.io/badge/PHP-7.4%20a%208.4-purple.svg)](https://php.net)
![Bootstrap](https://img.shields.io/badge/Bootstrap-3%20%7C%205-purple)
![License](https://img.shields.io/badge/license-GPL--3.0-orange)

**Autor:** [Kanonimpresor](https://marketingdeperformance.online) — Marketing de Performance  
**Licencia:** GPL-3.0  
**Basado en:** [e107inc/vstore](https://github.com/e107inc/vstore) (CaMerOn, Achim Ennenbach, e107 Inc). Modificado por Kanonimpresor, 2025-2026.

---

## 📋 Descripción

**Vstore** es un plugin de comercio electrónico para el CMS Bootstrap [e107 v2.x](https://e107.org). Permite gestionar una tienda online directamente desde su sitio e107: productos físicos, descargas digitales o servicios, con varios métodos de pago, IVA de la UE, cupones, control de stock, facturas en PDF y correos a los clientes, en español, inglés y portugués.

---

## ✨ Características Principales

### 🛒 Carrito de Compras y Checkout
- **Carrito** con añadir al carrito por AJAX y campo de cantidad en la página del producto
- **Compra como invitado**: el cliente puede comprar como invitado, iniciar sesión o registrarse
- **Checkout** con direcciones de facturación y envío, campos adicionales opcionales y teléfono
- **Panel del cliente**: historial de pedidos, detalle, descargas y facturas
- **Descargas digitales**: los archivos están disponibles cuando se recibe el pago
- **Variaciones de producto**: hasta dos grupos por producto (talla, color...) con cambio de precio e inventario propio

### 💳 Procesamiento de Pagos
- **Pasarelas de pago** (mediante [Omnipay](https://omnipay.thephpleague.com/)):
  - **PayPal** (estándar y REST)
  - **Mollie**: tarjetas, PayPal, **MB WAY**, **Multibanco**, iDEAL, Bancontact, transferencia, domiciliación, tarjetas regalo y más. Solo se ofrecen los métodos activos en su perfil de Mollie
  - **Transferencia bancaria** (manual): el pedido espera hasta que lo marque como pagado
- **Páginas de pago alojadas**: los datos de la tarjeta nunca pasan por su servidor
- **Webhook de Mollie**: los pedidos se crean antes de que el cliente pague, de modo que un pago pendiente (MB WAY, Multibanco) o realizado sin volver a la tienda nunca se pierde
- **Reembolsos** desde el pedido en las pasarelas que los permiten (Mollie, PayPal)
- **Estados del pedido**: Nuevo, En proceso, En espera, Completado, Cancelado, Reembolsado

> Los métodos de Mollie que solo aceptan euros (MB WAY, Multibanco, iDEAL, Bancontact, transferencia, domiciliación y tarjetas regalo) se ocultan en el checkout si la moneda de la tienda no es EUR.

### 💰 Precios y Descuentos
- **Cupones**:
  - Descuentos porcentuales o de importe fijo
  - Fechas de inicio y fin
  - Incluir/excluir categorías y productos
  - Límites de uso (total, por usuario y por artículo)
- **Cálculo de impuestos**:
  - Precios netos, con el IVA del país del cliente añadido
  - IVA de la UE con validación **VIES** del NIF/IVA (y dígito de control del NIF portugués)
  - **Inversión del sujeto pasivo** (0%) para empresas de otro país de la UE con NIF/IVA válido
  - Opción de cobrar el IVA del país de la tienda a consumidores de la UE (vendedores bajo el umbral de 10.000 EUR)
  - Clases de impuesto: estándar, reducido, superreducido, parking y más

### 📦 Gestión de Productos
- **Productos ilimitados** y **categorías** anidadas, restringibles a clases de usuario
- **Control de stock** por producto y por variación (`-1` para ilimitado)
- **Galerías de imágenes** con zoom, vídeos y archivos descargables
- **Productos relacionados**
- **Tienda en Bootstrap 5**: mosaicos de categoría, tarjetas de producto, barra de categorías bajo el breadcrumb, menús y carrito rediseñados, adaptable a móvil y personalizable con variables CSS (2.1.0)
- **Modo presupuesto** (2.1.0): un producto con el precio vacío muestra un botón "Presupuesto"; los productos de compra y de presupuesto comparten carrito (cada tipo se tramita por separado y el presupuesto solo lista productos y cantidades) y la solicitud se guarda como presupuesto (Presupuesto solicitado → Migrado a la app → Aprobado → Facturado) y se envía por correo al cliente y a la tienda
- **URLs amigables** para SEO
- **Integración con la búsqueda** de e107

### 🚚 Envío
- **Métodos de envío**: suma de todos los artículos, suma de artículos únicos o escalonado por precio o peso del pedido
- **Costes de envío por producto**
- **Direcciones de facturación y envío separadas**; notas del cliente y notas internas del administrador

### 📊 Estadísticas
- **Panel** con ingresos, pedidos del mes y un gráfico por periodo, también en el panel de control de e107

### 🎨 Personalización
- **Plantillas** de tienda, carrito, área del cliente, correos y factura; el tema puede sobrescribirlas en `<tema>/templates/vstore/`
- **Shortcodes** para diseños propios
- **CSS personalizado** desde el administrador
- **Diseño adaptable** con marcado Bootstrap (temas Bootstrap 3 y 5)
- **Totalmente traducible**: administración, tienda, correos, facturas y mensajes salen de archivos de idioma (español, inglés y portugués; el resto usa inglés)

### 🔧 Administración
- **Área de administración** de ventas, productos, categorías, variaciones, cupones, clientes, pasarelas, facturas y correos
- Pestañas **Guía de Usuario** y **Acerca de**, y una barra lateral de ayuda contextual
- **Plantillas de correo** por estado del pedido (confirmación, completado, cancelado, reembolsado), adaptables a móvil, con botón de restablecer
- **Facturas en PDF** creadas con TCPDF (instalado por Composer): prefijo, siguiente número, bloques del pie, vista previa; se adjuntan al correo y el cliente puede descargarlas
- **Permisos** para las acciones de administración

---

## 🆕 Novedades de la v2.1.0

- **Modo presupuesto**: los productos sin precio se piden como presupuesto, en el mismo carrito que los productos a la venta.
- **Tienda rediseñada** para Bootstrap 5: mosaicos de categoría, tarjetas de producto, barra de categorías, menús, carrito, checkout con resumen del pedido y panel del cliente con cifras y gráfico.
- **Efecto al añadir al carrito**: el producto vuela al icono del carrito, el contador pulsa y un aviso ofrece ir al carrito.
- **Administración**: nueva tarjeta de *presupuestos* en las estadísticas y filtro *Presupuestos* en los pedidos.

## Novedades anteriores: v2.0.0

- Administración y tienda **totalmente traducibles** (español, inglés y portugués).
- **Mollie MB WAY y Multibanco**, teléfono enviado a Mollie, **webhook** de Mollie y botón *Comprobar el pago en Mollie*.
- **IVA de la UE reescrito**: precios netos, VIES, inversión del sujeto pasivo, IVA de origen opcional.
- **Nuevo diseño de correos** y **facturas PDF integradas** (TCPDF 6.11 por Composer; ya no hace falta el plugin *pdf* de e107).
- **Capa de lógica de negocio** en `src/` (espacio de nombres `Vstore\`) con 242 pruebas automáticas (`php tests/run.php`). `vstore.class.php` conserva sus métodos públicos, por lo que los temas y plugins siguen funcionando.
- **Revisión de seguridad**: escape de salidas y de SQL, carrito limitado al visitante, credenciales ocultas en los registros, `composer audit` sin avisos.
- **Eliminados** Stripe, Braintree, CoinPayments y StarkPay (no tenían un checkout funcional). Las tarjetas las cubre Mollie.
- Nuevo **manual de usuario** ([docs/user-manual.html](docs/user-manual.html)) y **notas de la versión** ([docs/RELEASE_NOTES_v2.0.0.md](docs/RELEASE_NOTES_v2.0.0.md)).

### Actualizar desde la 1.x
1. Sustituya la carpeta del plugin (o haga `git pull`) y ejecute `composer install --no-dev --optimize-autoloader`.
2. En *Vstore → Plantillas de correo* y *Configuración de Facturas*, pulse **Restablecer plantilla** si guardó plantillas propias, para obtener el diseño nuevo y la factura corregida.
3. Desinstale el plugin *pdf* de e107 si no lo usa para otra cosa.
4. Para MB WAY, Multibanco y los demás métodos de Mollie solo en euros, ponga la moneda de la tienda en **EUR**.

---

## 🚀 Instalación

### Requisitos
- **e107 CMS**: v2.3.2 o superior ([Descargar](https://github.com/e107inc/e107))
- **PHP**: 7.4 a 8.4
- **Extensiones PHP**: SOAP (validación VIES), cURL (pasarelas de pago), JSON, mbstring, GD (imágenes y logotipo de la factura)
- **Base de datos**: MySQL 5.7+ / MariaDB 10.2+
- **Composer** (solo si instala desde el repositorio de código fuente)

### Pasos de instalación

1. **Obtenga el plugin**
   - **Paquete de la versión (recomendado)**: descargue `vstore-2.1.0.zip` desde la [página de Releases](https://github.com/e107-Kanonimpresor/vstore/releases) y extráigalo en `e107_plugins/` (ya incluye `vendor/`).
   - **Desde el código fuente**:
     ```bash
     cd e107_plugins
     git clone https://github.com/e107-Kanonimpresor/vstore.git
     cd vstore
     composer install --no-dev --optimize-autoloader
     ```

   > **⚠️ IMPORTANTE**: el repositorio de código fuente no incluye la carpeta `vendor/`. Sin `composer install` verá `Failed opening required 'vendor/autoload.php'`.

2. **Instale en e107**: *Admin → Plugins → Gestionar plugins → Vstore → Instalar*.

3. **Configure**: *Admin → Vstore → Preferencias*: moneda, formato de precio, unidad de peso, dirección de la tienda, envíos, impuestos, correos y checkout. La pestaña **Guía de Usuario** le guía paso a paso.

4. **Pasarelas de pago**: *Admin → Vstore → Pasarelas de Pago*. Introduzca las credenciales de al menos una y actívela. Pruébela antes en modo sandbox/pruebas.

5. **Cree categorías y productos**: *Admin → Vstore → Categorías / Productos*.

6. **Pruebe el checkout** con un pedido de prueba y sígalo en *Ventas*.

---

## 📖 Documentación

- [Manual de usuario](docs/user-manual.html)
- [Notas de la versión 2.0.0](docs/RELEASE_NOTES_v2.0.0.md) y [CHANGELOG](CHANGELOG.md)
- [Facturas](wiki/invoice.md)
- [Guía de pruebas](docs/TESTING_GUIDE.md)
- [Notas de desarrollo](DEV_NOTES.md) y [plan de actualización](docs/PLAN_ACTUALIZACION_V2.md)

---

## 🔧 Configuración

### Configuración de impuestos (ejemplo IVA UE)

```
Admin → Vstore → Preferencias → Impuestos
País del negocio:               PT (Portugal)
Cálculo de impuestos:           Activado
Comprobar NIF/IVA (VIES):       Activado
Clases de impuesto:             valores decimales, p. ej. 0.23 para el 23%

Los precios son netos; se añade el IVA del país de destino:
- Cliente de PT:                                  23% (su tipo local)
- Consumidor de ES:                               tipo español
- Empresa de DE con NIF/IVA válido en VIES:       0% (inversión del sujeto pasivo)
- Cliente de EE. UU.:                             0% (fuera de la UE)
```

### Pasarelas de pago

```
PayPal        → modo (Sandbox / Live) y credenciales de API
Mollie        → clave de API (test_... / live_...) y métodos de pago a ofrecer
Transferencia → los datos bancarios que verá el cliente
```

---

## 🛠️ Personalización

Las plantillas están en `templates/`. Para cambiar una sin tocar el plugin, cópiela en su tema como `<tema>/templates/vstore/<nombre>_template.php`:

```php
// <tema>/templates/vstore/vstore_template.php
$VSTORE_TEMPLATE['list']['item'] = '
<div class="product-card">
    {ITEM_PIC: w=300&h=300&crop=1}
    <h3>{ITEM_NAME}</h3>
    <p class="price">{ITEM_PRICE}</p>
    {ITEM_QTY}
    {ITEM_ADDTOCART}
</div>
';
```

Los correos usan `vstore_email_layout_template.php` (documento) y `vstore_email_template.php` (cuerpos); la factura usa `vstore_invoice_template.php`.

---

## 🤝 Contribuir

### Reportar errores
- Revise las [incidencias existentes](https://github.com/e107-Kanonimpresor/vstore/issues)
- Abra una nueva con: descripción clara, pasos para reproducirlo, versiones de e107, PHP y del plugin, y cualquier mensaje de error o captura

### Solicitudes de características
- Abra una incidencia con la etiqueta `enhancement` y describa el caso de uso

### Pull Requests
1. Haga un fork del repositorio
2. Cree una rama: `git checkout -b feature/amazing-feature`
3. Ejecute las pruebas: `php tests/run.php`
4. Confirme y suba su rama, y abra un Pull Request

### Pruebas
- Pruebe en varias versiones de PHP (7.4 a 8.4)
- Pruebe las pasarelas en modo sandbox
- Informe de problemas de compatibilidad

### Localización
- Los archivos de idioma están en `languages/<Idioma>/` (`<Idioma>_<área>.php`, formato array, inglés como respaldo)
- Las constantes usan el prefijo `LAN_VSTORE_*`

---

## 📝 Historial de Cambios

Consulte [CHANGELOG.md](CHANGELOG.md) para la lista completa de cambios.

**v2.0.0 (2026-10-17)**: traducciones, Mollie MB WAY/Multibanco y webhook, IVA de la UE reescrito, nuevos correos, facturas PDF integradas, capa de negocio con pruebas, revisión de seguridad. Vea las [notas de la versión](docs/RELEASE_NOTES_v2.0.0.md).

---

## 🐛 Problemas Conocidos

- Cancelar un pedido a mano desde el administrador no devuelve el stock (los pagos fallidos o caducados sí).
- Las credenciales guardadas de las pasarelas eliminadas siguen en las preferencias de la base de datos; se ignoran.
- El manual de usuario solo está en español.
- En localhost, Mollie no puede enviar el webhook: use el botón *Comprobar el pago en Mollie*.
- El correo en localhost necesita un servidor SMTP (por ejemplo smtp4dev o Mailtrap).
- Vacíe la caché de e107 tras cambiar plantillas.
- Las facturas PDF necesitan `vendor/tecnickcom/tcpdf` (ejecute `composer install`).

---

## 📄 Licencia

Este proyecto se distribuye bajo la **Licencia Pública General de GNU v3.0** - consulte el archivo [LICENSE](LICENSE).

- ✅ Libre para uso comercial y no comercial
- ✅ Puede modificar y distribuir el código
- ✅ Las modificaciones deben publicarse bajo la misma licencia GPL-3.0

---

## 🙏 Créditos y Agradecimientos

### Mantenedores
- Desarrolladores originales: CaMerOn, Achim Ennenbach y la [comunidad e107](https://e107.org)
- Mantenedor actual: [Kanonimpresor](https://marketingdeperformance.online) — Marketing de Performance ([GitHub](https://github.com/e107-Kanonimpresor))

### Dependencias
- [e107 CMS](https://github.com/e107inc/e107) - Sistema de gestión de contenidos
- [Omnipay](https://omnipay.thephpleague.com/) con sus controladores de PayPal y Mollie - abstracción de pagos
- [ibericode/vat](https://github.com/ibericode/vat) - Tipos de IVA de la UE y validación VIES
- [TCPDF](https://tcpdf.org/) - Facturas en PDF

### Agradecimientos Especiales
- A la comunidad e107 por las pruebas y los comentarios
- A los proveedores de pago por sus entornos sandbox
- A quienes reportan errores y envían pull requests

---

## 📞 Soporte y Comunidad

- **Documentación**: [manual de usuario](docs/user-manual.html) y la pestaña *Guía de Usuario* del administrador
- **Incidencias en GitHub**: [reportar errores](https://github.com/e107-Kanonimpresor/vstore/issues)
- **Comunidad e107**: [Q&A - Plugins](https://github.com/e107inc/e107/discussions/categories/q-a-plugins) y los [foros de e107.org](https://e107.org/forum)

---

## 🗺️ Hoja de Ruta

### ✅ Entregado en la 2.0.0
- [x] Compatibilidad total con PHP 8.x (7.4 a 8.4)
- [x] Sistema de cupones mejorado
- [x] Localización completa (español, inglés y portugués)
- [x] Mollie MB WAY y Multibanco, facturas PDF integradas, IVA de la UE reescrito, nuevo diseño de correos

### 🚧 En Desarrollo
- [x] Modo **presupuesto** de producto (2.1.0): si deja el precio vacío, el producto muestra un botón "Presupuesto / Quote / Orçamento" en lugar de "Añadir al carrito"; los presupuestos se guardan en el administrador para pasarlos después a una app de facturación certificada
- [ ] Más mejoras de los correos
- [ ] Más idiomas (traducciones de la comunidad)
- [ ] Integración con aplicación móvil

### Características Planificadas
- [ ] Más pasarelas de pago (Authorize.net, Square, etc.)
- [ ] Panel de análisis avanzado con gráficos
- [ ] Reseñas y valoraciones de productos
- [ ] Lista de deseos
- [ ] Recuperación de carritos abandonados
- [ ] Soporte de marketplace multivendedor
- [ ] Pagos por suscripción/recurrentes
- [ ] Tarjetas regalo y vales

---

## 📊 De un vistazo

- **Idiomas**: español, inglés y portugués
- **Métodos de pago**: PayPal, Mollie (tarjetas y métodos locales), transferencia bancaria
- **Pruebas**: 242 comprobaciones automáticas (`php tests/run.php`), PHP 7.4, 8.2 y 8.4

---

<div align="center">

**Hecho con ❤️ para la comunidad de e107 CMS**

[Reportar error](https://github.com/e107-Kanonimpresor/vstore/issues) · [Solicitar característica](https://github.com/e107-Kanonimpresor/vstore/issues) · [Releases](https://github.com/e107-Kanonimpresor/vstore/releases)

Copyright (c) 2026 Kanonimpresor / Marketing de Performance  
Plugin desarrollado para e107 CMS — https://marketingdeperformance.online

</div>
