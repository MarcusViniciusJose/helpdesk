<?php 
ob_start(); 

function getRoleBadge($role) {
    $badges = [
        'admin' => '<span class="badge bg-danger"><i class="bi bi-shield-fill-check me-1"></i>Administrador</span>',
        'ti'    => '<span class="badge bg-primary"><i class="bi bi-gear-fill me-1"></i>TI</span>',
        'user'  => '<span class="badge bg-secondary"><i class="bi bi-person-fill me-1"></i>Usuário</span>',
    ];
    return $badges[$role] ?? '<span class="badge bg-secondary">' . htmlspecialchars($role) . '</span>';
}
?>

<style>
/* Mobile-First Base Styles */
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

/* Alert styles */
.alert {
    font-size: 0.875rem;
    padding: 0.75rem 1rem;
    border-radius: 8px;
}

/* Card styles */
.card {
    border-radius: 8px;
}

/* Mobile Card List View */
.user-card-mobile {
    border-bottom: 1px solid #dee2e6;
    padding: 1rem;
}

.user-card-mobile:last-child {
    border-bottom: none;
}

.user-card-mobile:hover {
    background-color: #f8f9fa;
}

.user-name {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.user-email {
    font-size: 0.875rem;
    color: #6c757d;
    margin-bottom: 0.75rem;
}

.user-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: 0.75rem;
}

.user-meta .badge {
    font-size: 0.75rem;
}

.user-info {
    font-size: 0.8rem;
    color: #6c757d;
    margin-bottom: 0.75rem;
}

.user-actions {
    display: flex;
    gap: 0.5rem;
}

.user-actions .btn {
    flex: 1;
    min-height: 38px;
    font-size: 0.875rem;
}

/* Desktop Table View */
.table-view {
    display: none;
}

/* Empty state */
.empty-state {
    padding: 3rem 1rem;
    text-align: center;
}

.empty-state i {
    font-size: 3rem;
    color: #dee2e6;
}

/* Badge styles */
.badge {
    font-size: 0.75rem;
    padding: 0.35em 0.65em;
    font-weight: 500;
}

.badge i {
    font-size: 0.7rem;
}

/* Tablet Styles */
@media (min-width: 576px) {
    .page-header h4 {
        font-size: 1.75rem;
    }

    .user-card-mobile {
        padding: 1.25rem;
    }

    .user-name {
        font-size: 1.1rem;
    }

    .user-actions .btn {
        flex: initial;
        min-width: 120px;
    }
}

/* Desktop Styles */
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

    /* Hide mobile cards, show table */
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
    }

    .badge i {
        font-size: 0.7rem;
    }

    .empty-state {
        padding: 4rem 1rem;
    }

    .empty-state i {
        font-size: 4rem;
    }
}

/* Hide specific columns on smaller tablets */
@media (max-width: 991px) {
    .table-view .hide-md {
        display: none;
    }
}
</style>

<div class="container-fluid px-3 px-md-4 py-3 py-md-4">
    <!-- Page Header -->
    <div class="page-header">
        <h4 class="mb-0 fw-bold">
            <i class="bi bi-people-fill me-2"></i>Gerenciar Usuários
        </h4>
        <a class="btn btn-primary" href="<?= BASE_URL ?>/?url=user/create">
            <i class="bi bi-person-plus me-1"></i>Novo Usuário
        </a>
    </div>

    <!-- Success Alert -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i><?= htmlspecialchars($_SESSION['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <!-- Error Alert -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-2"></i><?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="card shadow-sm">
        <!-- Mobile Card View -->
        <div class="mobile-view d-md-none">
            <?php if (empty($users)): ?>
                <div class="empty-state">
                    <i class="bi bi-inbox d-block mb-3"></i>
                    <p class="text-muted mb-0">Nenhum usuário encontrado.</p>
                </div>
            <?php else: ?>
                <?php foreach ($users as $us): ?>
                <div class="user-card-mobile">
                    <div class="user-name">
                        <?= htmlspecialchars($us['name']) ?>
                        <?php if ($us['id'] == $_SESSION['user']['id']): ?>
                            <span class="badge bg-info">Você</span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="user-email">
                        <i class="bi bi-envelope me-1"></i>
                        <?= htmlspecialchars($us['email']) ?>
                    </div>
                    
                    <div class="user-meta">
                        <?= getRoleBadge($us['role']) ?>
                        
                        <?php if ($us['status'] === 'active'): ?>
                            <span class="badge bg-success">
                                <i class="bi bi-check-circle me-1"></i>Ativo
                            </span>
                        <?php else: ?>
                            <span class="badge bg-secondary">
                                <i class="bi bi-x-circle me-1"></i>Inativo
                            </span>
                        <?php endif; ?>
                        
                        <?php if ($us['department']): ?>
                            <span class="badge bg-primary">
                                <?= htmlspecialchars($us['department']) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="user-info">
                        <i class="bi bi-calendar me-1"></i>
                        Criado em: <?= date('d/m/Y', strtotime($us['created_at'])) ?>
                    </div>
                    
                    <div class="user-actions">
                        <a href="<?= BASE_URL ?>/?url=user/edit&id=<?= $us['id'] ?>" 
                           class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil me-1"></i>Editar
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Desktop Table View -->
        <div class="table-view card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover m-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Nome</th>
                            <th>E-mail</th>
                            <th class="hide-md" style="width: 150px;">Departamento</th>
                            <th style="width: 120px;">Papel</th>
                            <th style="width: 100px;">Status</th>
                            <th class="hide-md" style="width: 150px;">Criado em</th>
                            <th class="text-end pe-3" style="width: 100px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="7" class="py-5">
                                <div class="empty-state">
                                    <i class="bi bi-inbox d-block mb-3"></i>
                                    <p class="text-muted mb-0">Nenhum usuário encontrado.</p>
                                </div>
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($users as $us): ?>
                            <tr>
                                <td class="ps-3">
                                    <strong><?= htmlspecialchars($us['name']) ?></strong>
                                    <?php if ($us['id'] == $_SESSION['user']['id']): ?>
                                        <span class="badge bg-info ms-2">Você</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($us['email']) ?></td>
                                <td class="hide-md">
                                    <?php if ($us['department']): ?>
                                        <span class="badge bg-primary">
                                            <?= htmlspecialchars($us['department']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted small">Não definido</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= getRoleBadge($us['role']) ?></td>
                                <td>
                                    <?php if ($us['status'] === 'active'): ?>
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle me-1"></i>Ativo
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-x-circle me-1"></i>Inativo
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="hide-md">
                                    <small><?= date('d/m/Y', strtotime($us['created_at'])) ?></small>
                                </td>
                                <td class="text-end pe-3">
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= BASE_URL ?>/?url=user/edit&id=<?= $us['id'] ?>" 
                                           class="btn btn-outline-primary" 
                                           title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </div>
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