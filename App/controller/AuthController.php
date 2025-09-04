<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/User.php';
class AuthController{

    public function login(){
        if(Auth::check()){
            header('Location:' . BASE_URL . '/?url=dashboard/index');
            exit;
        }
        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function doLogin(){
        $email = $_POST['email'] ?? '';
        $pass = $_POST['password'] ?? '';

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        $ok = $user && password_verify($pass, $user['password_hash']);

        if($ok){
            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role'],
            ];
            header('Location: ' . BASE_URL . '/?url=dashboard/index');
            exit;
        }

        $error = 'Credenciais inválidas';
        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function logout(){
        session_destroy();
        header('Location: ' . BASE_URL . '/?url=auth/login');
        exit;
    }
}