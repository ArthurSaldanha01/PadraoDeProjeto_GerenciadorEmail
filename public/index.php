<?php
include('protect.php');
include('./config/db.php');

// Usar o email do usuário logado para pegar os e-mails recebidos
$sql = "SELECT id, remetente, destinatario, assunto, texto, modificado, envio FROM email WHERE destinatario = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $_SESSION['email']);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel</title>
    <link rel="stylesheet" href="./css/index.css">
</head>
<body>

    <div class="barra-lateral">
        <ul>
                <li><a href="#">Caixa de entrada</a></li>
                <li><a href="enviados.php">Enviados</a></li>

                <div class="botao-escrever">
                    <button onclick="window.location.href='escrever.php'">Escrever</button>
                </div>
        </ul>
    </div>

    <div class="conteudo">
        <div class="barra-superior">
            <p>Bem-vindo à caixa de entrada, <?php echo $_SESSION['nome']; ?></p>
            <button onclick="window.location.href='logout.php'">Sair</button>
        </div>

        <?php
            // Exibir e-mails recebidos
            while ($email = $result->fetch_assoc()) {
                echo "<a class='link-email' href='visualizar.php?id=" . $email['id'] . "'>
                        <div class='item-email'>
                            <span class='remetente'>" . htmlspecialchars($email['remetente']) . "</span>
                            <span class='assunto'>" . htmlspecialchars($email['assunto']) . "</span>
                            <span class='data'>" . htmlspecialchars(date('d M Y', strtotime($email['envio']))) . "</span>
                        </div>
                    </a>";
            }
        ?>
        
    </div>

</body>
</html>
