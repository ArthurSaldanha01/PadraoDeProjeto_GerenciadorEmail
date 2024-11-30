<?php
class HtmlDownloadStrategy implements DownloadStrategy {
    public function baixar($emailData) {
        $fileName = "email_" . uniqid() . ".html";
        $content = "<html><body>";
        $content .= "<h1>" . htmlspecialchars($emailData['assunto']) . "</h1>";
        $content .= "<p><strong>Remetente:</strong> " . htmlspecialchars($emailData['remetente']) . "</p>";
        $content .= "<p><strong>Destinatário:</strong> " . htmlspecialchars($emailData['destinatario']) . "</p>";
        $content .= "<div><strong>Mensagem:</strong><br>" . nl2br(htmlspecialchars($emailData['texto'])) . "</div>";
        $content .= "</body></html>";

        header("Content-Type: text/html");
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        echo $content;
        exit;
    }
}
?>
