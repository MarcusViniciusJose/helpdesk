<?php

require_once __DIR__ . '/../models/Notification.php';

class NotificationController{

    public function get(){
        header('Content-Type: application/json');
        $user_id = $_SESSION['user']['id'] ?? null;
        if(!$user_id){
            echo json_encode([]);
            return;
        }
        $model = new Notification();
        $data = $model->getUnreadByUser($user_id);
        echo json_encode($data);
    }

    public function markAsRead(){
        $id = $_POST['id'] ?? null;
        if($id){
            $model = new Notification();
            $model ->markAsRead($id);
            echo json_encode(['success' => true]);
            return;
        }
        echo json_encode(['success' => false, 'error' => 'ID não informado']);
    }

    public function history(){
        $user_id = $_SESSION['user']['id'] ?? null;
        if(!$user_id){
            echo 'Acesso Negado';
            return;
        }

        $model = new Notification();
        $notifications = $model->getAllByUser($user_id, 200);
        require_once __DIR__ . '/../views/notification/history.php';

    }
}