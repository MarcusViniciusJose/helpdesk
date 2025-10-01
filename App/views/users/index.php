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

<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="mb-0">
    <i class="bi bi-people-fill me-2"></i>Gerenciar Usuários
  </h4>
  <a class="btn btn-primary" href="<?= BASE_URL ?>/?url=user/create">
    <i class="bi bi-person-plus me-1"></i>Novo Usuário
  </a>
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
    <div class="table-responsive">
      <table class="table table-hover m-0 align-middle">
        <thead class="table-light">
          <tr>
            <th class="ps-3">Nome</th>
            <th>E-mail</th>
            <th style="width: 150px;">Papel</th>
            <th style="width: 120px;">Status</th>
            <th style="width: 150px;">Criado em</th>
            <th class="text-end pe-3" style="width: 250px;">Ações</th>
          </tr>
        </thead>
        <tbody>
        <?php if (empty($users)): ?>
          <tr>
            <td colspan="6" class="text-center text-muted py-5">
              <i class="bi bi-inbox fs-1 d-block mb-2"></i>
              Nenhum usuário encontrado.
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
              <td>
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

<?php 
$content = ob_get_clean(); 
require __DIR__ . '/../layouts/main.php'; 
?>