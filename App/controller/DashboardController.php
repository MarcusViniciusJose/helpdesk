<?php

class DashboardController{
    public function index(){
        Auth::requireLogin();
        $user = Auth::user();
        $stats = [
            'abertos' => 0,
            'em_andamento' => 0,
            'fechados' => 0,
        ];
        require_once __DIR__ . '/../views/dashboard/index.php';
    }
}