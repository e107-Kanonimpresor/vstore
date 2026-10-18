<?php
if (!defined('e107_INIT')) { exit; }

// START
define('LAN_VSTORE_GUIDE_START_TAB',    'Inicio rápido');
define('LAN_VSTORE_GUIDE_START_TITLE',  'Abre tu tienda en seis pasos');
define('LAN_VSTORE_GUIDE_START_INTRO',  'Sigue estos pasos en orden. Cada uno corresponde a una pestaña de esta área de administración.');
define('LAN_VSTORE_GUIDE_START_S1',     'Abre <strong>Preferencias</strong> y define la moneda, el formato de precio y la unidad de peso en la pestaña General.');
define('LAN_VSTORE_GUIDE_START_S2',     'Crea al menos una categoría en <strong>Categorías</strong>.');
define('LAN_VSTORE_GUIDE_START_S3',     'Añade tus productos en <strong>Productos</strong>. Usa un código único para cada producto.');
define('LAN_VSTORE_GUIDE_START_S4',     'Configura <strong>Envíos</strong> e <strong>Impuestos</strong> en las pestañas de Preferencias, si tu tienda los necesita.');
define('LAN_VSTORE_GUIDE_START_S5',     'Introduce las credenciales de al menos una pasarela en <strong>Pasarelas de Pago</strong> y actívala.');
define('LAN_VSTORE_GUIDE_START_S6',     'Realiza un pedido de prueba y síguelo en <strong>Ventas</strong>.');

// CATALOG
define('LAN_VSTORE_GUIDE_CATALOG_TAB',    'Catálogo');
define('LAN_VSTORE_GUIDE_CATALOG_TITLE',  'Categorías, productos y variaciones');
define('LAN_VSTORE_GUIDE_CATALOG_INTRO',  'El catálogo se construye con tres elementos relacionados.');
define('LAN_VSTORE_GUIDE_CATALOG_CAT_H',  'Categorías');
define('LAN_VSTORE_GUIDE_CATALOG_CAT_T',  'Las categorías organizan la tienda y pueden anidarse. Restringe una categoría a una clase de usuario para ocultarla al resto de visitantes.');
define('LAN_VSTORE_GUIDE_CATALOG_PROD_H', 'Productos');
define('LAN_VSTORE_GUIDE_CATALOG_PROD_T', 'Cada producto tiene un código único (mayúsculas, dígitos y guiones), un precio, un inventario y una clase de impuesto. Usa <code>-1</code> como inventario para stock ilimitado. Las imágenes y los vídeos se añaden en su propia pestaña. Si dejas el precio vacío (no 0), el producto se ofrece como <strong>presupuesto</strong>: no muestra precio y el botón pasa a ser "Presupuesto".');
define('LAN_VSTORE_GUIDE_CATALOG_VAR_H',  'Variaciones');
define('LAN_VSTORE_GUIDE_CATALOG_VAR_T',  'Crea un grupo de variaciones (por ejemplo tallas) una vez y asigna hasta dos grupos a cada producto. Una variación puede modificar el precio con un importe fijo o un porcentaje y puede llevar su propio inventario.');

// COUPONS
define('LAN_VSTORE_GUIDE_COUPONS_TAB',    'Cupones');
define('LAN_VSTORE_GUIDE_COUPONS_TITLE',  'Cupones de descuento');
define('LAN_VSTORE_GUIDE_COUPONS_INTRO',  'Los clientes introducen los cupones en el carrito.');
define('LAN_VSTORE_GUIDE_COUPONS_TYPES_H','Tipos de descuento');
define('LAN_VSTORE_GUIDE_COUPONS_TYPES_T','Un cupón porcentual descuenta una parte de cada artículo elegible. Un cupón fijo nunca descuenta más que el precio del artículo.');
define('LAN_VSTORE_GUIDE_COUPONS_RULES_H','Restricciones');
define('LAN_VSTORE_GUIDE_COUPONS_RULES_T','Define las fechas de inicio y fin e incluye o excluye categorías en la pestaña Restricciones. Las categorías excluidas siempre prevalecen sobre las incluidas.');
define('LAN_VSTORE_GUIDE_COUPONS_LIMITS_H','Límites');
define('LAN_VSTORE_GUIDE_COUPONS_LIMITS_T','Limita cuántas veces puede usarse un cupón en total y por usuario en la pestaña Límites. Usa <code>-1</code> para no limitar.');

// TAX
define('LAN_VSTORE_GUIDE_TAX_TAB',        'Impuestos e IVA');
define('LAN_VSTORE_GUIDE_TAX_TITLE',      'Cálculo de impuestos');
define('LAN_VSTORE_GUIDE_TAX_INTRO',      'El impuesto se calcula en el checkout a partir del país de facturación indicado por el cliente.');
define('LAN_VSTORE_GUIDE_TAX_SETUP_H',    'Configuración');
define('LAN_VSTORE_GUIDE_TAX_SETUP_T',    'En Preferencias, abre la pestaña Impuestos, activa el cálculo de impuestos y selecciona el país de tu negocio. Activa la comprobación online del NIF/IVA si vendes a empresas de la UE.');
define('LAN_VSTORE_GUIDE_TAX_CLASSES_H',  'Clases de impuesto');
define('LAN_VSTORE_GUIDE_TAX_CLASSES_T',  'Introduce los valores como decimales, por ejemplo <code>0.19</code> para 19%. Asigna una clase de impuesto a cada producto en la pestaña Avanzado.');
define('LAN_VSTORE_GUIDE_TAX_SCEN_H',     'Qué paga el cliente');
define('LAN_VSTORE_GUIDE_TAX_SC1',        'Cliente en tu país: se aplica tu tipo local.');
define('LAN_VSTORE_GUIDE_TAX_SC2',        'Consumidor de otro país de la UE: se aplica el tipo del país de destino.');
define('LAN_VSTORE_GUIDE_TAX_SC3',        'Empresa de la UE con NIF/IVA válido: 0% (inversión del sujeto pasivo).');
define('LAN_VSTORE_GUIDE_TAX_SC4',        'Cliente fuera de la UE: 0% (exportación).');

// SHIPPING
define('LAN_VSTORE_GUIDE_SHIP_TAB',       'Envíos');
define('LAN_VSTORE_GUIDE_SHIP_TITLE',     'Costes de envío');
define('LAN_VSTORE_GUIDE_SHIP_INTRO',     'Activa los envíos en la pestaña Envíos de Preferencias y elige un método de cálculo.');
define('LAN_VSTORE_GUIDE_SHIP_M1',        '<strong>Suma de todos los artículos</strong>: suma el coste de envío de cada artículo del carrito.');
define('LAN_VSTORE_GUIDE_SHIP_M2',        '<strong>Suma de artículos únicos</strong>: suma el coste de envío de cada producto distinto una sola vez.');
define('LAN_VSTORE_GUIDE_SHIP_M3',        '<strong>Escalonado</strong>: los costes dependen de umbrales del precio o peso del pedido. Empieza por el umbral más bajo y haz que el último supere tu pedido más grande.');

// GATEWAYS
define('LAN_VSTORE_GUIDE_GATE_TAB',       'Pagos');
define('LAN_VSTORE_GUIDE_GATE_TITLE',     'Pasarelas de pago');
define('LAN_VSTORE_GUIDE_GATE_INTRO',     'Cada pasarela tiene su propia pestaña con sus credenciales y un interruptor de activación.');
define('LAN_VSTORE_GUIDE_GATE_G1',        '<strong>PayPal</strong> (estándar y REST): requiere credenciales de API de tu cuenta de PayPal.');
define('LAN_VSTORE_GUIDE_GATE_G2',        '<strong>Mollie</strong>: requiere una clave de API y permite elegir los métodos de pago ofrecidos en el checkout.');
define('LAN_VSTORE_GUIDE_GATE_G3',        '<strong>Transferencia bancaria</strong>: el pedido queda pendiente hasta que lo marques como pagado.');
define('LAN_VSTORE_GUIDE_GATE_NOTE',      'Prueba siempre una pasarela en modo sandbox antes de pasar a producción.');

// ORDERS
define('LAN_VSTORE_GUIDE_ORDERS_TAB',     'Pedidos');
define('LAN_VSTORE_GUIDE_ORDERS_TITLE',   'Pedidos, emails y facturas');
define('LAN_VSTORE_GUIDE_ORDERS_INTRO',   'Todo lo relacionado con una venta se gestiona desde la pestaña Ventas.');
define('LAN_VSTORE_GUIDE_ORDERS_ST_H',    'Estado del pedido');
define('LAN_VSTORE_GUIDE_ORDERS_ST_T',    'Un pedido pasa por Nuevo, En proceso, En espera, Completado, Cancelado y Reembolsado. Los cambios de estado actualizan el inventario y envían el email correspondiente. Las solicitudes de presupuesto siguen su propio flujo: Presupuesto solicitado, Migrado a la app, Presupuesto aprobado y Facturado.');
define('LAN_VSTORE_GUIDE_ORDERS_EM_H',    'Plantillas de email');
define('LAN_VSTORE_GUIDE_ORDERS_EM_T',    'Hay una plantilla para cada cambio de estado. Usa el botón de restablecer para recuperar el texto original.');
define('LAN_VSTORE_GUIDE_ORDERS_INV_H',   'Facturas');
define('LAN_VSTORE_GUIDE_ORDERS_INV_T',   'Las facturas PDF las crea el propio Vstore (TCPDF). Define el prefijo de numeración, el siguiente número y el pie en Configuración de Facturas y revisa el resultado en la pestaña de vista previa.');

// TROUBLESHOOTING
define('LAN_VSTORE_GUIDE_TROUBLE_TAB',    'Solución de problemas');
define('LAN_VSTORE_GUIDE_TROUBLE_TITLE',  'Problemas habituales');
define('LAN_VSTORE_GUIDE_TROUBLE_INTRO',  'Revisa estos puntos antes de solicitar soporte.');
define('LAN_VSTORE_GUIDE_TROUBLE_Q1',     'Un error fatal menciona una clase o un archivo de autoload inexistente');
define('LAN_VSTORE_GUIDE_TROUBLE_A1',     'Ejecuta <code>composer install --no-dev --optimize-autoloader</code> dentro de la carpeta del plugin. El directorio <code>vendor</code> no se incluye en el repositorio.');
define('LAN_VSTORE_GUIDE_TROUBLE_Q2',     'Las facturas no se crean en PDF');
define('LAN_VSTORE_GUIDE_TROUBLE_A2',     'Ejecuta "composer install" en e107_plugins/vstore y activa las facturas PDF en Configuración de Facturas.');
define('LAN_VSTORE_GUIDE_TROUBLE_Q3',     'Se aplica un tipo de impuesto incorrecto');
define('LAN_VSTORE_GUIDE_TROUBLE_A3',     'Revisa el país del negocio, la clase de impuesto del producto y el país indicado en el formulario de facturación.');
define('LAN_VSTORE_GUIDE_TROUBLE_Q4',     'Necesito más información sobre lo que ocurre');
define('LAN_VSTORE_GUIDE_TROUBLE_A4',     'Descomenta la línea <code>e_DEBUG_VSTORE</code> en <code>e_header.php</code> para activar el registro de depuración y vuelve a quitarla en producción.');
