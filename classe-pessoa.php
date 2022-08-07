<?php

class Pessoa
{
    private $pdo;

    public function __construct(string $dbname, string $host, string $user, string $senha)
    {
        try
        {
            $this->pdo = new PDO("mysql:dbname=". $dbname . ";host=" . $host, $user, $senha);
        } 
        catch (PDOException $e) {
            echo "Erro no banco de dados" . $e->getMessage();
            exit();
        }
        catch (Exception $e) {
            echo "Erro genérico" . $e->getMessage();
            exit();
        }    
        
    }
    //Função para localizar os dados e deixar no canto direito da tela do formulário.
    public function buscarDados() : array
    {
        $res = array();
        $cmd = $this->pdo->query("SELECT * FROM pessoa ORDER BY nome");
        $res = $cmd->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    public function cadastrarPessoa(string $nome, string $telefone, string $email) : bool
    {
        //ANTES DE CADASTRAR PRECISAMOS VERIFICAR SE JÁ TEM O E-MAIL CADASTRADO!
        $cmd =$this->pdo->prepare("SELECT id FROM pessoa WHERE email = :e");
        $cmd->bindValue(":e", $email);
        $cmd->execute();  

        if($cmd->rowCount() > 0) { 
            return false; //email já existe no banco

        }else { // email não foi cadastrado anteriormente.
            $cmd = $this->pdo->prepare("INSERT INTO pessoa(nome, telefone, email) VALUES (:n, :t, :e)");
            $cmd->bindValue(":n", $nome);
            $cmd->bindValue(":t", $telefone);
            $cmd->bindValue(":e", $email);
            $cmd->execute();
            return true;
        }      
    }
    public function excluirPessoa($id)
    {
        $cmd =$this->pdo->prepare("DELETE FROM pessoa WHERE id=:id");
        $cmd->bindValue(":id", $id);
        $cmd->execute();  
    }

    public function buscarDadosPessoa($id) : array
    {
        $res = array();
        $cmd = $this->pdo->prepare("SELECT * FROM pessoa WHERE id = :id");
        $cmd->bindValue(":id", $id);
        $cmd->execute();
        $res = $cmd->fetch(PDO::FETCH_ASSOC);
        return $res;
    }

    public function atualizarDados(int $id, string $nome, string $telefone, string $email) 
    {
        $cmd = $this->pdo->prepare("UPDATE pessoa SET nome = :n, telefone = :t, email = :e WHERE id = :id");
        $cmd->bindValue(":n", $nome);
        $cmd->bindValue(":t", $telefone);
        $cmd->bindValue(":e", $email);
        $cmd->bindValue(":id", $id); 
        $cmd->execute();
    }
}