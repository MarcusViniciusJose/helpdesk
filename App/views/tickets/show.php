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

<style>
.breadcrumb {
    font-size: 0.875rem;
    padding: 0.5rem 0;
    margin-bottom: 1rem;
}

.page-header {
    margin-bottom: 1.5rem;
}

.page-header h4 {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}

.page-header h5 {
    font-size: 1.1rem;
    font-weight: normal;
}

.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin-top: 1rem;
}

.action-buttons .btn {
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
    margin-bottom: 1rem;
}

.card-body {
    padding: 1rem;
}

.card-title {
    font-size: 1rem;
    margin-bottom: 0.75rem;
}

.ticket-meta {
    font-size: 0.85rem;
    margin-bottom: 0.75rem;
}

.ticket-meta p {
    margin-bottom: 0.5rem;
}

.badge-group {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-top: 0.75rem;
}

.description-box {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 6px;
    line-height: 1.6;
    font-size: 0.9rem;
}

.comment-card {
    border: 1px solid #dee2e6;
    border-radius: 6px;
    padding: 0.875rem;
    margin-bottom: 0.75rem;
    background: #f8f9fa;
}

.comment-header {
    margin-bottom: 0.5rem;
}

.comment-author {
    font-weight: 600;
    color: #0d6efd;
    font-size: 0.9rem;
}

.comment-date {
    font-size: 0.75rem;
    color: #6c757d;
}

.comment-content {
    font-size: 0.875rem;
    line-height: 1.5;
    margin-left: 0;
}

.empty-comments {
    text-align: center;
    padding: 2rem 1rem;
    color: #6c757d;
}

.empty-comments i {
    font-size: 2.5rem;
    display: block;
    margin-bottom: 0.75rem;
    opacity: 0.3;
}

.comment-form {
    border-top: 1px solid #dee2e6;
    padding-top: 1rem;
    margin-top: 1rem;
}

.comment-form textarea {
    font-size: 16px;
    min-height: 100px;
}

.quick-actions {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.action-group label {
    font-size: 0.85rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.input-group .btn {
    min-height: 44px;
}

.detail-item {
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e9ecef;
}

.detail-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.detail-label {
    font-size: 0.8rem;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.375rem;
    display: block;
}

.detail-value {
    font-weight: 500;
    font-size: 0.95rem;
}

.attachment-preview {
    margin-top: 1rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 6px;
}

.attachment-preview img {
    max-width: 100%;
    height: auto;
    border-radius: 4px;
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

@media (min-width: 576px) {
    .page-header h4 {
        font-size: 1.75rem;
    }

    .page-header h5 {
        font-size: 1.2rem;
    }

    .card-body {
        padding: 1.25rem;
    }

    .description-box {
        padding: 1.25rem;
    }

    .comment-card {
        padding: 1rem;
    }

    .comment-content {
        margin-left: 1.5rem;
    }

    .comment-form textarea {
        min-height: 120px;
    }
}

@media (min-width: 768px) {
    .breadcrumb {
        font-size: 0.95rem;
        padding: 0.75rem 0;
        margin-bottom: 1.5rem;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 2rem;
    }

    .page-header h4 {
        font-size: 2rem;
        margin-bottom: 0.75rem;
    }

    .page-header h5 {
        font-size: 1.25rem;
    }

    .action-buttons {
        flex-direction: row;
        margin-top: 0;
    }

    .action-buttons .btn {
        width: auto;
    }

    .card-body {
        padding: 1.5rem;
    }

    .card-title {
        font-size: 1.1rem;
        margin-bottom: 1rem;
    }

    .ticket-meta {
        font-size: 0.9rem;
    }

    .description-box {
        padding: 1.5rem;
        font-size: 1rem;
    }

    .comment-card {
        padding: 1.25rem;
    }

    .comment-author {
        font-size: 1rem;
    }

    .comment-date {
        font-size: 0.8rem;
    }

    .comment-content {
        font-size: 0.95rem;
        margin-left: 2rem;
    }

    .comment-form textarea {
        min-height: 140px;
    }

    .quick-actions {
        flex-direction: row;
    }

    .action-group {
        flex: 1;
    }

    .empty-comments {
        padding: 3rem 1rem;
    }

    .empty-comments i {
        font-size: 3.5rem;
    }

    .detail-label {
        font-size: 0.75rem;
    }

    .detail-value {
        font-size: 1rem;
    }

    .modal-footer .btn {
        flex: initial;
    }
}

@media (max-width: 767px) {
    .btn-close {
        padding: 0.5rem;
    }

    .form-control,
    .form-select {
        font-size: 16px;
        padding: 0.75rem;
    }
}
</style>

<div class="container-fluid px-3 px-md-4 py-3 py-md-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
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

    <div class="page-header">
        <div>
            <h4 class="mb-2 fw-bold">
                <i class="bi bi-ticket-detailed me-2"></i>
                Chamado #<?= (int)$ticket['id'] ?>
            </h4>
            <h5 class="text-muted"><?= htmlspecialchars($ticket['title']) ?></h5>
        </div>
        <div class="action-buttons">
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

    <div class="row g-3">
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="ticket-meta">
                        <p class="mb-2">
                            <i class="bi bi-person me-1"></i>
                            <strong>Solicitante:</strong> <?= htmlspecialchars($ticket['requester_name']) ?>
                        </p>
                        <p class="mb-0">
                            <i class="bi bi-calendar me-1"></i>
                            <strong>Aberto em:</strong> <?= date('d/m/Y \à\s H:i', strtotime($ticket['created_at'])) ?>
                        </p>
                    </div>

                    <div class="badge-group">
                        <?= getPriorityBadge($ticket['priority']) ?>
                        <?= getStatusBadge($ticket['status']) ?>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <h6 class="mb-2 fw-semibold">
                            <i class="bi bi-file-text me-2"></i>Descrição
                        </h6>
                        <div class="description-box">
                            <?= nl2br(htmlspecialchars($ticket['description'])) ?>
                        </div>
                    </div>

                    <?php if (!empty($ticket['assigned_name'])): ?>
                        <div class="alert alert-info mb-0">
                            <i class="bi bi-person-check me-2"></i>
                            <strong>Atribuído a:</strong> <?= htmlspecialchars($ticket['assigned_name']) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($ticket['attachment'])): ?>
                        <div class="attachment-preview">
                            <strong class="d-block mb-2">
                                <i class="bi bi-paperclip me-1"></i>Arquivo Anexado
                            </strong>
                            <?php 
                                $fileUrl = BASE_URL . '/uploads/tickets/' . htmlspecialchars($ticket['attachment']);
                                $ext = pathinfo($ticket['attachment'], PATHINFO_EXTENSION);
                            ?>
                            <?php if (in_array(strtolower($ext), ['jpg','jpeg','png','gif'])): ?>
                                <img src="<?= $fileUrl ?>" alt="Anexo" class="img-fluid">
                            <?php else: ?>
                                <a href="<?= $fileUrl ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-download me-1"></i>Baixar arquivo
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if($isAdminOrTI || $ticket['requester_id'] == $user['id']): ?>
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="card-title fw-semibold">
                        <i class="bi bi-sliders me-2"></i>Ações Rápidas
                    </h6>
                    
                    <div class="quick-actions">
                        <div class="action-group">
                            <form method="post" action="<?= BASE_URL ?>/?url=ticket/changeStatus">
                                <input type="hidden" name="ticket_id" value="<?= (int)$ticket['id'] ?>">
                                <label class="detail-label">Alterar Status</label>
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
                        <div class="action-group">
                            <form method="post" action="<?= BASE_URL ?>/?url=ticket/assign">
                                <input type="hidden" name="ticket_id" value="<?= (int)$ticket['id'] ?>">
                                <label class="detail-label">Atribuir Técnico</label>
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
                    <h5 class="card-title fw-semibold">
                        <i class="bi bi-chat-left-text me-2"></i>
                        Comentários
                        <span class="badge bg-secondary"><?= count($comments) ?></span>
                    </h5>

                    <?php if (empty($comments)): ?>
                        <div class="empty-comments">
                            <i class="bi bi-chat-square-dots"></i>
                            <p class="mb-0">Nenhum comentário ainda. Seja o primeiro a comentar!</p>
                        </div>
                    <?php else: ?>
                        <div class="mb-3">
                            <?php foreach ($comments as $c): ?>
                                <div class="comment-card">
                                    <div class="comment-header d-flex justify-content-between align-items-start flex-wrap gap-2">
                                        <div class="comment-author">
                                            <i class="bi bi-person-circle me-1"></i>
                                            <?= htmlspecialchars($c['author_name']) ?>
                                        </div>
                                        <div class="comment-date">
                                            <i class="bi bi-clock me-1"></i>
                                            <?= date('d/m/Y H:i', strtotime($c['created_at'])) ?>
                                        </div>
                                    </div>
                                    <div class="comment-content">
                                        <?= nl2br(htmlspecialchars($c['content'])) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <div class="comment-form">
                        <h6 class="mb-3 fw-semibold">Adicionar Comentário</h6>
                        <form method="post" action="<?= BASE_URL ?>/?url=ticket/storeComment">
                            <input type="hidden" name="ticket_id" value="<?= (int)$ticket['id'] ?>">
                            <div class="mb-3">
                                <textarea name="content" 
                                          rows="4" 
                                          class="form-control" 
                                          placeholder="Escreva seu comentário..." 
                                          required></textarea>
                            </div>
                            <button class="btn btn-primary w-100 w-md-auto">
                                <i class="bi bi-send me-1"></i>Enviar Comentário
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="card-title fw-semibold">
                        <i class="bi bi-info-circle me-2"></i>Detalhes
                    </h6>
                    
                    <div class="detail-item">
                        <span class="detail-label">Prioridade</span>
                        <div class="detail-value"><?= getPriorityBadge($ticket['priority']) ?></div>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label">Status</span>
                        <div class="detail-value"><?= getStatusBadge($ticket['status']) ?></div>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label">Solicitante</span>
                        <div class="detail-value"><?= htmlspecialchars($ticket['requester_name']) ?></div>
                    </div>

                    <?php if (!empty($ticket['assigned_name'])): ?>
                    <div class="detail-item">
                        <span class="detail-label">Atribuído a</span>
                        <div class="detail-value text-success">
                            <i class="bi bi-person-check me-1"></i>
                            <?= htmlspecialchars($ticket['assigned_name']) ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="detail-item">
                        <span class="detail-label">Criado em</span>
                        <div class="detail-value">
                            <?= date('d/m/Y \à\s H:i', strtotime($ticket['created_at'])) ?>
                        </div>
                    </div>

                    <?php if($ticket['updated_at']): ?>
                    <div class="detail-item">
                        <span class="detail-label">Última atualização</span>
                        <div class="detail-value">
                            <?= date('d/m/Y \à\s H:i', strtotime($ticket['updated_at'])) ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if($isAdminOrTI): ?>
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle me-2"></i>Confirmar Exclusão
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2">Tem certeza que deseja excluir este chamado?</p>
                <p class="text-muted small mb-0">Esta ação não pode ser desfeita e todos os comentários serão removidos.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form method="post" action="<?= BASE_URL ?>/?url=ticket/delete" class="d-inline">
                    <input type="hidden" name="id" value="<?= (int)$ticket['id'] ?>">
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i>Excluir
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