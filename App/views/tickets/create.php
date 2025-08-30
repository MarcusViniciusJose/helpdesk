<?php ob_start(); ?>
<h4 class="mb-3">Abrir Chamado</h4>
<?php if (!empty($error)): ?>
  <div class="alert alert-danger py-2"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<div class="card">
  <div class="card-body">
    <form method="post" action="<?= BASE_URL ?>/?url=ticket/store">
      <div class="mb-3">
        <label class="form-label">Título</label>
        <input type="text" name="title" class="form-control" required value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
      </div>
      <div class="mb-3">
        <label class="form-label">Descrição</label>
        <textarea name="description" class="form-control" rows="5" required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Prioridade</label>
        <select name="priority" class="form-select">
          <option value="low">Baixa</option>
          <option value="medium" selected>Média</option>
          <option value="high">Alta</option>
          <option value="critical">Crítica</option>
        </select>
      </div>
      <button class="btn btn-success">Enviar</button>
      <a class="btn btn-outline-secondary" href="<?= BASE_URL ?>/?url=ticket/index">Cancelar</a>
    </form>
  </div>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/main.php'; ?>
