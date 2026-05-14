<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Get Queue Number | QueueMed</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"/>
  <style>
    body { background: #f0f4f8; min-height: 100vh; }
    .card { border-radius: 1.25rem; }
    .form-control:focus, .form-select:focus {
      border-color: #2563eb;
      box-shadow: 0 0 0 3px rgba(37,99,235,0.12);
    }
  </style>
</head>
<body>

<nav class="navbar bg-white border-bottom shadow-sm">
  <div class="container">
    <span class="navbar-brand fw-bold text-primary">
      <i class="bi bi-clipboard2-pulse"></i> QueueMed
    </span>
  </div>
</nav>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-5">

      <div class="text-center mb-4">
        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:64px;height:64px;">
          <i class="bi bi-ticket-perforated-fill text-primary fs-3"></i>
        </div>
        <h4 class="fw-bold mb-1">Get Your Queue Number</h4>
        <p class="text-muted small">Walk-in only. No login required.</p>
      </div>

      <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
      <?php endif; ?>

      <?php if (session()->getFlashdata('errors')): ?>
      <div class="alert alert-danger">
        <?php foreach (session()->getFlashdata('errors') as $e): ?>
          <div><?= esc($e) ?></div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
          <form method="POST" action="<?= base_url('book') ?>">
            <?= csrf_field() ?>

            <div class="mb-3">
              <label class="form-label fw-semibold small">Full Name</label>
              <input type="text" name="patient_name" class="form-control"
                     placeholder="e.g. Juan Dela Cruz"
                     value="<?= old('patient_name') ?>" required/>
            </div>

            <div class="mb-4">
              <label class="form-label fw-semibold small">Service</label>
              <select name="service_id" class="form-select" required>
                <option value="">Select a service...</option>
                <?php foreach ($services as $svc): ?>
                <option value="<?= $svc['id'] ?>" <?= old('service_id') == $svc['id'] ? 'selected' : '' ?>>
                  <?= esc($svc['name']) ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>

            <button type="submit" class="btn btn-primary w-100 fw-semibold py-2">
              <i class="bi bi-ticket-perforated me-1"></i> Get Queue Number
            </button>
          </form>
        </div>
      </div>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>