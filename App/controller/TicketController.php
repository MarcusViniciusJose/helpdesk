<?php

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
            'priority' => trim($_POST),
        ];
        if($data['title'] === '' || $data['description'] === ''){
            $error = 'Título e descrição são obrogatórias';
            require_once __DIR__ . '/../views/tickets/create.php';
            return;
        }
        $id = (new Ticket())->create($data);
        header('Location: ' . BASE_URL . '/?url=ticket/index');
        exit;
    }
}
