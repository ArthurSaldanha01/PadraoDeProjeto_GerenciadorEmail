<?php
include('config/db.php');

if (isset($_POST['email']) || isset($_POST['senha'])) {
    
    if(strlen($_POST['email']) == 0) {
        echo "Preencha seu e-mail";
    } else if(strlen($_POST['senha']) == 0) {
        echo "Preencha sua senha";
    } else {

        $email = $mysqli->real_escape_string($_POST['email']);
        $senha = $mysqli->real_escape_string($_POST['senha']);

        $sql_code = "SELECT * FROM usuario WHERE email = '$email' AND senha = '$senha'";
        $sql_query = $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);

        $quantidade = $sql_query->num_rows;

        if ($quantidade == 1) {
            
            $usuario = $sql_query->fetch_assoc();

            if (!isset($_SESSION)) {
                session_start();
            }

            $_SESSION['id'] = $usuario['id'];
            $_SESSION['nome'] = $usuario['nome'];
            $_SESSION['email'] = $usuario['email'];


            header("Location: index.php");

        } else {
            echo "Falha ao logar! E-mail ou senha incorretos";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso Gerenciador de Email</title>
    <link rel="stylesheet" href="./css/novologin.css">
</head>
<body>
    <section class="container_acesso">

        <div class="caixa1">

            <h1>Fazer login</h1>

        </div>

        <div class="caixa2">

            <form action="" method="POST">

                <label>E-mail</label>
                <br>
                <input type="text" name="email"><br>
                <br>
                <label>Senha</label>
                <br>
                <input type="password" name="senha"><br>
                <br>
                <button class="botao_login">Entrar</button>

            </form>

            <p class="cadastro">
                Crie sua conta! <a href="cadastro.php">Cadastre-se</a>
            </p>

        </div>

    </section>
</body>
</html>