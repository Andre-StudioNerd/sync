<?php
// Obtém a URL amigável do parâmetro GET
$url = isset($_GET['url']) ? $_GET['url'] : '';

// Divide a URL em partes separadas por "/"
$url_parts = explode('/', $url);

// Obtém o segmento da URL que identifica a página solicitada
$page = isset($url_parts[0]) ? $url_parts[0] : 'home';

// Inclui o conteúdo da página com base no segmento da URL
switch ($page) {
    case 'cadastro':
        include 'cadastrar.php';
        break;
    
    // Adicione mais casos conforme necessário
    default:
        include 'index.php';
        break;
}
?>
