<?php

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../core/Database.php';

class Notification {
    
    private PDO $db;

    public function __construct(){
        $this->db = Database::conn();
    }

    public function create($userId, $message, $link = null, $relatedTicketId = null){
        $stmt = $this->db->prepare("
            INSERT INTO notifications (user_id, message, link, related_ticket_id, created_at) 
            VALUES (:user_id, :message, :link, :related, NOW())
        ");
        return $stmt->execute([
            ':user_id' => $userId,
            ':message' => $message,
            ':link'    => $link,
            ':related' => $relatedTicketId
        ]);
    }

    public function createMultiple(array $userIds, $message, $link = null, $relatedTicketId = null){
        foreach ($userIds as $userId) {
            $this->create($userId, $message, $link, $relatedTicketId);
        }
    }

    public function notifyAdminsAndTI($message, $link = null, $relatedTicketId = null){
        $stmt = $this->db->query("SELECT id FROM users WHERE role IN ('admin', 'ti') AND status = 'active'");
        $users = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (!empty($users)) {
            $this->createMultiple($users, $message, $link, $relatedTicketId);
        }
    }

    public function getUnreadByUser($userId){
        $stmt = $this->db->prepare("
            SELECT id, message, link, created_at 
            FROM notifications 
            WHERE user_id = :user AND is_read = 0 
            ORDER BY created_at DESC
        ");
        $stmt->execute([':user' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function markAsRead($id){
        $stmt = $this->db->prepare("UPDATE notifications SET is_read = 1 WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function markAllAsRead($userId){
        $stmt = $this->db->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = :user");
        return $stmt->execute([':user' => $userId]);
    }

    public function delete($id, $userId){
        $stmt = $this->db->prepare("DELETE FROM notifications WHERE id = :id AND user_id = :user");
        return $stmt->execute([':id' => $id, ':user' => $userId]);
    }

   
    public function deleteAllRead($userId){
        $stmt = $this->db->prepare("DELETE FROM notifications WHERE user_id = :user AND is_read = 1");
        return $stmt->execute([':user' => $userId]);
    }

    
    public function deleteAll($userId){
        $stmt = $this->db->prepare("DELETE FROM notifications WHERE user_id = :user");
        return $stmt->execute([':user' => $userId]);
    }

    public function countByUser($userId){
        $stmt = $this->db->prepare("
            SELECT 
                SUM(CASE WHEN is_read = 0 THEN 1 ELSE 0 END) AS unread, 
                COUNT(*) AS total 
            FROM notifications 
            WHERE user_id = :user
        ");
        $stmt->execute([':user' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteByTicketId($ticketId){
        $stmt = $this->db->prepare("DELETE FROM notifications WHERE related_ticket_id = :tid");
        return $stmt->execute([':tid' => $ticketId]);
    }

    public function getAllByUser($userId, $limit = 100){
        $stmt = $this->db->prepare("
            SELECT * 
            FROM notifications 
            WHERE user_id = :user 
            ORDER BY created_at DESC 
            LIMIT :lim
        ");
        $stmt->bindValue(':user', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':lim', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}