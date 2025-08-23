<?php

class User{

    private PDO $db;

    public function __construct(){
        $this->db = Database::conn();
    }

    public function findByEmail($email) {
        $st = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $st->execute([$email]);
        return $st->fetch(PDO::FETCH_ASSOC);
    }

    public function create($name, $email, $passwordHash, $role='user'){
        $st = $this->db->prepare("INSERT INTO user (name, email, password_hash, role) VALUES (?, ?, ?, ?)");
        return $st->execute([$name, $email, $passwordHash, $role]);
    }
}