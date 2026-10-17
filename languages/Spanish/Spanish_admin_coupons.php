<?php
if (!defined('e107_INIT')) { exit; }

return array(
	'LAN_VSTORE_ADMIN_COUP_TAB_RESTRICTIONS' => 'Restricciones',
	'LAN_VSTORE_ADMIN_COUP_TAB_LIMITS'       => 'Límites',

	'LAN_VSTORE_ADMIN_COUP_CODE'             => 'Código del cupón',
	'LAN_VSTORE_ADMIN_COUP_CODE_H'           => 'Introduce un código único para este cupón. Usa solo letras mayúsculas y números (sin espacios).',
	'LAN_VSTORE_ADMIN_COUP_CODE_PH'          => 'Ej.: ESPECIAL2021',
	'LAN_VSTORE_ADMIN_COUP_TYPE'             => 'Tipo de descuento',
	'LAN_VSTORE_ADMIN_COUP_TYPE_H'           => 'Qué tipo de descuento se aplicará con este cupón',
	'LAN_VSTORE_ADMIN_COUP_TYPE_PERCENT'     => 'Porcentaje',
	'LAN_VSTORE_ADMIN_COUP_TYPE_FIXED'       => 'Fijo',
	'LAN_VSTORE_ADMIN_COUP_AMOUNT'           => 'Importe del descuento',
	'LAN_VSTORE_ADMIN_COUP_AMOUNT_H'         => 'Define el importe del descuento',
	'LAN_VSTORE_ADMIN_COUP_START'            => 'Fecha de inicio',
	'LAN_VSTORE_ADMIN_COUP_START_H'          => '¿Cuándo debe estar disponible el cupón?',
	'LAN_VSTORE_ADMIN_COUP_END'              => 'Fecha de fin',
	'LAN_VSTORE_ADMIN_COUP_END_H'            => '¿Cuándo dejará de estar disponible el cupón?',
	'LAN_VSTORE_ADMIN_COUP_CATS'             => 'Categorías',
	'LAN_VSTORE_ADMIN_COUP_CATS_H'           => 'Categorías a las que se aplicará este cupón',
	'LAN_VSTORE_ADMIN_COUP_CATS_EX'          => 'Excluir categorías',
	'LAN_VSTORE_ADMIN_COUP_CATS_EX_H'        => 'Categorías a las que este cupón no se aplicará nunca',
	'LAN_VSTORE_ADMIN_COUP_LIMIT_COUPON'     => 'Límite de uso por cupón',
	'LAN_VSTORE_ADMIN_COUP_LIMIT_COUPON_H'   => 'Cuántas veces puede usarse este cupón antes de quedar anulado. Introduce -1 para uso ilimitado.',
	'LAN_VSTORE_ADMIN_COUP_LIMIT_USER'       => 'Límite de uso por usuario',
	'LAN_VSTORE_ADMIN_COUP_LIMIT_USER_H'     => 'Cuántas veces puede usar este cupón un mismo usuario. Introduce -1 para uso ilimitado.',
	'LAN_VSTORE_ADMIN_COUP_LIMIT_ITEM'       => 'Limitar el uso a X artículos',
	'LAN_VSTORE_ADMIN_COUP_LIMIT_ITEM_H'     => 'Número máximo de artículos individuales a los que puede aplicarse este cupón en descuentos de producto. Introduce -1 para aplicarlo a todos los artículos válidos del carrito.',

	'LAN_VSTORE_ADMIN_COUP_ERR_INVALID'      => '¡Código de cupón no válido!',
	'LAN_VSTORE_ADMIN_COUP_ERR_EXISTS'       => '¡El código de cupón ya existe!',
);
