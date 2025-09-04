<h3 class="mb-3">Novo Usuário</h3>

<?php if (!empty($errors)): ?>
  <div class="alert alert-danger">
    <ul class="m-0">
      <?php foreach ($errors as $e): ?>
        <li><?= htmlspecialchars($e) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<form method="post" action="<?= BASE_URL ?>/?url=user/store" class="card">
  <div class="card-body">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Nome</label>
        <input type="text" name="name" class="form-control"
               value="<?= htmlspecialchars($form['name'] ?? '') ?>" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">E-mail corporativo</label>
        <input type="email" name="email" class="form-control"
               value="<?= htmlspecialchars($form['email'] ?? '') ?>" required>
      </div>

      <div class="col-md-4">
        <label class="form-label">Papel (Role)</label>
        <select name="role" class="form-select">
          <?php
            $sel = $form['role'] ?? 'user';
            $roles = ['user' => 'Usuário', 'ti'=>'Equipe TI', 'admin'=>'Administrador'];
            foreach ($roles as $val => $label):
          ?>
          <option value="<?= $val ?>" <?= $val===$sel?'selected':'' ?>><?= $label ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-4">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
          <?php
            $st = $form['status'] ?? 'active';
            $opts = ['active' => 'Ativo', 'inactive' => 'Inativo'];
            foreach ($opts as $val => $label):
          ?>
          <option value="<?= $val ?>" <?= $val===$st?'selected':'' ?>><?= $label ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-12">
        <div class="alert alert-info mb-0">
          A senha inicial será: <code>senha123</code>.  
          O usuário poderá trocá-la depois.
        </div>
      </div>
    </div>
  </div>
  <div class="card-footer d-flex justify-content-between">
    <a class="btn btn-outline-secondary" href="<?= BASE_URL ?>/?url=user/index">Cancelar</a>
    <button class="btn btn-primary" type="submit">Salvar</button>
  </div>
</form>
