<?php
// Inclui a classe Usuarios
require_once 'classes/Usuarios.php';
// Instancia a classe Usuarios
$u = new Usuarios();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Teste Sync</title>
    <!-- Estilos -->
    <link rel="stylesheet" href="style/main.css">
    <!-- Ícones -->
    <link rel="apple-touch-icon" sizes="180x180" href="imagens/icon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="imagens/icon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="imagens/icon/favicon-16x16.png">
    <link rel="manifest" href="imagens/icon/site.webmanifest">
    <link rel="mask-icon" href="imagens/icon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <!-- Ícones Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div id="corpo-form">
    <!-- Título -->
    <h1> <i class="fas fa-users"></i> PERFIL SYNC</h1>
    <!-- Formulário de login -->
    <form method="POST">
        <input type="email" name="email" placeholder="Usuário"/>
        <input type="password" name="senha" placeholder="Senha"/>
        <input type="submit" value="ACESSAR" name=""/>
        <!-- Logo -->
        <p class="centralize"><img src="imagens/logo.png" alt="logo" class="image_logo"></p>
        <!-- Link para cadastro -->
        <p class="line_class"> Ainda não é inscrito?</p>
        <p><a href="cadastro" class="buttom_class">INSCREVA-SE! <i class="fa fa-address-book" aria-hidden="true"></i></a></p>
    </form>
</div>
<?php
// Inclui o arquivo de conexão com o banco de dados
require_once('conexao.php');
// Verifica se o formulário foi submetido
if(isset($_POST['email'])):
    // Obtém os dados do formulário
    $email = addslashes($_POST['email']);
    $senha = addslashes($_POST['senha']);
    // Verifica se os campos de email e senha foram preenchidos
    if(!empty($email) && !empty($senha)):
        // Conecta ao banco de dados
        $u->conectar($database_name, $database_host, $database_user, $database_password);
        // Verifica se houve erro na conexão
        if($u->msgERRO == ""):
            // Tenta realizar o login
            if($u->logar($email, $senha)):
                // Redireciona para o perfil em caso de sucesso
                header("location: perfil");
            else: 
                ?>
                <!-- Exibe mensagem de erro em caso de falha no login -->
                <div class="msg-erro">
                    E-mail e/ou Senha Incorretos!
                </div>
                <?php
            endif;
        else:
            ?>
            <div class="msg-erro"> 
                <!-- Exibe mensagem de erro de conexão -->
                 <?php echo "Erro: ".$u->msgERRO; ?>
            </div>
            <?php
        endif;
    else:
        ?>
        <!-- Exibe mensagem de erro se os campos não estiverem preenchidos -->
        <div class="msg-erro">
            Preencha Todos os Campos!
        </div>
        <?php
    endif;
endif;
?>
</body>
</html>
