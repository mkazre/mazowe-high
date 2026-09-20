<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<form method="post" action="/admin/users/<?= $item ? (int) $item['id'] . '/edit' : 'create' ?>" class="admin-card">
  <?= csrf_field() ?>
  <div class="form-grid">
    <label>Full name<input type="text" name="name" required value="<?= esc($item['name'] ?? '') ?>"></label>
    <label>Email<input type="email" name="email" required value="<?= esc($item['email'] ?? '') ?>"></label>
    <label>Role
      <select name="role_id">
        <?php foreach ($roles as $r): ?>
          <option value="<?= (int) $r['id'] ?>" <?= ($item['role_id'] ?? null) == $r['id'] ? 'selected' : '' ?>><?= esc($r['name']) ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <label>Password <?= $item ? '(leave blank to keep current)' : '' ?><input type="password" name="password" <?= $item ? '' : 'required' ?>></label>
  </div>
  <?php if ($item): ?>
    <label>Status
      <select name="status">
        <option value="active" <?= ($item['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Active</option>
        <option value="suspended" <?= ($item['status'] ?? '') === 'suspended' ? 'selected' : '' ?>>Suspended</option>
      </select>
    </label>
  <?php endif; ?>
  <button class="btn btn-primary" type="submit">Save user</button>
</form>

<?= $this->endSection() ?>
