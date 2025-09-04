<?php

require_once __DIR__ . '/../core/Database.php';

class User{

    private PDO $db;

    public function __construct(){
        $this->db = Database::conn();
    }

    public function all(){
        $sql = "SELECT id, name, email, role, status, created_at FROM users ORDER BY name ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByEmail($email) {
        $st = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $st->execute([$email]);
        return $st->fetch(PDO::FETCH_ASSOC);
    }

    public function create(array $data){
        $sql = "INSERT INTO users (name, email, password_hash, role, status, created_at, updated_at)
                VALUES (:name, :email, :password_hash, :role, :status, NOW(), NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':name'          => $data['name'],
            ':email'         => $data['email'],
            ':password_hash' => $data['password_hash'],
            ':role'          => $data['role'],
            ':status'        => $data['status'],
        ]);
        return (int)$this->db ->lastInsertId();
    }

   
}