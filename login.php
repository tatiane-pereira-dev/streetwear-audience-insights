<?php
require 'functions.php';

iniciar_sessao_segura();
enviar_headers_no_cache();

if (!empty($_SESSION['logado'])) {
    header('Location: admin.php');
    exit;
}

$erro = '';

$usuario_ok = getenv('AXB_ADMIN_USER') ?: '';
$senha_hash = getenv('AXB_ADMIN_PASSWORD_HASH') ?: '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $senha   = $_POST['senha'] ?? '';

    $csrf = $_POST['csrf_token'] ?? '';
    $configurado = $usuario_ok !== '' && $senha_hash !== '';
    $usuario_valido = $configurado && hash_equals($usuario_ok, $usuario);
    $senha_valida = $configurado && password_verify($senha, $senha_hash);

    if (!validar_csrf($csrf)) {
        $erro = 'Sessão expirada. Recarregue a página e tente novamente.';
    } elseif ($usuario_valido && $senha_valida) {
        session_regenerate_id(true);
        $_SESSION['logado'] = true;
        header('Location: admin.php');
        exit;
    } elseif (!$configurado) {
        $erro = 'Ambiente administrativo ainda não configurado.';
    } else {
        $erro = 'Usuário ou senha incorretos.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AXB Lead Hub — Acesso Administrativo</title>

    <link rel="stylesheet" href="login.css?v=1">
</head>

<body class="login-page">

    <main class="login-shell">

        <section class="login-visual" aria-label="AXB Lead Hub">

            <div class="visual-top">
                <span class="system-label">AXB / LEAD HUB</span>
                <span class="system-code">ADMIN 001</span>
            </div>

            <div class="visual-content">

                <div class="brand-mark">
                    <span class="brand-main">AXB</span>
                    <span class="brand-sub">STREETWEAR</span>
                </div>

                <p class="visual-kicker">DADOS. SINAL. MOVIMENTO.</p>

                <h1>
                    QUEM ENTRA<br>
                    NO RADAR,<br>
                    DEIXA UM SINAL.
                </h1>

                <p class="visual-copy">
                    Ambiente administrativo para acompanhar os leads
                    gerados pelo ecossistema digital da AXB.
                </p>

            </div>

            <div class="visual-bottom">
                <span>LEADS / ORIGENS / EXPORTAÇÃO</span>
                <span>AXBSTREETWEAR</span>
            </div>

        </section>

        <section class="login-area">

            <div class="login-card">

                <div class="login-card-top">
                    <span class="eyebrow">ACESSO RESTRITO</span>
                    <span class="status-dot">SISTEMA ONLINE</span>
                </div>

                <div class="login-header">
                    <h2>Entrar no painel</h2>
                    <p>
                        Use suas credenciais para acessar o gerenciamento
                        de leads da AXB.
                    </p>
                </div>

                <?php if ($erro): ?>
                    <div class="login-error" role="alert">
                        <?= htmlspecialchars($erro) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="login.php" class="login-form">

                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(token_csrf(), ENT_QUOTES, 'UTF-8') ?>">

                    <div class="field-group">
                        <label for="usuario">Usuário</label>

                        <input
                            type="text"
                            id="usuario"
                            name="usuario"
                            placeholder="Digite seu usuário"
                            autocomplete="username"
                            autocapitalize="off"
                            autocorrect="off"
                            spellcheck="false"
                            required
                            autofocus
                        >
                    </div>

                    <div class="field-group">
                        <label for="senha">Senha</label>

                        <div class="password-field">
                            <input
                                type="password"
                                id="senha"
                                name="senha"
                                placeholder="Digite sua senha"
                                autocomplete="current-password"
                                autocapitalize="off"
                                autocorrect="off"
                                spellcheck="false"
                                required
                            >

                            <button
                                type="button"
                                class="password-toggle"
                                id="passwordToggle"
                                aria-label="Mostrar senha"
                            >
                                VER
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-login">
                        ACESSAR LEAD HUB
                        <span aria-hidden="true">→</span>
                    </button>

                </form>

                <div class="login-footer">
                    <span>© <?= date('Y') ?> AXB Streetwear</span>
                    <span>Desenvolvido por Tatiane Pereira</span>
                </div>

            </div>

        </section>

    </main>

    <script>
        const passwordInput = document.getElementById('senha');
        const passwordToggle = document.getElementById('passwordToggle');

        passwordToggle.addEventListener('click', function () {
            const isPassword = passwordInput.type === 'password';

            passwordInput.type = isPassword ? 'text' : 'password';
            passwordToggle.textContent = isPassword ? 'OCULTAR' : 'VER';
            passwordToggle.setAttribute(
                'aria-label',
                isPassword ? 'Ocultar senha' : 'Mostrar senha'
            );
        });
    </script>

</body>
</html>