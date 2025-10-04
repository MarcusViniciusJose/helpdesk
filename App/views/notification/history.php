<?php ob_start(); ?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">
            <i class="bi bi-bell me-2"></i>Notificações
        </h4>
        
        <?php if (!empty($notifications)): ?>
        <div class="btn-group">
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="markAllAsRead()">
                <i class="bi bi-check-all me-1"></i>Marcar todas como lidas
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
                <div class="text-center text-muted py-5">
                    <i class="bi bi-bell-slash fs-1 d-block mb-3 opacity-25"></i>
                    <h5>Nenhuma notificação</h5>
                    <p class="mb-0">Você não tem notificações no momento</p>
                </div>
            <?php else: ?>
                <div class="list-group list-group-flush">
                    <?php foreach ($notifications as $notif): ?>
                    <div class="list-group-item <?= $notif['is_read'] ? '' : 'bg-light border-start border-primary border-4' ?>">
                        <div class="d-flex w-100 justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-start mb-2">
                                    <i class="bi bi-<?= $notif['is_read'] ? 'envelope-open' : 'envelope-fill' ?> me-2 text-primary mt-1"></i>
                                    <p class="mb-0 flex-grow-1">
                                        <?= htmlspecialchars($notif['message']) ?>
                                    </p>
                                </div>
                                <small class="text-muted ms-4">
                                    <i class="bi bi-clock me-1"></i>
                                    <?= date('d/m/Y H:i', strtotime($notif['created_at'])) ?>
                                </small>
                            </div>
                            <div class="ms-3 d-flex gap-1">
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
                                        data-bs-target="#deleteModal<?= $notif['id'] ?>"
                                        title="Excluir notificação">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="deleteModal<?= $notif['id'] ?>" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Excluir Notificação</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    Tem certeza que deseja excluir esta notificação?
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

<div class="modal fade" id="deleteReadModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle me-2"></i>Excluir Notificações Lidas
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir todas as notificações já lidas?</p>
                <p class="text-muted small mb-0">Esta ação não pode ser desfeita.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
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
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Excluir Todas as Notificações
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>Atenção!</strong> Esta ação excluirá TODAS as suas notificações, incluindo as não lidas.</p>
                <p class="text-muted small mb-0">Esta ação não pode ser desfeita.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
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

function markAllAsRead() {
    if (confirm('Marcar todas as notificações como lidas?')) {
        fetch('<?= BASE_URL ?>/?url=notification/markAllAsRead', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            }
        });
    }
}
</script>

<?php 
$content = ob_get_clean(); 
require __DIR__ . '/../layouts/main.php'; 
?>