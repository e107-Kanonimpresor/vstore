# Plugin Vstore para e107

#### (Elija su idioma / Choose your language / Escolha o seu idioma)

[![Language-English](https://img.shields.io/badge/Language-English-blue)](README.md) 
[![Language-Português](https://img.shields.io/badge/Language-Português-green)](README.pt-PT.md) 
[![Language-Español](https://img.shields.io/badge/Language-Español-red)](README.es-ES.md) 

---

[![e107](https://img.shields.io/badge/e107-v2.3.0+-blue.svg)](https://e107.org)
[![PHP](https://img.shields.io/badge/PHP-7.4%2B%20|%208.x-purple.svg)](https://php.net)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-3.x%20|%204.x%20|%205.x-7952B3.svg)](https://getbootstrap.com)
[![License](https://img.shields.io/badge/License-GPL--3.0-green.svg)](LICENSE)
[![Join the chat at https://gitter.im/e107inc/vstore](https://badges.gitter.im/e107inc/vstore.svg)](https://gitter.im/e107inc/vstore?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

---

## 📋 Descripción

**Vstore** es una solución de comercio electrónico completa para [e107 v2.x](https://e107.org) Bootstrap CMS. Diseñado con flexibilidad y facilidad de uso, proporciona todo lo necesario para gestionar una tienda online profesional directamente desde tu sitio web e107.

Ya sea que vendas productos físicos, descargas digitales o servicios, Vstore se integra perfectamente con tu sitio e107, ofreciendo características potentes como múltiples pasarelas de pago, cálculo avanzado de impuestos, gestión de cupones y seguimiento de inventario.

---

## ✨ Características Principales

### 🛒 Carrito de Compras y Checkout
- **Carrito Dinámico**: Actualizaciones en tiempo real con funcionalidad AJAX
- **Compra como Invitado**: Permite compras sin registro de usuario
- **Checkout Multi-paso**: Flujo de compra limpio e intuitivo
- **Historial de Pedidos**: Panel del cliente con seguimiento de pedidos
- **Descargas Digitales**: Soporte para productos descargables
- **Variaciones de Productos**: Talla, color y atributos personalizados

### 💳 Procesamiento de Pagos
- **Múltiples Pasarelas de Pago**:
  - PayPal (Express Checkout & Standard)
  - Stripe (Tarjetas de Crédito/Débito)
  - Mollie (iDEAL, Bancontact y más de 20 métodos)
  - Braintree (PayPal y Tarjetas de Crédito)
  - CoinPayments (Criptomonedas)
  - Transferencia Bancaria (Manual)
- **Transacciones Seguras**: Procesamiento compatible con PCI
- **Seguimiento de Estado**: Nuevo, En Proceso, Completado, Reembolsado, etc.

### 💰 Precios y Descuentos
- **Sistema de Cupones**:
  - Descuentos por porcentaje o monto fijo
  - Restricciones por rango de fechas
  - Inclusión/exclusión de categorías/productos
  - Límites de uso (global y por usuario)
  - Requisitos de pedido mínimo
- **Cálculo de Impuestos**:
  - Soporte para IVA de la UE con validación VIES
  - Múltiples tasas de impuestos (estándar, reducida, súper reducida)
  - Mecanismo de inversión del sujeto pasivo B2B
  - Clases de impuestos específicas por país
  - Cálculo automático basado en ubicación

### 📦 Gestión de Productos
- **Productos Ilimitados**: Sin restricciones en el tamaño del catálogo
- **Categorías y Subcategorías**: Jerarquía organizada de productos
- **Variaciones de Productos**: Gestión de talla, color y atributos personalizados
- **Seguimiento de Inventario**: Gestión de stock con alertas de bajo stock
- **Galerías de Imágenes**: Múltiples imágenes por producto con zoom
- **URLs Amigables para SEO**: URLs limpias para mejor posicionamiento
- **Productos Relacionados**: Recomendaciones de venta cruzada

### 🚚 Envío y Cumplimiento
- **Tarifas de Envío Flexibles**:
  - Tarifa plana
  - Cálculo basado en peso
  - Opciones de envío gratuito
  - Costos de envío por producto
- **Gestión de Direcciones**: Direcciones de facturación y envío separadas
- **Notas de Pedido**: Notas del cliente y notas internas del administrador

### 📊 Análisis e Informes
- **Estadísticas de Ventas**: Seguimiento de ingresos y tendencias
- **Informes de Pedidos**: Información detallada de pedidos
- **Rendimiento de Productos**: Más vendidos e informes de inventario
- **Información de Clientes**: Historial de compras y comportamiento

### 🎨 Personalización
- **Sistema de Plantillas**: Plantillas HTML completamente personalizables
- **Shortcodes**: Amplia biblioteca de shortcodes para diseños personalizados
- **CSS Personalizado**: Añade tus propios estilos
- **Diseño Responsive**: Compatible con móviles desde el inicio
- **Soporte Multi-idioma**: Listo para traducción con archivos LAN

### 🔧 Características de Administración
- **Panel Intuitivo**: Gestión de pedidos, productos y clientes
- **Acciones en Masa**: Editar múltiples elementos simultáneamente
- **Plantillas de Email**: Emails de confirmación personalizables
- **Generación de Facturas**: Facturas PDF automáticas (requiere plugin PDF)
- **Permisos de Usuario**: Control de acceso basado en roles
- **Integración de Búsqueda**: Productos buscables vía búsqueda e107

---

## 🚀 Instalación

### Requisitos
- **e107 CMS**: v2.3.0 o superior ([Descargar](https://github.com/e107inc/e107))
- **PHP**: 7.4 o superior (compatible con PHP 8.x)
- **Extensiones PHP**:
  - SOAP (para validación de IVA)
  - cURL (para pasarelas de pago)
  - JSON
  - GD o Imagick (para procesamiento de imágenes)
- **Base de Datos**: MySQL 5.7+ / MariaDB 10.2+
- **Opcional**: [Plugin PDF](https://github.com/e107inc/pdf) para generación de facturas

### Pasos de Instalación

1. **Descargar el Plugin**:
   ```bash
   cd e107_plugins
   git clone https://github.com/Kanonimpresor/vstore.git
   ```

2. **Instalar Dependencias** (si instalas desde código fuente):
   ```bash
   cd vstore
   composer install
   ```

3. **Instalar vía Admin de e107**:
   - Navegar a: **Admin → Plugins → Gestionar Plugins**
   - Encontrar "Vstore" en la lista
   - Hacer clic en **Instalar**

4. **Configurar Ajustes**:
   - Ir a: **Admin → Vstore → Settings**
   - Configurar:
     - Información de la tienda (nombre, dirección, NIF)
     - Configuración de impuestos (activar IVA, tasas)
     - Pasarelas de pago (credenciales API)
     - Opciones de envío
     - Plantillas de email

5. **Crear Productos**:
   - Navegar a: **Admin → Vstore → Productos**
   - Añadir tu primer producto con imágenes y precios

6. **Probar Checkout**:
   - Añadir un producto al carrito
   - Completar un pedido de prueba (usar modo sandbox para pasarelas)

---

## 📖 Documentación

### Guías de Inicio Rápido
- [Crear Tu Primer Producto](wiki/creating-products.md)
- [Configurar Pasarelas de Pago](wiki/payment-gateways.md)
- [Configurar Impuestos](wiki/tax-configuration.md)
- [Gestionar Cupones](wiki/coupons.md)
- [Personalizar Plantillas](wiki/templates.md)

### Temas Avanzados
- [Generación de Facturas](wiki/invoice.md)
- [Shortcodes Personalizados](wiki/shortcodes.md)
- [Esquema de Base de Datos](wiki/database.md)
- [Integración API](wiki/api.md)
- [Mejores Prácticas de Seguridad](wiki/security.md)

### Recursos para Desarrolladores
- [Arquitectura del Plugin](wiki/architecture.md)
- [Extender Vstore](wiki/extending.md)
- [Desarrollo de Pasarelas de Pago](wiki/gateway-development.md)
- [Estándares de Código](wiki/coding-standards.md)

---

## 🔧 Configuración

### Configuración de Impuestos (Ejemplo IVA UE)

```php
// Admin → Vstore → Settings → Tax
País del Negocio: ES (España)
Cálculo de Impuestos: Activado
Verificar NIF-IVA: Activado

Clases de Impuestos:
- Estándar: 21% (España)
- Reducido: 10%
- Súper Reducido: 4%

// Manejo automático de IVA UE:
// - Cliente ES (B2C): 21% IVA
// - Cliente FR (B2C): Tasa de IVA francesa
// - Cliente DE con NIF válido (B2B): 0% (inversión sujeto pasivo)
// - Cliente US: 0% (fuera de la UE)
```

### Configuración de Pasarelas de Pago

#### PayPal
```php
Modo: Sandbox / Live
Client ID: TU_PAYPAL_CLIENT_ID
Client Secret: TU_PAYPAL_SECRET
```

#### Stripe
```php
Modo: Test / Live
Publishable Key: pk_test_...
Secret Key: sk_test_...
```

#### Mollie
```php
API Key: test_... / live_...
Métodos Habilitados: iDEAL, Tarjeta de Crédito, Bancontact
```

---

## 🤝 Contribuir

¡Damos la bienvenida a contribuciones de la comunidad! Aquí puedes ayudar:

### Reportar Errores
- Verificar [issues existentes](https://github.com/Kanonimpresor/vstore/issues)
- Crear un nuevo issue con:
  - Descripción clara del problema
  - Pasos para reproducir
  - Versión de e107, PHP y plugin
  - Mensajes de error o capturas de pantalla

### Solicitudes de Características
- Abrir un issue con la etiqueta `enhancement`
- Describir la característica y caso de uso
- Explicar por qué beneficiaría a otros usuarios

### Pull Requests
1. Fork del repositorio
2. Crear rama de característica: `git checkout -b feature/caracteristica-increible`
3. Commit de cambios: `git commit -m 'Añadir característica increíble'`
4. Push a la rama: `git push origin feature/caracteristica-increible`
5. Abrir un Pull Request

---

## 📝 Historial de Cambios

Ver [CHANGELOG.md](CHANGELOG.md) para la lista detallada de cambios, correcciones y nuevas características.

**Últimas Actualizaciones (Noviembre 2025)**:
- ✅ Compatibilidad corregida con ibericode/vat v2.x
- ✅ Resuelto vaciado de carrito al aplicar cupones
- ✅ Corregida validación de datos de envío en checkout
- ✅ Mejoras de compatibilidad con PHP 8.x
- ✅ Mejorado manejo de errores y validación

---

## 📄 Licencia

Este proyecto está licenciado bajo **GNU General Public License v3.0** - ver el archivo [LICENSE](LICENSE) para detalles.

---

## 🙏 Créditos y Agradecimientos

### Mantenedores
- Desarrollador Original: [Comunidad e107](https://e107.org)
- Mantenedor Actual: [Kanonimpresor](https://github.com/Kanonimpresor)

### Dependencias
- [e107 CMS](https://github.com/e107inc/e107) - Sistema de Gestión de Contenidos
- [Omnipay](https://omnipay.thephpleague.com/) - Abstracción de procesamiento de pagos
- [ibericode/vat](https://github.com/ibericode/vat) - Validación de IVA UE
- [TCPDF](https://tcpdf.org/) - Generación de PDF

---

## 📞 Soporte y Comunidad

### Obtener Ayuda
- **Documentación**: Consultar la [Wiki](wiki/) primero
- **Chat Comunitario**: [Canal Gitter](https://gitter.im/e107inc/vstore)
- **Foros e107**: [Foros e107.org](https://e107.org/forum)
- **GitHub Issues**: [Reportar errores](https://github.com/Kanonimpresor/vstore/issues)

---

<div align="center">

**Hecho con ❤️ para la Comunidad e107 CMS**

[Reportar Error](https://github.com/Kanonimpresor/vstore/issues) · [Solicitar Característica](https://github.com/Kanonimpresor/vstore/issues) · [Documentación](wiki/)

</div>
