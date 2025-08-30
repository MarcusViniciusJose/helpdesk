<?php ob_start(); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4>Chamados</h4>
  <a class="btn btn-primary" href="<?= BASE_URL ?>/?url=ticket/create">Abrir Chamado</a>
</div>

<div class="card">
  <div class="table-responsive">
    <table class="table table-striped align-middle mb-0">
      <thead><tr>
        <th>#</th><th>Título</th><th>Solicitante</th><th>Prioridade</th><th>Status</th><th>Aberto em</th>
      </tr></thead>
      <tbody>
        <?php foreach ($tickets as $t): ?>
        <tr>
          <td><?= (int)$t['id'] ?></td>
          <td><?= htmlspecialchars($t['title']) ?></td>
          <td><?= htmlspecialchars($t['requester_name']) ?></td>
          <td><?= htmlspecialchars($t['priority']) ?></td>
          <td><?= htmlspecialchars($t['status']) ?></td>
          <td><?= htmlspecialchars($t['created_at']) ?></td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($tickets)): ?>
        <tr><td colspan="6" class="text-center text-muted">Nenhum chamado encontrado.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/main.php'; ?>
