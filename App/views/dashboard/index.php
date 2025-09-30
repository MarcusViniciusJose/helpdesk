<?php 
ob_start(); 
?>
<div class="container-fluid py-4">
    <h4 class="mb-4">
        <i class="bi bi-speedometer2 me-2"></i>Dashboard
    </h4>
    
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
    
    <?php if ($stats['total'] === 0): ?>
    <div class="alert alert-info d-flex align-items-center shadow-sm" role="alert">
        <i class="bi bi-info-circle-fill me-3 fs-4"></i>
        <div>
            <strong>Nenhum ticket encontrado.</strong>
            <p class="mb-0">Você ainda não possui tickets no sistema.</p>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php 
$content = ob_get_clean(); 
require __DIR__ . '/../layouts/main.php'; 
?>