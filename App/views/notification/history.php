<?php ob_start(); ?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">
            <i class="bi bi-bell me-2"></i>Notificações
        </h4>
        
        <?php if (!empty($notifications)): ?>
        <div class="btn-group">
            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#markAllReadModal">
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
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-check-circle me-2"></i>Marcar Todas Como Lidas
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex align-items-start gap-3 mb-3">
                    <div class="flex-grow-1">
                        <h6 class="mb-2">Deseja marcar todas as notificações como lidas?</h6>
                        <p class="text-muted small mb-0">
                            Todas as suas notificações não lidas serão marcadas como visualizadas. 
                            Você ainda poderá acessá-las normalmente no histórico.
                        </p>
                    </div>
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
                    <i class="bi bi-check-all me-1"></i>Marcar Como Lidas
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteReadModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning bg-opacity-10 border-bottom">
                <h5 class="modal-title text-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>Excluir Notificações Lidas
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex align-items-start gap-3 mb-3">
                    <div class="flex-grow-1">
                        <h6 class="mb-2">Tem certeza que deseja excluir todas as notificações lidas?</h6>
                        <p class="text-muted small mb-0">
                            Esta ação removerá permanentemente todas as notificações que já foram visualizadas.
                        </p>
                    </div>
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
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Excluir Todas as Notificações
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex align-items-start gap-3 mb-3">
                    <div class="flex-grow-1">
                        <h6 class="mb-2">Tem certeza que deseja excluir TODAS as notificações?</h6>
                        <p class="text-muted small mb-0">
                            Esta ação removerá permanentemente todas as suas notificações, 
                            <strong>incluindo as não lidas</strong>.
                        </p>
                    </div>
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