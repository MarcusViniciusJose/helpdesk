<?php

class Auth{
    public static function user(){
        return $_SESSION['user'] ?? null;
    }

    public static function check(): bool{
        return isset($_SESSION['user']);
    }

    public static function requireLogin(){
        if(!self::check()){
            header('Location: ' . BASE_URL . '/?url=auth/login');
        }
    }

    public static function requireRole(array $roles){
        self::requireLogin();
        $u = self::user();
        if(!$u || !in_array($u['role'] ?? 'user', $roles, true)){
            http_response_code(403);
            die('Acesso negado.');
        }
    }
}