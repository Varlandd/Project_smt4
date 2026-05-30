@extends('layouts.app')

@section('title', 'RumahKu — Temukan Rumah Impian Sesuai Budget')

@push('styles')
<style>
/* ═══════════════════════════════════════════
   LANDING PAGE STYLES
   ═══════════════════════════════════════════ */

/* ── Reset & Base ── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
    --primary: #0f5c4e;
    --primary-mid: #1a7a68;
    --primary-light: #14b8a6;
    --primary-pale: #e6f4f1;
    --accent: #25d4a8;
    --white: #ffffff;
    --gray-50: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-400: #9ca3af;
    --gray-600: #4b5563;
    --gray-800: #1f2937;
    --text: #111827;
    --text-soft: #6b7280;
    --radius: 16px;
    --radius-sm: 10px;
    --shadow: 0 4px 24px rgba(15,92,78,.10);
    --shadow-lg: 0 12px 48px rgba(15,92,78,.16);
    --font: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
}

body { font-family: var(--font); color: var(--text); background: var(--white); }

.lp-container { max-width: 1200px; margin: 0 auto; padding: 0 24px; }

/* ── Buttons ── */
.lp-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 13px 26px; border-radius: 50px; font-weight: 600;
    font-size: 0.93rem; cursor: pointer; text-decoration: none;
    border: 2px solid transparent; transition: all .2s;
}
.lp-btn-primary { background: var(--primary); color: #fff; }
.lp-btn-primary:hover { background: var(--primary-mid); transform: translateY(-1px); box-shadow: 0 6px 20px rgba(15,92,78,.30); }
.lp-btn-outline { background: transparent; color: var(--primary); border-color: var(--primary); }
.lp-btn-outline:hover { background: var(--primary-pale); }
.lp-btn-white { background: #fff; color: var(--primary); }
.lp-btn-white:hover { background: var(--primary-pale); }

/* ════════════════════════════════
   HERO
   ════════════════════════════════ */
.lp-hero {
    background: linear-gradient(135deg, var(--gray-50) 0%, #e8f5f2 100%);
    padding: 80px 0 60px;
    overflow: hidden;
    position: relative;
}
.lp-hero::before {
    content: '';
    position: absolute; top: -80px; right: -80px;
    width: 500px; height: 500px;
    background: radial-gradient(circle, rgba(20,184,166,.12) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}
.lp-hero-inner {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 60px; align-items: center;
}
.lp-hero-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: #d1fae5; color: #065f46;
    font-size: 0.78rem; font-weight: 600; letter-spacing: .3px;
    padding: 6px 14px; border-radius: 50px; margin-bottom: 20px;
}
.lp-hero-badge svg { flex-shrink: 0; }
.lp-hero-title {
    font-size: clamp(2rem, 3.5vw, 2.8rem);
    font-weight: 800; line-height: 1.18; color: var(--text);
    margin-bottom: 18px;
}
.lp-hero-title .grad { color: var(--primary); }
.lp-hero-subtitle {
    font-size: 0.98rem; color: var(--text-soft);
    line-height: 1.7; margin-bottom: 28px; max-width: 480px;
}
.lp-hero-actions { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 40px; }
.lp-hero-stats { display: flex; gap: 32px; flex-wrap: wrap; }
.lp-stat-num { font-size: 1.6rem; font-weight: 800; color: var(--primary); line-height: 1; }
.lp-stat-lbl { font-size: 0.78rem; color: var(--text-soft); margin-top: 3px; }

/* ── Hero Visual: Logo House ── */
.lp-hero-visual {
    display: flex; align-items: center; justify-content: center;
    position: relative;
}
.lp-hero-logo-wrap {
    width: 340px; height: 340px;
    background: linear-gradient(145deg, var(--primary) 0%, var(--primary-light) 100%);
    border-radius: 36px;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    box-shadow: var(--shadow-lg);
    position: relative; overflow: hidden;
}
.lp-hero-logo-wrap::before {
    content: '';
    position: absolute; top: -40px; right: -40px;
    width: 180px; height: 180px;
    background: rgba(255,255,255,.08); border-radius: 50%;
}
.lp-hero-logo-wrap::after {
    content: '';
    position: absolute; bottom: -30px; left: -30px;
    width: 140px; height: 140px;
    background: rgba(255,255,255,.06); border-radius: 50%;
}
.lp-house-icon { position: relative; z-index: 1; }
.lp-house-icon svg { width: 120px; height: 120px; filter: drop-shadow(0 8px 24px rgba(0,0,0,.20)); }
.lp-hero-logo-label {
    position: relative; z-index: 1;
    color: #fff; font-size: 1.4rem; font-weight: 800;
    margin-top: 16px; letter-spacing: .5px;
}
.lp-hero-logo-sublabel {
    position: relative; z-index: 1;
    color: rgba(255,255,255,.75); font-size: 0.82rem;
    margin-top: 6px;
}

/* Floating cards on hero */
.lp-float-card {
    position: absolute;
    background: #fff; border-radius: var(--radius-sm);
    padding: 10px 14px; box-shadow: var(--shadow);
    display: flex; align-items: center; gap: 10px;
    font-size: 0.82rem; font-weight: 600; color: var(--text);
    animation: lp-float 4s ease-in-out infinite;
}
.lp-float-card-1 { top: 20px; left: -20px; animation-delay: 0s; }
.lp-float-card-2 { bottom: 40px; right: -20px; animation-delay: 2s; }
.lp-float-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--primary-light); flex-shrink: 0; }
@keyframes lp-float {
    0%,100% { transform: translateY(0); }
    50% { transform: translateY(-8px); }
}

/* ════════════════════════════════
   PROPERTY SHOWCASE STRIP
   ════════════════════════════════ */
.lp-showcase { padding: 48px 0; background: #fff; }
.lp-showcase-label {
    text-align: center; font-size: 0.78rem; font-weight: 600;
    color: var(--text-soft); letter-spacing: 1.5px; text-transform: uppercase;
    margin-bottom: 28px;
}

/* ════════════════════════════════
   SECTION BASE
   ════════════════════════════════ */
.lp-section { padding: 80px 0; }
.lp-section-light { background: var(--gray-50); }
.lp-section-dark { background: var(--primary); }
.lp-section-cta {
    background: linear-gradient(135deg, var(--primary) 0%, #1a7a68 100%);
}

.lp-section-tag {
    display: inline-block; font-size: 0.78rem; font-weight: 700;
    letter-spacing: 1px; text-transform: uppercase; color: var(--primary);
    margin-bottom: 12px;
}
.lp-section-tag-light { color: var(--accent); }
.lp-section-title {
    font-size: clamp(1.6rem, 2.5vw, 2.2rem);
    font-weight: 800; line-height: 1.25; color: var(--text);
    margin-bottom: 14px;
}
.lp-section-title.lp-white { color: #fff; }
.lp-section-desc { color: var(--text-soft); font-size: 0.97rem; line-height: 1.7; }
.lp-section-desc.lp-light { color: rgba(255,255,255,.75); }
.lp-section-header { margin-bottom: 48px; }
.lp-section-header.lp-center { text-align: center; }

/* ════════════════════════════════
   PROPERTY CARDS GRID
   ════════════════════════════════ */
.lp-prop-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
}
.lp-prop-card {
    background: #fff; border-radius: var(--radius);
    overflow: hidden; box-shadow: 0 2px 16px rgba(0,0,0,.07);
    transition: transform .25s, box-shadow .25s;
    text-decoration: none; color: inherit;
    display: block;
}
.lp-prop-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); }
.lp-prop-img {
    position: relative; height: 200px; overflow: hidden;
    background: var(--gray-100);
}
.lp-prop-img img {
    width: 100%; height: 100%; object-fit: cover;
    transition: transform .4s;
}
.lp-prop-card:hover .lp-prop-img img { transform: scale(1.04); }
.lp-prop-no-img {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
    color: var(--gray-400);
}
.lp-prop-badge {
    position: absolute; top: 12px; left: 12px;
    background: var(--primary); color: #fff;
    font-size: 0.7rem; font-weight: 700; letter-spacing: .5px;
    text-transform: uppercase;
    padding: 4px 10px; border-radius: 6px;
}
.lp-prop-body { padding: 18px 20px 20px; }
.lp-prop-price {
    font-size: 1.15rem; font-weight: 800; color: var(--primary);
    margin-bottom: 6px;
}
.lp-prop-name {
    font-size: 0.93rem; font-weight: 600; color: var(--text);
    margin-bottom: 8px; line-height: 1.4;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
    overflow: hidden;
}
.lp-prop-loc {
    font-size: 0.82rem; color: var(--text-soft); margin-bottom: 12px;
    display: flex; align-items: center; gap: 4px;
}
.lp-prop-meta {
    display: flex; flex-wrap: wrap; gap: 10px;
    font-size: 0.78rem; color: var(--gray-600);
    border-top: 1px solid var(--gray-100); padding-top: 12px;
}
.lp-prop-meta span { display: flex; align-items: center; gap: 4px; }

/* ════════════════════════════════
   FEATURES GRID
   ════════════════════════════════ */
.lp-feat-grid {
    display: grid; grid-template-columns: repeat(3, 1fr);
    gap: 24px;
}
.lp-feat-card {
    background: #fff; border-radius: var(--radius);
    padding: 28px 24px; box-shadow: 0 2px 16px rgba(0,0,0,.05);
    transition: transform .2s, box-shadow .2s;
}
.lp-feat-card:hover { transform: translateY(-3px); box-shadow: var(--shadow); }
.lp-feat-card.lp-feat-highlight {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-mid) 100%);
    color: #fff;
}
.lp-feat-icon {
    width: 48px; height: 48px; border-radius: 14px;
    background: var(--primary-pale); display: flex; align-items: center; justify-content: center;
    margin-bottom: 16px; font-size: 1.4rem;
}
.lp-feat-highlight .lp-feat-icon { background: rgba(255,255,255,.18); }
.lp-feat-title { font-size: 1rem; font-weight: 700; margin-bottom: 8px; }
.lp-feat-desc { font-size: 0.87rem; color: var(--text-soft); line-height: 1.65; }
.lp-feat-highlight .lp-feat-desc { color: rgba(255,255,255,.8); }
.lp-feat-highlight .lp-feat-title { color: #fff; }

/* ════════════════════════════════
   HOW IT WORKS
   ════════════════════════════════ */
.lp-how-inner {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 60px; align-items: start;
}
.lp-how-steps { display: flex; flex-direction: column; gap: 0; }
.lp-step {
    display: flex; gap: 20px; align-items: flex-start;
    padding: 24px 0;
    border-bottom: 1px solid var(--gray-100);
}
.lp-step:last-child { border-bottom: none; }
.lp-step-num {
    width: 44px; height: 44px; border-radius: 12px;
    background: var(--primary); color: #fff;
    font-size: 1rem; font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.lp-step-title { font-size: 0.97rem; font-weight: 700; margin-bottom: 6px; }
.lp-step-desc { font-size: 0.87rem; color: var(--text-soft); line-height: 1.6; }
.lp-how-visual {
    background: var(--gray-50); border-radius: var(--radius);
    padding: 32px; display: flex; flex-direction: column; gap: 16px;
}
.lp-how-tag { font-size: 0.78rem; font-weight: 700; color: var(--primary); letter-spacing: 1px; text-transform: uppercase; margin-bottom: 4px; }
.lp-how-title { font-size: 1.1rem; font-weight: 800; margin-bottom: 16px; }
.lp-how-stat-row { display: flex; gap: 16px; }
.lp-how-stat {
    flex: 1; background: #fff; border-radius: var(--radius-sm);
    padding: 16px; text-align: center;
    box-shadow: 0 2px 8px rgba(0,0,0,.05);
}
.lp-how-stat-val { font-size: 1.4rem; font-weight: 800; color: var(--primary); }
.lp-how-stat-lbl { font-size: 0.75rem; color: var(--text-soft); margin-top: 2px; }
.lp-how-bar-wrap { margin-top: 8px; }
.lp-how-bar-lbl { display: flex; justify-content: space-between; font-size: 0.78rem; color: var(--text-soft); margin-bottom: 6px; }
.lp-how-bar { height: 8px; background: var(--gray-200); border-radius: 99px; overflow: hidden; margin-bottom: 10px; }
.lp-how-bar-fill { height: 100%; border-radius: 99px; background: linear-gradient(90deg, var(--primary) 0%, var(--primary-light) 100%); }

/* ════════════════════════════════
   CALCULATOR
   ════════════════════════════════ */
.lp-calc-inner {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 60px; align-items: start;
}
.lp-calc-info .lp-section-title { color: #fff; }
.lp-calc-bullets { margin-top: 24px; display: flex; flex-direction: column; gap: 12px; }
.lp-calc-bullet { display: flex; align-items: flex-start; gap: 10px; }
.lp-calc-bullet-icon {
    width: 24px; height: 24px; border-radius: 50%;
    background: rgba(255,255,255,.15); color: #fff;
    font-size: 0.8rem; display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; margin-top: 1px;
}
.lp-calc-bullet-text { font-size: 0.9rem; color: rgba(255,255,255,.85); line-height: 1.5; }
.lp-calc-card {
    background: #fff; border-radius: var(--radius);
    padding: 32px; box-shadow: var(--shadow-lg);
}
.lp-calc-card h3 { font-size: 1rem; font-weight: 700; margin-bottom: 20px; color: var(--text); }
.lp-form-group { margin-bottom: 16px; }
.lp-form-group label { font-size: 0.83rem; font-weight: 600; color: var(--gray-600); margin-bottom: 6px; display: block; }
.lp-input-wrap { position: relative; }
.lp-input-prefix {
    position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
    font-size: 0.85rem; color: var(--text-soft); font-weight: 500;
}
.lp-form-group input,
.lp-form-group select {
    width: 100%; padding: 11px 14px 11px 36px;
    border: 1.5px solid var(--gray-200); border-radius: var(--radius-sm);
    font-size: 0.9rem; color: var(--text); background: #fff;
    outline: none; transition: border-color .2s;
    font-family: var(--font);
}
.lp-form-group select { padding-left: 12px; }
.lp-form-group input:focus,
.lp-form-group select:focus { border-color: var(--primary); }
.lp-form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.lp-calc-result {
    margin-top: 16px; padding: 18px;
    background: var(--primary-pale); border-radius: var(--radius-sm);
    display: none;
}
.lp-result-title { font-size: 0.85rem; font-weight: 700; color: var(--primary); margin-bottom: 12px; }
.lp-result-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
.lp-result-item { background: #fff; border-radius: 8px; padding: 12px; text-align: center; }
.lp-result-lbl { font-size: 0.72rem; color: var(--text-soft); margin-bottom: 4px; }
.lp-result-val { font-size: 0.88rem; font-weight: 800; color: var(--primary); }
.lp-result-action { margin-top: 14px; text-align: center; }

/* ════════════════════════════════
   CTA
   ════════════════════════════════ */
.lp-cta-inner { text-align: center; max-width: 560px; margin: 0 auto; }
.lp-cta-inner .lp-section-title { color: #fff; }
.lp-cta-inner .lp-section-desc { color: rgba(255,255,255,.8); margin-bottom: 28px; }
.lp-cta-stats {
    display: flex; justify-content: center; gap: 40px;
    margin-top: 40px;
}
.lp-cta-stat-val { font-size: 1.5rem; font-weight: 800; color: #fff; }
.lp-cta-stat-lbl { font-size: 0.8rem; color: rgba(255,255,255,.7); }

/* ════════════════════════════════
   CONTACT
   ════════════════════════════════ */
.lp-contact-grid {
    display: grid; grid-template-columns: 1fr 1.4fr;
    gap: 48px; align-items: start;
}
.lp-contact-items { display: flex; flex-direction: column; gap: 20px; }
.lp-contact-item { display: flex; gap: 16px; align-items: flex-start; }
.lp-contact-icon {
    width: 44px; height: 44px; border-radius: 12px;
    background: var(--primary-pale); display: flex; align-items: center;
    justify-content: center; font-size: 1.1rem; flex-shrink: 0;
}
.lp-contact-label { font-size: 0.78rem; color: var(--text-soft); margin-bottom: 2px; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; }
.lp-contact-val { font-size: 0.93rem; font-weight: 600; color: var(--text); }
.lp-contact-form { background: #fff; border-radius: var(--radius); padding: 32px; box-shadow: var(--shadow); }
.lp-contact-form h3 { font-size: 1.1rem; font-weight: 700; margin-bottom: 4px; }
.lp-contact-form p { font-size: 0.87rem; color: var(--text-soft); margin-bottom: 20px; }
.lp-contact-form .lp-form-group input,
.lp-contact-form .lp-form-group textarea {
    padding: 11px 14px; /* no prefix icon */
}
.lp-contact-form textarea {
    width: 100%; padding: 11px 14px;
    border: 1.5px solid var(--gray-200); border-radius: var(--radius-sm);
    font-size: 0.9rem; color: var(--text); background: #fff;
    outline: none; resize: vertical; min-height: 100px;
    font-family: var(--font); transition: border-color .2s;
}
.lp-contact-form textarea:focus { border-color: var(--primary); }
.lp-form-success {
    background: #d1fae5; color: #065f46;
    border-radius: 8px; padding: 10px 14px;
    font-size: 0.87rem; font-weight: 600; margin-top: 12px;
}

/* ════════════════════════════════
   FOOTER
   ════════════════════════════════ */
.lp-footer {
    background: var(--gray-800); color: rgba(255,255,255,.75);
    padding: 48px 0 24px;
}
.lp-footer-grid {
    display: grid; grid-template-columns: 1.5fr 1fr 1fr 1fr;
    gap: 40px; margin-bottom: 40px;
}
.lp-footer-brand { font-size: 1.3rem; font-weight: 800; color: #fff; margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
.lp-footer-tagline { font-size: 0.85rem; line-height: 1.65; margin-bottom: 20px; }
.lp-footer-social { display: flex; gap: 10px; }
.lp-footer-social a {
    width: 36px; height: 36px; border-radius: 8px;
    background: rgba(255,255,255,.1); display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 1rem; transition: background .2s; text-decoration: none;
}
.lp-footer-social a:hover { background: var(--primary); }
.lp-footer-col h4 { font-size: 0.85rem; font-weight: 700; color: #fff; margin-bottom: 14px; text-transform: uppercase; letter-spacing: .5px; }
.lp-footer-col ul { list-style: none; display: flex; flex-direction: column; gap: 8px; }
.lp-footer-col ul li a { font-size: 0.85rem; color: rgba(255,255,255,.65); text-decoration: none; transition: color .2s; }
.lp-footer-col ul li a:hover { color: var(--accent); }
.lp-footer-bottom { border-top: 1px solid rgba(255,255,255,.1); padding-top: 20px; display: flex; justify-content: space-between; align-items: center; }
.lp-footer-copy { font-size: 0.8rem; }
.lp-footer-links { display: flex; gap: 20px; }
.lp-footer-links a { font-size: 0.8rem; color: rgba(255,255,255,.55); text-decoration: none; }

/* ── Btn block ── */
.lp-btn-block { width: 100%; justify-content: center; }

/* ── Responsive ── */
@media (max-width: 900px) {
    .lp-hero-inner, .lp-how-inner, .lp-calc-inner, .lp-contact-grid { grid-template-columns: 1fr; }
    .lp-feat-grid, .lp-prop-grid { grid-template-columns: repeat(2, 1fr); }
    .lp-footer-grid { grid-template-columns: 1fr 1fr; }
    .lp-hero-visual { justify-content: flex-start; }
    .lp-float-card-1 { display: none; }
    .lp-how-inner { gap: 32px; }
    .lp-calc-inner { gap: 32px; }
}
@media (max-width: 600px) {
    .lp-feat-grid, .lp-prop-grid { grid-template-columns: 1fr; }
    .lp-hero-logo-wrap { width: 260px; height: 260px; }
    .lp-footer-grid { grid-template-columns: 1fr; }
    .lp-cta-stats { gap: 20px; flex-wrap: wrap; }
    .lp-hero-stats { gap: 20px; }
    .lp-form-row { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')

{{-- ════════════════════════
     HERO
     ════════════════════════ --}}
<section class="lp-hero">
    <div class="lp-container">
        <div class="lp-hero-inner">
            {{-- Left: Text --}}
            <div class="lp-hero-content">
                <div class="lp-hero-badge">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M9 11l3 3L22 4"/>
                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                    </svg>
                    Project Akhir — Sistem Pendukung Keputusan
                </div>

                <h1 class="lp-hero-title">
                    Temukan <span class="grad">Rumah Impian</span><br>
                    Sesuai Kemampuan<br>Finansial Kamu
                </h1>

                <p class="lp-hero-subtitle">
                    Sistem cerdas berbasis K-Nearest Neighbors (KNN) yang merekomendasikan rumah terbaik berdasarkan budget, lokasi, dan preferensi keluarga Anda.
                </p>

                <div class="lp-hero-actions">
                    <a href="#calculator" class="lp-btn lp-btn-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <rect x="4" y="2" width="16" height="20" rx="2"/>
                            <line x1="8" y1="6" x2="16" y2="6"/>
                            <line x1="8" y1="10" x2="16" y2="10"/>
                        </svg>
                        Hitung Budget Sekarang
                    </a>
                    <a href="#how" class="lp-btn lp-btn-outline">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M10 15l5-3-5-3v6z"/>
                        </svg>
                        Lihat Cara Kerja
                    </a>
                </div>

                <div class="lp-hero-stats">
                    <div>
                        <div class="lp-stat-num">{{ $totalRumah ?? '939' }}</div>
                        <div class="lp-stat-lbl">Data Properti</div>
                    </div>
                    <div>
                        <div class="lp-stat-num">95%</div>
                        <div class="lp-stat-lbl">Akurasi Prediksi</div>
                    </div>
                    <div>
                        <div class="lp-stat-num">{{ $totalLokasi ?? '939' }}</div>
                        <div class="lp-stat-lbl">Kota Tercakup</div>
                    </div>
                </div>
            </div>

            {{-- Right: Logo House --}}
            <div class="lp-hero-visual">
                <div class="lp-hero-logo-wrap">
                    <div class="lp-house-icon">
                        <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="8" y="45" width="84" height="48" rx="4" fill="rgba(255,255,255,0.25)"/>
                            <polygon points="50,8 5,50 95,50" fill="rgba(255,255,255,0.9)"/>
                            <rect x="38" y="62" width="24" height="30" rx="3" fill="rgba(255,255,255,0.5)"/>
                            <rect x="14" y="56" width="20" height="16" rx="2" fill="rgba(255,255,255,0.6)"/>
                            <rect x="66" y="56" width="20" height="16" rx="2" fill="rgba(255,255,255,0.6)"/>
                            <circle cx="75" cy="10" r="8" fill="rgba(255,255,255,0.15)"/>
                            <circle cx="20" cy="30" r="5" fill="rgba(255,255,255,0.10)"/>
                        </svg>
                    </div>
                    <div class="lp-hero-logo-label">RumahKu</div>
                    <div class="lp-hero-logo-sublabel">Sistem Rekomendasi Properti</div>
                </div>

                <div class="lp-float-card lp-float-card-1">
                    <div class="lp-float-dot"></div>
                    <span>AI Recommendation</span>
                </div>
                <div class="lp-float-card lp-float-card-2">
                    <div class="lp-float-dot"></div>
                    <span>{{ $totalRumah ?? '939' }} Properti Tersedia</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ════════════════════════
     PROPERTI TERMAHAL
     ════════════════════════ --}}
<section id="properti" class="lp-section lp-section-light">
    <div class="lp-container">
        <div class="lp-section-header" style="display:flex;justify-content:space-between;align-items:flex-end;flex-wrap:wrap;gap:16px;">
            
            @auth
            <a href="{{ route('properti.browse') }}" class="lp-btn lp-btn-outline">Lihat Semua Properti →</a>
            @endauth
        </div>

        <div class="lp-prop-grid">
            @forelse($mostExpensiveRumah ?? [] as $rumah)
            <div class="lp-prop-card">
                <div class="lp-prop-img">
                    @php $foto = is_array($rumah->foto) ? ($rumah->foto[0] ?? null) : $rumah->foto; @endphp
                    @if($foto && str_starts_with($foto, 'http'))
                        <img src="{{ $foto }}" alt="{{ $rumah->nama }}" loading="lazy">
                    @elseif($foto)
                        <img src="{{ asset($foto) }}" alt="{{ $rumah->nama }}" loading="lazy">
                    @else
                        <div class="lp-prop-no-img">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                                <polyline points="9 22 9 12 15 12 15 22"/>
                            </svg>
                        </div>
                    @endif
                    <div class="lp-prop-badge">{{ strtoupper($rumah->tipe ?? 'Rumah') }}</div>
                </div>
                <div class="lp-prop-body">
                    <div class="lp-prop-price">Rp {{ number_format($rumah->harga ?? 0, 0, ',', '.') }}</div>
                    <div class="lp-prop-loc">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        {{ $rumah->lokasi ?? '-' }}
                    </div>
                    <div class="lp-prop-meta">
                        <span>
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/></svg>
                            {{ $rumah->luas_tanah ?? '-' }}m²
                        </span>
                        <span>
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                            {{ $rumah->luas_bangunan ?? '-' }}m²
                        </span>
                        <span>
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 4v16"/><path d="M22 4v16"/><path d="M6 8h12"/><path d="M6 16h12"/></svg>
                            {{ $rumah->kamar_tidur ?? '-' }} KT
                        </span>
                        <span>
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 6 6.5 3.5a1.5 1.5 0 0 0-1-.5C4.683 3 4 3.683 4 4.5V17a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5"/><line x1="10" y1="5" x2="8" y2="7"/></svg>
                            {{ $rumah->kamar_mandi ?? '-' }} KM
                        </span>
                    </div>
                </div>
            </div>
            @empty
            <div style="grid-column:1/-1;text-align:center;padding:3rem;color:var(--text-soft);">
                <p>Belum ada data properti. Silakan import data melalui Admin Panel.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

{{-- ════════════════════════
     FEATURES
     ════════════════════════ --}}
<section id="features" class="lp-section">
    <div class="lp-container">
        <div class="lp-section-header lp-center">
            <span class="lp-section-tag">Fitur Unggulan</span>
            <h2 class="lp-section-title">Sistem Rekomendasi yang<br>Memahami Kebutuhan Kamu</h2>
            <p class="lp-section-desc">Kombinasi algoritma machine learning dan metode pengambilan keputusan untuk hasil yang optimal.</p>
        </div>

        <div class="lp-feat-grid">
            <div class="lp-feat-card">
                <div class="lp-feat-icon">📊</div>
                <h3 class="lp-feat-title">Prediksi Harga Akurat</h3>
                <p class="lp-feat-desc">Algoritma KNN memprediksi harga rumah berdasarkan lokasi, luas tanah, bangunan, dan fasilitas sekitar.</p>
            </div>
            <div class="lp-feat-card">
                <div class="lp-feat-icon">🎯</div>
                <h3 class="lp-feat-title">Rekomendasi Personal</h3>
                <p class="lp-feat-desc">Metode SAW/TOPSIS memberikan ranking rumah terbaik yang sesuai budget dan preferensi keluarga Anda.</p>
            </div>
            <div class="lp-feat-card">
                <div class="lp-feat-icon">🔍</div>
                <h3 class="lp-feat-title">Filter Cerdas</h3>
                <p class="lp-feat-desc">Filter berdasarkan lokasi, range harga, tipe rumah, cicilan KPR, jarak ke fasilitas umum, dan banyak lagi.</p>
            </div>
            <div class="lp-feat-card lp-feat-highlight" style="grid-column: 1 / 3;">
                <div class="lp-feat-icon">💰</div>
                <h3 class="lp-feat-title">Analisis Kemampuan Finansial</h3>
                <p class="lp-feat-desc">Hitung kemampuan cicilan berdasarkan pendapatan keluarga, tanggungan, dan profil finansial lengkap.</p>
                <a href="#calculator" class="lp-btn lp-btn-white" style="margin-top:18px;">Coba Sekarang →</a>
            </div>
            <div class="lp-feat-card">
                <div class="lp-feat-icon">📱</div>
                <h3 class="lp-feat-title">Akses Web & Mobile</h3>
                <p class="lp-feat-desc">Tersedia dalam versi web dan mobile untuk kemudahan akses kapan saja, di mana saja.</p>
            </div>
        </div>
    </div>
</section>

{{-- ════════════════════════
     HOW IT WORKS
     ════════════════════════ --}}
<section id="how" class="lp-section lp-section-light">
    <div class="lp-container">
        <div class="lp-how-inner">
            <div>
                <span class="lp-section-tag">Cara Kerja</span>
                <h2 class="lp-section-title">Temukan Rumah Impian<br>dalam 4 Langkah Mudah</h2>
                <p class="lp-section-desc">Proses yang simpel dan cepat untuk mendapatkan rekomendasi rumah terbaik.</p>
            </div>
            <div></div>
        </div>
        <div class="lp-how-inner" style="margin-top: 0;">
            <div class="lp-how-steps">
                @php
                $steps = [
                    ['num'=>'1','title'=>'Input Profil Finansial','desc'=>'Masukkan data pendapatan, tanggungan, cicilan existing, dan kemampuan uang muka.'],
                    ['num'=>'2','title'=>'Pilih Kriteria & Lokasi','desc'=>'Tentukan lokasi, tipe rumah, range harga, dan prioritas fasilitas yang diinginkan.'],
                    ['num'=>'3','title'=>'Sistem Analisis Data','desc'=>'Algoritma KNN memprediksi harga dan SAW/TOPSIS meranking properti sesuai kriteria.'],
                    ['num'=>'4','title'=>'Dapatkan Rekomendasi','desc'=>'Lihat daftar rumah terekomendasikan dengan skor kelayakan dan estimasi cicilan KPR.'],
                ];
                @endphp
                @foreach($steps as $step)
                <div class="lp-step">
                    <div class="lp-step-num">{{ $step['num'] }}</div>
                    <div>
                        <div class="lp-step-title">{{ $step['title'] }}</div>
                        <div class="lp-step-desc">{{ $step['desc'] }}</div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="lp-how-visual">
                <div class="lp-how-tag">Statistik Sistem</div>
                <div class="lp-how-title">Performa RumahKu</div>
                <div class="lp-how-stat-row">
                    <div class="lp-how-stat">
                        <div class="lp-how-stat-val">{{ $totalRumah ?? '939' }}</div>
                        <div class="lp-how-stat-lbl">Total Properti</div>
                    </div>
                    <div class="lp-how-stat">
                        <div class="lp-how-stat-val">95%</div>
                        <div class="lp-how-stat-lbl">Akurasi Model</div>
                    </div>
                    <div class="lp-how-stat">
                        <div class="lp-how-stat-val">{{ $totalLokasi ?? '10' }}</div>
                        <div class="lp-how-stat-lbl">Kota Tercakup</div>
                    </div>
                </div>
                <div class="lp-how-bar-wrap" style="margin-top:20px;">
                    <div class="lp-how-bar-lbl"><span>Akurasi Prediksi Harga</span><span>95%</span></div>
                    <div class="lp-how-bar"><div class="lp-how-bar-fill" style="width:95%"></div></div>
                    <div class="lp-how-bar-lbl"><span>Kepuasan Pengguna</span><span>92%</span></div>
                    <div class="lp-how-bar"><div class="lp-how-bar-fill" style="width:92%"></div></div>
                    <div class="lp-how-bar-lbl"><span>Kelengkapan Data</span><span>88%</span></div>
                    <div class="lp-how-bar"><div class="lp-how-bar-fill" style="width:88%"></div></div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ════════════════════════
     CALCULATOR
     ════════════════════════ --}}
<section id="calculator" class="lp-section lp-section-dark">
    <div class="lp-container">
        <div class="lp-calc-inner">
            <div class="lp-calc-info">
                <span class="lp-section-tag lp-section-tag-light">Kalkulator Budget</span>
                <h2 class="lp-section-title lp-white">Kalkulator<br>Kemampuan Membeli<br>Properti</h2>
                <div class="lp-calc-bullets">
                    <div class="lp-calc-bullet">
                        <div class="lp-calc-bullet-icon">✓</div>
                        <div class="lp-calc-bullet-text">Analisis Cicilan Bulanan Ideal</div>
                    </div>
                    <div class="lp-calc-bullet">
                        <div class="lp-calc-bullet-icon">✓</div>
                        <div class="lp-calc-bullet-text">Estimasi Tenor dan Suku Bunga</div>
                    </div>
                    <div class="lp-calc-bullet">
                        <div class="lp-calc-bullet-icon">✓</div>
                        <div class="lp-calc-bullet-text">Rekomendasi Alokasi DP (Down Payment)</div>
                    </div>
                </div>
            </div>

            <div class="lp-calc-card">
                <h3>💰 Hitung Kemampuan Beli</h3>
                <div class="lp-form-row">
                    <div class="lp-form-group">
                        <label>Penghasilan Bulanan</label>
                        <div class="lp-input-wrap">
                            <span class="lp-input-prefix">Rp</span>
                            <input type="text" id="lp_penghasilan" placeholder="10.000.000" step="100000" oninput="formatRupiah(this)"/>
                        </div>
                    </div>
                    <div class="lp-form-group">
                        <label>Uang Muka (DP)</label>
                        <div class="lp-input-wrap">
                            <span class="lp-input-prefix">Rp</span>
                            <input type="text" id="lp_dp" placeholder="50.000.000" step="1000000" oninput="formatRupiah(this)"/>
                        </div>
                    </div>
                </div>
                <div class="lp-form-row">
                    <div class="lp-form-group">
                        <label>Tenor (Tahun)</label>
                        <select id="lp_tenor">
                            <option value="5">5 Tahun</option>
                            <option value="10">10 Tahun</option>
                            <option value="15" selected>15 Tahun</option>
                            <option value="20">20 Tahun</option>
                        </select>
                    </div>
                    <div class="lp-form-group">
                        <label>Suku Bunga (%/tahun)</label>
                        <div class="lp-input-wrap">
                            <span class="lp-input-prefix">%</span>
                            <input type="number" id="lp_bunga" placeholder="8" step="0.5" value="8" style="padding-left:36px;"/>
                        </div>
                    </div>
                </div>
                <button type="button" id="lp_hitungBtn" class="lp-btn lp-btn-primary lp-btn-block">
                    Hitung Kemampuan Beli
                </button>

                <div class="lp-calc-result" id="lp_result">
                    <div class="lp-result-title">📊 Hasil Analisis Budget</div>
                    <div class="lp-result-grid">
                        <div class="lp-result-item">
                            <div class="lp-result-lbl">Budget Rumah</div>
                            <div class="lp-result-val" id="lp_resBudget">-</div>
                        </div>
                        <div class="lp-result-item">
                            <div class="lp-result-lbl">Cicilan/Bulan</div>
                            <div class="lp-result-val" id="lp_resCicilan">-</div>
                        </div>
                        <div class="lp-result-item">
                            <div class="lp-result-lbl">Sisa Pendapatan</div>
                            <div class="lp-result-val" id="lp_resSisa">-</div>
                        </div>
                    </div>
                    <div class="lp-result-action">
                        @auth
                        <a href="{{ route('properti.browse') }}" class="lp-btn lp-btn-primary">Cari Rumah Sesuai Budget</a>
                        @else
                        <a href="{{ route('register') }}" class="lp-btn lp-btn-primary">Daftar & Cari Rumah</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ════════════════════════
     CTA
     ════════════════════════ --}}
<section class="lp-section lp-section-cta">
    <div class="lp-container">
        <div class="lp-cta-inner">
            <span class="lp-section-tag lp-section-tag-light">Gratis & Mudah</span>
            <h2 class="lp-section-title lp-white">Siap Temukan<br>Rumah Impianmu?</h2>
            <p class="lp-section-desc lp-light">
                Bergabunglah dengan ribuan pengguna yang telah menemukan properti terbaik mereka menggunakan AI RumahKu.
            </p>
            @auth
            <a href="{{ route('rekomendasi') }}" class="lp-btn lp-btn-white">
                Mulai Rekomendasi AI →
            </a>
            @else
            <a href="{{ route('register') }}" class="lp-btn lp-btn-white">
                Daftar Sekarang →
            </a>
            @endauth
        </div>
    </div>
</section>

{{-- ════════════════════════
     CONTACT
     ════════════════════════ --}}
<section id="contact" class="lp-section lp-section-light">
    <div class="lp-container">
        <div class="lp-section-header lp-center">
            <span class="lp-section-tag">Hubungi Kami</span>
            <h2 class="lp-section-title">Ada Pertanyaan?<br>Kami Siap Membantu</h2>
            <p class="lp-section-desc">Kami siap memberikan panduan terbaik dalam mencari dan menentukan properti impian Anda.</p>
        </div>

        <div class="lp-contact-grid">
            <div class="lp-contact-items">
                <div class="lp-contact-item">
                    <div class="lp-contact-icon">📧</div>
                    <div>
                        <div class="lp-contact-label">Email</div>
                        <div class="lp-contact-val">suport@rumahku.id</div>
                    </div>
                </div>
                <div class="lp-contact-item">
                    <div class="lp-contact-icon">📞</div>
                    <div>
                        <div class="lp-contact-label">WhatsApp</div>
                        <div class="lp-contact-val">+62 812-0011-2234</div>
                    </div>
                </div>
                <div class="lp-contact-item">
                    <div class="lp-contact-icon">📍</div>
                    <div>
                        <div class="lp-contact-label">Lokasi</div>
                        <div class="lp-contact-val"> Mastrip, Jember, Jawa Timur</div>
                    </div>
                </div>
                <div class="lp-contact-item">
                    <div class="lp-contact-icon">⏰</div>
                    <div>
                        <div class="lp-contact-label">Jam Operasional</div>
                        <div class="lp-contact-val">Senin – Jumat, 08.00 – 17.00 WIB</div>
                    </div>
                </div>
            </div>

            <div class="lp-contact-form">
                <h3>✉️ Kirim Pesan</h3>
                <p>Sampaikan saran, masukan, atau pertanyaan Anda.</p>

                <form action="{{ route('kontak.send') }}" method="POST">
                    @csrf
                    <div class="lp-form-row">
                        <div class="lp-form-group">
                            <label>Nama Lengkap</label>
                            <div class="lp-input-wrap">
                                <input type="text" name="nama" placeholder="John Doe" required style="padding:11px 14px;">
                            </div>
                        </div>
                        <div class="lp-form-group">
                            <label>Email</label>
                            <div class="lp-input-wrap">
                                <input type="email" name="email" placeholder="john@mail.com" required style="padding:11px 14px;">
                            </div>
                        </div>
                    </div>
                    <div class="lp-form-group">
                        <label>Subjek</label>
                        <select name="subjek">
                            <option value="">Tanya Mengenai Akun/UI</option>
                            <option value="properti">Pertanyaan Properti</option>
                            <option value="teknis">Kendala Teknis</option>
                            <option value="lain">Lainnya</option>
                        </select>
                    </div>
                    <div class="lp-form-group">
                        <label>Pesan</label>
                        <textarea name="pesan" placeholder="Tuliskan pesan Anda..." required></textarea>
                    </div>
                    <button type="submit" class="lp-btn lp-btn-primary lp-btn-block">Kirim Pesan</button>
                </form>

                @if(session('success'))
                <div class="lp-form-success">{{ session('success') }}</div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    function formatRupiah(input) {
    let raw = input.value.replace(/\D/g, '');
    input.value = raw ? parseInt(raw).toLocaleString('id-ID') : '';
}

document.getElementById('lp_hitungBtn')?.addEventListener('click', function() {
    const penghasilan = parseFloat(document.getElementById('lp_penghasilan').value.replace(/\./g, '')) || 0;
    const dp          = parseFloat(document.getElementById('lp_dp').value.replace(/\./g, '')) || 0;
    const tenor       = parseInt(document.getElementById('lp_tenor').value) || 15;
    const bunga       = parseFloat(document.getElementById('lp_bunga').value) || 8;

    if (penghasilan < 1000000) { alert('Mohon isi penghasilan dengan benar!'); return; }

    const maxCicilan   = penghasilan * 0.30;
    const bungaBulanan = (bunga / 100) / 12;
    const bulan        = tenor * 12;
    const pokok        = maxCicilan * ((1 - Math.pow(1 + bungaBulanan, -bulan)) / bungaBulanan);
    const budgetRumah  = pokok + dp;
    const sisa         = penghasilan - maxCicilan;

    const fmt = v => 'Rp ' + Math.round(v).toLocaleString('id-ID');
    document.getElementById('lp_resBudget').textContent  = fmt(budgetRumah);
    document.getElementById('lp_resCicilan').textContent = fmt(maxCicilan);
    document.getElementById('lp_resSisa').textContent    = fmt(sisa);

    const res = document.getElementById('lp_result');
    res.style.display = 'block';
    res.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
});
</script>
@endpush