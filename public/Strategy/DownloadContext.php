<?php
class DownloadContext {
    private $strategy;

    public function setStrategy(DownloadStrategy $strategy) {
        $this->strategy = $strategy;
    }

    public function baixarEmail($emailData) {
        if (!$this->strategy) {
            throw new Exception("Estratégia de download não definida.");
        }
        $this->strategy->baixar($emailData);
    }
}
?>
