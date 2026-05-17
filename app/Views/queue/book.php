<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Get Queue Number | QueueMed</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"/>
  <style>
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

    html, body { height: 100%; }

    body {
      font-family: 'Inter', sans-serif;
      min-height: 100vh;
      background: radial-gradient(ellipse at 30% 20%, #5a2070 0%, #2e0f45 50%, #1a0a2e 100%);
      display: flex;
      flex-direction: column;
      color: #e8e0f5;
    }

    /* orbs */
    .orb {
      position: fixed; border-radius: 50%;
      pointer-events: none; filter: blur(80px); z-index: 0;
    }
    .orb-1 { width: 350px; height: 350px; background: rgba(180,80,200,0.2);  top: -80px;  left: -80px; }
    .orb-2 { width: 250px; height: 250px; background: rgba(120,60,200,0.18); bottom: 60px; right: -60px; }

    /* ── NAV ── */
    nav {
      position: relative; z-index: 10;
      background: rgba(255,255,255,0.05);
      border-bottom: 1px solid rgba(255,255,255,0.08);
      backdrop-filter: blur(12px);
      padding: 0 2.5rem;
      height: 58px;
      display: flex; align-items: center; justify-content: space-between;
      flex-shrink: 0;
    }
    .nav-brand {
      display: flex; align-items: center; gap: 10px;
      text-decoration: none;
    }
    .nav-brand .brand-mark {
      width: 34px; height: 34px;
      background: linear-gradient(135deg, #c86aaa, #9b5ec8);
      border-radius: 9px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1rem;
      box-shadow: 0 4px 14px rgba(180,80,200,0.35);
    }
    .nav-brand .brand-name {
      font-size: 1rem; font-weight: 800; color: #fff; letter-spacing: -.2px;
    }
    .nav-login {
      display: flex; align-items: center; gap: 6px;
      font-size: 0.78rem; font-weight: 500;
      color: rgba(255,255,255,0.55);
      text-decoration: none;
      background: rgba(255,255,255,0.07);
      border: 1px solid rgba(255,255,255,0.12);
      border-radius: 9px; padding: 7px 14px;
      transition: all 0.2s;
    }
    .nav-login:hover {
      color: #fff;
      background: rgba(200,106,200,0.15);
      border-color: rgba(200,106,200,0.35);
    }

    /* ── MAIN ── */
    main {
      flex: 1; position: relative; z-index: 1;
      display: flex; align-items: center; justify-content: center;
      padding: 3rem 1.5rem;
    }

    .page-wrap {
      width: 100%; max-width: 560px;
      animation: slideUp 0.45s cubic-bezier(0.22, 1, 0.36, 1) both;
    }
    @keyframes slideUp {
      from { opacity: 0; transform: translateY(24px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .page-title {
      font-size: 2.4rem; font-weight: 800;
      color: #fff; text-align: center; letter-spacing: -.5px;
      margin-bottom: 0.4rem;
    }
    .page-sub {
      font-size: 1rem; color: rgba(255,255,255,0.45);
      text-align: center; margin-bottom: 2rem;
    }

    /* alerts */
    .alert {
      border-radius: 12px; padding: 0.75rem 1rem;
      font-size: 0.82rem; margin-bottom: 1.25rem;
      background: rgba(220,80,80,0.12);
      border: 1px solid rgba(220,80,80,0.25);
      color: #f09090; line-height: 1.5;
    }

    /* card */
    .card {
      background: rgba(255,255,255,0.06);
      border: 1px solid rgba(255,255,255,0.12);
      border-radius: 20px; padding: 2.5rem;
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      box-shadow: 0 24px 64px rgba(0,0,0,0.3), inset 0 1px 0 rgba(255,255,255,0.08);
    }

    /* fields */
    .field { margin-bottom: 1.5rem; }
    .field label {
      display: block; font-size: 0.82rem; font-weight: 600;
      color: rgba(255,255,255,0.7); margin-bottom: 8px;
      letter-spacing: 0.06em; text-transform: uppercase;
    }
    .field input,
    .field select {
      width: 100%; padding: 14px 16px;
      font-family: 'Inter', sans-serif; font-size: 1rem;
      color: #fff;
      background: rgba(255,255,255,0.08);
      border: 1.5px solid rgba(255,255,255,0.12);
      border-radius: 12px; outline: none;
      appearance: none; -webkit-appearance: none;
      transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    }
    .field input::placeholder { color: rgba(255,255,255,0.2); }
    .field input:focus,
    .field select:focus {
      border-color: rgba(200,106,200,0.7);
      background: rgba(255,255,255,0.11);
      box-shadow: 0 0 0 3px rgba(200,106,200,0.15);
    }
    /* style select options (limited browser support) */
    .field select option { background: #2e0f45; color: #fff; }

    .select-wrap { position: relative; }
    .select-wrap::after {
      content: '\F282';
      font-family: 'bootstrap-icons';
      position: absolute; right: 13px; top: 50%;
      transform: translateY(-50%);
      color: rgba(255,255,255,0.3); pointer-events: none; font-size: 0.85rem;
    }

    .card-divider {
      height: 1px; background: rgba(255,255,255,0.08); margin: 1.25rem 0;
    }

    /* submit */
    .btn-submit {
      width: 100%; padding: 15px;
      background: linear-gradient(135deg, #9b5ec8 0%, #c86aaa 100%);
      color: #fff; font-family: 'Inter', sans-serif;
      font-weight: 700; font-size: 1rem; letter-spacing: 0.02em;
      border: none; border-radius: 12px; cursor: pointer;
      display: flex; align-items: center; justify-content: center; gap: 8px;
      box-shadow: 0 8px 24px rgba(155,94,200,0.4);
      transition: all 0.2s;
    }
    .btn-submit:hover {
      background: linear-gradient(135deg, #8a4eb8 0%, #b85a9a 100%);
      box-shadow: 0 12px 32px rgba(155,94,200,0.55);
      transform: translateY(-2px);
    }
    .btn-submit:active { transform: translateY(0); }

    /* footer */
    footer {
      position: relative; z-index: 1;
      text-align: center; font-size: 0.7rem;
      color: rgba(255,255,255,0.18); padding: 1.25rem; flex-shrink: 0;
    }
  </style>
</head>
<body>

<div class="orb orb-1"></div>
<div class="orb orb-2"></div>

<!-- NAV -->
<nav>
  <a href="<?= base_url('/') ?>" class="nav-brand">
    <div class="brand-mark"><i class="bi bi-clipboard2-pulse text-white"></i></div>
    <span class="brand-name">QueueMed</span>
  </a>
  <a href="<?= base_url('login') ?>" class="nav-login">
    <i class="bi bi-box-arrow-in-right"></i> Admin Login
  </a>
</nav>

<!-- MAIN -->
<main>
  <div class="page-wrap">

    <h1 class="page-title">Get Your Queue Number</h1>
    <p class="page-sub">Walk-in only &nbsp;·&nbsp; No login required</p>

    <?php if (session()->getFlashdata('error')): ?>
    <div class="alert"><i class="bi bi-exclamation-circle me-1"></i><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors')): ?>
    <div class="alert">
      <?php foreach (session()->getFlashdata('errors') as $e): ?>
        <div><i class="bi bi-exclamation-circle me-1"></i><?= esc($e) ?></div>
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
          <i class="bi bi-ticket-perforated"></i> Get Queue Number
        </button>

      </form>
    </div>

  </div>
</main>

<footer>&copy; <?= date('Y') ?> QueueMed. All rights reserved.</footer>

</body>
</html>
