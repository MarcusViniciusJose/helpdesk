<?php ob_start(); ?>
<h4 class="mb-3">Novo Usuário</h4>
<div class="card">
  <div class="card-body">
    <form method="post" action="<?= BASE_URL ?>/?url=user/store">
      <div class="mb-3">
        <label class="form-label">Nome</label>
        <input type="text" name="name" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">E-mail</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Papel</label>
        <select name="role" class="form-select" required>
          <option value="cliente">Cliente</option>
          <option value="atendente">Atendente</option>
          <option value="gerencia">Gerência</option>
          <option value="admin">Administrador</option>
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
          <option value="active" selected>Ativo</option>
          <option value="inactive">Inativo</option>
        </select>
      </div>
      <button class="btn btn-success">Salvar</button>
      <a href="<?= BASE_URL ?>/?url=user/index" class="btn btn-outline-secondary">Cancelar</a>
    </form>
  </div>
</div>

