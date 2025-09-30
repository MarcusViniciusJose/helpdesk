<?php 
ob_start(); 

function getStatusBadge($status) {
    $badges = [
        'open'        => '<span class="badge bg-primary">Aberto</span>',
        'in_progress' => '<span class="badge bg-warning">Em Andamento</span>',
        'closed'      => '<span class="badge bg-success">Fechado</span>',
    ];
    return $badges[$status] ?? '<span class="badge bg-secondary">' . htmlspecialchars($status) . '</span>';
}

function getPriorityBadge($priority) {
    $badges = [
        'low'      => '<span class="badge bg-info">Baixa</span>',
        'medium'   => '<span class="badge bg-secondary">Média</span>',
        'high'     => '<span class="badge bg-warning text-dark">Alta</span>',
        'critical' => '<span class="badge bg-danger">Crítica</span>',
    ];
    return $badges[$priority] ?? '<span class="badge bg-secondary">' . htmlspecialchars($priority) . '</span>';
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="mb-0">
    <i class="bi bi-ticket-perforated me-2"></i>Meus Chamados
  </h4>
  <a class="btn btn-primary" href="<?= BASE_URL ?>/?url=ticket/create">
    <i class="bi bi-plus-circle me-1"></i>Abrir Chamado
  </a>
</div>

<?php if (isset($_SESSION['success'])): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle me-2"></i><?= htmlspecialchars($_SESSION['success']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-triangle me-2"></i><?= htmlspecialchars($_SESSION['error']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="card shadow-sm">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th class="ps-3" style="width: 60px;">#</th>
            <th>Título</th>
            <th style="width: 200px;">Solicitante</th>
            <th style="width: 120px;">Prioridade</th>
            <th style="width: 140px;">Status</th>
            <th style="width: 160px;">Criado em</th>
            <th class="text-center pe-3" style="width: 100px;">Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($tickets)): ?>
          <tr>
            <td colspan="7" class="text-center text-muted py-5">
              <i class="bi bi-inbox fs-1 d-block mb-2"></i>
              Nenhum chamado encontrado.
            </td>
          </tr>
          <?php else: ?>
            <?php foreach ($tickets as $t): ?>
            <tr>
              <td class="ps-3 fw-bold"><?= (int)$t['id'] ?></td>
              <td>
                <a href="<?= BASE_URL ?>/?url=ticket/show&id=<?= $t['id'] ?>" class="text-decoration-none">
                  <?= htmlspecialchars($t['title']) ?>
                </a>
                <?php if(!empty($t['assigned_name'])): ?>
                  <br><small class="text-muted">
                    <i class="bi bi-person-check me-1"></i><?= htmlspecialchars($t['assigned_name']) ?>
                  </small>
                <?php endif; ?>
              </td>
              <td><?= htmlspecialchars($t['requester_name']) ?></td>
              <td><?= getPriorityBadge($t['priority']) ?></td>
              <td><?= getStatusBadge($t['status']) ?></td>
              <td>
                <small><?= date('d/m/Y H:i', strtotime($t['created_at'])) ?></small>
              </td>
              <td class="text-center pe-3">
                <a href="<?= BASE_URL ?>/?url=ticket/show&id=<?= $t['id'] ?>" 
                   class="btn btn-sm btn-outline-primary" 
                   title="Visualizar">
                  <i class="bi bi-eye"></i>
                </a>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php 
$content = ob_get_clean(); 
require __DIR__ . '/../layouts/main.php'; 
?>