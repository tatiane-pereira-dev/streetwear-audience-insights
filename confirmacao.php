<?php
require 'functions.php';
iniciar_sessao_segura();

$redirect_url = $_SESSION['lead_redirect'] ?? 'https://axbstreetwearclothing.com.br';
$lead_confirmado = !empty($_SESSION['lead_confirmado']);

if (!$lead_confirmado) {
    header('Location: index.php');
    exit;
}

// Evita reutilizar a confirmação em uma nova visita.
unset($_SESSION['lead_confirmado']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="dark">
    <title>AXB — Você entrou no radar</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Archivo:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css?v=3">
</head>
<body class="confirm-body">

    <main class="confirm-box">
        <div class="confirm-logo">
            <img src="https://funilpro.com.br/uploads/user_2587/pasta/6aab12f16692f8.09448219.png" alt="AXB">
        </div>

        <h1 class="confirm-title">Você entrou no radar.</h1>

        <p class="confirm-sub">
            Seu contato foi registrado. Em instantes, você segue para o próximo destino da AXB.
        </p>

        <div class="confirm-loader" aria-hidden="true">
            <div class="loader-bar"></div>
        </div>

        <p class="confirm-redirect">Redirecionando em instantes...</p>

        <a class="btn-confirm-link" href="<?= htmlspecialchars($redirect_url, ENT_QUOTES, 'UTF-8') ?>">
            Ir agora →
        </a>
    </main>

    <footer class="confirm-footer">
        <span>© <?= date('Y') ?> AXB Streetwear</span>
        <span>Drop Signal / Lead Hub</span>
    </footer>

    <script>
        window.setTimeout(function () {
            window.location.href = <?= json_encode($redirect_url, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>;
        }, 4000);
    </script>

</body>
</html>
