<?php ob_start(); ?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">
            <i class="bi bi-speedometer2 me-2"></i>Dashboard - Últimos 30 dias
        </h4>
        <div class="text-muted small">
            <i class="bi bi-calendar3 me-1"></i>
            <?= date('d/m/Y H:i') ?>
        </div>
    </div>

    <?php if ($isAdminOrTI && $kpis): ?>
    
    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card shadow-sm border-0 h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <div class="small text-white-50 mb-1">TAXA DE RESOLUÇÃO</div>
                            <h2 class="mb-0 fw-bold"><?= $kpis['taxa_resolucao'] ?>%</h2>
                        </div>
                        <i class="bi bi-graph-up-arrow fs-1 opacity-50"></i>
                    </div>
                    <div class="small">
                        <?= $kpis['resolvidos'] ?> de <?= $kpis['total_tickets'] ?> tickets resolvidos
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card shadow-sm border-0 h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <div class="small text-white-50 mb-1">TEMPO MÉDIO</div>
                            <h2 class="mb-0 fw-bold"><?= number_format($kpis['tempo_medio_horas'], 0) ?>h</h2>
                        </div>
                        <i class="bi bi-clock-history fs-1 opacity-50"></i>
                    </div>
                    <div class="small">
                        Para resolver um ticket
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card shadow-sm border-0 h-100" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <div class="small text-white-50 mb-1">URGENTES ABERTOS</div>
                            <h2 class="mb-0 fw-bold"><?= $kpis['urgentes_abertos'] ?></h2>
                        </div>
                        <i class="bi bi-exclamation-triangle-fill fs-1 opacity-50"></i>
                    </div>
                    <div class="small">
                        Prioridade alta/crítica
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card shadow-sm border-0 h-100" style="background: linear-gradient(135deg, #30cfd0 0%, #330867 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <div class="small text-white-50 mb-1">ATRASADOS (+3 DIAS)</div>
                            <h2 class="mb-0 fw-bold"><?= $kpis['atrasados'] ?></h2>
                        </div>
                        <i class="bi bi-hourglass-bottom fs-1 opacity-50"></i>
                    </div>
                    <div class="small">
                        Requerem atenção imediata
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-funnel me-2"></i>Fluxo de Atendimento
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="p-3 bg-light rounded mb-2">
                                <div class="fs-1 fw-bold text-primary"><?= $kpis['aguardando_atendimento'] ?></div>
                                <div class="small text-muted">Aguardando Atendimento</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 bg-light rounded mb-2">
                                <div class="fs-1 fw-bold text-warning"><?= $kpis['em_atendimento'] ?></div>
                                <div class="small text-muted">Em Atendimento</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 bg-light rounded mb-2">
                                <div class="fs-1 fw-bold text-success"><?= $kpis['resolvidos'] ?></div>
                                <div class="small text-muted">Resolvidos</div>
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
                            <div class="fs-4 fw-bold"><?= $kpis['novos_hoje'] ?></div>
                        </div>
                        <i class="bi bi-inbox text-primary fs-1 opacity-25"></i>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Resolvidos Hoje</div>
                            <div class="fs-4 fw-bold"><?= $kpis['resolvidos_hoje'] ?></div>
                        </div>
                        <i class="bi bi-check-circle text-success fs-1 opacity-25"></i>
                    </div>
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
                        <i class="bi bi-people me-2"></i>Performance da Equipe (Últimos 30 dias)
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
                                    <th class="text-center">Status</th>
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
                                        <div class="progress" style="height: 20px; min-width: 80px;">
                                            <div class="progress-bar 
                                                <?= $tech['taxa_conclusao'] >= 80 ? 'bg-success' : ($tech['taxa_conclusao'] >= 50 ? 'bg-warning' : 'bg-danger') ?>" 
                                                role="progressbar" 
                                                style="width: <?= $tech['taxa_conclusao'] ?>%"
                                                aria-valuenow="<?= $tech['taxa_conclusao'] ?>" 
                                                aria-valuemin="0" 
                                                aria-valuemax="100">
                                                <?= $tech['taxa_conclusao'] ?>%
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <?= $tech['tempo_medio_resolucao'] > 0 ? number_format($tech['tempo_medio_resolucao'], 0) . 'h' : '-' ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($tech['taxa_conclusao'] >= 80): ?>
                                            <span class="badge bg-success">Excelente</span>
                                        <?php elseif ($tech['taxa_conclusao'] >= 50): ?>
                                            <span class="badge bg-warning">Bom</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Atenção</span>
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

    <?php endif; ?>
</div>

<?php 
$content = ob_get_clean(); 
require __DIR__ . '/../layouts/main.php'; 
?>