<?php
include('protect.php');
include('./config/db.php');
include_once './Strategy/DownloadStrategy.php';
include_once './Strategy/TextoDownloadStrategy.php';
include_once './Strategy/HtmlDownloadStrategy.php';
include_once './Strategy/DownloadContext.php';

if (!isset($_GET['id']) || !isset($_GET['formato'])) {
    die("ID do e-mail ou formato não fornecido.");
}

$email_id = intval($_GET['id']);
$formato = $_GET['formato'];

$sql = "SELECT remetente, destinatario, assunto, texto, tipo, template FROM email WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $email_id);
$stmt->execute();
$result = $stmt->get_result();
$email = $result->fetch_assoc();

if (!$email) {
    die("E-mail não encontrado.");
}

$context = new DownloadContext();

switch ($formato) {
    case 'txt':
        $context->setStrategy(new TextoDownloadStrategy());
        break;
    case 'html':
        $context->setStrategy(new HtmlDownloadStrategy());
        break;
    default:
        die("Formato de download inválido.");
}

$emailData = [
    'remetente' => $email['remetente'],
    'destinatario' => $email['destinatario'],
    'assunto' => $email['assunto'],
    'texto' => $email['tipo'] === 'template' ? $email['template'] : $email['texto']
];

$context->baixarEmail($emailData);
?>
