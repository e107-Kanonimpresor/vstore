# Plugin Vstore para e107

#### (Escolha o seu idioma / Choose your language / Elija su idioma)

[![Language-English](https://img.shields.io/badge/Language-English-blue)](README.md) 
[![Language-Português](https://img.shields.io/badge/Language-Português-green)](README.pt-PT.md) 
[![Language-Español](https://img.shields.io/badge/Language-Español-red)](README.es-ES.md) 

---

[![e107](https://img.shields.io/badge/e107-v2.3.0+-blue.svg)](https://e107.org)
[![PHP](https://img.shields.io/badge/PHP-7.4%2B%20|%208.x-purple.svg)](https://php.net)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-3.x%20|%204.x%20|%205.x-7952B3.svg)](https://getbootstrap.com)
[![License](https://img.shields.io/badge/License-GPL--3.0-green.svg)](LICENSE)
[![Join the chat at https://gitter.im/e107inc/vstore](https://badges.gitter.im/e107inc/vstore.svg)](https://gitter.im/e107inc/vstore?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

---

## 📋 Descrição

**Vstore** é uma solução de comércio eletrónico completa para [e107 v2.x](https://e107.org) Bootstrap CMS. Desenvolvido com flexibilidade e facilidade de uso, fornece tudo o que precisa para gerir uma loja online profissional diretamente do seu site e107.

Quer venda produtos físicos, downloads digitais ou serviços, o Vstore integra-se perfeitamente com o seu site e107, oferecendo recursos poderosos como múltiplas plataformas de pagamento, cálculo avançado de impostos, gestão de cupões e rastreamento de inventário.

---

## ✨ Recursos Principais

### 🛒 Carrinho de Compras e Checkout
- **Carrinho Dinâmico**: Atualizações em tempo real com funcionalidade AJAX
- **Compra como Convidado**: Permite compras sem registo de utilizador
- **Checkout Multi-passo**: Fluxo de compra limpo e intuitivo
- **Histórico de Pedidos**: Painel do cliente com rastreamento de pedidos
- **Downloads Digitais**: Suporte para produtos descarregáveis
- **Variações de Produtos**: Tamanho, cor e atributos personalizados

### 💳 Processamento de Pagamentos
- **Múltiplas Plataformas de Pagamento**:
  - PayPal (Express Checkout & Standard)
  - Stripe (Cartões de Crédito/Débito)
  - Mollie (iDEAL, Bancontact e mais de 20 métodos)
  - Braintree (PayPal e Cartões de Crédito)
  - CoinPayments (Criptomoedas)
  - Transferência Bancária (Manual)
- **Transações Seguras**: Processamento compatível com PCI
- **Rastreamento de Estado**: Novo, Em Processamento, Completo, Reembolsado, etc.

### 💰 Preços e Descontos
- **Sistema de Cupões**:
  - Descontos por percentagem ou valor fixo
  - Restrições por intervalo de datas
  - Inclusão/exclusão de categorias/produtos
  - Limites de uso (global e por utilizador)
  - Requisitos de pedido mínimo
- **Cálculo de Impostos**:
  - Suporte para IVA da UE com validação VIES
  - Múltiplas taxas de impostos (normal, reduzida, super reduzida)
  - Mecanismo de inversão do sujeito passivo B2B
  - Classes de impostos específicas por país
  - Cálculo automático baseado na localização

### 📦 Gestão de Produtos
- **Produtos Ilimitados**: Sem restrições no tamanho do catálogo
- **Categorias e Subcategorias**: Hierarquia organizada de produtos
- **Variações de Produtos**: Gestão de tamanho, cor e atributos personalizados
- **Rastreamento de Inventário**: Gestão de stock com alertas de stock baixo
- **Galerias de Imagens**: Múltiplas imagens por produto com zoom
- **URLs Amigáveis para SEO**: URLs limpas para melhor posicionamento
- **Produtos Relacionados**: Recomendações de venda cruzada

### 🚚 Envio e Cumprimento
- **Tarifas de Envio Flexíveis**:
  - Taxa fixa
  - Cálculo baseado em peso
  - Opções de envio gratuito
  - Custos de envio por produto
- **Gestão de Moradas**: Moradas de faturação e envio separadas
- **Notas de Pedido**: Notas do cliente e notas internas do administrador

### 📊 Análise e Relatórios
- **Estatísticas de Vendas**: Rastreamento de receitas e tendências
- **Relatórios de Pedidos**: Informação detalhada de pedidos
- **Desempenho de Produtos**: Mais vendidos e relatórios de inventário
- **Informação de Clientes**: Histórico de compras e comportamento

### 🎨 Personalização
- **Sistema de Templates**: Templates HTML completamente personalizáveis
- **Shortcodes**: Ampla biblioteca de shortcodes para layouts personalizados
- **CSS Personalizado**: Adicione os seus próprios estilos
- **Design Responsivo**: Compatível com dispositivos móveis desde o início
- **Suporte Multi-idioma**: Pronto para tradução com ficheiros LAN

### 🔧 Recursos de Administração
- **Painel Intuitivo**: Gestão de pedidos, produtos e clientes
- **Ações em Massa**: Editar múltiplos elementos simultaneamente
- **Templates de Email**: Emails de confirmação personalizáveis
- **Geração de Faturas**: Faturas PDF automáticas (requer plugin PDF)
- **Permissões de Utilizador**: Controlo de acesso baseado em funções
- **Integração de Pesquisa**: Produtos pesquisáveis via pesquisa e107

---

## 🚀 Instalação

### Requisitos
- **e107 CMS**: v2.3.0 ou superior ([Download](https://github.com/e107inc/e107))
- **PHP**: 7.4 ou superior (compatível com PHP 8.x)
- **Extensões PHP**:
  - SOAP (para validação de IVA)
  - cURL (para plataformas de pagamento)
  - JSON
  - GD ou Imagick (para processamento de imagens)
- **Base de Dados**: MySQL 5.7+ / MariaDB 10.2+
- **Opcional**: [Plugin PDF](https://github.com/e107inc/pdf) para geração de faturas

### Passos de Instalação

1. **Descarregar o Plugin**:
   ```bash
   cd e107_plugins
   git clone https://github.com/Kanonimpresor/vstore.git
   ```

2. **Instalar Dependências** ⚠️ **OBRIGATÓRIO**:
   ```bash
   cd vstore
   composer install --no-dev
   ```
   
   > **⚠️ IMPORTANTE**: O diretório `vendor/` NÃO está incluído no repositório.  
   > **DEVE** executar `composer install` depois de clonar para descarregar todas as dependências.  
   > Sem este passo, o plugin NÃO funcionará e verá erros como:  
   > `Fatal error: Failed opening required 'vendor/autoload.php'`

3. **Instalar via Admin do e107**:
   - Navegar para: **Admin → Plugins → Gerir Plugins**
   - Encontrar "Vstore" na lista
   - Clicar em **Instalar**

4. **Configurar Definições**:
   - Ir para: **Admin → Vstore → Settings**
   - Configurar:
     - Informação da loja (nome, morada, NIF)
     - Configuração de impostos (ativar IVA, taxas)
     - Plataformas de pagamento (credenciais API)
     - Opções de envio
     - Templates de email

5. **Criar Produtos**:
   - Navegar para: **Admin → Vstore → Produtos**
   - Adicionar o seu primeiro produto com imagens e preços

6. **Testar Checkout**:
   - Adicionar um produto ao carrinho
   - Completar um pedido de teste (usar modo sandbox para plataformas)

---

## 📖 Documentação

### Guias de Início Rápido
- [Criar o Seu Primeiro Produto](wiki/creating-products.md)
- [Configurar Plataformas de Pagamento](wiki/payment-gateways.md)
- [Configurar Impostos](wiki/tax-configuration.md)
- [Gerir Cupões](wiki/coupons.md)
- [Personalizar Templates](wiki/templates.md)

### Tópicos Avançados
- [Geração de Faturas](wiki/invoice.md)
- [Shortcodes Personalizados](wiki/shortcodes.md)
- [Esquema de Base de Dados](wiki/database.md)
- [Integração API](wiki/api.md)
- [Melhores Práticas de Segurança](wiki/security.md)

### Recursos para Programadores
- [Arquitetura do Plugin](wiki/architecture.md)
- [Estender o Vstore](wiki/extending.md)
- [Desenvolvimento de Plataformas de Pagamento](wiki/gateway-development.md)
- [Padrões de Código](wiki/coding-standards.md)

---

## 🔧 Configuração

### Configuração de Impostos (Exemplo IVA UE)

```php
// Admin → Vstore → Settings → Tax
País do Negócio: PT (Portugal)
Cálculo de Impostos: Ativado
Verificar NIF-IVA: Ativado

Classes de Impostos:
- Normal: 23% (Portugal)
- Reduzida: 13%
- Intermédia: 6%

// Tratamento automático de IVA UE:
// - Cliente PT (B2C): 23% IVA
// - Cliente ES (B2C): Taxa de IVA espanhola
// - Cliente DE com NIF válido (B2B): 0% (inversão sujeito passivo)
// - Cliente US: 0% (fora da UE)
```

### Configuração de Plataformas de Pagamento

#### PayPal
```php
Modo: Sandbox / Live
Client ID: SEU_PAYPAL_CLIENT_ID
Client Secret: SEU_PAYPAL_SECRET
```

#### Stripe
```php
Modo: Test / Live
Publishable Key: pk_test_...
Secret Key: sk_test_...
```

#### Mollie
```php
API Key: test_... / live_...
Métodos Habilitados: iDEAL, Cartão de Crédito, Bancontact
```

---

## 🤝 Contribuir

Damos as boas-vindas a contribuições da comunidade! Aqui pode ajudar:

### Reportar Erros
- Verificar [issues existentes](https://github.com/Kanonimpresor/vstore/issues)
- Criar um novo issue com:
  - Descrição clara do problema
  - Passos para reproduzir
  - Versão do e107, PHP e plugin
  - Mensagens de erro ou capturas de ecrã

### Solicitações de Recursos
- Abrir um issue com a etiqueta `enhancement`
- Descrever o recurso e caso de uso
- Explicar por que beneficiaria outros utilizadores

### Pull Requests
1. Fork do repositório
2. Criar branch de recurso: `git checkout -b feature/recurso-incrivel`
3. Commit de alterações: `git commit -m 'Adicionar recurso incrível'`
4. Push para o branch: `git push origin feature/recurso-incrivel`
5. Abrir um Pull Request

---

## 📝 Histórico de Alterações

Ver [CHANGELOG.md](CHANGELOG.md) para a lista detalhada de alterações, correções e novos recursos.

**Últimas Atualizações (Novembro 2025)**:
- ✅ Compatibilidade corrigida com ibericode/vat v2.x
- ✅ Resolvido esvaziamento do carrinho ao aplicar cupões
- ✅ Corrigida validação de dados de envio no checkout
- ✅ Melhorias de compatibilidade com PHP 8.x
- ✅ Melhorado tratamento de erros e validação

---

## 📄 Licença

Este projeto está licenciado sob **GNU General Public License v3.0** - ver o ficheiro [LICENSE](LICENSE) para detalhes.

---

## 🙏 Créditos e Agradecimentos

### Mantenedores
- Programador Original: [Comunidade e107](https://e107.org)
- Mantenedor Atual: [Kanonimpresor](https://github.com/Kanonimpresor)

### Dependências
- [e107 CMS](https://github.com/e107inc/e107) - Sistema de Gestão de Conteúdos
- [Omnipay](https://omnipay.thephpleague.com/) - Abstração de processamento de pagamentos
- [ibericode/vat](https://github.com/ibericode/vat) - Validação de IVA UE
- [TCPDF](https://tcpdf.org/) - Geração de PDF

---

## 📞 Suporte e Comunidade

### Obter Ajuda
- **Documentação**: Consultar a [Wiki](wiki/) primeiro
- **Chat da Comunidade**: [Canal Gitter](https://gitter.im/e107inc/vstore)
- **Fóruns e107**: [Fóruns e107.org](https://e107.org/forum)
- **GitHub Issues**: [Reportar erros](https://github.com/Kanonimpresor/vstore/issues)

---

<div align="center">

**Feito com ❤️ para a Comunidade e107 CMS**

[Reportar Erro](https://github.com/Kanonimpresor/vstore/issues) · [Solicitar Recurso](https://github.com/Kanonimpresor/vstore/issues) · [Documentação](wiki/)

</div>
