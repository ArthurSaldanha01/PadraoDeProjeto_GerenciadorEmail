<?php
class TextoDownloadStrategy implements DownloadStrategy {
    public function baixar($emailData) {
        $fileName = "email_" . uniqid() . ".txt";
        $content = "Assunto: " . $emailData['assunto'] . "\n";
        $content .= "Remetente: " . $emailData['remetente'] . "\n";
        $content .= "Destinatário: " . $emailData['destinatario'] . "\n";
        $content .= "Mensagem:\n" . $emailData['texto'] . "\n";

        header("Content-Type: text/plain");
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        echo $content;
        exit;
    }
}
?>
