<?php
if (!defined('e107_INIT')) { exit; }

// Apenas texto simples (permitido <code>/<strong> em linha). A estrutura HTML está em templates/vstore_guide_template.php.

// RESUMO
define('LAN_VSTORE_GUIDE_OVERVIEW_TAB',    'Resumo');
define('LAN_VSTORE_GUIDE_OVERVIEW_TITLE',  'O Vstore num relance');
define('LAN_VSTORE_GUIDE_OVERVIEW_INTRO',  'O Vstore transforma o seu site e107 numa loja online: catálogo, carrinho, checkout, pagamentos, faturas e uma área de cliente. Os produtos sem preço são tratados como pedidos de orçamento. Este guia explica cada parte da área de administração.');
define('LAN_VSTORE_GUIDE_OVERVIEW_FLOW1',  'Preferências');
define('LAN_VSTORE_GUIDE_OVERVIEW_FLOW2',  'Catálogo');
define('LAN_VSTORE_GUIDE_OVERVIEW_FLOW3',  'Pagamentos');
define('LAN_VSTORE_GUIDE_OVERVIEW_FLOW4',  'Encomenda de teste');
define('LAN_VSTORE_GUIDE_OVERVIEW_FLOW5',  'Vendas');
define('LAN_VSTORE_GUIDE_OVERVIEW_FEAT_H', 'O que o Vstore faz');
define('LAN_VSTORE_GUIDE_OVERVIEW_F1_T',   'Catálogo');
define('LAN_VSTORE_GUIDE_OVERVIEW_F1_D',   'Categorias aninhadas, produtos, variações, galeria de imagens e ficheiros para download.');
define('LAN_VSTORE_GUIDE_OVERVIEW_F2_T',   'Orçamentos');
define('LAN_VSTORE_GUIDE_OVERVIEW_F2_D',   'Um produto sem preço é pedido em vez de comprado, com o seu próprio fluxo de trabalho.');
define('LAN_VSTORE_GUIDE_OVERVIEW_F3_T',   'Carrinho misto');
define('LAN_VSTORE_GUIDE_OVERVIEW_F3_D',   'Produtos para comprar e para orçamentar partilham um carrinho, cada bloco com o seu botão.');
define('LAN_VSTORE_GUIDE_OVERVIEW_F4_T',   'Impostos e IVA UE');
define('LAN_VSTORE_GUIDE_OVERVIEW_F4_D',   'Taxas por país e classe de imposto, autoliquidação com um NIF-IVA válido.');
define('LAN_VSTORE_GUIDE_OVERVIEW_F5_T',   'Pagamentos');
define('LAN_VSTORE_GUIDE_OVERVIEW_F5_D',   'PayPal, Mollie e transferência bancária, cada um ativado em separado.');
define('LAN_VSTORE_GUIDE_OVERVIEW_F6_T',   'Faturas PDF');
define('LAN_VSTORE_GUIDE_OVERVIEW_F6_D',   'Criadas pelo próprio Vstore com numeração e rodapé próprios.');
define('LAN_VSTORE_GUIDE_OVERVIEW_F7_T',   'Área de cliente');
define('LAN_VSTORE_GUIDE_OVERVIEW_F7_D',   'Encomendas, downloads, orçamentos abertos e um gráfico dos últimos seis meses.');
define('LAN_VSTORE_GUIDE_OVERVIEW_F8_T',   'Estatísticas');
define('LAN_VSTORE_GUIDE_OVERVIEW_F8_D',   'Caixas de vendas e um contador dos orçamentos à espera de resposta.');
define('LAN_VSTORE_GUIDE_OVERVIEW_WHERE_H','Onde encontrar cada coisa');
define('LAN_VSTORE_GUIDE_OVERVIEW_W1_N',   'Preferências');
define('LAN_VSTORE_GUIDE_OVERVIEW_W1_D',   'Moeda, envio, impostos, checkout e opções de apresentação.');
define('LAN_VSTORE_GUIDE_OVERVIEW_W2_N',   'Categorias e Produtos');
define('LAN_VSTORE_GUIDE_OVERVIEW_W2_D',   'O catálogo, com imagens, preços, inventário e variações.');
define('LAN_VSTORE_GUIDE_OVERVIEW_W3_N',   'Cupons');
define('LAN_VSTORE_GUIDE_OVERVIEW_W3_D',   'Códigos de desconto com datas, categorias e limites de utilização.');
define('LAN_VSTORE_GUIDE_OVERVIEW_W4_N',   'Gateways de Pagamento');
define('LAN_VSTORE_GUIDE_OVERVIEW_W4_D',   'Credenciais e ativação de cada método de pagamento.');
define('LAN_VSTORE_GUIDE_OVERVIEW_W5_N',   'Vendas');
define('LAN_VSTORE_GUIDE_OVERVIEW_W5_D',   'Encomendas e orçamentos, com o seu estado, fatura e histórico.');
define('LAN_VSTORE_GUIDE_OVERVIEW_W6_N',   'Modelos de Email e Configurações de Faturas');
define('LAN_VSTORE_GUIDE_OVERVIEW_W6_D',   'As mensagens que os clientes recebem e o aspeto da fatura em PDF.');
define('LAN_VSTORE_GUIDE_OVERVIEW_TIP',    'A barra lateral de cada página de administração mostra uma dica breve sobre a página em que está e ligações para a documentação e para o gestor de problemas.');

// INÍCIO RÁPIDO
define('LAN_VSTORE_GUIDE_START_TAB',       'Início rápido');
define('LAN_VSTORE_GUIDE_START_TITLE',     'Abra a sua loja em seis passos');
define('LAN_VSTORE_GUIDE_START_INTRO',     'Siga estes passos por ordem. Cada um indica uma entrada do menu desta área de administração.');
define('LAN_VSTORE_GUIDE_START_S1_T',      'Defina o essencial');
define('LAN_VSTORE_GUIDE_START_S1_D',      'Abra as <strong>Preferências</strong> e escolha a moeda, o formato de preço e a unidade de peso no separador Geral.');
define('LAN_VSTORE_GUIDE_START_S2_T',      'Crie categorias');
define('LAN_VSTORE_GUIDE_START_S2_D',      'Adicione pelo menos uma categoria em <strong>Categorias</strong>. Uma imagem melhora o aspeto do mosaico da loja.');
define('LAN_VSTORE_GUIDE_START_S3_T',      'Adicione produtos');
define('LAN_VSTORE_GUIDE_START_S3_D',      'Crie os seus produtos em <strong>Produtos</strong> com código único, preço, inventário e imagens. Deixe o preço vazio para oferecer um orçamento.');
define('LAN_VSTORE_GUIDE_START_S4_T',      'Envio e impostos');
define('LAN_VSTORE_GUIDE_START_S4_D',      'Configure o <strong>Envio</strong> e os <strong>Impostos</strong> nos separadores das Preferências, se a sua loja precisar.');
define('LAN_VSTORE_GUIDE_START_S5_T',      'Ative um método de pagamento');
define('LAN_VSTORE_GUIDE_START_S5_D',      'Introduza as credenciais de pelo menos um gateway em <strong>Gateways de Pagamento</strong> e ative-o. Use primeiro o modo sandbox.');
define('LAN_VSTORE_GUIDE_START_S6_T',      'Faça uma encomenda de teste');
define('LAN_VSTORE_GUIDE_START_S6_D',      'Compre um produto na loja, acompanhe a encomenda em <strong>Vendas</strong> e verifique os emails e a fatura.');
define('LAN_VSTORE_GUIDE_START_NOTE',      'As ligações da loja (loja e carrinho) são criadas na navegação do site ao instalar o plugin. Se os visitantes não as veem, reveja quem as pode ver nas ligações do site.');

// CATÁLOGO
define('LAN_VSTORE_GUIDE_CATALOG_TAB',     'Catálogo');
define('LAN_VSTORE_GUIDE_CATALOG_TITLE',   'Categorias, produtos e variações');
define('LAN_VSTORE_GUIDE_CATALOG_INTRO',   'O catálogo é composto por três elementos relacionados.');
define('LAN_VSTORE_GUIDE_CATALOG_CAT_H',   'Categorias');
define('LAN_VSTORE_GUIDE_CATALOG_CAT_T',   'As categorias organizam a loja e podem ser aninhadas. A loja mostra as categorias de raiz como mosaicos com imagem e número de produtos. Restrinja uma categoria a uma classe de utilizador para a ocultar dos restantes visitantes.');
define('LAN_VSTORE_GUIDE_CATALOG_PROD_H',  'Produtos');
define('LAN_VSTORE_GUIDE_CATALOG_PROD_T',  'Cada produto tem um código único (letras maiúsculas, dígitos e hífenes), um preço, um inventário e uma classe de imposto. Use <code>-1</code> como inventário para stock ilimitado.');
define('LAN_VSTORE_GUIDE_CATALOG_VAR_H',   'Variações');
define('LAN_VSTORE_GUIDE_CATALOG_VAR_T',   'Crie uma vez um grupo de variações (por exemplo tamanhos) e atribua até dois grupos a cada produto. Uma variação pode alterar o preço num valor fixo ou numa percentagem e ter o seu próprio inventário.');
define('LAN_VSTORE_GUIDE_CATALOG_IMG_H',   'Imagens e galeria');
define('LAN_VSTORE_GUIDE_CATALOG_IMG_T',   'Adicione as imagens de um produto no respetivo separador Imagens.');
define('LAN_VSTORE_GUIDE_CATALOG_IMG_L1',  'A primeira imagem é a principal; até doze mais aparecem como miniaturas por baixo.');
define('LAN_VSTORE_GUIDE_CATALOG_IMG_L2',  'Ao clicar na imagem abre-se um visualizador em tamanho completo. Os visitantes movem-se com as setas ou o teclado e fecham-no com <code>Esc</code>.');
define('LAN_VSTORE_GUIDE_CATALOG_IMG_L3',  'Carregue imagens com pelo menos 1200 px de largura: o visualizador pede uma versão de 1600 px, criada e guardada em cache pelo e107.');
define('LAN_VSTORE_GUIDE_CATALOG_PRICE_H', 'Campo de preço');
define('LAN_VSTORE_GUIDE_CATALOG_PRICE_T', 'O preço decide como o produto é oferecido.');
define('LAN_VSTORE_GUIDE_CATALOG_P1_N',    'Preço superior a 0');
define('LAN_VSTORE_GUIDE_CATALOG_P1_D',    'Produto normal: botão "Adicionar ao carrinho", passa pelo checkout e pelo pagamento.');
define('LAN_VSTORE_GUIDE_CATALOG_P2_N',    'Preço 0');
define('LAN_VSTORE_GUIDE_CATALOG_P2_D',    'Produto gratuito, por exemplo um download. Continua a ser comprado através do carrinho.');
define('LAN_VSTORE_GUIDE_CATALOG_P3_N',    'Preço vazio');
define('LAN_VSTORE_GUIDE_CATALOG_P3_D',    'Produto sob orçamento: a loja mostra "Preço sob consulta" e um botão "Orçamento". Veja o separador Orçamentos.');

// ORÇAMENTOS
define('LAN_VSTORE_GUIDE_QUOTES_TAB',      'Orçamentos');
define('LAN_VSTORE_GUIDE_QUOTES_TITLE',    'Pedidos de orçamento');
define('LAN_VSTORE_GUIDE_QUOTES_INTRO',    'Venda produtos que precisam de um preço à medida: o cliente pergunta e você responde com um orçamento. Os pedidos são guardados como encomendas com o seu próprio fluxo de trabalho.');
define('LAN_VSTORE_GUIDE_QUOTES_HOW_H',    'Como funciona');
define('LAN_VSTORE_GUIDE_QUOTES_E1_T',     '1. O cliente pede');
define('LAN_VSTORE_GUIDE_QUOTES_E1_D',     'Adiciona produtos sem preço ao carrinho e carrega em <strong>Pedir orçamento</strong>. O checkout tem os mesmos passos de uma compra, mas sem preços, impostos nem método de pagamento.');
define('LAN_VSTORE_GUIDE_QUOTES_E2_T',     '2. Você analisa');
define('LAN_VSTORE_GUIDE_QUOTES_E2_D',     'O pedido chega a <strong>Vendas</strong> (filtro <em>Orçamentos</em>) e ao email da loja. Use os botões da página da encomenda para o fazer avançar no fluxo.');
define('LAN_VSTORE_GUIDE_QUOTES_E3_T',     '3. Você fatura');
define('LAN_VSTORE_GUIDE_QUOTES_E3_D',     'O orçamento passa para a sua aplicação de faturação externa, que emite a fatura. Marque o pedido como <em>Faturado</em> quando terminar.');
define('LAN_VSTORE_GUIDE_QUOTES_MIX_H',    'Comprar e orçamentar no mesmo carrinho');
define('LAN_VSTORE_GUIDE_QUOTES_MIX_T',    'O carrinho mostra dois blocos: <strong>Produtos para comprar</strong> (preços, cupão, totais e botão de Checkout) e <strong>Produtos para orçamentar</strong> (sem preços e com um botão Pedir orçamento). Cada botão processa apenas as suas linhas; as restantes ficam no carrinho para o passo seguinte.');
define('LAN_VSTORE_GUIDE_QUOTES_RULES_H',  'Regras a recordar');
define('LAN_VSTORE_GUIDE_QUOTES_R1',       'Um orçamento nunca recebe número de fatura, pelo que não consome a sequência certificada de faturas.');
define('LAN_VSTORE_GUIDE_QUOTES_R2',       'Um orçamento não altera o inventário e não conta como encomenda nas estatísticas.');
define('LAN_VSTORE_GUIDE_QUOTES_R3',       'É enviado um email ao criar o pedido, ao cliente e em cópia à loja. As mudanças de estado posteriores não enviam nada.');
define('LAN_VSTORE_GUIDE_QUOTES_R4',       'O cliente pode ver o pedido e cancelá-lo na sua área. Um pedido cancelado pode ser reaberto como pedido de orçamento a partir da página da encomenda.');
define('LAN_VSTORE_GUIDE_QUOTES_R5',       'A página de estatísticas mostra um contador vermelho com os pedidos pendentes; liga à lista filtrada.');
define('LAN_VSTORE_GUIDE_QUOTES_NOTE',     'O texto do email do orçamento edita-se em Modelos de Email, no separador Orçamento solicitado.');

// CUPÕES
define('LAN_VSTORE_GUIDE_COUPONS_TAB',     'Cupons');
define('LAN_VSTORE_GUIDE_COUPONS_TITLE',   'Cupons de desconto');
define('LAN_VSTORE_GUIDE_COUPONS_INTRO',   'Os clientes introduzem os cupons no carrinho.');
define('LAN_VSTORE_GUIDE_COUPONS_TYPES_H', 'Tipos de desconto');
define('LAN_VSTORE_GUIDE_COUPONS_TYPES_T', 'Um cupom percentual desconta uma parte de cada artigo elegível. Um cupom fixo nunca desconta mais do que o preço do artigo.');
define('LAN_VSTORE_GUIDE_COUPONS_RULES_H', 'Restrições');
define('LAN_VSTORE_GUIDE_COUPONS_RULES_T', 'Defina as datas de início e fim e inclua ou exclua categorias no separador Restrições. As categorias excluídas prevalecem sempre sobre as incluídas.');
define('LAN_VSTORE_GUIDE_COUPONS_LIMITS_H','Limites');
define('LAN_VSTORE_GUIDE_COUPONS_LIMITS_T','Limite quantas vezes um cupom pode ser usado no total e por utilizador no separador Limites. Use <code>-1</code> para não limitar.');
define('LAN_VSTORE_GUIDE_COUPONS_NOTE',    'Os cupons aplicam-se apenas aos produtos para comprar. Os produtos sob orçamento não têm preço e nunca são descontados.');

// IMPOSTOS
define('LAN_VSTORE_GUIDE_TAX_TAB',         'Impostos e IVA');
define('LAN_VSTORE_GUIDE_TAX_TITLE',       'Cálculo de impostos');
define('LAN_VSTORE_GUIDE_TAX_INTRO',       'O imposto é calculado no checkout a partir do país de faturação introduzido pelo cliente.');
define('LAN_VSTORE_GUIDE_TAX_SETUP_H',     'Configuração');
define('LAN_VSTORE_GUIDE_TAX_SETUP_T',     'Nas Preferências abra o separador Impostos, ative o cálculo de impostos e selecione o país da sua empresa. Ative a verificação online do NIF-IVA se vende a empresas da UE.');
define('LAN_VSTORE_GUIDE_TAX_CLASSES_H',   'Classes de imposto');
define('LAN_VSTORE_GUIDE_TAX_CLASSES_T',   'Introduza os valores como decimais, por exemplo <code>0.19</code> para 19 %. Atribua uma classe de imposto a cada produto no separador Avançado.');
define('LAN_VSTORE_GUIDE_TAX_SCEN_H',      'O que o cliente paga');
define('LAN_VSTORE_GUIDE_TAX_COL_WHO',     'Cliente');
define('LAN_VSTORE_GUIDE_TAX_COL_RATE',    'Imposto aplicado');
define('LAN_VSTORE_GUIDE_TAX_SC1_W',       'No seu país');
define('LAN_VSTORE_GUIDE_TAX_SC1_R',       'A sua taxa local.');
define('LAN_VSTORE_GUIDE_TAX_SC2_W',       'Consumidor de outro país da UE');
define('LAN_VSTORE_GUIDE_TAX_SC2_R',       'A taxa do país de destino.');
define('LAN_VSTORE_GUIDE_TAX_SC3_W',       'Empresa da UE com NIF-IVA válido');
define('LAN_VSTORE_GUIDE_TAX_SC3_R',       '0 % (autoliquidação).');
define('LAN_VSTORE_GUIDE_TAX_SC4_W',       'Fora da UE');
define('LAN_VSTORE_GUIDE_TAX_SC4_R',       '0 % (exportação).');
define('LAN_VSTORE_GUIDE_TAX_NOTE',        'Os preços são introduzidos sem impostos. Os pedidos de orçamento não mostram impostos; são calculados depois na sua aplicação de faturação.');

// ENVIO
define('LAN_VSTORE_GUIDE_SHIP_TAB',        'Envio');
define('LAN_VSTORE_GUIDE_SHIP_TITLE',      'Custos de envio');
define('LAN_VSTORE_GUIDE_SHIP_INTRO',      'Ative o envio no separador Envio das Preferências e escolha um método de cálculo.');
define('LAN_VSTORE_GUIDE_SHIP_M1_T',       'Soma de todos os artigos');
define('LAN_VSTORE_GUIDE_SHIP_M1_D',       'Soma o custo de envio de cada artigo do carrinho.');
define('LAN_VSTORE_GUIDE_SHIP_M2_T',       'Soma de artigos únicos');
define('LAN_VSTORE_GUIDE_SHIP_M2_D',       'Soma o custo de envio de cada produto diferente apenas uma vez.');
define('LAN_VSTORE_GUIDE_SHIP_M3_T',       'Por escalões');
define('LAN_VSTORE_GUIDE_SHIP_M3_D',       'Os custos dependem de limites do preço ou do peso da encomenda. Comece pelo limite mais baixo e faça com que o último seja superior à sua maior encomenda.');
define('LAN_VSTORE_GUIDE_SHIP_NOTE',       'O envio é somado apenas aos produtos para comprar. Um pedido de orçamento não mostra custos de envio.');

// GATEWAYS
define('LAN_VSTORE_GUIDE_GATE_TAB',        'Pagamentos');
define('LAN_VSTORE_GUIDE_GATE_TITLE',      'Gateways de pagamento');
define('LAN_VSTORE_GUIDE_GATE_INTRO',      'Cada gateway tem o seu próprio separador com as credenciais e um interruptor de ativação.');
define('LAN_VSTORE_GUIDE_GATE_G1_T',       'PayPal');
define('LAN_VSTORE_GUIDE_GATE_G1_D',       'Standard e REST. Requer credenciais de API da sua conta PayPal.');
define('LAN_VSTORE_GUIDE_GATE_G2_T',       'Mollie');
define('LAN_VSTORE_GUIDE_GATE_G2_D',       'Requer uma chave de API e permite escolher os métodos de pagamento oferecidos no checkout.');
define('LAN_VSTORE_GUIDE_GATE_G3_T',       'Transferência bancária');
define('LAN_VSTORE_GUIDE_GATE_G3_D',       'A encomenda fica pendente até a marcar como paga.');
define('LAN_VSTORE_GUIDE_GATE_NOTE',       'Teste sempre um gateway em modo sandbox antes de passar a produção.');

// ENCOMENDAS
define('LAN_VSTORE_GUIDE_ORDERS_TAB',      'Encomendas');
define('LAN_VSTORE_GUIDE_ORDERS_TITLE',    'Encomendas, emails e faturas');
define('LAN_VSTORE_GUIDE_ORDERS_INTRO',    'Tudo o que diz respeito a uma venda gere-se a partir da entrada de menu Vendas.');
define('LAN_VSTORE_GUIDE_ORDERS_ST_H',     'Estado da encomenda');
define('LAN_VSTORE_GUIDE_ORDERS_ST_T',     'Ao mudar o estado atualiza-se o inventário e envia-se o email correspondente. Os pedidos de orçamento seguem o seu próprio fluxo.');
define('LAN_VSTORE_GUIDE_ORDERS_SN',       'A encomenda foi feita e aguarda o pagamento.');
define('LAN_VSTORE_GUIDE_ORDERS_SP',       'O pagamento chegou e a encomenda está a ser preparada.');
define('LAN_VSTORE_GUIDE_ORDERS_SH',       'A encomenda está em pausa, por exemplo à espera de stock.');
define('LAN_VSTORE_GUIDE_ORDERS_SC',       'A encomenda foi cumprida e é enviado ao cliente o email de encomenda concluída.');
define('LAN_VSTORE_GUIDE_ORDERS_SX',       'A encomenda foi cancelada e é enviado o email de cancelamento.');
define('LAN_VSTORE_GUIDE_ORDERS_SR',       'O pagamento foi devolvido ao cliente.');
define('LAN_VSTORE_GUIDE_ORDERS_SQ',       'Foi recebido um pedido de orçamento que aguarda resposta.');
define('LAN_VSTORE_GUIDE_ORDERS_SM',       'O pedido passou para a aplicação de faturação externa.');
define('LAN_VSTORE_GUIDE_ORDERS_SA',       'O cliente aceitou o orçamento.');
define('LAN_VSTORE_GUIDE_ORDERS_SF',       'O orçamento foi faturado na aplicação externa.');
define('LAN_VSTORE_GUIDE_ORDERS_EM_H',     'Modelos de email');
define('LAN_VSTORE_GUIDE_ORDERS_EM_T',     'Existe um modelo para cada mudança de estado e outro para os pedidos de orçamento. Use o botão de repor para recuperar o texto original.');
define('LAN_VSTORE_GUIDE_ORDERS_INV_H',    'Faturas');
define('LAN_VSTORE_GUIDE_ORDERS_INV_T',    'As faturas em PDF são criadas pelo próprio Vstore (TCPDF). Defina o prefixo, o número seguinte e o rodapé em Configurações de Faturas e verifique o resultado no separador de pré-visualização.');
define('LAN_VSTORE_GUIDE_ORDERS_STAT_H',   'Estatísticas');
define('LAN_VSTORE_GUIDE_ORDERS_STAT_T',   'A primeira página da área de administração mostra as caixas de vendas e o número de pedidos de orçamento novos.');
define('LAN_VSTORE_GUIDE_ORDERS_DASH_H',   'O que o cliente vê');
define('LAN_VSTORE_GUIDE_ORDERS_DASH_T',   'Na área de cliente o comprador encontra um resumo, um gráfico dos últimos seis meses, a lista de encomendas com distintivos de estado, o detalhe de cada encomenda, os downloads e os orçamentos abertos.');

// APARÊNCIA
define('LAN_VSTORE_GUIDE_LOOK_TAB',        'Aparência');
define('LAN_VSTORE_GUIDE_LOOK_TITLE',      'Adaptar a loja ao seu tema');
define('LAN_VSTORE_GUIDE_LOOK_INTRO',      'A loja foi concebida para temas Bootstrap 5 e usa as cores do tema ativo. Pode ajustá-la sem alterar o plugin.');
define('LAN_VSTORE_GUIDE_LOOK_CSS_H',      'Cores e formas');
define('LAN_VSTORE_GUIDE_LOOK_CSS_T',      'A folha de estilos <code>vstore.css</code> lê as cores do Bootstrap e expõe algumas variáveis. Substitua-as no CSS do seu tema:');
define('LAN_VSTORE_GUIDE_LOOK_TPL_H',      'Modelos');
define('LAN_VSTORE_GUIDE_LOOK_TPL_T',      'Cada parte da loja pode ser substituída a partir do seu tema. Copie apenas a parte que quer alterar para esta localização:');
define('LAN_VSTORE_GUIDE_LOOK_MENU_H',     'Menus');
define('LAN_VSTORE_GUIDE_LOOK_MENU1',      '<strong>Categorias do Vstore</strong>: as categorias de raiz como mosaicos com imagem e número de produtos.');
define('LAN_VSTORE_GUIDE_LOOK_MENU2',      '<strong>Produtos do Vstore</strong>: os últimos produtos de uma categoria ou de todas.');
define('LAN_VSTORE_GUIDE_LOOK_MENU3',      '<strong>Menu do carrinho</strong>: mostrado na navegação, com uma variante para pedidos de orçamento.');
define('LAN_VSTORE_GUIDE_LOOK_NOTE',       'Ative os menus no gestor de menus e atribua-os às áreas do seu layout.');

// RESOLUÇÃO DE PROBLEMAS
define('LAN_VSTORE_GUIDE_TROUBLE_TAB',     'Resolução de problemas');
define('LAN_VSTORE_GUIDE_TROUBLE_TITLE',   'Problemas frequentes');
define('LAN_VSTORE_GUIDE_TROUBLE_INTRO',   'Verifique estes pontos antes de pedir suporte.');
define('LAN_VSTORE_GUIDE_TROUBLE_Q1',      'Um erro fatal menciona uma classe ou um ficheiro de autoload em falta');
define('LAN_VSTORE_GUIDE_TROUBLE_A1',      'Execute <code>composer install --no-dev --optimize-autoloader</code> dentro da pasta do plugin. O diretório <code>vendor</code> não está incluído no repositório.');
define('LAN_VSTORE_GUIDE_TROUBLE_Q2',      'As faturas não são criadas em PDF');
define('LAN_VSTORE_GUIDE_TROUBLE_A2',      'Execute <code>composer install</code> em <code>e107_plugins/vstore</code> e ative as faturas PDF em Configurações de Faturas.');
define('LAN_VSTORE_GUIDE_TROUBLE_Q3',      'É aplicada uma taxa de imposto errada');
define('LAN_VSTORE_GUIDE_TROUBLE_A3',      'Verifique o país da sua empresa, a classe de imposto do produto e o país indicado no formulário de faturação.');
define('LAN_VSTORE_GUIDE_TROUBLE_Q4',      'Preciso de mais informação sobre o que se passa');
define('LAN_VSTORE_GUIDE_TROUBLE_A4',      'Retire o comentário da linha <code>e_DEBUG_VSTORE</code> em <code>e_header.php</code> para ativar o registo de depuração e volte a retirá-la em produção.');
define('LAN_VSTORE_GUIDE_TROUBLE_Q5',      'Os visitantes não veem as ligações da loja ou do carrinho');
define('LAN_VSTORE_GUIDE_TROUBLE_A5',      'Por omissão as ligações são criadas para membros. Altere a visibilidade das ligações do Vstore nas ligações do site da área de administração.');
define('LAN_VSTORE_GUIDE_TROUBLE_Q6',      'A loja parece diferente do meu tema ou os estilos não são aplicados');
define('LAN_VSTORE_GUIDE_TROUBLE_A6',      'O design precisa de um tema Bootstrap 5. Após uma atualização limpe a cache do navegador e a do e107 para que os novos <code>vstore.css</code> e <code>vstore.js</code> sejam carregados.');

// CREDITS
define('LAN_VSTORE_GUIDE_CREDITS_TAB',              'Créditos');
define('LAN_VSTORE_GUIDE_CREDITS_TITLE',            'Créditos e agradecimentos');
define('LAN_VSTORE_GUIDE_CREDITS_INTRO',            'O Vstore não existiria sem o trabalho dos seus autores originais, da comunidade e107 e das bibliotecas de código aberto listadas a seguir.');
define('LAN_VSTORE_GUIDE_CREDITS_AUTHOR_H',         'Autores');
define('LAN_VSTORE_GUIDE_CREDITS_AUTHOR_T',         'O Vstore foi criado por CaMerOn e Achim Ennenbach (e107 Inc). Esta versão é mantida e ampliada por Martin Costa (Kanonimpresor). Consulte o separador <strong>Sobre</strong> para os dados de contacto e as ligações de suporte.');
define('LAN_VSTORE_GUIDE_CREDITS_TECH_H',           'Tecnologias utilizadas');
define('LAN_VSTORE_GUIDE_CREDITS_TECH_E107',        'Framework da área de administração, sistema de plugins, parser, shortcodes e modelos.');
define('LAN_VSTORE_GUIDE_CREDITS_TECH_OMNIPAY',     'Biblioteca de abstração de pagamentos por trás dos gateways PayPal e Mollie.');
define('LAN_VSTORE_GUIDE_CREDITS_TECH_TCPDF',       'Cria as faturas em PDF sem serviços externos.');
define('LAN_VSTORE_GUIDE_CREDITS_TECH_VAT',         'Verifica os NIF-IVA da UE e fornece as taxas de IVA por país.');
define('LAN_VSTORE_GUIDE_CREDITS_TECH_BS',          'A loja usa Bootstrap 5; esta área de administração usa o Bootstrap 3 do tema de administração do e107.');
define('LAN_VSTORE_GUIDE_CREDITS_TECH_FA',          'Conjunto de ícones usado na área de administração e na loja.');
define('LAN_VSTORE_GUIDE_CREDITS_THANKS_H',         'Agradecimentos especiais');
define('LAN_VSTORE_GUIDE_CREDITS_THANKS_ORIG',      'CaMerOn, Achim Ennenbach e a equipa da e107 Inc, pelo plugin Vstore original em que esta versão se baseia.');
define('LAN_VSTORE_GUIDE_CREDITS_THANKS_E107',      'A comunidade e107, pelos testes, ideias e comentários.');
define('LAN_VSTORE_GUIDE_CREDITS_THANKS_TRANS',     'Todas as pessoas que ajudam a manter atualizadas as traduções em inglês, espanhol e português.');
define('LAN_VSTORE_GUIDE_CREDITS_LICENSE',          'O Vstore é software livre publicado sob a Licença Pública Geral GNU v3.0. Pode usá-lo, estudá-lo, partilhá-lo e melhorá-lo nos termos dessa licença.');
define('LAN_VSTORE_GUIDE_CREDITS_INVOLVED',         'Encontrou um erro ou tem uma ideia? Abra um problema no repositório do projeto a partir do botão Suporte do separador Sobre.');

// APPEARANCE: showcase menu
define('LAN_VSTORE_GUIDE_LOOK_MENU4',               '<strong>Montra do Vstore</strong>: cartões de produto como os das páginas de produtos, para qualquer área de menu, com pílulas de categorias opcionais.');
define('LAN_VSTORE_GUIDE_LOOK_SHOW_H',              'Opções do menu montra');
define('LAN_VSTORE_GUIDE_LOOK_SHOW_T',              'Adicione <em>vstore_showcase_menu</em> a uma área de menu no Gestor de menus e carregue no respetivo botão de configuração.');
define('LAN_VSTORE_GUIDE_LOOK_SHOW_O1_N',           'Categoria');
define('LAN_VSTORE_GUIDE_LOOK_SHOW_O1_D',           '<em>Todas as categorias</em> mostra produtos de qualquer categoria (aleatórios por omissão); escolha uma categoria para mostrar apenas os seus produtos.');
define('LAN_VSTORE_GUIDE_LOOK_SHOW_O2_N',           'Quantidade de produtos');
define('LAN_VSTORE_GUIDE_LOOK_SHOW_O2_D',           'De 1 a 24. Com pílulas, cada pílula mostra esta quantidade de produtos.');
define('LAN_VSTORE_GUIDE_LOOK_SHOW_O3_N',           'Produtos por linha');
define('LAN_VSTORE_GUIDE_LOOK_SHOW_O3_D',           'De uma a quatro colunas, ou automático. Escolha o que se ajusta à área de menu do seu tema: a grelha usa menos colunas por si só se a área for estreita.');
define('LAN_VSTORE_GUIDE_LOOK_SHOW_O4_N',           'Ordem');
define('LAN_VSTORE_GUIDE_LOOK_SHOW_O4_D',           'Aleatório, mais recentes, nome, preço ou a ordem manual do catálogo.');
define('LAN_VSTORE_GUIDE_LOOK_SHOW_O5_N',           'Pílulas de categorias');
define('LAN_VSTORE_GUIDE_LOOK_SHOW_O5_D',           'Pílulas por cima dos produtos que os filtram no próprio menu, pílulas que abrem a página da categoria, ou nenhuma.');
