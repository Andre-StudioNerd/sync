<?php

class Usuarios {

    private $pdo; // Variável para armazenar a conexão PDO com o banco de dados
    public $msgERRO = ""; // Variável para armazenar mensagens de erro

    // Método para estabelecer a conexão com o banco de dados
    public function conectar($nome, $host, $usuario, $senha){

        try {
            $this->pdo = new PDO("mysql:dbname=".$nome.";host=".$host,$usuario,$senha); // Conecta ao banco de dados
        }
        catch (PDOException $e) {
            $this->msgERRO = $e->getMessage(); // Captura e armazena mensagens de erro, se houver
        }
    }

    // Método para cadastrar um novo usuário
    public function cadastrar($nome, $telefone, $email, $senha, $genero, $idade, $endereco, $bairro, $cidade, $estado, $bio, $imagem){
        
        // Verifica se o email já está cadastrado no banco de dados
        $sql = $this->pdo->prepare("SELECT id_usuario FROM usuarios WHERE email = :e");
        $sql->bindValue(":e", $email);
        $sql->execute();

        // Se o email já estiver cadastrado, retorna false
        if ($sql->rowCount() > 0) {
            return false; 
        } else {
            // Caso contrário, insere os dados do novo usuário no banco de dados
            $sql = $this->pdo->prepare("INSERT INTO usuarios (nome, telefone, email, senha, sexo, nascimento, endereco, bairro, cidade, estado, bio, imagem) VALUES (:n, :t, :e, :s, :g, :i, :en, :b, :ci, :es, :bi, :im)");
            $sql->bindValue(":n", $nome);
            $sql->bindValue(":t", $telefone);
            $sql->bindValue(":e", $email);
            $sql->bindValue(":s", md5($senha)); // A senha é armazenada de forma criptografada
            $sql->bindValue(":g", $genero);
            $sql->bindValue(":i", $idade);
            $sql->bindValue(":en", $endereco);
            $sql->bindValue(":b", $bairro);
            $sql->bindValue(":ci", $cidade);
            $sql->bindValue(":es", $estado);
            $sql->bindValue(":bi", $bio);
            $sql->bindValue(":im", $imagem);

            $sql->execute(); // Executa a inserção no banco de dados
            return true; // Retorna true para indicar que o cadastro foi realizado com sucesso
        }
    }
    
    // Método para autenticar o login do usuário
    public function logar($email, $senha){

        $sql= $this->pdo->prepare("SELECT id_usuario FROM usuarios WHERE email = :e AND senha = :s");
        $sql->bindValue(":e", $email);
        $sql->bindValue(":s", md5($senha)); // A senha é comparada com a versão criptografada armazenada no banco de dados
        $sql->execute();

        // Se houver um registro correspondente no banco de dados, o usuário é considerado autenticado
        if($sql->rowCount() > 0) {
            $dado = $sql->fetch();
            session_start();
            $_SESSION['id_usuario'] = $dado['id_usuario']; // Inicia a sessão e armazena o ID do usuário logado
            return true; // Retorna true para indicar que o login foi bem-sucedido
        } else {
            return false; // Retorna false se o login falhar
        }
    }

    // Método para exibir informações de um usuário específico
    public function mostrar($id_usuario){
        $sql = $this->pdo->prepare("SELECT * FROM usuarios WHERE id_usuario = :id");
        $sql->bindValue(":id", $id_usuario);
        $sql->execute();

        // Se houver um registro correspondente, os dados do usuário são retornados
        if ($sql->rowCount() > 0) {
            $usuario = $sql->fetch();
            return $usuario;
        }
        return null; // Retorna null se o usuário não for encontrado
    }

    // Método para atualizar as informações de um usuário
    public function atualizar($id_usuario, $nome, $telefone, $email, $senha, $genero, $idade, $endereco, $bairro, $cidade, $estado, $bio, $imagem){
        
        // Verifica se o novo email já está cadastrado para outro usuário
        $sql = $this->pdo->prepare("SELECT id_usuario FROM usuarios WHERE email = :e AND id_usuario != :id");
        $sql->bindValue(":e", $email);
        $sql->bindValue(":id", $id_usuario);
        $sql->execute();
    
        // Se o email já estiver cadastrado para outro usuário, retorna false
        if ($sql->rowCount() > 0) {
            return false; 
        } else {
            // Caso contrário, atualiza os dados do usuário no banco de dados
            $sql = $this->pdo->prepare("UPDATE usuarios SET nome = :n, telefone = :t, email = :e, senha = :s, sexo = :g, nascimento = :i, endereco = :en, bairro = :b, cidade = :ci, estado = :es, bio = :bi, imagem = :im WHERE id_usuario = :id");
            $sql->bindValue(":id", $id_usuario);
            $sql->bindValue(":n", $nome);
            $sql->bindValue(":t", $telefone);
            $sql->bindValue(":e", $email);
            $sql->bindValue(":s", md5($senha)); // A senha é armazenada de forma criptografada
            $sql->bindValue(":g", $genero);
            $sql->bindValue(":i", $idade);
            $sql->bindValue(":en", $endereco);
            $sql->bindValue(":b", $bairro);
            $sql->bindValue(":ci", $cidade);
            $sql->bindValue(":es", $estado);
            $sql->bindValue(":bi", $bio);
            $sql->bindValue(":im", $imagem);
    
            $sql->execute(); // Executa a atualização no banco de dados
            return true; // Retorna true para indicar que a atualização foi realizada com sucesso
        }
    }
    
}

?>
