<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Your Queue Ticket | QueueMed</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"/>
  <style>
    body { background: #f0f4f8; }
    .ticket-card { border-radius: 1.5rem; max-width: 400px; margin: 0 auto; }
    .queue-number {
      font-size: 7rem;
      font-weight: 900;
      line-height: 1;
      color: #1e3a8a;
      letter-spacing: -2px;
    }
    .tear-line {
      border: none;
      border-top: 2px dashed #cbd5e1;
      margin: 0;
    }
  </style>
</head>
<body>

<nav class="navbar bg-white border-bottom shadow-sm">
  <div class="container">
    <span class="navbar-brand fw-bold text-primary">
      <i class="bi bi-clipboard2-pulse"></i> QueueMed
    </span>
  </div>
</nav>

<div class="container py-5">

  <div class="ticket-card card border-0 shadow">

    <!-- Header -->
    <div class="card-header bg-primary text-white text-center py-4 border-0" style="border-radius:1.5rem 1.5rem 0 0;">
      <i class="bi bi-ticket-perforated-fill fs-3 mb-1 d-block"></i>
      <div class="fw-bold fs-5">Queue Ticket</div>
      <div class="small opacity-75">QueueMed Clinic</div>
    </div>

    <!-- Queue Number -->
    <div class="card-body text-center py-4">
      <div class="text-muted small text-uppercase fw-semibold mb-1" style="letter-spacing:.08em;">Your Number</div>
      <div class="queue-number"><?= str_pad($ticket['queue_number'], 3, '0', STR_PAD_LEFT) ?></div>
      <div class="text-muted small mt-1"><?= esc($ticket['service_name']) ?></div>
    </div>

    <hr class="tear-line mx-3"/>

    <!-- Details -->
    <div class="card-body py-3">
      <div class="row g-2 text-center">
        <div class="col-6">
          <div class="small text-muted">Name</div>
          <div class="fw-semibold small"><?= esc($ticket['patient_name']) ?></div>
        </div>
        <div class="col-6">
          <div class="small text-muted">Date</div>
          <div class="fw-semibold small"><?= date('M d, Y', strtotime($ticket['date'])) ?></div>
        </div>
        <div class="col-6">
          <div class="small text-muted">Time Issued</div>
          <div class="fw-semibold small"><?= date('h:i A', strtotime($ticket['created_at'])) ?></div>
        </div>
        <div class="col-6">
          <div class="small text-muted">Status</div>
          <span class="badge bg-warning text-dark">Waiting</span>
        </div>
      </div>
    </div>

    <hr class="tear-line mx-3"/>

    <div class="card-body text-center py-3">
      <p class="text-muted small mb-3">
        <i class="bi bi-info-circle"></i>
        Please watch the <strong>Now Serving</strong> screen and wait for your number to be called.
      </p>
      <a href="<?= base_url('/') ?>" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-plus-circle me-1"></i> Get Another Number
      </a>
    </div>

  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>