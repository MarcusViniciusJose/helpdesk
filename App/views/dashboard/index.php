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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
.card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.progress-bar {
    transition: width 0.6s ease;
}

.table-responsive {
    border-radius: 0.375rem;
}

.badge {
    font-weight: 500;
    letter-spacing: 0.3px;
}

.card[style*="gradient"] {
    transition: all 0.3s ease;
}

.card[style*="gradient"]:hover {
    box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
}

.alert {
    border-left: 4px solid currentColor;
}

.list-group-item {
    border-left: none;
    border-right: none;
    transition: background-color 0.2s ease;
}

.list-group-item:hover {
    background-color: rgba(0, 0, 0, 0.02);
}
</style>
</head>
<body>
    <div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">
                <i class="bi bi-speedometer2 me-2"></i>Dashboard Gerencial
            </h4>
            <p class="text-muted small mb-0">Análise dos últimos 30 dias</p>
        </div>
        <div class="text-end">
            <div class="text-muted small">
                <i class="bi bi-calendar3 me-1"></i>
                <?= formatarDataPtBr(date('Y-m-d')) ?>
            </div>
            <div class="text-muted small">
                <i class="bi bi-clock me-1"></i>
                <?= date('H:i') ?>
            </div>
        </div>
    </div>

    <?php if ($isAdminOrTI && $kpis): ?>
    
    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card shadow-sm border-0 h-100" style="background: #2996f0b1;">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="flex-grow-1">
                            <div class="small text-white-50 mb-1">TAXA DE RESOLUÇÃO</div>
                            <h2 class="mb-0 fw-bold"><?= $kpis['taxa_resolucao'] ?>%</h2>
                            <div class="small mt-2">
                                <?= $kpis['resolvidos'] ?> de <?= $kpis['total_tickets'] ?> resolvidos
                            </div>
                        </div>
                        <i class="bi bi-graph-up-arrow fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card shadow-sm border-0 h-100" style="background: #4030f0b8;">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="flex-grow-1">
                            <div class="small text-white-50 mb-1">TEMPO MÉDIO</div>
                            <h2 class="mb-0 fw-bold"><?= number_format($kpis['tempo_medio_horas'], 0) ?>h</h2>
                            <div class="small mt-2">
                                Para resolver um ticket
                            </div>
                        </div>
                        <i class="bi bi-clock-history fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card shadow-sm border-0 h-100" style="background: #ef391dc6;">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="flex-grow-1">
                            <div class="small text-white-50 mb-1">URGENTES ABERTOS</div>
                            <h2 class="mb-0 fw-bold"><?= $kpis['urgentes_abertos'] ?></h2>
                            <div class="small mt-2">
                                Alta/Crítica prioridade
                            </div>
                        </div>
                        <i class="bi bi-exclamation-triangle-fill fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card shadow-sm border-0 h-100" style="background: #f0601dbb;">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="flex-grow-1">
                            <div class="small text-white-50 mb-1">ATRASADOS (+3 DIAS)</div>
                            <h2 class="mb-0 fw-bold"><?= $kpis['atrasados'] ?></h2>
                            <div class="small mt-2">
                                Atenção imediata
                            </div>
                        </div>
                        <i class="bi bi-hourglass-bottom fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-funnel me-2"></i>Fluxo de Atendimento
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center g-3">
                        <div class="col-4">
                            <div class="p-3 bg-light rounded">
                                <div class="fs-1 fw-bold text-primary"><?= $kpis['aguardando_atendimento'] ?></div>
                                <div class="small text-muted mt-2">Aguardando</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 bg-light rounded">
                                <div class="fs-1 fw-bold text-warning"><?= $kpis['em_atendimento'] ?></div>
                                <div class="small text-muted mt-2">Em Atendimento</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 bg-light rounded">
                                <div class="fs-1 fw-bold text-success"><?= $kpis['resolvidos'] ?></div>
                                <div class="small text-muted mt-2">Resolvidos</div>
                            </div>
                        </div>
                    </div>
                    
                    <?php if ($kpis['sem_responsavel'] > 0): ?>
                    <div class="alert alert-warning mt-3 mb-0 d-flex align-items-center">
                        <i class="bi bi-exclamation-circle-fill me-2"></i>
                        <strong><?= $kpis['sem_responsavel'] ?></strong>&nbsp;ticket(s) sem responsável atribuído
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
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

    <div class="row g-3 mb-4">
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-bar-chart-line me-2 text-primary"></i>Abertos vs Fechados (Últimos 7 dias)
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (empty($openVsClosedTrend)): ?>
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>
                            <p class="mb-0">Sem dados suficientes</p>
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
                                    <?php $saldo = $day['abertos'] - $day['fechados']; ?>
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

        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-building me-2 text-success"></i>Setores com Mais Chamados
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (empty($topDepartments)): ?>
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>
                            <p class="mb-0">Sem dados de departamentos</p>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($topDepartments as $idx => $dept): ?>
                            <div class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center flex-grow-1">
                                    <div class="me-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold
                                            <?= $idx === 0 ? 'bg-warning text-dark' : ($idx === 1 ? 'bg-secondary text-white' : ($idx === 2 ? 'bg-info text-white' : 'bg-light text-dark')) ?>" 
                                             style="width: 36px; height: 36px;">
                                            <?= $idx + 1 ?>º
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold"><?= htmlspecialchars($dept['setor']) ?></div>
                                        <small class="text-muted">
                                            <i class="bi bi-folder2-open me-1"></i><?= $dept['abertos'] ?> abertos
                                            <span class="mx-1">•</span>
                                            <i class="bi bi-check-circle me-1"></i><?= $dept['fechados'] ?> fechados
                                        </small>
                                    </div>
                                </div>
                                <span class="badge bg-primary rounded-pill fs-6 px-3"><?= $dept['total_chamados'] ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($techPerformance)): ?>
    <div class="row g-3 mb-4">
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
                                    <th class="text-center">Concluídos</th>
                                    <th class="text-center">Em Andamento</th>
                                    <th class="text-center">Taxa Conclusão</th>
                                    <th class="text-center">Tempo Médio</th>
                                    <th class="text-center">Avaliação</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($techPerformance as $tech): ?>
                                <tr>
                                    <td>
                                        <i class="bi bi-person-badge me-2 text-primary"></i>
                                        <strong><?= htmlspecialchars($tech['tecnico']) ?></strong>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary"><?= $tech['total_atribuidos'] ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success"><?= $tech['concluidos'] ?></span>
                                    </td>
                                    <td class="text-center">
                                        <?= $tech['em_andamento'] > 0 ? '<span class="badge bg-warning">' . $tech['em_andamento'] . '</span>' : '-' ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="progress" style="height: 22px; min-width: 100px;">
                                            <div class="progress-bar fw-semibold
                                                <?= $tech['taxa_conclusao'] >= 80 ? 'bg-success' : ($tech['taxa_conclusao'] >= 50 ? 'bg-warning' : 'bg-danger') ?>" 
                                                role="progressbar" 
                                                style="width: <?= $tech['taxa_conclusao'] ?>%">
                                                <?= $tech['taxa_conclusao'] ?>%
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <?= $tech['tempo_medio_resolucao'] > 0 ? '<span class="badge bg-info">' . number_format($tech['tempo_medio_resolucao'], 0) . 'h</span>' : '-' ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($tech['taxa_conclusao'] >= 80): ?>
                                            <span class="badge bg-success"><i class="bi bi-star-fill me-1"></i>Excelente</span>
                                        <?php elseif ($tech['taxa_conclusao'] >= 50): ?>
                                            <span class="badge bg-warning"><i class="bi bi-star-half me-1"></i>Bom</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger"><i class="bi bi-exclamation-triangle me-1"></i>Atenção</span>
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
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-warning">
                <div class="card-header bg-warning bg-opacity-10 border-0">
                    <h6 class="mb-0 fw-semibold text-warning">
                        <i class="bi bi-arrow-repeat me-2"></i>Problemas Mais Recorrentes
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 40%;">Problema</th>
                                    <th class="text-center">Prioridade</th>
                                    <th class="text-center">Repetições</th>
                                    <th class="text-center">Resolvidos</th>
                                    <th class="text-center">Taxa Resolução</th>
                                    <th>Última Vez</th>
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
                                        <div class="text-truncate" style="max-width: 400px;" title="<?= htmlspecialchars($issue['title']) ?>">
                                            <i class="bi bi-exclamation-circle text-warning me-2"></i>
                                            <?= htmlspecialchars($issue['title']) ?>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <?= getPriorityBadge($issue['priority']) ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-danger rounded-pill fw-bold"><?= $issue['ocorrencias'] ?>x</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success"><?= $issue['resolvidos'] ?></span>
                                    </td>
                                    <td class="text-center">
                                        <div class="progress" style="height: 22px; min-width: 80px;">
                                            <div class="progress-bar fw-semibold <?= $taxaResolucao >= 80 ? 'bg-success' : ($taxaResolucao >= 50 ? 'bg-warning' : 'bg-danger') ?>" 
                                                 style="width: <?= $taxaResolucao ?>%">
                                                <?= $taxaResolucao ?>%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
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
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-person-lines-fill me-2 text-danger"></i>Usuários que Mais Abriram Chamados
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 60px;">Ranking</th>
                                    <th>Usuário</th>
                                    <th>E-mail</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Abertos</th>
                                    <th class="text-center">Em Andamento</th>
                                    <th class="text-center">Fechados</th>
                                    <th>Último Ticket</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($topRequesters as $idx => $requester): ?>
                                <tr>
                                    <td class="text-center">
                                        <?php if ($idx === 0): ?>
                                            <span class="badge bg-warning text-dark fs-6 px-3">
                                                <i class="bi bi-trophy-fill me-1"></i>1º
                                            </span>
                                        <?php elseif ($idx === 1): ?>
                                            <span class="badge bg-secondary fs-6 px-3">
                                                <i class="bi bi-award-fill me-1"></i>2º
                                            </span>
                                        <?php elseif ($idx === 2): ?>
                                            <span class="badge bg-info fs-6 px-3">
                                                <i class="bi bi-award-fill me-1"></i>3º
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted"><?= $idx + 1 ?>º</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <i class="bi bi-person-circle me-2 text-primary"></i>
                                        <strong><?= htmlspecialchars($requester['name']) ?></strong>
                                    </td>
                                    <td>
                                        <small class="text-muted"><?= htmlspecialchars($requester['email']) ?></small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary rounded-pill fs-6 px-3"><?= $requester['total_chamados'] ?></span>
                                    </td>
                                    <td class="text-center">
                                        <?= $requester['abertos'] > 0 ? '<span class="badge bg-danger">' . $requester['abertos'] . '</span>' : '<span class="text-muted">-</span>' ?>
                                    </td>
                                    <td class="text-center">
                                        <?= $requester['em_andamento'] > 0 ? '<span class="badge bg-warning">' . $requester['em_andamento'] . '</span>' : '<span class="text-muted">-</span>' ?>
                                    </td>
                                    <td class="text-center">
                                        <?= $requester['fechados'] > 0 ? '<span class="badge bg-success">' . $requester['fechados'] . '</span>' : '<span class="text-muted">-</span>' ?>
                                    </td>
                                    <td>
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
    
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-start border-primary border-4 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small text-uppercase fw-semibold">Abertos</div>
                            <div class="fs-2 fw-bold text-primary"><?= $stats['abertos'] ?></div>
                        </div>
                        <div class="text-primary opacity-50">
                            <i class="bi bi-folder2-open" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-start border-warning border-4 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small text-uppercase fw-semibold">Em Andamento</div>
                            <div class="fs-2 fw-bold text-warning"><?= $stats['em_andamento'] ?></div>
                        </div>
                        <div class="text-warning opacity-50">
                            <i class="bi bi-hourglass-split" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-start border-success border-4 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small text-uppercase fw-semibold">Fechados</div>
                            <div class="fs-2 fw-bold text-success"><?= $stats['fechados'] ?></div>
                        </div>
                        <div class="text-success opacity-50">
                            <i class="bi bi-check-circle" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-start border-info border-4 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small text-uppercase fw-semibold">Total</div>
                            <div class="fs-2 fw-bold text-info"><?= $stats['total'] ?></div>
                        </div>
                        <div class="text-info opacity-50">
                            <i class="bi bi-card-list" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-ticket-perforated text-primary opacity-25" style="font-size: 4rem;"></i>
                    <h5 class="mt-3 mb-2">Seus Chamados</h5>
                    <p class="text-muted">
                        Você tem <?= $stats['total'] ?> chamado(s) registrado(s) no sistema.
                    </p>
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

    <?php endif; ?>
</div>
</body>
</html>



<?php 
$content = ob_get_clean(); 
require __DIR__ . '/../layouts/main.php'; 
?>