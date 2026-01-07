<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$currentUser = $_SESSION['user'] ?? null;
$isAdminOrTI = $currentUser && in_array($currentUser['role'], ['admin', 'ti'], true);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HelpDesk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            padding-top: 56px;
        }

        .mobile-topbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 56px;
            background: linear-gradient(90deg, #2c3e50 0%, #34495e 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 1030;
            display: flex;
            align-items: center;
            padding: 0 1rem;
        }

        .mobile-topbar h5 {
            color: #fff;
            margin: 0;
            font-weight: 600;
            font-size: 1.25rem;
        }

        .menu-toggle {
            background: none;
            border: none;
            color: #fff;
            font-size: 1.5rem;
            padding: 0.5rem;
            cursor: pointer;
            margin-right: 1rem;
        }

        .sidebar {
            position: fixed;
            top: 56px;
            left: -100%;
            width: 280px;
            height: calc(100vh - 56px);
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            transition: left 0.3s ease;
            z-index: 1020;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        .sidebar.show {
            left: 0;
        }

        .sidebar-backdrop {
            position: fixed;
            top: 56px;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1010;
            display: none;
        }

        .sidebar-backdrop.show {
            display: block;
        }

        .sidebar-header {
            padding: 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-header small {
            color: rgba(255,255,255,0.7);
            font-size: 0.875rem;
        }

        .sidebar-content {
            flex: 1;
            overflow-y: auto;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 1rem 1.25rem;
            margin: 0.25rem 0.75rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            font-weight: 500;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .sidebar .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.15);
        }

        .sidebar .nav-link.active {
            color: #fff;
            background-color: #3498db;
            box-shadow: 0 2px 8px rgba(52, 152, 219, 0.3);
        }

        .sidebar .nav-link i {
            width: 24px;
            font-size: 1.1rem;
        }

        .nav-divider {
            height: 1px;
            background-color: rgba(255,255,255,0.1);
            margin: 1rem 1.25rem;
        }

        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            background-color: rgba(0,0,0,0.1);
            margin-top: auto;
        }

        .main-content {
            background-color: #f8f9fa;
            min-height: calc(100vh - 56px);
            padding: 1rem;
        }

        .notification-badge {
            position: absolute;
            top: 50%;
            right: 1rem;
            transform: translateY(-50%);
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: 600;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        #toast-container {
            position: fixed;
            top: 70px;
            right: 10px;
            left: 10px;
            z-index: 9999;
        }

        @media (min-width: 768px) {
            body {
                padding-top: 0;
            }

            .mobile-topbar {
                display: none;
            }

            .sidebar {
                position: sticky;
                top: 0;
                left: 0;
                width: auto;
                height: 100vh;
                transition: none;
            }

            .sidebar-backdrop {
                display: none !important;
            }

            .main-content {
                min-height: 100vh;
                padding: 2rem;
            }

            #toast-container {
                top: 20px;
                right: 20px;
                left: auto;
                max-width: 400px;
            }
        }

        @media (max-width: 767px) {
            .sidebar .nav-link {
                min-height: 48px;
            }
        }
    </style>
    <script>
        (function() {
            let notificationCount = 0;
            
            function fetchNotifications() {
                fetch('<?= BASE_URL ?>/?url=notification/get')
                    .then(response => response.json())
                    .then(data => {
                        notificationCount = data.length;
                        updateBadge();
                        
                        if (notificationCount > 0 && !sessionStorage.getItem('notifications_shown_' + data[0].id)) {
                            showToast(data[0].message);
                            sessionStorage.setItem('notifications_shown_' + data[0].id, '1');
                        }
                    })
                    .catch(error => console.error('Erro ao buscar notificações:', error));
            }
            
            function updateBadge() {
                const badge = document.getElementById('notification-count');
                if (badge) {
                    if (notificationCount > 0) {
                        badge.textContent = notificationCount > 99 ? '99+' : notificationCount;
                        badge.style.display = 'flex';
                    } else {
                        badge.style.display = 'none';
                    }
                }
            }
            
            function showToast(message) {
                let toastContainer = document.getElementById('toast-container');
                if (!toastContainer) {
                    toastContainer = document.createElement('div');
                    toastContainer.id = 'toast-container';
                    document.body.appendChild(toastContainer);
                }
                
                const toast = document.createElement('div');
                toast.className = 'alert alert-info alert-dismissible fade show shadow-lg';
                toast.style.cssText = 'margin-bottom: 10px; animation: slideIn 0.3s ease-out;';
                toast.innerHTML = `
                    <div class="d-flex align-items-center">
                        <i class="bi bi-bell-fill me-2 fs-5"></i>
                        <div class="flex-grow-1">
                            <strong class="d-block small">Nova Notificação</strong>
                            <small>${message}</small>
                        </div>
                        <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
                    </div>
                `;
                
                toastContainer.appendChild(toast);
                
                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => toast.remove(), 300);
                }, 5000);
            }
            
            fetchNotifications();
            setInterval(fetchNotifications, 30000);
        })();
    </script>
</head>
<body>
    <div class="mobile-topbar d-md-none">
        <button class="menu-toggle" id="menuToggle" aria-label="Abrir menu">
            <i class="bi bi-list"></i>
        </button>
        <h5 style="color:white">
            <i class="bi bi-headset me-2" style="color:white"></i>HelpDesk
        </h5>
    </div>

    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 p-0 sidebar" id="sidebar">
                <div class="sidebar-header d-none d-md-block">
                    <h4 style="color:white">
                        <i class="bi bi-headset me-2" style="color:white"></i >HelpDesk
                    </h4>
                    <?php if ($currentUser): ?>
                        <small>
                            Olá, <?= htmlspecialchars(explode(' ', $currentUser['name'])[0]) ?>
                        </small>
                    <?php endif; ?>
                </div>

                <?php if ($currentUser): ?>
                <div class="sidebar-header d-md-none">
                    <small>
                        Olá, <?= htmlspecialchars(explode(' ', $currentUser['name'])[0]) ?>
                    </small>
                </div>
                <?php endif; ?>
                
                <div class="sidebar-content">
                    <ul class="nav flex-column pt-3">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL ?>/?url=dashboard/index">
                                <i class="bi bi-speedometer2 me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL ?>/?url=ticket/index">
                                <i class="bi bi-ticket-perforated me-2"></i>Chamados
                            </a>
                        </li>

                        <?php if ($isAdminOrTI): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= BASE_URL ?>/?url=calendar/index">
                                    <i class="bi bi-calendar-event me-2"></i>Agenda
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($isAdminOrTI): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL ?>/?url=user/index">
                                <i class="bi bi-people-fill me-2"></i>Usuários
                            </a>
                        </li>
                        <?php endif; ?>
                            
                        <li class="nav-item">
                            <a class="nav-link position-relative" href="<?= BASE_URL ?>/?url=notification/history">
                                <i class="bi bi-bell me-2"></i>Notificações
                                <span class="notification-badge" 
                                    id="notification-count" 
                                    style="display: none;">0</span>
                            </a>
                        </li>
                    </ul>
            
                    <div class="nav-divider"></div>
                    
                    <ul class="nav flex-column mb-3">
                        <li class="nav-item">
                            <a class="nav-link text-danger" href="<?= BASE_URL ?>/?url=auth/logout">
                                <i class="bi bi-box-arrow-right me-2"></i>Sair
                            </a>
                        </li>
                    </ul>
                </div>

                <?php if ($currentUser): ?>
                <div class="sidebar-footer">
                    <small class="text-white-50 d-block">
                        <i class="bi bi-shield-check me-1"></i>
                        <?php
                        $roleLabels = [
                            'admin' => 'Administrador',
                            'ti' => 'TI',
                            'user' => 'Usuário'
                        ];
                        echo $roleLabels[$currentUser['role']] ?? $currentUser['role'];
                        ?>
                    </small>
                </div>
                <?php endif; ?>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-0 main-content">
                <?= $content ?>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const currentPath = window.location.href;
            const navLinks = document.querySelectorAll('.sidebar .nav-link');
            
            navLinks.forEach(link => {
                link.classList.remove('active');
                const linkHref = link.getAttribute('href');
                if (linkHref && currentPath.includes(linkHref)) {
                    link.classList.add('active');
                }
            });

            const menuToggle = document.getElementById('menuToggle');
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');

            function toggleMenu() {
                sidebar.classList.toggle('show');
                backdrop.classList.toggle('show');
                
                const icon = menuToggle.querySelector('i');
                if (sidebar.classList.contains('show')) {
                    icon.className = 'bi bi-x-lg';
                } else {
                    icon.className = 'bi bi-list';
                }
            }

            if (menuToggle) {
                menuToggle.addEventListener('click', toggleMenu);
            }

            if (backdrop) {
                backdrop.addEventListener('click', toggleMenu);
            }

            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 768) {
                        toggleMenu();
                    }
                });
            });
        }); 
    </script>
</body>
</html>