<?php

require_once __DIR__ . "/../models/User.php";

class UserController {

    public function __construct() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $role = $_SESSION['user']['role'] ?? null;
        if (!in_array($role, ['admin', 'ti'], true)) {
            http_response_code(403);
            echo "ACESSO NEGADO";
            exit;
        }
    }

    public function index() {
        $userModel = new User();
        $users = $userModel->all();

        ob_start();
        require __DIR__ . '/../views/users/index.php';
        $content = ob_get_clean();

        require __DIR__ . '/../views/layouts/main.php';
    }

    public function create() {
        ob_start();
        require __DIR__ . '/../views/users/create.php';
        $content = ob_get_clean();

        require __DIR__ . '/../views/layouts/main.php';
    }
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/?url=user/index');
            return;
        }

        $name   = trim($_POST['name'] ?? '');
        $email  = trim($_POST['email'] ?? '');
        $role   = $_POST['role'] ?? 'user';
        $status = $_POST['status'] ?? 'active';

        $plainDefault  = 'senha123';
        $passwordHash  = password_hash($plainDefault, PASSWORD_BCRYPT);

        $errors = [];
        if ($name === '') $errors[] = 'Nome é obrigatório';
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'E-mail válido é obrigatório';
        if (!in_array($role, ['admin', 'ti', 'user'], true)) $errors[] = 'Papel inválido';
        if (!in_array($status, ['active', 'inactive'], true)) $errors[] = 'Status inválido';

        $userModel = new User();
        if ($email !== '' && $userModel->findByEmail($email)) {
            $errors[] = 'Já existe um usuário com esse e-mail.';
        }

        if ($errors) {
            $form = compact('name', 'email', 'role', 'status');
            ob_start();
            require __DIR__ . '/../views/users/create.php';
            $content = ob_get_clean();
            require __DIR__ . '/../views/layouts/main.php';
            return;
        }

        $userModel->create([
            'name'          => $name,
            'email'         => $email,
            'password_hash' => $passwordHash,
            'role'          => $role,
            'status'        => $status,
        ]);

        header('Location: ' . BASE_URL . '/?url=user/index&created=1');
        exit;
    }
}
