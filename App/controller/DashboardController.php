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
        $isAdminOrTI = in_array($user['role'], ['admin', 'ti'], true);
        
        $stats = $this->ticketModel->getStatsByUser($user);
        
        $kpis = null;
        $techPerformance = [];
        $topDepartments = [];
        $topIssues = [];
        $topRequesters = [];
        $openVsClosedTrend = [];
        
        if ($isAdminOrTI) {
            try {
                $kpis = $this->ticketModel->getManagementKPIs();
                $techPerformance = $this->ticketModel->getTechPerformance();
                $topDepartments = $this->ticketModel->getTopDepartments(5);
                $topIssues = $this->ticketModel->getTopIssues(10);
                $topRequesters = $this->ticketModel->getTopRequesters(10);
                $openVsClosedTrend = $this->ticketModel->getOpenVsClosedTrend(7);
            } catch (Exception $e) {
                error_log("Erro ao carregar dados do dashboard: " . $e->getMessage());
                $_SESSION['error'] = 'Erro ao carregar alguns dados do dashboard';
            }
        }
        
        require_once __DIR__ . '/../views/dashboard/index.php';
    }
}