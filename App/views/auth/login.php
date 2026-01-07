<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Login - HelpDesk</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light min-vh-100 d-flex align-items-center">

  <div class="container px-3">
    <div class="row justify-content-center">
      <div class="col-12 col-sm-10 col-md-8 col-lg-5 col-xl-4">

        <div class="card shadow-sm border-0">
          <div class="card-body p-4">

            <h4 class="text-center mb-4 fw-semibold">
              HelpDesk
            </h4>

            <?php if (!empty($error)): ?>
              <div class="alert alert-danger py-2 small text-center">
                <?= htmlspecialchars($error) ?>
              </div>
            <?php else: ?>
              <div class="alert alert-info py-2 small text-center">
                Use seu e-mail corporativo e a senha padrão
                <strong>senha123</strong>.
              </div>
            <?php endif; ?>

            <form method="post" action="<?= BASE_URL ?>/?url=auth/doLogin">

              <div class="mb-3">
                <label class="form-label">E-mail</label>
                <input
                  type="email"
                  class="form-control"
                  name="email"
                  placeholder="seu@email.com"
                  required
                  autofocus
                >
              </div>

              <div class="mb-4">
                <label class="form-label">Senha</label>
                <input
                  type="password"
                  class="form-control"
                  name="password"
                  placeholder="••••••••"
                  required
                >
              </div>

              <div class="d-grid">
                <button class="btn btn-primary btn-lg">
                  Entrar
                </button>
              </div>

            </form>

          </div>
        </div>

      </div>
    </div>
  </div>

</body>
</html>
