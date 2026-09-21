# Lógica do Drop Signal

O funil foi configurado no FunilPro e funciona como uma jornada de descoberta de perfil e intenção. O código interno do simulador de WhatsApp pertence à plataforma; este repositório documenta a lógica configurada e contém o Lead Hub desenvolvido em PHP.

## Perfis

- RAW — presença pela modelagem, peso e construção;
- MINIMAL — visual limpo, neutro e preciso;
- GRAPHIC — arte, estampa e conceito;
- UNDERGROUND — referência de rua e linguagem menos comercial.

## Sinais coletados no fluxo

Depois da classificação de perfil, o funil pergunta tamanho, preferência de cor, direção visual da peça, faixa de preço e reação esperada ao próximo lançamento.

## Qualificação

A resposta sobre intenção direciona o visitante para uma das quatro origens do Lead Hub:

- `alta-intencao`;
- `quer-ver-peca`;
- `preco`;
- `acompanhar`.

O Lead Hub recebe a origem pela URL, captura os dados de contato mediante aceite e organiza os leads para busca, filtro, exclusão e exportação.
