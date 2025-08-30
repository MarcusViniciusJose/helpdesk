<?php $u = Auth::user(); ?>
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>HelpDesk</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { min-height: 100vh; }
    .sidebar { width: 250px; }
  </style>
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?= BASE_URL ?>/?url=dashboard/index">HelpDesk</a>
    <div class="text-white">
      Olá, <?= htmlspecialchars($u['name']) ?> (<?= htmlspecialchars($u['role']) ?>)
      <a class="btn btn-sm btn-outline-light ms-3" href="<?= BASE_URL ?>/?url=auth/logout">Sair</a>
    </div>
  </div>
</nav>

<div class="d-flex">
  <aside class="sidebar bg-white border-end p-3">
    <div class="fw-bold text-uppercase small mb-2">Menu</div>
    <ul class="nav flex-column">
      <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/?url=dashboard/index">Dashboard</a></li>
      <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/?url=ticket/index">Meus Chamados</a></li>
      <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/?url=ticket/create">Abrir Chamado</a></li>

      <?php if (in_array($u['role'], ['admin','ti'], true)): ?>
        <li class="nav-item mt-2"><span class="text-muted small">Gestão</span></li>
        <li class="nav-item"><a class="nav-link" href="#">Fila TI (em breve)</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Usuários (em breve)</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Inventário (em breve)</a></li>
      <?php endif; ?>
    </ul>
  </aside>

  <main class="flex-grow-1 p-4">
    <?php  ?>
    <?= $content ?? '' ?>
  </main>
</div>
</body>
</html>
