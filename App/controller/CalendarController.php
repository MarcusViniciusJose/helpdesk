<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/CalendarEvent.php';


class CalendarController{

    private $eventModel;

    public function __construct()
    {   
        Auth::requireLogin();

        $user = Auth::user();
        if(!in_array($user['role'], ['ti','admin'])){
            $_SESSION['error'] = 'Acesso negado. Apenas membros da Equipe de TI podem acessar o calendário.';
            header('Location: ' . BASE_URL . '/?url=dashboard/index');
            exit; 
        }

        $this->eventModel = new CalendarEvent();
    }

    public function index(){
        $user = Auth::user();
        $teamMenbers = $this->eventModel->getTeamMembers();

        require_once __DIR__ . '/../views/calendar/index.php';
    }

    public function getEvents(){
    header('Content-Type: application/json');

    $user = Auth::user();
    $viewUserId = $_GET['user_id'] ?? null;

    if($user['role'] == 'admin'){
        if($viewUserId){
            $events = $this->eventModel->getByUser($viewUserId);
        }else{
            $events = $this->eventModel->getTeamEvents();
        }
    }else{
        $events = $this->eventModel->getByUser($user['id']);
    }
    
    $formattedEvents = array_map(function($event){
        $start = $event['start_date'];
        $end = $event['end_date'];
        
        if (strpos($start, '+') === false && strpos($start, 'Z') === false) {
            $start = str_replace(' ', 'T', $start);
        }
        if (strpos($end, '+') === false && strpos($end, 'Z') === false) {
            $end = str_replace(' ', 'T', $end);
        }
        
        return [
            'id' => $event['id'],
            'title' => $event['title'],
            'start' => $start,  
            'end' => $end,      
            'allDay' => (bool)$event['all_day'],
            'backgroundColor' => $event['color'],
            'borderColor' => $event['color'],
            'extendedProps' => [
                'description' => $event['description'],
                'location' => $event['location'],
                'event_type' => $event['event_type'],
                'status' => $event['status'],
                'user_name' => $event['user_name'],
                'user_id' => $event['user_id'],
                'start_date' => $event['start_date'],  
                'end_date' => $event['end_date'],  
            ]
        ];
    }, $events);
    
    echo json_encode($formattedEvents);
}

    public function store(){
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            header('Location: ' . BASE_URL . '/?url=calendar/index');
            return;
        }

        $user = Auth::user();

        $data = [
            'user_id' => $user['id'],
            'title' => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'start_date' => $_POST['start_date'] ?? '',
            'end_date' => $_POST['end_date'] ?? '',
            'all_day' => isset($_POST['all_day']) ? 1 : 0,
            'color' => $_POST['color'] ?? '#0d6efd',
            'location' => trim($_POST['location'] ?? ''), 
            'event_type' => $_POST['event_type'] ?? 'task',
            'status' => $_POST['status'] ?? 'peding'
        ];

        if(empty($data['title']) || empty($data['start_date']) || empty($data['end_date'])){
            $_SESSION['error'] = 'Titulo, data de ínicio e fim são obrigatórios';
            header('Location: ' . BASE_URL . '/?url=calendar/index');
            return;
        }

        $this->eventModel->create($data);
        
        $_SESSION['success'] = 'Evento criado com sucesso!';
        header('Location: ' . BASE_URL . '/?url=calendar/index');
        exit;
    }

    public function update(){
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            header('Location: ' . BASE_URL . '/?url=calendar/index');
            return;
        }

        $id = $_POST['id'] ?? null;
        $user = Auth::user();

        if(!$id){
            $_SESSION['error'] = 'Evento não encontrado';
            header('Location: ' . BASE_URL . '/?url=calendar/index' );
            return;
        }

        if(!$this->eventModel->isOwner($id, $user['id']) && $user['role'] !== 'admin'){
            $_SESSION['error'] = 'Você não tem permissão para alterar esse evento';
            header('Location: ' . BASE_URL . '/?url=calendar/index');
            return;
        }

        $data = [
            'title' => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'start_date' => $_POST['start_date'] ?? '',
            'end_date' => $_POST['end_date'] ?? '',
            'all_day' => isset($_POST['all_day']) ? 1 : 0,
            'color' => $_POST['color'] ?? '#0d6efd',
            'location' => trim($_POST['location'] ?? ''), 
            'event_type' => $_POST['event_type'] ?? 'task',
            'status' => $_POST['status'] ?? 'peding'
        ];
        $this->eventModel->update($id, $data);

        $_SESSION['success'] = 'Evento alterado com sucesso.';
        header('Location: ' . BASE_URL . '/?url=calendar/index');
        exit; 
    }

    public function updateDates(){
        header('Content-Type: application/json');

        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            echo json_encode(['success' => false]);
            return;
        }

        $id = $_POST['id'] ?? null;
        $startDate = $_POST['start_date'] ?? null;
        $endDate = $_POST['end_date'] ?? null;
        $user = Auth::user();

        if(!$id || !$startDate || !$endDate){
            echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
            return;
        }

        if(!$this->eventModel->isOwner($id,$user['id']) &&  $user['role'] !== 'admin'){
            echo json_encode(['success' => false, 'message' => 'Sem permissão']);
            return;
        }

        $success = $this->eventModel->updateDates($id, $startDate, $endDate);
        echo json_encode(['success' => $success]);
        

    }

    public function complete(){
        header('Content-Type: application/json');

        $id = $_POST['id'] ?? null;
        $user = Auth::user();

        if(!$id){
            echo json_encode(['success' => false]);
            return;
        }   

        if(!$this->eventModel->isOwner($id, $user['id']) && $user['role'] !== 'admin'){
            echo json_encode(['success' => false, 'message' => 'Sem permissão']);
            return;
        }

        $success = $this->eventModel->updateStatus($id, 'completed');
        echo json_encode(['success' => $success]);

    }

    public function delete(){
        if($_SERVER['REQUEST_METHOD'] !== 'POST' ){
            header('Location: ' . BASE_URL . '/?url=calendar/index');
            return;
        }
        
        $id = $_POST['id'] ?? null;
        $user = Auth::user();

        if(!$id){
            $_SESSION['error'] = 'Evento não encontrado';
            header('Location: ' . BASE_URL . '/?url=calendar/index');
            return;
        }
        
        if(!$this->eventModel->isOwner($id, $user['id']) && $user['role'] !== 'admin'){
            $_SESSION['error'] = 'Você não tem permissão para excluir este evento';
            header('Location: ' . BASE_URL . '/?url=calendar/index');
            return;
        }

        $this->eventModel->delete($id);

        $_SESSION['success'] = 'Evento deletado com sucesso';
        header('Location: ' . BASE_URL . '/?url=calendar/index');
        exit;
    }


}

