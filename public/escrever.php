<?php
include('protect.php'); 
include('./config/db.php');
include_once './facade/EmailFacade.php';
include_once './observer/LogObserver.php';

if (!isset($_SESSION['email'])) {
    die("Erro: O email do usuário não está definido.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $facade = new EmailFacade($mysqli);

    $logObserver = new LogObserver('log.txt');

    $facade->addObserver($logObserver);

    try {
        $remetente = $_SESSION['email'];
        $destinatario = $_POST['destinatario'];
        $assunto = $_POST['assunto'];
        $texto = $_POST['texto'];
        $usuario_id = $_SESSION['id'];
        $envio = date('Y-m-d H:i:s');
        $modificado = 0;
        $tipo_email = $_POST['tipo_email'];
        $template = null;

        $destinatario_id = $facade->validarDestinatario($destinatario);

        if ($facade->adicionarEmail($remetente, $destinatario, $assunto, $texto, $tipo_email, $usuario_id, $envio, $modificado, $template)) {
            echo "<script>alert('Email enviado com sucesso!'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Erro ao enviar o email.');</script>";
        }
    } catch (Exception $e) {
        echo "<script>alert('" . $e->getMessage() . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escrever Email</title>
    <link rel="stylesheet" href="./css/escrever.css">
</head>
<body>
    <div class="container">
        <h2>Escrever Email</h2>
        <form action="escrever.php" method="POST">
            <label for="destinatario">Destinatário</label>
            <input type="email" id="destinatario" name="destinatario" required>

            <label for="assunto">Assunto</label>
            <input type="text" id="assunto" name="assunto" required>

            <label for="texto">Mensagem</label>
            <textarea name="texto" id="texto" rows="8" required></textarea>

            <label for="tipo_email">Tipo de Email</label>
            <select name="tipo_email" id="tipo_email">
                <option value="simples">Simples</option>
                <option value="template">Com Template</option>
            </select>

            <button type="submit">Enviar</button>
        </form>

        <a href="index.php" class="btn-voltar">Voltar</a>
    </div>
</body>
</html>
