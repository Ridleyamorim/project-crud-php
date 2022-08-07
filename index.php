<?php

require_once "classe-pessoa.php";
$p = new Pessoa("crudpdo", "localhost", "root", "");

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projeto CRUD-PDO</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <?php
        if (isset($_POST['nome'])) { //clicou no botão cadastrar ou atualizar

            //--------------------- ATUALIZAR --------------------------
            if (isset($_GET['id_update']) and !empty($_GET['id_update'])) {

                $id_upd = addslashes($_GET['id_update']);
                $nome = addslashes($_POST['nome']);
                $telefone = addslashes($_POST['telefone']);
                $email = addslashes($_POST['email']);
                if (!empty($nome) and !empty($telefone) and !empty($email)) {
                    $p->atualizarDados($id_upd, $nome, $telefone, $email);
                    header("location: index.php");
                }
                else {
                    ?>
                        <div class="aviso">
                            <h4>Preencha todos os campos!</h4>
                        </div>
                    <?php
                } 
            } 
            //--------------------- CADASTRAR --------------------------
            else {
                
                $nome = addslashes($_POST['nome']);
                $telefone = addslashes($_POST['telefone']);
                $email = addslashes($_POST['email']);
                if (!empty($nome) and !empty($telefone) and !empty($email)) {
                    if (!$p->cadastrarPessoa($nome, $telefone, $email)) {
                        ?>
                            <div class="aviso">
                                <h4>E-mail já se encontra cadastrado!</h4>
                            </div>
                        <?php
                    }
                }
                else {
                    ?>
                        <div class="aviso">
                            <h4>Preencha todos os campos!</h4>
                        </div>
                    <?php
                }
            }
        }
    ?>
    <?php

        if (isset($_GET['id_update'])) {  //se a pessoa clicou em editar.
            $id_up = addslashes($_GET['id_update']);
            $res = $p->buscarDadosPessoa($id_up);
        }
    ?>    
    <section id="esquerda">
        <form method="POST">
            <h2>CADASTRAR PESSOA</h2>
            <label" for="nome">Nome</label>
            <input type="text" name="nome" id="nome"
            value="<?php if (isset($res)){ echo $res['nome'];}?>"
            >
            <label" for="telefone">Telefone</label>
            <input type="tel" name="telefone" id="telefone"
            value="<?php if (isset($res)){ echo $res['telefone'];}?>"
            >
            <label" for="email">E-mail</label>
            <input type="email" name="email" id="email"
            value="<?php if (isset($res)){ echo $res['email'];}?>"
            >
            <input type="submit" 
            value="<?php if (isset($res)) {echo "Atualizar";
            } else {echo "Cadastrar";} ?>" id="cadastrar"> <!-- value irá mudar nome do botão -->
        </form> 
    </section>
    <section id="direita">
        <table>
            <tr id="titulo">
                <td>Nome</td>
                <td>Telefone</td>
                <td colspan="2">E-mail</td>
            </tr>
        <?php
            $dados = $p->buscarDados();
            if (count($dados) > 0) {
                for ($i=0; $i < count($dados); $i++) { 
                    echo "<tr>";
                    foreach ($dados[$i] as $k => $v) {
                        if ($k != "id") {
                            echo "<td>" . $v . "</td>";
                        }
                    }
        ?>
                        <td>
                            <a href="index.php?id_update=<?php echo $dados[$i]["id"];?>">Editar</a>
                            <a href="index.php?id=<?php echo $dados[$i]['id'];?>">Excluir</a>
                        </td>
                    <?php
                    echo "</tr>";
                }
            }
    else {
        ?>  
        </table>
            <div class="aviso">
                <h4>Ainda não há pessoas cadastradas!</h4>
            </div>
        <?php
    }
        ?>
    </section>
</body>
</html>

<?php

    if (isset($_GET['id'])) {
        $id_pessoa = addslashes($_GET['id']);
        $p->excluirPessoa($id_pessoa);
        header("location: index.php");
    }

?>