<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/Notification.php';

class NotificationController{

    private $notificationModel;

    public function __construct(){
        $this->notificationModel = new Notification();
    }

    public function get(){
        header('Content-Type: application/json');
        $user_id = $_SESSION['user']['id'] ?? null;
        if(!$user_id){
            echo json_encode([]);
            return;
        }
        $data = $this->notificationModel->getUnreadByUser($user_id);
        echo json_encode($data);
    }

    public function markAsRead(){
        header('Content-Type: application/json');
        $id = $_POST['id'] ?? null;
        if($id){
            $this->notificationModel->markAsRead($id);
            echo json_encode(['success' => true]);
            return;
        }
        echo json_encode(['success' => false, 'error' => 'ID não informado']);
    }

    public function markAllAsRead(){
        header('Content-Type: application/json');
        $user_id = $_SESSION['user']['id'] ?? null;
        if(!$user_id){
            echo json_encode(['success' => false, 'error' => 'Usuário não autenticado']);
            return;
        }
        $this->notificationModel->markAllAsRead($user_id);
        echo json_encode(['success' => true]);
    }

    public function delete(){
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/?url=notification/history');
            return;
        }

        $id = $_POST['id'] ?? null;
        $user_id = $_SESSION['user']['id'] ?? null;

        if(!$id || !$user_id){
            $_SESSION['error'] = 'Dados inválidos';
            header('Location: ' . BASE_URL . '/?url=notification/history');
            return;
        }

        $this->notificationModel->delete($id, $user_id);
        $_SESSION['success'] = 'Notificação excluída com sucesso!';
        header('Location: ' . BASE_URL . '/?url=notification/history');
        exit;
    }

    public function deleteAllRead(){
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/?url=notification/history');
            return;
        }

        $user_id = $_SESSION['user']['id'] ?? null;
        if(!$user_id){
            $_SESSION['error'] = 'Usuário não autenticado';
            header('Location: ' . BASE_URL . '/?url=notification/history');
            return;
        }

        $this->notificationModel->deleteAllRead($user_id);
        $_SESSION['success'] = 'Notificações lidas excluídas com sucesso!';
        header('Location: ' . BASE_URL . '/?url=notification/history');
        exit;
    }

    public function deleteAll(){
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/?url=notification/history');
            return;
        }

        $user_id = $_SESSION['user']['id'] ?? null;
        if(!$user_id){
            $_SESSION['error'] = 'Usuário não autenticado';
            header('Location: ' . BASE_URL . '/?url=notification/history');
            return;
        }

        $this->notificationModel->deleteAll($user_id);
        $_SESSION['success'] = 'Todas as notificações foram excluídas!';
        header('Location: ' . BASE_URL . '/?url=notification/history');
        exit;
    }

    public function history(){
        Auth::requireLogin();
        
        $user_id = $_SESSION['user']['id'];
        $notifications = $this->notificationModel->getAllByUser($user_id, 200);
        
        require_once __DIR__ . '/../views/notification/history.php';
    }
}