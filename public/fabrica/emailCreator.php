<?php
abstract class emailCreator {
    protected $mysqli;

    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
    }

    abstract public function criarEmail($remetente, $destinatario, $assunto, $texto): Email;

}
?>
