<?php
session_start();

// Verifica se o usuário está autenticado
if (!isset($_SESSION['id_usuario'])) {
    header("location: index.php");
    exit;
}

// Inclui a classe de Usuários
require_once 'classes/Usuarios.php';
require_once 'classes/Security.php';
$u = new Usuarios();

// Inclui o arquivo de conexão com o banco de dados
require_once('conexao.php');

// Conecta-se ao banco de dados
$u->conectar($database_name, $database_host, $database_user, $database_password);

// Obtém o ID do usuário da sessão
$id_usuario = $_SESSION['id_usuario'];

// Obtém os dados do usuário pelo ID
$dados_usuario = $u->mostrar($id_usuario);

// Mensagem de sucesso após a atualização
$msg_sucesso = '';

// Verifica se o formulário foi enviado para atualização
if(isset($_POST['atualizar'])) {
    // Obtém os dados do formulário
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

    // Processa o arquivo de imagem se houver
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
        // Se nenhum arquivo de imagem foi enviado, defina o caminho como o caminho atual
        $caminho_imagem = $dados_usuario['imagem'];
    }

    // Verifica se os campos obrigatórios estão preenchidos
    if(!empty($nome) && !empty($telefone) && !empty($email)&& !empty($senha) && !empty($conf_senha)) {
        // Verifica se as senhas correspondem
        if($senha == $conf_senha) {
            // Atualiza os dados do usuário no banco de dados
            if($u->atualizar($id_usuario, $nome, $telefone, $email, $senha, $genero, $idade, $endereco, $bairro, $cidade, $estado, $bio, $caminho_imagem)) {
                $msg_sucesso = 'Dados atualizados com sucesso!';
                // Atualiza os dados do usuário com os novos dados
                $dados_usuario = $u->mostrar($id_usuario);
            } else {
                $msg_sucesso = 'O email já está em uso por outro usuário.';
            }
        } else {
            $msg_sucesso = 'Senha e Confirmar Senha não correspondem!';
        }
    } else {
        $msg_sucesso = 'Preencha todos os campos obrigatórios!';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Atualizar Dados</title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="apple-touch-icon" sizes="180x180" href="imagens/icon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="imagens/icon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="imagens/icon/favicon-16x16.png">
    <link rel="manifest" href="imagens/icon/site.webmanifest">
    <link rel="mask-icon" href="imagens/icon/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="mask-icon" href="imagens/icon/safari-pinned-tab.svg" color="#5bbad5">
    
    <!-- Links para os ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
<?php if (!empty($msg_sucesso)): ?>
        <div id="msg-sucesso"><?php echo $msg_sucesso; ?></div>
    <?php endif; ?>
<div id="corpo-form-cad">
    <h1 class="title"><i class="fas fa-user-edit"></i> Atualizar Dados</h1>
    
    <form method="POST" enctype="multipart/form-data">
        <!-- Campos preenchidos com os dados do usuário -->
        <label for="nome" class="labelInput">Nome *</label>
        <input type="text" name="nome" value="<?php echo $dados_usuario['nome']; ?>" maxlength="30"/>

        <!-- Outros campos preenchidos com os dados do usuário -->
        <label for="telefone" class="labelInput">Telefone *</label>
        <input type="text" name="telefone" value="<?php echo $dados_usuario['telefone']; ?>" maxlength="30"/>
        
        <label for="email" class="labelInput">E-mail *</label>
        <input type="email" name="email" value="<?php echo $dados_usuario['email']; ?>" maxlength="40"/>

        <label for="senha" class="labelInput">Senha *</label>
        <input type="password" name="senha" placeholder="Nova senha" maxlength="15"/>

        <input type="password" name="conf_senha" placeholder="Confirmar Senha"/>

        <label for="genero" class="labelInput">Gênero</label>
        <select name="genero" id="genero">
            <option value="nao informado" <?php if($dados_usuario['sexo'] == "nao informado") echo "selected"; ?>>Escolha</option>
            <option value="Masculino" <?php if($dados_usuario['sexo'] == "Masculino") echo "selected"; ?>>Masculino</option>
            <option value="Feminino" <?php if($dados_usuario['sexo'] == "Feminino") echo "selected"; ?>>Feminino</option>
            <option value="Outro" <?php if($dados_usuario['sexo'] == "Outro") echo "selected"; ?>>Outros</option>
        </select>
        
        <label for="idade" class="labelInput">Idade</label>
        <input type="text" name="idade" value="<?php echo $dados_usuario['nascimento']; ?>" id="data" placeholder="Data de Nascimento">
        
        <label for="endereco" class="labelInput">Endereço</label>
        <input type="text" name="endereco" value="<?php echo $dados_usuario['endereco']; ?>" placeholder="Seu endereço" maxlength="160"/>
        
        <label for="bairro" class="labelInput">Bairro</label>
        <input type="text" name="bairro" value="<?php echo $dados_usuario['bairro']; ?>" placeholder="Seu Bairro" maxlength="100"/>
        
        <label for="cidade" class="labelInput">Cidade</label>
        <input type="text" name="cidade" value="<?php echo $dados_usuario['cidade']; ?>" placeholder="Sua Cidade" maxlength="60"/>
        
        <label for="estado" class="labelInput">Estado</label>
        <input type="text" name="estado" value="<?php echo $dados_usuario['estado']; ?>" placeholder="Seu Estado" maxlength="60"/>
        
        <label for="bio" class="labelInput">Biografia</label>
        <textarea name="bio" placeholder="Descreva sobre Você"><?php echo $dados_usuario['bio']; ?></textarea>
        
        <label for="bio" class="labelInput">Foto do perfil</label>
        <label for="imagem" class="custom-file-upload">Escolher Arquivo</label>
        <input type="file" id="imagem" name="imagem" class="input-file" accept="image/*" onchange="updateFileName(this)">
        <span id="file-name" class="labelInput">Nenhum arquivo selecionado</span>

        
        <!-- Botão para enviar o formulário -->
        <input type="submit" value="GRAVAR" name="atualizar" maxlength="15"/>
    </form>
    <p><a href="perfil" class="buttom_cad">PERFIL <i class="fa fa-user" aria-hidden="true"></i></a></p>
    <!-- Link para sair -->
    <p><a href="sair" class="buttom_cad">SAIR <i class="fa fa-times" aria-hidden="true"></i></a></p>
</div>

</body>
</html>
