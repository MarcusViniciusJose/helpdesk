<?php

require_once __DIR__ . '/../../config/config.php';

class Comment {

    private PDO $db;

    public function __construct(){
        $this->db = Database::conn();
    }

    public function create($ticketId, $userId, $content){
        $stmt = $this->db->prepare("
            INSERT INTO ticket_comments (ticket_id, user_id, content, created_at) 
            VALUES (:ticket_id, :user_id, :content, NOW())
        ");
        return $stmt->execute([
            ':ticket_id' => $ticketId,
            ':user_id'   => $userId,
            ':content'   => $content,
        ]);
    }

    public function getByTicket($ticketId){
        $stmt = $this->db->prepare("
            SELECT tc.*, u.name AS author_name 
            FROM ticket_comments tc 
            JOIN users u ON u.id = tc.user_id 
            WHERE tc.ticket_id = :ticket_id 
            ORDER BY tc.created_at ASC
        ");
        $stmt->execute([':ticket_id' => $ticketId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
