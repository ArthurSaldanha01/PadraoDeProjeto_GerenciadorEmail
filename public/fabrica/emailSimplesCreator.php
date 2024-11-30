<?php
include_once 'emailCreator.php';
include_once 'emailSimplesConcreto.php';

class emailSimplesCreator extends emailCreator {
    public function __construct($mysqli) {
        parent::__construct($mysqli);
    }

    public function criarEmail($remetente, $destinatario, $assunto, $texto): Email {
        return new emailSimplesConcreto($remetente, $destinatario, $assunto, $texto, $this->mysqli);
    }
}
?>
