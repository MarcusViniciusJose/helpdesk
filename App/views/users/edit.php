<?php ob_start(); ?>

<style>
.breadcrumb {
    font-size: 0.875rem;
    padding: 0.5rem 0;
    margin-bottom: 1rem;
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

.form-text {
    font-size: 0.8rem;
    margin-top: 0.375rem;
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

.alert ul {
    padding-left: 1.25rem;
    margin-bottom: 0;
}

.alert li {
    margin-bottom: 0.25rem;
}

.info-item {
    margin-bottom: 0.75rem;
    font-size: 0.875rem;
}

.info-item:last-child {
    margin-bottom: 0;
}

.info-item strong {
    display: block;
    color: #6c757d;
    font-size: 0.8rem;
    margin-bottom: 0.25rem;
}

.warning-card {
    border-left-width: 4px;
}

.warning-card p {
    font-size: 0.875rem;
    line-height: 1.5;
}

.form-row {
    margin-bottom: 1rem;
}

@media (min-width: 576px) {
    .page-title {
        font-size: 1.75rem;
    }

    .card-body {
        padding: 1.25rem;
    }

    .info-item {
        font-size: 0.9rem;
    }

    .warning-card p {
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

    .info-item {
        font-size: 0.95rem;
        margin-bottom: 1rem;
    }

    .info-item strong {
        font-size: 0.85rem;
    }

    .warning-card p {
        font-size: 0.95rem;
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
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/?url=user/index">Usuários</a></li>
            <li class="breadcrumb-item active">Editar Usuário</li>
        </ol>
    </nav>

    <h4 class="page-title fw-bold">
        <i class="bi bi-pencil-square me-2"></i>Editar Usuário
    </h4>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <strong>Erro ao atualizar usuário:</strong>
            <ul class="mt-2">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row g-3">
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="post" action="<?= BASE_URL ?>/?url=user/update">
                        <input type="hidden" name="id" value="<?= (int)$user['id'] ?>">
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Nome Completo <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   class="form-control" 
                                   placeholder="Digite o nome completo"
                                   value="<?= htmlspecialchars($user['name']) ?>"
                                   required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                E-mail <span class="text-danger">*</span>
                            </label>
                            <input type="email" 
                                   name="email" 
                                   class="form-control" 
                                   placeholder="usuario@exemplo.com"
                                   value="<?= htmlspecialchars($user['email']) ?>"
                                   required>
                            <small class="form-text text-muted d-block mt-2">
                                Este será o login do usuário
                            </small>
                        </div>
                        
                        <div class="row g-3 form-row">
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">
                                    Papel (Permissão) <span class="text-danger">*</span>
                                </label>
                                <select name="role" class="form-select" required>
                                    <?php 
                                    $selectedRole = $user['role'];
                                    foreach (User::getRoles() as $value => $label): 
                                    ?>
                                        <option value="<?= $value ?>" <?= $selectedRole == $value ? 'selected' : '' ?>>
                                            <?= $label ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="form-text text-muted d-block mt-2">
                                    Define as permissões no sistema
                                </small>
                            </div>
                            
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">
                                    Departamento/Setor
                                </label>
                                <select name="department" class="form-select">
                                    <option value="">-- Selecione --</option>
                                    <?php 
                                    $selectedDept = $user['department'] ?? '';
                                    foreach (User::getDepartments() as $value => $label): 
                                    ?>
                                        <option value="<?= $value ?>" <?= $selectedDept == $value ? 'selected' : '' ?>>
                                            <?= $label ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="form-text text-muted d-block mt-2">
                                    Setor onde o usuário trabalha
                                </small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                <option value="active" <?= ($user['status'] ?? 'active') == 'active' ? 'selected' : '' ?>>
                                    Ativo
                                </option>
                                <option value="inactive" <?= ($user['status'] ?? '') == 'inactive' ? 'selected' : '' ?>>
                                    Inativo
                                </option>
                            </select>
                        </div>
                        
                        <div class="action-buttons">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-lg me-1"></i>Salvar Alterações
                            </button>
                            <a href="<?= BASE_URL ?>/?url=user/index" class="btn btn-outline-secondary">
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
                    <hr>
                    
                    <div class="info-item">
                        <strong>ID</strong>
                        <span class="d-block text-dark"><?= $user['id'] ?></span>
                    </div>
                    
                    <div class="info-item">
                        <strong>Criado em</strong>
                        <span class="d-block text-dark">
                            <?= date('d/m/Y \à\s H:i', strtotime($user['created_at'])) ?>
                        </span>
                    </div>
                    
                    <?php if (!empty($user['updated_at'])): ?>
                    <div class="info-item">
                        <strong>Última atualização</strong>
                        <span class="d-block text-dark">
                            <?= date('d/m/Y \à\s H:i', strtotime($user['updated_at'])) ?>
                        </span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card border-warning shadow-sm warning-card">
                <div class="card-body">
                    <h6 class="card-title text-warning fw-semibold">
                        <i class="bi bi-exclamation-triangle me-2"></i>Atenção
                    </h6>
                    <p class="mb-0">
                        Ao alterar o papel do usuário, suas permissões no sistema serão modificadas imediatamente.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean(); 
require __DIR__ . '/../layouts/main.php'; 
?>