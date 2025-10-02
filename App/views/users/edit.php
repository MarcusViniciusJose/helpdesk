<?php ob_start(); ?>

<nav aria-label="breadcrumb" class="mb-3">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/?url=user/index">Usuários</a></li>
    <li class="breadcrumb-item active">Editar Usuário</li>
  </ol>
</nav>

<h4 class="mb-4">
  <i class="bi bi-pencil-square me-2"></i>Editar Usuário
</h4>

<?php if (!empty($errors)): ?>
  <div class="alert alert-danger alert-dismissible fade show">
    <strong>Erro ao atualizar usuário:</strong>
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
          
          <div class="d-flex gap-2 mt-4">
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
  
  <div class="col-lg-4">
    <div class="card shadow-sm mb-3">
      <div class="card-body">
        <h6 class="card-title">
          <i class="bi bi-info-circle me-2"></i>Informações
        </h6>
        <hr>
        <p class="mb-2">
          <strong>ID:</strong> <?= $user['id'] ?>
        </p>
        <p class="mb-2">
          <strong>Criado em:</strong><br>
          <?= date('d/m/Y \à\s H:i', strtotime($user['created_at'])) ?>
        </p>
        <?php if (!empty($user['updated_at'])): ?>
        <p class="mb-0">
          <strong>Última atualização:</strong><br>
          <?= date('d/m/Y \à\s H:i', strtotime($user['updated_at'])) ?>
        </p>
        <?php endif; ?>
      </div>
    </div>

    <div class="card border-warning shadow-sm">
      <div class="card-body">
        <h6 class="card-title text-warning">
          <i class="bi bi-exclamation-triangle me-2"></i>Atenção
        </h6>
        <p class="small mb-0">
          Ao alterar o papel do usuário, suas permissões no sistema serão modificadas imediatamente.
        </p>
      </div>
    </div>
  </div>
</div>

<?php 
$content = ob_get_clean(); 
require __DIR__ . '/../layouts/main.php'; 
?>