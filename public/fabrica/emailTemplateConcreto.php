<?php
include_once 'Email.php';

class emailTemplateConcreto implements Email {
    private $remetente;
    private $destinatario;
    private $assunto;
    private $texto;
    private $mysqli;

    public function __construct($remetente, $destinatario, $assunto, $texto, $mysqli) {
        $this->remetente = $remetente;
        $this->destinatario = $destinatario;
        $this->assunto = $assunto;
        $this->texto = $texto;
        $this->mysqli = $mysqli;
    }

    public function enviar() {
        $logMessage = "Enviando e-mail com Template para {$this->destinatario} com assunto '{$this->assunto}'";
        $sql = "INSERT INTO email_logs (log_text) VALUES (?)";
        $stmt = $this->mysqli->prepare($sql);

        if (!$stmt) {
            die("Erro ao preparar a consulta de log: " . $this->mysqli->error);
        }

        $stmt->bind_param("s", $logMessage);
        $stmt->execute();
    }

    // Modificado para preencher o template com as informações
    public function getTemplate() {
        $template = "
            Prezado(a) {destinatario},
            Você recebeu um novo e-mail de {remetente} com o assunto: 
            
            {assunto}.

            Mensagem:

            {texto}
        ";

        // Substitui os marcadores pelas informações reais
        $template = str_replace("{remetente}", $this->remetente, $template);
        $template = str_replace("{destinatario}", $this->destinatario, $template);
        $template = str_replace("{assunto}", $this->assunto, $template);
        $template = str_replace("{texto}", nl2br($this->texto), $template); // nl2br para preservar quebras de linha no texto

        return $template;
    }
}

?>
