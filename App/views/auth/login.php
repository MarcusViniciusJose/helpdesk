<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Login - HelpDesk</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">

  <div class="container" style="max-width:420px;">
    <div class="card shadow-sm">
      <div class="card-body">
        <h5 class="card-title mb-3">Entrar</h5>

        <?php if (!empty($error)): ?>
          <div class="alert alert-danger py-2"><?= htmlspecialchars($error) ?></div>
        <?php else: ?>
          <div class="alert alert-info py-2">
            Use seu e-mail corporativo e a senha inicial <strong>senha123</strong> (altere depois).
          </div>
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
          <button class="btn btn-primary w-100">Entrar</button>
        </form>
      </div>
    </div>
  </div>

</body>
</html>
