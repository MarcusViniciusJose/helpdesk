<?php ob_start(); ?>

<nav aria-label="breadcrumb" class="mb-3">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/?url=ticket/index">Chamados</a></li>
    <li class="breadcrumb-item active">Novo Chamado</li>
  </ol>
</nav>

<h4 class="mb-4">
  <i class="bi bi-plus-circle me-2"></i>Abrir Novo Chamado
</h4>

<?php if (!empty($error)): ?>
  <div class="alert alert-danger alert-dismissible fade show">
    <i class="bi bi-exclamation-triangle me-2"></i><?= htmlspecialchars($error) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>

<div class="row">
  <div class="col-lg-8">
    <div class="card shadow-sm">
      <div class="card-body">
        <form method="post" action="<?= BASE_URL ?>/?url=ticket/store">
          <div class="mb-4">
            <label class="form-label fw-semibold">
              Título <span class="text-danger">*</span>
            </label>
            <input type="text" 
                   name="title" 
                   class="form-control" 
                   placeholder="Ex: Problema no acesso ao sistema"
                   required 
                   value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
            <small class="form-text text-muted">
              Descreva brevemente o problema ou solicitação
            </small>
          </div>
          
          <div class="mb-4">
            <label class="form-label fw-semibold">
              Descrição Detalhada <span class="text-danger">*</span>
            </label>
            <textarea name="description" 
                      class="form-control" 
                      rows="8" 
                      placeholder="Descreva o problema em detalhes, incluindo:&#10;- O que você estava fazendo quando o problema ocorreu?&#10;- Qual mensagem de erro apareceu (se houver)?&#10;- Quando o problema começou?"
                      required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
            <small class="form-text text-muted">
              Quanto mais detalhes, mais rápido conseguiremos ajudá-lo
            </small>
          </div>
          
          <div class="mb-4">
            <label class="form-label fw-semibold">Prioridade</label>
            <select name="priority" class="form-select">
              <option value="low">
                🟢 Baixa - Pode aguardar
              </option>
              <option value="medium" selected>
                🟡 Média - Problema moderado
              </option>
              <option value="high">
                🟠 Alta - Problema sério
              </option>
              <option value="critical">
                🔴 Crítica - Sistema parado
              </option>
            </select>
            <small class="form-text text-muted">
              Selecione a prioridade de acordo com a urgência
            </small>
          </div>
          
          <div class="d-flex gap-2">
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
  
  <div class="col-lg-4">
    <div class="card bg-light shadow-sm">
      <div class="card-body">
        <h6 class="card-title">
          <i class="bi bi-lightbulb me-2"></i>Dicas
        </h6>
        <ul class="small mb-0 ps-3">
          <li class="mb-2">Seja claro e objetivo no título</li>
          <li class="mb-2">Descreva o problema em detalhes</li>
          <li class="mb-2">Inclua mensagens de erro, se houver</li>
          <li class="mb-2">Informe quando o problema começou</li>
          <li>Selecione a prioridade correta</li>
        </ul>
      </div>
    </div>

    <div class="card border-info mt-3 shadow-sm">
      <div class="card-body">
        <h6 class="card-title text-info">
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
        <small class="d-block">
          <strong>🟢 Baixa:</strong> até 3 dias úteis
        </small>
      </div>
    </div>
  </div>
</div>

<?php 
$content = ob_get_clean(); 
require __DIR__ . '/../layouts/main.php'; 
?>