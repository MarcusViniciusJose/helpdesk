<?php

require_once __DIR__ . '/../../config/config.php';

class Comment{

    private $db;

    public function __construct(){
        $this->db = Database::conn();
    }

    public function create($ticketId, $userId, $content){
        $stmt = $this->db->prepare("INSERT INTO ticket_comments (ticket, user_id, content) VALUES (:ticket_id, :user_id, :content)");
        return $stmt->execute([
            ':ticket_id' => $ticketId,
            ':user_id' => $userId,
            ':content' => $content,
        ]);
    }

    public function getByTicket($ticketId){
        $stmt = $this->db->prepare("SELECT tc.*, u.name as author_name FROM ticket_comments tc JOIN users u ON u.id = tc.user_id WHERE tc.ticket_id = :tc.ticket_id ORDER BY tc.created_at ASC");
        $stmt->execute([':ticket_id' => $ticketId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    }
}