<?php
include_once 'Email.php';

class emailSimplesConcreto implements Email {
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

        $logMessage = "Enviando e-mail simples para {$this->destinatario} com assunto '{$this->assunto}'";
        
        echo $logMessage . "\n"; 
    
        $sql = "INSERT INTO email_logs (log_text) VALUES (?)";
        $stmt = $this->mysqli->prepare($sql);
    
        if (!$stmt) {
            die("Erro ao preparar a consulta: " . $this->mysqli->error);
        }
    
        $stmt->bind_param("s", $logMessage);
        if (!$stmt->execute()) {
            die("Erro ao inserir no log: " . $stmt->error);
        }
    
        echo "Log inserido com sucesso.\n";
    }

    public function getTexto() {
        return $this->texto;
    }
}
?>
