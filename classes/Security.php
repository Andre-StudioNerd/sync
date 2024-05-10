<?php

/**
 * Função para limpar entrada de dados
 */
function limpar_entrada($entrada) {
    $entrada = trim($entrada); // Remove espaços em branco no início e no fim
    $entrada = stripslashes($entrada); // Remove barras invertidas adicionadas por addslashes()
    $entrada = htmlspecialchars($entrada); // Converte caracteres especiais em entidades HTML
    return $entrada;
}

/**
 * Função para validar e limpar um email
 */
function validar_email($email) {
    // Remove todos os caracteres ilegais do email
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    // Verifica se o email é válido
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return limpar_entrada($email);
    } else {
        return false;
    }
}

/**
 * Função para gerar um hash seguro de senha usando bcrypt
 */
function gerar_hash_senha($senha) {
    $hash = password_hash($senha, PASSWORD_DEFAULT);
    return $hash;
}

/**
 * Função para verificar se a senha corresponde ao hash
 */
function verificar_hash_senha($senha, $hash) {
    return password_verify($senha, $hash);
}

/**
 * Função para gerar um token de autenticação seguro
 */
function gerar_token() {
    return bin2hex(random_bytes(32));
}

/**
 * Função para validar um token de autenticação
 */
function validar_token($token) {
    // Verificar se o token possui o formato correto
    if (preg_match('/^[a-f0-9]{64}$/', $token)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Função para redirecionar o usuário para outra página
 */
function redirecionar($url) {
    header("Location: $url");
    exit();
}

/**
 * Função para impedir ataques de CSRF (Cross-Site Request Forgery)
 */
function verificar_csrf($token) {
    if (!isset($_SESSION['csrf_token']) || $_SESSION['csrf_token'] !== $token) {
        redirecionar('erro.php'); // Redirecionar para uma página de erro
    }
}

/**
 * Função para gerar um token CSRF seguro
 */
function gerar_csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
function limpar_cache_navegador() {
    // Define uma data passada para expirar o cache
    $ontem = date("D, d M Y H:i:s", strtotime("-1 day"));
    
    // Define os cabeçalhos para expirar o cache
    header("Expires: $ontem GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
}

?>
