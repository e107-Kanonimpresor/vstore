<?php
if (!defined('e107_INIT')) { exit; }

return array(
	// Separadores
	'LAN_VSTORE_ADMIN_PREF_TAB_SHIP'      => 'Envios',
	'LAN_VSTORE_ADMIN_PREF_TAB_TAX'       => 'Impostos',
	'LAN_VSTORE_ADMIN_PREF_TAB_EMAIL'     => 'Emails',
	'LAN_VSTORE_ADMIN_PREF_TAB_HOWTO'     => 'Como encomendar',
	'LAN_VSTORE_ADMIN_PREF_TAB_ADMIN'     => 'Área de administração',
	'LAN_VSTORE_ADMIN_PREF_TAB_CHECKOUT'  => 'Checkout',
	'LAN_VSTORE_ADMIN_PREF_TAB_MENU'      => 'Menu',
	'LAN_VSTORE_ADMIN_PREF_TAB_CSS'       => 'CSS personalizado',

	// Geral
	'LAN_VSTORE_ADMIN_PREF_CAPTION'            => 'Título da loja',
	'LAN_VSTORE_ADMIN_PREF_CAPTION_H'          => 'O título apresentado no topo das páginas da loja.',
	'LAN_VSTORE_ADMIN_PREF_CAPTION_CAT'        => 'Título das categorias',
	'LAN_VSTORE_ADMIN_PREF_CAPTION_CAT_PH'     => 'Marcas de produtos',
	'LAN_VSTORE_ADMIN_PREF_CAPTION_STOCK'      => 'Texto de produto esgotado',
	'LAN_VSTORE_ADMIN_PREF_CAPTION_STOCK_PH'   => 'Esgotado',
	'LAN_VSTORE_ADMIN_PREF_CURRENCY'           => 'Moeda',
	'LAN_VSTORE_ADMIN_PREF_CURRENCY_H'         => 'Selecione uma moeda.',
	'LAN_VSTORE_ADMIN_PREF_AMOUNT_FORMAT'      => 'Formato do preço',
	'LAN_VSTORE_ADMIN_PREF_AMOUNT_FORMAT_H'    => 'Selecione o formato com que os preços dos produtos serão apresentados.',
	'LAN_VSTORE_ADMIN_PREF_AMOUNT_BEFORE'      => 'Moeda antes do número',
	'LAN_VSTORE_ADMIN_PREF_AMOUNT_BEHIND'      => 'Moeda depois do número',
	'LAN_VSTORE_ADMIN_PREF_WEIGHT_UNIT'        => 'Unidade de peso',
	'LAN_VSTORE_ADMIN_PREF_WEIGHT_UNIT_H'      => 'Selecione uma unidade de peso.',
	'LAN_VSTORE_ADMIN_PREF_USERCLASS'          => 'Atribuir classe de utilizador',
	'LAN_VSTORE_ADMIN_PREF_USERCLASS_H'        => 'Atribui uma classe de utilizador ao cliente após concluir a compra.',
	'LAN_VSTORE_ADMIN_PREF_USERCLASS_PRODUCT'  => 'Conforme definido no produto',
	'LAN_VSTORE_ADMIN_PREF_SHOW_STOCK'         => 'Mostrar/ocultar produtos esgotados',
	'LAN_VSTORE_ADMIN_PREF_SHOW_STOCK_H'       => 'Mostra ou oculta os produtos esgotados nas listagens de produtos',

	// Envios
	'LAN_VSTORE_ADMIN_PREF_SHIPPING'           => 'Calcular envio',
	'LAN_VSTORE_ADMIN_PREF_SHIPPING_H'         => 'Incluir o cálculo do envio no checkout.',
	'LAN_VSTORE_ADMIN_PREF_SHIP_METHOD'        => 'Método de cálculo',
	'LAN_VSTORE_ADMIN_PREF_SHIP_METHOD_H'      => 'Defina um método para calcular o custo de envio.',
	'LAN_VSTORE_ADMIN_PREF_SHIP_SUM_SIMPLE'    => 'Soma do custo de envio de todos os artigos',
	'LAN_VSTORE_ADMIN_PREF_SHIP_SUM_UNIQUE'    => 'Soma do custo de envio apenas dos artigos únicos',
	'LAN_VSTORE_ADMIN_PREF_SHIP_TIERED'        => 'Um sistema escalonado por preço ou peso',
	'LAN_VSTORE_ADMIN_PREF_SHIP_UNIT'          => 'Valor baseado em',
	'LAN_VSTORE_ADMIN_PREF_SHIP_LIMIT'         => 'Os custos são',
	'LAN_VSTORE_ADMIN_PREF_SHIP_DATA'          => 'Custos de envio escalonados',
	'LAN_VSTORE_ADMIN_PREF_SHIP_DATA_H'        => 'Introduza limiares na primeira coluna para fixar ou limitar o custo de envio com base no preço ou peso total da encomenda. Comece pelo limiar mais baixo e adicione mais até o último ser superior ao preço/peso máximo de uma encomenda habitual. Se o último limiar for demasiado baixo, o envio poderá ficar sem custo.',
	'LAN_VSTORE_ADMIN_PREF_SHIP_UNIT_PRICE'    => 'Quando o preço total da encomenda for inferior a &mdash;',
	'LAN_VSTORE_ADMIN_PREF_SHIP_UNIT_WEIGHT'   => 'Quando o peso total da encomenda for inferior a &mdash;',
	'LAN_VSTORE_ADMIN_PREF_SHIP_LIMIT_FIXED'   => 'Fixar o custo de envio em &mdash;',
	'LAN_VSTORE_ADMIN_PREF_SHIP_LIMIT_MAX'     => 'Limitar o custo de envio a não mais de &mdash;',

	// Emails
	'LAN_VSTORE_ADMIN_PREF_SENDER_NAME'        => 'Nome do remetente',
	'LAN_VSTORE_ADMIN_PREF_SENDER_NAME_PH'     => 'Departamento de vendas',
	'LAN_VSTORE_ADMIN_PREF_SENDER_H'           => 'Deixe vazio para usar o valor do sistema',
	'LAN_VSTORE_ADMIN_PREF_SENDER_EMAIL_PH'    => 'encomendas@minhaloja.com',
	'LAN_VSTORE_ADMIN_PREF_MERCHANT'           => 'Nome/morada do comerciante',
	'LAN_VSTORE_ADMIN_PREF_MERCHANT_PH'        => 'A Minha Loja, Lda., etc.',
	'LAN_VSTORE_ADMIN_PREF_MERCHANT_H'         => 'Será apresentado no email ao cliente.',

	// Como encomendar
	'LAN_VSTORE_ADMIN_PREF_HOWTO'              => 'Como encomendar',
	'LAN_VSTORE_ADMIN_PREF_HOWTO_H'            => 'Introduza a informação sobre como fazer uma encomenda.',

	// Administração
	'LAN_VSTORE_ADMIN_PREF_ITEMS_PERPAGE'      => 'Produtos por página',
	'LAN_VSTORE_ADMIN_PREF_CATS_PERPAGE'       => 'Categorias por página',

	// Checkout
	'LAN_VSTORE_ADMIN_PREF_ADDITIONAL'         => 'Campos adicionais',
	'LAN_VSTORE_ADMIN_PREF_ADD_PLACEHOLDER'    => 'Texto de exemplo',
	'LAN_VSTORE_ADMIN_PREF_ADD_FIELDTYPE'      => 'Tipo de campo',
	'LAN_VSTORE_ADMIN_PREF_ADD_REQUIRED'       => 'Obrigatório',
	'LAN_VSTORE_ADMIN_PREF_ADD_TEXTBOX'        => 'Caixa de texto',
	'LAN_VSTORE_ADMIN_PREF_ADD_CHECKBOX'       => 'Caixa de seleção',

	// CSS personalizado
	'LAN_VSTORE_ADMIN_PREF_CSS'                => 'CSS personalizado',
	'LAN_VSTORE_ADMIN_PREF_CSS_H'              => 'Use este campo para adicionar CSS próprio do Vstore sem necessidade de editar ficheiros.',

	// Impostos
	'LAN_VSTORE_ADMIN_PREF_TAX_CALC'           => 'Calcular impostos',
	'LAN_VSTORE_ADMIN_PREF_TAX_CALC_H'         => 'Ative para ativar o cálculo de impostos.',
	'LAN_VSTORE_ADMIN_PREF_TAX_COUNTRY'        => 'País do negócio',
	'LAN_VSTORE_ADMIN_PREF_TAX_COUNTRY_H'      => 'O país onde o negócio está localizado.',
	'LAN_VSTORE_ADMIN_PREF_TAX_VAT'            => 'Verificar NIF/IVA online (apenas UE!)',
	'LAN_VSTORE_ADMIN_PREF_TAX_VAT_H'          => 'Ative para verificar online o NIF/IVA (apenas UE!).',
	'LAN_VSTORE_ADMIN_PREF_TAX_ORIGIN'         => 'Cobrar aos consumidores da UE o IVA do país da loja',
	'LAN_VSTORE_ADMIN_PREF_TAX_ORIGIN_H'       => 'Desde julho de 2021 aplica-se o IVA do país do cliente nas vendas a consumidores de outros países da UE (OSS). Quem não ultrapassar os 10.000 EUR anuais de vendas à distância em toda a UE pode aplicar o IVA do seu próprio país. Ative apenas se for o seu caso; consulte o seu contabilista.',
	'LAN_VSTORE_ADMIN_PREF_TAX_CLASSES'        => 'Classes de imposto',
	'LAN_VSTORE_ADMIN_PREF_TAX_CLASSES_H'      => 'As classes de imposto e o valor predefinido a usar com os produtos.<br />Introduza o valor como número decimal, por exemplo 0.19 para 19%',
	'LAN_VSTORE_ADMIN_PREF_TAX_NAME'           => 'Nome',
	'LAN_VSTORE_ADMIN_PREF_TAX_DESCRIPTION'    => 'Descrição',
	'LAN_VSTORE_ADMIN_PREF_TAX_VALUE'          => 'Imposto',
	'LAN_VSTORE_ADMIN_PREF_TAX_DEF_NONE'       => 'Sem imposto',
	'LAN_VSTORE_ADMIN_PREF_TAX_DEF_REDUCED'    => 'Imposto reduzido',
	'LAN_VSTORE_ADMIN_PREF_TAX_DEF_STANDARD'   => 'Imposto normal',
	'LAN_VSTORE_ADMIN_PREF_TAX_ORDER_WARNING'  => 'As classes de imposto não parecem estar por ordem!<br>As 3 primeiras têm de ser "none", "reduced", "standard"!<br/>Adicione as classes específicas do seu país a seguir.',

	// Menu
	'LAN_VSTORE_ADMIN_PREF_MENU_CAT'           => 'Categoria de produtos',
	'LAN_VSTORE_ADMIN_PREF_MENU_COUNT'         => 'Limite de produtos',
	'LAN_VSTORE_ADMIN_PREF_MENU_COUNT_H'       => 'Introduza o número de produtos a apresentar no menu.',
);
