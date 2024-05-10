<?php
require_once 'classes/Usuarios.php';
$u = new Usuarios();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <title>Teste Sync</title>
    
    <link rel="stylesheet" href="style/main.css">
    
    <link rel="apple-touch-icon" sizes="180x180" href="imagens/icon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="imagens/icon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="imagens/icon/favicon-16x16.png">
    <link rel="manifest" href="imagens/icon/site.webmanifest">
    <link rel="mask-icon" href="imagens/icon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>

<div id="corpo-form">
    <h1> <i class="fas fa-users"></i> PERFIL SYNC</h1>

    <form method="POST">
        <input type="email" name="email" placeholder="Usuário"/>
        <input type="password" name="senha" placeholder="Senha"/>
        <input type="submit" value="ACESSAR" name=""/>
        <p class="centralize"><img src="imagens/logo.png" alt="logo" class="image_logo"></p>
        <p class="line_class"> Ainda não é inscrito?</p>
        <p><a href="cadastro" class="buttom_class">INSCREVA-SE! <i class="fa fa-address-book" aria-hidden="true"></i></a></p>
    </form>
    
<div>

</body>

<?php
require_once('conexao.php');

if(isset($_POST['email'])):
    
    $email = addslashes($_POST['email']);
    $senha = addslashes($_POST['senha']);

    if(!empty($email) && !empty($senha)):
      

        $u->conectar($database_name, $database_host, $database_user, $database_password);

        if($u->msgERRO == ""):

            if($u->logar($email, $senha)):

                header("location: perfil");

        
            else: 

                ?>

                <div class="msg-erro">
                    E-mail e/ou Senha Incorretos!
                </div>

                <?php

            endif;

        else:

            ?>
           
            <div class="msg-erro"> 
                
                 <?php echo "Erro: ".$u->msgERRO; ?>
            
            </div>

            <?php


        
        endif;

    else:

        ?>

            <div class="msg-erro">
                Preencha Todos os Campos!
            </div>

        <?php




    endif;


endif;



?>

</html>
