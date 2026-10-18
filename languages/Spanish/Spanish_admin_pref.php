<?php
if (!defined('e107_INIT')) { exit; }

return array(
	// Pestañas
	'LAN_VSTORE_ADMIN_PREF_TAB_SHIP'      => 'Envíos',
	'LAN_VSTORE_ADMIN_PREF_TAB_TAX'       => 'Impuestos',
	'LAN_VSTORE_ADMIN_PREF_TAB_EMAIL'     => 'Emails',
	'LAN_VSTORE_ADMIN_PREF_TAB_HOWTO'     => 'Cómo pedir',
	'LAN_VSTORE_ADMIN_PREF_TAB_ADMIN'     => 'Área de administración',
	'LAN_VSTORE_ADMIN_PREF_TAB_CHECKOUT'  => 'Checkout',
	'LAN_VSTORE_ADMIN_PREF_TAB_MENU'      => 'Menú',
	'LAN_VSTORE_ADMIN_PREF_TAB_CSS'       => 'CSS personalizado',

	// General
	'LAN_VSTORE_ADMIN_PREF_CAPTION'            => 'Título de la tienda',
	'LAN_VSTORE_ADMIN_PREF_CAPTION_H'          => 'El título que se muestra en la parte superior de las páginas de la tienda.',
	'LAN_VSTORE_ADMIN_PREF_CAPTION_CAT'        => 'Título de las categorías',
	'LAN_VSTORE_ADMIN_PREF_CAPTION_CAT_PH'     => 'Marcas de productos',
	'LAN_VSTORE_ADMIN_PREF_CAPTION_STOCK'      => 'Texto de producto agotado',
	'LAN_VSTORE_ADMIN_PREF_CAPTION_STOCK_PH'   => 'Agotado',
	'LAN_VSTORE_ADMIN_PREF_CURRENCY'           => 'Moneda',
	'LAN_VSTORE_ADMIN_PREF_CURRENCY_H'         => 'Selecciona una moneda.',
	'LAN_VSTORE_ADMIN_PREF_AMOUNT_FORMAT'      => 'Formato de precio',
	'LAN_VSTORE_ADMIN_PREF_AMOUNT_FORMAT_H'    => 'Selecciona el formato con el que se mostrarán los precios de los productos.',
	'LAN_VSTORE_ADMIN_PREF_AMOUNT_BEFORE'      => 'Moneda antes del número',
	'LAN_VSTORE_ADMIN_PREF_AMOUNT_BEHIND'      => 'Moneda después del número',
	'LAN_VSTORE_ADMIN_PREF_WEIGHT_UNIT'        => 'Unidad de peso',
	'LAN_VSTORE_ADMIN_PREF_WEIGHT_UNIT_H'      => 'Selecciona una unidad de peso.',
	'LAN_VSTORE_ADMIN_PREF_USERCLASS'          => 'Asignar clase de usuario',
	'LAN_VSTORE_ADMIN_PREF_USERCLASS_H'        => 'Asigna una clase de usuario al cliente al completar su compra.',
	'LAN_VSTORE_ADMIN_PREF_USERCLASS_PRODUCT'  => 'Según lo definido en el producto',
	'LAN_VSTORE_ADMIN_PREF_SHOW_STOCK'         => 'Mostrar/ocultar productos agotados',
	'LAN_VSTORE_ADMIN_PREF_SHOW_STOCK_H'       => 'Muestra u oculta los productos agotados en los listados de productos',

	// Envíos
	'LAN_VSTORE_ADMIN_PREF_SHIPPING'           => 'Calcular envío',
	'LAN_VSTORE_ADMIN_PREF_SHIPPING_H'         => 'Incluir el cálculo del envío en el checkout.',
	'LAN_VSTORE_ADMIN_PREF_SHIP_METHOD'        => 'Método de cálculo',
	'LAN_VSTORE_ADMIN_PREF_SHIP_METHOD_H'      => 'Define un método para calcular el coste de envío.',
	'LAN_VSTORE_ADMIN_PREF_SHIP_SUM_SIMPLE'    => 'Suma del coste de envío de todos los artículos',
	'LAN_VSTORE_ADMIN_PREF_SHIP_SUM_UNIQUE'    => 'Suma del coste de envío solo de los artículos únicos',
	'LAN_VSTORE_ADMIN_PREF_SHIP_TIERED'        => 'Un sistema escalonado según precio o peso',
	'LAN_VSTORE_ADMIN_PREF_SHIP_UNIT'          => 'Valor basado en',
	'LAN_VSTORE_ADMIN_PREF_SHIP_LIMIT'         => 'Los costes son',
	'LAN_VSTORE_ADMIN_PREF_SHIP_DATA'          => 'Costes de envío escalonados',
	'LAN_VSTORE_ADMIN_PREF_SHIP_DATA_H'        => 'Introduce umbrales en la primera columna para fijar o limitar el coste de envío según el precio o el peso total del pedido. Empieza por el umbral más bajo y añade más hasta que el último sea superior al precio/peso máximo de un pedido habitual. Si el último umbral es demasiado bajo, el envío podría quedar sin coste.',
	'LAN_VSTORE_ADMIN_PREF_SHIP_UNIT_PRICE'    => 'Cuando el precio total del pedido sea inferior a &mdash;',
	'LAN_VSTORE_ADMIN_PREF_SHIP_UNIT_WEIGHT'   => 'Cuando el peso total del pedido sea inferior a &mdash;',
	'LAN_VSTORE_ADMIN_PREF_SHIP_LIMIT_FIXED'   => 'Fijar el coste de envío en &mdash;',
	'LAN_VSTORE_ADMIN_PREF_SHIP_LIMIT_MAX'     => 'Limitar el coste de envío a no más de &mdash;',

	// Emails
	'LAN_VSTORE_ADMIN_PREF_SENDER_NAME'        => 'Nombre del remitente',
	'LAN_VSTORE_ADMIN_PREF_SENDER_NAME_PH'     => 'Departamento de ventas',
	'LAN_VSTORE_ADMIN_PREF_SENDER_H'           => 'Déjalo vacío para usar el valor del sistema',
	'LAN_VSTORE_ADMIN_PREF_SENDER_EMAIL_PH'    => 'pedidos@mitienda.com',
	'LAN_VSTORE_ADMIN_PREF_MERCHANT'           => 'Nombre/dirección del comercio',
	'LAN_VSTORE_ADMIN_PREF_MERCHANT_PH'        => 'Mi Tienda S.L., etc.',
	'LAN_VSTORE_ADMIN_PREF_MERCHANT_H'         => 'Se mostrará en el email al cliente.',

	// Cómo pedir
	'LAN_VSTORE_ADMIN_PREF_HOWTO'              => 'Cómo pedir',
	'LAN_VSTORE_ADMIN_PREF_HOWTO_H'            => 'Introduce la información sobre cómo realizar un pedido.',

	// Administración
	'LAN_VSTORE_ADMIN_PREF_ITEMS_PERPAGE'      => 'Productos por página',
	'LAN_VSTORE_ADMIN_PREF_CATS_PERPAGE'       => 'Categorías por página',

	// Checkout
	'LAN_VSTORE_ADMIN_PREF_ADDITIONAL'         => 'Campos adicionales',
	'LAN_VSTORE_ADMIN_PREF_ADD_PLACEHOLDER'    => 'Texto de ejemplo',
	'LAN_VSTORE_ADMIN_PREF_ADD_FIELDTYPE'      => 'Tipo de campo',
	'LAN_VSTORE_ADMIN_PREF_ADD_REQUIRED'       => 'Obligatorio',
	'LAN_VSTORE_ADMIN_PREF_ADD_TEXTBOX'        => 'Cuadro de texto',
	'LAN_VSTORE_ADMIN_PREF_ADD_CHECKBOX'       => 'Casilla de verificación',

	// CSS personalizado
	'LAN_VSTORE_ADMIN_PREF_CSS'                => 'CSS personalizado',
	'LAN_VSTORE_ADMIN_PREF_CSS_H'              => 'Usa este campo para añadir CSS propio de Vstore sin necesidad de editar archivos.',

	// Impuestos
	'LAN_VSTORE_ADMIN_PREF_TAX_CALC'           => 'Calcular impuestos',
	'LAN_VSTORE_ADMIN_PREF_TAX_CALC_H'         => 'Actívalo para activar el cálculo de impuestos.',
	'LAN_VSTORE_ADMIN_PREF_TAX_COUNTRY'        => 'País del negocio',
	'LAN_VSTORE_ADMIN_PREF_TAX_COUNTRY_H'      => 'El país donde está ubicado el negocio.',
	'LAN_VSTORE_ADMIN_PREF_TAX_VAT'            => 'Comprobar NIF/IVA online (¡solo UE!)',
	'LAN_VSTORE_ADMIN_PREF_TAX_VAT_H'          => 'Actívalo para comprobar online el NIF/IVA (¡solo UE!).',
	'LAN_VSTORE_ADMIN_PREF_TAX_ORIGIN'         => 'Cobrar a consumidores de la UE el IVA del país de la tienda',
	'LAN_VSTORE_ADMIN_PREF_TAX_ORIGIN_H'       => 'Desde julio de 2021 se aplica el IVA del país del cliente en las ventas a consumidores de otros países de la UE (OSS). Quien no supere los 10.000 EUR anuales de ventas a distancia en toda la UE puede aplicar el IVA de su propio país. Actívalo solo si es tu caso; consúltalo con tu asesor.',
	'LAN_VSTORE_ADMIN_PREF_TAX_CLASSES'        => 'Clases de impuesto',
	'LAN_VSTORE_ADMIN_PREF_TAX_CLASSES_H'      => 'Las clases de impuesto y el valor predeterminado que se usarán con los productos.<br />Introduce el valor como número decimal, por ejemplo 0.19 para 19%',
	'LAN_VSTORE_ADMIN_PREF_TAX_NAME'           => 'Nombre',
	'LAN_VSTORE_ADMIN_PREF_TAX_DESCRIPTION'    => 'Descripción',
	'LAN_VSTORE_ADMIN_PREF_TAX_VALUE'          => 'Impuesto',
	'LAN_VSTORE_ADMIN_PREF_TAX_DEF_NONE'       => 'Sin impuesto',
	'LAN_VSTORE_ADMIN_PREF_TAX_DEF_REDUCED'    => 'Impuesto reducido',
	'LAN_VSTORE_ADMIN_PREF_TAX_DEF_STANDARD'   => 'Impuesto estándar',
	'LAN_VSTORE_ADMIN_PREF_TAX_ORDER_WARNING'  => '¡Las clases de impuesto no parecen estar en orden!<br>¡Las 3 primeras deben ser "none", "reduced", "standard"!<br/>Añade tus clases específicas del país a continuación.',

	// Menú
	'LAN_VSTORE_ADMIN_PREF_MENU_CAT'           => 'Categoría de productos',
	'LAN_VSTORE_ADMIN_PREF_MENU_ALL'           => 'Todas las categorías (últimos productos)',
	'LAN_VSTORE_ADMIN_PREF_MENU_COUNT'         => 'Límite de productos',
	'LAN_VSTORE_ADMIN_PREF_MENU_COUNT_H'       => 'Introduce el número de productos que se mostrarán en el menú.',
);
