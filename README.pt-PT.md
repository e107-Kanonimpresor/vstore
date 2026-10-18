# Plugin Vstore para e107

#### (Escolha o seu idioma / Choose your language / Elija su idioma)

[![Language-English](https://img.shields.io/badge/Language-English-blue)](README.md) 
[![Language-Português](https://img.shields.io/badge/Language-Português-green)](README.pt-PT.md) 
[![Language-Español](https://img.shields.io/badge/Language-Español-red)](README.es-ES.md) 

---

[![Version](https://img.shields.io/badge/version-2.1.0-blue)](https://github.com/e107-Kanonimpresor/vstore/releases/tag/v2.1.0)
![e107](https://img.shields.io/badge/e107-2.3.2%2B-green)
[![PHP](https://img.shields.io/badge/PHP-7.4%20a%208.4-purple.svg)](https://php.net)
![Bootstrap](https://img.shields.io/badge/Bootstrap-3%20%7C%205-purple)
![License](https://img.shields.io/badge/license-GPL--3.0-orange)

**Autor:** [Kanonimpresor](https://marketingdeperformance.online) — Marketing de Performance  
**Licença:** GPL-3.0  
**Baseado em:** [e107inc/vstore](https://github.com/e107inc/vstore) (CaMerOn, Achim Ennenbach, e107 Inc). Modificado por Kanonimpresor, 2025-2026.

---

## 📋 Descrição

O **Vstore** é um plugin de comércio eletrónico para o CMS Bootstrap [e107 v2.x](https://e107.org). Permite gerir uma loja online diretamente a partir do seu site e107: produtos físicos, downloads digitais ou serviços, com vários métodos de pagamento, IVA da UE, cupões, controlo de stock, faturas em PDF e e-mails aos clientes, em português, inglês e espanhol.

---

## ✨ Principais Funcionalidades

### 🛒 Carrinho de Compras e Checkout
- **Carrinho** com adicionar ao carrinho por AJAX e campo de quantidade na página do produto
- **Compra como convidado**: o cliente pode comprar como convidado, iniciar sessão ou registar-se
- **Checkout** com moradas de faturação e envio, campos adicionais opcionais e telefone
- **Painel do cliente**: histórico de encomendas, detalhe, downloads e faturas
- **Downloads digitais**: os ficheiros ficam disponíveis quando o pagamento é recebido
- **Variações de produto**: até dois grupos por produto (tamanho, cor...) com alteração de preço e stock próprio

### 💳 Processamento de Pagamentos
- **Plataformas de pagamento** (através do [Omnipay](https://omnipay.thephpleague.com/)):
  - **PayPal** (padrão e REST)
  - **Mollie**: cartões, PayPal, **MB WAY**, **Multibanco**, iDEAL, Bancontact, transferência, débito direto, cartões-presente e mais. Só são oferecidos os métodos ativos no seu perfil Mollie
  - **Transferência bancária** (manual): a encomenda aguarda até a marcar como paga
- **Páginas de pagamento alojadas**: os dados do cartão nunca passam pelo seu servidor
- **Webhook da Mollie**: as encomendas são criadas antes de o cliente pagar, pelo que um pagamento pendente (MB WAY, Multibanco) ou feito sem voltar à loja nunca se perde
- **Reembolsos** a partir da encomenda nas plataformas que os permitem (Mollie, PayPal)
- **Estados da encomenda**: Nova, Em processamento, Em espera, Concluída, Cancelada, Reembolsada

> Os métodos Mollie que só aceitam euros (MB WAY, Multibanco, iDEAL, Bancontact, transferência, débito direto e cartões-presente) ficam ocultos no checkout se a moeda da loja não for EUR.

### 💰 Preços e Descontos
- **Cupões**:
  - Descontos percentuais ou de valor fixo
  - Datas de início e fim
  - Incluir/excluir categorias e produtos
  - Limites de utilização (total, por utilizador e por artigo)
- **Cálculo de impostos**:
  - Preços líquidos, com o IVA do país do cliente acrescentado
  - IVA da UE com validação **VIES** do NIF/IVA (e dígito de controlo do NIF português)
  - **Autoliquidação** (0%) para empresas de outro país da UE com NIF/IVA válido
  - Opção de cobrar o IVA do país da loja a consumidores da UE (vendedores abaixo do limiar de 10 000 EUR)
  - Classes de imposto: normal, reduzida, super-reduzida, parking e mais

### 📦 Gestão de Produtos
- **Produtos ilimitados** e **categorias** aninhadas, restringíveis a classes de utilizador
- **Controlo de stock** por produto e por variação (`-1` para ilimitado)
- **Galerias de imagens** com zoom, vídeos e ficheiros para download
- **Produtos relacionados**
- **Loja em Bootstrap 5**: mosaicos de categoria, cartões de produto, barra de categorias sob o breadcrumb, menus e carrinho redesenhados, adaptável a telemóvel e personalizável com variáveis CSS (2.1.0)
- **Menu montra** (2.1.0): `vstore_showcase_menu` mostra cartões de produto em qualquer área de menu, com opções no Gestor de menus: todas as categorias (aleatório) ou uma categoria, quantidade de produtos, de uma a quatro colunas, ordem e pílulas de categorias que filtram no próprio menu
- **Modo orçamento** (2.1.0): um produto com o preço vazio mostra um botão "Orçamento"; os produtos de compra e de orçamento partilham o carrinho (cada tipo é tratado em separado e o orçamento só lista produtos e quantidades) e o pedido fica guardado como orçamento (Orçamento solicitado → Migrado para a app → Aprovado → Faturado) e é enviado por e-mail ao cliente e à loja
- **URLs amigáveis** para SEO
- **Integração com a pesquisa** do e107

### 🚚 Envio
- **Métodos de envio**: soma de todos os artigos, soma de artigos únicos ou escalonado pelo preço ou peso da encomenda
- **Custos de envio por produto**
- **Moradas de faturação e envio separadas**; notas do cliente e notas internas do administrador

### 📊 Estatísticas
- **Painel** com receitas, encomendas do mês e um gráfico por período, também no painel de controlo do e107

### 🎨 Personalização
- **Modelos** da loja, carrinho, área do cliente, e-mails e fatura; o tema pode substituí-los em `<tema>/templates/vstore/`
- **Shortcodes** para layouts próprios
- **CSS personalizado** a partir da administração
- **Design responsivo** com marcação Bootstrap (temas Bootstrap 3 e 5)
- **Totalmente traduzível**: administração, loja, e-mails, faturas e mensagens vêm de ficheiros de idioma (português, inglês e espanhol; os restantes usam inglês)

### 🔧 Administração
- **Área de administração** de vendas, produtos, categorias, variações, cupões, clientes, plataformas, faturas e e-mails
- Separadores **Guia do Utilizador** e **Sobre**, e uma barra lateral de ajuda contextual
- **Modelos de e-mail** por estado da encomenda (confirmação, concluída, cancelada, reembolsada), adaptados a telemóvel, com botão de repor
- **Faturas em PDF** criadas com o TCPDF (instalado pelo Composer): prefixo, número seguinte, blocos do rodapé, pré-visualização; anexadas ao e-mail e disponíveis para o cliente
- **Permissões** para as ações de administração

---

## 🆕 Novidades da v2.1.0

- **Modo orçamento**: os produtos sem preço são pedidos como orçamento, no mesmo carrinho que os produtos à venda.
- **Loja redesenhada** para Bootstrap 5: mosaicos de categoria, cartões de produto, barra de categorias, menus, carrinho, checkout com resumo da encomenda e painel do cliente com valores e gráfico.
- **Efeito ao adicionar ao carrinho**: o produto voa para o ícone do carrinho, o contador pulsa e uma mensagem oferece o carrinho.
- **Administração**: nova caixa de *orçamentos* nas estatísticas e filtro *Orçamentos* nas encomendas.

## Novidades anteriores: v2.0.0

- Administração e loja **totalmente traduzíveis** (português, inglês e espanhol).
- **Mollie MB WAY e Multibanco**, telefone enviado à Mollie, **webhook** da Mollie e botão *Verificar pagamento na Mollie*.
- **IVA da UE reescrito**: preços líquidos, VIES, autoliquidação, IVA de origem opcional.
- **Novo design de e-mails** e **faturas PDF integradas** (TCPDF 6.11 via Composer; já não é necessário o plugin *pdf* do e107).
- **Camada de lógica de negócio** em `src/` (espaço de nomes `Vstore\`) com 242 testes automáticos (`php tests/run.php`). O `vstore.class.php` mantém os métodos públicos, pelo que temas e plugins continuam a funcionar.
- **Revisão de segurança**: escape de saídas e de SQL, carrinho limitado ao visitante, credenciais ocultas nos registos, `composer audit` sem avisos.
- **Removidos** Stripe, Braintree, CoinPayments e StarkPay (não tinham um checkout funcional). Os cartões são cobertos pela Mollie.
- Novo **manual do utilizador** ([docs/user-manual.html](docs/user-manual.html), em espanhol) e **notas da versão** ([docs/RELEASE_NOTES_v2.0.0.md](docs/RELEASE_NOTES_v2.0.0.md)).

### Atualizar a partir da 1.x
1. Substitua a pasta do plugin (ou faça `git pull`) e execute `composer install --no-dev --optimize-autoloader`.
2. Em *Vstore → Modelos de E-mail* e *Configurações de Faturas*, clique em **Repor modelo** se guardou modelos próprios, para obter o novo design e a fatura corrigida.
3. Desinstale o plugin *pdf* do e107 se não o usar para mais nada.
4. Para MB WAY, Multibanco e os restantes métodos Mollie só em euros, defina a moeda da loja como **EUR**.

---

## 🚀 Instalação

### Requisitos
- **e107 CMS**: v2.3.2 ou superior ([Descarregar](https://github.com/e107inc/e107))
- **PHP**: 7.4 a 8.4
- **Extensões PHP**: SOAP (validação VIES), cURL (plataformas de pagamento), JSON, mbstring, GD (imagens e logótipo da fatura)
- **Base de dados**: MySQL 5.7+ / MariaDB 10.2+
- **Composer** (apenas se instalar a partir do repositório de código-fonte)

### Passos de instalação

1. **Obtenha o plugin**
   - **Pacote da versão (recomendado)**: descarregue `vstore-2.1.0.zip` na [página de Releases](https://github.com/e107-Kanonimpresor/vstore/releases) e extraia-o para `e107_plugins/` (já inclui `vendor/`).
   - **A partir do código-fonte**:
     ```bash
     cd e107_plugins
     git clone https://github.com/e107-Kanonimpresor/vstore.git
     cd vstore
     composer install --no-dev --optimize-autoloader
     ```

   > **⚠️ IMPORTANTE**: o repositório de código-fonte não inclui a pasta `vendor/`. Sem `composer install` verá `Failed opening required 'vendor/autoload.php'`.

2. **Instale no e107**: *Admin → Plugins → Gerir plugins → Vstore → Instalar*.

3. **Configure**: *Admin → Vstore → Preferências*: moeda, formato do preço, unidade de peso, morada da loja, envios, impostos, e-mails e checkout. O separador **Guia do Utilizador** acompanha-o passo a passo.

4. **Plataformas de pagamento**: *Admin → Vstore → Plataformas de Pagamento*. Introduza as credenciais de pelo menos uma e ative-a. Teste-a antes em modo sandbox/teste.

5. **Crie categorias e produtos**: *Admin → Vstore → Categorias / Produtos*.

6. **Teste o checkout** com uma encomenda de teste e acompanhe-a em *Vendas*.

---

## 📖 Documentação

- [Manual do utilizador](docs/user-manual.html) (em espanhol)
- [Notas da versão 2.0.0](docs/RELEASE_NOTES_v2.0.0.md) e [CHANGELOG](CHANGELOG.md)
- [Faturas](wiki/invoice.md)
- [Guia de testes](docs/TESTING_GUIDE.md)
- [Notas de desenvolvimento](DEV_NOTES.md) e [plano de atualização](docs/PLAN_ACTUALIZACION_V2.md)

---

## 🔧 Configuração

### Configuração de impostos (exemplo IVA UE)

```
Admin → Vstore → Preferências → Impostos
País do negócio:                PT (Portugal)
Cálculo de impostos:            Ativado
Verificar NIF/IVA (VIES):       Ativado
Classes de imposto:             valores decimais, ex.: 0.23 para 23%

Os preços são líquidos; é acrescentado o IVA do país de destino:
- Cliente de PT:                                  23% (a sua taxa local)
- Consumidor de ES:                               taxa espanhola
- Empresa de DE com NIF/IVA válido no VIES:       0% (autoliquidação)
- Cliente dos EUA:                                0% (fora da UE)
```

### Plataformas de pagamento

```
PayPal        → modo (Sandbox / Live) e credenciais de API
Mollie        → chave de API (test_... / live_...) e métodos de pagamento a oferecer
Transferência → os dados bancários que o cliente verá
```

---

## 🛠️ Personalização

Os modelos estão em `templates/`. Para alterar um sem mexer no plugin, copie-o para o seu tema como `<tema>/templates/vstore/<nome>_template.php`:

```php
// <tema>/templates/vstore/vstore_template.php
$VSTORE_TEMPLATE['list']['item'] = '
<div class="product-card">
    {ITEM_PIC: w=300&h=300&crop=1}
    <h3>{ITEM_NAME}</h3>
    <p class="price">{ITEM_PRICE}</p>
    {ITEM_QTY}
    {ITEM_ADDTOCART}
</div>
';
```

Os e-mails usam `vstore_email_layout_template.php` (documento) e `vstore_email_template.php` (corpos); a fatura usa `vstore_invoice_template.php`.

---

## 🤝 Contribuir

### Reportar erros
- Consulte as [issues existentes](https://github.com/e107-Kanonimpresor/vstore/issues)
- Abra uma nova com: descrição clara, passos para reproduzir, versões do e107, PHP e do plugin, e qualquer mensagem de erro ou captura

### Pedidos de funcionalidades
- Abra uma issue com a etiqueta `enhancement` e descreva o caso de utilização

### Pull Requests
1. Faça um fork do repositório
2. Crie um ramo: `git checkout -b feature/amazing-feature`
3. Execute os testes: `php tests/run.php`
4. Faça commit e envie o seu ramo, e abra um Pull Request

### Testes
- Teste em várias versões do PHP (7.4 a 8.4)
- Teste as plataformas de pagamento em modo sandbox
- Reporte problemas de compatibilidade

### Localização
- Os ficheiros de idioma estão em `languages/<Idioma>/` (`<Idioma>_<área>.php`, formato array, inglês como alternativa)
- As constantes usam o prefixo `LAN_VSTORE_*`

---

## 📝 Histórico de Alterações

Consulte o [CHANGELOG.md](CHANGELOG.md) para a lista completa de alterações.

**v2.0.0 (2026-10-17)**: traduções, Mollie MB WAY/Multibanco e webhook, IVA da UE reescrito, novos e-mails, faturas PDF integradas, camada de negócio com testes, revisão de segurança. Veja as [notas da versão](docs/RELEASE_NOTES_v2.0.0.md).

---

## 🐛 Problemas Conhecidos

- Cancelar uma encomenda manualmente na administração não devolve o stock (os pagamentos falhados ou expirados devolvem).
- As credenciais guardadas das plataformas removidas continuam nas preferências da base de dados; são ignoradas.
- O manual do utilizador só está disponível em espanhol.
- Em localhost, a Mollie não consegue enviar o webhook: use o botão *Verificar pagamento na Mollie*.
- O e-mail em localhost precisa de um servidor SMTP (por exemplo smtp4dev ou Mailtrap).
- Limpe a cache do e107 depois de alterar modelos.
- As faturas PDF precisam de `vendor/tecnickcom/tcpdf` (execute `composer install`).

---

## 📄 Licença

Este projeto é distribuído sob a **Licença Pública Geral GNU v3.0** - consulte o ficheiro [LICENSE](LICENSE).

- ✅ Livre para uso comercial e não comercial
- ✅ Pode modificar e distribuir o código
- ✅ As modificações têm de ser publicadas sob a mesma licença GPL-3.0

---

## 🙏 Créditos e Agradecimentos

### Mantenedores
- Desenvolvedores originais: CaMerOn, Achim Ennenbach e a [comunidade e107](https://e107.org)
- Mantenedor atual: [Kanonimpresor](https://marketingdeperformance.online) — Marketing de Performance ([GitHub](https://github.com/e107-Kanonimpresor))

### Dependências
- [e107 CMS](https://github.com/e107inc/e107) - Sistema de gestão de conteúdos
- [Omnipay](https://omnipay.thephpleague.com/) com os seus controladores PayPal e Mollie - abstração de pagamentos
- [ibericode/vat](https://github.com/ibericode/vat) - Taxas de IVA da UE e validação VIES
- [TCPDF](https://tcpdf.org/) - Faturas em PDF

### Agradecimentos Especiais
- À comunidade e107 pelos testes e comentários
- Aos fornecedores de pagamento pelos ambientes sandbox
- A quem reporta erros e envia pull requests

---

## 📞 Suporte e Comunidade

- **Documentação**: [manual do utilizador](docs/user-manual.html) e o separador *Guia do Utilizador* da administração
- **Issues no GitHub**: [reportar erros](https://github.com/e107-Kanonimpresor/vstore/issues)
- **Comunidade e107**: [Q&A - Plugins](https://github.com/e107inc/e107/discussions/categories/q-a-plugins) e os [fóruns do e107.org](https://e107.org/forum)

---

## 🗺️ Roteiro

### ✅ Entregue na 2.0.0
- [x] Compatibilidade total com PHP 8.x (7.4 a 8.4)
- [x] Sistema de cupões melhorado
- [x] Localização completa (português, inglês e espanhol)
- [x] Mollie MB WAY e Multibanco, faturas PDF integradas, IVA da UE reescrito, novo design de e-mails

### 🚧 Em Desenvolvimento
- [x] Modo **orçamento** de produto (2.1.0): se deixar o preço vazio, o produto mostra um botão "Orçamento / Quote / Presupuesto" em vez de "Adicionar ao carrinho"; os orçamentos ficam guardados na administração para passar depois a uma app de faturação certificada
- [ ] Mais melhorias nos e-mails
- [ ] Mais idiomas (traduções da comunidade)
- [ ] Integração com aplicação móvel

### Funcionalidades Planeadas
- [ ] Mais plataformas de pagamento (Authorize.net, Square, etc.)
- [ ] Painel de análise avançado com gráficos
- [ ] Avaliações e classificações de produtos
- [ ] Lista de desejos
- [ ] Recuperação de carrinhos abandonados
- [ ] Suporte de marketplace multi-vendedor
- [ ] Pagamentos por subscrição/recorrentes
- [ ] Cartões-presente e vales

---

## 📊 Em resumo

- **Idiomas**: português, inglês e espanhol
- **Métodos de pagamento**: PayPal, Mollie (cartões e métodos locais), transferência bancária
- **Testes**: 242 verificações automáticas (`php tests/run.php`), PHP 7.4, 8.2 e 8.4

---

<div align="center">

**Feito com ❤️ para a comunidade e107 CMS**

[Reportar erro](https://github.com/e107-Kanonimpresor/vstore/issues) · [Pedir funcionalidade](https://github.com/e107-Kanonimpresor/vstore/issues) · [Releases](https://github.com/e107-Kanonimpresor/vstore/releases)

Copyright (c) 2026 Kanonimpresor / Marketing de Performance  
Plugin desenvolvido para e107 CMS — https://marketingdeperformance.online

</div>
