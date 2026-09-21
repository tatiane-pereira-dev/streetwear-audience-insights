# Versão pública do portfólio

Esta pasta preserva a lógica e a interface do Lead Hub apresentado no projeto, mas não é uma cópia bruta do ambiente de demonstração. Antes da publicação foram removidos dados de sessão, leads de teste, credenciais visíveis e o relatório interno de auditoria.

Também foram aplicados ajustes de segurança adequados a um repositório público: credenciais via ambiente, senha por hash, regeneração de sessão, CSRF na exclusão, banco ignorado pelo Git e proteção adicional na serialização de dados usada pela exportação.

Esses ajustes pertencem apenas à cópia de portfólio e não alteram a versão congelada entregue para avaliação.
