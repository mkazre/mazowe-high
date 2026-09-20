<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<form method="post" action="/admin/assessments/<?= (int) $assessment['id'] ?>/scores">
  <?= csrf_field() ?>
  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead><tr><th>Pupil</th><th>Score (/ <?= (int) $assessment['max_score'] ?>)</th><th>Comment</th></tr></thead>
      <tbody>
        <?php foreach ($students as $s): ?>
          <tr>
            <td><?= esc($s['first_name'] . ' ' . $s['last_name']) ?></td>
            <td><input type="number" step="0.5" name="score[<?= (int) $s['id'] ?>]" value="<?= esc($s['score']) ?>" style="width:90px"></td>
            <td><input type="text" name="comment[<?= (int) $s['id'] ?>]" value="<?= esc($s['comment']) ?>"></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <button class="btn btn-primary" type="submit" style="margin-top:16px">Save scores</button>
</form>

<?= $this->endSection() ?>
