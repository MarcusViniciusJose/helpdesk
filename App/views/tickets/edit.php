<?php ob_start(); ?>

<style>
.breadcrumb {
    font-size: 0.875rem;
    padding: 0.5rem 0;
    margin-bottom: 1rem;
}

.breadcrumb-item + .breadcrumb-item {
    padding-left: 0.375rem;
}

.page-title {
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
}

.page-title i {
    font-size: 1.25rem;
}

.form-label {
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.form-control,
.form-select {
    font-size: 16px; 
    padding: 0.75rem;
}

textarea.form-control {
    min-height: 150px;
}

.card {
    border-radius: 8px;
    margin-bottom: 1rem;
}

.card-body {
    padding: 1rem;
}

.card-title {
    font-size: 1rem;
    margin-bottom: 0.75rem;
}

.info-text {
    font-size: 0.875rem;
    line-height: 1.5;
}

.info-text strong {
    display: block;
    margin-bottom: 0.25rem;
    color: #6c757d;
    font-size: 0.8rem;
}

.btn {
    min-height: 44px;
    padding: 0.625rem 1rem;
    font-size: 0.95rem;
}

.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin-top: 1.5rem;
}

.action-buttons .btn {
    width: 100%;
}

.alert {
    font-size: 0.875rem;
    padding: 0.75rem 1rem;
    border-radius: 8px;
}

.form-row {
    margin-bottom: 1rem;
}

@media (min-width: 576px) {
    .page-title {
        font-size: 1.75rem;
    }

    textarea.form-control {
        min-height: 180px;
    }

    .card-body {
        padding: 1.25rem;
    }

    .info-text {
        font-size: 0.9rem;
    }
}

@media (min-width: 768px) {
    .breadcrumb {
        font-size: 0.95rem;
        padding: 0.75rem 0;
        margin-bottom: 1.5rem;
    }

    .page-title {
        font-size: 2rem;
        margin-bottom: 2rem;
    }

    .page-title i {
        font-size: 1.5rem;
    }

    .form-label {
        font-size: 1rem;
    }

    textarea.form-control {
        min-height: 200px;
    }

    .card-body {
        padding: 1.5rem;
    }

    .card-title {
        font-size: 1.1rem;
        margin-bottom: 1rem;
    }

    .action-buttons {
        flex-direction: row;
        margin-top: 2rem;
    }

    .action-buttons .btn {
        width: auto;
    }

    .info-text {
        font-size: 0.95rem;
    }

    .info-text strong {
        font-size: 0.85rem;
    }
}

@media (max-width: 767px) {
    .btn-close {
        padding: 0.5rem;
    }
}
</style>

<div class="container-fluid px-3 px-md-4 py-3 py-md-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/?url=ticket/index">Chamados</a></li>
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/?url=ticket/show&id=<?= $ticket['id'] ?>">Chamado #<?= $ticket['id'] ?></a></li>
            <li class="breadcrumb-item active">Editar</li>
        </ol>
    </nav>

    <h4 class="page-title fw-bold">
        <i class="bi bi-pencil-square me-2"></i>Editar Chamado #<?= (int)$ticket['id'] ?>
    </h4>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-2"></i><?= htmlspecialchars($error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row g-3">
        <div class="col-12 col-lg-8">
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
                        
                        <div class="row g-3 form-row">
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">Prioridade</label>
                                <select name="priority" class="form-select">
                                    <?php 
                                    $currentPriority = $_POST['priority'] ?? $ticket['priority'];
                                    $priorities = [
                                        'low' => '🟢 Baixa',
                                        'medium' => '🟡 Média',
                                        'high' => '🟠 Alta',
                                        'critical' => '🔴 Crítica'
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
                            <div class="col-12 col-md-6">
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
                        
                        <div class="action-buttons">
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
        
        <div class="col-12 col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="card-title fw-semibold">
                        <i class="bi bi-info-circle me-2"></i>Informações
                    </h6>
                    
                    <div class="info-text mb-3">
                        <strong>Solicitante</strong>
                        <span class="d-block text-dark"><?= htmlspecialchars($ticket['requester_name']) ?></span>
                    </div>
                    
                    <div class="info-text mb-3">
                        <strong>Criado em</strong>
                        <span class="d-block text-dark"><?= date('d/m/Y \à\s H:i', strtotime($ticket['created_at'])) ?></span>
                    </div>
                    
                    <?php if($ticket['updated_at']): ?>
                    <div class="info-text mb-0">
                        <strong>Atualizado em</strong>
                        <span class="d-block text-dark"><?= date('d/m/Y \à\s H:i', strtotime($ticket['updated_at'])) ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean(); 
require __DIR__ . '/../layouts/main.php'; 
?>