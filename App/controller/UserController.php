<?php

require_once __DIR__ . "/../models/User.php";

class UserController {

    private $userModel;

    public function __construct() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        
        $role = $_SESSION['user']['role'] ?? null;
        if (!in_array($role, ['admin', 'ti'], true)) {
            $_SESSION['error'] = 'Acesso negado';
            header('Location: ' . BASE_URL . '/?url=dashboard/index');
            exit;
        }

        $this->userModel = new User();
    }

    public function index() {
        $users = $this->userModel->all();
        
        require __DIR__ . '/../views/users/index.php';
    }

    public function create() {
        require __DIR__ . '/../views/users/create.php';
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

        $validRoles = array_keys(User::getRoles());
        if (!in_array($role, $validRoles, true)) {
            $role = 'user';
        }

        $plainDefault  = 'senha123';
        $passwordHash  = password_hash($plainDefault, PASSWORD_BCRYPT);

        $errors = [];
        if ($name === '') {
            $errors[] = 'Nome é obrigatório';
        }
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'E-mail válido é obrigatório';
        }
        if (!in_array($status, ['active', 'inactive'], true)) {
            $errors[] = 'Status inválido';
        }

        if ($email !== '' && $this->userModel->findByEmail($email)) {
            $errors[] = 'Já existe um usuário com esse e-mail.';
        }

        if ($errors) {
            $form = compact('name', 'email', 'role', 'status');
            require __DIR__ . '/../views/users/create.php';
            return;
        }

        $this->userModel->create([
            'name'          => $name,
            'email'         => $email,
            'password_hash' => $passwordHash,
            'role'          => $role,
            'status'        => $status,
        ]);

        $_SESSION['success'] = 'Usuário criado com sucesso! Senha padrão: senha123';
        header('Location: ' . BASE_URL . '/?url=user/index');
        exit;
    }

    public function edit() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            $_SESSION['error'] = 'Usuário não encontrado';
            header('Location: ' . BASE_URL . '/?url=user/index');
            return;
        }

        $user = $this->userModel->findById($id);
        
        if (!$user) {
            $_SESSION['error'] = 'Usuário não encontrado';
            header('Location: ' . BASE_URL . '/?url=user/index');
            return;
        }

        $currentUser = $_SESSION['user'];
        if ($user['role'] === 'admin' && $currentUser['role'] !== 'admin') {
            $_SESSION['error'] = 'Apenas administradores podem editar outros administradores';
            header('Location: ' . BASE_URL . '/?url=user/index');
            return;
        }

        require __DIR__ . '/../views/users/edit.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/?url=user/index');
            return;
        }

        $id     = $_POST['id'] ?? null;
        $name   = trim($_POST['name'] ?? '');
        $email  = trim($_POST['email'] ?? '');
        $role   = $_POST['role'] ?? 'user';
        $status = $_POST['status'] ?? 'active';

        if (!$id) {
            $_SESSION['error'] = 'Usuário não encontrado';
            header('Location: ' . BASE_URL . '/?url=user/index');
            return;
        }

        $user = $this->userModel->findById($id);
        if (!$user) {
            $_SESSION['error'] = 'Usuário não encontrado';
            header('Location: ' . BASE_URL . '/?url=user/index');
            return;
        }

        $validRoles = array_keys(User::getRoles());
        if (!in_array($role, $validRoles, true)) {
            $role = 'user';
        }

        $errors = [];
        if ($name === '') {
            $errors[] = 'Nome é obrigatório';
        }
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'E-mail válido é obrigatório';
        }
        if (!in_array($status, ['active', 'inactive'], true)) {
            $errors[] = 'Status inválido';
        }

        $existingUser = $this->userModel->findByEmail($email);
        if ($existingUser && $existingUser['id'] != $id) {
            $errors[] = 'Já existe outro usuário com esse e-mail.';
        }

        if ($errors) {
            $user = array_merge($user, compact('name', 'email', 'role', 'status'));
            require __DIR__ . '/../views/users/edit.php';
            return;
        }

        $this->userModel->update($id, [
            'name'   => $name,
            'email'  => $email,
            'role'   => $role,
            'status' => $status,
        ]);

        $_SESSION['success'] = 'Usuário atualizado com sucesso!';
        header('Location: ' . BASE_URL . '/?url=user/index');
        exit;
    }

    public function toggleStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/?url=user/index');
            return;
        }

        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            $_SESSION['error'] = 'Usuário não encontrado';
            header('Location: ' . BASE_URL . '/?url=user/index');
            return;
        }

        if ($id == $_SESSION['user']['id']) {
            $_SESSION['error'] = 'Você não pode desativar sua própria conta';
            header('Location: ' . BASE_URL . '/?url=user/index');
            return;
        }

        $this->userModel->toggleStatus($id);
        
        $_SESSION['success'] = 'Status do usuário alterado com sucesso!';
        header('Location: ' . BASE_URL . '/?url=user/index');
        exit;
    }

    public function resetPassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/?url=user/index');
            return;
        }

        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            $_SESSION['error'] = 'Usuário não encontrado';
            header('Location: ' . BASE_URL . '/?url=user/index');
            return;
        }

        $newPassword = 'senha123';
        $passwordHash = password_hash($newPassword, PASSWORD_BCRYPT);

        $this->userModel->updatePassword($id, $passwordHash);
        
        $_SESSION['success'] = 'Senha resetada com sucesso! Nova senha: senha123';
        header('Location: ' . BASE_URL . '/?url=user/index');
        exit;
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/?url=user/index');
            return;
        }

        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            $_SESSION['error'] = 'Usuário não encontrado';
            header('Location: ' . BASE_URL . '/?url=user/index');
            return;
        }

        if ($id == $_SESSION['user']['id']) {
            $_SESSION['error'] = 'Você não pode excluir sua própria conta';
            header('Location: ' . BASE_URL . '/?url=user/index');
            return;
        }

        if ($_SESSION['user']['role'] !== 'admin') {
            $_SESSION['error'] = 'Apenas administradores podem excluir usuários';
            header('Location: ' . BASE_URL . '/?url=user/index');
            return;
        }

        $deleted = $this->userModel->delete($id);
        
        if ($deleted) {
            $_SESSION['success'] = 'Usuário excluído com sucesso!';
        } else {
            $_SESSION['error'] = 'Não é possível excluir este usuário pois há tickets relacionados';
        }
        
        header('Location: ' . BASE_URL . '/?url=user/index');
        exit;
    }
}