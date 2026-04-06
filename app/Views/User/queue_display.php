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
    .now-serving-banner {
      background: linear-gradient(135deg, #dbeafe, #eff6ff);
      border-radius: 1.5rem;
      border: 2px solid #bfdbfe;
    }
    .queue-number-big {
      font-size: 8rem;
      font-weight: 900;
      color: #1e3a5f;
      line-height: 1;
    }
    .back-btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 8px 18px;
      font-size: .875rem;
      font-weight: 600;
      color: #334155;
      background: #f8fafc;
      border: 1.5px solid #cbd5e1;
      border-radius: 12px;
      text-decoration: none;
      transition: all .2s ease;
      box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    }
    .back-btn:hover {
      background: #e2e8f0;
      border-color: #94a3b8;
      color: #1e293b;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      transform: translateX(-2px);
    }
    .back-btn i { font-size: 1.1rem; }
  </style>
</head>
<body>

<nav class="navbar navbar-light bg-white border-bottom shadow-sm">
  <div class="container">
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

<div class="container py-4">

  <!-- NOW SERVING BANNER -->
  <div class="now-serving-banner p-4 mb-4">
    <div class="d-flex align-items-center gap-2 mb-2">
      <span class="badge bg-danger pulse">● LIVE</span>
      <span class="fw-semibold text-muted small text-uppercase">Now Serving</span>
    </div>
    <div class="row align-items-center">
      <div class="col">
        <div class="queue-number-big" id="serving-number">
          <span class="text-muted fs-1">— No Active Queue —</span>
        </div>
        <div class="text-muted mt-1" id="serving-name"></div>
      </div>
      <div class="col-auto text-end">
        <div class="d-flex gap-4">
          <div class="text-center">
            <div class="fs-3 fw-bold text-warning" id="waiting-count">0</div>
            <small class="text-muted">Waiting</small>
          </div>
          <div class="text-center">
            <div class="fs-3 fw-bold text-success" id="serving-count">0</div>
            <small class="text-muted">Serving</small>
          </div>
          <div class="text-center">
            <div class="fs-3 fw-bold text-secondary" id="completed-count">0</div>
            <small class="text-muted">Completed</small>
          </div>
        </div>
      </div>
    </div>
  </div>

  <p class="text-center text-muted small mt-3">
    <i class="bi bi-arrow-repeat pulse"></i> Auto-refreshes every 10 seconds
  </p>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function fetchLive() {
  fetch('<?= base_url('api/queue-live') ?>')
    .then(r => r.json())
    .then(data => {
      const servingNum  = document.getElementById('serving-number');
      const servingName = document.getElementById('serving-name');

      if (data.serving) {
        servingNum.textContent  = data.serving.queue_number;
        servingName.textContent = data.serving.patient_name;
      } else {
        servingNum.innerHTML    = '<span class="text-muted fs-1">— No Active Queue —</span>';
        servingName.textContent = '';
      }

      document.getElementById('waiting-count').textContent   = data.waiting   ?? 0;
      document.getElementById('serving-count').textContent   = data.serving   ? 1 : 0;
      document.getElementById('completed-count').textContent = data.completed ?? 0;
      document.getElementById('last-updated').textContent    = 'Updated: ' + new Date().toLocaleTimeString();
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