<?php ob_start(); ?>

<style>
.breadcrumb {
    font-size: 0.875rem;
    padding: 0.5rem 0;
    margin-bottom: 1rem;
}

.page-title {
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
}

.page-title i {
    font-size: 1.25rem;
}


.form-label {
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.form-control,
.form-select {
    font-size: 16px; 
    padding: 0.75rem;
}

textarea.form-control {
    min-height: 150px;
}

.form-text {
    font-size: 0.8rem;
    margin-top: 0.375rem;
}

.card {
    border-radius: 8px;
    margin-bottom: 1rem;
}

.card-body {
    padding: 1rem;
}

.card-title {
    font-size: 1rem;
    margin-bottom: 0.75rem;
}

#filePreview .alert {
    padding: 0.75rem;
    margin: 0;
}

#filePreview .fs-3 {
    font-size: 2rem;
}

.btn {
    min-height: 44px;
    padding: 0.625rem 1rem;
    font-size: 0.95rem;
}

.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.action-buttons .btn {
    width: 100%;
}

.info-card {
    margin-bottom: 1rem;
}

.info-card .card-body {
    padding: 1rem;
}

.info-card ul {
    margin-bottom: 0;
    padding-left: 1.25rem;
}

.info-card li {
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.info-card small {
    font-size: 0.875rem;
    line-height: 1.5;
}

kbd {
    padding: 0.2rem 0.4rem;
    font-size: 0.75rem;
    background-color: #212529;
    border-radius: 0.25rem;
}

.alert {
    font-size: 0.875rem;
    padding: 0.75rem 1rem;
    border-radius: 8px;
}

@media (min-width: 576px) {
    .page-title {
        font-size: 1.75rem;
    }

    textarea.form-control {
        min-height: 180px;
    }

    .card-body {
        padding: 1.25rem;
    }

    .info-card li {
        font-size: 0.9rem;
    }
}

@media (min-width: 768px) {
    .breadcrumb {
        font-size: 0.95rem;
        padding: 0.75rem 0;
        margin-bottom: 1.5rem;
    }

    .page-title {
        font-size: 2rem;
        margin-bottom: 2rem;
    }

    .page-title i {
        font-size: 1.5rem;
    }

    .form-label {
        font-size: 1rem;
    }

    textarea.form-control {
        min-height: 200px;
    }

    .card-body {
        padding: 1.5rem;
    }

    .card-title {
        font-size: 1.1rem;
        margin-bottom: 1rem;
    }

    .action-buttons {
        flex-direction: row;
    }

    .action-buttons .btn {
        width: auto;
    }

    .info-card {
        margin-bottom: 1.5rem;
    }

    .info-card li {
        font-size: 0.95rem;
    }

    .info-card small {
        font-size: 0.9rem;
    }

    kbd {
        font-size: 0.8rem;
    }
}

@media (min-width: 992px) {
    .info-card .card-body {
        padding: 1.25rem;
    }
}

@media (max-width: 767px) {
    .btn-close {
        padding: 0.5rem;
    }

    input[type="file"] {
        padding: 0.5rem;
    }
}
</style>

<div class="container-fluid px-3 px-md-4 py-3 py-md-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/?url=ticket/index">Chamados</a></li>
            <li class="breadcrumb-item active">Novo Chamado</li>
        </ol>
    </nav>

    <h4 class="page-title fw-bold">
        <i class="bi bi-plus-circle me-2"></i>Abrir Novo Chamado
    </h4>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-2"></i><?= htmlspecialchars($error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row g-3">
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="post" action="<?= BASE_URL ?>/?url=ticket/store" enctype="multipart/form-data">
                        <div class="mb-3 mb-md-4">
                            <label class="form-label fw-semibold">
                                Título <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="title" 
                                   class="form-control" 
                                   placeholder="Ex: Problema no acesso ao sistema"
                                   required 
                                   value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
                            <small class="form-text text-muted d-block mt-2">
                                Descreva brevemente o problema ou solicitação
                            </small>
                        </div>
                        
                        <div class="mb-3 mb-md-4">
                            <label class="form-label fw-semibold">
                                Descrição Detalhada <span class="text-danger">*</span>
                            </label>
                            <textarea name="description" 
                                      class="form-control" 
                                      rows="8" 
                                      placeholder="Descreva o problema em detalhes, incluindo:&#10;- O que você estava fazendo quando o problema ocorreu?&#10;- Qual mensagem de erro apareceu (se houver)?&#10;- Quando o problema começou?"
                                      required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                            <small class="form-text text-muted d-block mt-2">
                                Quanto mais detalhes, mais rápido conseguiremos ajudá-lo
                            </small>
                        </div>

                        <div class="mb-3 mb-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-paperclip me-1"></i>Anexar Print ou Arquivo (Opcional)
                            </label>
                            <input type="file" 
                                   name="attachment" 
                                   class="form-control" 
                                   accept="image/*,.pdf"
                                   id="fileInput">
                            <small class="form-text text-muted d-block mt-2">
                                <i class="bi bi-info-circle me-1"></i>
                                Anexe um print da tela do erro ou documento PDF (máx. 5MB)
                            </small>
                            
                            <div id="filePreview" class="mt-3" style="display: none;">
                                <div class="alert alert-info d-flex align-items-center mb-0">
                                    <i class="bi bi-file-earmark-image fs-3 me-3 flex-shrink-0"></i>
                                    <div class="flex-grow-1 min-width-0">
                                        <strong id="fileName" class="d-block text-truncate"></strong>
                                        <div class="small text-muted" id="fileSize"></div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-danger ms-2 flex-shrink-0" onclick="clearFile()">
                                        <i class="bi bi-x-lg"></i>
                                        <span class="d-none d-md-inline ms-1">Remover</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3 mb-md-4">
                            <label class="form-label fw-semibold">Prioridade</label>
                            <select name="priority" class="form-select">
                                <option value="low">🟢 Baixa - Pode aguardar</option>
                                <option value="medium" selected>🟡 Média - Problema moderado</option>
                                <option value="high">🟠 Alta - Problema sério</option>
                                <option value="critical">🔴 Crítica - Sistema parado</option>
                            </select>
                            <small class="form-text text-muted d-block mt-2">
                                Selecione a prioridade de acordo com a urgência
                            </small>
                        </div>

                        <div class="action-buttons">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-send me-1"></i>Enviar Chamado
                            </button>
                            <a class="btn btn-outline-secondary" href="<?= BASE_URL ?>/?url=ticket/index">
                                <i class="bi bi-x-lg me-1"></i>Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-lg-4">
            <div class="card bg-light shadow-sm info-card">
                <div class="card-body">
                    <h6 class="card-title fw-semibold">
                        <i class="bi bi-lightbulb me-2"></i>Dicas
                    </h6>
                    <ul class="mb-0">
                        <li>Seja claro e objetivo no título</li>
                        <li>Descreva o problema em detalhes</li>
                        <li>Inclua mensagens de erro, se houver</li>
                        <li>Informe quando o problema começou</li>
                        <li>📸 <strong>Anexe um print da tela com o erro</strong></li>
                        <li class="mb-0">Selecione a prioridade correta</li>
                    </ul>
                </div>
            </div>

            <div class="card border-info shadow-sm info-card">
                <div class="card-body">
                    <h6 class="card-title text-info fw-semibold">
                        <i class="bi bi-info-circle me-2"></i>Tempo de Resposta
                    </h6>
                    <small class="d-block mb-2">
                        <strong>🔴 Crítica:</strong> até 2 horas
                    </small>
                    <small class="d-block mb-2">
                        <strong>🟠 Alta:</strong> até 4 horas
                    </small>
                    <small class="d-block mb-2">
                        <strong>🟡 Média:</strong> até 1 dia útil
                    </small>
                    <small class="d-block mb-0">
                        <strong>🟢 Baixa:</strong> até 3 dias úteis
                    </small>
                </div>
            </div>

            <div class="card border-success shadow-sm info-card">
                <div class="card-body">
                    <h6 class="card-title text-success fw-semibold">
                        <i class="bi bi-camera me-2"></i>Como Tirar Print
                    </h6>
                    <small class="d-block mb-2">
                        <strong>Windows:</strong> <kbd>Win</kbd> + <kbd>Shift</kbd> + <kbd>S</kbd>
                    </small>
                    <small class="d-block mb-2">
                        <strong>Mac:</strong> <kbd>Cmd</kbd> + <kbd>Shift</kbd> + <kbd>4</kbd>
                    </small>
                    <small class="d-block mb-0">
                        <strong>Celular:</strong> <kbd>Volume</kbd> + <kbd>Power</kbd>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('fileInput')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('filePreview');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    
    if (file) {
        const maxSize = 5 * 1024 * 1024;
        if (file.size > maxSize) {
            alert('Arquivo muito grande! Tamanho máximo: 5MB');
            e.target.value = '';
            preview.style.display = 'none';
            return;
        }
        
        fileName.textContent = file.name;
        const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
        fileSize.textContent = `Tamanho: ${sizeMB} MB`;
        preview.style.display = 'block';
    } else {
        preview.style.display = 'none';
    }
});

function clearFile() {
    document.getElementById('fileInput').value = '';
    document.getElementById('filePreview').style.display = 'none';
}
</script>

<?php 
$content = ob_get_clean(); 
require __DIR__ . '/../layouts/main.php'; 
?>