<?php

/**
 * Helpers de segurança e persistência para a versão pública do portfólio.
 * Credenciais e dados reais ficam fora do código-fonte.
 */

function app_em_https() {
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (isset($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443);
}

function iniciar_sessao_segura() {
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    $secure = app_em_https();

    if (PHP_VERSION_ID >= 70300) {
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'secure' => $secure,
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
    } else {
        session_set_cookie_params(0, '/; samesite=Lax', '', $secure, true);
    }

    session_start();
}

function enviar_headers_no_cache() {
    if (headers_sent()) {
        return;
    }

    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Pragma: no-cache');
    header('Expires: Sat, 01 Jan 2000 00:00:00 GMT');
}

/**
 * Caminho do banco. Em produção, AXB_DB_PATH pode apontar para um local
 * fora do document root. Para desenvolvimento local, usa data/db.json.
 */
function arquivo_dados() {
    $configurado = getenv('AXB_DB_PATH');

    if (is_string($configurado) && trim($configurado) !== '') {
        return $configurado;
    }

    $diretorio = __DIR__ . DIRECTORY_SEPARATOR . 'data';

    if (!is_dir($diretorio)) {
        @mkdir($diretorio, 0750, true);
    }

    return $diretorio . DIRECTORY_SEPARATOR . 'db.json';
}

function ler_json($arquivo) {
    if (!file_exists($arquivo)) {
        return ['leads' => []];
    }

    $tamanho = filesize($arquivo);

    if ($tamanho === 0 || $tamanho === false) {
        return ['leads' => []];
    }

    $fp = fopen($arquivo, 'r');

    if (!$fp) {
        return ['leads' => []];
    }

    flock($fp, LOCK_SH);
    $conteudo = stream_get_contents($fp);
    flock($fp, LOCK_UN);
    fclose($fp);

    if ($conteudo === false || trim($conteudo) === '') {
        return ['leads' => []];
    }

    $dados = json_decode($conteudo, true);

    if (!is_array($dados)) {
        return ['leads' => []];
    }

    if (!isset($dados['leads']) || !is_array($dados['leads'])) {
        $dados['leads'] = [];
    }

    return $dados;
}

function salvar_json($arquivo, $dados) {
    $diretorio = dirname($arquivo);

    if (!is_dir($diretorio) && !@mkdir($diretorio, 0750, true)) {
        return false;
    }

    $fp = fopen($arquivo, 'c+');

    if (!$fp) {
        return false;
    }

    flock($fp, LOCK_EX);
    ftruncate($fp, 0);
    rewind($fp);

    $json = json_encode($dados, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    if ($json === false) {
        flock($fp, LOCK_UN);
        fclose($fp);
        return false;
    }

    $ok = fwrite($fp, $json) !== false;
    fflush($fp);
    flock($fp, LOCK_UN);
    fclose($fp);

    return $ok;
}

function url_base() {
    $script = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '';
    $base = str_replace('\\', '/', dirname($script));

    if ($base === '/' || $base === '\\' || $base === '.') {
        $base = '';
    }

    return rtrim($base, '/');
}

function redirecionar($arquivo) {
    $base = url_base();
    header('Location: ' . $base . '/' . ltrim($arquivo, '/'));
    exit;
}

function verificar_sessao() {
    iniciar_sessao_segura();

    if (empty($_SESSION['logado'])) {
        redirecionar('login.php');
    }
}

function token_csrf() {
    iniciar_sessao_segura();

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function validar_csrf($token) {
    iniciar_sessao_segura();

    return is_string($token)
        && isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}

function formatar_moeda($valor) {
    return 'R$ ' . number_format(
        (float)$valor,
        2,
        ',',
        '.'
    );
}


function validar_email($email) {
    $email = strtolower(trim($email));

    // Verifica o formato básico.
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    $partes = explode('@', $email);

    if (count($partes) !== 2) {
        return false;
    }

    $dominio = strtolower(trim($partes[1]));

    if ($dominio === '') {
        return false;
    }

    // Domínios usados apenas como exemplo/teste.
    $dominios_bloqueados = [
        'teste.com',
        'teste.com.br',
        'example.com',
        'example.com.br',
        'exemplo.com',
        'exemplo.com.br'
    ];

    if (in_array($dominio, $dominios_bloqueados, true)) {
        return false;
    }

    /*
     * Verifica se o domínio existe.
     * Um domínio de e-mail real normalmente possui registro MX.
     * Também aceitamos A como alternativa.
     */
    if (function_exists('checkdnsrr')) {
        $possui_mx = checkdnsrr($dominio, 'MX');
        $possui_a  = checkdnsrr($dominio, 'A');

        if (!$possui_mx && !$possui_a) {
            return false;
        }
    }

    return true;
}


function normalizar_whatsapp($telefone) {
    $numero = preg_replace('/\D+/', '', $telefone);

    /*
     * Remove o código internacional brasileiro
     * caso tenha sido informado como 55.
     */
    if (
        strlen($numero) === 13 &&
        substr($numero, 0, 2) === '55'
    ) {
        $numero = substr($numero, 2);
    }

    return $numero;
}


function validar_whatsapp_br($telefone) {
    $numero = normalizar_whatsapp($telefone);

    /*
     * Celular brasileiro:
     * 2 dígitos de DDD + 9 dígitos do telefone.
     */
    if (strlen($numero) !== 11) {
        return false;
    }

    $ddd    = substr($numero, 0, 2);
    $celular = substr($numero, 2);

    $ddds_validos = [
        '11','12','13','14','15','16','17','18','19',
        '21','22','24','27','28',
        '31','32','33','34','35','37','38',
        '41','42','43','44','45','46','47','48','49',
        '51','53','54','55',
        '61','62','63','64','65','66','67','68','69',
        '71','73','74','75','77','79',
        '81','82','83','84','85','86','87','88','89',
        '91','92','93','94','95','96','97','98','99'
    ];

    if (!in_array($ddd, $ddds_validos, true)) {
        return false;
    }

    // Celulares brasileiros começam com 9.
    if (substr($celular, 0, 1) !== '9') {
        return false;
    }

    // Bloqueia números repetidos, como 999999999.
    if (preg_match('/^(\d)\1+$/', $celular)) {
        return false;
    }

    return true;
}


function gerar_id() {
    return 'lead_' . bin2hex(random_bytes(8));
}
