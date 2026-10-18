<?php
if (!defined('e107_INIT')) { exit; }

return array(
	'LAN_VSTORE_ADMIN_ORD_TAB_LOG'         => 'Registro',

	'LAN_VSTORE_ADMIN_ORD_PAY_STATUS'      => 'Estado del pago',
	'LAN_VSTORE_ADMIN_ORD_REFUND_DATE'     => 'Fecha de reembolso',
	'LAN_VSTORE_ADMIN_ORD_INVOICE_NR'      => 'Factura n.º',
	'LAN_VSTORE_ADMIN_ORD_BILLING_TO'      => 'Facturar a',
	'LAN_VSTORE_ADMIN_ORD_SHIP_TO'         => 'Enviar a',
	'LAN_VSTORE_ADMIN_ORD_TRANSID'         => 'ID de transacción',
	'LAN_VSTORE_ADMIN_ORD_CURRENCY'        => 'Moneda',
	'LAN_VSTORE_ADMIN_ORD_SESSION'         => 'Sesión',
	'LAN_VSTORE_ADMIN_ORD_RAWDATA'         => 'Datos en bruto',

	'LAN_VSTORE_ADMIN_ORD_PAYSTATUS_INCOMPLETE' => 'Incompleto',
	'LAN_VSTORE_ADMIN_ORD_PAYSTATUS_COMPLETE'   => 'Completo',
	'LAN_VSTORE_ADMIN_ORD_PAYSTATUS_REFUNDED'   => 'Reembolsado',

	'LAN_VSTORE_ADMIN_ORD_FILTER_OPEN'     => 'Abiertos',
	'LAN_VSTORE_ADMIN_ORD_PAYSTATUS_NONE'       => 'Ninguno',
	'LAN_VSTORE_ADMIN_ORD_FILTER_QUOTES'   => 'Presupuestos',
	'LAN_VSTORE_ADMIN_ORD_BTN_MIGRATED'    => 'Marcar como migrado a la app',
	'LAN_VSTORE_ADMIN_ORD_BTN_APPROVED'    => 'Marcar como aprobado',
	'LAN_VSTORE_ADMIN_ORD_BTN_INVOICED'    => 'Marcar como facturado',
	'LAN_VSTORE_ADMIN_ORD_BTN_REOPEN'      => 'Reabrir como solicitud de presupuesto',
	'LAN_VSTORE_ADMIN_ORD_INVOICE_TIP'     => 'Haz clic para abrir/generar el PDF de la factura',
	'LAN_VSTORE_ADMIN_ORD_INVOICE_FORCE'   => 'Marca para forzar la creación de un nuevo PDF de factura al guardar',

	'LAN_VSTORE_ADMIN_ORD_BTN_CANCEL'      => 'Cancelar pedido',
	'LAN_VSTORE_ADMIN_ORD_BTN_HOLD'        => 'Poner en espera',
	'LAN_VSTORE_ADMIN_ORD_BTN_PROCESS'     => 'Procesar pedido',
	'LAN_VSTORE_ADMIN_ORD_BTN_CHECK_PAYMENT' => 'Comprobar pago en Mollie',
	'LAN_VSTORE_ADMIN_ORD_BTN_REFUND'      => 'Reembolsar pedido',
	'LAN_VSTORE_ADMIN_ORD_BTN_COMPLETE'    => 'Completar pedido',
	'LAN_VSTORE_ADMIN_ORD_CONFIRM_CANCEL'  => '¿Realmente quieres cancelar este pedido?',
	'LAN_VSTORE_ADMIN_ORD_CONFIRM_HOLD'    => '¿Realmente quieres poner el pedido en espera?',
	'LAN_VSTORE_ADMIN_ORD_CONFIRM_REFUND'  => '¿Realmente quieres reembolsar este pedido?',

	'LAN_VSTORE_ADMIN_ORD_COL_NAME'        => 'Nombre',
	'LAN_VSTORE_ADMIN_ORD_COL_DESCRIPTION' => 'Descripción',
	'LAN_VSTORE_ADMIN_ORD_COL_QTY'         => 'Cant.',
	'LAN_VSTORE_ADMIN_ORD_COL_PRICE'       => 'Precio',
	'LAN_VSTORE_ADMIN_ORD_COL_DATETIME'    => 'Fecha/Hora',
	'LAN_VSTORE_ADMIN_ORD_COL_USER'        => 'Usuario',
	'LAN_VSTORE_ADMIN_ORD_NA'              => 'n/d',
	'LAN_VSTORE_ADMIN_ORD_NO_BILLING'      => 'No hay dirección de facturación',
	'LAN_VSTORE_ADMIN_ORD_NO_SHIPPING'     => 'No hay dirección de envío',

	'LAN_VSTORE_ADMIN_ORD_MSG_USERCLASS'   => 'Las clases de usuario asignadas al cliente durante la compra no pueden eliminarse automáticamente.<br/>Haz clic <a href="[x]">aquí</a> para eliminar manualmente las siguientes clases de usuario.<br/>[y]',
	'LAN_VSTORE_ADMIN_ORD_MSG_REFUND_FAIL' => 'No se pudo reembolsar el pedido.',
	'LAN_VSTORE_ADMIN_ORD_MSG_INV_FAIL'    => 'No se pudo crear la factura.',
	'LAN_VSTORE_ADMIN_ORD_MSG_INV_OK'      => '¡Factura creada correctamente!',
);
