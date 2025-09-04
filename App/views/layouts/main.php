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

<!-- Navbar -->
<?php $u = Auth::user(); ?>
<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?= BASE_URL ?>/?url=dashboard/index">HelpDesk</a>
    <div class="d-flex align-items-center gap-3">
      <?php if ($u): ?>
        <div class="dropdown">
          <a class="text-white text-decoration-none position-relative" href="#" id="notifToggle" data-bs-toggle="dropdown" aria-expanded="false">
            🔔 <span id="notif-count" class="badge bg-danger">0</span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end" id="notif-list" style="width: 320px;">
            <li class="dropdown-item text-muted">Carregando...</li>
          </ul>
        </div>

        <div class="text-white">
          Olá, <?= htmlspecialchars($u['name']) ?> (<?= htmlspecialchars($u['role']) ?>)
          <a class="btn btn-sm btn-outline-light ms-3" href="<?= BASE_URL ?>/?url=auth/logout">Sair</a>
        </div>
      <?php endif; ?>
    </div>
  </div>
</nav>


<!-- Layout -->
<div class="d-flex">
  <!-- Sidebar -->
  <aside class="sidebar bg-white border-end p-3">
    <div class="fw-bold text-uppercase small mb-2">Menu</div>
    <ul class="nav flex-column">
      <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/?url=dashboard/index">Dashboard</a></li>
      <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/?url=ticket/index">Meus Chamados</a></li>
      <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/?url=ticket/create">Abrir Chamado</a></li>

      <?php if (in_array($u['role'], ['admin','ti'], true)): ?>
        <li class="nav-item mt-2"><span class="text-muted small">Gestão</span></li>
        <li class="nav-item"><a class="nav-link" href="#">Fila TI (em breve)</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/?url=user/index">Usuários</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Inventário (em breve)</a></li>
      <?php endif; ?>
    </ul>
  </aside>

  <!-- Conteúdo -->
  <main class="flex-grow-1 p-4">
    <?php // Conteúdo específico da view é injetado aqui ?>
    <?= $content ?? '' ?>
  </main>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
  async function fetchNotifications() {
    try {
      const res = await axios.get('<?= BASE_URL ?>/?url=notification/get');
      const data = res.data;
      const list = document.getElementById('notif-list');
      const count = document.getElementById('notif-count');
      list.innerHTML = '';

      if (!Array.isArray(data) || data.length === 0) {
        list.innerHTML = '<li class="dropdown-item text-muted">Sem notificações</li>';
        count.textContent = '0';
      } else {
        count.textContent = data.length;
        data.forEach(n => {
          const a = document.createElement('a');
          a.className = 'dropdown-item';
          a.href = n.link || '#';
          a.innerHTML = `<div class="small text-muted">${n.created_at ?? ''}</div><div>${n.message}</div>`;
          a.addEventListener('click', function(e) {
            e.preventDefault();
            fetch('<?= BASE_URL ?>/?url=notification/markAsRead', {
              method: 'POST',
              headers: {'Content-Type':'application/x-www-form-urlencoded'},
              body: 'id=' + encodeURIComponent(n.id)
            }).then(()=> {
              window.location = n.link || window.location.href;
            });
          });
          list.appendChild(a);
        });
      }
    } catch(err) {
      console.error('Erro ao buscar notificações', err);
    }
  }

  setInterval(fetchNotifications, 10000);
  fetchNotifications();
</script>
</body>
</html>
