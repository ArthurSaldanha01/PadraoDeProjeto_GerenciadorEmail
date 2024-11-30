<?php
class EditarEmail {
    private $mysqli;
    private $templateCreator;

    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
        $this->templateCreator = new emailTemplateCreator($this->mysqli);
    }

    public function atualizar($emailId, $remetente, $destinatario, $assunto, $texto, $tipoEmail) {
        $template = null;

        if ($tipoEmail === 'template') {
            $emailTemplate = $this->templateCreator->criarEmail($remetente, $destinatario, $assunto, $texto);
            
            if ($emailTemplate instanceof emailTemplateConcreto) {

                $template = $emailTemplate->getTemplate();
            } else {
                throw new Exception('Erro ao criar o template de e-mail.');
            }
        }

        $sql = $tipoEmail === 'template' 
            ? "UPDATE email SET remetente = ?, destinatario = ?, assunto = ?, texto = ?, template = ?, tipo = ?, modificado = NOW() WHERE id = ?"
            : "UPDATE email SET remetente = ?, destinatario = ?, assunto = ?, texto = ?, tipo = ?, modificado = NOW() WHERE id = ?";

        $stmt = $this->mysqli->prepare($sql);

        if (!$stmt) {
            throw new Exception('Erro ao preparar a consulta: ' . $this->mysqli->error);
        }

        if ($tipoEmail === 'template') {

            $stmt->bind_param("ssssssi", $remetente, $destinatario, $assunto, $texto, $template, $tipoEmail, $emailId);
        } else {

            $stmt->bind_param("sssssi", $remetente, $destinatario, $assunto, $texto, $tipoEmail, $emailId);
        }

        if (!$stmt->execute()) {
            throw new Exception('Erro ao executar a consulta: ' . $stmt->error);
        }

        return true;
    }
}

?>
