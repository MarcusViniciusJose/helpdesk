<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/Ticket.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../models/Notification.php';
require_once __DIR__ . '/../models/User.php';

class TicketController{
    public function index(){
        Auth::requireLogin();
        $model = new Ticket();
        $tickets = $model->allForUser(Auth::user());
        require_once __DIR__ . '/../views/tickets/index.php';
    }

    public function create(){
        Auth::requireLogin();
        require_once __DIR__ . '/../views/tickets/create.php';
    }

    public function store(){
        Auth::requireLogin();

        $data = [
            'requester_id' => Auth::user()['id'],
            'title' => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'priority' => $_POST['priority'] ?? 'medium',
        ];

        if($data['title'] === '' || $data['description'] === ''){
            $error = 'Título e descrição são obrogatórias';
            require_once __DIR__ . '/../views/tickets/create.php';
            return;
        }


        $id = (new Ticket())->create($data);
        $notification = new Notification();
        $userModel = new User();
        $db = Database::conn();
        $stmt = $db->query("SELECT id FROM users WHERE role = 'ti' OR role = 'admin'");
        $receivers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($receivers as $r){
            $notification->create($r['id'], "Novo chamado aberto: ", htmlspecialchars($data['title']), BASE_URL . "/?url=ticket/show&id=" . $id, $id);
        }

        header('Location: ' . BASE_URL . '/?url=ticket/index');
        exit;
    }

    public function show(){
        Auth::requireLogin();
        $id = $_GET['id'] ?? null;
        if(!$id){
            echo "Chamado não encontrado";
            return;
        }
        $tickets = (new Ticket())->getById($id);
        if(!$tickets){
            echo "Chamado não encontrado";
            return;
        }
        $comments = (new Comment())->getByTicket($id); 
        $users = (new User());
        $db = Database::conn();
        $stmt = $db->query("SELECT id, name FROM users WHERE role IN ('ti', 'admin') ORDER BY name");
        $techs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../views/tickets/show.php';
    }

    public function storeComment(){
        Auth::requireLogin();
        $ticketId = $_POST['ticket_id'] ?? null;
        $content = trim($_POST['content'] ?? '');
        if(!$ticketId|| !$content === ''){
            header('Location: ' . BASE_URL . '/?url=ticket/show&id=' . $ticketId);
            return;
        }
        $commentModel = new Comment();
        $commentModel->create($ticketId, Auth::user()['id'], $content);


        $ticketModel = new Ticket();
        $ticket = $ticketModel->getById($ticketId);
        $notification = new Notification();

        $message = Auth::user()['name'] . " comentou no chamado " . htmlspecialchars(substr($content,0,120));

        if($ticket['requester_id'] != Auth::user()['id']){
            $notification->create($ticket['assigned_to'], $message, BASE_URL . "/?url=ticket/show&id=" . $ticketId, $ticketId);
        }
        header('Location: ' . BASE_URL . '/?url=ticket/show&id=' . $ticketId);
        exit;
    }
}
