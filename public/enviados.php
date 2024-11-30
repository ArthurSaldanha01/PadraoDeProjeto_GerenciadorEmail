<?php
include('protect.php');
include('./config/db.php');

$usuario_email = $_SESSION['email'];

$sql = "SELECT id, destinatario, assunto, texto, envio FROM email WHERE remetente = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $usuario_email);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-mails Enviados</title>
    <link rel="stylesheet" href="./css/index.css">
</head>
<body>

    <div class="barra-lateral">
        <ul>
            <li><a href="index.php">Caixa de entrada</a></li>
            <li><a href="enviados.php">Enviados</a></li>

            <div class="botao-escrever">
                <button onclick="window.location.href='escrever.php'">Escrever</button>
            </div>
        </ul>
    </div>

    <div class="conteudo">
        <div class="barra-superior">
            <p>Bem-vindo à caixa de e-mails enviados, <?php echo $_SESSION['nome']; ?></p>
            <button onclick="window.location.href='logout.php'">Sair</button>
        </div>

        <?php
            // Exibir os e-mails enviados
            if ($result->num_rows > 0) {
                while ($email = $result->fetch_assoc()) {
                    echo "<a class='link-email' href='ver_email.php?id=" . $email['id'] . "'>
                            <div class='item-email'>
                                <span class='remetente'>" . htmlspecialchars($email['destinatario']) . "</span>
                                <span class='assunto'>" . htmlspecialchars($email['assunto']) . "</span>
                                <span class='data'>" . htmlspecialchars(date('d M Y', strtotime($email['envio']))) . "</span>
                            </div>
                        </a>";
                }
            } else {
                echo "<p>Você ainda não enviou nenhum e-mail.</p>";
            }
        ?>
        
    </div>

</body>
</html>
