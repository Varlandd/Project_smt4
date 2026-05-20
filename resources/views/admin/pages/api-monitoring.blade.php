@extends('admin.layouts.admin')
@section('title', 'API Monitoring — RumahKu')
@section('page-title', 'API Monitoring')

@push('styles')
<style>
/* ── Summary Strip ── */
.api-summary{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:28px}
.api-sum-card{background:#fff;border-radius:14px;padding:20px 22px;box-shadow:0 1px 3px rgba(0,0,0,.06);display:flex;align-items:center;gap:14px}
.api-sum-icon{width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.4rem;flex-shrink:0}
.api-sum-val{font-size:1.5rem;font-weight:800;color:#0f172a;line-height:1.2}
.api-sum-lbl{font-size:.75rem;color:#64748b;margin-top:2px}

/* ── Toolbar ── */
.api-toolbar{display:flex;align-items:center;gap:12px;margin-bottom:24px;flex-wrap:wrap}
.api-toolbar .btn-test-all{background:linear-gradient(135deg,#0f766e,#14b8a6);color:#fff;border:none;padding:10px 22px;border-radius:12px;font-weight:700;font-size:.88rem;cursor:pointer;display:flex;align-items:center;gap:8px;transition:all .2s}
.api-toolbar .btn-test-all:hover{transform:translateY(-2px);box-shadow:0 6px 20px rgba(15,118,110,.35)}
.api-toolbar .btn-test-all:disabled{opacity:.6;cursor:not-allowed;transform:none}
.filter-tabs{display:flex;gap:6px;margin-left:auto}
.filter-tab{padding:7px 16px;border-radius:8px;border:1px solid #e2e8f0;background:#f8fafc;font-size:.8rem;font-weight:600;cursor:pointer;color:#64748b;transition:all .2s}
.filter-tab.active,.filter-tab:hover{background:#0f766e;color:#fff;border-color:#0f766e}

/* ── Category ── */
.api-category{margin-bottom:28px}
.cat-header{display:flex;align-items:center;gap:10px;margin-bottom:14px;cursor:pointer;user-select:none}
.cat-header h3{font-size:1.05rem;font-weight:700;color:#1e293b;margin:0}
.cat-badge{font-size:.7rem;font-weight:700;padding:3px 10px;border-radius:6px}
.cat-badge.public{background:#dcfce7;color:#15803d}
.cat-badge.protected{background:#fef3c7;color:#92400e}
.cat-chevron{margin-left:auto;transition:transform .2s;color:#94a3b8}
.cat-header.collapsed .cat-chevron{transform:rotate(-90deg)}

/* ── Endpoint Card ── */
.ep-grid{display:flex;flex-direction:column;gap:10px}
.ep-card{background:#fff;border-radius:12px;border:1px solid #e2e8f0;overflow:hidden;transition:all .2s}
.ep-card:hover{box-shadow:0 4px 16px rgba(0,0,0,.06)}
.ep-row{display:flex;align-items:center;gap:12px;padding:14px 18px;cursor:pointer}
.ep-method{padding:4px 10px;border-radius:6px;font-size:.7rem;font-weight:800;text-transform:uppercase;min-width:54px;text-align:center;letter-spacing:.03em;flex-shrink:0}
.m-get{background:#dcfce7;color:#15803d}.m-post{background:#dbeafe;color:#1e40af}
.m-put{background:#fef3c7;color:#92400e}.m-delete{background:#fce7f3;color:#be185d}
.ep-path{font-family:'Courier New',monospace;font-size:.85rem;font-weight:600;color:#334155}
.ep-desc{font-size:.78rem;color:#94a3b8;margin-left:auto;flex-shrink:0;max-width:200px;text-align:right}
.ep-status{width:10px;height:10px;border-radius:50%;flex-shrink:0;background:#cbd5e1}
.ep-status.ok{background:#4ade80;box-shadow:0 0 6px rgba(74,222,128,.5)}
.ep-status.err{background:#f87171;box-shadow:0 0 6px rgba(248,113,113,.5)}
.ep-status.loading{background:#fbbf24;animation:pulse 1s infinite}
@keyframes pulse{0%,100%{opacity:1}50%{opacity:.4}}
.ep-btn{padding:6px 16px;border-radius:8px;border:1px solid #0f766e;background:transparent;color:#0f766e;font-size:.78rem;font-weight:700;cursor:pointer;transition:all .15s;flex-shrink:0}
.ep-btn:hover{background:#0f766e;color:#fff}
.ep-btn:disabled{opacity:.5;cursor:not-allowed}
.ep-time{font-size:.75rem;color:#94a3b8;font-weight:600;min-width:50px;text-align:right;flex-shrink:0}

/* ── Detail Panel ── */
.ep-detail{display:none;border-top:1px solid #f1f5f9;padding:16px 18px;background:#f8fafc}
.ep-detail.open{display:block}
.ep-detail-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.ep-panel{display:flex;flex-direction:column}
.ep-panel-label{font-size:.72rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:6px}
.ep-textarea{width:100%;min-height:100px;border:1px solid #e2e8f0;border-radius:8px;padding:10px 12px;font-family:'Courier New',monospace;font-size:.8rem;resize:vertical;background:#fff;color:#334155;box-sizing:border-box}
.ep-response{width:100%;min-height:100px;max-height:300px;overflow:auto;border:1px solid #e2e8f0;border-radius:8px;padding:10px 12px;font-family:'Courier New',monospace;font-size:.78rem;background:#0f172a;color:#4ade80;white-space:pre-wrap;word-break:break-all;box-sizing:border-box}
.ep-meta{display:flex;gap:12px;margin-top:8px;flex-wrap:wrap}
.ep-meta-tag{font-size:.72rem;padding:3px 10px;border-radius:6px;font-weight:700}
.tag-code{background:#dbeafe;color:#1e40af}
.tag-code.c2xx{background:#dcfce7;color:#15803d}
.tag-code.c4xx{background:#fef3c7;color:#92400e}
.tag-code.c5xx{background:#fce7f3;color:#be185d}
.tag-code.c0{background:#f1f5f9;color:#64748b}
.tag-time{background:#ede9fe;color:#6d28d9}

/* ── Progress bar ── */
.bulk-progress{display:none;margin-bottom:20px}
.bulk-progress.active{display:block}
.bp-bar-wrap{background:#e2e8f0;border-radius:8px;height:8px;overflow:hidden;margin-bottom:8px}
.bp-bar{height:100%;background:linear-gradient(90deg,#0f766e,#14b8a6);border-radius:8px;transition:width .3s;width:0}
.bp-text{font-size:.78rem;color:#64748b;font-weight:600}

/* ── Dark Mode ── */
.dark-mode .api-sum-card{background:#1e293b;box-shadow:0 1px 3px rgba(0,0,0,.3)}
.dark-mode .api-sum-val{color:#f1f5f9}
.dark-mode .ep-card{background:#1e293b;border-color:#334155}
.dark-mode .ep-path{color:#e2e8f0}
.dark-mode .ep-detail{background:#0f172a;border-top-color:#334155}
.dark-mode .ep-textarea{background:#1e293b;border-color:#334155;color:#e2e8f0}
.dark-mode .cat-header h3{color:#f1f5f9}
.dark-mode .filter-tab{background:#0f172a;border-color:#334155;color:#94a3b8}
.dark-mode .filter-tab.active,.dark-mode .filter-tab:hover{background:#0f766e;color:#fff;border-color:#0f766e}
.dark-mode .bp-bar-wrap{background:#334155}

@media(max-width:968px){.api-summary{grid-template-columns:1fr 1fr}.ep-detail-grid{grid-template-columns:1fr}.ep-desc{display:none}}
@media(max-width:600px){.api-summary{grid-template-columns:1fr}.ep-row{flex-wrap:wrap}.filter-tabs{margin-left:0;width:100%}}
</style>
@endpush

@section('content')

{{-- Summary Cards --}}
<div class="api-summary">
    <div class="api-sum-card">
        <div class="api-sum-icon" style="background:#dcfce7">🔗</div>
        <div><div class="api-sum-val" id="sumTotal">19</div><div class="api-sum-lbl">Total Endpoint</div></div>
    </div>
    <div class="api-sum-card">
        <div class="api-sum-icon" style="background:#dbeafe">✅</div>
        <div><div class="api-sum-val" id="sumOk">—</div><div class="api-sum-lbl">Online / Success</div></div>
    </div>
    <div class="api-sum-card">
        <div class="api-sum-icon" style="background:#fce7f3">❌</div>
        <div><div class="api-sum-val" id="sumErr">—</div><div class="api-sum-lbl">Error / Offline</div></div>
    </div>
    <div class="api-sum-card">
        <div class="api-sum-icon" style="background:#fef3c7">⚡</div>
        <div><div class="api-sum-val" id="sumAvg">—</div><div class="api-sum-lbl">Avg Response (ms)</div></div>
    </div>
</div>

{{-- Toolbar --}}
<div class="api-toolbar">
    <button class="btn-test-all" id="btnTestAll" onclick="bulkTestAll()">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"/></svg>
        Test Semua API
    </button>
    <div class="filter-tabs">
        <button class="filter-tab active" onclick="filterCat('all',this)">Semua</button>
        <button class="filter-tab" onclick="filterCat('public',this)">🔓 Public</button>
        <button class="filter-tab" onclick="filterCat('protected',this)">🔒 Protected</button>
    </div>
</div>

{{-- Bulk Progress --}}
<div class="bulk-progress" id="bulkProgress">
    <div class="bp-bar-wrap"><div class="bp-bar" id="bpBar"></div></div>
    <div class="bp-text" id="bpText">Testing 0/19...</div>
</div>

{{-- ═══ PUBLIC ENDPOINTS ═══ --}}
<div class="api-category" data-cat="public">
    <div class="cat-header" onclick="toggleCat(this)">
        <span style="font-size:1.2rem">🔓</span>
        <h3>Mobile API — Public</h3>
        <span class="cat-badge public">11 Endpoints</span>
        <svg class="cat-chevron" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
    </div>
    <div class="ep-grid" id="publicGrid">
        @php
        $publicEndpoints = [
            ['POST','/api/register','Registrasi user baru','{"name":"Test User","email":"testmon@example.com","password":"password123","password_confirmation":"password123"}'],
            ['POST','/api/login','Login user','{"email":"testmon@example.com","password":"password123"}'],
            ['GET','/api/stats','Statistik rumah',''],
            ['GET','/api/lokasi','Daftar lokasi',''],
            ['GET','/api/fasilitas','Daftar fasilitas',''],
            ['GET','/api/image-proxy','Image proxy (CORS)',''],
            ['GET','/api/rumah','List semua rumah',''],
            ['GET','/api/rumah/1','Detail rumah (ID sample)',''],
            ['POST','/api/predict','Prediksi harga (ML)','{"luas_tanah":100,"luas_bangunan":80,"kamar_tidur":3,"kamar_mandi":2,"lokasi":"Jakarta Selatan"}'],
            ['POST','/api/recommend','Rekomendasi TOPSIS','{"lokasi":"","budget_max":0,"w_harga":3,"w_tanah":3,"w_bangunan":3,"w_kamar":3}'],
            ['POST','/api/kalkulator','Kalkulator KPR','{"penghasilan":15000000,"uang_muka":100000000,"cicilan_lain":2000000,"tenor":20}'],
        ];
        @endphp
        @foreach($publicEndpoints as $i => $ep)
        <div class="ep-card" data-cat="public" data-idx="pub-{{ $i }}">
            <div class="ep-row" onclick="toggleDetail('pub-{{ $i }}')">
                <span class="ep-method m-{{ strtolower($ep[0]) }}">{{ $ep[0] }}</span>
                <span class="ep-path">{{ $ep[1] }}</span>
                <span class="ep-desc">{{ $ep[2] }}</span>
                <span class="ep-time" id="time-pub-{{ $i }}"></span>
                <span class="ep-status" id="status-pub-{{ $i }}"></span>
                <button class="ep-btn" id="btn-pub-{{ $i }}" onclick="event.stopPropagation();testSingle('pub-{{ $i }}')">Test</button>
            </div>
            <div class="ep-detail" id="detail-pub-{{ $i }}">
                <div class="ep-detail-grid">
                    <div class="ep-panel">
                        <span class="ep-panel-label">Request Body (JSON)</span>
                        <textarea class="ep-textarea" id="body-pub-{{ $i }}" placeholder="Tidak ada body (GET request)">{{ $ep[3] }}</textarea>
                    </div>
                    <div class="ep-panel">
                        <span class="ep-panel-label">Response</span>
                        <div class="ep-response" id="resp-pub-{{ $i }}">Belum ditest...</div>
                        <div class="ep-meta" id="meta-pub-{{ $i }}"></div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- ═══ PROTECTED ENDPOINTS ═══ --}}
<div class="api-category" data-cat="protected">
    <div class="cat-header" onclick="toggleCat(this)">
        <span style="font-size:1.2rem">🔒</span>
        <h3>Mobile API — Protected (Sanctum)</h3>
        <span class="cat-badge protected">8 Endpoints</span>
        <svg class="cat-chevron" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
    </div>
    <div class="ep-grid" id="protectedGrid">
        @php
        $protectedEndpoints = [
            ['POST','/api/logout','Logout user',''],
            ['POST','/api/rumah/search','Pencarian rumah','{"lokasi":"Jakarta"}'],
            ['GET','/api/favorit','List favorit user',''],
            ['POST','/api/favorit/1','Tambah favorit (ID sample)',''],
            ['DELETE','/api/favorit/1','Hapus favorit (ID sample)',''],
            ['GET','/api/user','Profil user',''],
            ['PUT','/api/user/profile','Update profil','{"name":"Admin Test","email":"admin@rumahku.com","phone":"08123456789"}'],
            ['PUT','/api/user/password','Update password','{"current_password":"password","password":"password","password_confirmation":"password"}'],
        ];
        @endphp
        @foreach($protectedEndpoints as $i => $ep)
        <div class="ep-card" data-cat="protected" data-idx="prot-{{ $i }}">
            <div class="ep-row" onclick="toggleDetail('prot-{{ $i }}')">
                <span class="ep-method m-{{ strtolower($ep[0]) }}">{{ $ep[0] }}</span>
                <span class="ep-path">{{ $ep[1] }}</span>
                <span class="ep-desc">{{ $ep[2] }}</span>
                <span class="ep-time" id="time-prot-{{ $i }}"></span>
                <span class="ep-status" id="status-prot-{{ $i }}"></span>
                <button class="ep-btn" id="btn-prot-{{ $i }}" onclick="event.stopPropagation();testSingle('prot-{{ $i }}')">Test</button>
            </div>
            <div class="ep-detail" id="detail-prot-{{ $i }}">
                <div class="ep-detail-grid">
                    <div class="ep-panel">
                        <span class="ep-panel-label">Request Body (JSON)</span>
                        <textarea class="ep-textarea" id="body-prot-{{ $i }}" placeholder="Tidak ada body">{{ $ep[3] }}</textarea>
                    </div>
                    <div class="ep-panel">
                        <span class="ep-panel-label">Response</span>
                        <div class="ep-response" id="resp-prot-{{ $i }}">Belum ditest...</div>
                        <div class="ep-meta" id="meta-prot-{{ $i }}"></div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@endsection

@push('scripts')
<script>
const BASE = "{{ url('/') }}";
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
const TEST_URL = "{{ route('admin.api-monitoring.test') }}";

// All endpoints registry
const endpoints = [
    @foreach($publicEndpoints as $i => $ep)
    {idx:'pub-{{ $i }}',method:'{{ $ep[0] }}',path:'{{ $ep[1] }}',desc:'{{ $ep[2] }}'},
    @endforeach
    @foreach($protectedEndpoints as $i => $ep)
    {idx:'prot-{{ $i }}',method:'{{ $ep[0] }}',path:'{{ $ep[1] }}',desc:'{{ $ep[2] }}'},
    @endforeach
];

let results = {};

function toggleCat(el) {
    el.classList.toggle('collapsed');
    const grid = el.nextElementSibling;
    grid.style.display = el.classList.contains('collapsed') ? 'none' : 'flex';
}

function toggleDetail(idx) {
    document.getElementById('detail-' + idx).classList.toggle('open');
}

function filterCat(cat, btn) {
    document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('.api-category').forEach(c => {
        if (cat === 'all') { c.style.display = ''; }
        else { c.style.display = c.dataset.cat === cat ? '' : 'none'; }
    });
}

async function testSingle(idx) {
    const ep = endpoints.find(e => e.idx === idx);
    if (!ep) return;

    const btn = document.getElementById('btn-' + idx);
    const statusEl = document.getElementById('status-' + idx);
    const timeEl = document.getElementById('time-' + idx);
    const respEl = document.getElementById('resp-' + idx);
    const metaEl = document.getElementById('meta-' + idx);
    const bodyEl = document.getElementById('body-' + idx);
    const detailEl = document.getElementById('detail-' + idx);

    // Open detail panel
    detailEl.classList.add('open');

    // Set loading
    btn.disabled = true;
    btn.textContent = '...';
    statusEl.className = 'ep-status loading';
    respEl.textContent = 'Mengirim request...';
    metaEl.innerHTML = '';

    // Parse body
    let body = {};
    const raw = bodyEl.value.trim();
    if (raw) {
        try { body = JSON.parse(raw); } catch(e) {
            respEl.textContent = '❌ JSON body tidak valid: ' + e.message;
            statusEl.className = 'ep-status err';
            btn.disabled = false; btn.textContent = 'Test';
            return;
        }
    }

    try {
        const res = await fetch(TEST_URL, {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'},
            body: JSON.stringify({
                method: ep.method,
                path: ep.path,
                body: body,
            })
        });

        const data = await res.json();
        const code = data.status_code || 0;
        const time = data.response_time || 0;

        // Update status
        statusEl.className = 'ep-status ' + (code >= 200 && code < 400 ? 'ok' : 'err');
        timeEl.textContent = time + 'ms';

        // Response body
        const bodyStr = typeof data.response_body === 'object'
            ? JSON.stringify(data.response_body, null, 2)
            : String(data.response_body);
        respEl.textContent = bodyStr;

        // Meta tags
        const codeClass = code >= 200 && code < 300 ? 'c2xx' : code >= 400 && code < 500 ? 'c4xx' : code >= 500 ? 'c5xx' : 'c0';
        metaEl.innerHTML = `
            <span class="ep-meta-tag tag-code ${codeClass}">Status: ${code}</span>
            <span class="ep-meta-tag tag-time">⚡ ${time}ms</span>
        `;

        results[idx] = { code, time, success: code >= 200 && code < 400 };
    } catch(e) {
        statusEl.className = 'ep-status err';
        respEl.textContent = '❌ Fetch error: ' + e.message;
        results[idx] = { code: 0, time: 0, success: false };
    }

    btn.disabled = false;
    btn.textContent = 'Test';
    updateSummary();
}

async function bulkTestAll() {
    const btn = document.getElementById('btnTestAll');
    const progress = document.getElementById('bulkProgress');
    const bar = document.getElementById('bpBar');
    const text = document.getElementById('bpText');

    btn.disabled = true;
    progress.classList.add('active');

    const total = endpoints.length;
    let done = 0;

    for (const ep of endpoints) {
        text.textContent = `Testing ${done + 1}/${total} — ${ep.method} ${ep.path}`;
        bar.style.width = ((done / total) * 100) + '%';
        await testSingle(ep.idx);
        done++;
    }

    bar.style.width = '100%';
    text.textContent = `✅ Selesai! ${done}/${total} endpoint telah ditest.`;
    btn.disabled = false;

    setTimeout(() => { progress.classList.remove('active'); }, 5000);
}

function updateSummary() {
    const vals = Object.values(results);
    const ok = vals.filter(v => v.success).length;
    const err = vals.filter(v => !v.success).length;
    const times = vals.filter(v => v.time > 0).map(v => v.time);
    const avg = times.length ? Math.round(times.reduce((a,b) => a+b, 0) / times.length) : 0;

    document.getElementById('sumOk').textContent = ok;
    document.getElementById('sumErr').textContent = err;
    document.getElementById('sumAvg').textContent = avg ? avg + 'ms' : '—';
}
</script>
@endpush
