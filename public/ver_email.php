<?php
include('protect.php');
include('./config/db.php');
include_once './fabrica/emailSimplesCreator.php';
include_once './fabrica/emailTemplateCreator.php';
include_once './fabrica/emailTemplateConcreto.php';
include_once './facade/EmailFacade.php';
include_once './Observer/LogObserver.php';

$emailFacade = new EmailFacade($mysqli);


$logObserver = new LogObserver();
$emailFacade->addObserver($logObserver);

if (isset($_GET['id'])) {
    $email_id = $_GET['id'];

    $sql = "SELECT remetente, destinatario, assunto, texto, tipo FROM email WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $email_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $email = $result->fetch_assoc();
} else {
    echo "ID do e-mail não fornecido.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['excluir'])) {
    if ($emailFacade->deletarEmail($email_id)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Erro ao excluir o e-mail.";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['salvar'])) {
    $remetente = $_POST['remetente'];
    $destinatario = $_POST['destinatario'];
    $assunto = $_POST['assunto'];
    $texto = $_POST['texto'];
    $tipo_email = $_POST['tipo_email'];

    try {
        if ($emailFacade->atualizarEmail($email_id, $remetente, $destinatario, $assunto, $texto, $tipo_email)) {
            header("Location: ver_email.php?id=$email_id&status=sucesso");
            exit;
        } else {
            echo "Erro ao atualizar o e-mail.";
        }
    } catch (Exception $e) {
        echo "<script>alert('" . $e->getMessage() . "');</script>";
    }
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

            <?php if (isset($_GET['status']) && $_GET['status'] == 'sucesso'): ?>
                <p style="color: green;">E-mail atualizado com sucesso!</p>
            <?php endif; ?>

            <div class="email-mensagem">
                <h2>Mensagem do E-mail:</h2>
                <?php if ($email['tipo'] === 'template'): ?>
                    <?php
                    $templateEmail = new emailTemplateConcreto(
                        $email['remetente'],
                        $email['destinatario'],
                        $email['assunto'],
                        $email['texto'],
                        $mysqli
                    );
                    $templateDinamico = $templateEmail->getTemplate();
                    ?>
                    <p><?php echo nl2br(htmlspecialchars($templateDinamico)); ?></p>
                <?php else: ?>
                    <p><?php echo nl2br(htmlspecialchars($email['texto'])); ?></p>
                <?php endif; ?>
            </div>

            <form method="POST">
                <label for="remetente">Remetente:</label>
                <input type="text" id="remetente" name="remetente" value="<?php echo htmlspecialchars($email['remetente']); ?>" required>

                <label for="destinatario">Destinatário:</label>
                <input type="text" id="destinatario" name="destinatario" value="<?php echo htmlspecialchars($email['destinatario']); ?>" required>

                <label for="assunto">Assunto:</label>
                <input type="text" id="assunto" name="assunto" value="<?php echo htmlspecialchars($email['assunto']); ?>" required>

                <label for="texto">Texto do E-mail:</label>
                <textarea id="texto" name="texto" rows="8" required><?php echo htmlspecialchars($email['texto']); ?></textarea>

                <label for="tipo_email">Tipo de Email</label>
                <select name="tipo_email" id="tipo_email">
                    <option value="simples" <?php echo $email['tipo'] === 'simples' ? 'selected' : ''; ?>>Simples</option>
                    <option value="template" <?php echo $email['tipo'] === 'template' ? 'selected' : ''; ?>>Com Template</option>
                </select>

                <div class="acoes">
                    <button type="submit" name="salvar">Salvar Alterações</button>
                    <button type="submit" name="excluir" onclick="return confirm('Tem certeza que deseja excluir este e-mail?');" style="background-color: red;">Excluir E-mail</button>
                </div>
            </form>
        <?php else: ?>
            <p>E-mail não encontrado.</p>
        <?php endif; ?>
    </div>

</body>
</html>
