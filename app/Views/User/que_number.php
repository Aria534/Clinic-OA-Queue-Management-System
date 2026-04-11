<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Book a Queue | QueueMed</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"/>
  <style>
    :root {
      --eg: #1a7a3c;
      --eg-dark: #145f2e;
      --eg-mid: #2ea05a;
      --eg-light: #e8f5ee;
      --eg-border: #c8e6d4;
    }
    body { background: linear-gradient(135deg, #e8f5ee 0%, #d0ead9 100%); min-height: 100vh; }
    .navbar-brand { color: var(--eg) !important; }
    .card { border-radius: 1.5rem; }
    .form-control, .form-select {
      border-color: var(--eg-border);
    }
    .form-control:focus, .form-select:focus {
      border-color: var(--eg);
      box-shadow: 0 0 0 3px rgba(26,122,60,0.12);
    }
    .btn-green {
      background: linear-gradient(135deg, var(--eg) 0%, var(--eg-mid) 100%);
      border: none;
      color: #fff;
      font-weight: 600;
      padding: 12px;
      border-radius: 10px;
      transition: all 0.2s;
    }
    .btn-green:hover {
      background: linear-gradient(135deg, var(--eg-dark) 0%, var(--eg) 100%);
      color: #fff;
      transform: translateY(-1px);
      box-shadow: 0 6px 20px rgba(26,122,60,0.3);
    }
  </style>
</head>
<body>

<nav class="navbar navbar-light bg-white border-bottom shadow-sm">
  <div class="container">
    <span class="navbar-brand fw-bold">
      <i class="bi bi-clipboard2-pulse"></i> QueueMed
    </span>
  </div>
</nav>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-5">

      <div class="card border-0 shadow" style="border-radius:1.5rem;">
        <div class="card-body p-4">

          <h4 class="fw-bold mb-1">Get Your Queue Number</h4>
          <p class="text-muted small mb-4">No login required. Fill in your details below.</p>

          <?php if (session()->getFlashdata('error')): ?>
          <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
          <?php endif; ?>

          <form method="POST" action="<?= base_url('book') ?>">
            <?= csrf_field() ?>

            <div class="mb-3">
              <label class="form-label fw-semibold small">Full Name</label>
              <input type="text" name="patient_name" class="form-control"
                     value="<?= old('patient_name') ?>"/>
            </div>

            <div class="mb-4">
              <label class="form-label fw-semibold small">Service</label>
              <select name="service_id" class="form-select" required>
                <option value="">Select a service...</option>
                <?php 
                $allowed = ['General Consultation', 'Dental Check-up', 'Blood Test'];
                foreach ($services as $svc): 
                  if (in_array($svc['name'], $allowed)):
                ?>
                <option value="<?= $svc['id'] ?>" <?= old('service_id')==$svc['id']?'selected':'' ?>>
                  <?= esc($svc['name']) ?>
                </option>
                <?php endif; endforeach; ?>
              </select>
            </div>

            <div class="mb-4">
              <label class="form-label fw-semibold small">Notes <span class="text-muted fw-normal">(optional)</span></label>
              <textarea name="notes" class="form-control" rows="2"
                        placeholder="Any concerns or additional info..."><?= old('notes') ?></textarea>
            </div>

            <button type="submit" class="btn btn-green w-100">
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