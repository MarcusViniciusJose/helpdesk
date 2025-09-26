<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>HelpDesk</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="d-flex">
    <div class="bg-dark text-white p-3" style="width:250px; min-height:100vh;">
      <h4 class="mb-4">HelpDesk</h4>
      <ul class="nav flex-column">
        <li class="nav-item"><a href="<?= BASE_URL ?>/?url=dashboard/index" class="nav-link text-white">Dashboard</a></li>
        <li class="nav-item"><a href="<?= BASE_URL ?>/?url=ticket/index" class="nav-link text-white">Chamados</a></li>
        <li class="nav-item"><a href="<?= BASE_URL ?>/?url=user/index" class="nav-link text-white">Usuários</a></li>
        <li class="nav-item"><a href="<?= BASE_URL ?>/?url=auth/logout" class="nav-link text-white">Sair</a></li>
      </ul>
    </div>

    <div class="p-4 flex-fill">
      <?= $content ?>
    </div>
  </div>
</body>
</html>
