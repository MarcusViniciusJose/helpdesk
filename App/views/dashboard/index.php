<?php 
ob_start(); 

function getStatusBadge($status) {
    $badges = [
        'open'        => '<span class="badge bg-primary">Aberto</span>',
        'in_progress' => '<span class="badge bg-warning">Em Andamento</span>',
        'closed'      => '<span class="badge bg-success">Fechado</span>',
    ];
    return $badges[$status] ?? '<span class="badge bg-secondary">' . htmlspecialchars($status) . '</span>';
}

function getPriorityBadge($priority) {
    $badges = [
        'low'      => '<span class="badge bg-info">Baixa</span>',
        'medium'   => '<span class="badge bg-secondary">Média</span>',
        'high'     => '<span class="badge bg-warning text-dark">Alta</span>',
        'critical' => '<span class="badge bg-danger">Crítica</span>',
    ];
    return $badges[$priority] ?? '<span class="badge bg-secondary">' . htmlspecialchars($priority) . '</span>';
}

function formatarDataPtBr($data) {
    $timestamp = strtotime($data);
    $meses = [
        1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
        5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
        9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
    ];
    
    $dia = date('d', $timestamp);
    $mes = $meses[(int)date('m', $timestamp)];
    $ano = date('Y', $timestamp);
    
    return "$dia de $mes de $ano";
}

function diaDaSemana($data) {
    $dias = [
        'Sunday' => 'Dom',
        'Monday' => 'Seg',
        'Tuesday' => 'Ter',
        'Wednesday' => 'Qua',
        'Thursday' => 'Qui',
        'Friday' => 'Sex',
        'Saturday' => 'Sáb'
    ];
    
    $diaIngles = date('l', strtotime($data));
    return $dias[$diaIngles] ?? '';
}
?>

<style>
.card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border-radius: 8px;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.progress-bar {
    transition: width 0.6s ease;
}

.table-responsive {
    border-radius: 8px;
    -webkit-overflow-scrolling: touch;
}

.badge {
    font-weight: 500;
    letter-spacing: 0.3px;
    font-size: 0.75rem;
    padding: 0.35em 0.65em;
}

.alert {
    border-left: 4px solid currentColor;
    border-radius: 8px;
    font-size: 0.875rem;
}

.list-group-item {
    border-left: none;
    border-right: none;
    transition: background-color 0.2s ease;
    padding: 0.75rem 0;
}

.list-group-item:hover {
    background-color: rgba(0, 0, 0, 0.02);
}

.page-header h5 {
    font-size: 1.25rem;
    margin-bottom: 0.25rem;
}

.date-time-info {
    font-size: 0.875rem;
}

.kpi-card {
    border-radius: 10px;
    overflow: hidden;
}

.kpi-card .card-body {
    padding: 1rem;
}

.kpi-card h2 {
    font-size: 1.75rem;
    margin-bottom: 0;
}

.kpi-card .small {
    font-size: 0.75rem;
}

.kpi-icon {
    font-size: 2.5rem;
}

.stat-card {
    border-left-width: 4px;
}

.stat-card .fs-2 {
    font-size: 1.75rem;
}

.stat-icon {
    font-size: 2rem;
}

.flow-box {
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.flow-box .fs-1 {
    font-size: 2rem;
}

.table {
    font-size: 0.875rem;
}

.table th {
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    white-space: nowrap;
}

.table td {
    vertical-align: middle;
}

.mobile-hide {
    display: none;
}

.progress {
    min-width: 60px;
    height: 20px;
}

.progress-bar {
    font-size: 0.7rem;
}

.table .badge {
    min-width: 50px;
    display: inline-block;
}

.ranking-badge {
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-weight: bold;
    font-size: 0.875rem;
}

.card-header {
    padding: 0.875rem 1rem;
}

.card-header h6 {
    font-size: 0.875rem;
    font-weight: 600;
}

.card-body {
    padding: 1rem;
}

.text-truncate-mobile {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    max-width: 150px;
}

.empty-state {
    padding: 2rem 1rem;
}

.empty-state i {
    font-size: 3rem;
}

@media (min-width: 576px) {
    .page-header h5 {
        font-size: 1.5rem;
    }

    .kpi-card h2 {
        font-size: 2rem;
    }

    .flow-box .fs-1 {
        font-size: 2.5rem;
    }

    .text-truncate-mobile {
        max-width: 250px;
    }

    .ranking-badge {
        width: 36px;
        height: 36px;
    }
}

@media (min-width: 768px) {
    .mobile-hide {
        display: table-cell;
    }

    .page-header h5 {
        font-size: 1.75rem;
    }

    .kpi-card .card-body {
        padding: 1.5rem;
    }

    .kpi-card h2 {
        font-size: 2.25rem;
    }

    .kpi-icon {
        font-size: 3rem;
    }

    .flow-box {
        padding: 1.5rem;
    }

    .flow-box .fs-1 {
        font-size: 3rem;
    }

    .stat-card .fs-2 {
        font-size: 2rem;
    }

    .stat-icon {
        font-size: 2.5rem;
    }

    .table {
        font-size: 0.9rem;
    }

    .table th {
        font-size: 0.8rem;
    }

    .progress {
        min-width: 100px;
        height: 22px;
    }

    .progress-bar {
        font-size: 0.75rem;
    }

    .card-header {
        padding: 1rem 1.25rem;
    }

    .card-header h6 {
        font-size: 1rem;
    }

    .card-body {
        padding: 1.25rem;
    }

    .empty-state {
        padding: 3rem 1rem;
    }

    .empty-state i {
        font-size: 4rem;
    }

    .text-truncate-mobile {
        max-width: 400px;
    }
}

@media (min-width: 992px) {
    .page-header h5 {
        font-size: 2rem;
    }

    .kpi-card h2 {
        font-size: 2.5rem;
    }
}

@media (max-width: 767px) {
    .btn {
        min-height: 44px;
        padding: 0.625rem 1rem;
    }

    .btn-sm {
        min-height: 38px;
        padding: 0.5rem 0.75rem;
    }
}

@media (max-width: 767px) {
    .table-responsive {
        border: 1px solid #dee2e6;
    }

    .table-responsive table {
        min-width: 600px;
    }
}
</style>

<div class="container-fluid px-3 px-md-4 py-3 py-md-4">
    <div class="row align-items-center mb-3 mb-md-4 g-2">
        <div class="col-12 col-md-8">
            <div class="page-header">
                <h5 class="mb-1 fw-bold">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard Gerencial
                </h5>
                <p class="text-muted small mb-0">Análise dos últimos 30 dias</p>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="date-time-info text-muted text-start text-md-end">
                <div>
                    <i class="bi bi-calendar3 me-1"></i>
                    <?= formatarDataPtBr(date('Y-m-d')) ?>
                </div>
                <div>
                    <i class="bi bi-clock me-1"></i>
                    <?= date('H:i') ?>
                </div>
            </div>
        </div>
    </div>

    <?php if ($isAdminOrTI && $kpis): ?>
    
    <div class="row g-3 mb-3 mb-md-4">
        <div class="col-6 col-lg-3">
            <div class="card kpi-card shadow-sm border-0 h-100" style="background: linear-gradient(135deg, #2996f0 0%, #1d7bc7 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="small text-white-50 mb-1 text-uppercase">Taxa de Resolução</div>
                            <h2 class="mb-0 fw-bold"><?= $kpis['taxa_resolucao'] ?>%</h2>
                            <div class="small mt-2">
                                <?= $kpis['resolvidos'] ?>/<?= $kpis['total_tickets'] ?>
                            </div>
                        </div>
                        <i class="bi bi-graph-up-arrow kpi-icon opacity-50 d-none d-md-block"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card kpi-card shadow-sm border-0 h-100" style="background: linear-gradient(135deg, #4030f0 0%, #3020c7 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="small text-white-50 mb-1 text-uppercase">Tempo Médio</div>
                            <h2 class="mb-0 fw-bold"><?= number_format($kpis['tempo_medio_horas'], 0) ?>h</h2>
                            <div class="small mt-2">
                                Para resolver
                            </div>
                        </div>
                        <i class="bi bi-clock-history kpi-icon opacity-50 d-none d-md-block"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card kpi-card shadow-sm border-0 h-100" style="background: linear-gradient(135deg, #ef391d 0%, #c72d15 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="small text-white-50 mb-1 text-uppercase">Urgentes</div>
                            <h2 class="mb-0 fw-bold"><?= $kpis['urgentes_abertos'] ?></h2>
                            <div class="small mt-2">
                                Alta/Crítica
                            </div>
                        </div>
                        <i class="bi bi-exclamation-triangle-fill kpi-icon opacity-50 d-none d-md-block"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card kpi-card shadow-sm border-0 h-100" style="background: linear-gradient(135deg, #f0601d 0%, #c74d15 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="small text-white-50 mb-1 text-uppercase">Atrasados</div>
                            <h2 class="mb-0 fw-bold"><?= $kpis['atrasados'] ?></h2>
                            <div class="small mt-2">
                                +3 dias
                            </div>
                        </div>
                        <i class="bi bi-hourglass-bottom kpi-icon opacity-50 d-none d-md-block"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3 mb-md-4">
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-funnel me-2"></i>Fluxo de Atendimento
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center g-2 g-md-3">
                        <div class="col-12 col-sm-4">
                            <div class="flow-box">
                                <div class="fs-1 fw-bold text-primary"><?= $kpis['aguardando_atendimento'] ?></div>
                                <div class="small text-muted mt-2">Aguardando</div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="flow-box">
                                <div class="fs-1 fw-bold text-warning"><?= $kpis['em_atendimento'] ?></div>
                                <div class="small text-muted mt-2">Em Atendimento</div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="flow-box">
                                <div class="fs-1 fw-bold text-success"><?= $kpis['resolvidos'] ?></div>
                                <div class="small text-muted mt-2">Resolvidos</div>
                            </div>
                        </div>
                    </div>
                    
                    <?php if ($kpis['sem_responsavel'] > 0): ?>
                    <div class="alert alert-warning mt-3 mb-0 d-flex align-items-center">
                        <i class="bi bi-exclamation-circle-fill me-2 flex-shrink-0"></i>
                        <span><strong><?= $kpis['sem_responsavel'] ?></strong> ticket(s) sem responsável</span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-calendar-day me-2"></i>Resumo de Hoje
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div>
                            <div class="text-muted small">Novos Tickets</div>
                            <div class="fs-3 fw-bold text-primary"><?= $kpis['novos_hoje'] ?></div>
                        </div>
                        <i class="bi bi-inbox text-primary fs-1 opacity-25"></i>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Resolvidos Hoje</div>
                            <div class="fs-3 fw-bold text-success"><?= $kpis['resolvidos_hoje'] ?></div>
                        </div>
                        <i class="bi bi-check-circle text-success fs-1 opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3 mb-md-4">
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-bar-chart-line me-2 text-primary"></i>
                        Abertos vs Fechados <span class="d-none d-md-inline">(Últimos 7 dias)</span>
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (empty($openVsClosedTrend)): ?>
                        <div class="empty-state text-center text-muted">
                            <i class="bi bi-inbox d-block mb-2 opacity-25"></i>
                            <p class="mb-0 small">Sem dados suficientes</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Data</th>
                                        <th class="text-center">Abertos</th>
                                        <th class="text-center">Fechados</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($openVsClosedTrend as $day): ?>
                                    <tr>
                                        <td>
                                            <strong><?= date('d/m', strtotime($day['data'])) ?></strong>
                                            <small class="text-muted ms-1"><?= diaDaSemana($day['data']) ?></small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-primary"><?= $day['abertos'] ?></span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success"><?= $day['fechados'] ?></span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-building me-2 text-success"></i>
                        Setores com Mais Chamados
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (empty($topDepartments)): ?>
                        <div class="empty-state text-center text-muted">
                            <i class="bi bi-inbox d-block mb-2 opacity-25"></i>
                            <p class="mb-0 small">Sem dados de departamentos</p>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($topDepartments as $idx => $dept): ?>
                            <div class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center gap-2">
                                <div class="d-flex align-items-center flex-grow-1 min-width-0">
                                    <div class="me-2 me-md-3 flex-shrink-0">
                                        <div class="ranking-badge
                                            <?= $idx === 0 ? 'bg-warning text-dark' : ($idx === 1 ? 'bg-secondary text-white' : ($idx === 2 ? 'bg-info text-white' : 'bg-light text-dark')) ?>">
                                            <?= $idx + 1 ?>º
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 min-width-0">
                                        <div class="fw-semibold text-truncate"><?= htmlspecialchars($dept['setor']) ?></div>
                                        <small class="text-muted d-block d-sm-inline">
                                            <i class="bi bi-folder2-open me-1"></i><?= $dept['abertos'] ?>
                                            <span class="mx-1">•</span>
                                            <i class="bi bi-check-circle me-1"></i><?= $dept['fechados'] ?>
                                        </small>
                                    </div>
                                </div>
                                <span class="badge bg-primary rounded-pill fs-6 px-2 px-md-3 flex-shrink-0">
                                    <?= $dept['total_chamados'] ?>
                                </span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($techPerformance)): ?>
    <div class="row g-3 mb-3 mb-md-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-people me-2 text-info"></i>Performance da Equipe
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Técnico</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center mobile-hide">Concluídos</th>
                                    <th class="text-center mobile-hide">Em Andamento</th>
                                    <th class="text-center">Taxa</th>
                                    <th class="text-center mobile-hide">Tempo Médio</th>
                                    <th class="text-center">Avaliação</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($techPerformance as $tech): ?>
                                <tr>
                                    <td>
                                        <i class="bi bi-person-badge me-1 me-md-2 text-primary"></i>
                                        <strong class="d-none d-md-inline"><?= htmlspecialchars($tech['tecnico']) ?></strong>
                                        <strong class="d-md-none"><?= htmlspecialchars(explode(' ', $tech['tecnico'])[0]) ?></strong>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary"><?= $tech['total_atribuidos'] ?></span>
                                    </td>
                                    <td class="text-center mobile-hide">
                                        <span class="badge bg-success"><?= $tech['concluidos'] ?></span>
                                    </td>
                                    <td class="text-center mobile-hide">
                                        <?= $tech['em_andamento'] > 0 ? '<span class="badge bg-warning">' . $tech['em_andamento'] . '</span>' : '<span class="text-muted">-</span>' ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="progress">
                                            <div class="progress-bar fw-semibold
                                                <?= $tech['taxa_conclusao'] >= 80 ? 'bg-success' : ($tech['taxa_conclusao'] >= 50 ? 'bg-warning' : 'bg-danger') ?>" 
                                                role="progressbar" 
                                                style="width: <?= $tech['taxa_conclusao'] ?>%">
                                                <?= $tech['taxa_conclusao'] ?>%
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center mobile-hide">
                                        <?= $tech['tempo_medio_resolucao'] > 0 ? '<span class="badge bg-info">' . number_format($tech['tempo_medio_resolucao'], 0) . 'h</span>' : '<span class="text-muted">-</span>' ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($tech['taxa_conclusao'] >= 80): ?>
                                            <span class="badge bg-success">
                                                <i class="bi bi-star-fill me-1 d-none d-md-inline"></i>
                                                <span class="d-none d-md-inline">Excelente</span>
                                                <span class="d-md-none">A+</span>
                                            </span>
                                        <?php elseif ($tech['taxa_conclusao'] >= 50): ?>
                                            <span class="badge bg-warning">
                                                <i class="bi bi-star-half me-1 d-none d-md-inline"></i>
                                                <span class="d-none d-md-inline">Bom</span>
                                                <span class="d-md-none">B</span>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">
                                                <i class="bi bi-exclamation-triangle me-1 d-none d-md-inline"></i>
                                                <span class="d-none d-md-inline">Atenção</span>
                                                <span class="d-md-none">C</span>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($topIssues)): ?>
    <div class="row g-3 mb-3 mb-md-4">
        <div class="col-12">
            <div class="card shadow-sm border-warning">
                <div class="card-header bg-warning bg-opacity-10 border-0">
                    <h6 class="mb-0 fw-semibold text-warning">
                        <i class="bi bi-arrow-repeat me-2"></i>Problemas Mais Recorrentes
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Problema</th>
                                    <th class="text-center mobile-hide">Prioridade</th>
                                    <th class="text-center">Repetições</th>
                                    <th class="text-center mobile-hide">Resolvidos</th>
                                    <th class="text-center">Taxa</th>
                                    <th class="mobile-hide">Última Vez</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($topIssues as $issue): ?>
                                <?php 
                                $taxaResolucao = $issue['ocorrencias'] > 0 
                                    ? round(($issue['resolvidos'] / $issue['ocorrencias']) * 100) 
                                    : 0;
                                ?>
                                <tr>
                                    <td>
                                        <div class="text-truncate-mobile" title="<?= htmlspecialchars($issue['title']) ?>">
                                            <i class="bi bi-exclamation-circle text-warning me-1 me-md-2"></i>
                                            <?= htmlspecialchars($issue['title']) ?>
                                        </div>
                                    </td>
                                    <td class="text-center mobile-hide">
                                        <?= getPriorityBadge($issue['priority']) ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-danger rounded-pill fw-bold"><?= $issue['ocorrencias'] ?>x</span>
                                    </td>
                                    <td class="text-center mobile-hide">
                                        <span class="badge bg-success"><?= $issue['resolvidos'] ?></span>
                                    </td>
                                    <td class="text-center">
                                        <div class="progress">
                                            <div class="progress-bar fw-semibold <?= $taxaResolucao >= 80 ? 'bg-success' : ($taxaResolucao >= 50 ? 'bg-warning' : 'bg-danger') ?>" 
                                                 style="width: <?= $taxaResolucao ?>%">
                                                <?= $taxaResolucao ?>%
                                            </div>
                                        </div>
                                    </td>
                                    <td class="mobile-hide">
                                        <small class="text-muted">
                                            <?= date('d/m H:i', strtotime($issue['ultima_ocorrencia'])) ?>
                                        </small>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="alert alert-warning border-0 mt-3 mb-0">
                        <i class="bi bi-lightbulb-fill me-2"></i>
                        <strong>Análise:</strong> Problemas recorrentes indicam necessidade de ações preventivas como 
                        treinamento, documentação ou melhorias em processos/sistemas.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($topRequesters)): ?>
    <div class="row g-3 mb-3 mb-md-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-person-lines-fill me-2 text-danger"></i>Usuários que Mais Abriram Chamados
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 60px;">Ranking</th>
                                    <th>Usuário</th>
                                    <th class="mobile-hide">E-mail</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center mobile-hide">Abertos</th>
                                    <th class="text-center mobile-hide">Em Andamento</th>
                                    <th class="text-center mobile-hide">Fechados</th>
                                    <th class="mobile-hide">Último Ticket</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($topRequesters as $idx => $requester): ?>
                                <tr>
                                    <td class="text-center">
                                        <?php if ($idx === 0): ?>
                                            <span class="badge bg-warning text-dark fs-6 px-2 px-md-3">
                                                <i class="bi bi-trophy-fill me-1 d-none d-md-inline"></i>1º
                                            </span>
                                        <?php elseif ($idx === 1): ?>
                                            <span class="badge bg-secondary fs-6 px-2 px-md-3">
                                                <i class="bi bi-award-fill me-1 d-none d-md-inline"></i>2º
                                            </span>
                                        <?php elseif ($idx === 2): ?>
                                            <span class="badge bg-info fs-6 px-2 px-md-3">
                                                <i class="bi bi-award-fill me-1 d-none d-md-inline"></i>3º
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted"><?= $idx + 1 ?>º</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <i class="bi bi-person-circle me-1 me-md-2 text-primary"></i>
                                        <strong class="d-none d-md-inline"><?= htmlspecialchars($requester['name']) ?></strong>
                                        <strong class="d-md-none"><?= htmlspecialchars(explode(' ', $requester['name'])[0]) ?></strong>
                                    </td>
                                    <td class="mobile-hide">
                                        <small><?= htmlspecialchars($requester['email']) ?></small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary rounded-pill fs-6 px-2 px-md-3"><?= $requester['total_chamados'] ?></span>
                                    </td>
                                    <td class="text-center mobile-hide">
                                        <?= $requester['abertos'] > 0 ? '<span class="badge bg-danger">' . $requester['abertos'] . '</span>' : '<span class="text-muted">-</span>' ?>
                                    </td>
                                    <td class="text-center mobile-hide">
                                        <?= $requester['em_andamento'] > 0 ? '<span class="badge bg-warning">' . $requester['em_andamento'] . '</span>' : '<span class="text-muted">-</span>' ?>
                                    </td>
                                    <td class="text-center mobile-hide">
                                        <?= $requester['fechados'] > 0 ? '<span class="badge bg-success">' . $requester['fechados'] . '</span>' : '<span class="text-muted">-</span>' ?>
                                    </td>
                                    <td class="mobile-hide">
                                        <small class="text-muted">
                                            <?= date('d/m/Y H:i', strtotime($requester['ultimo_chamado'])) ?>
                                        </small>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="alert alert-info border-0 mt-3 mb-0">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        <strong>Recomendação:</strong> Usuários com alto volume de chamados podem necessitar de 
                        suporte adicional, treinamento específico ou revisão de suas ferramentas de trabalho.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php else: ?>
    
    <div class="row g-3 mb-3 mb-md-4">
        <div class="col-6 col-md-3">
            <div class="card stat-card border-start border-primary border-4 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small text-uppercase fw-semibold mb-1">Abertos</div>
                            <div class="fs-2 fw-bold text-primary"><?= $stats['abertos'] ?></div>
                        </div>
                        <div class="text-primary opacity-50">
                            <i class="bi bi-folder2-open stat-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-6 col-md-3">
            <div class="card stat-card border-start border-warning border-4 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small text-uppercase fw-semibold mb-1">Em Andamento</div>
                            <div class="fs-2 fw-bold text-warning"><?= $stats['em_andamento'] ?></div>
                        </div>
                        <div class="text-warning opacity-50">
                            <i class="bi bi-hourglass-split stat-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-6 col-md-3">
            <div class="card stat-card border-start border-success border-4 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small text-uppercase fw-semibold mb-1">Fechados</div>
                            <div class="fs-2 fw-bold text-success"><?= $stats['fechados'] ?></div>
                        </div>
                        <div class="text-success opacity-50">
                            <i class="bi bi-check-circle stat-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-6 col-md-3">
            <div class="card stat-card border-start border-info border-4 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small text-uppercase fw-semibold mb-1">Total</div>
                            <div class="fs-2 fw-bold text-info"><?= $stats['total'] ?></div>
                        </div>
                        <div class="text-info opacity-50">
                            <i class="bi bi-card-list stat-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body text-center py-4 py-md-5">
                    <i class="bi bi-ticket-perforated text-primary opacity-25" style="font-size: 3rem;"></i>
                    <h5 class="mt-3 mb-2">Seus Chamados</h5>
                    <p class="text-muted mb-4">
                        Você tem <?= $stats['total'] ?> chamado(s) registrado(s) no sistema.
                    </p>
                    <div class="d-grid d-sm-flex gap-2 justify-content-center">
                        <a href="<?= BASE_URL ?>/?url=ticket/index" class="btn btn-primary">
                            <i class="bi bi-list-ul me-2"></i>Ver Meus Chamados
                        </a>
                        <a href="<?= BASE_URL ?>/?url=ticket/create" class="btn btn-success">
                            <i class="bi bi-plus-circle me-2"></i>Abrir Novo Chamado
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php endif; ?>
</div>

<?php 
$content = ob_get_clean(); 
require __DIR__ . '/../layouts/main.php'; 
?>