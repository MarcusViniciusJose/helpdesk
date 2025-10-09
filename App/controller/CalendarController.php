<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/CalendarEvent.php';


class CalendarController{

    private $eventModel;

    public function __construct()
    {   
        Auth::requireLogin();

        $user = Auth::user();
        if(!in_array($user['role'], ['ti,admin'])){
            $_SESSION['error'] = 'Acesso negado. Apenas membros da Equipe de TI podem acessar o calendário.';
            header('Location: ' . BASE_URL . '/?URL=dashboard/index');
            exit; 
        }

        $this->eventModel = new CalendarEvent();
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
            return [
                'id' => $event['id'],
                'title' => $event['title'],
                'start' => $event['start_date'],
                'end' => $event['end_date'],
                'allDay' => $event['all_day'],
                'backgroundColor' => $event['color'],
                'borderColor' => $event['color'],
                'extendedProps' => [
                    'description' => $event['description'],
                    'location' => $event['location'],
                    'event_type' => $event['event_type'],
                    'status' => $event['status'],
                    'user_name' => $event['user_name'],
                    'user_id' => $event['user_id'],
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
            'user_id' => $user['user_id'],
            'title' => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'start_date' => $_POST['start_date'] ?? '',
            'end_date' => $_POST['end_date'] ?? '',
            'all_day' => isset($_POST['all_day']) ? 1 : 0,
            'color' => $_POST['color'] ?? '#0d6efd',
            'location' => trim($_POST['location'] ?? ''), 
            'event_type' => $_POST['event_type'] ?? 'task',
            'status' => $_POST['status'] ?? 'peding',
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
        
    }
}

