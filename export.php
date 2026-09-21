<?php

require 'functions.php';

verificar_sessao();
enviar_headers_no_cache();


/*
|--------------------------------------------------------------------------
| CARREGA OS LEADS
|--------------------------------------------------------------------------
*/

$dados = ler_json(arquivo_dados());

if (
    isset($dados['leads']) &&
    is_array($dados['leads'])
) {
    $leads = $dados['leads'];
} else {
    $leads = array();
}


/*
|--------------------------------------------------------------------------
| RECEBE OS FILTROS DO PAINEL
|--------------------------------------------------------------------------
*/

$busca = isset($_GET['busca'])
    ? trim($_GET['busca'])
    : '';

$filtro_origem = isset($_GET['origem'])
    ? trim($_GET['origem'])
    : '';


/*
|--------------------------------------------------------------------------
| FILTRO DE BUSCA
|--------------------------------------------------------------------------
*/

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


/*
|--------------------------------------------------------------------------
| FILTRO DE ORIGEM
|--------------------------------------------------------------------------
*/

if ($filtro_origem !== '') {

    $leads = array_filter(
        $leads,
        function ($lead) use ($filtro_origem) {

            $origem = isset($lead['origem'])
                ? $lead['origem']
                : 'geral';

            return $origem === $filtro_origem;
        }
    );
}


/*
|--------------------------------------------------------------------------
| ORDENA DO MAIS RECENTE PARA O MAIS ANTIGO
|--------------------------------------------------------------------------
*/

$leads = array_values($leads);

usort(
    $leads,
    function ($a, $b) {

        $data_a = isset($a['data'])
            ? $a['data']
            : '';

        $data_b = isset($b['data'])
            ? $b['data']
            : '';

        return strcmp($data_b, $data_a);
    }
);

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
    AXB Lead Hub — Exportar Leads
</title>

<style>

* {
    box-sizing: border-box;
}

body {
    background: #0d0d0d;
    color: #ffffff;
    font-family: Arial, sans-serif;

    display: flex;
    align-items: center;
    justify-content: center;

    min-height: 100vh;

    margin: 0;

    padding: 20px;
}

.box {
    width: 440px;

    max-width: 100%;

    text-align: center;

    padding: 40px;

    border: 1px solid #333333;

    background: #151515;
}

.logo {
    font-size: 32px;

    font-weight: 900;

    letter-spacing: 4px;

    margin-bottom: 6px;
}

.sub {
    color: #777777;

    font-size: 12px;

    text-transform: uppercase;

    letter-spacing: 2px;

    margin-bottom: 35px;
}

h1 {
    margin: 0 0 12px;

    font-size: 28px;
}

p {
    color: #aaaaaa;

    line-height: 1.6;

    margin: 0 0 25px;
}

.info {
    padding: 14px;

    border: 1px solid #2b2b2b;

    background: #111111;

    margin-bottom: 20px;
}

.info strong {
    color: #ffffff;
}

button,
a {

    display: block;

    width: 100%;

    box-sizing: border-box;

    padding: 15px;

    border: 0;

    text-decoration: none;

    font-weight: bold;

    cursor: pointer;

    font-size: 13px;

    letter-spacing: 1px;
}

button {
    background: #ffffff;

    color: #000000;
}

button:hover {
    background: #dddddd;
}

a {
    background: #222222;

    color: #ffffff;

    margin-top: 12px;
}

a:hover {
    background: #2c2c2c;
}

.filtro {
    margin-top: 18px;

    color: #777777;

    font-size: 12px;
}

</style>

</head>


<body>


<div class="box">


    <div class="logo">
        AXB
    </div>


    <div class="sub">
        Lead Hub
    </div>


    <h1>
        Exportar Leads
    </h1>


    <p>
        Gere um arquivo CSV com os contatos
        atualmente selecionados no painel.
    </p>


    <div class="info">

        <strong>
            <?= count($leads) ?>
        </strong>

        lead(s) disponível(is)
        para exportação.

    </div>


    <?php if (
        $busca !== '' ||
        $filtro_origem !== ''
    ): ?>

        <div class="filtro">

            Exportação baseada nos filtros
            ativos do painel.

        </div>

    <?php endif; ?>


    <button
        type="button"
        onclick="baixarCSV()"
    >

        BAIXAR CSV

    </button>


    <a href="admin.php">

        VOLTAR AO PAINEL

    </a>


</div>


<script>

/*
|--------------------------------------------------------------------------
| LEADS JÁ FILTRADOS PELO PHP
|--------------------------------------------------------------------------
*/

const leads = <?= json_encode(
    $leads,
    JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
) ?>;


/*
|--------------------------------------------------------------------------
| PROTEÇÃO E FORMATAÇÃO DAS CÉLULAS
|--------------------------------------------------------------------------
*/

function campoCSV(valor) {

    if (
        valor === null ||
        valor === undefined
    ) {
        return '""';
    }

    valor = String(valor);


    /*
     * Evita fórmulas automáticas
     * quando aberto no Excel.
     */

    const limpo =
        valor.trimStart();

    if (
        limpo.startsWith('=') ||
        limpo.startsWith('+') ||
        limpo.startsWith('-') ||
        limpo.startsWith('@')
    ) {

        valor = "'" + valor;
    }


    /*
     * Escapa aspas.
     */

    valor =
        valor.replace(
            /"/g,
            '""'
        );


    return '"' + valor + '"';
}


/*
|--------------------------------------------------------------------------
| GERA E BAIXA O CSV
|--------------------------------------------------------------------------
*/

function baixarCSV() {

    const linhas = [];


    /*
     * Cabeçalho
     */

    linhas.push(
        [
            'Nome',
            'WhatsApp',
            'E-mail',
            'Origem',
            'Data'
        ]
        .map(campoCSV)
        .join(';')
    );


    /*
     * Dados
     */

    leads.forEach(
        function (lead) {

            linhas.push(
                [
                    lead.nome || '',
                    lead.whatsapp || '',
                    lead.email || '',
                    lead.origem || '',
                    lead.data || ''
                ]
                .map(campoCSV)
                .join(';')
            );
        }
    );


    /*
     * BOM UTF-8 + conteúdo
     */

    const csv =
        '\uFEFF' +
        linhas.join('\r\n');


    /*
     * Cria o arquivo no navegador.
     */

    const blob =
        new Blob(
            [csv],
            {
                type:
                    'text/csv;charset=utf-8;'
            }
        );


    const url =
        URL.createObjectURL(blob);


    const link =
        document.createElement('a');


    link.href = url;


    link.download =
        'leads-axb-' +
        new Date()
            .toISOString()
            .slice(0, 10) +
        '.csv';


    document.body.appendChild(link);

    link.click();

    document.body.removeChild(link);

    URL.revokeObjectURL(url);
}

</script>


</body>

</html>