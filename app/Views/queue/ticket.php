<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Your Queue Ticket | QueueMed</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"/>
  <style>
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
    html, body { height: 100%; }

    body {
      font-family: 'Inter', sans-serif;
      min-height: 100vh;
      background: radial-gradient(ellipse at 30% 20%, #5a2070 0%, #2e0f45 50%, #1a0a2e 100%);
      display: flex; flex-direction: column;
      color: #e8e0f5;
    }

    .orb {
      position: fixed; border-radius: 50%;
      pointer-events: none; filter: blur(80px); z-index: 0;
    }
    .orb-1 { width: 350px; height: 350px; background: rgba(180,80,200,0.2);  top: -80px;  left: -80px; }
    .orb-2 { width: 250px; height: 250px; background: rgba(120,60,200,0.18); bottom: 60px; right: -60px; }

    /* NAV */
    nav {
      position: relative; z-index: 10;
      background: rgba(255,255,255,0.05);
      border-bottom: 1px solid rgba(255,255,255,0.08);
      backdrop-filter: blur(12px);
      padding: 0 2.5rem; height: 58px;
      display: flex; align-items: center; justify-content: space-between;
      flex-shrink: 0;
    }
    .nav-brand {
      display: flex; align-items: center; gap: 10px; text-decoration: none;
    }
    .nav-brand .brand-mark {
      width: 34px; height: 34px;
      background: linear-gradient(135deg, #c86aaa, #9b5ec8);
      border-radius: 9px;
      display: flex; align-items: center; justify-content: center; font-size: 1rem;
      box-shadow: 0 4px 14px rgba(180,80,200,0.35);
    }
    .nav-brand .brand-name { font-size: 1rem; font-weight: 800; color: #fff; letter-spacing: -.2px; }
    .nav-back {
      display: flex; align-items: center; gap: 6px;
      font-size: 0.78rem; font-weight: 500;
      color: rgba(255,255,255,0.55); text-decoration: none;
      background: rgba(255,255,255,0.07);
      border: 1px solid rgba(255,255,255,0.12);
      border-radius: 9px; padding: 7px 14px;
      transition: all 0.2s;
    }
    .nav-back:hover {
      color: #fff; background: rgba(200,106,200,0.15);
      border-color: rgba(200,106,200,0.35);
    }

    /* MAIN */
    main {
      flex: 1; position: relative; z-index: 1;
      display: flex; align-items: center; justify-content: center;
      padding: 3rem 1.5rem;
    }

    /* TICKET CARD */
    .ticket-wrap {
      width: 100%; max-width: 460px;
      animation: slideUp 0.5s cubic-bezier(0.22, 1, 0.36, 1) both;
    }
    @keyframes slideUp {
      from { opacity: 0; transform: translateY(28px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .ticket-card {
      background: rgba(255,255,255,0.07);
      border: 1px solid rgba(255,255,255,0.12);
      border-radius: 24px;
      overflow: hidden;
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      box-shadow: 0 24px 64px rgba(0,0,0,0.35), inset 0 1px 0 rgba(255,255,255,0.1);
    }

    /* ticket header */
    .ticket-header {
      background: linear-gradient(135deg, #9b5ec8 0%, #c86aaa 100%);
      padding: 2rem; text-align: center;
      box-shadow: 0 8px 24px rgba(155,94,200,0.4);
    }
    .ticket-header i { font-size: 2rem; color: #fff; margin-bottom: .5rem; display: block; }
    .ticket-header .title { font-size: 1.2rem; font-weight: 800; color: #fff; }
    .ticket-header .sub   { font-size: .78rem; color: rgba(255,255,255,0.65); margin-top: 2px; }

    /* queue number section */
    .ticket-number {
      padding: 2rem; text-align: center;
      border-bottom: 1px dashed rgba(255,255,255,0.1);
    }
    .ticket-number .label {
      font-size: .68rem; font-weight: 700; letter-spacing: .12em;
      text-transform: uppercase; color: rgba(255,255,255,0.4); margin-bottom: .5rem;
    }
    .ticket-number .num {
      font-size: 6rem; font-weight: 900; line-height: 1;
      color: #e8d5ff; letter-spacing: -3px;
      text-shadow: 0 0 40px rgba(200,106,200,0.3);
    }
    .ticket-number .service {
      font-size: .88rem; color: rgba(255,255,255,0.5); margin-top: .5rem;
    }

    /* details */
    .ticket-details {
      padding: 1.5rem 2rem;
      border-bottom: 1px dashed rgba(255,255,255,0.1);
      display: grid; grid-template-columns: 1fr 1fr; gap: 1.2rem;
    }
    .detail-item .lbl {
      font-size: .68rem; font-weight: 600; letter-spacing: .08em;
      text-transform: uppercase; color: rgba(255,255,255,0.35); margin-bottom: 4px;
    }
    .detail-item .val {
      font-size: .92rem; font-weight: 600; color: #f0e8ff;
    }
    .badge-waiting {
      display: inline-block;
      background: rgba(255,200,60,0.2);
      border: 1px solid rgba(255,200,60,0.4);
      color: #ffd060; font-size: .78rem; font-weight: 700;
      padding: 3px 12px; border-radius: 50px;
    }
    .badge-serving {
      display: inline-block;
      background: rgba(80,220,160,0.2);
      border: 1px solid rgba(80,220,160,0.4);
      color: #60e0b0; font-size: .78rem; font-weight: 700;
      padding: 3px 12px; border-radius: 50px;
    }

    /* footer */
    .ticket-footer {
      padding: 1.5rem 2rem; text-align: center;
    }
    .ticket-footer .info {
      font-size: .82rem; color: rgba(255,255,255,0.35);
      margin-bottom: 1.25rem; line-height: 1.6;
    }
    .ticket-footer .info strong { color: rgba(255,255,255,0.65); }
    .btn-another {
      display: inline-flex; align-items: center; gap: 7px;
      padding: 10px 24px;
      background: rgba(255,255,255,0.08);
      border: 1.5px solid rgba(255,255,255,0.15);
      border-radius: 12px; color: rgba(255,255,255,0.7);
      font-family: 'Inter', sans-serif; font-size: .88rem; font-weight: 600;
      text-decoration: none; transition: all .2s;
    }
    .btn-another:hover {
      background: rgba(200,106,200,0.15);
      border-color: rgba(200,106,200,0.4);
      color: #fff;
    }

    footer {
      position: relative; z-index: 1;
      text-align: center; font-size: .68rem;
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
  <a href="<?= base_url('/') ?>" class="nav-back">
    <i class="bi bi-arrow-left"></i> Back
  </a>
</nav>

<main>
  <div class="ticket-wrap">
    <div class="ticket-card">

      <!-- Header -->
      <div class="ticket-header">
        <i class="bi bi-ticket-perforated-fill"></i>
        <div class="title">Queue Ticket</div>
        <div class="sub">QueueMed Clinic</div>
      </div>

      <!-- Number -->
      <div class="ticket-number">
        <div class="label">Your Number</div>
        <div class="num"><?= str_pad($ticket['queue_number'], 3, '0', STR_PAD_LEFT) ?></div>
        <div class="service"><?= esc($ticket['service_name']) ?></div>
      </div>

      <!-- Details -->
      <div class="ticket-details">
        <div class="detail-item">
          <div class="lbl">Name</div>
          <div class="val"><?= esc($ticket['patient_name']) ?></div>
        </div>
        <div class="detail-item">
          <div class="lbl">Date</div>
          <div class="val"><?= date('M d, Y', strtotime($ticket['date'])) ?></div>
        </div>
        <div class="detail-item">
          <div class="lbl">Time Issued</div>
          <div class="val"><?= date('h:i A', strtotime($ticket['created_at'])) ?></div>
        </div>
        <div class="detail-item">
          <div class="lbl">Status</div>
          <span class="badge-waiting">Waiting</span>
        </div>
      </div>

      <!-- Footer -->
      <div class="ticket-footer">
        <p class="info">
          <i class="bi bi-info-circle me-1"></i>
          Please watch the <strong>Now Serving</strong> screen and wait for your number to be called.
        </p>
        <a href="<?= base_url('/') ?>" class="btn-another">
          <i class="bi bi-plus-circle"></i> Get Another Number
        </a>
      </div>

    </div>
  </div>
</main>

<footer>&copy; <?= date('Y') ?> QueueMed. All rights reserved.</footer>

</body>
</html>
