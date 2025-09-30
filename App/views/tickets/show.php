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

$user = Auth::user();
$isAdminOrTI = in_array($user['role'], ['admin', 'ti']);
$canEdit = $isAdminOrTI || ($ticket['requester_id'] == $user['id'] && $ticket['status'] === 'open');
?>

<nav aria-label="breadcrumb" class="mb-3">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/?url=ticket/index">Chamados</a></li>
    <li class="breadcrumb-item active">Chamado #<?= $ticket['id'] ?></li>
  </ol>
</nav>

<?php if (isset($_SESSION['success'])): ?>
  <div class="alert alert-success alert-dismissible fade show">
    <i class="bi bi-check-circle me-2"></i><?= htmlspecialchars($_SESSION['success']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
  <div class="alert alert-danger alert-dismissible fade show">
    <i class="bi bi-exclamation-triangle me-2"></i><?= htmlspecialchars($_SESSION['error']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-start mb-4">
  <div>
    <h4 class="mb-2">
      <i class="bi bi-ticket-detailed me-2"></i>
      Chamado #<?= (int)$ticket['id'] ?>
    </h4>
    <h5 class="text-muted fw-normal"><?= htmlspecialchars($ticket['title']) ?></h5>
  </div>
  <div class="d-flex gap-2">
    <?php if($canEdit): ?>
      <a class="btn btn-outline-primary" href="<?= BASE_URL ?>/?url=ticket/edit&id=<?= $ticket['id'] ?>">
        <i class="bi bi-pencil me-1"></i>Editar
      </a>
    <?php endif; ?>
    
    <?php if($isAdminOrTI): ?>
      <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
        <i class="bi bi-trash me-1"></i>Excluir
      </button>
    <?php endif; ?>
    
    <a class="btn btn-outline-secondary" href="<?= BASE_URL ?>/?url=ticket/index">
      <i class="bi bi-arrow-left me-1"></i>Voltar
    </a>
  </div>
</div>

<div class="row">
  <div class="col-lg-8">
    <div class="card shadow-sm mb-4">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <div>
            <p class="text-muted small mb-1">
              <i class="bi bi-person me-1"></i>
              <strong>Solicitante:</strong> <?= htmlspecialchars($ticket['requester_name']) ?>
            </p>
            <p class="text-muted small mb-0">
              <i class="bi bi-calendar me-1"></i>
              <strong>Aberto em:</strong> <?= date('d/m/Y \à\s H:i', strtotime($ticket['created_at'])) ?>
            </p>
          </div>
          <div class="text-end">
            <?= getPriorityBadge($ticket['priority']) ?>
            <?= getStatusBadge($ticket['status']) ?>
          </div>
        </div>

        <hr>

        <div class="mb-4">
          <h6 class="mb-3"><i class="bi bi-file-text me-2"></i>Descrição</h6>
          <div class="bg-light p-3 rounded">
            <?= nl2br(htmlspecialchars($ticket['description'])) ?>
          </div>
        </div>

        <?php if (!empty($ticket['assigned_name'])): ?>
          <div class="alert alert-info mb-0">
            <i class="bi bi-person-check me-2"></i>
            <strong>Atribuído a:</strong> <?= htmlspecialchars($ticket['assigned_name']) ?>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <?php if($isAdminOrTI || $ticket['requester_id'] == $user['id']): ?>
    <div class="card shadow-sm mb-4">
      <div class="card-body">
        <h6 class="card-title mb-3">
          <i class="bi bi-sliders me-2"></i>Ações Rápidas
        </h6>
        
        <div class="row g-3">
          <div class="col-md-6">
            <form method="post" action="<?= BASE_URL ?>/?url=ticket/changeStatus">
              <input type="hidden" name="ticket_id" value="<?= (int)$ticket['id'] ?>">
              <label class="form-label small fw-semibold">Alterar Status</label>
              <div class="input-group">
                <select name="status" class="form-select">
                  <option value="open" <?= $ticket['status']=='open' ? 'selected':'' ?>>Aberto</option>
                  <option value="in_progress" <?= $ticket['status']=='in_progress' ? 'selected':'' ?>>Em Andamento</option>
                  <option value="closed" <?= $ticket['status']=='closed' ? 'selected':'' ?>>Fechado</option>
                </select>
                <button class="btn btn-primary" type="submit">
                  <i class="bi bi-check-lg"></i>
                </button>
              </div>
            </form>
          </div>

          <?php if ($isAdminOrTI): ?>
          <div class="col-md-6">
            <form method="post" action="<?= BASE_URL ?>/?url=ticket/assign">
              <input type="hidden" name="ticket_id" value="<?= (int)$ticket['id'] ?>">
              <label class="form-label small fw-semibold">Atribuir Técnico</label>
              <div class="input-group">
                <select name="tech_id" class="form-select">
                  <option value="">-- Selecione --</option>
                  <?php foreach ($techs as $t): ?>
                    <option value="<?= $t['id'] ?>" <?= ($ticket['assigned_to'] == $t['id']) ? 'selected' : '' ?>>
                      <?= htmlspecialchars($t['name']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <button class="btn btn-success" type="submit">
                  <i class="bi bi-person-plus"></i>
                </button>
              </div>
            </form>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <div class="card shadow-sm">
      <div class="card-body">
        <h5 class="card-title mb-4">
          <i class="bi bi-chat-left-text me-2"></i>
          Comentários
          <span class="badge bg-secondary"><?= count($comments) ?></span>
        </h5>

        <?php if (empty($comments)): ?>
          <div class="text-center text-muted py-4">
            <i class="bi bi-chat-square-dots fs-1 d-block mb-2"></i>
            <p>Nenhum comentário ainda. Seja o primeiro a comentar!</p>
          </div>
        <?php else: ?>
          <div class="mb-4">
            <?php foreach ($comments as $c): ?>
              <div class="border rounded p-3 mb-3 bg-light">
                <div class="d-flex justify-content-between align-items-start mb-2">
                  <div>
                    <strong class="text-primary">
                      <i class="bi bi-person-circle me-1"></i>
                      <?= htmlspecialchars($c['author_name']) ?>
                    </strong>
                  </div>
                  <small class="text-muted">
                    <i class="bi bi-clock me-1"></i>
                    <?= date('d/m/Y H:i', strtotime($c['created_at'])) ?>
                  </small>
                </div>
                <div class="ms-4">
                  <?= nl2br(htmlspecialchars($c['content'])) ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <div class="border-top pt-4">
          <h6 class="mb-3">Adicionar Comentário</h6>
          <form method="post" action="<?= BASE_URL ?>/?url=ticket/storeComment">
            <input type="hidden" name="ticket_id" value="<?= (int)$ticket['id'] ?>">
            <div class="mb-3">
              <textarea name="content" 
                        rows="4" 
                        class="form-control" 
                        placeholder="Escreva seu comentário..." 
                        required></textarea>
            </div>
            <button class="btn btn-primary">
              <i class="bi bi-send me-1"></i>Enviar Comentário
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card shadow-sm mb-3">
      <div class="card-body">
        <h6 class="card-title mb-3">
          <i class="bi bi-info-circle me-2"></i>Detalhes
        </h6>
        
        <div class="mb-3">
          <small class="text-muted d-block mb-1">Prioridade</small>
          <?= getPriorityBadge($ticket['priority']) ?>
        </div>

        <div class="mb-3">
          <small class="text-muted d-block mb-1">Status</small>
          <?= getStatusBadge($ticket['status']) ?>
        </div>

        <div class="mb-3">
          <small class="text-muted d-block mb-1">Solicitante</small>
          <strong><?= htmlspecialchars($ticket['requester_name']) ?></strong>
        </div>

        <?php if (!empty($ticket['assigned_name'])): ?>
        <div class="mb-3">
          <small class="text-muted d-block mb-1">Atribuído a</small>
          <strong class="text-success">
            <i class="bi bi-person-check me-1"></i>
            <?= htmlspecialchars($ticket['assigned_name']) ?>
          </strong>
        </div>
        <?php endif; ?>

        <hr>

        <div class="mb-2">
          <small class="text-muted d-block mb-1">Criado em</small>
          <small><?= date('d/m/Y \à\s H:i', strtotime($ticket['created_at'])) ?></small>
        </div>

        <?php if($ticket['updated_at']): ?>
        <div>
          <small class="text-muted d-block mb-1">Última atualização</small>
          <small><?= date('d/m/Y \à\s H:i', strtotime($ticket['updated_at'])) ?></small>
        </div>
        <?php endif; ?>
      </div>
    </div>

    

<?php if($isAdminOrTI): ?>
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="deleteModalLabel">
          <i class="bi bi-exclamation-triangle me-2"></i>Confirmar Exclusão
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="mb-0">Tem certeza que deseja excluir este chamado?</p>
        <p class="text-muted small mb-0">Esta ação não pode ser desfeita e todos os comentários serão removidos.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <form method="post" action="<?= BASE_URL ?>/?url=ticket/delete" class="d-inline">
          <input type="hidden" name="id" value="<?= (int)$ticket['id'] ?>">
          <button type="submit" class="btn btn-danger">
            <i class="bi bi-trash me-1"></i>Excluir Definitivamente
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<?php 
$content = ob_get_clean(); 
require __DIR__ . '/../layouts/main.php'; 
?>