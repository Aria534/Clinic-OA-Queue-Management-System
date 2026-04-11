<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login | QueueMed</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"/>
  <style>
    :root {
      --sp: #7c5cbf;
      --sp-dark: #5e3fa3;
      --sp-mid: #9b7dd4;
      --sp-light: #f3effe;
      --sp-border: #d8c8f0;
    }
    * { font-family: 'Segoe UI', Arial, sans-serif !important; }
    body { background: linear-gradient(135deg, #f3effe 0%, #e4d5f7 100%); min-height: 100vh;
      display: flex; align-items: center; justify-content: center; }
    .card { border: none; border-radius: 16px; box-shadow: 0 12px 40px rgba(124,92,191,0.18); width: 100%; max-width: 440px; }
    .form-control, .input-group-text, .btn-outline-secondary {
      border-color: var(--sp-border);
    }
    .input-group-text { background: var(--sp-light); color: var(--sp); }
    .form-control:focus { border-color: var(--sp); box-shadow: 0 0 0 3px rgba(124,92,191,0.15); }
    .form-check-input:checked { background-color: var(--sp); border-color: var(--sp); }
    .btn-outline-secondary { color: var(--sp); }
    .btn-outline-secondary:hover { background: var(--sp-light); border-color: var(--sp); color: var(--sp-dark); }
    .btn-purple {
      background: linear-gradient(135deg, var(--sp) 0%, var(--sp-mid) 100%);
      border: none; color: #fff; font-weight: 600; padding: 11px; border-radius: 10px;
      transition: all 0.2s;
    }
    .btn-purple:hover {
      background: linear-gradient(135deg, var(--sp-dark) 0%, var(--sp) 100%);
      color: #fff; transform: translateY(-1px); box-shadow: 0 6px 18px rgba(124,92,191,0.35);
    }
    .forgot-link { color: var(--sp); font-size: 0.83rem; text-decoration: none; }
    .forgot-link:hover { color: var(--sp-dark); text-decoration: underline; }
    .card-footer { background: var(--sp-light); border-top: 1px solid var(--sp-border); border-radius: 0 0 16px 16px !important; }
  </style>
</head>
<body>

<div class="card shadow">
  <div class="card-body p-4">

    <!-- BRAND -->
    <div class="text-center mb-4">
      <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:56px;height:56px;font-size:1.5rem;">
        <i class="bi bi-clipboard2-pulse"></i>
      </div>
      <h4 class="fw-bold mb-0">QueueMed</h4>
      <small class="text-muted">Appointment & Queue Management System</small>
    </div>

    <!-- SUCCESS MESSAGE -->
    <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success d-flex align-items-center gap-2 py-2">
      <i class="bi bi-check-circle-fill"></i>
      <?= session()->getFlashdata('success') ?>
    </div>
    <?php endif; ?>

    <!-- ERROR MESSAGE -->
    <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger d-flex align-items-center gap-2 py-2">
      <i class="bi bi-exclamation-circle-fill"></i>
      <?= session()->getFlashdata('error') ?>
    </div>
    <?php endif; ?>

    <!-- VALIDATION ERRORS -->
    <?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger py-2">
      <ul class="mb-0 small">
        <?php foreach (session()->getFlashdata('errors') as $err): ?>
        <li><?= esc($err) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <?php endif; ?>

    <!-- LOGIN FORM -->
    <form method="POST" action="<?= base_url('login') ?>">
      <?= csrf_field() ?>
      <div class="mb-3">
        <label class="form-label fw-semibold small">Email Address</label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-envelope"></i></span>
          <input type="email" name="email" class="form-control"
            placeholder="Enter your email"
            value="<?= old('email') ?>" required autofocus/>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold small">Password</label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-lock"></i></span>
          <input type="password" name="password" id="password" class="form-control"
            placeholder="Enter your password" required/>
          <button type="button" class="btn btn-outline-secondary" id="togglePw">
            <i class="bi bi-eye" id="eyeIcon"></i>
          </button>
        </div>
      </div>

      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="form-check mb-0">
          <input class="form-check-input" type="checkbox" id="remember"/>
          <label class="form-check-label small" for="remember">Remember me</label>
        </div>
        <a href="#" class="forgot-link">Forgot password?</a>
      </div>

      <button type="submit" class="btn btn-purple w-100">
        <i class="bi bi-box-arrow-in-right me-1"></i> Login
      </button>
    </form>

    <!-- GET QUEUE NUMBER BUTTON -->
    <div class="text-center mt-3">
      <div class="text-muted small mb-2">Are you a patient?</div>
      <a href="<?= base_url('/') ?>" class="btn btn-outline-success w-100">
        <i class="bi bi-ticket-perforated me-1"></i> Get Queue Number
      </a>
    </div>

  </div>
  <div class="card-footer text-center text-muted small py-2">
    &copy; <?= date('Y') ?> QueueMed. All rights reserved.
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const togglePw = document.getElementById('togglePw');
  const pwInput  = document.getElementById('password');
  const eyeIcon  = document.getElementById('eyeIcon');
  togglePw?.addEventListener('click', () => {
    const hidden = pwInput.type === 'password';
    pwInput.type = hidden ? 'text' : 'password';
    eyeIcon.className = hidden ? 'bi bi-eye-slash' : 'bi bi-eye';
  });
</script>
</body>
</html>