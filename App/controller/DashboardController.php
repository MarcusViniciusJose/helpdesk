<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/Ticket.php';

class DashboardController{
    
    private Ticket $ticketModel;
    
    public function __construct(){
        $this->ticketModel = new Ticket();
    }
    
    public function index(){
        Auth::requireLogin();
        
        $user = Auth::user();
        $isAdminOrTI = in_array($user['role'], ['admin', 'ti']);
        
        $stats = $this->ticketModel->getStatsByUser($user);
        
        $kpis = null;
        $techPerformance = [];
        
        if ($isAdminOrTI) {
            $kpis = $this->ticketModel->getManagementKPIs();
            $techPerformance = $this->ticketModel->getTechPerformance();
        }
        
        require_once __DIR__ . '/../views/dashboard/index.php';
    }
}