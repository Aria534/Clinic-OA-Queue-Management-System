<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login | QueueMed</title>
  <style>
    :root {
      --bg:        #f7f4ef;
      --ink:       #111010;
      --muted:     #8a8680;
      --accent:    #c84b2f;
      --accent2:   #e8a87c;
      --panel:     #ffffff;
      --border:    #e2ddd6;
      --success:   #2d6a4f;
      --error:     #c84b2f;
    }

    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      background: var(--bg);
      font-family: Arial, sans-serif;
      min-height: 100vh;
      display: grid;
      grid-template-columns: 1fr 1fr;
      overflow: hidden;
    }

    /* ── LEFT PANEL ── */
    .left {
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      padding: 3rem;
      position: relative;
      overflow: hidden;
      background: #1a0a1e;
    }

    /* Girly gradient background: deep plum + lilac + dusty rose */
    .left-bg {
      position: absolute;
      inset: 0;
      background:
        radial-gradient(ellipse 90% 70% at 10% 100%, rgba(186, 85, 140, 0.45) 0%, transparent 60%),
        radial-gradient(ellipse 70% 50% at 90% 10%, rgba(147, 99, 186, 0.35) 0%, transparent 55%),
        radial-gradient(ellipse 60% 40% at 50% 50%, rgba(218, 143, 175, 0.18) 0%, transparent 60%);
      pointer-events: none;
    }

    /* Soft shimmer grid */
    .left-grid {
      position: absolute;
      inset: 0;
      background-image:
        linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
      background-size: 48px 48px;
      pointer-events: none;
    }

    /* Floating orbs */
    .orb {
      position: absolute;
      border-radius: 50%;
      pointer-events: none;
      filter: blur(60px);
    }
    .orb-1 {
      width: 260px; height: 260px;
      background: rgba(200, 100, 160, 0.22);
      bottom: 80px; left: -60px;
    }
    .orb-2 {
      width: 180px; height: 180px;
      background: rgba(140, 80, 200, 0.2);
      top: 60px; right: -40px;
    }
    .orb-3 {
      width: 120px; height: 120px;
      background: rgba(240, 180, 210, 0.15);
      top: 42%; left: 55%;
    }

    .left-brand {
      position: relative;
      z-index: 1;
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .brand-mark {
      width: 42px; height: 42px;
      background: linear-gradient(135deg, #c86aaa, #9b5ec8);
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.2rem;
      flex-shrink: 0;
    }

    .brand-text {
      font-family: Arial, sans-serif;
      font-weight: 800;
      font-size: 1.4rem;
      color: #fff;
      letter-spacing: -0.5px;
    }

    .brand-sub {
      font-size: 0.7rem;
      color: rgba(255,255,255,0.35);
      letter-spacing: 0.15em;
      text-transform: uppercase;
      margin-top: 1px;
    }

    .left-hero {
      position: relative;
      z-index: 1;
    }

    .left-tag {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: rgba(200, 100, 160, 0.18);
      border: 1px solid rgba(200, 100, 160, 0.35);
      color: #f0b8d8;
      font-size: 0.72rem;
      font-weight: 700;
      letter-spacing: 0.12em;
      text-transform: uppercase;
      padding: 0.35rem 0.85rem;
      border-radius: 50px;
      margin-bottom: 1.5rem;
    }

    .left-tag .dot {
      width: 6px; height: 6px;
      background: #e87fbd;
      border-radius: 50%;
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0%, 100% { opacity: 1; transform: scale(1); }
      50%       { opacity: 0.4; transform: scale(0.8); }
    }

    .left-headline {
      font-family: Arial, sans-serif;
      font-weight: 900;
      font-size: clamp(2.8rem, 4.5vw, 4rem);
      line-height: 1.05;
      color: #fff;
      letter-spacing: -2px;
      margin-bottom: 1.25rem;
    }

    .left-headline em {
      font-style: normal;
      background: linear-gradient(120deg, #f0b8d8, #c89ce8);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .left-desc {
      font-size: 0.92rem;
      color: rgba(255,255,255,0.45);
      line-height: 1.7;
      max-width: 340px;
    }

    /* ── RIGHT PANEL ── */
    .right {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 3rem 4rem;
      position: relative;
    }

    .right::before {
      content: '';
      position: absolute;
      inset: 0;
      background-image: url("data:image/svg+xml,%3Csvg width='20' height='20' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle cx='1' cy='1' r='1' fill='%23111010' fill-opacity='0.04'/%3E%3C/svg%3E");
      pointer-events: none;
    }

    .form-wrap {
      width: 100%;
      max-width: 400px;
      position: relative;
      z-index: 1;
      animation: slideUp 0.6s cubic-bezier(0.22, 1, 0.36, 1) both;
    }

    @keyframes slideUp {
      from { opacity: 0; transform: translateY(24px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .form-heading {
      font-family: Arial, sans-serif;
      font-weight: 700;
      font-size: 1.9rem;
      color: var(--ink);
      letter-spacing: -0.5px;
      margin-bottom: 0.4rem;
    }

    .form-sub {
      font-size: 0.88rem;
      color: var(--muted);
      margin-bottom: 2.25rem;
    }

    /* alerts */
    .alert {
      border-radius: 10px;
      padding: 0.75rem 1rem;
      font-size: 0.83rem;
      margin-bottom: 1.25rem;
      display: flex;
      align-items: flex-start;
      gap: 8px;
      line-height: 1.5;
    }
    .alert-success { background: #edf7f1; color: var(--success); border: 1px solid #b7dfc9; }
    .alert-danger  { background: #fdf2f0; color: var(--error);   border: 1px solid #f3c4bb; }
    .alert ul { padding-left: 1rem; margin: 0; }

    /* form fields */
    .field { margin-bottom: 1.25rem; }

    .field label {
      display: block;
      font-size: 0.8rem;
      font-weight: 700;
      color: var(--ink);
      margin-bottom: 0.45rem;
      letter-spacing: 0.02em;
      font-family: Arial, sans-serif;
    }

    .input-wrap {
      position: relative;
      display: flex;
      align-items: center;
    }

    .input-icon {
      position: absolute;
      left: 14px;
      color: var(--muted);
      font-size: 0.95rem;
      pointer-events: none;
      line-height: 1;
    }

    /* email field has icon, password field does NOT */
    .input-wrap input.with-icon {
      padding: 0.8rem 1rem 0.8rem 2.65rem;
    }

    .input-wrap input.no-icon {
      padding: 0.8rem 2.65rem 0.8rem 1rem;
    }

    .input-wrap input {
      width: 100%;
      font-family: Arial, sans-serif;
      font-size: 0.92rem;
      color: var(--ink);
      background: var(--panel);
      border: 1.5px solid var(--border);
      border-radius: 10px;
      outline: none;
      transition: border-color 0.2s, box-shadow 0.2s;
    }

    .input-wrap input:focus {
      border-color: #c86aaa;
      box-shadow: 0 0 0 3px rgba(200, 106, 170, 0.12);
    }

    .input-wrap input::placeholder { color: #c4bfb9; }

    .pw-toggle {
      position: absolute;
      right: 12px;
      background: none;
      border: none;
      cursor: pointer;
      color: var(--muted);
      padding: 4px;
      line-height: 1;
      font-size: 1rem;
      transition: color 0.2s;
    }
    .pw-toggle:hover { color: var(--ink); }

    /* row */
    .field-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 1.75rem;
    }

    .check-label {
      display: flex;
      align-items: center;
      gap: 8px;
      cursor: pointer;
      font-size: 0.83rem;
      color: var(--muted);
      user-select: none;
      font-family: Arial, sans-serif;
    }

    .check-label input[type="checkbox"] {
      width: 15px; height: 15px;
      accent-color: #c86aaa;
      cursor: pointer;
    }

    .forgot {
      font-size: 0.83rem;
      color: #c84b2f;
      text-decoration: none;
      font-weight: 700;
      font-family: Arial, sans-serif;
    }
    .forgot:hover { text-decoration: underline; }

    /* submit */
    .btn-login {
      width: 100%;
      padding: 0.9rem;
      background: var(--ink);
      color: #fff;
      font-family: Arial, sans-serif;
      font-weight: 700;
      font-size: 0.95rem;
      letter-spacing: 0.05em;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      margin-bottom: 1rem;
    }
    .btn-login:hover {
      background: #2a2a2a;
      transform: translateY(-1px);
      box-shadow: 0 8px 24px rgba(17,16,16,0.2);
    }
    .btn-login:active { transform: translateY(0); }

    /* divider */
    .divider {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 1rem;
      font-size: 0.75rem;
      color: var(--muted);
      font-family: Arial, sans-serif;
    }
    .divider::before, .divider::after {
      content: '';
      flex: 1;
      height: 1px;
      background: var(--border);
    }

    /* patient btn */
    .btn-patient {
      width: 100%;
      padding: 0.85rem;
      background: transparent;
      color: var(--ink);
      font-family: Arial, sans-serif;
      font-weight: 500;
      font-size: 0.88rem;
      border: 1.5px solid var(--border);
      border-radius: 10px;
      cursor: pointer;
      transition: border-color 0.2s, background 0.2s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      text-decoration: none;
    }
    .btn-patient:hover {
      border-color: var(--ink);
      background: rgba(17,16,16,0.03);
      color: var(--ink);
    }

    /* copyright */
    .form-footer {
      margin-top: 2rem;
      font-size: 0.72rem;
      color: var(--muted);
      text-align: center;
      font-family: Arial, sans-serif;
    }

    /* responsive */
    @media (max-width: 768px) {
      body { grid-template-columns: 1fr; }
      .left { display: none; }
      .right { padding: 2rem 1.5rem; }
    }
  </style>
</head>
<body>

<!-- ── LEFT ── -->
<div class="left">
  <div class="left-bg"></div>
  <div class="left-grid"></div>
  <div class="orb orb-1"></div>
  <div class="orb orb-2"></div>
  <div class="orb orb-3"></div>

  <div class="left-brand">
    <div class="brand-mark">🏥</div>
    <div>
      <div class="brand-text">QueueMed</div>
      <div class="brand-sub">Queue Management</div>
    </div>
  </div>

  <div class="left-hero">
    <div class="left-tag">
      <span class="dot"></span>
      System Online
    </div>
    <h1 class="left-headline">
      <em>QueueMed</em>
    </h1>
    <p class="left-desc">
      Manage patient flow in real-time. Reduce wait times, improve experience, and keep your clinic running smoothly.
    </p>
  </div>

  <!-- stats row removed -->
  <div></div>
</div>

<!-- ── RIGHT ── -->
<div class="right">
  <div class="form-wrap">

    <h2 class="form-heading">Welcome back</h2>
    <p class="form-sub">Sign in to your admin account</p>

    <!-- ALERTS -->
    <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
      ✓ <?= session()->getFlashdata('success') ?>
    </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
      ✕ <?= session()->getFlashdata('error') ?>
    </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
      <ul>
        <?php foreach (session()->getFlashdata('errors') as $err): ?>
        <li><?= esc($err) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <?php endif; ?>

    <!-- FORM -->
    <form method="POST" action="<?= base_url('login') ?>">
      <?= csrf_field() ?>

      <div class="field">
        <label>Email Address</label>
        <div class="input-wrap">
          <span class="input-icon">✉</span>
          <input type="email" name="email" class="with-icon" placeholder="you@clinic.com"
            value="<?= old('email') ?>" required autofocus/>
        </div>
      </div>

      <div class="field">
        <label>Password</label>
        <div class="input-wrap">
          <!-- No lock icon, clean input -->
          <input type="password" name="password" id="password" class="no-icon"
            placeholder="Enter your password" required/>
          <button type="button" class="pw-toggle" id="togglePw" title="Show/hide password">
            <span id="eyeIcon">👁</span>
          </button>
        </div>
      </div>

      <div class="field-row">
        <label class="check-label">
          <input type="checkbox" id="remember"/>
          Remember me
        </label>
        <a href="#" class="forgot">Forgot password?</a>
      </div>

      <button type="submit" class="btn-login">
        Login
      </button>
    </form>

    <div class="divider">or</div>

    <a href="<?= base_url('/') ?>" class="btn-patient">
      Get Queue Number (Patient)
    </a>

    <div class="form-footer">
      &copy; <?= date('Y') ?> QueueMed. All rights reserved.
    </div>

  </div>
</div>

<script>
  const togglePw = document.getElementById('togglePw');
  const pwInput  = document.getElementById('password');
  const eyeIcon  = document.getElementById('eyeIcon');

  togglePw?.addEventListener('click', () => {
    const hidden = pwInput.type === 'password';
    pwInput.type = hidden ? 'text' : 'password';
    eyeIcon.textContent = hidden ? '🙈' : '👁';
  });
</script>
</body>
</html>