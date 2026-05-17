<?php
$page     = $page ?? 'dashboard';
$name     = session()->get('name') ?? 'Admin';
$initials = strtoupper(substr($name, 0, 1) . (strpos($name, ' ') !== false ? substr($name, strpos($name, ' ') + 1, 1) : ''));

$nav_items = [
  ['id'=>'dashboard',    'label'=>'Dashboard',     'icon'=>'bi-grid-1x2-fill',    'href'=> base_url('admin/dashboard')],
  ['id'=>'appointments', 'label'=>'Appointments',  'icon'=>'bi-calendar-check',   'href'=> base_url('admin/appointments')],
  ['id'=>'queue',        'label'=>'Queue Monitor', 'icon'=>'bi-ticket-perforated','href'=> base_url('admin/queue')],
  ['id'=>'services',     'label'=>'Services',      'icon'=>'bi-clipboard2-pulse', 'href'=> base_url('admin/services')],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= ucfirst($page) ?> | QueueMed Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

    :root {
      --sidebar-bg:    #a98bc8;
      --accent:        #9b6fc8;
      --accent-light:  #3a1a5a;
      --accent-soft:   #2e0f45;
      --body-bg:       #1a0a2e;
      --card-bg:       rgba(255,255,255,0.06);
      --card-border:   rgba(255,255,255,0.1);
      --text-dark:     #f0e8ff;
      --text-mid:      #c8a8e8;
      --text-soft:     #8a6aaa;
    }

    *, *::before, *::after { box-sizing: border-box; }

    body {
      background: radial-gradient(ellipse at 30% 20%, #5a2070 0%, #2e0f45 50%, #1a0a2e 100%) !important;
      font-family: 'Inter', 'Segoe UI', sans-serif;
      color: var(--text-dark);
      min-height: 100vh;
    }

    /* ── SIDEBAR ── */
    .sidebar {
      width: 260px; min-height: 100vh; position: fixed; top: 0; left: 0; z-index: 200;
      background: linear-gradient(180deg, #3a1255 0%, #2a0d40 50%, #1a0a2e 100%);
      box-shadow: 6px 0 32px rgba(0,0,0,0.4);
      display: flex; flex-direction: column;
      border-right: 1px solid rgba(255,255,255,0.06);
    }
    .sidebar-brand {
      padding: 24px 20px 20px;
      border-bottom: 1px solid rgba(255,255,255,0.18);
    }
    .sidebar-brand .brand-icon {
      width: 46px; height: 46px;
      background: rgba(255,255,255,0.25);
      border: 1.5px solid rgba(255,255,255,0.4);
      border-radius: 14px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.4rem;
      box-shadow: 0 4px 14px rgba(0,0,0,0.1);
    }
    .sidebar-brand .brand-name { font-size: 1.05rem; font-weight: 700; letter-spacing: -.2px; color: #fff; }
    .sidebar-brand .brand-sub  { font-size: .7rem; color: rgba(255,255,255,0.6); margin-top: 1px; }

    .nav-section-label {
      font-size: .65rem; letter-spacing: 1.6px; color: rgba(255,255,255,0.45);
      padding: 0 16px; margin: 18px 0 8px; text-transform: uppercase; font-weight: 600;
    }
    .nav-link-item {
      display: flex; align-items: center; gap: 11px;
      padding: 10px 14px; border-radius: 12px; margin-bottom: 2px;
      color: rgba(255,255,255,0.78); text-decoration: none; font-size: .88rem; font-weight: 500;
      transition: all 0.2s ease;
    }
    .nav-link-item i { font-size: 1rem; flex-shrink: 0; }
    .nav-link-item:hover {
      background: rgba(255,255,255,0.18);
      color: #fff;
      transform: translateX(4px);
    }
    .nav-link-item.active {
      background: rgba(255,255,255,0.28);
      border: 1px solid rgba(255,255,255,0.3);
      color: #fff; font-weight: 600;
      box-shadow: 0 4px 16px rgba(0,0,0,0.12);
    }
    .sidebar-footer {
      padding: 16px 20px;
      border-top: 1px solid rgba(255,255,255,0.15);
      background: rgba(0,0,0,0.1);
      margin-top: auto;
    }
    .avatar {
      width: 38px; height: 38px;
      background: rgba(255,255,255,0.3);
      border: 1.5px solid rgba(255,255,255,0.4);
      border-radius: 50%; display: flex; align-items: center; justify-content: center;
      font-size: .85rem; font-weight: 700; color: #fff; flex-shrink: 0;
    }
    .avatar-name { font-size: .85rem; font-weight: 600; color: #fff; }
    .avatar-role { font-size: .68rem; color: rgba(255,255,255,0.55); }

    /* ── TOPBAR ── */
    .topbar {
      left: 260px; right: 0; height: 64px; z-index: 100; width: auto;
      background: rgba(255,255,255,0.05);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border-bottom: 1px solid rgba(255,255,255,0.08);
      box-shadow: 0 2px 20px rgba(0,0,0,0.2);
      padding: 0 28px !important;
      overflow: visible;
    }
    .topbar .breadcrumb { margin: 0; }
    .topbar .breadcrumb-item a { color: #c8a0e8; text-decoration: none; }
    .topbar .breadcrumb-item.active { color: rgba(255,255,255,0.7); font-weight: 600; font-size: .9rem; }
    .topbar .breadcrumb-item + .breadcrumb-item::before { color: rgba(255,255,255,0.25); }
    .topbar .date-badge {
      background: rgba(200,106,200,0.15);
      color: #d8a8f0;
      border-radius: 10px; padding: 6px 14px;
      font-size: .8rem; font-weight: 600;
      white-space: nowrap; flex-shrink: 0;
      border: 1px solid rgba(200,106,200,0.25);
    }

    /* ── MAIN ── */
    .main-content { margin-left: 260px; padding-top: 64px; }

    /* ── STAT CARDS ── */
    .stat-card {
      border: 1px solid var(--card-border) !important;
      border-radius: 18px !important;
      background: var(--card-bg) !important;
      backdrop-filter: blur(12px);
      box-shadow: 0 4px 24px rgba(120,80,180,0.08) !important;
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 10px 32px rgba(120,80,180,0.15) !important;
    }
    .stat-card .card-body { padding: 22px 24px; }
    .stat-icon {
      width: 52px; height: 52px; border-radius: 14px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.4rem; flex-shrink: 0;
    }
    .stat-icon.blue   { background: #ede8ff; color: #7c5cbf; }
    .stat-icon.orange { background: #fff3e0; color: #e07b00; }
    .stat-icon.green  { background: #e0f7ef; color: #0a9e6a; }
    .stat-icon.teal   { background: #e8e0ff; color: #7b5ea7; }
    .stat-num   { font-size: 2rem; font-weight: 800; line-height: 1; color: var(--text-dark); }
    .stat-label { font-size: .78rem; color: var(--text-soft); margin-top: 5px; font-weight: 500; letter-spacing: .2px; }

    /* ── PAGE TITLE ── */
    .page-title {
      font-size: 1.2rem; font-weight: 700; color: var(--text-dark); margin-bottom: 20px;
      display: flex; align-items: center; gap: 8px;
    }
    .page-title span { font-size: .78rem; font-weight: 400; color: var(--text-soft); }

    /* ── CONTENT CARDS ── */
    .content-card {
      border: 1px solid rgba(255,255,255,0.1) !important;
      border-radius: 18px !important;
      background: rgba(255,255,255,0.06) !important;
      backdrop-filter: blur(12px);
      box-shadow: 0 4px 24px rgba(0,0,0,0.25) !important;
      overflow: hidden;
    }
    .content-card .card-header {
      background: rgba(255,255,255,0.05) !important;
      border-bottom: 1px solid rgba(255,255,255,0.08) !important;
      padding: 16px 22px; font-weight: 700; color: #e8d8ff; font-size: .92rem;
      display: flex; align-items: center; justify-content: space-between;
    }
    .content-card .card-body { color: #e0d0f8; }

    /* ── TABLE ── */
    .table thead th {
      background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.5);
      font-size: .72rem; text-transform: uppercase; letter-spacing: .8px;
      border: none; padding: 13px 18px; font-weight: 600;
    }
    .table tbody td { padding: 13px 18px; vertical-align: middle; border-color: rgba(255,255,255,0.06); font-size: .88rem; color: #e0d0f8; }
    .table tbody tr { background: transparent !important; }
    .table tbody tr:hover { background: rgba(255,255,255,0.05) !important; }
    .table { --bs-table-bg: transparent; --bs-table-striped-bg: transparent; --bs-table-hover-bg: rgba(255,255,255,0.05); color: #e0d0f8; }
    .queue-num { color: #d8a8f8; font-weight: 800; font-family: 'Inter', monospace; font-size: .95rem; }
    .fw-semibold, .fw-medium { color: #f0e8ff !important; }
    .text-muted { color: rgba(255,255,255,0.45) !important; }

    /* ── STAT CARDS ── */
    .stat-card {
      border: 1px solid rgba(255,255,255,0.1) !important;
      border-radius: 18px !important;
      background: rgba(255,255,255,0.06) !important;
      backdrop-filter: blur(12px);
      box-shadow: 0 4px 24px rgba(0,0,0,0.2) !important;
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 10px 32px rgba(0,0,0,0.35) !important;
    }
    .stat-card .card-body { padding: 22px 24px; }
    .stat-icon {
      width: 52px; height: 52px; border-radius: 14px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.4rem; flex-shrink: 0;
    }
    .stat-icon.blue   { background: rgba(180,120,255,0.15); color: #c8a0f8; }
    .stat-icon.orange { background: rgba(255,180,60,0.15);  color: #ffc060; }
    .stat-icon.green  { background: rgba(80,220,160,0.15);  color: #60e0b0; }
    .stat-icon.teal   { background: rgba(160,120,255,0.15); color: #c0a0f8; }
    .stat-num   { font-size: 2rem; font-weight: 800; line-height: 1; color: #fff; }
    .stat-label { font-size: .78rem; color: rgba(255,255,255,0.4); margin-top: 5px; font-weight: 500; }

    /* ── PAGE TITLE ── */
    .page-title {
      font-size: 1.2rem; font-weight: 700; color: #f0e8ff; margin-bottom: 20px;
      display: flex; align-items: center; gap: 8px;
    }
    .page-title span { font-size: .78rem; font-weight: 400; color: rgba(255,255,255,0.3); }

    /* ── BUTTONS ── */
    .btn-clinic {
      background: linear-gradient(135deg, #9b5ec8 0%, #c86aaa 100%);
      border: none; color: #fff; font-weight: 600; border-radius: 10px;
      box-shadow: 0 4px 14px rgba(155,94,200,0.35);
      transition: all 0.2s;
    }
    .btn-clinic:hover {
      background: linear-gradient(135deg, #8a4eb8 0%, #b85a9a 100%);
      color: #fff; box-shadow: 0 6px 20px rgba(155,94,200,0.5);
      transform: translateY(-1px);
    }
    .btn-success {
      background: linear-gradient(135deg, #9b5ec8 0%, #c86aaa 100%) !important;
      border: none !important;
      box-shadow: 0 4px 14px rgba(155,94,200,0.3) !important;
    }
    .btn-outline-secondary {
      border-color: rgba(255,255,255,0.2) !important;
      color: rgba(255,255,255,0.5) !important;
    }
    .btn-outline-secondary:hover {
      background: rgba(255,255,255,0.1) !important;
      color: #fff !important;
    }

    /* ── FORM ── */
    .form-control, .form-select {
      background: rgba(255,255,255,0.08) !important;
      border-color: rgba(255,255,255,0.12) !important;
      border-radius: 10px;
      font-size: .88rem;
      color: #fff !important;
    }
    .form-control::placeholder { color: rgba(255,255,255,0.2) !important; }
    .form-control:focus, .form-select:focus {
      border-color: rgba(200,106,200,0.7) !important;
      box-shadow: 0 0 0 3px rgba(200,106,200,0.15) !important;
      background: rgba(255,255,255,0.11) !important;
    }
    .form-select option { background: #2e0f45; color: #fff; }
    .form-select-sm { border-radius: 8px; font-size: .8rem; color: #fff !important; }
    .form-label { color: rgba(255,255,255,0.7); font-weight: 600; font-size: .82rem; }

    /* ── LIST GROUP ── */
    .list-group-item {
      border-color: rgba(255,255,255,0.07) !important;
      background: transparent !important;
      color: #e0d0f8;
    }
    .list-group-item:hover { background: rgba(255,255,255,0.04) !important; }
    .list-group-item .fw-semibold, .list-group-item .fw-medium { color: #f0e8ff; }
    .list-group-item .text-muted { color: rgba(255,255,255,0.4) !important; }

    /* ── ALERTS ── */
    .alert-success { background: rgba(100,220,160,0.1); border-color: rgba(100,220,160,0.2); color: #7de8b8; border-radius: 12px; }
    .alert-danger  { background: rgba(220,80,80,0.1);  border-color: rgba(220,80,80,0.2);  color: #f09090; border-radius: 12px; }

    /* ── BREADCRUMB ── */
    .breadcrumb-item.active { color: rgba(255,255,255,0.7); font-weight: 600; }

    @media(max-width:991px){
      .sidebar { transform: translateX(-100%); transition: transform .3s; }
      .sidebar.show { transform: translateX(0); }
      .main-content { margin-left: 0; }
      .topbar { left: 0; }
    }
  </style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar text-white d-flex flex-column">
  <div class="sidebar-brand d-flex align-items-center gap-3">
    <div class="brand-icon"><i class="bi bi-clipboard2-pulse text-white"></i></div>
    <div>
      <div class="brand-name">QueueMed</div>
      <div class="brand-sub">Admin Panel</div>
    </div>
  </div>

  <nav class="flex-grow-1 px-3 py-2 overflow-auto">
    <div class="nav-section-label text-white">Main Menu</div>
    <?php foreach ($nav_items as $item): ?>
    <a href="<?= $item['href'] ?>" class="nav-link-item <?= $page===$item['id']?'active':'' ?>">
      <i class="bi <?= $item['icon'] ?>"></i> <?= $item['label'] ?>
    </a>
    <?php endforeach; ?>
  </nav>

  <div class="sidebar-footer d-flex align-items-center gap-2">
    <div class="avatar"><?= esc($initials) ?></div>
    <div class="flex-grow-1 overflow-hidden">
      <div class="avatar-name text-white text-truncate"><?= esc($name) ?></div>
      <div class="avatar-role text-white">Administrator</div>
    </div>
    <a href="<?= base_url('logout') ?>" class="text-white opacity-75 fs-5" title="Logout"><i class="bi bi-box-arrow-right"></i></a>
  </div>
</div>

<!-- TOPBAR -->
<nav class="navbar topbar px-4 position-fixed d-flex align-items-center justify-content-between">
  <div class="d-flex align-items-center gap-2">
    <button class="btn btn-sm btn-outline-secondary d-lg-none" id="sidebarToggle"><i class="bi bi-list"></i></button>
    <ol class="breadcrumb mb-0">
      <li class="breadcrumb-item active fw-semibold"><?= ucfirst($page) ?></li>
    </ol>
  </div>
  <div class="d-flex align-items-center gap-2">
    <span class="date-badge"><i class="bi bi-calendar3 me-1"></i><?= date('M d, Y') ?></span>
  </div>
</nav>

<!-- PAGE CONTENT -->
<div class="main-content">
  <div class="p-4">

    <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success d-flex align-items-center gap-2 border-0 rounded-3">
      <i class="bi bi-check-circle-fill"></i> <?= session()->getFlashdata('success') ?>
    </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger d-flex align-items-center gap-2 border-0 rounded-3">
      <i class="bi bi-exclamation-circle-fill"></i> <?= session()->getFlashdata('error') ?>
    </div>
    <?php endif; ?>

    <!-- ==================== DASHBOARD ==================== -->
    <?php if ($page === 'dashboard'): ?>

    <div class="page-title"><i class="bi bi-grid-1x2-fill me-2"></i>Dashboard</div>

    <div class="row g-3 mb-4">
      <div class="col-6 col-xl-3">
        <div class="card stat-card h-100">
          <div class="card-body d-flex align-items-center gap-3">
            <div class="stat-icon blue"><i class="bi bi-calendar2-week"></i></div>
            <div>
              <div class="stat-num"><?= $total_today ?? 0 ?></div>
              <div class="stat-label">Total Today</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-6 col-xl-3">
        <div class="card stat-card h-100">
          <div class="card-body d-flex align-items-center gap-3">
            <div class="stat-icon orange"><i class="bi bi-hourglass-split"></i></div>
            <div>
              <div class="stat-num"><?= $pending_count ?? 0 ?></div>
              <div class="stat-label">Pending</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-6 col-xl-3">
        <div class="card stat-card h-100">
          <div class="card-body d-flex align-items-center gap-3">
            <div class="stat-icon green"><i class="bi bi-check-circle"></i></div>
            <div>
              <div class="stat-num"><?= $completed_today ?? 0 ?></div>
              <div class="stat-label">Completed Today</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-6 col-xl-3">
        <div class="card stat-card h-100">
          <div class="card-body d-flex align-items-center gap-3">
            <div class="stat-icon teal"><i class="bi bi-people"></i></div>
            <div>
              <div class="stat-num"><?= $total_patients ?? 0 ?></div>
              <div class="stat-label">Total Patients</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card content-card">
      <div class="card-header">
        <span><i class="bi bi-clock-history me-2" style="color:#8a9e7e;"></i>Recent Appointments</span>
        <a href="<?= base_url('admin/appointments') ?>" class="btn btn-sm btn-clinic px-3">View All</a>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table mb-0">
            <thead><tr><th>Queue #</th><th>Patient</th><th>Service</th><th>Date</th><th>Status</th><th>Action</th></tr></thead>
            <tbody>
            <?php foreach ($recent_appts ?? [] as $a): ?>
            <?php $s = $a['status'] ?? 'waiting'; $b = match($s) { 'serving'=>'info','completed'=>'success','skipped'=>'danger', default=>'warning' }; ?>
            <tr>
              <td><span class="queue-num">#<?= str_pad(esc($a['queue_number']), 3, '0', STR_PAD_LEFT) ?></span></td>
              <td class="fw-semibold"><?= esc($a['patient_name'] ?? '-') ?></td>
              <td class="text-muted"><?= esc($a['service_name'] ?? '-') ?></td>
              <td class="text-muted"><?= esc($a['date'] ?? '-') ?></td>
              <td><span class="badge bg-<?= $b ?> rounded-pill px-3"><?= ucfirst($s) ?></span></td>
              <td>
                <form method="POST" action="<?= base_url('admin/appointments/update') ?>" class="d-inline">
                  <?= csrf_field() ?>
                  <input type="hidden" name="id" value="<?= $a['id'] ?>">
                  <select name="status" class="form-select form-select-sm d-inline w-auto" onchange="this.form.submit()">
                    <?php foreach (['waiting','serving','completed','skipped'] as $opt): ?>
                    <option value="<?= $opt ?>" <?= $s===$opt?'selected':'' ?>><?= ucfirst($opt) ?></option>
                    <?php endforeach; ?>
                  </select>
                </form>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($recent_appts)): ?>
            <tr><td colspan="6" class="text-center text-muted py-5">
              <i class="bi bi-calendar-x fs-3 d-block mb-2 opacity-25"></i>No appointments today.
            </td></tr>
            <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- ==================== APPOINTMENTS ==================== -->
    <?php elseif ($page === 'appointments'): ?>

    <div class="page-title"><i class="bi bi-calendar-check me-2"></i>Appointments</div>
    <div class="card content-card">
      <div class="card-header"><i class="bi bi-calendar-check me-2 text-muted"></i>All Appointments</div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table mb-0">
            <thead><tr><th>Queue #</th><th>Patient</th><th>Service</th><th>Date</th><th>Status</th><th>Action</th></tr></thead>
            <tbody>
            <?php foreach ($appointments ?? [] as $a): ?>
            <?php $s = $a['status'] ?? 'waiting'; $b = match($s) { 'serving'=>'info','completed'=>'success','skipped'=>'danger', default=>'warning' }; ?>
            <tr>
              <td><span class="queue-num">#<?= str_pad(esc($a['queue_number']), 3, '0', STR_PAD_LEFT) ?></span></td>
              <td class="fw-medium"><?= esc($a['patient_name'] ?? '-') ?></td>
              <td class="text-muted"><?= esc($a['service_name'] ?? '-') ?></td>
              <td class="text-muted"><?= esc($a['date'] ?? '-') ?></td>
              <td><span class="badge bg-<?= $b ?> rounded-pill"><?= ucfirst($s) ?></span></td>
              <td>
                <form method="POST" action="<?= base_url('admin/appointments/update') ?>" class="d-inline">
                  <?= csrf_field() ?>
                  <input type="hidden" name="id" value="<?= $a['id'] ?>">
                  <select name="status" class="form-select form-select-sm d-inline w-auto" onchange="this.form.submit()">
                    <?php foreach (['waiting','serving','completed','skipped'] as $opt): ?>
                    <option value="<?= $opt ?>" <?= $s===$opt?'selected':'' ?>><?= ucfirst($opt) ?></option>
                    <?php endforeach; ?>
                  </select>
                </form>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($appointments)): ?>
            <tr><td colspan="6" class="text-center text-muted py-4">No appointments found.</td></tr>
            <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- ==================== QUEUE ==================== -->
    <?php elseif ($page === 'queue'): ?>

    <div class="page-title"><i class="bi bi-ticket-perforated me-2"></i>Queue Monitor</div>
    <div class="d-flex justify-content-end mb-3">
      <form method="POST" action="<?= base_url('admin/queue/next') ?>">
        <?= csrf_field() ?>
        <button type="submit" class="btn btn-clinic px-4">
          <i class="bi bi-skip-forward me-1"></i> Call Next
        </button>
      </form>
    </div>

    <div class="card content-card">
      <ul class="list-group list-group-flush">
        <?php foreach ($queue ?? [] as $q): ?>
        <li class="list-group-item d-flex align-items-center gap-3 py-3 px-4">
          <div class="stat-icon blue" style="width:40px;height:40px;border-radius:10px;font-size:1rem;">
            <i class="bi bi-person"></i>
          </div>
          <div class="flex-grow-1">
            <div class="fw-semibold small"><?= esc($q['patient_name'] ?? '-') ?></div>
            <div class="text-muted" style="font-size:.78rem"><?= esc($q['service_name'] ?? '-') ?></div>
          </div>
          <span class="queue-num me-2">#<?= esc($q['queue_number'] ?? '-') ?></span>
          <?php $s = $q['status'] ?? 'waiting'; ?>
          <?php if ($s === 'serving'): ?>
            <span class="badge bg-success rounded-pill">Serving</span>
          <?php else: ?>
            <span class="badge bg-warning text-dark rounded-pill">Waiting</span>
          <?php endif; ?>
        </li>
        <?php endforeach; ?>
        <?php if (empty($queue)): ?>
        <li class="list-group-item text-center text-muted py-5">
          <i class="bi bi-inbox fs-3 d-block mb-2 opacity-25"></i>No queue for today.
        </li>
        <?php endif; ?>
      </ul>
    </div>

    <!-- ==================== SERVICES ==================== -->
    <?php elseif ($page === 'services'): ?>

    <div class="page-title"><i class="bi bi-clipboard2-pulse me-2"></i>Services</div>

    <div class="row g-3">
      <div class="col-12 col-md-5">
        <div class="card content-card">
          <div class="card-header"><i class="bi bi-plus-circle me-2 text-muted"></i>Add Service</div>
          <div class="card-body">
            <form method="POST" action="<?= base_url('admin/services/store') ?>">
              <?= csrf_field() ?>
              <div class="mb-3">
                <label class="form-label small fw-semibold">Service Name</label>
                <input type="text" name="name" class="form-control" required/>
              </div>
              <div class="mb-3">
                <label class="form-label small fw-semibold">Description</label>
                <textarea name="description" class="form-control" rows="2"></textarea>
              </div>
              <button type="submit" class="btn btn-clinic w-100">Add Service</button>
            </form>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-7">
        <div class="card content-card">
          <div class="card-header"><i class="bi bi-list-ul me-2 text-muted"></i>All Services</div>
          <ul class="list-group list-group-flush">
            <?php foreach ($services ?? [] as $svc): ?>
            <li class="list-group-item d-flex align-items-center justify-content-between py-3 px-4">
              <div>
                <div class="fw-semibold small"><?= esc($svc['name']) ?></div>
                <div class="text-muted" style="font-size:.78rem"><?= esc($svc['duration'] ?? '') ?> mins</div>
              </div>
              <form method="POST" action="<?= base_url('admin/services/toggle/' . $svc['id']) ?>">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-sm <?= $svc['is_active'] ? 'btn-success' : 'btn-outline-secondary' ?> rounded-pill px-3">
                  <?= $svc['is_active'] ? 'Active' : 'Inactive' ?>
                </button>
              </form>
            </li>
            <?php endforeach; ?>
            <?php if (empty($services)): ?>
            <li class="list-group-item text-center text-muted py-3">No services yet.</li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </div>

    <?php endif; ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const toggle  = document.getElementById('sidebarToggle');
  const sidebar = document.querySelector('.sidebar');
  toggle?.addEventListener('click', () => sidebar.classList.toggle('show'));
  document.addEventListener('click', e => {
    if (!sidebar.contains(e.target) && !toggle?.contains(e.target))
      sidebar.classList.remove('show');
  });
</script>
</body>
</html>
