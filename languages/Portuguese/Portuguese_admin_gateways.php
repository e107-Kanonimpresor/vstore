<?php
if (!defined('e107_INIT')) { exit; }

return array(
	'LAN_VSTORE_ADMIN_GATE_TAB_BANK'          => 'Transferência bancária',
	'LAN_VSTORE_ADMIN_GATE_MOLLIE_TEST'       => 'Modo de teste do Mollie',
	'LAN_VSTORE_ADMIN_GATE_MOLLIE_TEST_H'     => 'Usar o modo de teste do Mollie',
	'LAN_VSTORE_ADMIN_GATE_MOLLIE_KEY_LIVE'   => 'Chave API do Mollie (produção)',
	'LAN_VSTORE_ADMIN_GATE_MOLLIE_KEY_TEST'   => 'Chave API do Mollie (testes)',
	'LAN_VSTORE_ADMIN_GATE_MOLLIE_KEYS_NOTE'  => 'Obtenha as suas chaves API <a href="https://www.mollie.com/dashboard/developers/api-keys">aqui</a>',
	'LAN_VSTORE_ADMIN_GATE_MOLLIE_METHODS'    => 'Métodos de pagamento do Mollie',
	'LAN_VSTORE_ADMIN_GATE_MOLLIE_METHODS_NOTE' => 'Selecione pelo menos 1 método de pagamento.<br/>O método de pagamento TEM de estar ativado no seu painel Mollie ANTES de o poder usar com o Vstore!<br/>Tenha em conta que nem todos os métodos suportam todas as moedas.',
	'LAN_VSTORE_ADMIN_GATE_BANK_TITLE_DEF'    => 'Transferência bancária',
	'LAN_VSTORE_ADMIN_GATE_BANK_DETAILS'      => 'Transferência bancária',
	'LAN_VSTORE_ADMIN_GATE_BANK_DETAILS_PH'   => 'Dados da conta bancária',
	'LAN_VSTORE_ADMIN_GATE_CLASSNAME'         => 'Nome da classe',
	'LAN_VSTORE_ADMIN_GATE_DEPRECATED_TITLE'  => 'Importante',
	'LAN_VSTORE_ADMIN_GATE_RETIRED'           => 'Estas plataformas já não são suportadas e nunca são oferecidas no checkout: [x]. As definições guardadas são ignoradas. Utilize a Mollie (cartões e métodos locais), o PayPal ou a transferência bancária.',
	'LAN_VSTORE_ADMIN_GATE_MOLLIE_CURRENCY'   => 'Os métodos Mollie [x] só aceitam euros, por isso não são oferecidos no checkout enquanto a moeda da loja for [y]. Altere a moeda para EUR em Preferências para os oferecer.',
	'LAN_VSTORE_ADMIN_GATE_DEPRECATED_TEXT'   => '<p>A forma como as preferências dos gateways são guardadas foi alterada.</p><p><b>Estas preferências antigas foram eliminadas.</b></p><p>Se desejar, pode voltar a introduzir estes valores no formulário abaixo.</p>',
);
