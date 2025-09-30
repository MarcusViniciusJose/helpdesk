<?php ob_start(); ?>

<nav aria-label="breadcrumb" class="mb-3">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/?url=ticket/index">Chamados</a></li>
    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/?url=ticket/show&id=<?= $ticket['id'] ?>">Chamado #<?= $ticket['id'] ?></a></li>
    <li class="breadcrumb-item active">Editar</li>
  </ol>
</nav>

<h4 class="mb-4">
  <i class="bi bi-pencil-square me-2"></i>Editar Chamado #<?= (int)$ticket['id'] ?>
</h4>

<?php if (!empty($error)): ?>
  <div class="alert alert-danger alert-dismissible fade show">
    <i class="bi bi-exclamation-triangle me-2"></i><?= htmlspecialchars($error) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>

<div class="row">
  <div class="col-lg-8">
    <div class="card shadow-sm">
      <div class="card-body">
        <form method="post" action="<?= BASE_URL ?>/?url=ticket/update">
          <input type="hidden" name="id" value="<?= (int)$ticket['id'] ?>">
          
          <div class="mb-3">
            <label class="form-label fw-semibold">
              Título <span class="text-danger">*</span>
            </label>
            <input type="text" 
                   name="title" 
                   class="form-control" 
                   required 
                   value="<?= htmlspecialchars($_POST['title'] ?? $ticket['title']) ?>">
          </div>
          
          <div class="mb-3">
            <label class="form-label fw-semibold">
              Descrição <span class="text-danger">*</span>
            </label>
            <textarea name="description" 
                      class="form-control" 
                      rows="8" 
                      required><?= htmlspecialchars($_POST['description'] ?? $ticket['description']) ?></textarea>
          </div>
          
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-semibold">Prioridade</label>
              <select name="priority" class="form-select">
                <?php 
                $currentPriority = $_POST['priority'] ?? $ticket['priority'];
                $priorities = [
                  'low' => 'Baixa',
                  'medium' => 'Média',
                  'high' => 'Alta',
                  'critical' => 'Crítica'
                ];
                foreach($priorities as $value => $label): 
                ?>
                  <option value="<?= $value ?>" <?= $currentPriority == $value ? 'selected' : '' ?>>
                    <?= $label ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            
            <?php if(in_array(Auth::user()['role'], ['admin', 'ti'])): ?>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-semibold">Status</label>
              <select name="status" class="form-select">
                <?php 
                $currentStatus = $_POST['status'] ?? $ticket['status'];
                $statuses = [
                  'open' => 'Aberto',
                  'in_progress' => 'Em Andamento',
                  'closed' => 'Fechado'
                ];
                foreach($statuses as $value => $label): 
                ?>
                  <option value="<?= $value ?>" <?= $currentStatus == $value ? 'selected' : '' ?>>
                    <?= $label ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <?php else: ?>
              <input type="hidden" name="status" value="<?= htmlspecialchars($ticket['status']) ?>">
            <?php endif; ?>
          </div>
          
          <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-success">
              <i class="bi bi-check-lg me-1"></i>Salvar Alterações
            </button>
            <a class="btn btn-outline-secondary" href="<?= BASE_URL ?>/?url=ticket/show&id=<?= $ticket['id'] ?>">
              <i class="bi bi-x-lg me-1"></i>Cancelar
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
  
  <div class="col-lg-4">
    <div class="card shadow-sm">
      <div class="card-body">
        <h6 class="card-title mb-3">
          <i class="bi bi-info-circle me-2"></i>Informações
        </h6>
        <p class="mb-2">
          <strong>Solicitante:</strong><br>
          <?= htmlspecialchars($ticket['requester_name']) ?>
        </p>
        <p class="mb-2">
          <strong>Criado em:</strong><br>
          <?= date('d/m/Y \à\s H:i', strtotime($ticket['created_at'])) ?>
        </p>
        <?php if($ticket['updated_at']): ?>
        <p class="mb-0">
          <strong>Atualizado em:</strong><br>
          <?= date('d/m/Y \à\s H:i', strtotime($ticket['updated_at'])) ?>
        </p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php 
$content = ob_get_clean(); 
require __DIR__ . '/../layouts/main.php'; 
?>