<?php
require_once 'classes/Usuarios.php';
require_once 'classes/Security.php';
$u = new Usuarios();

if(isset($_POST['cadastrar'])) {
    require_once('conexao.php');

    $nome = addslashes($_POST['nome']);
    $telefone = addslashes($_POST['telefone']);
    $email = addslashes($_POST['email']);
    $senha = addslashes($_POST['senha']);
    $conf_senha = addslashes($_POST['conf_senha']);
    $genero = addslashes($_POST['genero']);
    $idade = addslashes($_POST['idade']);
    $endereco = addslashes($_POST['endereco']);
    $bairro = addslashes($_POST['bairro']);
    $cidade = addslashes($_POST['cidade']);
    $estado = addslashes($_POST['estado']);
    $bio = addslashes($_POST['bio']);
    
    // Processamento do arquivo de imagem
    $imagem_nome = $_FILES['imagem']['name'];
    $imagem_tmp = $_FILES['imagem']['tmp_name'];

    if (!empty($imagem_nome) && !empty($imagem_tmp)) {
        // Obtém a extensão do arquivo de imagem
        $extensao = pathinfo($imagem_nome, PATHINFO_EXTENSION);

        // Define o novo nome do arquivo como o email do usuário com a extensão
        $novo_nome_imagem = $email . '.' . $extensao;

        // Define o caminho onde a imagem será salva no servidor
        $caminho_imagem = "imagens_up/" . $novo_nome_imagem;

        // Move o arquivo de imagem para o diretório de destino com o novo nome
        move_uploaded_file($imagem_tmp, $caminho_imagem);
    } else {
        // Se nenhum arquivo de imagem foi enviado, defina o caminho como vazio
        $caminho_imagem = "";
    }

    if(!empty($nome) && !empty($telefone) && !empty($email) && !empty($senha) && !empty($conf_senha)) {
        $u->conectar($database_name, $database_host, $database_user, $database_password);

        if($u->msgERRO == "") {
            if($senha == $conf_senha) {
                if($u->cadastrar($nome, $telefone, $email, $senha, $genero, $idade, $endereco, $bairro, $cidade, $estado, $bio, $caminho_imagem)) {
                    echo '<div id="msg-sucesso">Cadastrado com Sucesso! Acesse para entrar!</div>';
                } else {
                    echo '<div class="msg-erro">Email já cadastrado!</div>';
                }
            } else {
                echo '<div class="msg-erro">Senha e Confirmar Senha não correspondem!</div>';
            }
        } else {
            echo '<div class="msg-erro">Erro: '.$u->msgERRO.'</div>';
        }
    } else {
        echo '<div class="msg-erro">Preencha Todos os Campos Obrigátorios! (*)</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Teste Sync</title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="apple-touch-icon" sizes="180x180" href="imagens/icon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="imagens/icon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="imagens/icon/favicon-16x16.png">
    <link rel="manifest" href="imagens/icon/site.webmanifest">
    <link rel="mask-icon" href="imagens/icon/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="mask-icon" href="imagens/icon/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#data').mask('00/00/0000');
        });
    </script>
     <script>
        function updateFileName(input) {
    var span = document.getElementById('file-name');
    if (input.files.length > 0) {
        span.textContent = input.files[0].name;
    } else {
        span.textContent = 'Nenhum arquivo selecionado';
    }
}

    </script>
</head>
<body>

<div id="corpo-form-cad">
    <h1 class="title"><i class="fas fa-user-plus"></i> Cadastre-se</h1>
    <form method="POST" enctype="multipart/form-data">
        <label for="nome" class="labelInput">Nome *</label>
        <input type="text" name="nome" placeholder="Nome Completo" maxlength="30"/>
        <label for="telefone" class="labelInput">Telefone *</label>
        <input type="text" name="telefone" placeholder="Seu telefone" maxlength="30"/>
        <label for="email" class="labelInput">E-mail *</label>
        <input type="email" name="email" placeholder="Seu E-Mail" maxlength="40"/>
        <label for="password" class="labelInput">Senha *</label>
        <input type="password" name="senha" placeholder="Sua Senha" maxlength="15"/>
        <input type="password" name="conf_senha" placeholder="Confirmar Senha"/>
        <label for="genero" class="labelInput">Gênero</label>
        <select name="genero" id="genero">
            <option value="nao informado">Escolha</option>
            <option value="Masculino">Masculino</option>
            <option value="Feminino">Feminino</option>
            <option value="Outro">Outros</option>
        </select>
        <label for="idade" class="labelInput">Idade</label>
        <input type="text" name="idade" id="data" placeholder="Data de Nascimento">
        <label for="endereco" class="labelInput">Endereço</label>
        <input type="text" name="endereco" placeholder="Seu endereço" maxlength="160"/>
        <label for="bairro" class="labelInput">Bairro</label>
        <input type="text" name="bairro" placeholder="Seu Bairro" maxlength="100"/>
        <label for="cidade" class="labelInput">Cidade</label>
        <input type="text" name="cidade" placeholder="Sua Cidade" maxlength="60"/>
        <label for="estado" class="labelInput">Estado</label>
        <input type="text" name="estado" placeholder="Seu Estado" maxlength="60"/>
        <label for="bio" class="labelInput">Biografia</label>
        <textarea name="bio" placeholder="Descreva sobre Você"></textarea>
        <label for="bio" class="labelInput">Foto do perfil</label>
        <label for="imagem" class="custom-file-upload">Escolher Arquivo</label>
        <input type="file" id="imagem" name="imagem" class="input-file" accept="image/*" onchange="updateFileName(this)">
        <span id="file-name" class="labelInput">Nenhum arquivo selecionado</span>
        <input type="submit" value="GRAVAR" name="cadastrar" maxlength="15"/>
        
    </form>
    <p><a href="sair" class="buttom_cad">SAIR <i class="fa fa-times" aria-hidden="true"></i></a></p>
</div>

</body>
</html>
