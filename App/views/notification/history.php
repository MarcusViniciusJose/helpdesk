<?php ob_start(); ?>

<style>
.page-header {
    margin-bottom: 1.5rem;
}

.page-header h4 {
    font-size: 1.5rem;
    margin-bottom: 1rem;
}

.action-buttons-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.action-buttons-group .btn {
    width: 100%;
    min-height: 44px;
    font-size: 0.875rem;
}

.alert {
    font-size: 0.875rem;
    padding: 0.75rem 1rem;
    border-radius: 8px;
}

.card {
    border-radius: 8px;
}

.empty-state {
    padding: 3rem 1rem;
    text-align: center;
}

.empty-state i {
    font-size: 3rem;
    opacity: 0.3;
}

.empty-state h5 {
    font-size: 1.25rem;
    margin-top: 1rem;
    margin-bottom: 0.5rem;
}

.notification-item {
    padding: 1rem;
    border-bottom: 1px solid #dee2e6;
}

.notification-item:last-child {
    border-bottom: none;
}

.notification-item.unread {
    background-color: #f8f9fa;
    border-left: 4px solid #0d6efd;
}

.notification-content {
    margin-bottom: 0.75rem;
}

.notification-icon {
    font-size: 1.25rem;
    margin-right: 0.75rem;
    flex-shrink: 0;
}

.notification-message {
    font-size: 0.95rem;
    line-height: 1.5;
    margin: 0;
}

.notification-time {
    font-size: 0.75rem;
    color: #6c757d;
    margin-left: 2rem;
}

.notification-actions {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin-top: 0.75rem;
}

.notification-actions .btn {
    width: 100%;
    min-height: 38px;
}

.modal-body {
    padding: 1rem;
}

.modal-footer {
    padding: 1rem;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.modal-footer .btn {
    flex: 1;
    min-width: 120px;
}

.modal-info-box {
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 6px;
    margin-bottom: 1rem;
}

.modal-info-box h6 {
    font-size: 1rem;
    margin-bottom: 0.5rem;
}

.modal-info-box p {
    font-size: 0.875rem;
    margin-bottom: 0;
}

@media (min-width: 576px) {
    .page-header h4 {
        font-size: 1.75rem;
    }

    .notification-item {
        padding: 1.25rem;
    }

    .notification-message {
        font-size: 1rem;
    }

    .notification-time {
        font-size: 0.8rem;
    }

    .empty-state {
        padding: 4rem 1rem;
    }

    .empty-state i {
        font-size: 4rem;
    }

    .modal-info-box h6 {
        font-size: 1.1rem;
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

    .action-buttons-group {
        flex-direction: row;
        gap: 0.5rem;
    }

    .action-buttons-group .btn {
        width: auto;
    }

    .notification-content {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0;
    }

    .notification-message-wrapper {
        flex-grow: 1;
    }

    .notification-actions {
        flex-direction: row;
        margin-top: 0;
        margin-left: 1rem;
        gap: 0.5rem;
    }

    .notification-actions .btn {
        width: auto;
    }

    .notification-time {
        margin-left: 2rem;
        margin-top: 0.5rem;
    }

    .modal-footer .btn {
        flex: initial;
    }

    .empty-state {
        padding: 5rem 1rem;
    }

    .empty-state i {
        font-size: 5rem;
    }

    .empty-state h5 {
        font-size: 1.5rem;
    }
}

@media (max-width: 767px) {
    .btn-close {
        padding: 0.5rem;
    }
}
</style>

<div class="container-fluid px-3 px-md-4 py-3 py-md-4">
    <div class="page-header">
        <h4 class="mb-0 fw-bold">
            <i class="bi bi-bell me-2"></i>Notificações
        </h4>
        
        <?php if (!empty($notifications)): ?>
        <div class="action-buttons-group">
            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#markAllReadModal">
                <i class="bi bi-check-all me-1"></i>Marcar lidas
            </button>
            <button type="button" class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#deleteReadModal">
                <i class="bi bi-trash me-1"></i>Excluir lidas
            </button>
            <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteAllModal">
                <i class="bi bi-trash-fill me-1"></i>Excluir todas
            </button>
        </div>
        <?php endif; ?>
    </div>

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

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <?php if (empty($notifications)): ?>
                <div class="empty-state">
                    <i class="bi bi-bell-slash d-block mb-3"></i>
                    <h5 class="fw-bold">Nenhuma notificação</h5>
                    <p class="text-muted mb-0">Você não tem notificações no momento</p>
                </div>
            <?php else: ?>
                <div class="list-group list-group-flush">
                    <?php foreach ($notifications as $notif): ?>
                    <div class="notification-item <?= $notif['is_read'] ? '' : 'unread' ?>">
                        <div class="notification-content">
                            <div class="notification-message-wrapper">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-<?= $notif['is_read'] ? 'envelope-open' : 'envelope-fill' ?> notification-icon text-primary"></i>
                                    <p class="notification-message flex-grow-1">
                                        <?= htmlspecialchars($notif['message']) ?>
                                    </p>
                                </div>
                                <div class="notification-time">
                                    <i class="bi bi-clock me-1"></i>
                                    <?= date('d/m/Y H:i', strtotime($notif['created_at'])) ?>
                                </div>
                            </div>
                            
                            <div class="notification-actions">
                                <?php if ($notif['link']): ?>
                                    <a href="<?= htmlspecialchars($notif['link']) ?>" 
                                       class="btn btn-sm btn-outline-primary"
                                       onclick="markAsRead(<?= $notif['id'] ?>)">
                                        <i class="bi bi-eye me-1"></i>Ver
                                    </a>
                                <?php endif; ?>
                                
                                <button type="button" 
                                        class="btn btn-sm btn-outline-danger" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteModal<?= $notif['id'] ?>">
                                    <i class="bi bi-trash me-1"></i>Excluir
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="deleteModal<?= $notif['id'] ?>" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Excluir Notificação</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p class="mb-0">Tem certeza que deseja excluir esta notificação?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <form method="post" action="<?= BASE_URL ?>/?url=notification/delete" class="d-inline">
                                        <input type="hidden" name="id" value="<?= $notif['id'] ?>">
                                        <button type="submit" class="btn btn-danger">
                                            <i class="bi bi-trash me-1"></i>Excluir
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="modal fade" id="markAllReadModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-check-circle me-2"></i>Marcar Todas Como Lidas
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="modal-info-box">
                    <h6>Deseja marcar todas as notificações como lidas?</h6>
                    <p class="text-muted">
                        Todas as suas notificações não lidas serão marcadas como visualizadas. 
                        Você ainda poderá acessá-las normalmente no histórico.
                    </p>
                </div>
                
                <?php 
                $unreadCount = count(array_filter($notifications, fn($n) => !$n['is_read']));
                if ($unreadCount > 0): 
                ?>
                <div class="alert alert-info mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong><?= $unreadCount ?></strong> notificação(ões) não lida(s) serão marcadas como lidas.
                </div>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-primary" onclick="confirmMarkAllAsRead()">
                    <i class="bi bi-check-all me-1"></i>Confirmar
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteReadModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header bg-warning bg-opacity-10 border-bottom">
                <h5 class="modal-title text-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>Excluir Notificações Lidas
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="modal-info-box">
                    <h6>Tem certeza que deseja excluir todas as notificações lidas?</h6>
                    <p class="text-muted">
                        Esta ação removerá permanentemente todas as notificações que já foram visualizadas.
                    </p>
                </div>
                <div class="alert alert-warning mb-0">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Atenção:</strong> Esta ação não pode ser desfeita.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-1"></i>Cancelar
                </button>
                <form method="post" action="<?= BASE_URL ?>/?url=notification/deleteAllRead" class="d-inline">
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-trash me-1"></i>Excluir Lidas
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteAllModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Excluir Todas as Notificações
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="modal-info-box">
                    <h6>Tem certeza que deseja excluir TODAS as notificações?</h6>
                    <p class="text-muted">
                        Esta ação removerá permanentemente todas as suas notificações, 
                        <strong>incluindo as não lidas</strong>.
                    </p>
                </div>
                <div class="alert alert-danger mb-0">
                    <i class="bi bi-exclamation-octagon me-2"></i>
                    <strong>ATENÇÃO:</strong> Esta ação é irreversível e apagará todo o histórico de notificações.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-1"></i>Cancelar
                </button>
                <form method="post" action="<?= BASE_URL ?>/?url=notification/deleteAll" class="d-inline">
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash-fill me-1"></i>Excluir Todas
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function markAsRead(id) {
    fetch('<?= BASE_URL ?>/?url=notification/markAsRead', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'id=' + id
    });
}

function confirmMarkAllAsRead() {
    fetch('<?= BASE_URL ?>/?url=notification/markAllAsRead', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const modal = bootstrap.Modal.getInstance(document.getElementById('markAllReadModal'));
            modal.hide();
            window.location.reload();
        } else {
            alert('Erro ao marcar notificações como lidas');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao processar requisição');
    });
}
</script>

<?php 
$content = ob_get_clean(); 
require __DIR__ . '/../layouts/main.php'; 
?>