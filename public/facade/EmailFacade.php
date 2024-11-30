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

    public function addObserver(Observer $observer) {
        $this->observers[] = $observer;
    }

    public function removeObserver(Observer $observer) {
        $this->observers = array_filter($this->observers, function ($existingObserver) use ($observer) {
            return $existingObserver !== $observer;
        });
    }

    public function notifyObservers($eventData) {
        foreach ($this->observers as $observer) {
            $observer->update($eventData);
        }
    }

    public function adicionarEmail($remetente, $destinatario, $assunto, $texto, $tipo_email, $usuario_id, $envio, $modificado, $template = null) {
        $result = $this->adicionarEmail->inserirEmail($remetente, $destinatario, $assunto, $texto, $tipo_email, $usuario_id, $envio, $modificado, $template);

        if ($result) {

            $this->notifyObservers([
                'evento' => 'adicionar_email',
                'remetente' => $remetente,
                'destinatario' => $destinatario
            ]);
        }

        return $result;
    }

    public function atualizarEmail($emailId, $remetente, $destinatario, $assunto, $texto, $tipoEmail) {
        $result = $this->editarEmail->atualizar($emailId, $remetente, $destinatario, $assunto, $texto, $tipoEmail);

        if ($result) {

            $this->notifyObservers([
                'evento' => 'editar_email',
                'email_id' => $emailId,
                'remetente' => $remetente,
                'destinatario' => $destinatario
            ]);
        }

        return $result;
    }

    public function deletarEmail($emailId) {
        $result = $this->excluirEmail->deletar($emailId);

        if ($result) {

            $this->notifyObservers([
                'evento' => 'deletar_email',
                'email_id' => $emailId
            ]);
        }

        return $result;
    }

    public function validarDestinatario($email) {
        return $this->validacaoDestinatario->validarDestinatario($email);  // Chamando o método da classe ValidacaoDestinatario
    }
}
?>
