<?php
class ValidacaoDestinatario {
    private $mysqli;

    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
    }

    public function validarDestinatario($email) {
        $sql = "SELECT id FROM usuario WHERE email = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 0) {
            throw new Exception("Destinatário não encontrado no sistema.");
        }

        return $resultado->fetch_assoc()['id'];
    }
}
?>