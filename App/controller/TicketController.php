<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/Ticket.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Comment.php';
require_once __DIR__ . '/../models/Notification.php';

class TicketController{
    
    private $ticketModel;
    
    public function __construct(){
        $this->ticketModel = new Ticket();
    }
    
    public function index(){
        Auth::requireLogin();
        $tickets = $this->ticketModel->allForUser(Auth::user());
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
            'title'        => trim($_POST['title'] ?? ''),
            'description'  => trim($_POST['description'] ?? ''),
            'priority'     => $_POST['priority'] ?? 'medium',
        ];

        if($data['title'] === '' || $data['description'] === ''){
            $error = 'Título e descrição são obrigatórios';
            require_once __DIR__ . '/../views/tickets/create.php';
            return;
        }

        $id = $this->ticketModel->create($data);
        
        $notification = new Notification();
        $db = Database::conn();
        $stmt = $db->query("SELECT id FROM users WHERE role IN ('ti', 'admin')");
        $receivers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach($receivers as $r){
            $notification->create(
                $r['id'], 
                "Novo chamado aberto: " . htmlspecialchars($data['title']), 
                "", 
                BASE_URL . "/?url=ticket/show&id=" . $id, 
                $id
            );
        }

        $_SESSION['success'] = 'Chamado criado com sucesso!';
        header('Location: ' . BASE_URL . '/?url=ticket/show&id=' . $id);
        exit;
    }

    public function show(){
        Auth::requireLogin();
        
        $id = $_GET['id'] ?? null;
        if(!$id){
            $_SESSION['error'] = 'Chamado não encontrado';
            header('Location: ' . BASE_URL . '/?url=ticket/index');
            return;
        }
        
        $ticket = $this->ticketModel->getById($id);
        if(!$ticket){
            $_SESSION['error'] = 'Chamado não encontrado';
            header('Location: ' . BASE_URL . '/?url=ticket/index');
            return;
        }
        
        $user = Auth::user();
        $canView = in_array($user['role'], ['admin', 'ti']) || 
                   $ticket['requester_id'] == $user['id'] ||
                   $ticket['assigned_to'] == $user['id'];
        
        if(!$canView){
            $_SESSION['error'] = 'Você não tem permissão para visualizar este chamado';
            header('Location: ' . BASE_URL . '/?url=ticket/index');
            return;
        }
        
        $comments = (new Comment())->getByTicket($id); 
        
        $db = Database::conn();
        $stmt = $db->query("SELECT id, name FROM users WHERE role IN ('ti', 'admin') ORDER BY name");
        $techs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../views/tickets/show.php';
    }

    public function edit(){
        Auth::requireLogin();
        
        $id = $_GET['id'] ?? null;
        if(!$id){
            $_SESSION['error'] = 'Chamado não encontrado';
            header('Location: ' . BASE_URL . '/?url=ticket/index');
            return;
        }
        
        $ticket = $this->ticketModel->getById($id);
        if(!$ticket){
            $_SESSION['error'] = 'Chamado não encontrado';
            header('Location: ' . BASE_URL . '/?url=ticket/index');
            return;
        }
        
        if(!$this->ticketModel->canUserEdit($id, Auth::user())){
            $_SESSION['error'] = 'Você não tem permissão para editar este chamado';
            header('Location: ' . BASE_URL . '/?url=ticket/show&id=' . $id);
            return;
        }
        
        require __DIR__ . '/../views/tickets/edit.php';
    }

    public function update(){
        Auth::requireLogin();
        
        $id = $_POST['id'] ?? null;
        if(!$id){
            $_SESSION['error'] = 'Chamado não encontrado';
            header('Location: ' . BASE_URL . '/?url=ticket/index');
            return;
        }
        
        if(!$this->ticketModel->canUserEdit($id, Auth::user())){
            $_SESSION['error'] = 'Você não tem permissão para editar este chamado';
            header('Location: ' . BASE_URL . '/?url=ticket/show&id=' . $id);
            return;
        }
        
        $data = [
            'title'       => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'priority'    => $_POST['priority'] ?? 'medium',
            'status'      => $_POST['status'] ?? 'open',
        ];
        
        if($data['title'] === '' || $data['description'] === ''){
            $error = 'Título e descrição são obrigatórios';
            $ticket = $this->ticketModel->getById($id);
            require_once __DIR__ . '/../views/tickets/edit.php';
            return;
        }
        
        $this->ticketModel->update($id, $data);
        
        $_SESSION['success'] = 'Chamado atualizado com sucesso!';
        header('Location: ' . BASE_URL . '/?url=ticket/show&id=' . $id);
        exit;
    }

    public function delete(){
        Auth::requireLogin();
        
        $id = $_POST['id'] ?? null;
        if(!$id){
            $_SESSION['error'] = 'Chamado não encontrado';
            header('Location: ' . BASE_URL . '/?url=ticket/index');
            return;
        }
        
        $user = Auth::user();
        if(!in_array($user['role'], ['admin', 'ti'])){
            $_SESSION['error'] = 'Você não tem permissão para excluir chamados';
            header('Location: ' . BASE_URL . '/?url=ticket/show&id=' . $id);
            return;
        }
        
        $this->ticketModel->delete($id);
        
        $_SESSION['success'] = 'Chamado excluído com sucesso!';
        header('Location: ' . BASE_URL . '/?url=ticket/index');
        exit;
    }

    public function changeStatus(){
        Auth::requireLogin();
        
        $ticketId = $_POST['ticket_id'] ?? null;
        $status = $_POST['status'] ?? null;
        
        if(!$ticketId || !$status){
            header('Location: ' . BASE_URL . '/?url=ticket/index');
            return;
        }
        
        $this->ticketModel->updateStatus($ticketId, $status);
        
        $_SESSION['success'] = 'Status atualizado com sucesso!';
        header('Location: ' . BASE_URL . '/?url=ticket/show&id=' . $ticketId);
        exit;
    }

    public function assign(){
        Auth::requireLogin();
        
        $user = Auth::user();
        if(!in_array($user['role'], ['admin', 'ti'])){
            $_SESSION['error'] = 'Você não tem permissão para atribuir chamados';
            header('Location: ' . BASE_URL . '/?url=ticket/index');
            return;
        }
        
        $ticketId = $_POST['ticket_id'] ?? null;
        $techId = $_POST['tech_id'] ?? null;
        
        if(!$ticketId){
            header('Location: ' . BASE_URL . '/?url=ticket/index');
            return;
        }
        
        $this->ticketModel->assignTo($ticketId, $techId);
        
        if($techId){
            $ticket = $this->ticketModel->getById($ticketId);
            $notification = new Notification();
            $notification->create(
                $techId,
                "Chamado atribuído a você: " . htmlspecialchars($ticket['title']),
                "",
                BASE_URL . "/?url=ticket/show&id=" . $ticketId,
                $ticketId
            );
        }
        
        $_SESSION['success'] = 'Chamado atribuído com sucesso!';
        header('Location: ' . BASE_URL . '/?url=ticket/show&id=' . $ticketId);
        exit;
    }

    public function storeComment(){
        Auth::requireLogin();
        
        $ticketId = $_POST['ticket_id'] ?? null;
        $content = trim($_POST['content'] ?? '');
        
        if(!$ticketId || $content === ''){
            header('Location: ' . BASE_URL . '/?url=ticket/show&id=' . $ticketId);
            return;
        }
        
        $commentModel = new Comment();
        $commentModel->create($ticketId, Auth::user()['id'], $content);

        $ticket = $this->ticketModel->getById($ticketId);
        $notification = new Notification();
        $message = Auth::user()['name'] . " comentou no chamado";
        
        if($ticket['requester_id'] != Auth::user()['id']){
            $notification->create(
                $ticket['requester_id'], 
                $message, 
                htmlspecialchars(substr($content, 0, 100)),
                BASE_URL . "/?url=ticket/show&id=" . $ticketId, 
                $ticketId
            );
        }
        
        if($ticket['assigned_to'] && $ticket['assigned_to'] != Auth::user()['id']){
            $notification->create(
                $ticket['assigned_to'], 
                $message,
                htmlspecialchars(substr($content, 0, 100)), 
                BASE_URL . "/?url=ticket/show&id=" . $ticketId, 
                $ticketId
            );
        }
        
        $_SESSION['success'] = 'Comentário adicionado com sucesso!';
        header('Location: ' . BASE_URL . '/?url=ticket/show&id=' . $ticketId);
        exit;
    }
}