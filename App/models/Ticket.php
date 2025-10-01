<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../core/Database.php';

class Ticket {

    private PDO $db;

    public function __construct(){
        $this->db = Database::conn();
    }
    
    public function getStatsByUser($user){
        $isAdminOrTI = in_array($user['role'], ['admin', 'ti'], true);
        
        if ($isAdminOrTI) {
            $stmt = $this->db->query(" SELECT COUNT(CASE WHEN status = 'open' THEN 1 END) as abertos,
                    COUNT(CASE WHEN status = 'in_progress' THEN 1 END) as em_andamento,
                    COUNT(CASE WHEN status = 'closed' THEN 1 END) as fechados,
                    COUNT(*) as total
                FROM tickets
            ");
        } else {
            $stmt = $this->db->prepare("SELECT COUNT(CASE WHEN status = 'open' THEN 1 END) as abertos,
                    COUNT(CASE WHEN status = 'in_progress' THEN 1 END) as em_andamento,
                    COUNT(CASE WHEN status = 'closed' THEN 1 END) as fechados,
                    COUNT(*) as total
                FROM tickets 
                WHERE requester_id = ?
            ");
            $stmt->execute([$user['id']]);
        }
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return [
            'abertos'       => (int)($result['abertos'] ?? 0),
            'em_andamento'  => (int)($result['em_andamento'] ?? 0),
            'fechados'      => (int)($result['fechados'] ?? 0),
            'total'         => (int)($result['total'] ?? 0),
        ];
    }
    
    public function allForUser($user){
        if (in_array($user['role'], ['admin', 'ti'], true)) {
            $st = $this->db->query("
                SELECT t.*, u.name AS requester_name,
                       ua.name AS assigned_name
                FROM tickets t 
                JOIN users u ON u.id = t.requester_id 
                LEFT JOIN users ua ON ua.id = t.assigned_to
                ORDER BY t.created_at DESC
            ");
            return $st->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $st = $this->db->prepare("
                SELECT t.*, u.name AS requester_name,
                       ua.name AS assigned_name
                FROM tickets t 
                JOIN users u ON u.id = t.requester_id 
                LEFT JOIN users ua ON ua.id = t.assigned_to
                WHERE requester_id = ? 
                ORDER BY t.created_at DESC
            ");
            $st->execute([$user['id']]);
            return $st->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    public function create($data){
        $st = $this->db->prepare("
            INSERT INTO tickets (requester_id, title, description, priority, status, created_at) 
            VALUES (:requester_id, :title, :description, :priority, 'open', NOW())
        ");
        $st->execute([
            ':requester_id' => $data['requester_id'],
            ':title'        => $data['title'],
            ':description'  => $data['description'],
            ':priority'     => $data['priority'],
        ]);
        return $this->db->lastInsertId();
    }

    public function getById($id){
        $stmt = $this->db->prepare("
            SELECT t.*, u.name AS requester_name,
                   ua.name AS assigned_name
            FROM tickets t 
            JOIN users u ON u.id = t.requester_id 
            LEFT JOIN users ua ON ua.id = t.assigned_to
            WHERE t.id = :id
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data){
        $stmt = $this->db->prepare("
            UPDATE tickets 
            SET title = :title,
                description = :description,
                priority = :priority,
                status = :status,
                updated_at = NOW() 
            WHERE id = :id
        ");
        return $stmt->execute([
            ':title'       => $data['title'],
            ':description' => $data['description'],
            ':priority'    => $data['priority'],
            ':status'      => $data['status'],
            ':id'          => $id
        ]);
    }

    public function updateStatus($id, $status){
        $stmt = $this->db->prepare("
            UPDATE tickets 
            SET status = :status, updated_at = NOW() 
            WHERE id = :id
        ");
        return $stmt->execute([
            ':status' => $status, 
            ':id'     => $id
        ]);
    }

    public function assignTo($id, $userId){
        $stmt = $this->db->prepare("
            UPDATE tickets 
            SET assigned_to = :uid, updated_at = NOW() 
            WHERE id = :id
        ");
        return $stmt->execute([
            ':uid' => $userId,
            ':id'  => $id
        ]);
    }

    public function delete($id){
        $stmt = $this->db->prepare("DELETE FROM tickets_comments WHERE ticket_id = :id");
        $stmt->execute([':id' => $id]);
        
        $stmt = $this->db->prepare("DELETE FROM tickets WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function canUserEdit($ticketId, $user){
        if (in_array($user['role'], ['admin', 'ti'], true)) {
            return true;
        }
        
        $ticket = $this->getById($ticketId);
        return $ticket && 
               $ticket['requester_id'] == $user['id'] && 
               $ticket['status'] === 'open';
    }
public function getManagementKPIs(): array {
    $sql = "
        SELECT 
            -- Totais básicos
            COUNT(*) as total_tickets,
            COUNT(CASE WHEN status = 'open' THEN 1 END) as aguardando_atendimento,
            COUNT(CASE WHEN status = 'in_progress' THEN 1 END) as em_atendimento,
            COUNT(CASE WHEN status = 'closed' THEN 1 END) as resolvidos,
            
            -- Prioridades críticas
            COUNT(CASE WHEN priority IN ('critical', 'high') AND status != 'closed' THEN 1 END) as urgentes_abertos,
            
            -- Performance
            COUNT(CASE WHEN status = 'closed' AND DATE(updated_at) = CURDATE() THEN 1 END) as resolvidos_hoje,
            COUNT(CASE WHEN DATE(created_at) = CURDATE() THEN 1 END) as novos_hoje,
            
            -- Alertas
            COUNT(CASE WHEN assigned_to IS NULL AND status = 'open' THEN 1 END) as sem_responsavel,
            COUNT(CASE WHEN DATEDIFF(NOW(), created_at) > 3 AND status != 'closed' THEN 1 END) as atrasados
        FROM tickets
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
    ";
    
    $stmt = $this->db->query($sql);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $data['taxa_resolucao'] = $data['total_tickets'] > 0 
        ? round(($data['resolvidos'] / $data['total_tickets']) * 100, 1) 
        : 0;
    
    $sqlTime = "
        SELECT AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as tempo_medio
        FROM tickets
        WHERE status = 'closed' 
        AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
    ";
    $stmtTime = $this->db->query($sqlTime);
    $timeData = $stmtTime->fetch(PDO::FETCH_ASSOC);
    $data['tempo_medio_horas'] = $timeData['tempo_medio'] ? round($timeData['tempo_medio'], 1) : 0;
    
    return $data;
}

public function getTechPerformance(): array {
    $sql = "
        SELECT 
            u.name as tecnico,
            COUNT(t.id) as total_atribuidos,
            COUNT(CASE WHEN t.status = 'closed' THEN 1 END) as concluidos,
            COUNT(CASE WHEN t.status = 'in_progress' THEN 1 END) as em_andamento,
            COUNT(CASE WHEN t.status = 'open' THEN 1 END) as pendentes,
            AVG(CASE 
                WHEN t.status = 'closed' 
                THEN TIMESTAMPDIFF(HOUR, t.created_at, t.updated_at) 
            END) as tempo_medio_resolucao
        FROM users u
        LEFT JOIN tickets t ON t.assigned_to = u.id 
            AND t.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        WHERE u.role IN ('ti', 'admin')
        GROUP BY u.id, u.name
        HAVING total_atribuidos > 0
        ORDER BY concluidos DESC
    ";
    
    $stmt = $this->db->query($sql);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($data as &$tech) {
        $tech['taxa_conclusao'] = $tech['total_atribuidos'] > 0 
            ? round(($tech['concluidos'] / $tech['total_atribuidos']) * 100, 1) 
            : 0;
        $tech['tempo_medio_resolucao'] = $tech['tempo_medio_resolucao'] 
            ? round($tech['tempo_medio_resolucao'], 1) 
            : 0;
    }
    
    return $data;
}
}