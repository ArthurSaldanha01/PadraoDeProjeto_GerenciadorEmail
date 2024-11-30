<?php
include_once 'D:\xampp\htdocs\gerenciador_email\public\Observer/Observer.php';

class LogObserver implements Observer {
    private $logFile;

    public function __construct($logFile = 'log.txt') {
        $this->logFile = $logFile;
    }

    public function update($eventData) {
        // Formatar mensagem de log dependendo do tipo de evento
        $logMessage = "";
        
        switch ($eventData['evento']) {
            case 'adicionar_email':
                $logMessage = sprintf(
                    "[%s] Evento: Adicionar Email - Remetente: %s, Destinatário: %s\n",
                    date('Y-m-d H:i:s'),
                    $eventData['remetente'],
                    $eventData['destinatario']
                );
                break;
            
            case 'editar_email':
                $logMessage = sprintf(
                    "[%s] Evento: Editar Email - Email ID: %d, Remetente: %s, Destinatário: %s\n",
                    date('Y-m-d H:i:s'),
                    $eventData['email_id'],
                    $eventData['remetente'],
                    $eventData['destinatario']
                );
                break;
                
            case 'deletar_email':
                $logMessage = sprintf(
                    "[%s] Evento: Deletar Email - Email ID: %d\n",
                    date('Y-m-d H:i:s'),
                    $eventData['email_id']
                );
                break;

            default:
                $logMessage = sprintf(
                    "[%s] Evento desconhecido: %s\n",
                    date('Y-m-d H:i:s'),
                    json_encode($eventData)
                );
        }

        // Escreve a mensagem no arquivo de log
        file_put_contents($this->logFile, $logMessage, FILE_APPEND);
    }
}
?>
