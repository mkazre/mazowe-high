<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<h3>Assessments</h3>
<p class="form-note">New assessments are created from the mobile teacher app (Enter marks). Click one below to enter or review scores here instead.</p>
<div class="admin-table-wrap">
  <table class="admin-table">
    <thead><tr><th>Class</th><th>Subject</th><th>Name</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($assessments as $a): ?>
        <tr>
          <td><?= esc($a['class_name']) ?></td>
          <td><?= esc($a['subject_name']) ?></td>
          <td><?= esc($a['name']) ?></td>
          <td class="row-actions"><a href="/admin/assessments/<?= (int) $a['id'] ?>">Enter scores</a></td>
        </tr>
      <?php endforeach; ?>
      <?php if (empty($assessments)): ?><tr><td colspan="4">No assessments yet.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>

<h3 style="margin-top:28px">Term reports</h3>
<div class="admin-table-wrap">
  <table class="admin-table">
    <thead><tr><th>Pupil</th><th>Status</th><th>Generated</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($reports as $r): ?>
        <tr>
          <td><?= esc($r['first_name'] . ' ' . $r['last_name']) ?></td>
          <td><span class="pill <?= $r['published'] ? 'pill-published' : '' ?>"><?= $r['published'] ? 'Published' : 'Draft' ?></span></td>
          <td><?= esc($r['generated_at']) ?></td>
          <td class="row-actions">
            <?php if (! $r['published']): ?>
              <form method="post" action="/admin/assessments/reports/<?= (int) $r['id'] ?>/publish">
                <?= csrf_field() ?>
                <button type="submit">Publish</button>
              </form>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (empty($reports)): ?><tr><td colspan="4">No reports generated yet.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>

<?= $this->endSection() ?>
