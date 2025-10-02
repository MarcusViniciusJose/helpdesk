<?php

require_once __DIR__ . '/../core/Database.php';

class User {

    private PDO $db;

    public function __construct(){
        $this->db = Database::conn();
    }

    public function all(){
        $sql = "SELECT id, name, email, role, department, status, created_at 
                FROM users 
                ORDER BY name ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByEmail(string $email): ?array {
        $st = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $st->execute([$email]);
        $user = $st->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public function findById(int $id): ?array {
        $st = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $st->execute([$id]);
        $user = $st->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public function create(array $data): int {
        $sql = "INSERT INTO users (name, email, password_hash, role, department, status, created_at, updated_at)
                VALUES (:name, :email, :password_hash, :role, :department, :status, NOW(), NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':name'          => $data['name'],
            ':email'         => $data['email'],
            ':password_hash' => $data['password_hash'],
            ':role'          => $data['role'],
            ':department'    => $data['department'] ?? null,
            ':status'        => $data['status'],
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $sql = "UPDATE users 
                SET name = :name, 
                    email = :email, 
                    role = :role,
                    department = :department,
                    status = :status,
                    updated_at = NOW()
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':name'       => $data['name'],
            ':email'      => $data['email'],
            ':role'       => $data['role'],
            ':department' => $data['department'] ?? null,
            ':status'     => $data['status'],
            ':id'         => $id,
        ]);
    }

    public function updatePassword(int $id, string $passwordHash): bool {
        $sql = "UPDATE users 
                SET password_hash = :password_hash, 
                    updated_at = NOW() 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':password_hash' => $passwordHash,
            ':id'            => $id,
        ]);
    }

    public function toggleStatus(int $id): bool {
        $sql = "UPDATE users 
                SET status = CASE WHEN status = 'active' THEN 'inactive' ELSE 'active' END,
                    updated_at = NOW()
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function delete(int $id): bool {
        $check = $this->db->prepare("SELECT COUNT(*) FROM tickets WHERE requester_id = ? OR assigned_to = ?");
        $check->execute([$id, $id]);
        
        if ($check->fetchColumn() > 0) {
            return false;
        }
        
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function getRoles(): array {
        return [
            'user'  => 'Usuário',
            'ti'    => 'TI',
            'admin' => 'Administrador',
        ];
    }

    public static function getDepartments(): array {
        return [
            'RH&DP'                 => 'RH & Desenvolvimento de Pessoas',
            'Gestão da qualidade'   => 'Gestão da Qualidade',
            'CTRL. da qualidade'    => 'Controle da Qualidade',
            'PCP'                   => 'PCP',
            'Administrativo'        => 'Administrativo',
            'Engenharia'            => 'Engenharia',
            'Produção'              => 'Produção',
            'Manutenção'            => 'Manutenção',
            'Expedição'             => 'Expedição',
            'Recebimento'           => 'Recebimento',
            'Setup'                 => 'Setup',
            'TI'                    => 'TI',
        ];
    }

    public static function getRoleLabel(string $role): string {
        $roles = self::getRoles();
        return $roles[$role] ?? $role;
    }

    public static function getDepartmentLabel(?string $department): string {
        if (!$department) return 'Não definido';
        $departments = self::getDepartments();
        return $departments[$department] ?? $department;
    }
}