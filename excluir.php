<?php
require 'functions.php';
verificar_sessao();
enviar_headers_no_cache();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf_token'] ?? '';

    if (!validar_csrf($csrf)) {
        http_response_code(403);
        exit('Requisição inválida.');
    }

    $id = trim($_POST['id'] ?? '');
    if ($id !== '') {
        $dados = ler_json(arquivo_dados());
        $dados['leads'] = array_values(
            array_filter(
                $dados['leads'] ?? [],
                function ($l) use ($id) {
                    // Mantém leads antigos que não possuem 'id' definido,
                    // evitando um aviso de índice indefinido e a remoção acidental de dados.
                    return !isset($l['id']) || $l['id'] !== $id;
                }
            )
        );
        salvar_json(arquivo_dados(), $dados);
    }
}

redirecionar('admin.php');