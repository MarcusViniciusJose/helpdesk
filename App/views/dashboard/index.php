<?php ob_start(); ?>
<h4 class="mb-4">Dashboard</h4>
<div class="row g-3">
  <div class="col-md-4"><div class="card"><div class="card-body">
    <div class="text-muted small">Chamados Abertos</div>
    <div class="fs-3"><?= (int)$stats['abertos'] ?></div>
  </div></div></div>
  <div class="col-md-4"><div class="card"><div class="card-body">
    <div class="text-muted small">Em Andamento</div>
    <div class="fs-3"><?= (int)$stats['em_andamento'] ?></div>
  </div></div></div>
  <div class="col-md-4"><div class="card"><div class="card-body">
    <div class="text-muted small">Fechados</div>
    <div class="fs-3"><?= (int)$stats['fechados'] ?></div>
  </div></div></div>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/main.php'; ?>
