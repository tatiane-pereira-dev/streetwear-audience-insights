# Streetwear Audience Insights

Projeto desenvolvido a partir da **AXB Streetwear Clothing**, uma marca pessoal de streetwear já existente.

A solução nasceu de uma pergunta de negócio:

**Quem é realmente o público da marca e quais estilos apresentam maior interesse e intenção de compra?**

<img width="1892" height="870" alt="Página inicial do Drop Signal da AXB Streetwear Clothing" src="https://github.com/user-attachments/assets/dfd9463f-ba67-4d8f-8758-5807a3f23210" />

*Página de entrada do Drop Signal: a jornada permite acessar diretamente o e-commerce ou iniciar a descoberta de perfil.*

## 🎯 O problema

Para uma marca que trabalha com drops, decidir quais estilos e produtos desenvolver sem conhecer profundamente a audiência aumenta o risco de criar peças pouco alinhadas ao público.

Curtidas, seguidores e alcance mostram interesse, mas não necessariamente revelam:

- qual estilo representa cada pessoa;
- quais perfis predominam na audiência;
- quais grupos demonstram maior intenção de compra;
- quais preferências podem orientar futuros drops.

## 💡 A solução

Foi criada uma jornada interativa baseada em um questionário de perfil.

O visitante responde perguntas relacionadas a estilo, preferências e comportamento e, ao final, recebe uma identificação de perfil.

<img width="1890" height="851" alt="Perfis RAW, MINIMAL, GRAPHIC e UNDERGROUND usados na segmentação da audiência" src="https://github.com/user-attachments/assets/e5e17170-48e9-4b92-8646-f4f3045a160e" />

*Os quatro perfis utilizados na segmentação da audiência: RAW, MINIMAL, GRAPHIC e UNDERGROUND.*

Para a marca, essas respostas também geram dados que permitem observar:

- distribuição dos diferentes perfis;
- preferências predominantes;
- sinais de intenção de compra;
- perfis com maior potencial comercial.

Com o acúmulo dessas respostas, os padrões encontrados podem apoiar decisões sobre futuros produtos e drops.

## 🔄 Fluxo da experiência

Página de entrada  
↓  
Questionário via WhatsApp  
↓  
Identificação e segmentação do perfil  
↓  
Resultado e recomendação relacionada ao perfil  
↓  
Captação do contato  
↓  
Qualificação e registro no Lead Hub  
↓  
Redirecionamento conforme a classificação do lead

<img width="1895" height="712" alt="Resumo visual do funcionamento do Drop Signal" src="https://github.com/user-attachments/assets/4cd5e056-cd52-4ee1-a3ae-e888dd82a365" />

*Visão resumida da jornada: o visitante faz suas escolhas, recebe um perfil e as respostas se transformam em sinais para a leitura da audiência e dos próximos lançamentos.*

### Questionário em formato WhatsApp no FunilPro

A jornada continua em um simulador de conversa em formato WhatsApp configurado no FunilPro, onde as respostas ajudam a identificar preferências e sinais de intenção do visitante.

<img width="582" height="862" alt="Etapa do questionário de perfil via WhatsApp" src="https://github.com/user-attachments/assets/2b75cb10-8249-4c2c-9502-3f9e2108c150" />

*Exemplo de uma das etapas do questionário utilizado para identificar o perfil do visitante.*

### Resultado do perfil

Ao final do fluxo, o visitante recebe um perfil de estilo com base nas respostas fornecidas durante a jornada.

<img width="596" height="857" alt="Resultado do questionário com perfil MINIMAL" src="https://github.com/user-attachments/assets/e296910f-4e4a-4580-98d2-fff2a9a2af8d" />

*Exemplo de resultado do questionário: perfil MINIMAL.*

### Captação de lead

Após a identificação do perfil, o visitante pode entrar no radar da marca informando seus dados de contato e autorizando o uso das informações para continuidade da jornada.

<img width="1892" height="852" alt="Tela de captação de lead da AXB Streetwear Clothing" src="https://github.com/user-attachments/assets/26ab1682-673d-4694-93cb-bfd6d3df10c6" />

*Tela de captação utilizada para registrar o contato e conectar o perfil identificado à etapa de qualificação.*

### Redirecionamento da jornada

Após o cadastro, o fluxo direciona o visitante conforme a classificação do lead. Contatos em **Acompanhamento** seguem para o Instagram da marca, enquanto os demais são direcionados ao site/e-commerce.

<img width="967" height="846" alt="Tela de confirmação e redirecionamento após o cadastro" src="https://github.com/user-attachments/assets/414012dc-9cba-4e66-bc76-b8cfa5c8f94e" />

*Tela de confirmação e redirecionamento após o registro do contato.*

### Painel de leads

Os contatos captados são organizados no Lead Hub, onde podem ser visualizados, filtrados e acompanhados conforme o nível de intenção identificado durante a jornada.

<img width="1886" height="846" alt="Painel de leads com segmentação por intenção, busca, filtros e exportação" src="https://github.com/user-attachments/assets/db044ebd-42fe-46f3-a6aa-7eaa22457f77" />

*Painel de leads com segmentação por intenção, busca, filtros e exportação de dados.*

> **Observação:** os dados exibidos no painel são fictícios e utilizados exclusivamente para demonstração do projeto.

## 📊 Inteligência para produto

O objetivo do fluxo não é apenas recomendar um perfil.

Durante a jornada no FunilPro, o visitante responde perguntas sobre estilo, tamanho, preferência de cor, direção visual da peça, faixa de preço e intenção em relação ao próximo lançamento.

Esses sinais foram pensados para ajudar a marca a entender melhor sua audiência e apoiar decisões futuras sobre produto, posicionamento e próximos drops.

Na versão atual, o **Lead Hub não armazena todo o histórico dessas respostas**. Ele recebe a classificação final de intenção do visitante e registra os dados de contato, consentimento e data de entrada.

A leitura completa das preferências acontece no fluxo configurado no FunilPro, enquanto o Lead Hub funciona como a camada de captação, organização e qualificação comercial.

## 🧲 Lead Hub

A jornada também foi ampliada com um sistema complementar de captação e gerenciamento de leads.

Em vez de registrar apenas dados de contato, o sistema utiliza sinais obtidos durante o fluxo para classificar os leads em quatro grupos:

- **Alta intenção** — sinais mais fortes de intenção de compra;
- **Quer ver a peça** — interesse existente, mas ainda precisa conhecer melhor o produto;
- **Sensível a preço** — decisão mais influenciada pelo preço;
- **Acompanhamento** — interesse presente, mas sem intenção imediata de compra.

A classificação é **baseada em regras definidas a partir das respostas do fluxo**, e não em um modelo preditivo.

## ⚙️ Funcionalidades

- Questionário de perfil
- Segmentação de audiência
- Recomendação baseada no perfil
- Fluxo conversacional em formato WhatsApp
- Captação de leads
- Qualificação de leads
- Classificação por intenção
- Redirecionamento por classificação
- Validação de dados
- Prevenção de duplicidades
- Busca e filtros
- Gerenciamento de contatos
- Exportação de dados

## 🛠 Tecnologias e ferramentas

- PHP
- JavaScript
- HTML
- CSS
- Persistência de dados
- Automação de processos
- IA generativa como apoio ao desenvolvimento, testes e resolução de problemas
- FunilPro — configuração da landing page e do fluxo conversacional

## 🧠 Visão de negócio

O projeto conecta desenvolvimento web, CRM, automação e marketing.

A proposta é transformar uma interação com o visitante em informação útil tanto para relacionamento comercial quanto para decisões sobre produto.

> **Um quiz que começa dizendo ao cliente quem ele é e termina ajudando a marca a descobrir para quem ela deve criar.**

## 🚧 Status

Projeto em desenvolvimento e testes.

Este repositório contém uma versão pública sanitizada do Lead Hub, sem credenciais reais, dados pessoais ou arquivos de sessão.

A lógica do funil configurado no FunilPro está documentada em [`docs/FUNIL.md`](docs/FUNIL.md). Detalhes sobre a versão pública e configuração local estão disponíveis em [`docs/PUBLIC_VERSION.md`](docs/PUBLIC_VERSION.md) e [`docs/SETUP.md`](docs/SETUP.md).
