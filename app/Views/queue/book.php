<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Get Queue Number | QueueMed</title>
  <style>
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: Arial, sans-serif;
      background: #0b0f1a;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      color: #d4d8e8;
    }

    /* ── NAV ── */
    nav {
      background: #060910;
      border-bottom: 1px solid #1e2538;
      padding: 0 2.5rem;
      height: 54px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-shrink: 0;
    }

    .nav-brand {
      font-size: 1.05rem;
      font-weight: 700;
      color: #e8eaf0;
      letter-spacing: -0.2px;
      text-decoration: none;
    }

    .nav-back {
      display: flex;
      align-items: center;
      gap: 6px;
      font-size: 0.78rem;
      color: #8b92a8;
      text-decoration: none;
      background: #131929;
      border: 1px solid #252d42;
      border-radius: 8px;
      padding: 7px 14px;
      transition: color 0.2s, border-color 0.2s, background 0.2s;
    }
    .nav-back:hover {
      color: #c8cdd9;
      border-color: #3a4460;
      background: #1a2238;
    }

    /* ── MAIN ── */
    main {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 3rem 1.5rem;
    }

    .page-wrap {
      width: 100%;
      max-width: 440px;
      animation: slideUp 0.45s cubic-bezier(0.22, 1, 0.36, 1) both;
    }

    @keyframes slideUp {
      from { opacity: 0; transform: translateY(24px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .page-title {
      font-size: 1.7rem;
      font-weight: 800;
      color: #e8eaf0;
      text-align: center;
      letter-spacing: -0.5px;
      margin-bottom: 0.35rem;
    }

    .page-sub {
      font-size: 0.84rem;
      color: #5c6480;
      text-align: center;
      margin-bottom: 1.75rem;
    }

    /* alerts */
    .alert {
      border-radius: 10px;
      padding: 0.75rem 1rem;
      font-size: 0.83rem;
      margin-bottom: 1.25rem;
      border: 1px solid #4a1f1f;
      background: #1e1010;
      color: #f09595;
      line-height: 1.5;
    }

    /* card */
    .card {
      background: #111827;
      border: 1px solid #1e2a3d;
      border-radius: 14px;
      padding: 1.75rem;
    }

    /* fields */
    .field { margin-bottom: 1.25rem; }

    .field label {
      display: block;
      font-size: 0.72rem;
      font-weight: 700;
      color: #6b7494;
      margin-bottom: 7px;
      letter-spacing: 0.06em;
      text-transform: uppercase;
    }

    .field input,
    .field select {
      width: 100%;
      padding: 11px 14px;
      font-family: Arial, sans-serif;
      font-size: 0.9rem;
      color: #d4d8e8;
      background: #0d1320;
      border: 1px solid #1e2a3d;
      border-radius: 9px;
      outline: none;
      appearance: none;
      -webkit-appearance: none;
      transition: border-color 0.2s, box-shadow 0.2s;
    }

    .field input::placeholder { color: #303752; }

    .field input:focus,
    .field select:focus {
      border-color: #3b6fd4;
      box-shadow: 0 0 0 3px rgba(59,111,212,0.15);
    }

    .select-wrap { position: relative; }
    .select-wrap::after {
      content: '▾';
      position: absolute;
      right: 13px;
      top: 50%;
      transform: translateY(-50%);
      color: #4a5270;
      pointer-events: none;
      font-size: 0.82rem;
    }

    .card-divider {
      height: 1px;
      background: #1a2235;
      margin: 1.25rem 0;
    }

    /* submit */
    .btn-submit {
      width: 100%;
      padding: 13px;
      background: #1a4dd6;
      color: #fff;
      font-family: Arial, sans-serif;
      font-weight: 700;
      font-size: 0.92rem;
      letter-spacing: 0.02em;
      border: none;
      border-radius: 9px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: background 0.2s, transform 0.15s;
    }
    .btn-submit:hover {
      background: #1642bb;
      transform: translateY(-1px);
    }
    .btn-submit:active { transform: translateY(0); }

    /* footer */
    footer {
      text-align: center;
      font-size: 0.72rem;
      color: #2e3450;
      padding: 1.25rem;
      flex-shrink: 0;
    }
  </style>
</head>
<body>

<!-- NAV -->
<nav>
  <a href="<?= base_url('/') ?>" class="nav-brand">QueueMed</a>
  <a href="<?= base_url('login') ?>" class="nav-back">
     Admin Login
  </a>
</nav>

<!-- MAIN -->
<main>
  <div class="page-wrap">

    <h1 class="page-title">Get Your Queue Number</h1>
    <p class="page-sub">Walk-in only &nbsp;·&nbsp; No login required</p>

    <!-- ALERTS -->
    <?php if (session()->getFlashdata('error')): ?>
    <div class="alert">✕ <?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors')): ?>
    <div class="alert">
      <?php foreach (session()->getFlashdata('errors') as $e): ?>
        <div>✕ <?= esc($e) ?></div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="card">
      <form method="POST" action="<?= base_url('book') ?>">
        <?= csrf_field() ?>

        <div class="field">
          <label>Full Name</label>
          <input type="text" name="patient_name"
                 placeholder="e.g. Juan Dela Cruz"
                 value="<?= old('patient_name') ?>" required/>
        </div>

        <div class="field">
          <label>Service</label>
          <div class="select-wrap">
            <select name="service_id" required>
              <option value="">Select a service...</option>
              <?php foreach ($services as $svc): ?>
              <option value="<?= $svc['id'] ?>" <?= old('service_id') == $svc['id'] ? 'selected' : '' ?>>
                <?= esc($svc['name']) ?>
              </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="card-divider"></div>

        <button type="submit" class="btn-submit">
          🎫 &nbsp;Get Queue Number
        </button>

      </form>
    </div>

  </div>
</main>

<footer>
  &copy; <?= date('Y') ?> QueueMed. All rights reserved.
</footer>

</body>
</html>