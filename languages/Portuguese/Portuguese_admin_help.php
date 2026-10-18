<?php
if (!defined('e107_INIT')) { exit; }

// START
define('LAN_VSTORE_GUIDE_START_TAB',    'Início rápido');
define('LAN_VSTORE_GUIDE_START_TITLE',  'Abra a sua loja em seis passos');
define('LAN_VSTORE_GUIDE_START_INTRO',  'Siga estes passos por ordem. Cada um corresponde a um separador desta área de administração.');
define('LAN_VSTORE_GUIDE_START_S1',     'Abra as <strong>Preferências</strong> e defina a moeda, o formato de preço e a unidade de peso no separador Geral.');
define('LAN_VSTORE_GUIDE_START_S2',     'Crie pelo menos uma categoria em <strong>Categorias</strong>.');
define('LAN_VSTORE_GUIDE_START_S3',     'Adicione os seus produtos em <strong>Produtos</strong>. Use um código único para cada produto.');
define('LAN_VSTORE_GUIDE_START_S4',     'Configure os <strong>Envios</strong> e os <strong>Impostos</strong> nos separadores das Preferências, se a sua loja precisar.');
define('LAN_VSTORE_GUIDE_START_S5',     'Introduza as credenciais de pelo menos um gateway em <strong>Gateways de Pagamento</strong> e ative-o.');
define('LAN_VSTORE_GUIDE_START_S6',     'Faça uma encomenda de teste e acompanhe-a em <strong>Vendas</strong>.');

// CATALOG
define('LAN_VSTORE_GUIDE_CATALOG_TAB',    'Catálogo');
define('LAN_VSTORE_GUIDE_CATALOG_TITLE',  'Categorias, produtos e variações');
define('LAN_VSTORE_GUIDE_CATALOG_INTRO',  'O catálogo é construído com três elementos relacionados.');
define('LAN_VSTORE_GUIDE_CATALOG_CAT_H',  'Categorias');
define('LAN_VSTORE_GUIDE_CATALOG_CAT_T',  'As categorias organizam a loja e podem ser aninhadas. Restrinja uma categoria a uma classe de utilizador para a ocultar dos restantes visitantes.');
define('LAN_VSTORE_GUIDE_CATALOG_PROD_H', 'Produtos');
define('LAN_VSTORE_GUIDE_CATALOG_PROD_T', 'Cada produto tem um código único (maiúsculas, dígitos e hífenes), um preço, um inventário e uma classe de imposto. Use <code>-1</code> como inventário para stock ilimitado. As imagens e os vídeos são adicionados no seu próprio separador. Se deixar o preço vazio (não 0), o produto é oferecido como <strong>orçamento</strong>: não mostra preço e o botão passa a ser "Orçamento".');
define('LAN_VSTORE_GUIDE_CATALOG_VAR_H',  'Variações');
define('LAN_VSTORE_GUIDE_CATALOG_VAR_T',  'Crie um grupo de variações (por exemplo tamanhos) uma vez e atribua até dois grupos a cada produto. Uma variação pode alterar o preço com um valor fixo ou uma percentagem e pode ter o seu próprio inventário.');

// COUPONS
define('LAN_VSTORE_GUIDE_COUPONS_TAB',    'Cupões');
define('LAN_VSTORE_GUIDE_COUPONS_TITLE',  'Cupões de desconto');
define('LAN_VSTORE_GUIDE_COUPONS_INTRO',  'Os clientes introduzem os cupões no carrinho.');
define('LAN_VSTORE_GUIDE_COUPONS_TYPES_H','Tipos de desconto');
define('LAN_VSTORE_GUIDE_COUPONS_TYPES_T','Um cupão percentual desconta uma parte de cada artigo elegível. Um cupão fixo nunca desconta mais do que o preço do artigo.');
define('LAN_VSTORE_GUIDE_COUPONS_RULES_H','Restrições');
define('LAN_VSTORE_GUIDE_COUPONS_RULES_T','Defina as datas de início e fim e inclua ou exclua categorias no separador Restrições. As categorias excluídas prevalecem sempre sobre as incluídas.');
define('LAN_VSTORE_GUIDE_COUPONS_LIMITS_H','Limites');
define('LAN_VSTORE_GUIDE_COUPONS_LIMITS_T','Limite quantas vezes um cupão pode ser usado no total e por utilizador no separador Limites. Use <code>-1</code> para não limitar.');

// TAX
define('LAN_VSTORE_GUIDE_TAX_TAB',        'Impostos e IVA');
define('LAN_VSTORE_GUIDE_TAX_TITLE',      'Cálculo de impostos');
define('LAN_VSTORE_GUIDE_TAX_INTRO',      'O imposto é calculado no checkout a partir do país de faturação indicado pelo cliente.');
define('LAN_VSTORE_GUIDE_TAX_SETUP_H',    'Configuração');
define('LAN_VSTORE_GUIDE_TAX_SETUP_T',    'Nas Preferências, abra o separador Impostos, ative o cálculo de impostos e selecione o país do seu negócio. Ative a verificação online do NIF/IVA se vender a empresas da UE.');
define('LAN_VSTORE_GUIDE_TAX_CLASSES_H',  'Classes de imposto');
define('LAN_VSTORE_GUIDE_TAX_CLASSES_T',  'Introduza os valores como decimais, por exemplo <code>0.19</code> para 19%. Atribua uma classe de imposto a cada produto no separador Avançado.');
define('LAN_VSTORE_GUIDE_TAX_SCEN_H',     'O que paga o cliente');
define('LAN_VSTORE_GUIDE_TAX_SC1',        'Cliente no seu país: aplica-se a sua taxa local.');
define('LAN_VSTORE_GUIDE_TAX_SC2',        'Consumidor de outro país da UE: aplica-se a taxa do país de destino.');
define('LAN_VSTORE_GUIDE_TAX_SC3',        'Empresa da UE com NIF/IVA válido: 0% (autoliquidação).');
define('LAN_VSTORE_GUIDE_TAX_SC4',        'Cliente fora da UE: 0% (exportação).');

// SHIPPING
define('LAN_VSTORE_GUIDE_SHIP_TAB',       'Envios');
define('LAN_VSTORE_GUIDE_SHIP_TITLE',     'Custos de envio');
define('LAN_VSTORE_GUIDE_SHIP_INTRO',     'Ative os envios no separador Envios das Preferências e escolha um método de cálculo.');
define('LAN_VSTORE_GUIDE_SHIP_M1',        '<strong>Soma de todos os artigos</strong>: soma o custo de envio de cada artigo do carrinho.');
define('LAN_VSTORE_GUIDE_SHIP_M2',        '<strong>Soma de artigos únicos</strong>: soma o custo de envio de cada produto diferente apenas uma vez.');
define('LAN_VSTORE_GUIDE_SHIP_M3',        '<strong>Escalonado</strong>: os custos dependem de limiares do preço ou peso da encomenda. Comece pelo limiar mais baixo e faça com que o último ultrapasse a sua maior encomenda.');

// GATEWAYS
define('LAN_VSTORE_GUIDE_GATE_TAB',       'Pagamentos');
define('LAN_VSTORE_GUIDE_GATE_TITLE',     'Gateways de pagamento');
define('LAN_VSTORE_GUIDE_GATE_INTRO',     'Cada gateway tem o seu próprio separador com as credenciais e um interruptor de ativação.');
define('LAN_VSTORE_GUIDE_GATE_G1',        '<strong>PayPal</strong> (standard e REST): requer credenciais de API da sua conta PayPal.');
define('LAN_VSTORE_GUIDE_GATE_G2',        '<strong>Mollie</strong>: requer uma chave de API e permite escolher os métodos de pagamento oferecidos no checkout.');
define('LAN_VSTORE_GUIDE_GATE_G3',        '<strong>Transferência bancária</strong>: a encomenda fica pendente até a marcar como paga.');
define('LAN_VSTORE_GUIDE_GATE_NOTE',      'Teste sempre um gateway em modo sandbox antes de passar a produção.');

// ORDERS
define('LAN_VSTORE_GUIDE_ORDERS_TAB',     'Encomendas');
define('LAN_VSTORE_GUIDE_ORDERS_TITLE',   'Encomendas, emails e faturas');
define('LAN_VSTORE_GUIDE_ORDERS_INTRO',   'Tudo o que está relacionado com uma venda é gerido no separador Vendas.');
define('LAN_VSTORE_GUIDE_ORDERS_ST_H',    'Estado da encomenda');
define('LAN_VSTORE_GUIDE_ORDERS_ST_T',    'Uma encomenda passa por Nova, Em processamento, Em espera, Concluída, Cancelada e Reembolsada. As alterações de estado atualizam o inventário e enviam o email correspondente. Os pedidos de orçamento seguem o seu próprio fluxo: Orçamento solicitado, Migrado para a app, Orçamento aprovado e Faturado.');
define('LAN_VSTORE_GUIDE_ORDERS_EM_H',    'Modelos de email');
define('LAN_VSTORE_GUIDE_ORDERS_EM_T',    'Existe um modelo para cada alteração de estado. Use o botão de repor para recuperar o texto original.');
define('LAN_VSTORE_GUIDE_ORDERS_INV_H',   'Faturas');
define('LAN_VSTORE_GUIDE_ORDERS_INV_T',   'As faturas PDF são criadas pelo próprio Vstore (TCPDF). Defina o prefixo de numeração, o número seguinte e o rodapé em Configurações de Faturas e verifique o resultado no separador de pré-visualização.');

// TROUBLESHOOTING
define('LAN_VSTORE_GUIDE_TROUBLE_TAB',    'Resolução de problemas');
define('LAN_VSTORE_GUIDE_TROUBLE_TITLE',  'Problemas habituais');
define('LAN_VSTORE_GUIDE_TROUBLE_INTRO',  'Verifique estes pontos antes de pedir suporte.');
define('LAN_VSTORE_GUIDE_TROUBLE_Q1',     'Um erro fatal menciona uma classe ou um ficheiro de autoload inexistente');
define('LAN_VSTORE_GUIDE_TROUBLE_A1',     'Execute <code>composer install --no-dev --optimize-autoloader</code> dentro da pasta do plugin. O diretório <code>vendor</code> não está incluído no repositório.');
define('LAN_VSTORE_GUIDE_TROUBLE_Q2',     'As faturas não são criadas em PDF');
define('LAN_VSTORE_GUIDE_TROUBLE_A2',     'Execute "composer install" em e107_plugins/vstore e ative as faturas PDF em Configurações de Faturas.');
define('LAN_VSTORE_GUIDE_TROUBLE_Q3',     'É aplicada uma taxa de imposto incorreta');
define('LAN_VSTORE_GUIDE_TROUBLE_A3',     'Verifique o país do negócio, a classe de imposto do produto e o país indicado no formulário de faturação.');
define('LAN_VSTORE_GUIDE_TROUBLE_Q4',     'Preciso de mais informação sobre o que está a acontecer');
define('LAN_VSTORE_GUIDE_TROUBLE_A4',     'Retire o comentário da linha <code>e_DEBUG_VSTORE</code> em <code>e_header.php</code> para ativar o registo de depuração e volte a removê-la em produção.');
