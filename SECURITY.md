# Segurança

Esta versão pública foi sanitizada para portfólio.

- credenciais administrativas não ficam no código;
- a senha é validada por hash (`password_verify`);
- o ID de sessão é regenerado após login;
- cookies de sessão usam `HttpOnly` e `SameSite=Lax` (e `Secure` em HTTPS);
- exclusão de leads exige token CSRF;
- o banco real e arquivos de sessão são ignorados pelo Git;
- a exportação para JavaScript usa flags `JSON_HEX_*`;
- a exportação CSV preserva a proteção contra fórmulas iniciadas por `=`, `+`, `-` ou `@`.

Para implantação real, recomenda-se armazenar o banco fora do document root, usar HTTPS e configurar permissões de arquivo restritivas.
