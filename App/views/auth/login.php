<?php ob_start(); ?>
<div class="container py-5" style="max-width:420px;">
  <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="card-title mb-3">Entrar</h5>
      <?php if (!empty($error)): ?>
        <div class="alert alert-danger py-2"><?= htmlspecialchars($error) ?></div>
      <?php else: ?>
        <div class="alert alert-info py-2">Use seu e-mail corporativo e a senha inicial <strong>senha123</strong> (altere depois).</div>
      <?php endif; ?>
      <form method="post" action="<?= BASE_URL ?>/?url=auth/doLogin">
        <div class="mb-3">
          <label class="form-label">E-mail</label>
          <input type="email" class="form-control" name="email" required autofocus>
        </div>
        <div class="mb-3">
          <label class="form-label">Senha</label>
          <input type="password" class="form-control" name="password" required>
        </div>
        <button class="btn btn-primary w-100">Acessar</button>
      </form>
    </div>
  </div>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/main.php'; ?>
