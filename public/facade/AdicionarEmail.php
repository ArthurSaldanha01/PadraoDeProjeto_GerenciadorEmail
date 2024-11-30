<?php
class AdicionarEmail {
    private $mysqli;

    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
    }

    public function inserirEmail($remetente, $destinatario, $assunto, $texto, $tipo_email, $usuario_id, $envio, $modificado, $template = null) {
        $emailCreator = null;
    
        // Verifica o tipo do e-mail e instancia o criador correspondente
        if ($tipo_email === 'com_template') {
            $emailCreator = new emailTemplateCreator($this->mysqli);  // Passa o mysqli para o criador
    
            // Cria o e-mail com template
            $email = $emailCreator->criarEmail($remetente, $destinatario, $assunto, $texto);
    
            // Obtém o template preenchido
            $template = $email->getTemplate();
    
            // Envia o e-mail e registra o log
            $email->enviar();
        } elseif ($tipo_email === 'simples') {
            $emailCreator = new emailSimplesCreator($this->mysqli);  // Passa o mysqli para o criador
    
            // Cria o e-mail simples
            $email = $emailCreator->criarEmail($remetente, $destinatario, $assunto, $texto);
    
            // Envia o e-mail e registra o log
            $email->enviar();
        }
    
        // Inserção do e-mail no banco de dados
        $sql = "INSERT INTO email (remetente, destinatario, assunto, texto, envio, usuario_id, modificado, tipo, template) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->mysqli->prepare($sql);
    
        if (!$stmt) {
            die("Erro ao preparar a consulta: " . $this->mysqli->error);
        }
    
        // Bind dos parâmetros para a execução da query
        $stmt->bind_param("sssssisss", $remetente, $destinatario, $assunto, $texto, $envio, $usuario_id, $modificado, $tipo_email, $template);
    
        if (!$stmt->execute()) {
            die("Erro na execução da consulta: " . $stmt->error);
        }
    
        return true;
    }
    
}
?>
