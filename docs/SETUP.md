# Configuração local

1. Use PHP 7.3+ (PHP 8.x recomendado).
2. Copie `data/db.example.json` para `data/db.json`.
3. Defina `AXB_ADMIN_USER`.
4. Gere um hash com `php -r "echo password_hash('SUA_SENHA', PASSWORD_DEFAULT), PHP_EOL;"` e defina `AXB_ADMIN_PASSWORD_HASH`.
5. Para produção, defina `AXB_DB_PATH` para um arquivo fora do document root.

Nenhuma credencial real faz parte deste repositório.
