<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="m-0">Usuários</h3>
  <a class="btn btn-primary" href="<?= BASE_URL ?>/?url=user/create">Novo Usuário</a>
</div>

<?php if (!empty($_GET['created'])): ?>
  <div class="alert alert-success">Usuário criado com sucesso! Senha padrão: <code>senha123</code></div>
<?php endif; ?>

<div class="card">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover m-0">
        <thead class="table-light">
          <tr>
            <th>Nome</th>
            <th>E-mail</th>
            <th>Papel</th>
            <th>Status</th>
            <th>Criado em</th>
            <th class="text-end">Ações</th>
          </tr>
        </thead>
        <tbody>
        <?php if (empty($users)): ?>
          <tr><td colspan="6" class="text-center text-muted py-4">Nenhum usuário encontrado.</td></tr>
        <?php else: ?>
          <?php foreach ($users as $us): ?>
            <tr>
              <td><?= htmlspecialchars($us['name']) ?></td>
              <td><?= htmlspecialchars($us['email']) ?></td>
              <td><span class="badge text-bg-secondary"><?= htmlspecialchars($us['role']) ?></span></td>
              <td>
                <?php if ($us['status'] === 'active'): ?>
                  <span class="badge text-bg-success">Ativo</span>
                <?php else: ?>
                  <span class="badge text-bg-secondary">Inativo</span>
                <?php endif; ?>
              </td>
              <td><?= htmlspecialchars($us['created_at']) ?></td>
              <td class="text-end">
                <!-- Próximos passos: editar / desativar / resetar senha -->
                <button class="btn btn-sm btn-outline-secondary" disabled>Editar</button>
                <button class="btn btn-sm btn-outline-secondary" disabled>Ativar/Desativar</button>
                <button class="btn btn-sm btn-outline-secondary" disabled>Resetar Senha</button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
