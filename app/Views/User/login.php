<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login | QueueMed</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"/>
  <style>
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

    html, body { height: 100%; overflow: hidden; }

    body {
      font-family: 'Inter', sans-serif;
      height: 100vh;
      display: grid;
      grid-template-columns: 1fr 1fr;
    }

    /* ── LEFT PANEL ── */
    .left {
      position: relative;
      overflow: hidden;
      background: #1a0a2e;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      padding: 2.5rem;
      height: 100vh;
    }

    /* layered purple gradient */
    .left::before {
      content: '';
      position: absolute; inset: 0;
      background:
        radial-gradient(ellipse 80% 60% at 20% 110%, rgba(180,80,200,0.5) 0%, transparent 55%),
        radial-gradient(ellipse 60% 50% at 85% 5%,  rgba(120,60,180,0.4) 0%, transparent 55%),
        radial-gradient(ellipse 50% 40% at 50% 50%, rgba(200,120,220,0.15) 0%, transparent 60%);
      pointer-events: none;
    }

    /* subtle grid */
    .left::after {
      content: '';
      position: absolute; inset: 0;
      background-image:
        linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
      background-size: 52px 52px;
      pointer-events: none;
    }

    /* floating orbs */
    .orb {
      position: absolute; border-radius: 50%;
      pointer-events: none; filter: blur(70px);
    }
    .orb-1 { width: 300px; height: 300px; background: rgba(180,80,200,0.25); bottom: 60px; left: -80px; }
    .orb-2 { width: 200px; height: 200px; background: rgba(120,60,200,0.22); top: 40px; right: -50px; }
    .orb-3 { width: 140px; height: 140px; background: rgba(230,160,240,0.12); top: 45%; left: 50%; }

    .left-brand {
      position: relative; z-index: 2;
      display: flex; align-items: center; gap: 12px;
    }
    .brand-mark {
      width: 44px; height: 44px;
      background: linear-gradient(135deg, #c86aaa, #9b5ec8);
      border-radius: 12px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.25rem;
      box-shadow: 0 4px 20px rgba(180,80,200,0.4);
    }
    .brand-name { font-size: 1.1rem; font-weight: 800; color: #fff; letter-spacing: -.3px; }
    .brand-sub  { font-size: .65rem; color: rgba(255,255,255,0.35); letter-spacing: .15em; text-transform: uppercase; }

    .left-hero { position: relative; z-index: 2; }

    .left-tag {
      display: inline-flex; align-items: center; gap: 8px;
      background: rgba(200,100,220,0.15);
      border: 1px solid rgba(200,100,220,0.3);
      color: #e8b0f0;
      font-size: .68rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase;
      padding: .35rem .9rem; border-radius: 50px;
      margin-bottom: 1.5rem;
    }
    .left-tag .dot {
      width: 6px; height: 6px;
      background: #e87fbd; border-radius: 50%;
      animation: pulse 2s infinite;
    }
    @keyframes pulse {
      0%,100% { opacity:1; transform:scale(1); }
      50%      { opacity:.4; transform:scale(.8); }
    }

    .left-headline {
      font-size: clamp(3rem, 5vw, 4.5rem);
      font-weight: 900; line-height: 1.02;
      color: #fff; letter-spacing: -2.5px;
      margin-bottom: 1.2rem;
    }
    .left-headline em {
      font-style: normal;
      background: linear-gradient(120deg, #f0b8e8, #c89ce8, #a87de0);
      -webkit-background-clip: text; -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    .left-desc {
      font-size: .9rem; color: rgba(255,255,255,0.4);
      line-height: 1.75; max-width: 320px;
    }

    /* stats pills */
    .left-stats {
      position: relative; z-index: 2;
      display: flex; gap: 12px; flex-wrap: wrap;
    }
    .stat-pill {
      background: rgba(255,255,255,0.07);
      border: 1px solid rgba(255,255,255,0.12);
      border-radius: 12px; padding: 10px 16px;
      backdrop-filter: blur(8px);
    }
    .stat-pill .num  { font-size: 1.2rem; font-weight: 800; color: #fff; line-height: 1; }
    .stat-pill .lbl  { font-size: .65rem; color: rgba(255,255,255,0.4); margin-top: 2px; letter-spacing: .05em; }

    /* ── RIGHT PANEL ── */
    .right {
      position: relative;
      display: flex; flex-direction: column;
      align-items: center; justify-content: center;
      padding: 3rem 4rem;
      background: radial-gradient(ellipse at 55% 35%, #5a2070 0%, #2e0f45 55%, #1a0a2e 100%);
      overflow: hidden;
      height: 100vh;
    }
    .right::before {
      content: '';
      position: absolute; inset: 0;
      background-image:
        linear-gradient(rgba(255,255,255,0.035) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.035) 1px, transparent 1px);
      background-size: 100px 100px;
      pointer-events: none;
    }

    /* glass card */
    .form-card {
      width: 100%; max-width: 400px;
      position: relative; z-index: 1;
      background: rgba(255,255,255,0.06);
      border: 1px solid rgba(255,255,255,0.12);
      border-radius: 24px;
      padding: 2.5rem;
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      box-shadow: 0 24px 64px rgba(0,0,0,0.35), inset 0 1px 0 rgba(255,255,255,0.1);
      animation: slideUp .55s cubic-bezier(.22,1,.36,1) both;
    }
    @keyframes slideUp {
      from { opacity:0; transform:translateY(28px); }
      to   { opacity:1; transform:translateY(0); }
    }

    .form-heading {
      font-size: 2rem; font-weight: 800;
      color: #fff; letter-spacing: -.5px;
      margin-bottom: .35rem;
    }
    .form-sub {
      font-size: .88rem; color: rgba(255,255,255,0.45);
      margin-bottom: 2rem;
    }

    /* alerts */
    .alert {
      border-radius: 12px; padding: .75rem 1rem;
      font-size: .82rem; margin-bottom: 1.25rem;
      display: flex; align-items: flex-start; gap: 8px; line-height: 1.5;
    }
    .alert-success { background: rgba(100,220,160,0.12); color: #7de8b8; border: 1px solid rgba(100,220,160,0.25); }
    .alert-danger  { background: rgba(220,80,80,0.12);  color: #f09090; border: 1px solid rgba(220,80,80,0.25); }
    .alert ul { padding-left: 1rem; margin: 0; }

    /* fields */
    .field { margin-bottom: 1.1rem; }
    .field label {
      display: block; font-size: .75rem; font-weight: 600;
      color: rgba(255,255,255,0.7); margin-bottom: .4rem; letter-spacing: .03em;
    }
    .input-wrap { position: relative; display: flex; align-items: center; }
    .input-icon {
      position: absolute; left: 13px;
      color: rgba(255,255,255,0.3); font-size: .95rem; pointer-events: none;
    }
    .input-wrap input {
      width: 100%;
      font-family: 'Inter', sans-serif; font-size: .9rem;
      color: #fff;
      background: rgba(255,255,255,0.08);
      border: 1.5px solid rgba(255,255,255,0.12);
      border-radius: 12px; outline: none;
      padding: .8rem 1rem .8rem 2.6rem;
      transition: border-color .2s, box-shadow .2s, background .2s;
    }
    .input-wrap input.no-icon { padding-left: 1rem; padding-right: 2.8rem; }
    .input-wrap input::placeholder { color: rgba(255,255,255,0.2); }
    .input-wrap input:focus {
      border-color: rgba(200,106,200,0.7);
      background: rgba(255,255,255,0.11);
      box-shadow: 0 0 0 3px rgba(200,106,200,0.15);
    }

    .pw-toggle {
      position: absolute; right: 12px;
      background: none; border: none; cursor: pointer;
      color: rgba(255,255,255,0.3); font-size: 1rem;
      transition: color .2s; padding: 4px;
    }
    .pw-toggle:hover { color: rgba(255,255,255,0.7); }

    .field-row {
      display: flex; align-items: center; justify-content: space-between;
      margin-bottom: 1.6rem;
    }
    .check-label {
      display: flex; align-items: center; gap: 8px;
      cursor: pointer; font-size: .8rem;
      color: rgba(255,255,255,0.45); user-select: none;
    }
    .check-label input[type="checkbox"] {
      width: 15px; height: 15px; accent-color: #c86aaa; cursor: pointer;
    }
    .forgot {
      font-size: .8rem; color: #d090e8;
      text-decoration: none; font-weight: 600;
    }
    .forgot:hover { color: #e8b0f8; text-decoration: underline; }

    /* login button */
    .btn-login {
      width: 100%; padding: .9rem;
      background: linear-gradient(135deg, #9b5ec8 0%, #c86aaa 100%);
      color: #fff; font-family: 'Inter', sans-serif;
      font-weight: 700; font-size: .92rem; letter-spacing: .03em;
      border: none; border-radius: 12px; cursor: pointer;
      box-shadow: 0 8px 24px rgba(155,94,200,0.4);
      transition: all .2s;
      display: flex; align-items: center; justify-content: center; gap: 8px;
      margin-bottom: 1rem;
    }
    .btn-login:hover {
      background: linear-gradient(135deg, #8a4eb8 0%, #b85a9a 100%);
      box-shadow: 0 12px 32px rgba(155,94,200,0.55);
      transform: translateY(-2px);
    }
    .btn-login:active { transform: translateY(0); }

    /* divider */
    .divider {
      display: flex; align-items: center; gap: 12px;
      margin-bottom: 1rem; font-size: .72rem;
      color: rgba(255,255,255,0.2);
    }
    .divider::before, .divider::after {
      content: ''; flex: 1; height: 1px;
      background: rgba(255,255,255,0.1);
    }

    /* patient button */
    .btn-patient {
      width: 100%; padding: .82rem;
      background: rgba(255,255,255,0.06);
      color: rgba(255,255,255,0.65);
      font-family: 'Inter', sans-serif; font-weight: 500; font-size: .86rem;
      border: 1.5px solid rgba(255,255,255,0.12);
      border-radius: 12px; cursor: pointer;
      transition: all .2s;
      display: flex; align-items: center; justify-content: center;
      gap: 8px; text-decoration: none;
    }
    .btn-patient:hover {
      border-color: rgba(200,106,200,0.4);
      background: rgba(200,106,200,0.1);
      color: #fff;
    }

    .form-footer {
      margin-top: 1.75rem; font-size: .68rem;
      color: rgba(255,255,255,0.2); text-align: center;
    }

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
  <div class="orb orb-1"></div>
  <div class="orb orb-2"></div>
  <div class="orb orb-3"></div>

  <div class="left-brand">
    <div class="brand-mark"><i class="bi bi-clipboard2-pulse text-white"></i></div>
    <div>
      <div class="brand-name">QueueMed</div>
      <div class="brand-sub">Queue Management</div>
    </div>
  </div>

  <div class="left-hero">
    <div class="left-tag">
      <span class="dot"></span>
      System Online
    </div>
    <h1 class="left-headline"><em>QueueMed</em></h1>
    <p class="left-desc">
      Manage patient flow in real-time. Reduce wait times, improve experience, and keep your clinic running smoothly.
    </p>
  </div>

  <div></div>
</div>

<!-- ── RIGHT ── -->
<div class="right">
  <div class="form-card">

    <h2 class="form-heading">Welcome back</h2>
    <p class="form-sub">Sign in to your admin account</p>

    <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
      <i class="bi bi-check-circle-fill"></i> <?= session()->getFlashdata('success') ?>
    </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
      <i class="bi bi-exclamation-circle-fill"></i> <?= session()->getFlashdata('error') ?>
    </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
      <i class="bi bi-exclamation-circle-fill"></i>
      <ul>
        <?php foreach (session()->getFlashdata('errors') as $err): ?>
        <li><?= esc($err) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <?php endif; ?>

    <form method="POST" action="<?= base_url('login') ?>">
      <?= csrf_field() ?>

      <div class="field">
        <label>Email Address</label>
        <div class="input-wrap">
          <i class="bi bi-envelope input-icon"></i>
          <input type="email" name="email" placeholder="you@clinic.com"
                 value="<?= old('email') ?>" required autofocus/>
        </div>
      </div>

      <div class="field">
        <label>Password</label>
        <div class="input-wrap">
          <input type="password" name="password" id="password" class="no-icon"
                 placeholder="Enter your password" required/>
          <button type="button" class="pw-toggle" id="togglePw">
            <i class="bi bi-eye" id="eyeIcon"></i>
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
        <i class="bi bi-box-arrow-in-right"></i> Login
      </button>
    </form>

    <div class="divider">or</div>

    <a href="<?= base_url('/') ?>" class="btn-patient">
      <i class="bi bi-ticket-perforated"></i> Get Queue Number (Patient)
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
    eyeIcon.className = hidden ? 'bi bi-eye-slash' : 'bi bi-eye';
  });
</script>
</body>
</html>
