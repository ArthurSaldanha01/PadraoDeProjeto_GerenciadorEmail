<?php
include('./config/db.php'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    

    $sql = "SELECT id FROM usuario WHERE email = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        echo "Este e-mail já está cadastrado.";
    } else {

        $sql = "INSERT INTO usuario (nome, email, senha) VALUES (?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sss", $nome, $email, $senha);

        if ($stmt->execute()) {
            echo "Cadastro realizado com sucesso! <a href='login.php'>Faça login</a>";
        } else {
            echo "Erro ao cadastrar usuário. Por favor, tente novamente.";
        }
    }

    $stmt->close();
    $mysqli->close();
} else {

    header("Location: cadastro.php");
    exit();
}
?>
