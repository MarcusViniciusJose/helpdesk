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

<style>
.page-header {
    margin-bottom: 1.5rem;
}

.page-header h4 {
    font-size: 1.5rem;
    margin-bottom: 0.75rem;
}

.page-header .btn {
    width: 100%;
    min-height: 44px;
}

.alert {
    font-size: 0.875rem;
    padding: 0.75rem 1rem;
    border-radius: 8px;
}

.card {
    border-radius: 8px;
}

.ticket-card-mobile {
    border-bottom: 1px solid #dee2e6;
    padding: 1rem;
}

.ticket-card-mobile:last-child {
    border-bottom: none;
}

.ticket-card-mobile:hover {
    background-color: #f8f9fa;
}

.ticket-id {
    font-size: 0.875rem;
    font-weight: 600;
    color: #6c757d;
}

.ticket-title {
    font-size: 1rem;
    font-weight: 500;
    margin: 0.25rem 0 0.5rem 0;
    line-height: 1.4;
}

.ticket-title a {
    color: #212529;
    text-decoration: none;
}

.ticket-title a:hover {
    color: #0d6efd;
}

.ticket-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: 0.75rem;
}

.ticket-meta .badge {
    font-size: 0.75rem;
}

.ticket-info {
    font-size: 0.8rem;
    color: #6c757d;
    margin-bottom: 0.5rem;
}

.ticket-info i {
    width: 16px;
}

.ticket-actions {
    display: flex;
    gap: 0.5rem;
}

.ticket-actions .btn {
    flex: 1;
    min-height: 38px;
    font-size: 0.875rem;
}

.table-view {
    display: none;
}

.empty-state {
    padding: 3rem 1rem;
    text-align: center;
}

.empty-state i {
    font-size: 3rem;
    color: #dee2e6;
}

@media (min-width: 576px) {
    .page-header h4 {
        font-size: 1.75rem;
    }

    .ticket-card-mobile {
        padding: 1.25rem;
    }

    .ticket-title {
        font-size: 1.1rem;
    }

    .ticket-actions .btn {
        flex: initial;
        min-width: 120px;
    }
}

@media (min-width: 768px) {
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .page-header h4 {
        font-size: 2rem;
        margin-bottom: 0;
    }

    .page-header .btn {
        width: auto;
    }

    .mobile-view {
        display: none !important;
    }

    .table-view {
        display: block;
    }

    .table {
        font-size: 0.9rem;
    }

    .table th {
        font-size: 0.85rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .table td {
        vertical-align: middle;
    }

    .badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
    }

    .empty-state {
        padding: 4rem 1rem;
    }

    .empty-state i {
        font-size: 4rem;
    }
}

@media (max-width: 991px) {
    .table-view .hide-md {
        display: none;
    }
}
</style>

<div class="container-fluid px-3 px-md-4 py-3 py-md-4">
    <div class="page-header">
        <h4 class="mb-0 fw-bold">
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
        <div class="mobile-view d-md-none">
            <?php if (empty($tickets)): ?>
                <div class="empty-state">
                    <i class="bi bi-inbox d-block mb-3"></i>
                    <p class="text-muted mb-0">Nenhum chamado encontrado.</p>
                </div>
            <?php else: ?>
                <?php foreach ($tickets as $t): ?>
                <div class="ticket-card-mobile">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="ticket-id">#<?= (int)$t['id'] ?></span>
                    </div>
                    
                    <h6 class="ticket-title">
                        <a href="<?= BASE_URL ?>/?url=ticket/show&id=<?= $t['id'] ?>">
                            <?= htmlspecialchars($t['title']) ?>
                        </a>
                    </h6>
                    
                    <div class="ticket-meta">
                        <?= getPriorityBadge($t['priority']) ?>
                        <?= getStatusBadge($t['status']) ?>
                    </div>
                    
                    <div class="ticket-info">
                        <div class="mb-1">
                            <i class="bi bi-person me-1"></i>
                            <?= htmlspecialchars($t['requester_name']) ?>
                        </div>
                        <?php if(!empty($t['assigned_name'])): ?>
                        <div class="mb-1">
                            <i class="bi bi-person-check me-1"></i>
                            Responsável: <?= htmlspecialchars($t['assigned_name']) ?>
                        </div>
                        <?php endif; ?>
                        <div>
                            <i class="bi bi-calendar me-1"></i>
                            <?= date('d/m/Y H:i', strtotime($t['created_at'])) ?>
                        </div>
                    </div>
                    
                    <div class="ticket-actions">
                        <a href="<?= BASE_URL ?>/?url=ticket/show&id=<?= $t['id'] ?>" 
                           class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye me-1"></i>Visualizar
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="table-view card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width: 60px;">#</th>
                            <th>Título</th>
                            <th class="hide-md" style="width: 200px;">Solicitante</th>
                            <th style="width: 120px;">Prioridade</th>
                            <th style="width: 140px;">Status</th>
                            <th class="hide-md" style="width: 160px;">Criado em</th>
                            <th class="text-center pe-3" style="width: 100px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($tickets)): ?>
                        <tr>
                            <td colspan="7" class="py-5">
                                <div class="empty-state">
                                    <i class="bi bi-inbox d-block mb-3"></i>
                                    <p class="text-muted mb-0">Nenhum chamado encontrado.</p>
                                </div>
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($tickets as $t): ?>
                            <tr>
                                <td class="ps-3 fw-bold"><?= (int)$t['id'] ?></td>
                                <td>
                                    <a href="<?= BASE_URL ?>/?url=ticket/show&id=<?= $t['id'] ?>" 
                                       class="text-decoration-none fw-semibold">
                                        <?= htmlspecialchars($t['title']) ?>
                                    </a>
                                    <?php if(!empty($t['assigned_name'])): ?>
                                        <br><small class="text-muted">
                                            <i class="bi bi-person-check me-1"></i><?= htmlspecialchars($t['assigned_name']) ?>
                                        </small>
                                    <?php endif; ?>
                                </td>
                                <td class="hide-md"><?= htmlspecialchars($t['requester_name']) ?></td>
                                <td><?= getPriorityBadge($t['priority']) ?></td>
                                <td><?= getStatusBadge($t['status']) ?></td>
                                <td class="hide-md">
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
</div>

<?php 
$content = ob_get_clean(); 
require __DIR__ . '/../layouts/main.php'; 
?>