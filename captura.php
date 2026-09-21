<?php

require 'functions.php';
iniciar_sessao_segura();

$origem = trim($_GET['origem'] ?? '');

$origens_validas = [
    'alta-intencao',
    'quer-ver-peca',
    'preco',
    'acompanhar'
];

if (!in_array($origem, $origens_validas, true)) {
    $origem = 'geral';
}

$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = trim($_POST['nome'] ?? '');
    $whatsapp = trim($_POST['whatsapp'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $aceite = isset($_POST['aceite']) ? 1 : 0;

    $origem_post = trim(
        $_POST['origem'] ?? $origem
    );

    if (!in_array($origem_post, $origens_validas, true)) {
        $origem_post = 'geral';
    }

    $whatsapp_numeros = normalizar_whatsapp($whatsapp);

    /*
     * =========================================
     * VALIDAÇÃO DOS CAMPOS
     * =========================================
     */

    if ($nome === '') {
        $erros[] = 'Informe seu nome.';
    } elseif (strlen($nome) < 2) {
        $erros[] = 'Informe um nome válido.';
    }

    if ($whatsapp === '') {

        $erros[] = 'Informe seu WhatsApp.';

    } elseif (!validar_whatsapp_br($whatsapp)) {

        $erros[] =
            'Informe um número de WhatsApp celular válido com DDD.';

    }

    if ($email === '') {

        $erros[] = 'Informe seu e-mail.';

    } elseif (!validar_email($email)) {

        $erros[] = 'Informe um e-mail válido.';

    }

    if (!$aceite) {
        $erros[] =
            'Você precisa aceitar o recebimento das comunicações para continuar.';
    }

    /*
     * =========================================
     * DUPLICIDADE
     * =========================================
     */

    if (empty($erros)) {

        $dados = ler_json(arquivo_dados());

        if (
            !isset($dados['leads']) ||
            !is_array($dados['leads'])
        ) {
            $dados['leads'] = [];
        }

        $email_duplicado = false;
        $whatsapp_duplicado = false;

        foreach ($dados['leads'] as $lead_existente) {

            $email_existente = strtolower(
                trim($lead_existente['email'] ?? '')
            );

            $whatsapp_existente = normalizar_whatsapp(
                $lead_existente['whatsapp'] ?? ''
            );

            if (
                $email_existente !== '' &&
                $email_existente === $email
            ) {
                $email_duplicado = true;
            }

            if (
                $whatsapp_existente !== '' &&
                $whatsapp_existente === $whatsapp_numeros
            ) {
                $whatsapp_duplicado = true;
            }
        }

        if ($email_duplicado) {
            $erros[] =
                'Este e-mail já está cadastrado no radar da AXB.';
        }

        if ($whatsapp_duplicado) {
            $erros[] =
                'Este WhatsApp já está cadastrado no radar da AXB.';
        }
    }

    /*
     * =========================================
     * SALVA O LEAD
     * =========================================
     */

    if (empty($erros)) {

        $lead = [
            'id' => gerar_id(),

            // Os dados são armazenados sem escapar HTML.
            // O escape deve ser feito apenas na exibição (admin.php),
            // evitando o problema de dupla codificação (ex: "&amp;amp;").
            'nome' => $nome,

            'whatsapp' => $whatsapp,

            'email' => $email,

            'origem' => $origem_post,

            'aceite' => $aceite,

            'data' => date('Y-m-d H:i:s')
        ];

        $dados['leads'][] = $lead;

        $salvo = salvar_json(
            arquivo_dados(),
            $dados
        );

        if (!$salvo) {
            $erros[] = 'Não foi possível registrar seu contato agora. Tente novamente.';
        }

        /*
         * =========================================
         * REDIRECIONAMENTO
         * =========================================
         */

        $redirect_map = [

            'alta-intencao' =>
                'https://axbstreetwearclothing.com.br',

            'quer-ver-peca' =>
                'https://axbstreetwearclothing.com.br',

            'preco' =>
                'https://axbstreetwearclothing.com.br/produtos',

            'acompanhar' =>
                'https://instagram.com/axbstreetwear'
        ];

        $redirect_url =
            $redirect_map[$origem_post]
            ?? 'https://axbstreetwearclothing.com.br';

        if (empty($erros)) {
            $_SESSION['lead_confirmado'] = true;
            $_SESSION['lead_redirect'] = $redirect_url;

            header('Location: confirmacao.php');
            exit;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<meta
    name="color-scheme"
    content="dark"
>

<title>AXB — Entre no Radar</title>

<link
    rel="preconnect"
    href="https://fonts.googleapis.com"
>

<link
    rel="preconnect"
    href="https://fonts.gstatic.com"
    crossorigin
>

<link
    href="https://fonts.googleapis.com/css2?family=Anton&family=Archivo:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600&display=swap"
    rel="stylesheet"
>

<link
    rel="stylesheet"
    href="style.css"
>

</head>

<body class="capture-body">

<main class="lead-shell">

    <section
        class="lead-visual"
        aria-label="AXB Streetwear"
    >

        <div
            class="grain"
            aria-hidden="true"
        ></div>

        <img
            class="lead-photo"
            src="https://funilpro.com.br/uploads/user_2587/pasta/6aab12b8f2dfc8.28407161.jpg"
            alt="Modelo usando camiseta AXB"
        >

        <div
            class="lead-photo-overlay"
            aria-hidden="true"
        ></div>

        <a
            class="brand-mark"
            href="https://axbstreetwearclothing.com.br"
            target="_blank"
            rel="noopener"
            aria-label="Visitar site oficial AXB"
        >

            <img
                src="https://funilpro.com.br/uploads/user_2587/pasta/6aab12f16692f8.09448219.png"
                alt="AXB"
            >

        </a>

        <div class="lead-copy">

            <div class="eyebrow">
                DROP SIGNAL / ACESSO ANTECIPADO
            </div>

            <h1>
                ENTRE<br>
                NO RADAR.
            </h1>

            <p>
                Receba os próximos movimentos da AXB
                antes do público geral.
            </p>

        </div>

        <div class="visual-note">
            AXB / DROP 001
        </div>

    </section>


    <section class="lead-form-panel">

        <div class="form-wrap">

            <div class="form-topline">

                <img
                    class="form-logo"
                    src="https://funilpro.com.br/uploads/user_2587/pasta/6aab12e5e0f191.84794248.png"
                    alt="AXB"
                >

                <span>
                    LEAD HUB
                </span>

            </div>


            <div class="form-header">

                <div class="micro-label">
                    ACESSO ANTECIPADO
                </div>

                <h2>
                    Seu sinal<br>
                    já chegou.
                </h2>

                <p>
                    Deixe seu contato para receber drops,
                    lançamentos e novidades da AXB.
                </p>

            </div>


            <?php if (!empty($erros)): ?>

                <div
                    class="alert-erro"
                    role="alert"
                >

                    <?php foreach ($erros as $erro): ?>

                        <div>
                            <?= htmlspecialchars(
                                $erro,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </div>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>


            <form
                method="POST"
                action="captura.php<?= $origem !== 'geral'
                    ? '?origem=' . urlencode($origem)
                    : ''
                ?>"
                class="lead-form"
            >

                <input
                    type="hidden"
                    name="origem"
                    value="<?= htmlspecialchars(
                        $origem,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                >


                <div class="field-group">

                    <label for="nome">
                        NOME
                    </label>

                    <input
                        type="text"
                        id="nome"
                        name="nome"
                        placeholder="Seu nome"
                        value="<?= htmlspecialchars(
                            $_POST['nome'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                        autocomplete="name"
                        required
                    >

                </div>


                <div class="field-group">

                    <label for="whatsapp">
                        WHATSAPP
                    </label>

                    <input
                        type="tel"
                        id="whatsapp"
                        name="whatsapp"
                        placeholder="(48) 99999-9999"
                        value="<?= htmlspecialchars(
                            $_POST['whatsapp'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                        autocomplete="tel"
                        inputmode="numeric"
                        maxlength="15"
                        required
                    >

                </div>


                <div class="field-group">

                    <label for="email">
                        E-MAIL
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="seu@email.com"
                        value="<?= htmlspecialchars(
                            $_POST['email'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                        autocomplete="email"
                        required
                    >

                </div>


                <label class="check-label">

                    <input
                        type="checkbox"
                        name="aceite"
                        <?= isset($_POST['aceite'])
                            ? 'checked'
                            : ''
                        ?>
                        required
                    >

                    <span
                        class="check-box"
                        aria-hidden="true"
                    ></span>

                    <span class="check-copy">
                        Aceito receber comunicações da
                        AXB Streetwear por WhatsApp e e-mail.
                    </span>

                </label>


                <button
                    type="submit"
                    class="btn-capture"
                >

                    <span>
                        ENTRAR NO RADAR
                    </span>

                    <span aria-hidden="true">
                        ↗
                    </span>

                </button>

            </form>


            <div class="form-foot">

                <span>
                    SEM SPAM.
                </span>

                <span>
                    SAÍDA A QUALQUER MOMENTO.
                </span>

            </div>

        </div>

    </section>

</main>


<footer class="capture-footer">

    <span>
        © <?= date('Y') ?> AXB Streetwear
    </span>

    <a
        href="https://axbstreetwearclothing.com.br"
        target="_blank"
        rel="noopener"
    >
        SITE OFICIAL ↗
    </a>

</footer>


<script>

(function () {

    const phone =
        document.getElementById('whatsapp');

    if (!phone) {
        return;
    }

    phone.addEventListener(
        'input',
        function () {

            let value =
                this.value
                    .replace(/\D/g, '')
                    .slice(0, 11);

            if (value.length > 10) {

                value = value.replace(
                    /(\d{2})(\d{5})(\d{4})/,
                    '($1) $2-$3'
                );

            } else if (value.length > 6) {

                value = value.replace(
                    /(\d{2})(\d{4})(\d{0,4})/,
                    '($1) $2-$3'
                );

            } else if (value.length > 2) {

                value = value.replace(
                    /(\d{2})(\d+)/,
                    '($1) $2'
                );

            } else if (value.length > 0) {

                value = value.replace(
                    /(\d{0,2})/,
                    '($1'
                );
            }

            this.value = value;
        }
    );

})();

</script>

</body>
</html>