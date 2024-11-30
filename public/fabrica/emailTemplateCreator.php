<?php
include_once 'emailCreator.php';
include_once 'emailTemplateConcreto.php';

class emailTemplateCreator extends emailCreator {
    public function __construct($mysqli) {
        parent::__construct($mysqli);
    }

    public function criarEmail($remetente, $destinatario, $assunto, $texto): Email {
        return new emailTemplateConcreto($remetente, $destinatario, $assunto, $texto, $this->mysqli);
    }
}
?>
