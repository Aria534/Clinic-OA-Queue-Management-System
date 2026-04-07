<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Now Serving | QueueMed</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"/>
  <style>
    body { background: #f0f4f8; }
    .pulse { animation: pulse 2s infinite; }
    @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.5} }

    .dept-card {
      background: #fff;
      border-radius: 1.25rem;
      border: 1.5px solid #e2e8f0;
      overflow: hidden;
    }
    .dept-header {
      padding: 10px 16px;
      font-size: .75rem;
      font-weight: 600;
      letter-spacing: .06em;
      text-transform: uppercase;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .dept-header.blue   { background: #dbeafe; color: #1e40af; border-bottom: 1px solid #bfdbfe; }
    .dept-header.green  { background: #dcfce7; color: #166534; border-bottom: 1px solid #bbf7d0; }
    .dept-header.amber  { background: #fef9c3; color: #854d0e; border-bottom: 1px solid #fde047; }
    .dept-header.purple { background: #ede9fe; color: #5b21b6; border-bottom: 1px solid #ddd6fe; }
    .dept-header.pink   { background: #fce7f3; color: #9d174d; border-bottom: 1px solid #fbcfe8; }

    .queue-number-big {
      font-size: 4.5rem;
      font-weight: 900;
      color: #1e3a5f;
      line-height: 1;
    }
    .back-btn {
      display: inline-flex; align-items: center; gap: 6px;
      padding: 8px 18px; font-size: .875rem; font-weight: 600;
      color: #334155; background: #f8fafc;
      border: 1.5px solid #cbd5e1; border-radius: 12px;
      text-decoration: none; transition: all .2s ease;
    }
    .back-btn:hover {
      background: #e2e8f0; border-color: #94a3b8;
      color: #1e293b; transform: translateX(-2px);
    }
    .up-next-item {
      display: flex; align-items: center;
      justify-content: space-between;
      padding: 5px 0;
      border-bottom: 1px solid #f1f5f9;
      font-size: .8rem;
    }
    .up-next-item:last-child { border-bottom: none; }
    .no-queue { font-size: .9rem; color: #94a3b8; font-style: italic; }

    .colors { --colors: blue, green, amber, purple, pink; }
  </style>
</head>
<body>

<nav class="navbar navbar-light bg-white border-bottom shadow-sm">
  <div class="container-fluid px-4">
    <div class="d-flex align-items-center gap-3">
      <a href="<?= base_url('/') ?>" class="back-btn">
        <i class="bi bi-arrow-left"></i> Back
      </a>
      <a class="navbar-brand fw-bold text-primary mb-0" href="<?= base_url('/') ?>">
        <i class="bi bi-clipboard2-pulse"></i> QueueMed
      </a>
    </div>
    <span class="small text-muted">
      <i class="bi bi-clock-history pulse"></i>
      <span id="last-updated">Connecting...</span>
    </span>
  </div>
</nav>

<div class="container-fluid px-4 py-4">

  <div class="row g-3" id="dept-container">
    <!-- Department cards will be injected here by JS -->
    <div class="col-12 text-center text-muted py-5" id="loading-msg">
      <i class="bi bi-arrow-repeat pulse fs-3"></i>
      <p class="mt-2">Loading queue data...</p>
    </div>
  </div>

  <p class="text-center text-muted small mt-4">
    <i class="bi bi-arrow-repeat pulse"></i> Auto-refreshes every 10 seconds
  </p>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
const COLORS = ['blue', 'green', 'amber', 'purple', 'pink'];

function buildCard(dept, colorClass) {
  const servingHTML = dept.serving
    ? `<div class="queue-number-big">${dept.serving.queue_number}</div>
       <div class="text-muted mt-1">${dept.serving.patient_name}</div>`
    : `<div class="no-queue mt-2">— No Active Queue —</div>`;

  const upNextHTML = dept.up_next.length
    ? dept.up_next.map((p, i) => `
        <div class="up-next-item">
          <span class="fw-bold text-primary" style="font-family:monospace">${p.queue_number}</span>
          <span class="text-muted">${p.patient_name}</span>
          <span class="text-muted">#${i + 1}</span>
        </div>`).join('')
    : `<div class="text-muted small fst-italic">No one waiting</div>`;

  return `
    <div class="col-12 col-md-6 col-xl-4">
      <div class="dept-card h-100">
        <div class="dept-header ${colorClass}">
          <span>${dept.service_name}</span>
          <span class="badge bg-danger pulse" style="font-size:.7rem">● LIVE</span>
        </div>
        <div class="p-3">
          <div class="small text-muted text-uppercase fw-semibold mb-1" style="font-size:.7rem;letter-spacing:.07em">Now Serving</div>
          ${servingHTML}
        </div>
        <div class="d-flex border-top border-bottom text-center">
          <div class="flex-fill py-2">
            <div class="fs-4 fw-bold text-warning">${dept.waiting}</div>
            <div class="small text-muted">Waiting</div>
          </div>
          <div class="flex-fill py-2 border-start border-end">
            <div class="fs-4 fw-bold text-success">${dept.serving ? 1 : 0}</div>
            <div class="small text-muted">Serving</div>
          </div>
          <div class="flex-fill py-2">
            <div class="fs-4 fw-bold text-secondary">${dept.completed}</div>
            <div class="small text-muted">Completed</div>
          </div>
        </div>
        <div class="p-3">
          <div class="small text-muted text-uppercase fw-semibold mb-2" style="font-size:.7rem;letter-spacing:.07em">Up Next</div>
          ${upNextHTML}
        </div>
      </div>
    </div>`;
}

function fetchLive() {
  fetch('<?= base_url('api/queue-live') ?>')
    .then(r => r.json())
    .then(data => {
      const container = document.getElementById('dept-container');
      const loader    = document.getElementById('loading-msg');
      if (loader) loader.remove();

      if (!data.departments || data.departments.length === 0) {
        container.innerHTML = `
          <div class="col-12 text-center text-muted py-5">
            <i class="bi bi-inbox fs-1"></i>
            <p class="mt-2">No active queues today.</p>
          </div>`;
      } else {
        container.innerHTML = data.departments
          .map((dept, i) => buildCard(dept, COLORS[i % COLORS.length]))
          .join('');
      }

      document.getElementById('last-updated').textContent =
        'Updated: ' + new Date().toLocaleTimeString();
    })
    .catch(() => {
      document.getElementById('last-updated').textContent = 'Connection error. Retrying...';
    });
}

fetchLive();
setInterval(fetchLive, 10000);
</script>

</body>
</html>