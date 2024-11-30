<?php
include('protect.php');
include('./config/db.php');

if (isset($_GET['id'])) {
    $email_id = $_GET['id'];

    $sql = "SELECT remetente, destinatario, assunto, texto, tipo, template FROM email WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $email_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $email = $result->fetch_assoc();
} else {
    echo "ID do e-mail não fornecido.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Email</title>
    <link rel="stylesheet" href="./css/ver_email.css">
</head>
<body>

    <div class="conteudo">
        <h1>Visualizar Email</h1>

        <a href="index.php" class="botao-voltar">Voltar para Caixa de Entrada</a>

        <?php if ($email): ?>

            <div class="email-detalhes">
                <h2>Detalhes do E-mail:</h2>
                <p><strong>Remetente:</strong> <?php echo htmlspecialchars($email['remetente']); ?></p>
                <p><strong>Destinatário:</strong> <?php echo htmlspecialchars($email['destinatario']); ?></p>
                <p><strong>Assunto:</strong> <?php echo htmlspecialchars($email['assunto']); ?></p>
            </div>

            <div class="email-mensagem">
                <h2>Mensagem do E-mail:</h2>
                <?php if ($email['tipo'] === 'template'): ?>
                    <p><?php echo nl2br(htmlspecialchars($email['template'])); ?></p>
                <?php else: ?>
                    <p><?php echo nl2br(htmlspecialchars($email['texto'])); ?></p>
                <?php endif; ?>
            </div>

            <div class="email-download">
                <h2>Baixar E-mail:</h2>
                <form action="baixar_email.php" method="GET">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($email_id); ?>">
                    <label for="formato">Selecione o formato para download:</label>
                    <select name="formato" id="formato" required>
                        <option value="txt">TXT</option>
                        <option value="html">HTML</option>
                    </select>
                    <button type="submit">Baixar</button>
                </form>
            </div>

        <?php else: ?>
            <p>E-mail não encontrado.</p>
        <?php endif; ?>
        
    </div>

</body>
</html>
