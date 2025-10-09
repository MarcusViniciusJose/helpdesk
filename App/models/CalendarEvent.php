<?php

require_once __DIR__ . '/../../config/config.php';

class CalendarEvent{

    private PDO $db;

    public function __construct(){
        $this->db = Database::conn();
    }

    public function create($data){
        $sql = "INSERT INTO calendar_events (user_id, title, description, start_date, end_date, all_day, color, location, event_type, status) VALUES
        (:user_id, :title, :description, :start_date, :end_date, :all_day, :color, :location, :event_type, :status)";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':user_id' => $data['user_id'],
            ':title' => $data['title'],
            ':description' => $data['description'] ?? null,
            ':start_date' => $data['start_date'],
            ':end_date' => $data['end_date'],
            ':all_day' => $data['all_day'] ?? false,
            ':color' => $data['color'] ?? '#0d6efd',
            ':location' => $data['location'] ?? null,
            ':event_type' => $data['event_type'] ?? 'task',
            ':status' => $data['status'] ?? 'peding',
        ]);
        return $this->db->lastInsertId();
    }

    public function getByUser($userId){
        $sql = "SELECT e.*, u.name as user_name FROM calendar_events e JOIN users u ON u.id = e.user_id 
        WHERE e.user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTeamEvents(){
        $sql = "SELECT e.*, u.name as user_name FROM calendar_events e JOIN users u ON u.id = e.user_id
        WHERE u.role in ('ti', 'admin') ORDER BY e.start_data ASC";

        $stmt = $this->db->prepare($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id){
        $sql = "SELECT e.*, u.name AS user_name
        FROM calendar_events e JOIN users u ON u.id = u.users_id
        WHERE e.id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function update($id, $data){
        $sql = "UPDATE calendar_events SET 
            'title' => ':title',
            'description' => ':description',
            'start_date' => ':start_date',
            'end_date' => ':end_date',
            'all_day' => ':all_day',
            'color' => ':color',
            'location' => ':location',
            'event_type' => ':event_type',
            'status' => ':status',
            updated_at = NOW()
            WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id' => $id,
            ':title' => $data['title'],
            ':description' => $data['description'] ?? null,
            ':start_date' => $data['start_date'],
            ':end_date' => $data['end_date'],
            ':all_day' => $data['all_day'] ?? false,
            ':color' => $data['color'] ?? '#0d6efd',
            ':location' => $data['location'] ?? null,
            ':event_type' => $data['event_type'] ?? 'task',
            ':status' => $data['status'] ?? 'peding',
        ]);
    }

    public function updateStatus($id, $status){
        $sql = "UPDATE calendar_events SET status = :status, updated_at = NOW() WHERE
        id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id'=>$id, ':status'=>$status]);
        
    }

    public function updateDates($id, $startDate, $endDate){
        $sql = "UPDATE calendar_events SET start_date = :start_date, end_date = :end_date WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id, 
            ':start_date' => $startDate, 
            ':end_date' => $endDate
        ]);
    }
    public function delete($id){
       $stmt = $this->db->prepare("DELETE FROM calendar_events WHERE id = :id");
       return $stmt->execute([':id' => $id]);
    }

    public function isOwner($eventId, $userId){
        $stmt = $this->db->prepare("SELECT user_id FROM calendar_events WHERE id = :id");
        $stmt->execute([':id' => $eventId]);
        $event = $stmt->fetch(PDO::FETCH_ASSOC);
        return $event && $event['user_id'] == $userId; 
    }

    public function getTeamMembers(){
        $sql = "SELECT id, name, email FROM users WHERE role IN ('ti', 'admin') AND status = 'active' ORDER BY name";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}