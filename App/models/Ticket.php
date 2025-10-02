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
            COUNT(*) as total_tickets,
            COUNT(CASE WHEN status = 'open' THEN 1 END) as aguardando_atendimento,
            COUNT(CASE WHEN status = 'in_progress' THEN 1 END) as em_atendimento,
            COUNT(CASE WHEN status = 'closed' THEN 1 END) as resolvidos,
            COUNT(CASE WHEN priority IN ('critical', 'high') AND status != 'closed' THEN 1 END) as urgentes_abertos,
            COUNT(CASE WHEN status = 'closed' AND DATE(updated_at) = CURDATE() THEN 1 END) as resolvidos_hoje,
            COUNT(CASE WHEN DATE(created_at) = CURDATE() THEN 1 END) as novos_hoje,
            COUNT(CASE WHEN assigned_to IS NULL AND status = 'open' THEN 1 END) as sem_responsavel,
            COUNT(CASE WHEN DATEDIFF(NOW(), created_at) > 3 AND status != 'closed' THEN 1 END) as atrasados
        FROM tickets
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
    ";
    
    $stmt = $this->db->query($sql);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $data['total_tickets'] = (int)($data['total_tickets'] ?? 0);
    $data['aguardando_atendimento'] = (int)($data['aguardando_atendimento'] ?? 0);
    $data['em_atendimento'] = (int)($data['em_atendimento'] ?? 0);
    $data['resolvidos'] = (int)($data['resolvidos'] ?? 0);
    $data['urgentes_abertos'] = (int)($data['urgentes_abertos'] ?? 0);
    $data['resolvidos_hoje'] = (int)($data['resolvidos_hoje'] ?? 0);
    $data['novos_hoje'] = (int)($data['novos_hoje'] ?? 0);
    $data['sem_responsavel'] = (int)($data['sem_responsavel'] ?? 0);
    $data['atrasados'] = (int)($data['atrasados'] ?? 0);
    
    $data['taxa_resolucao'] = $data['total_tickets'] > 0 
        ? round(($data['resolvidos'] / $data['total_tickets']) * 100, 1) 
        : 0;
    
    $sqlTime = "
        SELECT AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as tempo_medio
        FROM tickets
        WHERE status = 'closed' 
        AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        AND updated_at IS NOT NULL
    ";
    $stmtTime = $this->db->query($sqlTime);
    $timeData = $stmtTime->fetch(PDO::FETCH_ASSOC);
    $data['tempo_medio_horas'] = $timeData['tempo_medio'] ? round($timeData['tempo_medio'], 1) : 0;
    
    return $data;
}


public function getOpenVsClosedTrend(int $days = 7): array {
    $sql = "
        SELECT 
            DATE(created_at) as data,
            COUNT(*) as total,
            COUNT(CASE WHEN status = 'open' THEN 1 END) as abertos,
            COUNT(CASE WHEN status = 'closed' THEN 1 END) as fechados
        FROM tickets
        WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL :days DAY)
        GROUP BY DATE(created_at)
        ORDER BY data DESC
    ";
    
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':days', $days, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($result)) {
        return [];
    }
    
    return $result;
}

public function getTopDepartments(int $limit = 5): array {
    $sql = "
        SELECT 
            COALESCE(u.department, 'Não definido') as setor,
            COUNT(t.id) as total_chamados,
            COUNT(CASE WHEN t.status = 'open' THEN 1 END) as abertos,
            COUNT(CASE WHEN t.status = 'closed' THEN 1 END) as fechados
        FROM tickets t
        JOIN users u ON u.id = t.requester_id
        WHERE t.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        GROUP BY u.department
        HAVING total_chamados > 0
        ORDER BY total_chamados DESC
        LIMIT :limit
    ";
    
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


public function getTopIssues(int $limit = 10): array {
    $sql = "
        SELECT 
            title,
            priority,
            COUNT(*) as ocorrencias,
            COUNT(CASE WHEN status = 'closed' THEN 1 END) as resolvidos,
            MAX(created_at) as ultima_ocorrencia
        FROM tickets
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        GROUP BY title, priority
        HAVING COUNT(*) > 1
        ORDER BY ocorrencias DESC
        LIMIT :limit
    ";
    
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


public function getTopRequesters(int $limit = 10): array {
    $sql = "
        SELECT 
            u.id,
            u.name,
            u.email,
            COUNT(t.id) as total_chamados,
            COUNT(CASE WHEN t.status = 'open' THEN 1 END) as abertos,
            COUNT(CASE WHEN t.status = 'in_progress' THEN 1 END) as em_andamento,
            COUNT(CASE WHEN t.status = 'closed' THEN 1 END) as fechados,
            MAX(t.created_at) as ultimo_chamado
        FROM users u
        JOIN tickets t ON t.requester_id = u.id
        WHERE t.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        GROUP BY u.id, u.name, u.email
        HAVING total_chamados > 0
        ORDER BY total_chamados DESC
        LIMIT :limit
    ";
    
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                WHEN t.status = 'closed' AND t.updated_at IS NOT NULL
                THEN TIMESTAMPDIFF(HOUR, t.created_at, t.updated_at) 
            END) as tempo_medio_resolucao
        FROM users u
        LEFT JOIN tickets t ON t.assigned_to = u.id 
            AND t.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        WHERE u.role IN ('ti', 'admin') AND u.status = 'active'
        GROUP BY u.id, u.name
        HAVING total_atribuidos > 0
        ORDER BY concluidos DESC
    ";
    
    $stmt = $this->db->query($sql);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($data as &$tech) {
        $tech['total_atribuidos'] = (int)$tech['total_atribuidos'];
        $tech['concluidos'] = (int)$tech['concluidos'];
        $tech['em_andamento'] = (int)$tech['em_andamento'];
        $tech['pendentes'] = (int)$tech['pendentes'];
        
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