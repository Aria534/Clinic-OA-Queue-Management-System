<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Now Serving | QueueMed</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet"/>
  <style>
    :root {
      --bg: #0a0f1e;
      --panel: #111827;
      --border: #1e2d45;
      --blue: #2563eb;
      --blue-glow: rgba(37,99,235,0.3);
      --green: #10b981;
      --green-glow: rgba(16,185,129,0.25);
      --text: #f1f5f9;
      --muted: #64748b;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      background: var(--bg);
      color: var(--text);
      font-family: 'DM Sans', sans-serif;
      min-height: 100vh;
      display: grid;
      grid-template-rows: auto 1fr auto;
      overflow: hidden;
    }

    /* ── HEADER ── */
    header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 1.25rem 2.5rem;
      border-bottom: 1px solid var(--border);
      background: var(--panel);
    }
    .brand {
      display: flex; align-items: center; gap: 12px;
    }
    .brand-icon {
      width: 40px; height: 40px;
      background: var(--blue);
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.2rem;
    }
    .brand-name { font-family: 'Bebas Neue', sans-serif; font-size: 1.6rem; letter-spacing: 2px; color: var(--text); }
    .brand-sub  { font-size: .75rem; color: var(--muted); margin-top: -2px; }

    .clock { text-align: right; }
    .clock-time { font-family: 'Bebas Neue', sans-serif; font-size: 2rem; letter-spacing: 3px; color: var(--text); }
    .clock-date { font-size: .75rem; color: var(--muted); }

    /* ── MAIN ── */
    main {
      display: grid;
      grid-template-columns: 1fr 380px;
      gap: 0;
      height: 100%;
    }

    /* NOW SERVING */
    .now-serving {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 3rem;
      position: relative;
      overflow: hidden;
    }
    .now-serving::before {
      content: '';
      position: absolute;
      width: 600px; height: 600px;
      background: radial-gradient(circle, var(--blue-glow) 0%, transparent 70%);
      top: 50%; left: 50%;
      transform: translate(-50%, -50%);
      pointer-events: none;
    }

    .ns-label {
      font-size: .85rem;
      font-weight: 600;
      letter-spacing: .2em;
      text-transform: uppercase;
      color: var(--blue);
      margin-bottom: 1rem;
      display: flex; align-items: center; gap: 8px;
    }
    .ns-label .dot {
      width: 8px; height: 8px;
      background: var(--blue);
      border-radius: 50%;
      animation: blink 1.5s infinite;
    }
    @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.2} }

    .ns-number {
      font-family: 'Bebas Neue', sans-serif;
      font-size: clamp(8rem, 22vw, 18rem);
      line-height: 1;
      color: #fff;
      letter-spacing: -4px;
      text-shadow: 0 0 80px var(--blue-glow);
      transition: all .4s ease;
    }
    .ns-number.empty { color: var(--muted); font-size: clamp(3rem, 8vw, 6rem); letter-spacing: 2px; }

    .ns-patient {
      margin-top: 1rem;
      font-size: 1.4rem;
      font-weight: 500;
      color: var(--text);
      opacity: .85;
    }
    .ns-service {
      margin-top: .4rem;
      font-size: 1rem;
      color: var(--muted);
      background: var(--panel);
      border: 1px solid var(--border);
      padding: .3rem 1rem;
      border-radius: 50px;
    }

    /* ── QUEUE SIDEBAR ── */
    .queue-sidebar {
      border-left: 1px solid var(--border);
      background: var(--panel);
      display: flex;
      flex-direction: column;
      overflow: hidden;
    }
    .qs-header {
      padding: 1.25rem 1.5rem;
      border-bottom: 1px solid var(--border);
      display: flex; align-items: center; justify-content: space-between;
    }
    .qs-title { font-size: .8rem; font-weight: 600; letter-spacing: .15em; text-transform: uppercase; color: var(--muted); }
    .qs-count {
      background: var(--blue);
      color: #fff;
      font-size: .75rem;
      font-weight: 600;
      padding: .2rem .6rem;
      border-radius: 50px;
    }

    .qs-list {
      flex: 1;
      overflow-y: auto;
      padding: .5rem 0;
    }
    .qs-list::-webkit-scrollbar { width: 4px; }
    .qs-list::-webkit-scrollbar-track { background: transparent; }
    .qs-list::-webkit-scrollbar-thumb { background: var(--border); border-radius: 2px; }

    .qs-item {
      display: flex;
      align-items: center;
      gap: 14px;
      padding: .85rem 1.5rem;
      border-bottom: 1px solid var(--border);
      transition: background .2s;
    }
    .qs-item:last-child { border-bottom: none; }
    .qs-pos { font-size: .75rem; color: var(--muted); width: 20px; text-align: center; }
    .qs-num {
      font-family: 'Bebas Neue', sans-serif;
      font-size: 1.6rem;
      color: var(--text);
      letter-spacing: 1px;
      min-width: 52px;
    }
    .qs-info { flex: 1; overflow: hidden; }
    .qs-name { font-size: .85rem; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .qs-svc  { font-size: .72rem; color: var(--muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    .qs-empty { padding: 2rem 1.5rem; text-align: center; color: var(--muted); font-size: .85rem; }

    /* ── FOOTER ── */
    footer {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: .85rem 2.5rem;
      border-top: 1px solid var(--border);
      background: var(--panel);
    }
    .stats { display: flex; gap: 2rem; }
    .stat-item { display: flex; flex-direction: column; align-items: center; }
    .stat-val  { font-family: 'Bebas Neue', sans-serif; font-size: 1.4rem; letter-spacing: 1px; }
    .stat-lbl  { font-size: .65rem; color: var(--muted); text-transform: uppercase; letter-spacing: .1em; }
    .stat-waiting   .stat-val { color: #f59e0b; }
    .stat-serving   .stat-val { color: var(--green); }
    .stat-completed .stat-val { color: #94a3b8; }

    .refresh-note { font-size: .72rem; color: var(--muted); display: flex; align-items: center; gap: 6px; }
    .spin { animation: spin 2s linear infinite; display: inline-block; }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* flash animation when number changes */
    @keyframes flashIn {
      0%   { transform: scale(1.15); opacity: 0; }
      100% { transform: scale(1);    opacity: 1; }
    }
    .flash { animation: flashIn .5s ease; }
  </style>
</head>
<body>

<header>
  <div class="brand">
    <div class="brand-icon">🏥</div>
    <div>
      <div class="brand-name">QueueMed</div>
      <div class="brand-sub">Queue Management System</div>
    </div>
  </div>
  <div class="clock">
    <div class="clock-time" id="clock-time">00:00:00</div>
    <div class="clock-date" id="clock-date"></div>
  </div>
</header>

<main>

  <!-- NOW SERVING -->
  <div class="now-serving">
    <div class="ns-label"><span class="dot"></span> Now Serving</div>
    <div class="ns-number" id="ns-number">—</div>
    <div class="ns-patient" id="ns-patient"></div>
    <div class="ns-service" id="ns-service" style="display:none;"></div>
  </div>

  <!-- WAITING QUEUE -->
  <div class="queue-sidebar">
    <div class="qs-header">
      <span class="qs-title">Up Next</span>
      <span class="qs-count" id="qs-count">0</span>
    </div>
    <div class="qs-list" id="qs-list">
      <div class="qs-empty">No one waiting</div>
    </div>
  </div>

</main>

<footer>
  <div class="stats">
    <div class="stat-item stat-waiting">
      <span class="stat-val" id="stat-waiting">0</span>
      <span class="stat-lbl">Waiting</span>
    </div>
    <div class="stat-item stat-serving">
      <span class="stat-val" id="stat-serving">0</span>
      <span class="stat-lbl">Serving</span>
    </div>
    <div class="stat-item stat-completed">
      <span class="stat-val" id="stat-completed">0</span>
      <span class="stat-lbl">Completed</span>
    </div>
  </div>
  <div class="refresh-note">
    <span class="spin">↻</span> Auto-refreshes every 5 seconds
  </div>
</footer>

<script>
// ── CLOCK ──
function updateClock() {
  const now  = new Date();
  const time = now.toLocaleTimeString('en-US', { hour12: true, hour: '2-digit', minute: '2-digit', second: '2-digit' });
  const date = now.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
  document.getElementById('clock-time').textContent = time;
  document.getElementById('clock-date').textContent = date;
}
setInterval(updateClock, 1000);
updateClock();

// ── QUEUE FETCH ──
let lastServing = null;

function pad(n) { return String(n).padStart(3, '0'); }

function fetchQueue() {
  fetch('<?= base_url('api/display') ?>')
    .then(r => r.json())
    .then(data => {
      const nsNum     = document.getElementById('ns-number');
      const nsPatient = document.getElementById('ns-patient');
      const nsService = document.getElementById('ns-service');

      // Now Serving
      if (data.serving) {
        const num = pad(data.serving.queue_number);
        if (num !== lastServing) {
          nsNum.classList.remove('flash');
          void nsNum.offsetWidth; // reflow
          nsNum.classList.add('flash');
          lastServing = num;
        }
        nsNum.textContent     = num;
        nsNum.classList.remove('empty');
        nsPatient.textContent = data.serving.patient_name;
        nsService.textContent = data.serving.service_name;
        nsService.style.display = 'inline-block';
      } else {
        nsNum.textContent     = '—';
        nsNum.classList.add('empty');
        nsPatient.textContent = '';
        nsService.style.display = 'none';
        lastServing = null;
      }

      // Waiting list
      const list    = document.getElementById('qs-list');
      const waiting = (data.queue || []).filter(q => q.status === 'waiting');

      document.getElementById('qs-count').textContent    = waiting.length;
      document.getElementById('stat-waiting').textContent   = data.stats?.waiting   ?? 0;
      document.getElementById('stat-serving').textContent   = data.stats?.serving   ?? 0;
      document.getElementById('stat-completed').textContent = data.stats?.completed ?? 0;

      if (waiting.length === 0) {
        list.innerHTML = '<div class="qs-empty">No one waiting</div>';
      } else {
        list.innerHTML = waiting.map((q, i) => `
          <div class="qs-item">
            <span class="qs-pos">${i + 1}</span>
            <span class="qs-num">${pad(q.queue_number)}</span>
            <div class="qs-info">
              <div class="qs-name">${q.patient_name}</div>
              <div class="qs-svc">${q.service_name ?? ''}</div>
            </div>
          </div>`).join('');
      }
    })
    .catch(() => {});
}

fetchQueue();
setInterval(fetchQueue, 5000);
</script>
</body>
</html>