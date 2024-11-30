<?php
class AdicionarEmail {
    private $mysqli;

    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
    }

    public function inserirEmail($remetente, $destinatario, $assunto, $texto, $tipo_email, $usuario_id, $envio, $modificado, $template = null) {
        $emailCreator = null;
    
        if ($tipo_email === 'com_template') {

            $emailCreator = new emailTemplateCreator($this->mysqli);
    

            $email = $emailCreator->criarEmail($remetente, $destinatario, $assunto, $texto);
    

            $template = $email->getTemplate();
    

            $email->enviar();
        } elseif ($tipo_email === 'simples') {
            $emailCreator = new emailSimplesCreator($this->mysqli);
    
            $email = $emailCreator->criarEmail($remetente, $destinatario, $assunto, $texto);
    
            $email->enviar();
        }
    
        $sql = "INSERT INTO email (remetente, destinatario, assunto, texto, envio, usuario_id, modificado, tipo, template) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->mysqli->prepare($sql);
    
        if (!$stmt) {
            die("Erro ao preparar a consulta: " . $this->mysqli->error);
        }
    
        $stmt->bind_param("sssssisss", $remetente, $destinatario, $assunto, $texto, $envio, $usuario_id, $modificado, $tipo_email, $template);
    
        if (!$stmt->execute()) {
            die("Erro na execução da consulta: " . $stmt->error);
        }
    
        return true;
    }
    
}
?>
