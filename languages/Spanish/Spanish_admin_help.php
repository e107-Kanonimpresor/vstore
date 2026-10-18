<?php
if (!defined('e107_INIT')) { exit; }

// Solo texto plano (se permite <code>/<strong> en línea). La estructura HTML está en templates/vstore_guide_template.php.

// RESUMEN
define('LAN_VSTORE_GUIDE_OVERVIEW_TAB',    'Resumen');
define('LAN_VSTORE_GUIDE_OVERVIEW_TITLE',  'Vstore de un vistazo');
define('LAN_VSTORE_GUIDE_OVERVIEW_INTRO',  'Vstore convierte tu sitio e107 en una tienda online: catálogo, carrito, checkout, pagos, facturas y un área de cliente. Los productos sin precio se gestionan como solicitudes de presupuesto. Esta guía explica cada parte del área de administración.');
define('LAN_VSTORE_GUIDE_OVERVIEW_FLOW1',  'Preferencias');
define('LAN_VSTORE_GUIDE_OVERVIEW_FLOW2',  'Catálogo');
define('LAN_VSTORE_GUIDE_OVERVIEW_FLOW3',  'Pagos');
define('LAN_VSTORE_GUIDE_OVERVIEW_FLOW4',  'Pedido de prueba');
define('LAN_VSTORE_GUIDE_OVERVIEW_FLOW5',  'Ventas');
define('LAN_VSTORE_GUIDE_OVERVIEW_FEAT_H', 'Qué hace Vstore');
define('LAN_VSTORE_GUIDE_OVERVIEW_F1_T',   'Catálogo');
define('LAN_VSTORE_GUIDE_OVERVIEW_F1_D',   'Categorías anidadas, productos, variaciones, galería de imágenes y archivos descargables.');
define('LAN_VSTORE_GUIDE_OVERVIEW_F2_T',   'Presupuestos');
define('LAN_VSTORE_GUIDE_OVERVIEW_F2_D',   'Un producto sin precio se solicita en lugar de comprarse, con su propio flujo de trabajo.');
define('LAN_VSTORE_GUIDE_OVERVIEW_F3_T',   'Carrito mixto');
define('LAN_VSTORE_GUIDE_OVERVIEW_F3_D',   'Productos para comprar y para presupuestar comparten un carrito, cada bloque con su propio botón.');
define('LAN_VSTORE_GUIDE_OVERVIEW_F4_T',   'Impuestos e IVA UE');
define('LAN_VSTORE_GUIDE_OVERVIEW_F4_D',   'Tasas por país y clase de impuesto, inversión del sujeto pasivo con un NIF-IVA válido.');
define('LAN_VSTORE_GUIDE_OVERVIEW_F5_T',   'Pagos');
define('LAN_VSTORE_GUIDE_OVERVIEW_F5_D',   'PayPal, Mollie y transferencia bancaria, cada uno se activa por separado.');
define('LAN_VSTORE_GUIDE_OVERVIEW_F6_T',   'Facturas PDF');
define('LAN_VSTORE_GUIDE_OVERVIEW_F6_D',   'Las crea el propio Vstore con numeración y pie de página propios.');
define('LAN_VSTORE_GUIDE_OVERVIEW_F7_T',   'Área de cliente');
define('LAN_VSTORE_GUIDE_OVERVIEW_F7_D',   'Pedidos, descargas, presupuestos abiertos y un gráfico de los últimos seis meses.');
define('LAN_VSTORE_GUIDE_OVERVIEW_F8_T',   'Estadísticas');
define('LAN_VSTORE_GUIDE_OVERVIEW_F8_D',   'Cajas de ventas y un contador de los presupuestos pendientes de respuesta.');
define('LAN_VSTORE_GUIDE_OVERVIEW_WHERE_H','Dónde encontrar cada cosa');
define('LAN_VSTORE_GUIDE_OVERVIEW_W1_N',   'Preferencias');
define('LAN_VSTORE_GUIDE_OVERVIEW_W1_D',   'Moneda, envío, impuestos, checkout y opciones de visualización.');
define('LAN_VSTORE_GUIDE_OVERVIEW_W2_N',   'Categorías y Productos');
define('LAN_VSTORE_GUIDE_OVERVIEW_W2_D',   'El catálogo, con imágenes, precios, inventario y variaciones.');
define('LAN_VSTORE_GUIDE_OVERVIEW_W3_N',   'Cupones');
define('LAN_VSTORE_GUIDE_OVERVIEW_W3_D',   'Códigos de descuento con fechas, categorías y límites de uso.');
define('LAN_VSTORE_GUIDE_OVERVIEW_W4_N',   'Pasarelas de Pago');
define('LAN_VSTORE_GUIDE_OVERVIEW_W4_D',   'Credenciales y activación de cada método de pago.');
define('LAN_VSTORE_GUIDE_OVERVIEW_W5_N',   'Ventas');
define('LAN_VSTORE_GUIDE_OVERVIEW_W5_D',   'Pedidos y presupuestos, con su estado, factura e historial.');
define('LAN_VSTORE_GUIDE_OVERVIEW_W6_N',   'Plantillas de Email y Configuración de Facturas');
define('LAN_VSTORE_GUIDE_OVERVIEW_W6_D',   'Los mensajes que reciben los clientes y el aspecto de la factura en PDF.');
define('LAN_VSTORE_GUIDE_OVERVIEW_TIP',    'La barra lateral de cada página de administración muestra un consejo breve sobre la página en la que estás y enlaces a la documentación y al gestor de incidencias.');

// INICIO RÁPIDO
define('LAN_VSTORE_GUIDE_START_TAB',       'Inicio rápido');
define('LAN_VSTORE_GUIDE_START_TITLE',     'Abre tu tienda en seis pasos');
define('LAN_VSTORE_GUIDE_START_INTRO',     'Sigue estos pasos en orden. Cada uno apunta a una entrada del menú de esta área de administración.');
define('LAN_VSTORE_GUIDE_START_S1_T',      'Define lo básico');
define('LAN_VSTORE_GUIDE_START_S1_D',      'Abre <strong>Preferencias</strong> y elige la moneda, el formato de precio y la unidad de peso en la pestaña General.');
define('LAN_VSTORE_GUIDE_START_S2_T',      'Crea categorías');
define('LAN_VSTORE_GUIDE_START_S2_D',      'Añade al menos una categoría en <strong>Categorías</strong>. Una imagen mejora el aspecto del mosaico de la tienda.');
define('LAN_VSTORE_GUIDE_START_S3_T',      'Añade productos');
define('LAN_VSTORE_GUIDE_START_S3_D',      'Crea tus productos en <strong>Productos</strong> con código único, precio, inventario e imágenes. Deja el precio vacío para ofrecer un presupuesto.');
define('LAN_VSTORE_GUIDE_START_S4_T',      'Envío e impuestos');
define('LAN_VSTORE_GUIDE_START_S4_D',      'Configura <strong>Envío</strong> e <strong>Impuestos</strong> en las pestañas de Preferencias, si tu tienda los necesita.');
define('LAN_VSTORE_GUIDE_START_S5_T',      'Activa un método de pago');
define('LAN_VSTORE_GUIDE_START_S5_D',      'Introduce las credenciales de al menos una pasarela en <strong>Pasarelas de Pago</strong> y actívala. Usa primero el modo sandbox.');
define('LAN_VSTORE_GUIDE_START_S6_T',      'Haz un pedido de prueba');
define('LAN_VSTORE_GUIDE_START_S6_D',      'Compra un producto desde la tienda, sigue el pedido en <strong>Ventas</strong> y revisa los emails y la factura.');
define('LAN_VSTORE_GUIDE_START_NOTE',      'Los enlaces de la tienda (tienda y carrito) se crean en la navegación del sitio al instalar el plugin. Si los visitantes no los ven, revisa quién puede verlos en los enlaces del sitio.');

// CATÁLOGO
define('LAN_VSTORE_GUIDE_CATALOG_TAB',     'Catálogo');
define('LAN_VSTORE_GUIDE_CATALOG_TITLE',   'Categorías, productos y variaciones');
define('LAN_VSTORE_GUIDE_CATALOG_INTRO',   'El catálogo se compone de tres elementos relacionados.');
define('LAN_VSTORE_GUIDE_CATALOG_CAT_H',   'Categorías');
define('LAN_VSTORE_GUIDE_CATALOG_CAT_T',   'Las categorías organizan la tienda y pueden anidarse. La tienda muestra las categorías raíz como mosaicos con imagen y número de productos. Restringe una categoría a una clase de usuario para ocultarla a los demás visitantes.');
define('LAN_VSTORE_GUIDE_CATALOG_PROD_H',  'Productos');
define('LAN_VSTORE_GUIDE_CATALOG_PROD_T',  'Cada producto tiene un código único (letras mayúsculas, dígitos y guiones), un precio, un inventario y una clase de impuesto. Usa <code>-1</code> como inventario para stock ilimitado.');
define('LAN_VSTORE_GUIDE_CATALOG_VAR_H',   'Variaciones');
define('LAN_VSTORE_GUIDE_CATALOG_VAR_T',   'Crea una vez un grupo de variaciones (por ejemplo tallas) y asigna hasta dos grupos a cada producto. Una variación puede cambiar el precio en un importe fijo o un porcentaje y llevar su propio inventario.');
define('LAN_VSTORE_GUIDE_CATALOG_IMG_H',   'Imágenes y galería');
define('LAN_VSTORE_GUIDE_CATALOG_IMG_T',   'Añade las imágenes de un producto en su pestaña Imágenes.');
define('LAN_VSTORE_GUIDE_CATALOG_IMG_L1',  'La primera imagen es la principal; hasta doce más aparecen como miniaturas debajo.');
define('LAN_VSTORE_GUIDE_CATALOG_IMG_L2',  'Al hacer clic en la imagen se abre en un visor a tamaño completo. Los visitantes se mueven con las flechas o el teclado y lo cierran con <code>Esc</code>.');
define('LAN_VSTORE_GUIDE_CATALOG_IMG_L3',  'Sube imágenes de al menos 1200 px de ancho: el visor pide una versión de 1600 px, creada y guardada en caché por e107.');
define('LAN_VSTORE_GUIDE_CATALOG_PRICE_H', 'Campo de precio');
define('LAN_VSTORE_GUIDE_CATALOG_PRICE_T', 'El precio decide cómo se ofrece el producto.');
define('LAN_VSTORE_GUIDE_CATALOG_P1_N',    'Precio mayor que 0');
define('LAN_VSTORE_GUIDE_CATALOG_P1_D',    'Producto normal: botón "Añadir al carrito", pasa por el checkout y el pago.');
define('LAN_VSTORE_GUIDE_CATALOG_P2_N',    'Precio 0');
define('LAN_VSTORE_GUIDE_CATALOG_P2_D',    'Producto gratuito, por ejemplo una descarga. Se sigue comprando a través del carrito.');
define('LAN_VSTORE_GUIDE_CATALOG_P3_N',    'Precio vacío');
define('LAN_VSTORE_GUIDE_CATALOG_P3_D',    'Producto bajo presupuesto: la tienda muestra "Precio a consultar" y un botón "Presupuesto". Consulta la pestaña Presupuestos.');

// PRESUPUESTOS
define('LAN_VSTORE_GUIDE_QUOTES_TAB',      'Presupuestos');
define('LAN_VSTORE_GUIDE_QUOTES_TITLE',    'Solicitudes de presupuesto');
define('LAN_VSTORE_GUIDE_QUOTES_INTRO',    'Vende productos que necesitan un precio a medida: el cliente pregunta y tú respondes con un presupuesto. Las solicitudes se guardan como pedidos con su propio flujo de trabajo.');
define('LAN_VSTORE_GUIDE_QUOTES_HOW_H',    'Cómo funciona');
define('LAN_VSTORE_GUIDE_QUOTES_E1_T',     '1. El cliente solicita');
define('LAN_VSTORE_GUIDE_QUOTES_E1_D',     'Añade productos sin precio al carrito y pulsa <strong>Solicitar presupuesto</strong>. El checkout tiene los mismos pasos que una compra, pero sin precios, impuestos ni método de pago.');
define('LAN_VSTORE_GUIDE_QUOTES_E2_T',     '2. Tú lo revisas');
define('LAN_VSTORE_GUIDE_QUOTES_E2_D',     'La solicitud llega a <strong>Ventas</strong> (filtro <em>Presupuestos</em>) y al email de la tienda. Usa los botones de la página del pedido para avanzarla en el flujo.');
define('LAN_VSTORE_GUIDE_QUOTES_E3_T',     '3. Tú lo facturas');
define('LAN_VSTORE_GUIDE_QUOTES_E3_D',     'El presupuesto pasa a tu aplicación de facturación externa, que emite la factura. Marca la solicitud como <em>Facturado</em> cuando termine.');
define('LAN_VSTORE_GUIDE_QUOTES_MIX_H',    'Comprar y presupuestar en el mismo carrito');
define('LAN_VSTORE_GUIDE_QUOTES_MIX_T',    'El carrito muestra dos bloques: <strong>Productos para comprar</strong> (precios, cupón, totales y botón de Checkout) y <strong>Productos para presupuestar</strong> (sin precios y con un botón Solicitar presupuesto). Cada botón procesa solo sus líneas; las demás permanecen en el carrito para el paso siguiente.');
define('LAN_VSTORE_GUIDE_QUOTES_RULES_H',  'Reglas que conviene recordar');
define('LAN_VSTORE_GUIDE_QUOTES_R1',       'Un presupuesto nunca recibe número de factura, por lo que no consume la secuencia certificada de facturas.');
define('LAN_VSTORE_GUIDE_QUOTES_R2',       'Un presupuesto no modifica el inventario y no cuenta como pedido en las estadísticas.');
define('LAN_VSTORE_GUIDE_QUOTES_R3',       'Se envía un email al crear la solicitud, al cliente y en copia a la tienda. Los cambios de estado posteriores no envían nada.');
define('LAN_VSTORE_GUIDE_QUOTES_R4',       'El cliente puede ver la solicitud y cancelarla desde su área. Una solicitud cancelada puede reabrirse como solicitud de presupuesto desde la página del pedido.');
define('LAN_VSTORE_GUIDE_QUOTES_R5',       'La página de estadísticas muestra un contador rojo con las solicitudes pendientes; enlaza con la lista filtrada.');
define('LAN_VSTORE_GUIDE_QUOTES_NOTE',     'El texto del email del presupuesto se edita en Plantillas de Email, en la pestaña Presupuesto solicitado.');

// CUPONES
define('LAN_VSTORE_GUIDE_COUPONS_TAB',     'Cupones');
define('LAN_VSTORE_GUIDE_COUPONS_TITLE',   'Cupones de descuento');
define('LAN_VSTORE_GUIDE_COUPONS_INTRO',   'Los clientes introducen los cupones en el carrito.');
define('LAN_VSTORE_GUIDE_COUPONS_TYPES_H', 'Tipos de descuento');
define('LAN_VSTORE_GUIDE_COUPONS_TYPES_T', 'Un cupón porcentual descuenta una parte de cada artículo elegible. Un cupón fijo nunca descuenta más que el precio del artículo.');
define('LAN_VSTORE_GUIDE_COUPONS_RULES_H', 'Restricciones');
define('LAN_VSTORE_GUIDE_COUPONS_RULES_T', 'Define las fechas de inicio y fin e incluye o excluye categorías en la pestaña Restricciones. Las categorías excluidas siempre prevalecen sobre las incluidas.');
define('LAN_VSTORE_GUIDE_COUPONS_LIMITS_H','Límites');
define('LAN_VSTORE_GUIDE_COUPONS_LIMITS_T','Limita cuántas veces puede usarse un cupón en total y por usuario en la pestaña Límites. Usa <code>-1</code> para no limitar.');
define('LAN_VSTORE_GUIDE_COUPONS_NOTE',    'Los cupones solo se aplican a los productos para comprar. Los productos bajo presupuesto no tienen precio y nunca se descuentan.');

// IMPUESTOS
define('LAN_VSTORE_GUIDE_TAX_TAB',         'Impuestos e IVA');
define('LAN_VSTORE_GUIDE_TAX_TITLE',       'Cálculo de impuestos');
define('LAN_VSTORE_GUIDE_TAX_INTRO',       'El impuesto se calcula en el checkout a partir del país de facturación que introduce el cliente.');
define('LAN_VSTORE_GUIDE_TAX_SETUP_H',     'Configuración');
define('LAN_VSTORE_GUIDE_TAX_SETUP_T',     'En Preferencias abre la pestaña Impuestos, activa el cálculo de impuestos y selecciona el país de tu empresa. Activa la comprobación online del NIF-IVA si vendes a empresas de la UE.');
define('LAN_VSTORE_GUIDE_TAX_CLASSES_H',   'Clases de impuesto');
define('LAN_VSTORE_GUIDE_TAX_CLASSES_T',   'Introduce los valores como decimales, por ejemplo <code>0.19</code> para el 19 %. Asigna una clase de impuesto a cada producto en la pestaña Avanzado.');
define('LAN_VSTORE_GUIDE_TAX_SCEN_H',      'Qué paga el cliente');
define('LAN_VSTORE_GUIDE_TAX_COL_WHO',     'Cliente');
define('LAN_VSTORE_GUIDE_TAX_COL_RATE',    'Impuesto aplicado');
define('LAN_VSTORE_GUIDE_TAX_SC1_W',       'En tu país');
define('LAN_VSTORE_GUIDE_TAX_SC1_R',       'Tu tasa local.');
define('LAN_VSTORE_GUIDE_TAX_SC2_W',       'Consumidor de otro país de la UE');
define('LAN_VSTORE_GUIDE_TAX_SC2_R',       'La tasa del país de destino.');
define('LAN_VSTORE_GUIDE_TAX_SC3_W',       'Empresa de la UE con NIF-IVA válido');
define('LAN_VSTORE_GUIDE_TAX_SC3_R',       '0 % (inversión del sujeto pasivo).');
define('LAN_VSTORE_GUIDE_TAX_SC4_W',       'Fuera de la UE');
define('LAN_VSTORE_GUIDE_TAX_SC4_R',       '0 % (exportación).');
define('LAN_VSTORE_GUIDE_TAX_NOTE',        'Los precios se introducen sin impuestos. Las solicitudes de presupuesto no muestran impuestos; se calculan después en tu aplicación de facturación.');

// ENVÍO
define('LAN_VSTORE_GUIDE_SHIP_TAB',        'Envío');
define('LAN_VSTORE_GUIDE_SHIP_TITLE',      'Gastos de envío');
define('LAN_VSTORE_GUIDE_SHIP_INTRO',      'Activa el envío en la pestaña Envío de Preferencias y elige un método de cálculo.');
define('LAN_VSTORE_GUIDE_SHIP_M1_T',       'Suma de todos los artículos');
define('LAN_VSTORE_GUIDE_SHIP_M1_D',       'Suma el coste de envío de cada artículo del carrito.');
define('LAN_VSTORE_GUIDE_SHIP_M2_T',       'Suma de artículos únicos');
define('LAN_VSTORE_GUIDE_SHIP_M2_D',       'Suma el coste de envío de cada producto distinto una sola vez.');
define('LAN_VSTORE_GUIDE_SHIP_M3_T',       'Por tramos');
define('LAN_VSTORE_GUIDE_SHIP_M3_D',       'Los costes dependen de umbrales del precio o del peso del pedido. Empieza por el umbral más bajo y haz que el último sea mayor que tu pedido más grande.');
define('LAN_VSTORE_GUIDE_SHIP_NOTE',       'El envío solo se suma a los productos para comprar. Una solicitud de presupuesto no muestra gastos de envío.');

// PASARELAS
define('LAN_VSTORE_GUIDE_GATE_TAB',        'Pagos');
define('LAN_VSTORE_GUIDE_GATE_TITLE',      'Pasarelas de pago');
define('LAN_VSTORE_GUIDE_GATE_INTRO',      'Cada pasarela tiene su propia pestaña con sus credenciales y un interruptor de activación.');
define('LAN_VSTORE_GUIDE_GATE_G1_T',       'PayPal');
define('LAN_VSTORE_GUIDE_GATE_G1_D',       'Estándar y REST. Requiere credenciales de API de tu cuenta de PayPal.');
define('LAN_VSTORE_GUIDE_GATE_G2_T',       'Mollie');
define('LAN_VSTORE_GUIDE_GATE_G2_D',       'Requiere una clave de API y permite elegir los métodos de pago que se ofrecen en el checkout.');
define('LAN_VSTORE_GUIDE_GATE_G3_T',       'Transferencia bancaria');
define('LAN_VSTORE_GUIDE_GATE_G3_D',       'El pedido queda pendiente hasta que lo marques como pagado.');
define('LAN_VSTORE_GUIDE_GATE_NOTE',       'Prueba siempre una pasarela en modo sandbox antes de pasar a producción.');

// PEDIDOS
define('LAN_VSTORE_GUIDE_ORDERS_TAB',      'Pedidos');
define('LAN_VSTORE_GUIDE_ORDERS_TITLE',    'Pedidos, emails y facturas');
define('LAN_VSTORE_GUIDE_ORDERS_INTRO',    'Todo lo relacionado con una venta se gestiona desde la entrada de menú Ventas.');
define('LAN_VSTORE_GUIDE_ORDERS_ST_H',     'Estado del pedido');
define('LAN_VSTORE_GUIDE_ORDERS_ST_T',     'Al cambiar el estado se actualiza el inventario y se envía el email correspondiente. Las solicitudes de presupuesto siguen su propio flujo.');
define('LAN_VSTORE_GUIDE_ORDERS_SN',       'El pedido se ha realizado y espera el pago.');
define('LAN_VSTORE_GUIDE_ORDERS_SP',       'El pago ha llegado y el pedido se está preparando.');
define('LAN_VSTORE_GUIDE_ORDERS_SH',       'El pedido está en pausa, por ejemplo a la espera de stock.');
define('LAN_VSTORE_GUIDE_ORDERS_SC',       'El pedido se ha cumplido y se envía al cliente el email de pedido completado.');
define('LAN_VSTORE_GUIDE_ORDERS_SX',       'El pedido se ha cancelado y se envía el email de cancelación.');
define('LAN_VSTORE_GUIDE_ORDERS_SR',       'El pago se ha devuelto al cliente.');
define('LAN_VSTORE_GUIDE_ORDERS_SQ',       'Se recibió una solicitud de presupuesto y espera respuesta.');
define('LAN_VSTORE_GUIDE_ORDERS_SM',       'La solicitud pasó a la aplicación de facturación externa.');
define('LAN_VSTORE_GUIDE_ORDERS_SA',       'El cliente aceptó el presupuesto.');
define('LAN_VSTORE_GUIDE_ORDERS_SF',       'El presupuesto se facturó en la aplicación externa.');
define('LAN_VSTORE_GUIDE_ORDERS_EM_H',     'Plantillas de email');
define('LAN_VSTORE_GUIDE_ORDERS_EM_T',     'Hay una plantilla para cada cambio de estado y otra para las solicitudes de presupuesto. Usa el botón de restablecer para recuperar el texto original.');
define('LAN_VSTORE_GUIDE_ORDERS_INV_H',    'Facturas');
define('LAN_VSTORE_GUIDE_ORDERS_INV_T',    'Las facturas en PDF las crea el propio Vstore (TCPDF). Define el prefijo, el siguiente número y el pie en Configuración de Facturas y comprueba el resultado en la pestaña de vista previa.');
define('LAN_VSTORE_GUIDE_ORDERS_STAT_H',   'Estadísticas');
define('LAN_VSTORE_GUIDE_ORDERS_STAT_T',   'La primera página del área de administración muestra las cajas de ventas y el número de solicitudes de presupuesto nuevas.');
define('LAN_VSTORE_GUIDE_ORDERS_DASH_H',   'Lo que ve el cliente');
define('LAN_VSTORE_GUIDE_ORDERS_DASH_T',   'En el área de cliente el comprador encuentra un resumen, un gráfico de los últimos seis meses, la lista de pedidos con insignias de estado, el detalle de cada pedido, las descargas y los presupuestos abiertos.');

// APARIENCIA
define('LAN_VSTORE_GUIDE_LOOK_TAB',        'Apariencia');
define('LAN_VSTORE_GUIDE_LOOK_TITLE',      'Adaptar la tienda a tu tema');
define('LAN_VSTORE_GUIDE_LOOK_INTRO',      'La tienda está diseñada para temas Bootstrap 5 y toma sus colores del tema activo. Puedes ajustarla sin tocar el plugin.');
define('LAN_VSTORE_GUIDE_LOOK_CSS_H',      'Colores y formas');
define('LAN_VSTORE_GUIDE_LOOK_CSS_T',      'La hoja de estilos <code>vstore.css</code> lee los colores de Bootstrap y expone algunas variables. Sobrescríbelas en el CSS de tu tema:');
define('LAN_VSTORE_GUIDE_LOOK_TPL_H',      'Plantillas');
define('LAN_VSTORE_GUIDE_LOOK_TPL_T',      'Cada parte de la tienda puede sustituirse desde tu tema. Copia solo la parte que quieras cambiar en esta ubicación:');
define('LAN_VSTORE_GUIDE_LOOK_MENU_H',     'Menús');
define('LAN_VSTORE_GUIDE_LOOK_MENU1',      '<strong>Categorías de Vstore</strong>: las categorías raíz como mosaicos con imagen y número de productos.');
define('LAN_VSTORE_GUIDE_LOOK_MENU2',      '<strong>Productos de Vstore</strong>: los últimos productos de una categoría o de todas.');
define('LAN_VSTORE_GUIDE_LOOK_MENU3',      '<strong>Desplegable del carrito</strong>: se muestra en la navegación, con una variante para solicitudes de presupuesto.');
define('LAN_VSTORE_GUIDE_LOOK_NOTE',       'Activa los menús en el gestor de menús y asígnalos a las áreas de tu diseño.');

// SOLUCIÓN DE PROBLEMAS
define('LAN_VSTORE_GUIDE_TROUBLE_TAB',     'Solución de problemas');
define('LAN_VSTORE_GUIDE_TROUBLE_TITLE',   'Problemas frecuentes');
define('LAN_VSTORE_GUIDE_TROUBLE_INTRO',   'Revisa estos puntos antes de pedir soporte.');
define('LAN_VSTORE_GUIDE_TROUBLE_Q1',      'Un error fatal menciona una clase o un archivo de autoload que falta');
define('LAN_VSTORE_GUIDE_TROUBLE_A1',      'Ejecuta <code>composer install --no-dev --optimize-autoloader</code> dentro de la carpeta del plugin. El directorio <code>vendor</code> no está incluido en el repositorio.');
define('LAN_VSTORE_GUIDE_TROUBLE_Q2',      'Las facturas no se crean en PDF');
define('LAN_VSTORE_GUIDE_TROUBLE_A2',      'Ejecuta <code>composer install</code> en <code>e107_plugins/vstore</code> y activa las facturas PDF en Configuración de Facturas.');
define('LAN_VSTORE_GUIDE_TROUBLE_Q3',      'Se aplica un tipo de impuesto incorrecto');
define('LAN_VSTORE_GUIDE_TROUBLE_A3',      'Revisa el país de tu empresa, la clase de impuesto del producto y el país indicado en el formulario de facturación.');
define('LAN_VSTORE_GUIDE_TROUBLE_Q4',      'Necesito más información sobre lo que ocurre');
define('LAN_VSTORE_GUIDE_TROUBLE_A4',      'Descomenta la línea <code>e_DEBUG_VSTORE</code> de <code>e_header.php</code> para activar el registro de depuración y quítala de nuevo en producción.');
define('LAN_VSTORE_GUIDE_TROUBLE_Q5',      'Los visitantes no ven los enlaces de tienda o carrito');
define('LAN_VSTORE_GUIDE_TROUBLE_A5',      'Por defecto los enlaces se crean para miembros. Cambia la visibilidad de los enlaces de Vstore en los enlaces del sitio del área de administración.');
define('LAN_VSTORE_GUIDE_TROUBLE_Q6',      'La tienda se ve distinta de mi tema o no se aplican los estilos');
define('LAN_VSTORE_GUIDE_TROUBLE_A6',      'El diseño necesita un tema Bootstrap 5. Tras una actualización vacía la caché del navegador y la de e107 para que se carguen los nuevos <code>vstore.css</code> y <code>vstore.js</code>.');

// CREDITS
define('LAN_VSTORE_GUIDE_CREDITS_TAB',              'Créditos');
define('LAN_VSTORE_GUIDE_CREDITS_TITLE',            'Créditos y agradecimientos');
define('LAN_VSTORE_GUIDE_CREDITS_INTRO',            'Vstore no existiría sin el trabajo de sus autores originales, de la comunidad e107 y de las bibliotecas de código abierto que se listan a continuación.');
define('LAN_VSTORE_GUIDE_CREDITS_AUTHOR_H',         'Autores');
define('LAN_VSTORE_GUIDE_CREDITS_AUTHOR_T',         'Vstore fue creado por CaMerOn y Achim Ennenbach (e107 Inc). Esta versión la mantiene y amplía Martin Costa (Kanonimpresor). Consulta la pestaña <strong>Acerca de</strong> para los datos de contacto y los enlaces de soporte.');
define('LAN_VSTORE_GUIDE_CREDITS_TECH_H',           'Tecnologías utilizadas');
define('LAN_VSTORE_GUIDE_CREDITS_TECH_E107',        'Framework del área de administración, sistema de plugins, parser, shortcodes y plantillas.');
define('LAN_VSTORE_GUIDE_CREDITS_TECH_OMNIPAY',     'Biblioteca de abstracción de pagos que hay detrás de las pasarelas PayPal y Mollie.');
define('LAN_VSTORE_GUIDE_CREDITS_TECH_TCPDF',       'Crea las facturas en PDF sin servicios externos.');
define('LAN_VSTORE_GUIDE_CREDITS_TECH_VAT',         'Comprueba los NIF-IVA de la UE y aporta las tasas de IVA por país.');
define('LAN_VSTORE_GUIDE_CREDITS_TECH_BS',          'La tienda usa Bootstrap 5; esta área de administración usa el Bootstrap 3 del tema de administración de e107.');
define('LAN_VSTORE_GUIDE_CREDITS_TECH_FA',          'Conjunto de iconos usado en el área de administración y en la tienda.');
define('LAN_VSTORE_GUIDE_CREDITS_THANKS_H',         'Agradecimientos especiales');
define('LAN_VSTORE_GUIDE_CREDITS_THANKS_ORIG',      'CaMerOn, Achim Ennenbach y el equipo de e107 Inc, por el plugin Vstore original en el que se basa esta versión.');
define('LAN_VSTORE_GUIDE_CREDITS_THANKS_E107',      'La comunidad e107, por las pruebas, las ideas y los comentarios.');
define('LAN_VSTORE_GUIDE_CREDITS_THANKS_TRANS',     'Todas las personas que ayudan a mantener al día las traducciones al inglés, español y portugués.');
define('LAN_VSTORE_GUIDE_CREDITS_LICENSE',          'Vstore es software libre publicado bajo la Licencia Pública General de GNU v3.0. Puedes usarlo, estudiarlo, compartirlo y mejorarlo según los términos de esa licencia.');
define('LAN_VSTORE_GUIDE_CREDITS_INVOLVED',         '¿Has encontrado un error o tienes una idea? Abre una incidencia en el repositorio del proyecto desde el botón Soporte de la pestaña Acerca de.');

// APPEARANCE: showcase menu
define('LAN_VSTORE_GUIDE_LOOK_MENU4',               '<strong>Escaparate de Vstore</strong>: tarjetas de producto como las de las páginas de productos, para cualquier área de menú, con píldoras de categorías opcionales.');
define('LAN_VSTORE_GUIDE_LOOK_SHOW_H',              'Opciones del menú escaparate');
define('LAN_VSTORE_GUIDE_LOOK_SHOW_T',              'Añade <em>vstore_showcase_menu</em> a un área de menú en el Gestor de menús y pulsa su botón de configuración.');
define('LAN_VSTORE_GUIDE_LOOK_SHOW_O1_N',           'Categoría');
define('LAN_VSTORE_GUIDE_LOOK_SHOW_O1_D',           '<em>Todas las categorías</em> muestra productos de cualquier categoría (aleatorios por defecto); elige una categoría para mostrar solo sus productos.');
define('LAN_VSTORE_GUIDE_LOOK_SHOW_O2_N',           'Cantidad de productos');
define('LAN_VSTORE_GUIDE_LOOK_SHOW_O2_D',           'De 1 a 24. Con píldoras, cada píldora muestra esta cantidad de productos.');
define('LAN_VSTORE_GUIDE_LOOK_SHOW_O3_N',           'Productos por fila');
define('LAN_VSTORE_GUIDE_LOOK_SHOW_O3_D',           'De una a cuatro columnas, o automático. Elige lo que encaje con el área de menú de tu tema: la rejilla usa menos columnas por sí sola si el área es estrecha.');
define('LAN_VSTORE_GUIDE_LOOK_SHOW_O4_N',           'Orden');
define('LAN_VSTORE_GUIDE_LOOK_SHOW_O4_D',           'Aleatorio, más recientes, nombre, precio o el orden manual del catálogo.');
define('LAN_VSTORE_GUIDE_LOOK_SHOW_O5_N',           'Píldoras de categorías');
define('LAN_VSTORE_GUIDE_LOOK_SHOW_O5_D',           'Píldoras sobre los productos que los filtran en el mismo menú, píldoras que abren la página de la categoría, o ninguna.');
