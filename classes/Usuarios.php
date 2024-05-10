<?php

class Usuarios {

    private $pdo; 
    public $msgERRO = "";

    public function conectar($nome, $host, $usuario, $senha){

        try {
            $this->pdo = new PDO("mysql:dbname=".$nome.";host=".$host,$usuario,$senha);
        }
        catch (PDOException $e) {
            $this->msgERRO = $e->getMessage();
        }
    }

    public function cadastrar($nome, $telefone, $email, $senha, $genero, $idade, $endereco, $bairro, $cidade, $estado, $bio, $imagem){
        
        $sql = $this->pdo->prepare("SELECT id_usuario FROM usuarios WHERE email = :e");
        $sql->bindValue(":e", $email);
        $sql->execute();

        if ($sql->rowCount() > 0) {
            return false; 
        } else {
            $sql = $this->pdo->prepare("INSERT INTO usuarios (nome, telefone, email, senha, sexo, nascimento, endereco, bairro, cidade, estado, bio, imagem) VALUES (:n, :t, :e, :s, :g, :i, :en, :b, :ci, :es, :bi, :im)");
            $sql->bindValue(":n", $nome);
            $sql->bindValue(":t", $telefone);
            $sql->bindValue(":e", $email);
            $sql->bindValue(":s", md5($senha));
            $sql->bindValue(":g", $genero);
            $sql->bindValue(":i", $idade);
            $sql->bindValue(":en", $endereco);
            $sql->bindValue(":b", $bairro);
            $sql->bindValue(":ci", $cidade);
            $sql->bindValue(":es", $estado);
            $sql->bindValue(":bi", $bio);
            $sql->bindValue(":im", $imagem);

            $sql->execute();
            return true;
        }
    }
    
    public function logar($email, $senha){

        $sql= $this->pdo->prepare("SELECT id_usuario FROM usuarios WHERE email = :e AND senha = :s");
        $sql->bindValue(":e", $email);
        $sql->bindValue(":s", md5($senha));
        $sql->execute();

        if($sql->rowCount() > 0) {
            $dado = $sql->fetch();
            session_start();
            $_SESSION['id_usuario'] = $dado['id_usuario'];
            return true; //logado com Sucesso
        } else {
            return false;
        }
    }

    public function mostrar($id_usuario){
        $sql = $this->pdo->prepare("SELECT * FROM usuarios WHERE id_usuario = :id");
        $sql->bindValue(":id", $id_usuario);
        $sql->execute();
        if ($sql->rowCount() > 0) {
            // Usuário encontrado, exibe os dados
            $usuario = $sql->fetch();
            return $usuario;
        }
        return null; // Se nenhum usuário encontrado
    }

    public function atualizar($id_usuario, $nome, $telefone, $email, $senha, $genero, $idade, $endereco, $bairro, $cidade, $estado, $bio, $imagem){
        
        $sql = $this->pdo->prepare("SELECT id_usuario FROM usuarios WHERE email = :e AND id_usuario != :id");
        $sql->bindValue(":e", $email);
        $sql->bindValue(":id", $id_usuario);
        $sql->execute();
    
        if ($sql->rowCount() > 0) {
            return false; 
        } else {
            $sql = $this->pdo->prepare("UPDATE usuarios SET nome = :n, telefone = :t, email = :e, senha = :s, sexo = :g, nascimento = :i, endereco = :en, bairro = :b, cidade = :ci, estado = :es, bio = :bi, imagem = :im WHERE id_usuario = :id");
            $sql->bindValue(":id", $id_usuario);
            $sql->bindValue(":n", $nome);
            $sql->bindValue(":t", $telefone);
            $sql->bindValue(":e", $email);
            $sql->bindValue(":s", md5($senha));
            $sql->bindValue(":g", $genero);
            $sql->bindValue(":i", $idade);
            $sql->bindValue(":en", $endereco);
            $sql->bindValue(":b", $bairro);
            $sql->bindValue(":ci", $cidade);
            $sql->bindValue(":es", $estado);
            $sql->bindValue(":bi", $bio);
            $sql->bindValue(":im", $imagem);
    
            $sql->execute();
            return true;
        }
    }
    
}

?>
