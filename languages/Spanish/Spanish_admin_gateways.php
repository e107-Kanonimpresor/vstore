<?php
if (!defined('e107_INIT')) { exit; }

return array(
	'LAN_VSTORE_ADMIN_GATE_TAB_BANK'          => 'Transferencia bancaria',
	'LAN_VSTORE_ADMIN_GATE_MOLLIE_TEST'       => 'Modo de prueba de Mollie',
	'LAN_VSTORE_ADMIN_GATE_MOLLIE_TEST_H'     => 'Usar el modo de prueba de Mollie',
	'LAN_VSTORE_ADMIN_GATE_MOLLIE_KEY_LIVE'   => 'Clave API de Mollie (producción)',
	'LAN_VSTORE_ADMIN_GATE_MOLLIE_KEY_TEST'   => 'Clave API de Mollie (pruebas)',
	'LAN_VSTORE_ADMIN_GATE_MOLLIE_KEYS_NOTE'  => 'Obtén tus claves API <a href="https://www.mollie.com/dashboard/developers/api-keys">aquí</a>',
	'LAN_VSTORE_ADMIN_GATE_MOLLIE_METHODS'    => 'Métodos de pago de Mollie',
	'LAN_VSTORE_ADMIN_GATE_MOLLIE_METHODS_NOTE' => 'Selecciona al menos 1 método de pago.<br/>¡El método de pago DEBE estar activado en tu panel de Mollie ANTES de poder usarlo con Vstore!<br/>Ten en cuenta que no todos los métodos admiten todas las monedas.',
	'LAN_VSTORE_ADMIN_GATE_BANK_TITLE_DEF'    => 'Transferencia bancaria',
	'LAN_VSTORE_ADMIN_GATE_BANK_DETAILS'      => 'Transferencia bancaria',
	'LAN_VSTORE_ADMIN_GATE_BANK_DETAILS_PH'   => 'Datos de la cuenta bancaria',
	'LAN_VSTORE_ADMIN_GATE_CLASSNAME'         => 'Nombre de la clase',
	'LAN_VSTORE_ADMIN_GATE_DEPRECATED_TITLE'  => 'Importante',
	'LAN_VSTORE_ADMIN_GATE_RETIRED'           => 'Estas pasarelas ya no están soportadas y nunca se ofrecen en el checkout: [x]. Sus ajustes guardados se ignoran. Usa Mollie (tarjetas y métodos locales), PayPal o transferencia bancaria.',
	'LAN_VSTORE_ADMIN_GATE_MOLLIE_CURRENCY'   => 'Los métodos de Mollie [x] solo aceptan euros, por lo que no se ofrecen en el checkout mientras la moneda de la tienda sea [y]. Cambia la moneda a EUR en Preferencias para ofrecerlos.',
	'LAN_VSTORE_ADMIN_GATE_DEPRECATED_TEXT'   => '<p>Ha cambiado la forma de almacenar las preferencias de las pasarelas.</p><p><b>Estas preferencias antiguas se han eliminado.</b></p><p>Si lo deseas, puedes volver a introducir estos valores en el formulario siguiente.</p>',
);
