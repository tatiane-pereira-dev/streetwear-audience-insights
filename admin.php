<?php

require 'functions.php';

verificar_sessao();

enviar_headers_no_cache();

/*
|--------------------------------------------------------------------------
| CARREGA O BANCO
|--------------------------------------------------------------------------
*/

$dados = ler_json(arquivo_dados());

if (!isset($dados['leads']) || !is_array($dados['leads'])) {
    $dados['leads'] = array();
}

$todos_leads = $dados['leads'];
$leads = $todos_leads;


/*
|--------------------------------------------------------------------------
| FUNÇÃO AUXILIAR: NORMALIZA O NOME DA ORIGEM
|--------------------------------------------------------------------------
*/

function normalizar_origem_lead($lead) {

    $origem_lead = isset($lead['origem'])
        ? trim($lead['origem'])
        : 'geral';

    if ($origem_lead === '') {
        $origem_lead = 'geral';
    }

    return $origem_lead;
}


/*
|--------------------------------------------------------------------------
| ORDENA DO MAIS RECENTE PARA O MAIS ANTIGO
|--------------------------------------------------------------------------
*/

usort($leads, function ($a, $b) {

    $data_a = isset($a['data']) ? $a['data'] : '';
    $data_b = isset($b['data']) ? $b['data'] : '';

    return strcmp($data_b, $data_a);
});


/*
|--------------------------------------------------------------------------
| FILTROS
|--------------------------------------------------------------------------
*/

$busca = isset($_GET['busca'])
    ? trim($_GET['busca'])
    : '';

$filtro_origem = isset($_GET['origem'])
    ? trim($_GET['origem'])
    : '';


if ($busca !== '') {

    $busca_normalizada = strtolower($busca);

    $leads = array_filter(
        $leads,
        function ($lead) use ($busca_normalizada) {

            $nome = isset($lead['nome'])
                ? strtolower($lead['nome'])
                : '';

            $email = isset($lead['email'])
                ? strtolower($lead['email'])
                : '';

            $whatsapp = isset($lead['whatsapp'])
                ? strtolower($lead['whatsapp'])
                : '';

            return
                strpos($nome, $busca_normalizada) !== false ||
                strpos($email, $busca_normalizada) !== false ||
                strpos($whatsapp, $busca_normalizada) !== false;
        }
    );
}


if ($filtro_origem !== '') {

    $leads = array_filter(
        $leads,
        function ($lead) use ($filtro_origem) {

            $origem_lead = normalizar_origem_lead($lead);

            return $origem_lead === $filtro_origem;
        }
    );
}


$leads = array_values($leads);


/*
|--------------------------------------------------------------------------
| CONTAGEM POR ORIGEM
|--------------------------------------------------------------------------
*/

$origens_count = array();

foreach ($todos_leads as $lead) {

    $origem_lead = normalizar_origem_lead($lead);

    if (!isset($origens_count[$origem_lead])) {
        $origens_count[$origem_lead] = 0;
    }

    $origens_count[$origem_lead]++;
}


/*
|--------------------------------------------------------------------------
| LINKS
|--------------------------------------------------------------------------
*/

$link_publico = 'captura.php';

if ($filtro_origem !== '') {
    $link_publico .= '?origem=' . urlencode($filtro_origem);
}


$link_exportacao = 'export.php';

$parametros = array();

if ($busca !== '') {
    $parametros['busca'] = $busca;
}

if ($filtro_origem !== '') {
    $parametros['origem'] = $filtro_origem;
}

if (!empty($parametros)) {
    $link_exportacao .= '?' . http_build_query($parametros);
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

<title>
    AXB Lead Hub — Painel
</title>

<link
    rel="stylesheet"
    href="style.css"
>

</head>


<body class="admin-body">


<!-- SIDEBAR -->

<aside class="sidebar">

    <div class="sidebar-brand">

        <span class="sb-logo">
            AXB
        </span>

        <span class="sb-sub">
            Lead Hub
        </span>

    </div>


    <nav class="sidebar-nav">

        <a
            href="admin.php"
            class="nav-item active"
        >
            📋 Leads
        </a>

        <a
            href="<?= htmlspecialchars(
                $link_exportacao,
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
            class="nav-item"
        >
            ⬇ Exportar CSV
        </a>

        <a
            href="<?= htmlspecialchars(
                $link_publico,
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
            class="nav-item"
            target="_blank"
            rel="noopener"
        >
            🔗 Página Pública
        </a>

        <a
            href="logout.php"
            class="nav-item nav-logout"
        >
            ↩ Sair
        </a>

    </nav>


    <div class="sidebar-footer">

        Desenvolvido por: Tatiane

    </div>

</aside>


<!-- PAINEL -->

<main class="admin-main">


    <div class="admin-header">

        <div>

            <h1>
                Painel de Leads
            </h1>

            <p style="margin:6px 0 0;color:#777;">
                Acompanhe os contatos captados pela AXB.
            </p>

        </div>

        <span class="admin-date">

            <?= date('d/m/Y H:i') ?>

        </span>

    </div>


    <!-- RESUMO -->

    <div class="summary-grid">


        <div class="summary-card">

            <div class="sc-num">

                <?= count($todos_leads) ?>

            </div>

            <div class="sc-label">

                Total de Leads

            </div>

        </div>


        <?php foreach ($origens_count as $origem_nome => $quantidade): ?>

            <div class="summary-card">

                <div class="sc-num">

                    <?= (int)$quantidade ?>

                </div>

                <div class="sc-label">

                    <?= htmlspecialchars(
                        $origem_nome,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>

                </div>

            </div>

        <?php endforeach; ?>


    </div>


    <!-- FILTROS -->

    <div class="filter-bar">

        <form
            method="GET"
            action="admin.php"
            class="filter-form"
        >

            <input
                type="text"
                name="busca"
                class="filter-input"
                placeholder="Buscar por nome, e-mail ou WhatsApp..."
                value="<?= htmlspecialchars(
                    $busca,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
            >


            <select
                name="origem"
                class="filter-select"
            >

                <option value="">
                    Todas as origens
                </option>

                <?php

                $origens_lista = array(
                    'alta-intencao',
                    'quer-ver-peca',
                    'preco',
                    'acompanhar',
                    'geral'
                );

                foreach ($origens_lista as $origem_opcao):

                ?>

                    <option
                        value="<?= htmlspecialchars(
                            $origem_opcao,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                        <?= $filtro_origem === $origem_opcao
                            ? 'selected'
                            : ''
                        ?>
                    >

                        <?= htmlspecialchars(
                            $origem_opcao,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>

                    </option>

                <?php endforeach; ?>

            </select>


            <button
                type="submit"
                class="btn-filter"
            >

                Filtrar

            </button>


            <?php if ($busca !== '' || $filtro_origem !== ''): ?>

                <a
                    href="admin.php"
                    class="btn-clear"
                >

                    Limpar

                </a>

            <?php endif; ?>


            <a
                href="<?= htmlspecialchars(
                    $link_exportacao,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
                class="btn-export"
            >

                ⬇ Exportar CSV

            </a>

        </form>

    </div>


    <!-- TABELA -->

    <div class="table-wrapper">

        <table class="leads-table">


            <thead>

                <tr>

                    <th>#</th>

                    <th>Nome</th>

                    <th>WhatsApp</th>

                    <th>E-mail</th>

                    <th>Origem</th>

                    <th>Data</th>

                    <th>Ações</th>

                </tr>

            </thead>


            <tbody>


            <?php if (empty($leads)): ?>


                <tr>

                    <td
                        colspan="7"
                        class="empty-row"
                    >

                        Nenhum lead encontrado.

                    </td>

                </tr>


            <?php else: ?>


                <?php foreach ($leads as $indice => $lead): ?>


                    <?php

                    $nome = isset($lead['nome'])
                        ? $lead['nome']
                        : '';

                    $whatsapp = isset($lead['whatsapp'])
                        ? $lead['whatsapp']
                        : '';

                    $email = isset($lead['email'])
                        ? $lead['email']
                        : '';

                    $origem_lead = normalizar_origem_lead($lead);

                    $data_lead = isset($lead['data'])
                        ? $lead['data']
                        : '';

                    $id_lead = isset($lead['id'])
                        ? $lead['id']
                        : '';

                    ?>


                    <tr>


                        <td class="td-num">

                            <?= $indice + 1 ?>

                        </td>


                        <td>

                            <?= htmlspecialchars(
                                $nome,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>

                        </td>


                        <td>

                            <?= htmlspecialchars(
                                $whatsapp,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>

                        </td>


                        <td>

                            <?= htmlspecialchars(
                                $email,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>

                        </td>


                        <td>

                            <span
                                class="badge-origem badge-<?= htmlspecialchars(
                                    $origem_lead,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                            >

                                <?= htmlspecialchars(
                                    $origem_lead,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </span>

                        </td>


                        <td>

                            <?php

                            if (
                                $data_lead !== '' &&
                                strtotime($data_lead) !== false
                            ) {

                                echo date(
                                    'd/m/Y H:i',
                                    strtotime($data_lead)
                                );

                            } else {

                                echo '-';

                            }

                            ?>

                        </td>


                        <td>


                            <?php if ($id_lead !== ''): ?>

                                <form
                                    method="POST"
                                    action="excluir.php"
                                    onsubmit="return confirm('Tem certeza que deseja excluir este lead?')"
                                >

                                    <input
                                        type="hidden"
                                        name="csrf_token"
                                        value="<?= htmlspecialchars(token_csrf(), ENT_QUOTES, 'UTF-8') ?>"
                                    >

                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?= htmlspecialchars(
                                            $id_lead,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>"
                                    >

                                    <button
                                        type="submit"
                                        class="btn-del"
                                    >

                                        Excluir

                                    </button>

                                </form>

                            <?php else: ?>

                                <span>
                                    —
                                </span>

                            <?php endif; ?>


                        </td>


                    </tr>


                <?php endforeach; ?>


            <?php endif; ?>


            </tbody>


        </table>


    </div>


    <div class="table-count">

        Exibindo
        <?= count($leads) ?>
        de
        <?= count($todos_leads) ?>
        lead(s)

    </div>


</main>


</body>

</html>