<?php

session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("location: index.php");
    exit;
}

require_once 'classes/Usuarios.php';
require_once 'classes/Security.php';
require_once('conexao.php');
$u = new Usuarios();

// Defina as variáveis de conexão com o banco de dados
//$database_name = "projeto_login";
//$database_host = "localhost";
//$database_user = "root";
//$database_password = "";

// Conecta ao banco de dados
$u->conectar($database_name, $database_host, $database_user, $database_password);

// Obtém o ID do usuário da sessão
$id_usuario = $_SESSION['id_usuario'];

// Obtém os dados do usuário pelo ID
$dados_usuario = $u->mostrar($id_usuario);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <title>Área Restrita</title>
    <link rel="stylesheet" href="style/area_privada.css">
    <link rel="apple-touch-icon" sizes="180x180" href="imagens/icon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="imagens/icon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="imagens/icon/favicon-16x16.png">
    <link rel="manifest" href="imagens/icon/site.webmanifest">
    <link rel="mask-icon" href="imagens/icon/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="mask-icon" href="imagens/icon/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

<div id="cortes">
    
   

    <div class="dados-usuario">
       <header class="title"> <h1><i class="fa fa-address-card" aria-hidden="true"></i> CRACHÁ DA SYNC</h1></header>
        <p class="photo"><img src="<?php echo $dados_usuario['imagem']; ?>" alt="Imagem do Usuário" class="image_profile"></p>
        <p class="line"><strong>Nome:</strong> <?php echo $dados_usuario['nome']; ?></p>
        <p class="line"><strong>Telefone:</strong> <?php echo $dados_usuario['telefone']; ?></p>
        <p class="line"><strong>Email:</strong> <?php echo $dados_usuario['email']; ?></p>
        <p class="line"><strong>Sexo:</strong> <?php echo $dados_usuario['sexo']; ?></p>
        <p class="line"><strong>Data de Nascimento:</strong> <?php echo $dados_usuario['nascimento']; ?></p>
        <p class="line"><strong>Endereço:</strong> <?php echo $dados_usuario['endereco']; ?></p>
        <p class="line"><strong>Bairro:</strong> <?php echo $dados_usuario['bairro']; ?></p>
        <p class="line"><strong>Cidade:</strong> <?php echo $dados_usuario['cidade']; ?></p>
        <p class="line"><strong>Estado:</strong> <?php echo $dados_usuario['estado']; ?></p>
        <p class="line"><strong>Biografia:</strong> <?php echo $dados_usuario['bio']; ?></p> 
        <p class="photo"><img src="imagens/logo.png" alt="logo" class="image_sync"></p>
        
    </div>
        <p><a href="atualiza" class="buttom_class">ATUALIZAR <i class="fa fa-upload" aria-hidden="true"></i></a></p>
        <p><a href="sair" class="buttom_class">SAIR <i class="fa fa-times" aria-hidden="true"></i></a></p>
    

</div>

</body>
</html>
