<?php
if (!defined('e107_INIT')) { exit; }

return array(
	'LAN_VSTORE_ADMIN_COUP_TAB_RESTRICTIONS' => 'Restrições',
	'LAN_VSTORE_ADMIN_COUP_TAB_LIMITS'       => 'Limites',

	'LAN_VSTORE_ADMIN_COUP_CODE'             => 'Código do cupão',
	'LAN_VSTORE_ADMIN_COUP_CODE_H'           => 'Introduza um código único para este cupão. Use apenas letras maiúsculas e números (sem espaços).',
	'LAN_VSTORE_ADMIN_COUP_CODE_PH'          => 'Ex.: ESPECIAL2021',
	'LAN_VSTORE_ADMIN_COUP_TYPE'             => 'Tipo de desconto',
	'LAN_VSTORE_ADMIN_COUP_TYPE_H'           => 'Que tipo de desconto será aplicado com este cupão',
	'LAN_VSTORE_ADMIN_COUP_TYPE_PERCENT'     => 'Percentagem',
	'LAN_VSTORE_ADMIN_COUP_TYPE_FIXED'       => 'Fixo',
	'LAN_VSTORE_ADMIN_COUP_AMOUNT'           => 'Valor do desconto',
	'LAN_VSTORE_ADMIN_COUP_AMOUNT_H'         => 'Defina o valor do desconto',
	'LAN_VSTORE_ADMIN_COUP_START'            => 'Data de início',
	'LAN_VSTORE_ADMIN_COUP_START_H'          => 'Quando deve o cupão ficar disponível?',
	'LAN_VSTORE_ADMIN_COUP_END'              => 'Data de fim',
	'LAN_VSTORE_ADMIN_COUP_END_H'            => 'Quando deixará o cupão de estar disponível?',
	'LAN_VSTORE_ADMIN_COUP_CATS'             => 'Categorias',
	'LAN_VSTORE_ADMIN_COUP_CATS_H'           => 'Categorias às quais este cupão será aplicado',
	'LAN_VSTORE_ADMIN_COUP_CATS_EX'          => 'Excluir categorias',
	'LAN_VSTORE_ADMIN_COUP_CATS_EX_H'        => 'Categorias às quais este cupão nunca será aplicado',
	'LAN_VSTORE_ADMIN_COUP_LIMIT_COUPON'     => 'Limite de utilização por cupão',
	'LAN_VSTORE_ADMIN_COUP_LIMIT_COUPON_H'   => 'Quantas vezes este cupão pode ser usado antes de ficar anulado. Introduza -1 para utilização ilimitada.',
	'LAN_VSTORE_ADMIN_COUP_LIMIT_USER'       => 'Limite de utilização por utilizador',
	'LAN_VSTORE_ADMIN_COUP_LIMIT_USER_H'     => 'Quantas vezes este cupão pode ser usado por um mesmo utilizador. Introduza -1 para utilização ilimitada.',
	'LAN_VSTORE_ADMIN_COUP_LIMIT_ITEM'       => 'Limitar a utilização a X artigos',
	'LAN_VSTORE_ADMIN_COUP_LIMIT_ITEM_H'     => 'Número máximo de artigos individuais aos quais este cupão pode ser aplicado em descontos de produto. Introduza -1 para aplicar a todos os artigos elegíveis do carrinho.',

	'LAN_VSTORE_ADMIN_COUP_ERR_INVALID'      => 'Código de cupão inválido!',
	'LAN_VSTORE_ADMIN_COUP_ERR_EXISTS'       => 'O código de cupão já existe!',
);
