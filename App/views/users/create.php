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

.access-level-item {
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #dee2e6;
}

.access-level-item:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.access-level-item .badge {
    font-size: 0.8rem;
    padding: 0.4em 0.8em;
    margin-bottom: 0.5rem;
    display: inline-block;
}

.access-level-item p {
    font-size: 0.85rem;
    line-height: 1.6;
    margin: 0;
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

    .access-level-item p {
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

    .access-level-item .badge {
        font-size: 0.85rem;
    }

    .access-level-item p {
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
            <li class="breadcrumb-item active">Novo Usuário</li>
        </ol>
    </nav>

    <h4 class="page-title fw-bold">
        <i class="bi bi-person-plus me-2"></i>Novo Usuário
    </h4>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <strong>Erro ao criar usuário:</strong>
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
                    <form method="post" action="<?= BASE_URL ?>/?url=user/store">
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Nome Completo <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   class="form-control" 
                                   placeholder="Digite o nome completo"
                                   value="<?= htmlspecialchars($form['name'] ?? '') ?>"
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
                                   value="<?= htmlspecialchars($form['email'] ?? '') ?>"
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
                                    $selectedRole = $form['role'] ?? 'user';
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
                                    $selectedDept = $form['department'] ?? '';
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
                                <option value="active" <?= ($form['status'] ?? 'active') == 'active' ? 'selected' : '' ?>>
                                    Ativo
                                </option>
                                <option value="inactive" <?= ($form['status'] ?? '') == 'inactive' ? 'selected' : '' ?>>
                                    Inativo
                                </option>
                            </select>
                        </div>
                        
                        <div class="action-buttons">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-lg me-1"></i>Criar Usuário
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
            <div class="card bg-light shadow-sm">
                <div class="card-body">
                    <h6 class="card-title fw-semibold">
                        <i class="bi bi-shield-check me-2"></i>Níveis de Acesso
                    </h6>
                    <hr>
                    
                    <div class="access-level-item">
                        <span class="badge bg-danger">Administrador</span>
                        <p>
                            • Acesso total ao sistema<br>
                            • Gerencia usuários<br>
                            • Gerencia tickets<br>
                            • Configurações do sistema
                        </p>
                    </div>
                    
                    <div class="access-level-item">
                        <span class="badge bg-primary">TI</span>
                        <p>
                            • Visualiza todos os tickets<br>
                            • Atribui e gerencia tickets<br>
                            • Altera status de tickets
                        </p>
                    </div>
                    
                    <div class="access-level-item">
                        <span class="badge bg-secondary">Usuário</span>
                        <p>
                            • Abre tickets<br>
                            • Visualiza seus tickets<br>
                            • Comenta em tickets
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean(); 
require __DIR__ . '/../layouts/main.php'; 
?>