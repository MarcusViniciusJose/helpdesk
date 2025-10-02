<?php ob_start(); ?>

<nav aria-label="breadcrumb" class="mb-3">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/?url=user/index">Usuários</a></li>
    <li class="breadcrumb-item active">Novo Usuário</li>
  </ol>
</nav>

<h4 class="mb-4">
  <i class="bi bi-person-plus me-2"></i>Novo Usuário
</h4>

<?php if (!empty($errors)): ?>
  <div class="alert alert-danger alert-dismissible fade show">
    <strong>Erro ao criar usuário:</strong>
    <ul class="mb-0 mt-2">
      <?php foreach ($errors as $error): ?>
        <li><?= htmlspecialchars($error) ?></li>
      <?php endforeach; ?>
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>

<div class="row">
  <div class="col-lg-8">
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
            <small class="form-text text-muted">
              Este será o login do usuário
            </small>
          </div>
          
          <div class="row">
    <div class="col-md-6 mb-3">
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
        <small class="form-text text-muted">
            Define as permissões no sistema
        </small>
    </div>
    
    <div class="col-md-6 mb-3">
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
        <small class="form-text text-muted">
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
          
          
          <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            <strong>Senha padrão:</strong> senha123
            <br>
            <small>O usuário poderá alterar a senha após o primeiro login.</small>
          </div>
          
          <div class="d-flex gap-2 mt-4">
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
  
  <div class="col-lg-4">
    <div class="card bg-light shadow-sm">
      <div class="card-body">
        <h6 class="card-title">
          <i class="bi bi-shield-check me-2"></i>Níveis de Acesso
        </h6>
        <hr>
        <div class="mb-3">
          <span class="badge bg-danger mb-2">Administrador</span>
          <p class="small mb-0">
            • Acesso total ao sistema<br>
            • Gerencia usuários<br>
            • Gerencia tickets<br>
            • Configurações do sistema
          </p>
        </div>
        <div class="mb-3">
          <span class="badge bg-primary mb-2">TI</span>
          <p class="small mb-0">
            • Visualiza todos os tickets<br>
            • Atribui e gerencia tickets<br>
            • Altera status de tickets
          </p>
        </div>
        <div>
          <span class="badge bg-secondary mb-2">Usuário</span>
          <p class="small mb-0">
            • Abre tickets<br>
            • Visualiza seus tickets<br>
            • Comenta em tickets
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