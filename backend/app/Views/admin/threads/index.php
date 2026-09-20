<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<?php foreach ($threads as $t): ?>
  <div class="admin-card">
    <h3 style="margin-top:0"><?= esc($t['subject']) ?><?php if ($t['first_name']): ?> — <?= esc($t['first_name'] . ' ' . $t['last_name']) ?><?php endif; ?></h3>
    <?php foreach ($t['messages'] as $m): ?>
      <p><strong><?= esc($m['sender_label']) ?>:</strong> <?= esc($m['body']) ?></p>
    <?php endforeach; ?>
    <form method="post" action="/admin/threads/<?= (int) $t['id'] ?>/reply" style="display:flex;gap:8px;margin-top:12px">
      <?= csrf_field() ?>
      <input type="text" name="body" placeholder="Reply…" style="flex:1" required>
      <button type="submit" class="btn btn-primary">Send</button>
    </form>
  </div>
<?php endforeach; ?>
<?php if (empty($threads)): ?><p>No message threads yet.</p><?php endif; ?>

<?= $this->endSection() ?>
