<?php
class ExcluirEmail {
    private $mysqli;

    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
    }

    public function deletar($emailId) {
        $sql = "DELETE FROM email WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $emailId);
        return $stmt->execute();
    }
}
?>
