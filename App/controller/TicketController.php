<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/Ticket.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Comment.php';
require_once __DIR__ . '/../models/Notification.php';

class TicketController{
    
    private $ticketModel;
    private $notificationModel;
    
    public function __construct(){
        $this->ticketModel = new Ticket();
        $this->notificationModel = new Notification();
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
        'attachment'   => null, 
    ];

    if($data['title'] === '' || $data['description'] === ''){
        $error = 'Título e descrição são obrigatórios';
        require_once __DIR__ . '/../views/tickets/create.php';
        return;
    }

    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
        $fileTmp  = $_FILES['attachment']['tmp_name'];
        $fileName = basename($_FILES['attachment']['name']);
        $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed  = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'txt'];

        if (!in_array($fileExt, $allowed)) {
            $error = 'Tipo de arquivo não permitido.';
            require_once __DIR__ . '/../views/tickets/create.php';
            return;
        }

        $newFileName = uniqid('ticket_') . '.' . $fileExt;
        $uploadDir = __DIR__ . '/../../public/uploads/tickets/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($fileTmp, $uploadDir . $newFileName)) {
            $data['attachment'] = $newFileName;
        } else {
            $error = 'Erro ao enviar o arquivo.';
            require_once __DIR__ . '/../views/tickets/create.php';
            return;
        }
    }

    $ticketId = $this->ticketModel->create($data);
    
    $priorityLabel = [
        'low' => 'Baixa',
        'medium' => 'Média',
        'high' => 'Alta',
        'critical' => 'CRÍTICA'
    ][$data['priority']] ?? $data['priority'];
    
    $message = sprintf(
        "Novo chamado #%d criado por %s - Prioridade: %s",
        $ticketId,
        Auth::user()['name'],
        $priorityLabel
    );
    
    $this->notificationModel->notifyAdminsAndTI(
        $message,
        BASE_URL . "/?url=ticket/show&id=" . $ticketId,
        $ticketId
    );

    $_SESSION['success'] = 'Chamado criado com sucesso!';
    header('Location: ' . BASE_URL . '/?url=ticket/show&id=' . $ticketId);
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
        
        $ticket = $this->ticketModel->getById($id);

        if($ticket && !empty($ticket['attachment'])){
            $filePath = __DIR__ . '/../../public/uploads/tickets/' . $ticket['attachment'];

            if($filePath){
                unlink($filePath);
            }

        }
        
        $this->ticketModel->delete($id);
        
        $_SESSION['success'] = 'Chamado excluído com sucesso!';
        header('Location: ' . BASE_URL . '/?url=ticket/index');
        exit;
    }

    public function changeStatus(){
        Auth::requireLogin();
        
        $ticketId = $_POST['ticket_id'] ?? null;
        $newStatus = $_POST['status'] ?? null;
        
        if(!$ticketId || !$newStatus){
            header('Location: ' . BASE_URL . '/?url=ticket/index');
            return;
        }
        
        $ticket = $this->ticketModel->getById($ticketId);
        if (!$ticket) {
            $_SESSION['error'] = 'Chamado não encontrado';
            header('Location: ' . BASE_URL . '/?url=ticket/index');
            return;
        }
        
        $oldStatus = $ticket['status'];
        
        $this->ticketModel->updateStatus($ticketId, $newStatus);
        
        $statusLabels = [
            'open' => 'Aberto',
            'in_progress' => 'Em Andamento',
            'closed' => 'Fechado'
        ];
        
        $link = BASE_URL . "/?url=ticket/show&id=" . $ticketId;
        
        if ($newStatus === 'in_progress' && $oldStatus !== 'in_progress') {
            $message = sprintf(
                "Seu chamado #%d '%s' está agora EM ANDAMENTO",
                $ticketId,
                $ticket['title']
            );
            
            $this->notificationModel->create(
                $ticket['requester_id'],
                $message,
                $link,
                $ticketId
            );
        }
        
        if ($newStatus === 'closed' && $oldStatus !== 'closed') {
            $message = sprintf(
                "Seu chamado #%d '%s' foi CONCLUÍDO",
                $ticketId,
                $ticket['title']
            );
            
            $this->notificationModel->create(
                $ticket['requester_id'],
                $message,
                $link,
                $ticketId
            );
        }
        
        if ($newStatus === 'open' && $oldStatus === 'closed') {
            $message = sprintf(
                "Chamado #%d foi REABERTO por %s",
                $ticketId,
                Auth::user()['name']
            );
            
            $this->notificationModel->notifyAdminsAndTI($message, $link, $ticketId);
        }
        
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
        
        $ticket = $this->ticketModel->getById($ticketId);
        if (!$ticket) {
            $_SESSION['error'] = 'Chamado não encontrado';
            header('Location: ' . BASE_URL . '/?url=ticket/index');
            return;
        }
        
        $this->ticketModel->assignTo($ticketId, $techId);
        
        $link = BASE_URL . "/?url=ticket/show&id=" . $ticketId;
        
        if($techId){
            $message = sprintf(
                "Chamado #%d '%s' foi atribuído a você",
                $ticketId,
                $ticket['title']
            );
            
            $this->notificationModel->create($techId, $message, $link, $ticketId);
            
            if ($ticket['requester_id'] != $techId) {
                $techUser = (new User())->findById($techId);
                $messageRequester = sprintf(
                    "Seu chamado #%d foi atribuído para %s",
                    $ticketId,
                    $techUser['name']
                );
                
                $this->notificationModel->create(
                    $ticket['requester_id'],
                    $messageRequester,
                    $link,
                    $ticketId
                );
            }
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
        $link = BASE_URL . "/?url=ticket/show&id=" . $ticketId;
        $currentUserId = Auth::user()['id'];
        
        $message = sprintf(
            "%s comentou no chamado #%d",
            Auth::user()['name'],
            $ticketId
        );
        
        $toNotify = [];
        
        if ($ticket['requester_id'] != $currentUserId) {
            $toNotify[] = $ticket['requester_id'];
        }
        
        if ($ticket['assigned_to'] && $ticket['assigned_to'] != $currentUserId) {
            $toNotify[] = $ticket['assigned_to'];
        }
        
        if (!in_array(Auth::user()['role'], ['admin', 'ti'])) {
            $db = Database::conn();
            $stmt = $db->query("SELECT id FROM users WHERE role IN ('admin', 'ti') AND status = 'active'");
            $adminTI = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            foreach ($adminTI as $userId) {
                if ($userId != $currentUserId && !in_array($userId, $toNotify)) {
                    $toNotify[] = $userId;
                }
            }
        }
        
        $toNotify = array_unique($toNotify);
        foreach ($toNotify as $userId) {
            $this->notificationModel->create($userId, $message, $link, $ticketId);
        }
        
        $_SESSION['success'] = 'Comentário adicionado com sucesso!';
        header('Location: ' . BASE_URL . '/?url=ticket/show&id=' . $ticketId);
        exit;
    }
}