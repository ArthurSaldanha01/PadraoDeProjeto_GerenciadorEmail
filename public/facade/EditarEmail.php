<?php
class EditarEmail {
    private $mysqli;
    private $templateCreator;

    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
        $this->templateCreator = new emailTemplateCreator($this->mysqli); // Passando $mysqli para o templateCreator
    }

    public function atualizar($emailId, $remetente, $destinatario, $assunto, $texto, $tipoEmail) {
        $template = null;

        // Verifica se o tipo de e-mail é um template
        if ($tipoEmail === 'template') {
            // Cria a instância do template com base nas informações
            $emailTemplate = $this->templateCreator->criarEmail($remetente, $destinatario, $assunto, $texto);
            
            // Verifica se a instância criada é do tipo emailTemplateConcreto
            if ($emailTemplate instanceof emailTemplateConcreto) {
                // Preenche o template com os dados e obtém o template gerado
                $template = $emailTemplate->getTemplate(); // Usando getTemplate() ao invés de preencherTemplate()
            } else {
                throw new Exception('Erro ao criar o template de e-mail.');
            }
        }

        // SQL para atualização
        // Aqui, vamos atualizar tanto o texto quanto o tipo
        $sql = $tipoEmail === 'template' 
            ? "UPDATE email SET remetente = ?, destinatario = ?, assunto = ?, texto = ?, template = ?, tipo = ?, modificado = NOW() WHERE id = ?"
            : "UPDATE email SET remetente = ?, destinatario = ?, assunto = ?, texto = ?, tipo = ?, modificado = NOW() WHERE id = ?";

        $stmt = $this->mysqli->prepare($sql);

        if (!$stmt) {
            throw new Exception('Erro ao preparar a consulta: ' . $this->mysqli->error);
        }

        // Bind dos parâmetros
        if ($tipoEmail === 'template') {
            // Bind dos parâmetros para o template
            $stmt->bind_param("ssssssi", $remetente, $destinatario, $assunto, $texto, $template, $tipoEmail, $emailId);
        } else {
            // Bind dos parâmetros para o email simples
            $stmt->bind_param("sssssi", $remetente, $destinatario, $assunto, $texto, $tipoEmail, $emailId);
        }

        // Executa a consulta
        if (!$stmt->execute()) {
            throw new Exception('Erro ao executar a consulta: ' . $stmt->error);
        }

        return true;
    }
}

?>
