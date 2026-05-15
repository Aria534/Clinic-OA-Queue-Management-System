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
  <style>
    :root {
      --clinic-blue:   #8a9e7e;
      --clinic-dark:   #6b7d61;
      --clinic-light:  #f0f4ee;
      --clinic-border: #d4dece;
      --clinic-teal:   #7a9470;
    }

    body { background: #fce8ed !important; font-family: 'Segoe UI', sans-serif; }
    .main-content > div { background: transparent; }
    .content-card, .stat-card { background: #fff; }

    /* SIDEBAR */
    .sidebar {
      width: 260px; min-height: 100vh; position: fixed; top: 0; left: 0; z-index: 200;
      background: linear-gradient(160deg, #4a5e42 0%, #6b7d61 50%, #8a9e7e 100%);
      backdrop-filter: blur(10px);
      box-shadow: 4px 0 24px rgba(0,0,0,0.15);
    }
    .sidebar-brand {
      padding: 22px 20px;
      border-bottom: 1px solid rgba(255,255,255,0.15);
    }
    .sidebar-brand .brand-icon {
      width: 44px; height: 44px;
      background: rgba(255,255,255,0.2);
      backdrop-filter: blur(8px);
      border: 1px solid rgba(255,255,255,0.3);
      border-radius: 12px;
      display: flex; align-items: center; justify-content: center; font-size: 1.4rem;
    }
    .sidebar-brand .brand-name { font-size: 1.05rem; font-weight: 700; letter-spacing: .3px; }
    .sidebar-brand .brand-sub  { font-size: .72rem; opacity: .65; }

    .nav-section-label {
      font-size: .68rem; letter-spacing: 1.4px; opacity: .5;
      padding: 0 14px; margin: 14px 0 6px; text-transform: uppercase;
    }
    .nav-link-item {
      display: flex; align-items: center; gap: 12px;
      padding: 11px 14px; border-radius: 10px; margin-bottom: 3px;
      color: rgba(255,255,255,0.8); text-decoration: none; font-size: .95rem;
      transition: all 0.18s;
    }
    .nav-link-item i { font-size: 1.05rem; }
    .nav-link-item:hover {
      background: rgba(255,255,255,0.15);
      backdrop-filter: blur(6px);
      color: #fff;
      transform: translateX(3px);
    }
    .nav-link-item.active {
      background: rgba(255,255,255,0.22);
      backdrop-filter: blur(8px);
      border: 1px solid rgba(255,255,255,0.25);
      color: #fff; font-weight: 600;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .sidebar-footer {
      padding: 16px 20px;
      border-top: 1px solid rgba(255,255,255,0.15);
      background: rgba(0,0,0,0.08);
    }
    .avatar {
      width: 38px; height: 38px;
      background: rgba(255,255,255,0.25);
      border: 1px solid rgba(255,255,255,0.3);
      border-radius: 50%; display: flex; align-items: center; justify-content: center;
      font-size: .88rem; font-weight: 700; color: #fff; flex-shrink: 0;
    }
    .avatar-name  { font-size: .88rem; font-weight: 600; }
    .avatar-role  { font-size: .72rem; opacity: .6; }

    /* TOPBAR */
    .topbar {
      left: 260px; right: 0; height: 64px; z-index: 100;
      background: rgba(255,255,255,0.85);
      backdrop-filter: blur(12px);
      border-bottom: 1px solid #dde5d8;
      box-shadow: 0 2px 12px rgba(0,0,0,0.06);
      padding-right: 24px !important;
      overflow: hidden;
    }
    .topbar .breadcrumb-item a, .topbar .breadcrumb-item.active { color: var(--clinic-blue); font-weight: 500; }
    .topbar .date-badge {
      background: var(--clinic-light); color: var(--clinic-blue);
      border-radius: 8px; padding: 5px 10px; font-size: .82rem; font-weight: 500;
      white-space: nowrap; flex-shrink: 0;
    }
    .main-content { margin-left: 260px; padding-top: 64px; }

    /* STAT CARDS */
    .stat-card {
      border: none; border-radius: 16px;
      box-shadow: 0 2px 16px rgba(107,125,97,0.10);
      transition: transform 0.15s, box-shadow 0.15s;
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 6px 24px rgba(107,125,97,0.18); }
    .stat-card .card-body { padding: 20px 22px; }
    .stat-icon {
      width: 54px; height: 54px; border-radius: 14px;
      display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0;
    }
    .stat-icon.blue   { background: #e8ede5; color: #6b7d61; }
    .stat-icon.orange { background: #fef3c7; color: #d97706; }
    .stat-icon.green  { background: #d1fae5; color: #059669; }
    .stat-icon.teal   { background: #e2e8df; color: #6b7d61; }
    .stat-num  { font-size: 2rem; font-weight: 800; line-height: 1; color: #1e2d1a; }
    .stat-label { font-size: .82rem; color: #7a8c72; margin-top: 4px; font-weight: 500; }

    /* PAGE TITLE */
    .page-title { font-size: 1.25rem; font-weight: 700; color: #2d3d28; margin-bottom: 20px; }
    .page-title span { font-size: .82rem; font-weight: 400; color: #9aab90; margin-left: 8px; }

    /* CARDS */
    .content-card { border: none; border-radius: 16px; box-shadow: 0 2px 16px rgba(107,125,97,0.09); overflow: hidden; }
    .content-card .card-header {
      background: #fff; border-bottom: 1px solid #eaf0e6;
      padding: 16px 22px; font-weight: 700; color: #2d3d28; font-size: .95rem;
      display: flex; align-items: center; justify-content: space-between;
    }

    /* TABLE */
    .table thead th {
      background: #f4f7f2; color: #6b7d61; font-size: .75rem;
      text-transform: uppercase; letter-spacing: .7px; border: none; padding: 13px 18px; font-weight: 600;
    }
    .table tbody td { padding: 13px 18px; vertical-align: middle; border-color: #f4f7f2; font-size: .9rem; }
    .table tbody tr:hover { background: #f8faf6; }
    .queue-num { color: var(--clinic-blue); font-weight: 800; font-family: monospace; font-size: 1rem; }

    /* BUTTONS */
    .btn-clinic {
      background: linear-gradient(135deg, #6b7d61 0%, #8a9e7e 100%);
      border: none; color: #fff; font-weight: 600; border-radius: 8px;
    }
    .btn-clinic:hover {
      background: linear-gradient(135deg, #4a5e42 0%, #6b7d61 100%);
      color: #fff; box-shadow: 0 4px 12px rgba(107,125,97,0.35);
    }

    /* FORM CONTROLS */
    .form-control:focus, .form-select:focus {
      border-color: var(--clinic-blue); box-shadow: 0 0 0 3px rgba(138,158,126,0.18);
    }
    .form-select-sm { border-radius: 6px; font-size: .82rem; }

    /* BREADCRUMB */
    .breadcrumb-item.active { color: var(--clinic-blue); font-weight: 500; }

    @media(max-width:991px){
      .sidebar { transform: translateX(-100%); transition: transform .3s; }
      .sidebar.show { transform: translateX(0); }
      .main-content { margin-left: 0; }
      .topbar { left: 0; }
    }  </style>
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
<nav class="navbar topbar px-4 position-fixed d-flex align-items-center justify-content-between w-100">
  <div class="d-flex align-items-center gap-2">
    <button class="btn btn-sm btn-outline-secondary d-lg-none" id="sidebarToggle"><i class="bi bi-list"></i></button>
    <ol class="breadcrumb mb-0">
      <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>" class="text-decoration-none"><i class="bi bi-house-fill"></i></a></li>
      <li class="breadcrumb-item active fw-semibold"><?= ucfirst($page) ?></li>
    </ol>
  </div>
  <div class="d-flex align-items-center gap-2">
    <span class="date-badge"><i class="bi bi-calendar3 me-1"></i><?= date('M d, Y') ?></span>
    <a href="<?= base_url('admin/appointments') ?>" class="btn btn-sm btn-clinic px-3">
      <i class="bi bi-plus-lg me-1"></i> New Appointment
    </a>
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

    <div class="page-title"><i class="bi bi-grid-1x2-fill me-2"></i>Dashboard <span>Overview for today</span></div>

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
            <?php $s = $a['status'] ?? 'pending'; $b = match($s) { 'confirmed'=>'primary','completed'=>'success','cancelled'=>'danger','serving'=>'info', default=>'warning' }; ?>
            <tr>
              <td><span class="queue-num">#<?= esc($a['queue_number'] ?? '-') ?></span></td>
              <td class="fw-semibold"><?= esc($a['patient_name'] ?? $a['name'] ?? '-') ?></td>
              <td class="text-muted"><?= esc($a['service_name'] ?? '-') ?></td>              <td class="text-muted"><?= esc($a['appointment_date'] ?? '-') ?></td>
              <td><span class="badge bg-<?= $b ?> rounded-pill px-3"><?= ucfirst($s) ?></span></td>
              <td>
                <form method="POST" action="<?= base_url('admin/appointments/update') ?>" class="d-inline">
                  <?= csrf_field() ?>
                  <input type="hidden" name="id" value="<?= $a['id'] ?>">
                  <select name="status" class="form-select form-select-sm d-inline w-auto" onchange="this.form.submit()">
                    <?php foreach (['pending','confirmed','serving','completed','cancelled'] as $opt): ?>
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

    <div class="page-title"><i class="bi bi-calendar-check me-2"></i>Appointments <span>All records</span></div>
    <div class="card content-card">
      <div class="card-header"><i class="bi bi-calendar-check me-2 text-muted"></i>All Appointments</div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table mb-0">
            <thead><tr><th>Queue #</th><th>Patient</th><th>Service</th><th>Date</th><th>Status</th><th>Action</th></tr></thead>
            <tbody>
            <?php foreach ($appointments ?? [] as $a): ?>
            <?php $s = $a['status'] ?? 'pending'; $b = match($s) { 'confirmed'=>'primary','completed'=>'success','cancelled'=>'danger','serving'=>'info', default=>'warning' }; ?>
            <tr>
              <td><span class="queue-num">#<?= esc($a['queue_number'] ?? '-') ?></span></td>
              <td class="fw-medium"><?= esc($a['patient_name'] ?? $a['name'] ?? '-') ?></td>
              <td class="text-muted"><?= esc($a['service_name'] ?? '-') ?></td>
              <td class="text-muted"><?= esc($a['appointment_date'] ?? '-') ?></td>
              <td><span class="badge bg-<?= $b ?> rounded-pill"><?= ucfirst($s) ?></span></td>
              <td>
                <form method="POST" action="<?= base_url('admin/appointments/update') ?>" class="d-inline">
                  <?= csrf_field() ?>
                  <input type="hidden" name="id" value="<?= $a['id'] ?>">
                  <select name="status" class="form-select form-select-sm d-inline w-auto" onchange="this.form.submit()">
                    <?php foreach (['pending','confirmed','serving','completed','cancelled'] as $opt): ?>
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

    <div class="page-title"><i class="bi bi-ticket-perforated me-2"></i>Queue Monitor <span>Live queue status</span></div>
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

    <div class="page-title"><i class="bi bi-clipboard2-pulse me-2"></i>Services <span>Manage clinic services</span></div>

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
