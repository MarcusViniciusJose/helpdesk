<?php

class Ticket{

    private PDO $db;
    public function __construct(){
        $this->db = Database::conn();
    }
    
    public function allForUser($user){
        if(in_array($user['role'],['admin', 'ti'], true)){
            $st=$this->db->query("SELECT t.*, u.name, AS requester_name FROM tickets t JOIN users u ON u.id =  t.requester_id ORDER BY t.created_at DESC ");
            return $st->fetchAll(PDO::FETCH_ASSOC);
        }else{
            $st = $this->db->prepare("SELECT t.*, u.name AS requester_name FROM tickets t JOIN users u ON u.id = t.requester_id WHERE requester_id = ? ORDER BY t.created_at DESC");
            $st->execute([$user['id']]);
            return $st->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    public function create($data){
        $st = $this->db->prepare("INSERT INTO tickets (requester_id, title, description, priority, status) VALUES (:requester_id, :title, :description, :priority, 'open')");
        $st->execute([
            ':requested_id' => $data['requested_id'],
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':priority' => $data['priority'],
        ]);
        return $this->db->lastInsertId();
    }

    public function getById($id){
        $stmt = $this->db->prepare("SELECT t.*, u.name AS requester_name FROM tickets t JOIN users u ON u.id = t.requester_id WHERE t.id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $status){
        $stmt = $this->db->prepare("UPDATE tickets SET status = :status, updated_at = NOW() WHERE id = :id");
        $stmt->execute([':status' => $status, 
        ':id' => $id
        ]);
    }

    public function assignTo($id, $userId){
        $stmt = $this->db->prepare("UPDATE tickets SET assigned_to = :uid, update_at = NOW() WHERE id = :id");
        return $stmt->execute([
            ':uid' => $userId,
            ':id' => $id
        ]);
    }

}
