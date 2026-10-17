<?php
if (!defined('e107_INIT')) { exit; }

return array(
	'LAN_VSTORE_ADMIN_ORD_TAB_LOG'         => 'Registo',

	'LAN_VSTORE_ADMIN_ORD_PAY_STATUS'      => 'Estado do pagamento',
	'LAN_VSTORE_ADMIN_ORD_REFUND_DATE'     => 'Data de reembolso',
	'LAN_VSTORE_ADMIN_ORD_INVOICE_NR'      => 'Fatura n.º',
	'LAN_VSTORE_ADMIN_ORD_BILLING_TO'      => 'Faturar a',
	'LAN_VSTORE_ADMIN_ORD_SHIP_TO'         => 'Enviar para',
	'LAN_VSTORE_ADMIN_ORD_TRANSID'         => 'ID da transação',
	'LAN_VSTORE_ADMIN_ORD_CURRENCY'        => 'Moeda',
	'LAN_VSTORE_ADMIN_ORD_SESSION'         => 'Sessão',
	'LAN_VSTORE_ADMIN_ORD_RAWDATA'         => 'Dados em bruto',

	'LAN_VSTORE_ADMIN_ORD_PAYSTATUS_INCOMPLETE' => 'Incompleto',
	'LAN_VSTORE_ADMIN_ORD_PAYSTATUS_COMPLETE'   => 'Completo',
	'LAN_VSTORE_ADMIN_ORD_PAYSTATUS_REFUNDED'   => 'Reembolsado',

	'LAN_VSTORE_ADMIN_ORD_FILTER_OPEN'     => 'Abertas',
	'LAN_VSTORE_ADMIN_ORD_INVOICE_TIP'     => 'Clique para abrir/gerar o PDF da fatura',
	'LAN_VSTORE_ADMIN_ORD_INVOICE_FORCE'   => 'Marque para forçar a criação de um novo PDF de fatura ao guardar',

	'LAN_VSTORE_ADMIN_ORD_BTN_CANCEL'      => 'Cancelar encomenda',
	'LAN_VSTORE_ADMIN_ORD_BTN_HOLD'        => 'Pôr em espera',
	'LAN_VSTORE_ADMIN_ORD_BTN_PROCESS'     => 'Processar encomenda',
	'LAN_VSTORE_ADMIN_ORD_BTN_CHECK_PAYMENT' => 'Verificar pagamento na Mollie',
	'LAN_VSTORE_ADMIN_ORD_BTN_REFUND'      => 'Reembolsar encomenda',
	'LAN_VSTORE_ADMIN_ORD_BTN_COMPLETE'    => 'Concluir encomenda',
	'LAN_VSTORE_ADMIN_ORD_CONFIRM_CANCEL'  => 'Quer mesmo cancelar esta encomenda?',
	'LAN_VSTORE_ADMIN_ORD_CONFIRM_HOLD'    => 'Quer mesmo pôr a encomenda em espera?',
	'LAN_VSTORE_ADMIN_ORD_CONFIRM_REFUND'  => 'Quer mesmo reembolsar esta encomenda?',

	'LAN_VSTORE_ADMIN_ORD_COL_NAME'        => 'Nome',
	'LAN_VSTORE_ADMIN_ORD_COL_DESCRIPTION' => 'Descrição',
	'LAN_VSTORE_ADMIN_ORD_COL_QTY'         => 'Qtd.',
	'LAN_VSTORE_ADMIN_ORD_COL_PRICE'       => 'Preço',
	'LAN_VSTORE_ADMIN_ORD_COL_DATETIME'    => 'Data/Hora',
	'LAN_VSTORE_ADMIN_ORD_COL_USER'        => 'Utilizador',
	'LAN_VSTORE_ADMIN_ORD_NA'              => 'n/d',
	'LAN_VSTORE_ADMIN_ORD_NO_BILLING'      => 'Não existe morada de faturação',
	'LAN_VSTORE_ADMIN_ORD_NO_SHIPPING'     => 'Não existe morada de envio',

	'LAN_VSTORE_ADMIN_ORD_MSG_USERCLASS'   => 'As classes de utilizador atribuídas ao cliente durante a compra não podem ser removidas automaticamente.<br/>Clique <a href="[x]">aqui</a> para remover manualmente as seguintes classes de utilizador.<br/>[y]',
	'LAN_VSTORE_ADMIN_ORD_MSG_REFUND_FAIL' => 'Não foi possível reembolsar a encomenda.',
	'LAN_VSTORE_ADMIN_ORD_MSG_INV_FAIL'    => 'Não foi possível criar a fatura.',
	'LAN_VSTORE_ADMIN_ORD_MSG_INV_OK'      => 'Fatura criada com sucesso!',
);
