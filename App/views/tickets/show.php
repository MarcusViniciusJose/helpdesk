<?php ob_start(); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4>Chamado #<?= (int)$ticket['id'] ?> - <?= htmlspecialchars($ticket['title']) ?></h4>
  <div>
    <a class="btn btn-outline-secondary" href="<?= BASE_URL ?>/?url=ticket/index">Voltar</a>
  </div>
</div>

<div class="card mb-3">
  <div class="card-body">
    <p class="text-muted small">Solicitante: <?= htmlspecialchars($ticket['requester_name']) ?> • Aberto em: <?= $ticket['created_at'] ?></p>
    <p><?= nl2br(htmlspecialchars($ticket['description'])) ?></p>

    <div class="mt-3">
      <strong>Status:</strong> <span class="badge bg-info"><?= htmlspecialchars($ticket['status']) ?></span>
      <?php if (!empty($ticket['assigned_to'])): ?>
        <span class="ms-3">Atribuído a: <?= htmlspecialchars($ticket['assigned_to']) ?></span>
      <?php endif; ?>
    </div>

    <div class="mt-3">
      <form method="post" action="<?= BASE_URL ?>/?url=ticket/changeStatus" class="d-inline">
        <input type="hidden" name="ticket_id" value="<?= (int)$ticket['id'] ?>">
        <select name="status" class="form-select d-inline-block w-auto">
          <option value="open" <?= $ticket['status']=='open' ? 'selected':'' ?>>Aberto</option>
          <option value="in_progress" <?= $ticket['status']=='in_progress' ? 'selected':'' ?>>Em Andamento</option>
          <option value="closed" <?= $ticket['status']=='closed' ? 'selected':'' ?>>Fechado</option>
        </select>
        <button class="btn btn-sm btn-primary">Alterar Status</button>
      </form>

      <?php if (in_array($_SESSION['user']['role'], ['ti','admin'])): ?>
      <form method="post" action="<?= BASE_URL ?>/?url=ticket/assign" class="d-inline ms-2">
        <input type="hidden" name="ticket_id" value="<?= (int)$ticket['id'] ?>">
        <select name="tech_id" class="form-select d-inline-block w-auto">
          <option value="">-- Atribuir a --</option>
          <?php foreach ($techs as $t): ?>
            <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['name']) ?></option>
          <?php endforeach; ?>
        </select>
        <button class="btn btn-sm btn-outline-success">Atribuir</button>
      </form>
      <?php endif; ?>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-8">
    <div class="card mb-3">
      <div class="card-body">
        <h5>Comentários</h5>

        <?php if (empty($comments)): ?>
          <p class="text-muted">Nenhum comentário ainda.</p>
        <?php else: ?>
          <?php foreach ($comments as $c): ?>
            <div class="mb-3 border rounded p-2">
              <div class="small text-muted"><?= htmlspecialchars($c['author_name']) ?> • <?= $c['created_at'] ?></div>
              <div><?= nl2br(htmlspecialchars($c['content'])) ?></div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>

        <form method="post" action="<?= BASE_URL ?>/?url=ticket/storeComment">
          <input type="hidden" name="ticket_id" value="<?= (int)$ticket['id'] ?>">
          <div class="mb-2">
            <textarea name="content" rows="4" class="form-control" placeholder="Escreva um comentário..." required></textarea>
          </div>
          <button class="btn btn-primary">Comentar</button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
        <h6>Detalhes</h6>
        <p class="mb-1"><strong>Prioridade:</strong> <?= htmlspecialchars($ticket['priority']) ?></p>
        <p class="mb-1"><strong>Criador:</strong> <?= htmlspecialchars($ticket['requester_name']) ?></p>
        <p class="mb-1"><strong>Criado em:</strong> <?= $ticket['created_at'] ?></p>
      </div>
    </div>
  </div>
</div>

<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/main.php'; ?>
