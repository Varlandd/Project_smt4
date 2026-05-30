@extends('layouts.app')

@section('title', 'Rekomendasi Finansial — RumahKu')

@push('styles')
<style>
    .finansial-page {
        min-height: 100vh;
        padding: 6rem 2rem 4rem;
        background: linear-gradient(135deg, #f0fdfa 0%, #ffffff 50%, #ecfdf5 100%);
    }
    .finansial-container { max-width: 1200px; margin: 0 auto; }

    /* ── Hero Section ── */
    .finansial-hero {
        background: linear-gradient(135deg, #0d9488 0%, #0f766e 40%, #115e59 100%);
        border-radius: 24px;
        padding: 3rem;
        color: white;
        margin-bottom: 2.5rem;
        position: relative;
        overflow: hidden;
    }
    .finansial-hero::before {
        content: '';
        position: absolute;
        top: -60%;
        right: -15%;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(255,255,255,.08) 0%, transparent 70%);
        border-radius: 50%;
    }
    .finansial-hero::after {
        content: '';
        position: absolute;
        bottom: -40%;
        left: -10%;
        width: 350px;
        height: 350px;
        background: radial-gradient(circle, rgba(255,255,255,.06) 0%, transparent 70%);
        border-radius: 50%;
    }
    .finansial-hero-content { position: relative; z-index: 1; }
    .finansial-hero .breadcrumb {
        display: flex; align-items: center; gap: .5rem;
        font-size: .85rem; opacity: .75; margin-bottom: 1rem;
    }
    .finansial-hero .breadcrumb a { color: white; text-decoration: none; }
    .finansial-hero .breadcrumb a:hover { opacity: 1; text-decoration: underline; }
    .finansial-hero h1 { font-size: 2rem; font-weight: 800; margin-bottom: .6rem; }
    .finansial-hero p { opacity: .85; font-size: 1.05rem; max-width: 600px; line-height: 1.6; }

    /* ── Steps Indicator ── */
    .steps-bar {
        display: flex;
        justify-content: center;
        gap: 0;
        margin-bottom: 2.5rem;
    }
    .step-item {
        display: flex; align-items: center; gap: .6rem;
        padding: .8rem 1.5rem;
        background: white;
        border: 1.5px solid var(--border);
        font-size: .85rem;
        font-weight: 700;
        color: var(--text-soft);
        transition: all .3s;
    }
    .step-item:first-child { border-radius: 14px 0 0 14px; }
    .step-item:last-child { border-radius: 0 14px 14px 0; }
    .step-item .step-num {
        width: 28px; height: 28px;
        border-radius: 50%;
        background: var(--border);
        color: var(--text-soft);
        display: flex; align-items: center; justify-content: center;
        font-size: .8rem; font-weight: 800;
        transition: all .3s;
    }
    .step-item.active {
        background: var(--primary-light);
        border-color: var(--primary);
        color: var(--primary);
    }
    .step-item.active .step-num {
        background: var(--primary);
        color: white;
    }
    .step-item.done {
        background: #dcfce7;
        border-color: #16a34a;
        color: #16a34a;
    }
    .step-item.done .step-num {
        background: #16a34a;
        color: white;
    }

    /* ── Form Card ── */
    .form-card {
        background: white;
        border-radius: 20px;
        padding: 2.5rem;
        border: 1px solid var(--border);
        box-shadow: 0 4px 24px rgba(0,0,0,.04);
        margin-bottom: 2.5rem;
    }
    .form-card-header {
        display: flex; align-items: center; gap: .8rem;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1.5px solid var(--border);
    }
    .form-card-header .icon-box {
        width: 48px; height: 48px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem;
    }
    .form-card-header h2 { font-size: 1.3rem; font-weight: 800; color: var(--text-dark); }
    .form-card-header p { font-size: .85rem; color: var(--text-soft); margin-top: .2rem; }

    /* ── Section Divider ── */
    .form-section-title {
        font-size: .95rem;
        font-weight: 800;
        color: var(--text-dark);
        margin-bottom: 1rem;
        margin-top: .5rem;
        display: flex;
        align-items: center;
        gap: .5rem;
        padding-bottom: .5rem;
        border-bottom: 1.5px solid var(--border);
    }
    .form-section-title .section-badge {
        background: var(--primary-light);
        color: var(--primary);
        padding: .2rem .7rem;
        border-radius: 6px;
        font-size: .75rem;
        font-weight: 700;
    }

    .fin-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .fin-form-group {
        display: flex; flex-direction: column; gap: .5rem;
    }
    .fin-form-group.full-width {
        grid-column: 1 / -1;
    }
    .fin-form-group label {
        font-size: .85rem; font-weight: 700; color: var(--text-dark);
        display: flex; align-items: center; gap: .4rem;
    }
    .fin-form-group label .label-icon {
        font-size: 1rem;
    }
    .fin-form-group .input-wrapper {
        position: relative;
    }
    .fin-form-group .input-wrapper .prefix {
        position: absolute;
        left: 14px; top: 50%; transform: translateY(-50%);
        font-size: .85rem; font-weight: 600; color: var(--primary);
        pointer-events: none;
    }
    .fin-form-group input,
    .fin-form-group select {
        width: 100%;
        padding: 13px 16px;
        border: 1.5px solid var(--border);
        border-radius: 12px;
        font-size: .95rem;
        font-family: inherit;
        font-weight: 500;
        outline: none;
        transition: all .25s;
        background: #fafafa;
        color: var(--text-dark);
    }
    .fin-form-group input.has-prefix {
        padding-left: 42px;
    }
    .fin-form-group input:focus,
    .fin-form-group select:focus {
        border-color: var(--primary);
        background: white;
        box-shadow: 0 0 0 4px rgba(13, 148, 136, .08);
    }
    .fin-form-group .input-hint {
        font-size: .78rem; color: var(--text-soft); margin-top: .2rem;
    }

    /* ── Info Box ── */
    .info-box {
        background: linear-gradient(135deg, #eff6ff, #dbeafe);
        border: 1px solid #bfdbfe;
        border-radius: 14px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 2rem;
        display: flex; align-items: flex-start; gap: .8rem;
    }
    .info-box .info-icon {
        font-size: 1.3rem; flex-shrink: 0; margin-top: .1rem;
    }
    .info-box .info-content h4 {
        font-size: .9rem; font-weight: 700; color: #1e40af; margin-bottom: .3rem;
    }
    .info-box .info-content p {
        font-size: .82rem; color: #3b82f6; line-height: 1.5;
    }

    /* ── Calculate Button ── */
    .btn-calculate {
        width: 100%;
        padding: 15px;
        background: linear-gradient(135deg, var(--primary) 0%, #0f766e 100%);
        color: white;
        border: none;
        border-radius: 14px;
        font-size: 1rem;
        font-weight: 700;
        font-family: inherit;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: .6rem;
        transition: all .3s;
        box-shadow: 0 4px 14px rgba(13, 148, 136, .3);
    }
    .btn-calculate:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(13, 148, 136, .4);
    }
    .btn-calculate:active { transform: translateY(0); }
    .btn-calculate:disabled {
        opacity: .6; cursor: not-allowed; transform: none;
    }
    .btn-calculate svg { width: 20px; height: 20px; }

    /* ── Spinner ── */
    .spinner {
        width: 20px; height: 20px;
        border: 2.5px solid rgba(255,255,255,.3);
        border-top-color: white;
        border-radius: 50%;
        animation: spin .8s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* ── Result Section ── */
    .result-section {
        display: none;
        animation: fadeInUp .5s ease;
    }
    .result-section.show { display: block; }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ── Budget Summary Card ── */
    .budget-summary {
        background: linear-gradient(135deg, #0d9488, #0f766e, #115e59);
        border-radius: 20px;
        padding: 2.5rem;
        color: white;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    .budget-summary::before {
        content: '';
        position: absolute;
        top: -50%; right: -20%;
        width: 300px; height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,.1), transparent 70%);
        border-radius: 50%;
    }
    .budget-summary-content { position: relative; z-index: 1; }
    .budget-summary h3 {
        font-size: 1rem; font-weight: 600; opacity: .85; margin-bottom: .3rem;
    }
    .budget-summary .budget-amount {
        font-size: 2.5rem; font-weight: 800; margin-bottom: 1.5rem;
        text-shadow: 0 2px 10px rgba(0,0,0,.15);
    }
    .budget-detail-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }
    .budget-detail-item {
        background: rgba(255,255,255,.12);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        padding: 1rem;
        border: 1px solid rgba(255,255,255,.15);
    }
    .budget-detail-item .label {
        font-size: .75rem; opacity: .7; margin-bottom: .3rem;
    }
    .budget-detail-item .value {
        font-size: 1.1rem; font-weight: 800;
    }

    /* ── ML Cluster Badge ── */
    .cluster-badge-section {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        border: 1px solid var(--border);
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1.25rem;
        flex-wrap: wrap;
    }
    .cluster-badge {
        display: inline-flex; align-items: center; gap: .5rem;
        padding: .6rem 1.2rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: .9rem;
    }
    .cluster-badge.ekonomis { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
    .cluster-badge.premium { background: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; }
    .cluster-badge-info {
        flex: 1;
        min-width: 200px;
    }
    .cluster-badge-info h4 { font-size: .9rem; font-weight: 700; color: var(--text-dark); margin-bottom: .2rem; }
    .cluster-badge-info p { font-size: .82rem; color: var(--text-soft); line-height: 1.5; }

    /* ── Criteria Summary ── */
    .criteria-summary {
        background: var(--bg-soft);
        border-radius: 16px;
        padding: 1.5rem;
        border: 1px solid var(--border);
        margin-bottom: 2rem;
    }
    .criteria-summary h4 {
        font-size: .9rem; font-weight: 700; color: var(--text-dark);
        margin-bottom: 1rem;
        display: flex; align-items: center; gap: .4rem;
    }
    .criteria-chips {
        display: flex; gap: .6rem; flex-wrap: wrap;
    }
    .criteria-chip {
        background: white;
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: .5rem .9rem;
        font-size: .82rem;
        font-weight: 600;
        color: var(--text-dark);
        display: flex; align-items: center; gap: .35rem;
    }
    .criteria-chip .chip-icon { font-size: .9rem; }
    .criteria-chip .chip-label { color: var(--text-soft); margin-right: .2rem; }

    /* ── Property Results ── */
    .results-header {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: .5rem;
    }
    .results-header h2 {
        font-size: 1.3rem; font-weight: 800; color: var(--text-dark);
    }
    .results-header .results-count {
        background: var(--primary-light);
        color: var(--primary);
        padding: .4rem 1rem;
        border-radius: 20px;
        font-size: .85rem;
        font-weight: 700;
    }
    .results-header .fallback-note {
        width: 100%;
        font-size: .82rem;
        color: #b45309;
        background: #fef3c7;
        padding: .5rem 1rem;
        border-radius: 8px;
        border: 1px solid #fde68a;
        font-weight: 600;
    }

    .property-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
    }

    /* ── Empty State ── */
    .empty-result {
        background: white;
        border-radius: 20px;
        padding: 4rem 2rem;
        text-align: center;
        border: 2px dashed var(--border);
    }
    .empty-result .empty-icon { font-size: 4rem; margin-bottom: 1rem; }
    .empty-result h3 { font-size: 1.2rem; font-weight: 700; margin-bottom: .5rem; }
    .empty-result p { color: var(--text-soft); max-width: 400px; margin: 0 auto; line-height: 1.6; }

    /* ── Error Alert ── */
    .error-alert {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 14px;
        padding: 1rem 1.5rem;
        color: #dc2626;
        font-size: .9rem;
        font-weight: 600;
        display: none;
        margin-bottom: 1.5rem;
        align-items: center;
        gap: .5rem;
    }
    .error-alert.show { display: flex; }

    /* ── KPR Simulation Card ── */
    .kpr-section {
        margin-top: 2.5rem;
        display: none;
        animation: fadeInUp .5s ease;
    }
    .kpr-section.show { display: block; }
    .kpr-card {
        background: white;
        border-radius: 20px;
        padding: 2.5rem;
        border: 1px solid var(--border);
        box-shadow: 0 4px 24px rgba(0,0,0,.04);
        position: relative;
        overflow: hidden;
    }
    .kpr-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: linear-gradient(90deg, #f59e0b, #d97706, #b45309);
    }
    .kpr-card-header {
        display: flex; align-items: center; gap: .8rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1.5px solid var(--border);
    }
    .kpr-card-header .kpr-icon-box {
        width: 48px; height: 48px;
        border-radius: 14px;
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem;
    }
    .kpr-card-header h2 { font-size: 1.3rem; font-weight: 800; color: var(--text-dark); }
    .kpr-card-header p { font-size: .85rem; color: var(--text-soft); margin-top: .2rem; }
    .kpr-card-header .kpr-optional {
        margin-left: auto;
        background: #fef3c7;
        color: #92400e;
        padding: .3rem .8rem;
        border-radius: 8px;
        font-size: .75rem;
        font-weight: 700;
        border: 1px solid #fde68a;
    }
    .kpr-info-box {
        background: linear-gradient(135deg, #fefce8, #fef9c3);
        border: 1px solid #fde68a;
        border-radius: 14px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
        display: flex; align-items: flex-start; gap: .8rem;
    }
    .kpr-info-box .info-icon { font-size: 1.2rem; flex-shrink: 0; }
    .kpr-info-box .info-content h4 { font-size: .88rem; font-weight: 700; color: #92400e; margin-bottom: .3rem; }
    .kpr-info-box .info-content p { font-size: .8rem; color: #a16207; line-height: 1.5; }
    .kpr-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
        margin-bottom: 1.5rem;
    }
    .btn-kpr {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #d97706, #b45309);
        color: white;
        border: none;
        border-radius: 14px;
        font-size: 1rem;
        font-weight: 700;
        font-family: inherit;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: .6rem;
        transition: all .3s;
        box-shadow: 0 4px 14px rgba(217, 119, 6, .3);
    }
    .btn-kpr:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(217, 119, 6, .4);
    }

    /* ── KPR Result ── */
    .kpr-result {
        display: none;
        margin-top: 2rem;
        animation: fadeInUp .4s ease;
    }
    .kpr-result.show { display: block; }
    .kpr-verdict {
        border-radius: 18px;
        padding: 2rem;
        text-align: center;
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
    }
    .kpr-verdict::before {
        content: '';
        position: absolute;
        top: -40%; right: -15%;
        width: 250px; height: 250px;
        background: radial-gradient(circle, rgba(255,255,255,.15), transparent 70%);
        border-radius: 50%;
    }
    .kpr-verdict.layak {
        background: linear-gradient(135deg, #059669, #047857, #065f46);
        color: white;
    }
    .kpr-verdict.tidak-layak {
        background: linear-gradient(135deg, #dc2626, #b91c1c, #991b1b);
        color: white;
    }
    .kpr-verdict .verdict-icon {
        font-size: 3.5rem;
        margin-bottom: .6rem;
        position: relative; z-index: 1;
    }
    .kpr-verdict .verdict-title {
        font-size: 1.6rem;
        font-weight: 800;
        margin-bottom: .4rem;
        position: relative; z-index: 1;
    }
    .kpr-verdict .verdict-desc {
        font-size: .92rem;
        opacity: .9;
        max-width: 500px;
        margin: 0 auto;
        line-height: 1.6;
        position: relative; z-index: 1;
    }
    .kpr-detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .kpr-detail-card {
        background: white;
        border-radius: 14px;
        padding: 1.25rem;
        border: 1px solid var(--border);
        text-align: center;
        transition: all .25s;
    }
    .kpr-detail-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
    .kpr-detail-card .kpr-d-icon { font-size: 1.3rem; margin-bottom: .4rem; }
    .kpr-detail-card .kpr-d-label {
        font-size: .75rem; color: var(--text-soft); font-weight: 600;
        margin-bottom: .25rem;
    }
    .kpr-detail-card .kpr-d-value {
        font-size: 1rem; font-weight: 800; color: var(--text-dark);
    }
    .kpr-detail-card .kpr-d-value.danger { color: #dc2626; }
    .kpr-detail-card .kpr-d-value.success { color: #059669; }
    .kpr-detail-card .kpr-d-value.warning { color: #d97706; }

    /* ── DSR Gauge ── */
    .dsr-gauge {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        border: 1px solid var(--border);
        margin-bottom: 1.5rem;
    }
    .dsr-gauge h4 {
        font-size: .9rem; font-weight: 700; margin-bottom: 1rem;
        display: flex; align-items: center; gap: .4rem;
    }
    .dsr-bar-wrap {
        background: #f1f5f9;
        border-radius: 10px;
        height: 32px;
        position: relative;
        overflow: hidden;
        margin-bottom: .6rem;
    }
    .dsr-bar-fill {
        height: 100%;
        border-radius: 10px;
        transition: width .8s ease;
        display: flex; align-items: center; justify-content: flex-end;
        padding-right: .8rem;
        font-size: .78rem; font-weight: 800; color: white;
        min-width: 60px;
    }
    .dsr-bar-fill.safe { background: linear-gradient(90deg, #22c55e, #16a34a); }
    .dsr-bar-fill.warning { background: linear-gradient(90deg, #f59e0b, #d97706); }
    .dsr-bar-fill.danger { background: linear-gradient(90deg, #ef4444, #dc2626); }
    .dsr-bar-limit {
        position: absolute;
        top: 0; bottom: 0;
        left: 30%;
        width: 2px;
        background: #1e293b;
        z-index: 2;
    }
    .dsr-bar-limit-label {
        position: absolute;
        top: -22px; left: 30%;
        transform: translateX(-50%);
        font-size: .7rem; font-weight: 700;
        color: #1e293b;
        background: #f1f5f9;
        padding: .1rem .4rem;
        border-radius: 4px;
    }
    .dsr-legend {
        display: flex; gap: 1.5rem; font-size: .78rem; color: var(--text-soft); font-weight: 600;
    }
    .dsr-legend span { display: flex; align-items: center; gap: .3rem; }
    .dsr-dot {
        width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0;
    }

    /* ── Responsive ── */
    @media (max-width: 768px) {
        .finansial-page { padding: 5rem 1rem 3rem; }
        .finansial-hero { padding: 2rem; }
        .finansial-hero h1 { font-size: 1.5rem; }
        .form-card { padding: 1.5rem; }
        .fin-form-grid { grid-template-columns: 1fr; }
        .budget-summary { padding: 1.5rem; }
        .budget-summary .budget-amount { font-size: 1.8rem; }
        .budget-detail-grid { grid-template-columns: 1fr; }
        .steps-bar { flex-direction: column; }
        .step-item:first-child { border-radius: 14px 14px 0 0; }
        .step-item:last-child { border-radius: 0 0 14px 14px; }
        .property-grid { grid-template-columns: 1fr; }
        .cluster-badge-section { flex-direction: column; align-items: flex-start; }
        .kpr-card { padding: 1.5rem; }
        .kpr-form-grid { grid-template-columns: 1fr; }
        .kpr-detail-grid { grid-template-columns: 1fr 1fr; }
        .kpr-card-header .kpr-optional { display: none; }
    }

    /* ── Property Card Styles ── */
    .property-card {
        background: white;
        border-radius: 18px;
        overflow: hidden;
        border: 1px solid var(--border);
        transition: all .3s;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        position: relative;
    }
    .property-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-lg); border-color: var(--primary); }
    .property-card-img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
        display: flex; align-items: center; justify-content: center;
        font-size: 3rem; color: var(--text-soft);
        position: relative;
    }
    .property-card-img img { width: 100%; height: 100%; object-fit: cover; }
    .property-card-body { padding: 1.3rem; flex: 1; display: flex; flex-direction: column; }
    .property-card-type {
        display: inline-block;
        background: var(--primary-light);
        color: var(--primary);
        padding: .2rem .7rem;
        border-radius: 6px;
        font-size: .75rem;
        font-weight: 700;
        margin-bottom: .6rem;
        width: fit-content;
    }
    .property-card-name { font-size: 1.05rem; font-weight: 700; margin-bottom: .4rem; color: var(--text-dark); }
    .property-card-loc {
        display: flex; align-items: center; gap: .35rem;
        font-size: .85rem; color: var(--text-soft); margin-bottom: .8rem;
    }
    .property-card-loc svg { width: 14px; height: 14px; flex-shrink: 0; }
    .property-card-specs {
        display: flex; gap: 1rem; margin-bottom: 1rem;
        padding-top: .8rem; border-top: 1px solid var(--border);
    }
    .spec-item { display: flex; align-items: center; gap: .3rem; font-size: .8rem; color: var(--text-soft); font-weight: 600; }
    .spec-item svg { width: 14px; height: 14px; }
    .property-card-bottom {
        display: flex; justify-content: space-between; align-items: center;
        margin-top: auto;
    }
    .property-card-price { font-size: 1.15rem; font-weight: 800; color: var(--primary); }
    .btn-fav {
        width: 36px; height: 36px; border-radius: 10px; border: 1.5px solid var(--border);
        background: white; cursor: pointer; display: flex; align-items: center; justify-content: center;
        transition: all .2s;
    }
    .btn-fav:hover { border-color: #ef4444; background: #fef2f2; }
    .btn-fav svg { width: 18px; height: 18px; }
    .btn-fav.active { background: #fef2f2; border-color: #ef4444; }
    .btn-fav.active svg { fill: #ef4444; stroke: #ef4444; }
</style>
@endpush

@section('content')
<section class="finansial-page">
    <div class="finansial-container">

        {{-- ═══ HERO ═══ --}}
        <div class="finansial-hero">
            <div class="finansial-hero-content">
                <div class="breadcrumb">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <span>›</span>
                    <span>Rekomendasi Finansial</span>
                </div>
                <h1>💰 Rekomendasi Rumah Sesuai Kemampuan Finansial</h1>
                <p>Masukkan data keuangan dan kriteria rumah impianmu. Sistem akan menghitung budget ideal dan menggunakan AI untuk menemukan rumah yang paling cocok.</p>
            </div>
        </div>

        {{-- ═══ STEPS ═══ --}}
        <div class="steps-bar">
            <div class="step-item active" id="step1">
                <span class="step-num">1</span>
                <span>Isi Data & Kriteria</span>
            </div>
            <div class="step-item" id="step2">
                <span class="step-num">2</span>
                <span>Analisis AI</span>
            </div>
            <div class="step-item" id="step3">
                <span class="step-num">3</span>
                <span>Rekomendasi</span>
            </div>
            <div class="step-item" id="step4">
                <span class="step-num">4</span>
                <span>Simulasi KPR</span>
            </div>
        </div>

        {{-- ═══ ERROR ALERT ═══ --}}
        <div class="error-alert" id="errorAlert">
            <span>⚠️</span>
            <span id="errorMessage"></span>
        </div>

        {{-- ═══ FORM CARD ═══ --}}
        <div class="form-card" id="formCard">

            <div class="form-card-header">
                <div class="icon-box" style="background: linear-gradient(135deg, #fef3c7, #fde68a);">💵</div>
                <div>
                    <h2>Data Keuangan Bulanan</h2>
                    <p>Masukkan informasi pendapatan dan pengeluaran bulanan kamu</p>
                </div>
            </div>

            <div class="info-box">
                <span class="info-icon">ℹ️</span>
                <div class="info-content">
                    <h4>Cara Kerja Perhitungan</h4>
                    <p>Budget rumah dihitung dengan rumus: <strong>3 × (Pendapatan Bersih × 12 bulan)</strong>. Lalu sistem menggunakan model <strong>KNN Machine Learning</strong> untuk mengklasifikasi dan mencocokkan rumah sesuai kriteria yang kamu inginkan.</p>
                </div>
            </div>

            <form id="finansialForm">
                @csrf

                <div class="form-section-title">
                    <span>💰</span> Data Keuangan
                    <span class="section-badge">Wajib</span>
                </div>

                <div class="fin-form-grid">
                    <div class="fin-form-group">
                        <label>
                            <span class="label-icon">💰</span>
                            Pendapatan Total / Bulan
                        </label>
                        <div class="input-wrapper">
                            <span class="prefix">Rp</span>
                            <input type="text" id="pendapatan" name="pendapatan" class="has-prefix" placeholder="10.000.000" required oninput="formatRupiahFin(this)" />
                        </div>
                        <span class="input-hint">Total gaji/pendapatan seluruh keluarga per bulan</span>
                    </div>

                    <div class="fin-form-group">
                        <label>
                            <span class="label-icon">📋</span>
                            Total Pengeluaran / Bulan
                        </label>
                        <div class="input-wrapper">
                            <span class="prefix">Rp</span>
                            <input type="text" id="pengeluaran" name="pengeluaran" class="has-prefix" placeholder="5.000.000" required oninput="formatRupiahFin(this)" />
                        </div>
                        <span class="input-hint">Kebutuhan pokok, cicilan, dll (tanpa biaya sewa)</span>
                    </div>
                </div>

                <div class="form-section-title" style="margin-top: 1rem;">
                    <span>🏠</span> Kriteria Rumah Impian
                    <span class="section-badge">Kriteria ML</span>
                </div>

                <div class="fin-form-grid">
                    <div class="fin-form-group">
                        <label>
                            <span class="label-icon">🛏️</span>
                            Kamar Tidur
                        </label>
                        <input type="number" id="kamar_tidur" name="kamar_tidur" placeholder="3" required min="1" max="20" />
                        <span class="input-hint">Jumlah kamar tidur yang diinginkan</span>
                    </div>

                    <div class="fin-form-group">
                        <label>
                            <span class="label-icon">🚿</span>
                            Kamar Mandi
                        </label>
                        <input type="number" id="kamar_mandi" name="kamar_mandi" placeholder="2" required min="1" max="20" />
                        <span class="input-hint">Jumlah kamar mandi yang diinginkan</span>
                    </div>

                    <div class="fin-form-group">
                        <label>
                            <span class="label-icon">📐</span>
                            Luas Tanah (m²)
                        </label>
                        <input type="number" id="luas_tanah" name="luas_tanah" placeholder="120" required min="1" />
                        <span class="input-hint">Luas tanah minimal yang diinginkan</span>
                    </div>

                    <div class="fin-form-group">
                        <label>
                            <span class="label-icon">🏗️</span>
                            Luas Bangunan (m²)
                        </label>
                        <input type="number" id="luas_bangunan" name="luas_bangunan" placeholder="80" required min="1" />
                        <span class="input-hint">Luas bangunan minimal yang diinginkan</span>
                    </div>

                    <div class="fin-form-group">
                        <label>
                            <span class="label-icon">🏙️</span>
                            Kota
                        </label>
                        <select id="kota" name="kota" required>
                            <option value="">— Pilih Kota —</option>
                            <option value="Jakarta">Jakarta</option>
                            <option value="Bogor">Bogor</option>
                            <option value="Depok">Depok</option>
                            <option value="Tangerang">Tangerang</option>
                            <option value="Bekasi">Bekasi</option>
                        </select>
                        <span class="input-hint">Kota lokasi rumah yang diinginkan</span>
                    </div>

                    <div class="fin-form-group">
                        <label>
                            <span class="label-icon">📍</span>
                            Posisi Kota
                        </label>
                        <select id="posisi_kota" name="posisi_kota" required>
                            <option value="">— Pilih Posisi —</option>
                            <option value="Pusat Kota">Pusat Kota</option>
                            <option value="Dekat Pusat Kota">Dekat Pusat Kota</option>
                            <option value="Pinggiran Kota">Pinggiran Kota</option>
                        </select>
                        <span class="input-hint">Posisi area di dalam kota</span>
                    </div>
                </div>

                <button type="submit" class="btn-calculate" id="btnHitung">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                    </svg>
                    Hitung & Cari Rekomendasi dengan AI
                </button>
            </form>
        </div>

        {{-- ═══ RESULT SECTION ═══ --}}
        <div class="result-section" id="resultSection">

            {{-- Budget Summary --}}
            <div class="budget-summary">
                <div class="budget-summary-content">
                    <h3>💎 Budget Rumah Ideal Kamu</h3>
                    <div class="budget-amount" id="budgetAmount">-</div>
                    <div class="budget-detail-grid">
                        <div class="budget-detail-item">
                            <div class="label">Pendapatan Bersih / Bulan</div>
                            <div class="value" id="detailBersih">-</div>
                        </div>
                        <div class="budget-detail-item">
                            <div class="label">Pendapatan Bersih / Tahun</div>
                            <div class="value" id="detailTahunan">-</div>
                        </div>
                        <div class="budget-detail-item">
                            <div class="label">Rumus: 3 × Pendapatan Tahunan</div>
                            <div class="value" id="detailRumus">-</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ML Cluster Badge --}}
            <div class="cluster-badge-section" id="clusterSection" style="display: none;">
                <div class="cluster-badge" id="clusterBadge">
                    <span id="clusterIcon">🤖</span>
                    <span id="clusterText">-</span>
                </div>
                <div class="cluster-badge-info">
                    <h4 id="clusterTitle">Hasil Analisis AI</h4>
                    <p id="clusterDescription">-</p>
                </div>
            </div>

            {{-- Criteria Summary --}}
            <div class="criteria-summary" id="criteriaSummary">
                <h4>📝 Kriteria Pencarian</h4>
                <div class="criteria-chips" id="criteriaChips">
                    {{-- Filled dynamically --}}
                </div>
            </div>

            {{-- Property Results --}}
            <div class="results-header" id="resultsHeader">
                <h2>🏠 Rekomendasi Rumah Sesuai Budget & Kriteria</h2>
                <span class="results-count" id="resultsCount">0 Properti</span>
                <div class="fallback-note" id="fallbackNote" style="display: none;"></div>
            </div>

            <div class="property-grid" id="propertyGrid">
                {{-- Filled dynamically --}}
            </div>

            {{-- Empty State --}}
            <div class="empty-result" id="emptyResult" style="display: none;">
                <div class="empty-icon">🏗️</div>
                <h3>Belum Ada Properti yang Sesuai</h3>
                <p>Maaf, saat ini belum ada properti yang sesuai dengan budget dan kriteria kamu. Coba ubah kriteria atau tingkatkan pendapatan untuk melihat lebih banyak pilihan.</p>
            </div>

            {{-- ═══ STEP 4: SIMULASI KPR (Opsional) ═══ --}}
            <div class="kpr-section" id="kprSection">
                <div class="kpr-card">
                    <div class="kpr-card-header">
                        <div class="kpr-icon-box">🏦</div>
                        <div>
                            <h2>Simulasi KPR (Kredit Pemilikan Rumah)</h2>
                            <p>Cek apakah kamu layak mengajukan KPR berdasarkan standar OJK/BI</p>
                        </div>
                        <span class="kpr-optional">Opsional</span>
                    </div>

                    <div class="kpr-info-box">
                        <span class="info-icon">📋</span>
                        <div class="info-content">
                            <h4>Standar Kelayakan KPR (OJK/BI)</h4>
                            <p><strong>• LTV (Loan to Value):</strong> Minimal DP 20% dari harga rumah untuk rumah pertama.<br>
                            <strong>• DSR (Debt Service Ratio):</strong> Total cicilan maksimal 30% dari pendapatan kotor bulanan.<br>
                            <strong>• Suku Bunga:</strong> Rata-rata bunga KPR fixed ~8.5% per tahun (bisa disesuaikan).<br>
                            <strong>• Rumus Cicilan:</strong> PMT = P × [r(1+r)ⁿ] / [(1+r)ⁿ - 1]</p>
                        </div>
                    </div>

                    <form id="kprForm">
                        <div class="kpr-form-grid">
                            <div class="fin-form-group">
                                <label><span class="label-icon">🏠</span> Harga Rumah yang Dipilih</label>
                                <div class="input-wrapper">
                                    <span class="prefix">Rp</span>
                                    <input type="number" id="kpr_harga" class="has-prefix" placeholder="500000000" step="1000000" required min="0" />
                                </div>
                                <span class="input-hint">Masukkan harga rumah yang ingin kamu KPR-kan</span>
                            </div>

                            <div class="fin-form-group">
                                <label><span class="label-icon">💵</span> Uang Muka (DP) %</label>
                                <select id="kpr_dp" required>
                                    <option value="20">20% (Minimum OJK)</option>
                                    <option value="25">25%</option>
                                    <option value="30" selected>30%</option>
                                    <option value="35">35%</option>
                                    <option value="40">40%</option>
                                    <option value="50">50%</option>
                                </select>
                                <span class="input-hint">Minimal 20% sesuai ketentuan OJK untuk rumah pertama</span>
                            </div>

                            <div class="fin-form-group">
                                <label><span class="label-icon">📅</span> Tenor KPR (Tahun)</label>
                                <select id="kpr_tenor" required>
                                    <option value="5">5 Tahun (60 bulan)</option>
                                    <option value="10">10 Tahun (120 bulan)</option>
                                    <option value="15" selected>15 Tahun (180 bulan)</option>
                                    <option value="20">20 Tahun (240 bulan)</option>
                                    <option value="25">25 Tahun (300 bulan)</option>
                                    <option value="30">30 Tahun (360 bulan)</option>
                                </select>
                                <span class="input-hint">Jangka waktu cicilan KPR</span>
                            </div>

                            <div class="fin-form-group">
                                <label><span class="label-icon">📊</span> Suku Bunga (% / Tahun)</label>
                                <input type="number" id="kpr_bunga" value="8.5" step="0.1" min="1" max="20" required />
                                <span class="input-hint">Rata-rata bunga KPR bank ~7-10% per tahun</span>
                            </div>
                        </div>

                        <button type="submit" class="btn-kpr" id="btnKpr">
                            🧮 Hitung Kelayakan KPR
                        </button>
                    </form>

                    {{-- KPR Result --}}
                    <div class="kpr-result" id="kprResult">

                        {{-- Verdict --}}
                        <div class="kpr-verdict" id="kprVerdict">
                            <div class="verdict-icon" id="kprVerdictIcon">-</div>
                            <div class="verdict-title" id="kprVerdictTitle">-</div>
                            <div class="verdict-desc" id="kprVerdictDesc">-</div>
                        </div>

                        {{-- Detail Cards --}}
                        <div class="kpr-detail-grid">
                            <div class="kpr-detail-card">
                                <div class="kpr-d-icon">🏠</div>
                                <div class="kpr-d-label">Harga Rumah</div>
                                <div class="kpr-d-value" id="kprHargaRumah">-</div>
                            </div>
                            <div class="kpr-detail-card">
                                <div class="kpr-d-icon">💵</div>
                                <div class="kpr-d-label">Uang Muka (DP)</div>
                                <div class="kpr-d-value" id="kprDpAmount">-</div>
                            </div>
                            <div class="kpr-detail-card">
                                <div class="kpr-d-icon">💳</div>
                                <div class="kpr-d-label">Pokok Pinjaman</div>
                                <div class="kpr-d-value" id="kprPinjaman">-</div>
                            </div>
                            <div class="kpr-detail-card">
                                <div class="kpr-d-icon">📅</div>
                                <div class="kpr-d-label">Cicilan / Bulan</div>
                                <div class="kpr-d-value" id="kprCicilan">-</div>
                            </div>
                            <div class="kpr-detail-card">
                                <div class="kpr-d-icon">📊</div>
                                <div class="kpr-d-label">DSR (Debt Service Ratio)</div>
                                <div class="kpr-d-value" id="kprDsr">-</div>
                            </div>
                            <div class="kpr-detail-card">
                                <div class="kpr-d-icon">💰</div>
                                <div class="kpr-d-label">Total Bayar (Pokok + Bunga)</div>
                                <div class="kpr-d-value" id="kprTotalBayar">-</div>
                            </div>
                        </div>

                        {{-- DSR Gauge --}}
                        <div class="dsr-gauge">
                            <h4>📊 Rasio Cicilan terhadap Pendapatan (DSR)</h4>
                            <div style="position: relative; margin-bottom: .5rem;">
                                <span class="dsr-bar-limit-label">Batas 30%</span>
                                <div class="dsr-bar-wrap">
                                    <div class="dsr-bar-fill" id="dsrBarFill" style="width: 0%;">0%</div>
                                    <div class="dsr-bar-limit"></div>
                                </div>
                            </div>
                            <div class="dsr-legend">
                                <span><span class="dsr-dot" style="background: #22c55e;"></span> Aman (&lt;25%)</span>
                                <span><span class="dsr-dot" style="background: #f59e0b;"></span> Mendekati Batas (25-30%)</span>
                                <span><span class="dsr-dot" style="background: #ef4444;"></span> Melebihi Batas (&gt;30%)</span>
                            </div>
                        </div>

                        {{-- Sisa Pendapatan --}}
                        <div class="criteria-summary">
                            <h4>💡 Rincian Keuangan Setelah KPR</h4>
                            <div class="criteria-chips" id="kprRincian"></div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Button Hitung Ulang --}}
            <div style="text-align: center; margin-top: 2rem;">
                <button type="button" class="btn-calculate" id="btnReset" style="max-width: 400px; margin: 0 auto; background: linear-gradient(135deg, #6366f1, #4f46e5);">
                    🔄 Hitung Ulang dengan Kriteria Baru
                </button>
            </div>

        </div>

    </div>
</section>
@endsection

@push('scripts')
<script>
function formatRupiahFin(input) {
    let raw = input.value.replace(/\D/g, '');
    input.value = raw ? parseInt(raw).toLocaleString('id-ID') : '';
}
document.addEventListener('DOMContentLoaded', function() {

    const form          = document.getElementById('finansialForm');
    const btnHitung     = document.getElementById('btnHitung');
    const btnReset      = document.getElementById('btnReset');
    const resultSection = document.getElementById('resultSection');
    const formCard      = document.getElementById('formCard');
    const errorAlert    = document.getElementById('errorAlert');
    const errorMessage  = document.getElementById('errorMessage');
    const propertyGrid  = document.getElementById('propertyGrid');
    const emptyResult   = document.getElementById('emptyResult');

    // Steps
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    const step3 = document.getElementById('step3');
    const step4 = document.getElementById('step4');

    // ── Helper: Format Rupiah singkat (juta/miliar) ──
    function formatRupiah(num) {
        if (num >= 1000000000) {
            return 'Rp ' + (num / 1000000000).toFixed(1).replace('.0', '') + ' Miliar';
        }
        if (num >= 1000000) {
            return 'Rp ' + (num / 1000000).toFixed(1).replace('.0', '') + ' Juta';
        }
        return 'Rp ' + num.toLocaleString('id-ID');
    }

    // ── Helper: Format Rupiah lengkap ──
    function formatRupiahFull(num) {
        return 'Rp ' + Math.round(num).toLocaleString('id-ID');
    }

    // ── Loading state tombol Hitung ──
    function setLoading(loading) {
        if (loading) {
            btnHitung.disabled = true;
            btnHitung.innerHTML = '<span class="spinner"></span> Menganalisis dengan AI...';
        } else {
            btnHitung.disabled = false;
            btnHitung.innerHTML = `
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                </svg>
                Hitung & Cari Rekomendasi dengan AI`;
        }
    }

    // ── Tampilkan error sementara ──
    function showError(msg) {
        errorMessage.textContent = msg;
        errorAlert.classList.add('show');
        setTimeout(() => errorAlert.classList.remove('show'), 5000);
    }

    // ══════════════════════════════════════════
    // STEP 1–3: SUBMIT FORM FINANSIAL
    // ══════════════════════════════════════════
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        errorAlert.classList.remove('show');

        const pendapatan  = parseFloat(document.getElementById('pendapatan').value.replace(/\./g, ''))  || 0;
        const pengeluaran = parseFloat(document.getElementById('pengeluaran').value.replace(/\./g, '')) || 0;
        const kamar_tidur   = parseInt(document.getElementById('kamar_tidur').value)     || 0;
        const kamar_mandi   = parseInt(document.getElementById('kamar_mandi').value)     || 0;
        const luas_tanah    = parseFloat(document.getElementById('luas_tanah').value)    || 0;
        const luas_bangunan = parseFloat(document.getElementById('luas_bangunan').value) || 0;
        const kota          = document.getElementById('kota').value;
        const posisi_kota   = document.getElementById('posisi_kota').value;

        if (pendapatan <= 0) {
            showError('Pendapatan harus lebih dari 0.');
            return;
        }
        if (pengeluaran >= pendapatan) {
            showError('Pengeluaran harus lebih kecil dari pendapatan agar ada pendapatan bersih.');
            return;
        }
        if (!kota) {
            showError('Silakan pilih kota.');
            return;
        }
        if (!posisi_kota) {
            showError('Silakan pilih posisi kota.');
            return;
        }

        setLoading(true);

        // Update steps → Step 2 aktif
        step1.classList.remove('active');
        step1.classList.add('done');
        step2.classList.add('active');

        try {
            const response = await fetch('{{ route("rekomendasi.finansial.hitung") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    pendapatan, pengeluaran,
                    kamar_tidur, kamar_mandi,
                    luas_tanah, luas_bangunan,
                    kota, posisi_kota
                }),
            });

            const data = await response.json();

            if (!response.ok || data.status === 'error') {
                showError(data.message || 'Terjadi kesalahan saat menghitung.');
                setLoading(false);
                step2.classList.remove('active');
                step1.classList.remove('done');
                step1.classList.add('active');
                return;
            }

            // Update steps → Step 3 aktif
            step2.classList.remove('active');
            step2.classList.add('done');
            step3.classList.add('active');

            // ── Budget Summary ──
            document.getElementById('budgetAmount').textContent  = formatRupiahFull(data.budget);
            document.getElementById('detailBersih').textContent  = formatRupiah(data.pendapatan_bersih);
            document.getElementById('detailTahunan').textContent = formatRupiah(data.pendapatan_bersih * 12);
            document.getElementById('detailRumus').textContent   = '3 × ' + formatRupiah(data.pendapatan_bersih * 12);

            // ── ML Cluster Badge ──
            const clusterSection = document.getElementById('clusterSection');
            if (data.ml_status === 'online' && data.predicted_cluster !== null) {
                clusterSection.style.display = 'flex';
                const badge      = document.getElementById('clusterBadge');
                const isEkonomis = data.predicted_cluster === 0;

                badge.className = 'cluster-badge ' + (isEkonomis ? 'ekonomis' : 'premium');
                document.getElementById('clusterIcon').textContent        = isEkonomis ? '🏡' : '🏰';
                document.getElementById('clusterText').textContent        = 'Cluster ' + data.predicted_cluster + ' — ' + (data.kategori || (isEkonomis ? 'Ekonomis' : 'Premium'));
                document.getElementById('clusterTitle').textContent       = 'Analisis AI: Kategori ' + (data.kategori || (isEkonomis ? 'Ekonomis' : 'Premium'));
                document.getElementById('clusterDescription').textContent = isEkonomis
                    ? 'Berdasarkan budget dan kriteria kamu, model KNN merekomendasikan properti kategori Ekonomis — rumah dengan harga terjangkau dan efisien.'
                    : 'Berdasarkan budget dan kriteria kamu, model KNN merekomendasikan properti kategori Premium — rumah dengan lokasi strategis dan spesifikasi tinggi.';
            } else {
                clusterSection.style.display = 'none';
            }

            // ── Criteria Chips ──
            document.getElementById('criteriaChips').innerHTML = `
                <div class="criteria-chip"><span class="chip-icon">🛏️</span> <span class="chip-label">KT:</span> ${kamar_tidur}</div>
                <div class="criteria-chip"><span class="chip-icon">🚿</span> <span class="chip-label">KM:</span> ${kamar_mandi}</div>
                <div class="criteria-chip"><span class="chip-icon">📐</span> <span class="chip-label">L. Tanah:</span> ${luas_tanah} m²</div>
                <div class="criteria-chip"><span class="chip-icon">🏗️</span> <span class="chip-label">L. Bangunan:</span> ${luas_bangunan} m²</div>
                <div class="criteria-chip"><span class="chip-icon">🏙️</span> <span class="chip-label">Kota:</span> ${kota}</div>
                <div class="criteria-chip"><span class="chip-icon">📍</span> <span class="chip-label">Posisi:</span> ${posisi_kota}</div>
                <div class="criteria-chip"><span class="chip-icon">💰</span> <span class="chip-label">Budget:</span> ${formatRupiah(data.budget)}</div>
            `;

            // ── Fallback Note ──
            const fallbackNote = document.getElementById('fallbackNote');
            if (data.is_fallback && data.req_kota) {
                fallbackNote.style.display = 'block';
                fallbackNote.textContent   = '⚠️ Belum ada properti yang cocok di ' + data.req_kota + ' dengan kriteria tersebut. Menampilkan properti dari kota lain yang sesuai budget.';
            } else {
                fallbackNote.style.display = 'none';
            }

            // ── Property Grid ──
            document.getElementById('resultsCount').textContent = data.total_rumah + ' Properti';

            if (data.total_rumah > 0) {
                propertyGrid.innerHTML     = data.html;
                propertyGrid.style.display = 'grid';
                emptyResult.style.display  = 'none';
            } else {
                propertyGrid.style.display = 'none';
                emptyResult.style.display  = 'block';
            }

            // ── Tampilkan KPR Section ──
            document.getElementById('kprSection').classList.add('show');

            // Simpan data finansial untuk kalkulasi KPR
            window._finansialData = {
                pendapatan:  pendapatan,
                pengeluaran: pengeluaran,
                budget:      data.budget,
            };

            // Sembunyikan form, tampilkan hasil
            formCard.style.display = 'none';
            resultSection.classList.add('show');
            resultSection.scrollIntoView({ behavior: 'smooth', block: 'start' });

        } catch (err) {
            showError('Gagal menghubungi server. Coba lagi nanti.');
            step2.classList.remove('active');
            step1.classList.remove('done');
            step1.classList.add('active');
        }

        setLoading(false);
    });

    // ══════════════════════════════════════════
    // STEP 4: SIMULASI KPR (OJK/BI Standard)
    // ══════════════════════════════════════════
    const kprForm   = document.getElementById('kprForm');
    const kprResult = document.getElementById('kprResult');

    kprForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const hargaRumah   = parseFloat(document.getElementById('kpr_harga').value) || 0;
        const dpPersen     = parseFloat(document.getElementById('kpr_dp').value)    || 30;
        const tenorTahun   = parseInt(document.getElementById('kpr_tenor').value)   || 15;
        const bungaTahunan = parseFloat(document.getElementById('kpr_bunga').value) || 8.5;

        if (hargaRumah <= 0) {
            alert('Masukkan harga rumah yang valid.');
            return;
        }

        const finansialData       = window._finansialData || {};
        const pendapatanBulanan   = finansialData.pendapatan || 0;
        const pengeluaranBulanan  = finansialData.pengeluaran || 0;

        if (pendapatanBulanan <= 0) {
            alert('Data pendapatan tidak ditemukan. Silakan hitung ulang dari awal.');
            return;
        }

        // ── Perhitungan KPR ──
        const dpAmount      = hargaRumah * (dpPersen / 100);
        const pokokPinjaman = hargaRumah - dpAmount;
        const tenorBulan    = tenorTahun * 12;
        const bungaBulanan  = (bungaTahunan / 100) / 12;

        // Rumus PMT: P × [r(1+r)^n] / [(1+r)^n - 1]
        let cicilanBulanan;
        if (bungaBulanan === 0) {
            cicilanBulanan = pokokPinjaman / tenorBulan;
        } else {
            const factor   = Math.pow(1 + bungaBulanan, tenorBulan);
            cicilanBulanan = pokokPinjaman * (bungaBulanan * factor) / (factor - 1);
        }

        const totalBayar  = cicilanBulanan * tenorBulan;
        const totalBunga  = totalBayar - pokokPinjaman;
        const dsr         = (cicilanBulanan / pendapatanBulanan) * 100;
        const isLayak     = dsr <= 30;
        const sisaPendapatan = pendapatanBulanan - pengeluaranBulanan - cicilanBulanan;

        // ── Update Steps ──
        step3.classList.remove('active');
        step3.classList.add('done');
        step4.classList.remove('active', 'done');
        step4.classList.add(isLayak ? 'done' : 'active');

        // ── Verdict ──
        const verdict = document.getElementById('kprVerdict');
        verdict.className = 'kpr-verdict ' + (isLayak ? 'layak' : 'tidak-layak');
        document.getElementById('kprVerdictIcon').textContent  = isLayak ? '✅' : '❌';
        document.getElementById('kprVerdictTitle').textContent = isLayak ? 'LAYAK KPR' : 'TIDAK LAYAK KPR';
        document.getElementById('kprVerdictDesc').textContent  = isLayak
            ? `Selamat! DSR kamu ${dsr.toFixed(1)}% (di bawah batas 30%). Kamu memenuhi syarat kelayakan KPR berdasarkan standar OJK/BI.`
            : `Maaf, DSR kamu ${dsr.toFixed(1)}% (melebihi batas 30%). Berdasarkan standar OJK/BI, cicilan terlalu besar dibandingkan pendapatan. Coba tambah DP atau perpanjang tenor.`;

        // ── Detail Cards ──
        document.getElementById('kprHargaRumah').textContent = formatRupiahFull(hargaRumah);
        document.getElementById('kprDpAmount').textContent   = formatRupiahFull(dpAmount) + ` (${dpPersen}%)`;
        document.getElementById('kprPinjaman').textContent   = formatRupiahFull(pokokPinjaman);

        const cicilanEl   = document.getElementById('kprCicilan');
        cicilanEl.textContent = formatRupiahFull(cicilanBulanan);
        cicilanEl.className   = 'kpr-d-value ' + (isLayak ? 'success' : 'danger');

        const dsrEl       = document.getElementById('kprDsr');
        dsrEl.textContent = dsr.toFixed(1) + '%';
        dsrEl.className   = 'kpr-d-value ' + (dsr <= 25 ? 'success' : dsr <= 30 ? 'warning' : 'danger');

        document.getElementById('kprTotalBayar').textContent = formatRupiahFull(totalBayar);

        // ── DSR Gauge ──
        const dsrBar      = document.getElementById('dsrBarFill');
        const dsrWidth    = Math.min(dsr, 100);
        dsrBar.style.width = dsrWidth + '%';
        dsrBar.textContent = dsr.toFixed(1) + '%';
        dsrBar.className   = 'dsr-bar-fill ' + (dsr <= 25 ? 'safe' : dsr <= 30 ? 'warning' : 'danger');

        // ── Rincian Keuangan Setelah KPR ──
        document.getElementById('kprRincian').innerHTML = `
            <div class="criteria-chip"><span class="chip-icon">💰</span> <span class="chip-label">Pendapatan:</span> ${formatRupiahFull(pendapatanBulanan)}</div>
            <div class="criteria-chip"><span class="chip-icon">📋</span> <span class="chip-label">Pengeluaran:</span> ${formatRupiahFull(pengeluaranBulanan)}</div>
            <div class="criteria-chip"><span class="chip-icon">🏦</span> <span class="chip-label">Cicilan KPR:</span> ${formatRupiahFull(cicilanBulanan)}</div>
            <div class="criteria-chip"><span class="chip-icon">${sisaPendapatan >= 0 ? '✅' : '⚠️'}</span> <span class="chip-label">Sisa:</span> ${formatRupiahFull(Math.abs(sisaPendapatan))}${sisaPendapatan < 0 ? ' (MINUS!)' : ''}</div>
            <div class="criteria-chip"><span class="chip-icon">📊</span> <span class="chip-label">Bunga/Tahun:</span> ${bungaTahunan}%</div>
            <div class="criteria-chip"><span class="chip-icon">📅</span> <span class="chip-label">Tenor:</span> ${tenorTahun} Tahun</div>
            <div class="criteria-chip"><span class="chip-icon">💸</span> <span class="chip-label">Total Bunga:</span> ${formatRupiah(totalBunga)}</div>
        `;

        // Tampilkan hasil KPR
        kprResult.classList.add('show');
        kprResult.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });

    // ══════════════════════════════════════════
    // RESET / HITUNG ULANG
    // ══════════════════════════════════════════
    btnReset.addEventListener('click', function() {
        resultSection.classList.remove('show');
        formCard.style.display = 'block';
        propertyGrid.innerHTML = '';
        emptyResult.style.display = 'none';
        document.getElementById('clusterSection').style.display = 'none';
        document.getElementById('fallbackNote').style.display   = 'none';
        document.getElementById('kprSection').classList.remove('show');
        kprResult.classList.remove('show');
        document.getElementById('kpr_harga').value = '';

        // Reset steps
        step1.classList.add('active');
        step1.classList.remove('done');
        step2.classList.remove('active', 'done');
        step3.classList.remove('active', 'done');
        step4.classList.remove('active', 'done');

        formCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });

});
</script>
@endpush