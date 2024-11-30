<?php
include_once 'D:\xampp\htdocs\gerenciador_email\public\fabrica/emailTemplateCreator.php';
include_once 'D:\xampp\htdocs\gerenciador_email\public\fabrica/emailTemplateConcreto.php';
include_once 'D:\xampp\htdocs\gerenciador_email\public\fabrica/emailSimplesCreator.php';
include_once 'D:\xampp\htdocs\gerenciador_email\public\fabrica/emailSimplesConcreto.php';
include_once 'D:\xampp\htdocs\gerenciador_email\public\facade/EditarEmail.php';
include_once 'D:\xampp\htdocs\gerenciador_email\public\facade/ExcluirEmail.php';
include_once 'D:\xampp\htdocs\gerenciador_email\public\facade/AdicionarEmail.php';
include_once 'D:\xampp\htdocs\gerenciador_email\public\facade/ValidacaoDestinatario.php';
include_once 'D:\xampp\htdocs\gerenciador_email\public\Observer/Subject.php';
include_once 'D:\xampp\htdocs\gerenciador_email\public\Observer/LogObserver.php';

class EmailFacade implements Subject {
    private $excluirEmail;
    private $editarEmail;
    private $adicionarEmail;
    private $validacaoDestinatario;
    private $observers = [];

    public function __construct($mysqli) {
        $this->excluirEmail = new ExcluirEmail($mysqli);
        $this->editarEmail = new EditarEmail($mysqli);
        $this->adicionarEmail = new AdicionarEmail($mysqli);
        $this->validacaoDestinatario = new ValidacaoDestinatario($mysqli);
    }

    // Método para adicionar um observador
    public function addObserver(Observer $observer) {
        $this->observers[] = $observer;
    }

    // Método para remover um observador
    public function removeObserver(Observer $observer) {
        $this->observers = array_filter($this->observers, function ($existingObserver) use ($observer) {
            return $existingObserver !== $observer;
        });
    }

    // Método para notificar os observadores sobre um evento
    public function notifyObservers($eventData) {
        foreach ($this->observers as $observer) {
            $observer->update($eventData);
        }
    }

    // Método para adicionar um email
    public function adicionarEmail($remetente, $destinatario, $assunto, $texto, $tipo_email, $usuario_id, $envio, $modificado, $template = null) {
        $result = $this->adicionarEmail->inserirEmail($remetente, $destinatario, $assunto, $texto, $tipo_email, $usuario_id, $envio, $modificado, $template);

        if ($result) {
            // Notifica os observadores sobre o evento de adicionar email
            $this->notifyObservers([
                'evento' => 'adicionar_email',
                'remetente' => $remetente,
                'destinatario' => $destinatario
            ]);
        }

        return $result;
    }

    // Método para atualizar um email
    public function atualizarEmail($emailId, $remetente, $destinatario, $assunto, $texto, $tipoEmail) {
        $result = $this->editarEmail->atualizar($emailId, $remetente, $destinatario, $assunto, $texto, $tipoEmail);

        if ($result) {
            // Notifica os observadores sobre o evento de atualizar email
            $this->notifyObservers([
                'evento' => 'editar_email',
                'email_id' => $emailId,
                'remetente' => $remetente,
                'destinatario' => $destinatario
            ]);
        }

        return $result;
    }

    // Método para deletar um email
    public function deletarEmail($emailId) {
        $result = $this->excluirEmail->deletar($emailId);

        if ($result) {
            // Notifica os observadores sobre o evento de deletar email
            $this->notifyObservers([
                'evento' => 'deletar_email',
                'email_id' => $emailId
            ]);
        }

        return $result;
    }

    // Método para validar destinatário
    public function validarDestinatario($email) {
        return $this->validacaoDestinatario->validarDestinatario($email);  // Chamando o método da classe ValidacaoDestinatario
    }
}
?>
