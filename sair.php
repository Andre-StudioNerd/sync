<?php
// Inicia a sessão
session_start();
// Remove a variável de sessão 'id_usuario'
unset($_SESSION['id_usuario']);
// Redireciona para a página inicial
header('location: index.php');
?>
