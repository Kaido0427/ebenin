<!DOCTYPE html> 
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $user->organization->organization_name }} | Dashboard</title>
    <link rel="shortcut icon" href="favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.css">
    <script src="https://cdn.tiny.cloud/1/b48vfvkg90ldzl0j7ik2l1xoqmo0b8ex3oresudqipdxcttg/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="https://cdn.kkiapay.me/k.js"></script>

<style>
/* ═══════════════════════════════════════════════
   VARIABLES & RESET
═══════════════════════════════════════════════ */
:root {
    --bg:        #0d0f14;
    --bg2:       #13161e;
    --bg3:       #1a1e2a;
    --border:    #252936;
    --accent:    #e63946;
    --accent2:   #ff6b6b;
    --gold:      #f4a261;
    --text:      #e8eaf0;
    --text2:     #8b90a4;
    --text3:     #555b72;
    --success:   #2ec4b6;
    --warning:   #f4a261;
    --sidebar-w: 260px;
    --header-h:  64px;
    --radius:    12px;
    --shadow:    0 4px 24px rgba(0,0,0,.4);
    --trans:     all .2s ease;
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { font-size: 15px; scroll-behavior: smooth; }
body {
    font-family: 'Sora', sans-serif;
    background: var(--bg);
    color: var(--text);
    min-height: 100vh;
    overflow-x: hidden;
}
a { text-decoration: none; color: inherit; }
button { cursor: pointer; font-family: inherit; }
input, textarea, select { font-family: inherit; }
img { max-width: 100%; }

/* ═══════════════════════════════════════════════
   SIDEBAR
═══════════════════════════════════════════════ */
.sidebar {
    position: fixed; top: 0; left: 0;
    width: var(--sidebar-w);
    height: 100vh;
    background: var(--bg2);
    border-right: 1px solid var(--border);
    display: flex; flex-direction: column;
    z-index: 200;
    transition: transform .3s cubic-bezier(.4,0,.2,1);
    overflow-y: auto;
}
.sidebar-brand {
    display: flex; align-items: center; gap: 12px;
    padding: 20px 20px 16px;
    border-bottom: 1px solid var(--border);
    min-height: var(--header-h);
}
.sidebar-brand img {
    width: 38px; height: 38px;
    border-radius: 8px; object-fit: cover;
}
.sidebar-brand-name {
    font-weight: 700; font-size: .85rem;
    color: var(--text);
    white-space: nowrap; overflow: hidden;
    text-overflow: ellipsis;
    max-width: 150px;
}
.sidebar-section {
    padding: 16px 12px 4px;
    font-size: .65rem; font-weight: 700;
    letter-spacing: .12em; text-transform: uppercase;
    color: var(--text3);
}
.sidebar-nav { list-style: none; padding: 4px 12px; }
.sidebar-nav li + li { margin-top: 2px; }
.sidebar-nav a {
    display: flex; align-items: center; gap: 10px;
    padding: 9px 12px;
    border-radius: 8px;
    font-size: .85rem; font-weight: 500;
    color: var(--text2);
    transition: var(--trans);
}
.sidebar-nav a:hover,
.sidebar-nav a.active {
    background: var(--bg3);
    color: var(--text);
}
.sidebar-nav a.active { color: var(--accent); }
.sidebar-nav svg { width: 16px; height: 16px; flex-shrink: 0; }
.sidebar-badge {
    margin-left: auto;
    background: var(--accent);
    color: #fff;
    font-size: .6rem; font-weight: 700;
    padding: 2px 6px;
    border-radius: 20px;
}
.sidebar-footer {
    margin-top: auto;
    padding: 16px 12px;
    border-top: 1px solid var(--border);
}
.sidebar-user {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 12px;
    border-radius: 8px;
    background: var(--bg3);
    cursor: pointer;
    transition: var(--trans);
    position: relative;
}
.sidebar-user:hover { background: var(--border); }
.sidebar-user img {
    width: 34px; height: 34px;
    border-radius: 50%; object-fit: cover;
    border: 2px solid var(--border);
}
.sidebar-user-info { flex: 1; min-width: 0; }
.sidebar-user-name {
    font-weight: 600; font-size: .8rem;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.sidebar-user-email {
    font-size: .68rem; color: var(--text2);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}

/* Overlay mobile */
.sidebar-overlay {
    display: none;
    position: fixed; inset: 0;
    background: rgba(0,0,0,.6);
    z-index: 199;
}
.sidebar-overlay.active { display: block; }

/* ═══════════════════════════════════════════════
   HEADER
═══════════════════════════════════════════════ */
.header {
    position: fixed;
    top: 0; left: var(--sidebar-w); right: 0;
    height: var(--header-h);
    background: var(--bg2);
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center;
    padding: 0 20px; gap: 12px;
    z-index: 100;
    transition: left .3s cubic-bezier(.4,0,.2,1);
}
.header-toggle {
    display: none;
    width: 36px; height: 36px;
    border: 1px solid var(--border);
    border-radius: 8px;
    background: var(--bg3);
    align-items: center; justify-content: center;
    color: var(--text2);
    flex-shrink: 0;
}
.header-toggle svg { width: 18px; height: 18px; }
.header-title {
    font-weight: 700; font-size: 1rem;
    color: var(--text);
    flex: 1;
}
.header-subtitle {
    font-size: .75rem; color: var(--text2);
    font-weight: 400;
}
.header-actions { display: flex; align-items: center; gap: 8px; }
.header-btn {
    width: 36px; height: 36px;
    border: 1px solid var(--border);
    border-radius: 8px;
    background: var(--bg3);
    display: flex; align-items: center; justify-content: center;
    color: var(--text2);
    transition: var(--trans);
    position: relative;
    cursor: pointer;
}
.header-btn:hover { border-color: var(--accent); color: var(--accent); }
.header-btn svg { width: 16px; height: 16px; }
.header-btn .badge {
    position: absolute; top: -4px; right: -4px;
    background: var(--accent);
    color: #fff; font-size: .55rem; font-weight: 700;
    width: 16px; height: 16px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    border: 2px solid var(--bg2);
}
.header-avatar {
    width: 34px; height: 34px;
    border-radius: 50%; object-fit: cover;
    border: 2px solid var(--border);
    cursor: pointer;
    transition: var(--trans);
}
.header-avatar:hover { border-color: var(--accent); }

/* Subscription pill */
.sub-pill {
    display: flex; align-items: center; gap: 6px;
    padding: 4px 12px 4px 8px;
    border-radius: 20px;
    font-size: .72rem; font-weight: 600;
    border: 1px solid;
    white-space: nowrap;
    cursor: pointer;
    transition: var(--trans);
}
.sub-pill.active { border-color: var(--success); color: var(--success); background: rgba(46,196,182,.08); }
.sub-pill.warning { border-color: var(--warning); color: var(--warning); background: rgba(244,162,97,.08); }
.sub-pill.expired { border-color: var(--accent); color: var(--accent); background: rgba(230,57,70,.08); }
.sub-pill svg { width: 12px; height: 12px; }
.sub-dot {
    width: 6px; height: 6px;
    border-radius: 50%;
    background: currentColor;
    animation: pulse 2s infinite;
}
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: .4; }
}

/* ═══════════════════════════════════════════════
   MAIN CONTENT
═══════════════════════════════════════════════ */
.main {
    margin-left: var(--sidebar-w);
    margin-top: var(--header-h);
    min-height: calc(100vh - var(--header-h));
    padding: 24px;
    transition: margin-left .3s cubic-bezier(.4,0,.2,1);
}

/* ═══════════════════════════════════════════════
   CARDS & STATS
═══════════════════════════════════════════════ */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}
.stat-card {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 20px;
    display: flex; align-items: center; gap: 16px;
    transition: var(--trans);
}
.stat-card:hover { border-color: var(--accent); transform: translateY(-1px); }
.stat-icon {
    width: 44px; height: 44px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.stat-icon svg { width: 20px; height: 20px; }
.stat-icon.red    { background: rgba(230,57,70,.12);   color: var(--accent); }
.stat-icon.teal   { background: rgba(46,196,182,.12);  color: var(--success); }
.stat-icon.gold   { background: rgba(244,162,97,.12);  color: var(--gold); }
.stat-icon.blue   { background: rgba(100,149,237,.12); color: #6495ed; }
.stat-value { font-size: 1.6rem; font-weight: 700; line-height: 1; }
.stat-label { font-size: .72rem; color: var(--text2); margin-top: 2px; }

/* ═══════════════════════════════════════════════
   TABLE CARD
═══════════════════════════════════════════════ */
.card {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    overflow: hidden;
}
.card-head {
    display: flex; align-items: center;
    padding: 16px 20px;
    border-bottom: 1px solid var(--border);
    gap: 12px;
    flex-wrap: wrap;
}
.card-title { font-weight: 700; font-size: .95rem; flex: 1; }
.btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px;
    border-radius: 8px;
    font-size: .8rem; font-weight: 600;
    border: none;
    transition: var(--trans);
    cursor: pointer;
    white-space: nowrap;
}
.btn svg { width: 14px; height: 14px; }
.btn-primary { background: var(--accent); color: #fff; }
.btn-primary:hover { background: var(--accent2); }
.btn-secondary { background: var(--bg3); color: var(--text); border: 1px solid var(--border); }
.btn-secondary:hover { border-color: var(--accent); color: var(--accent); }
.btn-warning { background: rgba(244,162,97,.15); color: var(--gold); border: 1px solid rgba(244,162,97,.3); }
.btn-warning:hover { background: rgba(244,162,97,.25); }
.btn-danger { background: rgba(230,57,70,.12); color: var(--accent); border: 1px solid rgba(230,57,70,.3); }
.btn-success { background: rgba(46,196,182,.12); color: var(--success); border: 1px solid rgba(46,196,182,.3); }
.btn-sm { padding: 5px 10px; font-size: .75rem; }
.btn-block { width: 100%; justify-content: center; }

/* Table */
.table-wrap { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; }
thead th {
    padding: 12px 16px;
    font-size: .72rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .08em;
    color: var(--text3);
    border-bottom: 1px solid var(--border);
    white-space: nowrap;
}
tbody td {
    padding: 14px 16px;
    font-size: .83rem;
    border-bottom: 1px solid var(--border);
    vertical-align: middle;
}
tbody tr:last-child td { border-bottom: none; }
tbody tr:hover { background: var(--bg3); }
.post-title-cell { max-width: 300px; }
.post-title-text {
    font-weight: 500;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    display: block;
}
.post-cat {
    display: inline-block;
    padding: 2px 8px;
    background: var(--bg3);
    border-radius: 20px;
    font-size: .65rem;
    color: var(--text2);
    margin-top: 2px;
}
.actions-cell { display: flex; gap: 6px; justify-content: flex-end; white-space: nowrap; }
.empty-state {
    text-align: center;
    padding: 48px 24px;
    color: var(--text3);
}
.empty-state svg { width: 40px; height: 40px; margin-bottom: 12px; opacity: .4; }
.empty-state p { font-size: .85rem; }

/* Pagination */
.pagination-wrap { padding: 16px 20px; border-top: 1px solid var(--border); }
.pagination-wrap .pagination { margin: 0; gap: 4px; display: flex; flex-wrap: wrap; }
.page-link { background: var(--bg3) !important; border-color: var(--border) !important; color: var(--text2) !important; border-radius: 6px !important; }
.page-item.active .page-link { background: var(--accent) !important; border-color: var(--accent) !important; color: #fff !important; }

/* ═══════════════════════════════════════════════
   MODALS
═══════════════════════════════════════════════ */
.modal-backdrop {
    display: none;
    position: fixed; inset: 0;
    background: rgba(0,0,0,.7);
    z-index: 1000;
    align-items: center;
    justify-content: center;
    padding: 16px;
}
.modal-backdrop.open { display: flex; }
.modal-box {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: 16px;
    width: 100%; max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: var(--shadow);
    animation: modalIn .25s ease;
}
@keyframes modalIn {
    from { opacity: 0; transform: translateY(20px) scale(.97); }
    to   { opacity: 1; transform: none; }
}
.modal-header {
    display: flex; align-items: center;
    padding: 20px 24px 0;
    gap: 12px;
}
.modal-header h3 { flex: 1; font-size: 1rem; font-weight: 700; }
.modal-close {
    width: 32px; height: 32px;
    border: 1px solid var(--border);
    border-radius: 8px;
    background: var(--bg3);
    color: var(--text2);
    display: flex; align-items: center; justify-content: center;
    transition: var(--trans);
}
.modal-close:hover { border-color: var(--accent); color: var(--accent); }
.modal-close svg { width: 14px; height: 14px; }
.modal-body { padding: 20px 24px; }
.modal-footer {
    padding: 0 24px 20px;
    display: flex; gap: 8px; justify-content: flex-end;
    flex-wrap: wrap;
}

/* Tab system inside modal */
.tab-btns {
    display: flex; gap: 4px;
    overflow-x: auto;
    padding-bottom: 4px;
    margin-bottom: 20px;
    border-bottom: 1px solid var(--border);
}
.tab-btn {
    padding: 8px 14px;
    border-radius: 8px 8px 0 0;
    font-size: .78rem; font-weight: 600;
    color: var(--text2);
    background: transparent;
    border: none;
    white-space: nowrap;
    transition: var(--trans);
    border-bottom: 2px solid transparent;
    margin-bottom: -1px;
}
.tab-btn.active { color: var(--accent); border-bottom-color: var(--accent); }
.tab-panel { display: none; }
.tab-panel.active { display: block; }

/* ═══════════════════════════════════════════════
   FORM ELEMENTS
═══════════════════════════════════════════════ */
.form-group { margin-bottom: 16px; }
.form-label {
    display: block;
    font-size: .78rem; font-weight: 600;
    color: var(--text2);
    margin-bottom: 6px;
}
.form-control {
    width: 100%;
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 10px 12px;
    font-size: .85rem;
    color: var(--text);
    transition: var(--trans);
    outline: none;
}
.form-control:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(230,57,70,.1); }
.form-control::placeholder { color: var(--text3); }
select.form-control { cursor: pointer; }
select.form-control option { background: var(--bg2); }
textarea.form-control { resize: vertical; min-height: 100px; }

/* ═══════════════════════════════════════════════
   SUBSCRIPTION WIDGET MODAL
═══════════════════════════════════════════════ */
.sub-countdown {
    text-align: center;
    padding: 24px;
    background: var(--bg);
    border-radius: var(--radius);
    border: 1px solid var(--border);
    margin-bottom: 16px;
}
.sub-countdown-num {
    font-family: 'JetBrains Mono', monospace;
    font-size: 1.5rem; font-weight: 500;
    color: var(--text);
    letter-spacing: .02em;
}
.sub-countdown-label { font-size: .72rem; color: var(--text2); margin-top: 4px; }

/* ═══════════════════════════════════════════════
   TOAST NOTIFICATION
═══════════════════════════════════════════════ */
.toast-container {
    position: fixed; top: 80px; right: 20px;
    z-index: 2000; display: flex; flex-direction: column; gap: 8px;
}
.toast {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 12px 16px;
    display: flex; align-items: center; gap: 10px;
    min-width: 280px; max-width: 360px;
    box-shadow: var(--shadow);
    animation: toastIn .3s ease;
}
@keyframes toastIn {
    from { opacity: 0; transform: translateX(30px); }
    to   { opacity: 1; transform: none; }
}
.toast-icon { width: 20px; height: 20px; flex-shrink: 0; }
.toast-success { border-left: 3px solid var(--success); }
.toast-success .toast-icon { color: var(--success); }
.toast-error { border-left: 3px solid var(--accent); }
.toast-error .toast-icon { color: var(--accent); }
.toast-msg { font-size: .82rem; flex: 1; }

/* ═══════════════════════════════════════════════
   RESPONSIVE — MOBILE
═══════════════════════════════════════════════ */
@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
    }
    .sidebar.open {
        transform: translateX(0);
    }
    .header {
        left: 0;
    }
    .header-toggle {
        display: flex;
    }
    .main {
        margin-left: 0;
        padding: 16px;
    }
    .sub-pill span.sub-label { display: none; }
    .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
    .stat-card { padding: 14px; gap: 10px; }
    .stat-value { font-size: 1.3rem; }
    .post-title-cell { max-width: 160px; }
    /* Mobile : cacher seulement la colonne Date, garder Actions visible */
    thead th:nth-child(3),
    tbody td:nth-child(3) { display: none; }
    /* Boutons d'action : icône seule sur mobile */
    .btn-label-mobile { display: none; }
    .actions-cell { gap: 4px; }
    .btn-sm { padding: 6px 8px; }
    .modal-box { max-height: 95vh; border-radius: 16px 16px 0 0; }
    .modal-backdrop { align-items: flex-end; padding: 0; }
    .modal-header { padding: 16px 16px 0; }
    .modal-body { padding: 16px; }
    .modal-footer { padding: 0 16px 16px; }
    .header-actions .sub-pill { display: none; }
}
@media (max-width: 480px) {
    .stats-grid { grid-template-columns: 1fr 1fr; }
}
</style>
</head>

<body>

{{-- ═══ TOAST ═══ --}}
<div class="toast-container" id="toastContainer">
    @if(session('success'))
        <div class="toast toast-success" id="initToast">
            <svg class="toast-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span class="toast-msg">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="toast toast-error" id="initToast">
            <svg class="toast-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            <span class="toast-msg">{{ session('error') }}</span>
        </div>
    @endif
</div>

{{-- ═══ OVERLAY MOBILE ═══ --}}
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

@php
    $host = request()->getHost();
    $baseDomain = str_contains($host, 'e-benin.bj') ? 'e-benin.bj' : 'e-benin.com';
    $expiryDate = null;
    if ($user->subscription_started_at && $user->subscription_quantity) {
        $expiryDate = $user->subscription_started_at->copy()->addMonths($user->subscription_quantity);
    }
    $isExpired = !$expiryDate || now()->greaterThanOrEqualTo($expiryDate);
    $isWarning = $expiryDate && !$isExpired && now()->diffInDays($expiryDate) <= 7;
@endphp

{{-- ═══ SIDEBAR ═══ --}}
<aside class="sidebar" id="sidebar">
    {{-- Brand --}}
    <div class="sidebar-brand">
        <img src="{{ asset($organization->organization_logo) }}" alt="Logo" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($organization->organization_name) }}&background=e63946&color=fff'">
        <span class="sidebar-brand-name">{{ $organization->organization_name }}</span>
    </div>

    {{-- Nav --}}
    <div class="sidebar-section">Navigation</div>
    <ul class="sidebar-nav">
        <li>
            <a href="https://{{ $baseDomain }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/></svg>
                E-Benin
            </a>
        </li>
        @if(Auth::user()->isAdmin === 0)
        <li>
            <a href="https://{{ $user->organization->subdomain }}.{{ $baseDomain }}/blog">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Mon Blog
            </a>
        </li>
        @endif
        <li>
            <a href="#" class="active">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                Dashboard
            </a>
        </li>
    </ul>

    @if(Auth::user()->isAdmin === 0)
    <div class="sidebar-section">Rubriques</div>
    <ul class="sidebar-nav">
        @forelse($rubriques as $rubrique)
        <li>
            <a href="https://{{ $user->organization->subdomain }}.{{ $baseDomain }}/category/{{ $rubrique->id }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                {{ $rubrique->name }}
            </a>
        </li>
        @empty
        <li><a href="#" style="cursor:default; opacity:.5;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Aucune rubrique
        </a></li>
        @endforelse
    </ul>
    @endif

    <div class="sidebar-section">Paramètres</div>
    <ul class="sidebar-nav">
        <li><a href="#" onclick="openModal('settingsModal')">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
            Paramètres
        </a></li>
        <li>
            <a href="{{ route('logOut') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color: var(--accent);">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Déconnexion
            </a>
        </li>
    </ul>
    <form id="logout-form" action="{{ route('logOut') }}" method="POST" style="display:none;">@csrf</form>

    {{-- User --}}
    <div class="sidebar-footer">
        <div class="sidebar-user" onclick="openModal('settingsModal')">
            @if($biographie && $biographie->avatar)
                <img src="{{ asset($biographie->avatar) }}" alt="Avatar">
            @else
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=1a1e2a&color=e63946&size=64" alt="Avatar">
            @endif
            <div class="sidebar-user-info">
                <div class="sidebar-user-name">{{ Str::limit($user->name, 22) }}</div>
                <div class="sidebar-user-email">{{ $user->email }}</div>
            </div>
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </div>
    </div>
</aside>

{{-- ═══ HEADER ═══ --}}
<header class="header">
    <button class="header-toggle" id="menuToggle" onclick="toggleSidebar()">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
    </button>

    <div class="header-title">
        Tableau de bord
        <div class="header-subtitle">{{ now()->isoFormat('dddd D MMMM YYYY') }}</div>
    </div>

    <div class="header-actions">
        {{-- Pill abonnement --}}
        <div class="sub-pill {{ $isExpired ? 'expired' : ($isWarning ? 'warning' : 'active') }}"
             onclick="openModal('subModal')" title="Mon abonnement">
            <span class="sub-dot"></span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="10" stroke-width="2"/><path stroke-linecap="round" stroke-width="2" d="M12 6v6l4 2"/></svg>
            <span class="sub-label" id="headerCountdown">
                @if($isExpired) Expiré
                @elseif($isWarning) Expire bientôt
                @else Actif
                @endif
            </span>
        </div>

        {{-- Commentaires --}}
        @if(Auth::user()->isAdmin === 0)
        <div class="header-btn" onclick="openModal('commentsModal')">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            @if($comments->count() > 0)
                <span class="badge">{{ $comments->count() }}</span>
            @endif
        </div>
        @endif

        {{-- Paramètres --}}
        <div class="header-btn" onclick="openModal('settingsModal')">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
        </div>

        {{-- Avatar --}}
        @if($biographie && $biographie->avatar)
            <img src="{{ asset($biographie->avatar) }}" class="header-avatar" onclick="openModal('settingsModal')" alt="Avatar">
        @else
            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=1a1e2a&color=e63946&size=64" class="header-avatar" onclick="openModal('settingsModal')" alt="Avatar">
        @endif
    </div>
</header>

{{-- ═══════════════════════════════════════════════
     MAIN
═══════════════════════════════════════════════ --}}
<main class="main">

    {{-- Stats --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon red">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div>
                <div class="stat-value">{{ $posts->total() }}</div>
                <div class="stat-label">Articles publiés</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon teal">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            </div>
            <div>
                <div class="stat-value">{{ $comments->count() }}</div>
                <div class="stat-label">Commentaires</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon gold">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div class="stat-value" id="daysLeft">
                    @if($expiryDate && !$isExpired)
                        {{ now()->diffInDays($expiryDate) }}j
                    @else 0j @endif
                </div>
                <div class="stat-label">Jours restants</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
            </div>
            <div>
                <div class="stat-value">{{ $rubriques->count() }}</div>
                <div class="stat-label">Rubriques</div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card">
        <div class="card-head">
            <div class="card-title">
                @if(Auth::user()->isAdmin === 0) Mes Articles
                @elseif(Auth::user()->isResponsable === 1) Mes Publicités
                @endif
            </div>
            @if(Auth::user()->isAdmin === 0)
            <button class="btn btn-primary" onclick="openModal('createArticleModal')">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Nouvel article
            </button>
            @elseif(Auth::user()->isResponsable === 1)
            <button class="btn btn-primary" onclick="openModal('createPubModal')">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Nouvelle pub
            </button>
            @endif
        </div>

        <div class="table-wrap">
            @if(Auth::user()->isAdmin === 0)
            <table>
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Rubrique</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                    <tr>
                        <td class="post-title-cell">
                            <span class="post-title-text">{{ $post->libelle }}</span>
                        </td>
                        <td>
                            <span class="post-cat">{{ $post->rubriques->first()?->name ?? '—' }}</span>
                        </td>
                        <td style="color:var(--text2); font-size:.78rem; white-space:nowrap;">
                            {{ $post->created_at->format('d/m/Y') }}
                        </td>
                        <td>
                            <div class="actions-cell">
                                <a href="https://{{ $user->organization->subdomain }}.{{ $baseDomain }}/post/{{ $post->id }}"
                                   class="btn btn-sm btn-secondary" target="_blank" title="Voir">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="13" height="13"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <button class="btn btn-sm btn-warning edit-article-btn"
                                    data-id="{{ $post->id }}"
                                    data-libelle="{{ addslashes($post->libelle) }}"
                                    data-sous-titre="{{ addslashes($post->sous_titre) }}"
                                    data-rubrique="{{ $post->rubriques->first()?->id ?? '' }}"
                                    data-video="{{ addslashes($post->video ?? '') }}"
                                    data-description="{{ addslashes(strip_tags($post->description ?? '')) }}">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="13" height="13"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    <span class="btn-label-mobile">Modifier</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">
                            <div class="empty-state">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <p>Aucun article publié pour le moment.</p>
                                <button class="btn btn-primary" style="margin-top:12px;" onclick="openModal('createArticleModal')">Créer mon premier article</button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            @elseif(Auth::user()->isResponsable === 1)
            <table>
                <thead>
                    <tr><th>Image</th><th>URL</th><th>Espace</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($publicites as $pub)
                    <tr>
                        <td><img src="{{ asset($pub->image) }}" alt="Pub" style="width:70px; height:45px; object-fit:cover; border-radius:6px; border:1px solid var(--border);"></td>
                        <td style="max-width:180px;"><span style="font-size:.78rem; word-break:break-all;">{{ Str::limit($pub->url, 40) }}</span></td>
                        <td><span class="post-cat">{{ $pub->space }}</span></td>
                        <td>
                            <div class="actions-cell">
                                <button class="btn btn-sm btn-warning"
                                    onclick="openEditPub({{ $pub->id }}, '{{ addslashes($pub->url) }}', '{{ $pub->space }}')">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="13" height="13"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    <span class="btn-label-mobile">Modifier</span>
                                </button>
                                <form method="POST" action="{{ route('publicite.delete', $pub->id) }}" onsubmit="return confirm('Supprimer cette publicité ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="13" height="13"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4"><div class="empty-state"><p>Aucune publicité trouvée.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
            @endif
        </div>

        @if(Auth::user()->isAdmin === 0 && $posts->lastPage() > 1)
        <div class="pagination-wrap">
            {{ $posts->links('vendor.pagination.bootstrap-4') }}
        </div>
        @endif
    </div>
</main>

{{-- ═══════════════════════════════════════════════════════════════════
     MODAL : CRÉER ARTICLE
═══════════════════════════════════════════════════════════════════ --}}
<div class="modal-backdrop" id="createArticleModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Nouvel article</h3>
            <button class="modal-close" onclick="closeModal('createArticleModal')">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route('articles.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Catégorie *</label>
                    <select class="form-control" name="rubrique_id" id="createRubriqueSelect" required>
                        <option value="" disabled selected>Choisir une catégorie</option>
                        @foreach($rubriques as $rubrique)
                            <option value="{{ $rubrique->id }}" data-name="{{ $rubrique->name }}">{{ $rubrique->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Titre *</label>
                    <input type="text" class="form-control" name="libelle" required placeholder="Titre de l'article">
                </div>
                <div class="form-group">
                    <label class="form-label">Sous-titre *</label>
                    <input type="text" class="form-control" name="sub_title" required placeholder="Accroche courte">
                </div>
                <div class="form-group">
                    <label class="form-label">Corps de l'article</label>
                    <textarea class="form-control" id="createDescription" name="description" rows="5" placeholder="Contenu..."></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Image de couverture</label>
                    <input type="file" class="form-control" name="image" accept="image/*">
                </div>
                <div class="form-group" id="videoGroup" style="display:none;">
                    <label class="form-label">Lien vidéo (Reportage)</label>
                    <input type="text" class="form-control" id="createVideo" name="video" placeholder="https://youtube.com/...">
                </div>
                <div class="form-group" id="necroVideoGroup" style="display:none;">
                    <label class="form-label">Vidéo nécrologie</label>
                    <input type="file" class="form-control" id="createNecroVideo" name="necro_video" accept="video/*">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('createArticleModal')">Annuler</button>
                <button type="submit" class="btn btn-primary">Publier l'article</button>
            </div>
        </form>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════════
     MODAL : MODIFIER ARTICLE
═══════════════════════════════════════════════════════════════════ --}}
<div class="modal-backdrop" id="editArticleModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Modifier l'article</h3>
            <button class="modal-close" onclick="closeModal('editArticleModal')">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="editArticleForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Catégorie *</label>
                    <select class="form-control" name="rubrique_id" id="editRubriqueSelect" required>
                        @foreach($rubriques as $rubrique)
                            <option value="{{ $rubrique->id }}" data-name="{{ $rubrique->name }}">{{ $rubrique->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Titre *</label>
                    <input type="text" class="form-control" name="libelle" id="editLibelle" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Sous-titre *</label>
                    <input type="text" class="form-control" name="sub_title" id="editSubTitle" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Corps de l'article</label>
                    <textarea class="form-control" id="editDescription" name="description" rows="5"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Nouvelle image (optionnel)</label>
                    <input type="file" class="form-control" name="image" accept="image/*">
                </div>
                <div class="form-group">
                    <label class="form-label">Lien vidéo</label>
                    <input type="text" class="form-control" name="video" id="editVideo" placeholder="https://...">
                </div>
                <div class="form-group">
                    <label class="form-label">Vidéo nécrologie</label>
                    <input type="file" class="form-control" name="necro_video" accept="video/*">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('editArticleModal')">Annuler</button>
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </div>
        </form>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════════
     MODAL : CRÉER PUBLICITÉ
═══════════════════════════════════════════════════════════════════ --}}
<div class="modal-backdrop" id="createPubModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Nouvelle publicité</h3>
            <button class="modal-close" onclick="closeModal('createPubModal')">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route('publicite.create') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Image *</label>
                    <input type="file" class="form-control" name="image" accept="image/*" required>
                </div>
                <div class="form-group">
                    <label class="form-label">URL de destination</label>
                    <input type="text" class="form-control" name="url" placeholder="https://...">
                </div>
                <div class="form-group">
                    <label class="form-label">Emplacement</label>
                    <select class="form-control" name="space">
                        <option value="e-benin">Accueil E-Benin</option>
                        <option value="blog.e-benin">Tous les blogs</option>
                        <option value="article.e-benin">Pages articles</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('createPubModal')">Annuler</button>
                <button type="submit" class="btn btn-primary">Créer</button>
            </div>
        </form>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════════
     MODAL : MODIFIER PUBLICITÉ
═══════════════════════════════════════════════════════════════════ --}}
<div class="modal-backdrop" id="editPubModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Modifier la publicité</h3>
            <button class="modal-close" onclick="closeModal('editPubModal')">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="editPubForm" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Nouvelle image</label>
                    <input type="file" class="form-control" name="image" accept="image/*">
                </div>
                <div class="form-group">
                    <label class="form-label">URL</label>
                    <input type="text" class="form-control" name="url" id="editPubUrl">
                </div>
                <div class="form-group">
                    <label class="form-label">Emplacement</label>
                    <select class="form-control" name="space" id="editPubSpace">
                        <option value="e-benin">Accueil E-Benin</option>
                        <option value="blog.e-benin">Tous les blogs</option>
                        <option value="article.e-benin">Pages articles</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('editPubModal')">Annuler</button>
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </div>
        </form>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════════
     MODAL : PARAMÈTRES (tabs)
═══════════════════════════════════════════════════════════════════ --}}
<div class="modal-backdrop" id="settingsModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Paramètres</h3>
            <button class="modal-close" onclick="closeModal('settingsModal')">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="modal-body">
            <div class="tab-btns">
                <button class="tab-btn active" onclick="switchTab('tabOrg', this)">Organisation</button>
                <button class="tab-btn" onclick="switchTab('tabPassword', this)">Mot de passe</button>
                <button class="tab-btn" onclick="switchTab('tabBio', this)">Biographie</button>
                <button class="tab-btn" onclick="switchTab('tabSocials', this)">Réseaux sociaux</button>
            </div>

            {{-- Tab Organisation --}}
            <div class="tab-panel active" id="tabOrg">
                <form method="POST" action="{{ route('org.update', ['id' => $organization->id]) }}" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="form-group">
                        <label class="form-label">Nom de l'organisation *</label>
                        <input type="text" class="form-control" name="organization_name" value="{{ $organization->organization_name }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Adresse</label>
                        <input type="text" class="form-control" name="organization_address" value="{{ $organization->organization_address }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Téléphone</label>
                        <input type="text" class="form-control" name="organization_phone" value="{{ $organization->organization_phone }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email *</label>
                        <input type="email" class="form-control" name="organization_email" value="{{ $organization->organization_email }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Logo</label>
                        <input type="file" class="form-control" name="organization_logo" accept="image/*">
                        @if($organization->organization_logo)
                            <img src="{{ asset($organization->organization_logo) }}" style="margin-top:8px; height:50px; border-radius:6px; border:1px solid var(--border);">
                        @endif
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Mettre à jour</button>
                </form>
            </div>

            {{-- Tab Mot de passe --}}
            <div class="tab-panel" id="tabPassword">
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Mot de passe actuel *</label>
                        <input type="password" class="form-control" name="current_password" required placeholder="••••••••">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nouveau mot de passe *</label>
                        <input type="password" class="form-control" name="new_password" required placeholder="••••••••">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirmer *</label>
                        <input type="password" class="form-control" name="new_password_confirmation" required placeholder="••••••••">
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Changer le mot de passe</button>
                </form>
            </div>

            {{-- Tab Biographie --}}
            <div class="tab-panel" id="tabBio">
                <form method="POST"
                    action="{{ $biographie ? route('bio.update', ['id' => $biographie->id]) : route('bio.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    @if($biographie) @method('PUT') @endif
                    <div class="form-group">
                        <label class="form-label">Votre biographie</label>
                        <textarea class="form-control" name="bio" rows="4" required placeholder="Parlez de vous...">{{ $biographie->bio ?? '' }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Avatar</label>
                        <input type="file" class="form-control" name="avatar" accept="image/*">
                        @if($biographie && $biographie->avatar)
                            <img src="{{ asset($biographie->avatar) }}" style="margin-top:8px; height:50px; width:50px; border-radius:50%; object-fit:cover; border:2px solid var(--border);">
                        @endif
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">
                        {{ $biographie ? 'Mettre à jour' : 'Ajouter la biographie' }}
                    </button>
                </form>
            </div>

            {{-- Tab Réseaux sociaux --}}
            <div class="tab-panel" id="tabSocials">
                {{-- Ajouter --}}
                <p style="font-size:.78rem; color:var(--text2); margin-bottom:12px; font-weight:600;">Ajouter un réseau</p>
                <form method="POST" action="{{ route('social.store') }}">
                    @csrf
                    <input type="hidden" name="organization_id" value="{{ $user->organization->id }}">
                    <div class="form-group">
                        <label class="form-label">Réseau social</label>
                        <select class="form-control" name="social_id" required>
                            <option value="" disabled selected>Choisir...</option>
                            @foreach($reseaux as $reseau)
                                <option value="{{ $reseau->id }}">{{ $reseau->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">URL de votre page</label>
                        <input type="url" class="form-control" name="url" required placeholder="https://...">
                    </div>
                    <button type="submit" class="btn btn-success btn-block">Ajouter</button>
                </form>

                @if($orgSocials->count() > 0)
                <hr style="border-color:var(--border); margin:20px 0;">
                <p style="font-size:.78rem; color:var(--text2); margin-bottom:12px; font-weight:600;">Mettre à jour</p>
                <form method="POST" action="{{ route('social.update', $organization->id) }}">
                    @csrf @method('PUT')
                    <div class="form-group">
                        <label class="form-label">Réseau à modifier</label>
                        <select class="form-control" name="social_id" id="socialUpdateSelect">
                            @foreach($orgSocials as $orgSocial)
                                <option value="{{ $orgSocial->social->id }}" data-url="{{ $orgSocial->url }}">
                                    {{ $orgSocial->social->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nouvelle URL</label>
                        <input type="url" class="form-control" name="url" id="socialUpdateUrl" required placeholder="https://...">
                    </div>
                    <button type="submit" class="btn btn-warning btn-block">Mettre à jour</button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════════
     MODAL : ABONNEMENT
═══════════════════════════════════════════════════════════════════ --}}
<div class="modal-backdrop" id="subModal">
    <div class="modal-box" style="max-width:400px;">
        <div class="modal-header">
            <h3>Mon abonnement</h3>
            <button class="modal-close" onclick="closeModal('subModal')">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="modal-body">
            <div class="sub-countdown">
                <div class="sub-countdown-num" id="subCountdownDisplay">
                    @if($expiryDate && !$isExpired)
                        {{ $expiryDate->format('d/m/Y') }}
                    @else Expiré @endif
                </div>
                <div class="sub-countdown-label" id="subCountdownTimer">Chargement...</div>
            </div>

            @if($isExpired || $isWarning)
            <div style="margin-bottom:16px; padding:12px; background:rgba(230,57,70,.08); border:1px solid rgba(230,57,70,.2); border-radius:8px; font-size:.8rem; color:var(--accent);">
                {{ $isExpired ? 'Votre abonnement a expiré. Renouvelez pour continuer.' : 'Votre abonnement expire bientôt. Pensez à renouveler !' }}
            </div>
            @endif

            <div class="form-group">
                <label class="form-label">Nombre de mois à ajouter</label>
                <input type="number" class="form-control" id="subMonthsInput" min="1" value="1">
                <p style="margin-top:6px; font-size:.75rem; color:var(--text2);">
                    Montant : <strong id="subAmountDisplay" style="color:var(--gold);">10 000 FCFA</strong>
                </p>
            </div>
            <div id="kkiapay-container-board"></div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════════
     MODAL : COMMENTAIRES
═══════════════════════════════════════════════════════════════════ --}}
<div class="modal-backdrop" id="commentsModal">
    <div class="modal-box" style="max-width:500px;">
        <div class="modal-header">
            <h3>Commentaires ({{ $comments->count() }})</h3>
            <button class="modal-close" onclick="closeModal('commentsModal')">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="modal-body" style="padding-top:12px;">
            @forelse($comments as $comment)
            <div style="padding:12px; background:var(--bg); border-radius:8px; margin-bottom:8px; border:1px solid var(--border);">
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
                    <div style="width:28px;height:28px;border-radius:50%;background:var(--bg3);display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:700;color:var(--accent);">
                        {{ strtoupper(substr($comment->reader_name ?? 'A', 0, 1)) }}
                    </div>
                    <strong style="font-size:.82rem;">{{ $comment->reader_name ?? 'Anonyme' }}</strong>
                    <span style="font-size:.7rem;color:var(--text3);margin-left:auto;">{{ $comment->created_at->diffForHumans() }}</span>
                </div>
                <p style="font-size:.82rem; color:var(--text2); margin:0;">{{ $comment->comments }}</p>
            </div>
            @empty
            <div class="empty-state"><p>Aucun commentaire pour le moment.</p></div>
            @endforelse
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════════
     SCRIPTS
═══════════════════════════════════════════════════════════════════ --}}
<script src="https://cdn.kkiapay.me/k.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
// ── Modal system ─────────────────────────────────────────────
function openModal(id) {
    document.getElementById(id).classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeModal(id) {
    document.getElementById(id).classList.remove('open');
    document.body.style.overflow = '';
}
// Fermer modal en cliquant sur le backdrop
document.querySelectorAll('.modal-backdrop').forEach(function(backdrop) {
    backdrop.addEventListener('click', function(e) {
        if (e.target === backdrop) closeModal(backdrop.id);
    });
});
// Fermer avec Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-backdrop.open').forEach(function(m) {
            closeModal(m.id);
        });
    }
});

// ── Sidebar mobile ───────────────────────────────────────────
function toggleSidebar() {
    var sidebar = document.getElementById('sidebar');
    var overlay = document.getElementById('sidebarOverlay');
    var isOpen  = sidebar.classList.toggle('open');
    overlay.classList.toggle('active', isOpen);
}
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('sidebarOverlay').classList.remove('active');
}

// ── Tab system ───────────────────────────────────────────────
function switchTab(panelId, btn) {
    document.querySelectorAll('.tab-panel').forEach(function(p) { p.classList.remove('active'); });
    document.querySelectorAll('.tab-btn').forEach(function(b) { b.classList.remove('active'); });
    document.getElementById(panelId).classList.add('active');
    btn.classList.add('active');
}

// ── Toast auto-hide ──────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function() {
    var toast = document.getElementById('initToast');
    if (toast) setTimeout(function() {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity .4s';
        setTimeout(function() { toast.remove(); }, 400);
    }, 4000);
});

// ── Créer article : gestion champs conditionnels ─────────────
document.getElementById('createRubriqueSelect').addEventListener('change', function() {
    var name = this.options[this.selectedIndex].dataset.name || '';
    document.getElementById('videoGroup').style.display    = name === 'Reportage'  ? 'block' : 'none';
    document.getElementById('necroVideoGroup').style.display = name === 'Necrologie' || name === 'Nécrologie' ? 'block' : 'none';
    if (name !== 'Reportage')  document.getElementById('createVideo').value = '';
});

// ── Modifier article ─────────────────────────────────────────
// Gestion du bouton modifier article via délégation (data-attributes)
document.addEventListener('click', function(e) {
    var btn = e.target.closest('.edit-article-btn');
    if (!btn) return;

    var id          = btn.dataset.id;
    var libelle     = btn.dataset.libelle;
    var sousTitre   = btn.dataset.sousTitre;
    var rubriqueId  = btn.dataset.rubrique;
    var video       = btn.dataset.video || '';
    var description = btn.dataset.description || '';

    document.getElementById('editLibelle').value  = libelle;
    document.getElementById('editSubTitle').value = sousTitre;
    document.getElementById('editVideo').value    = video;
    document.getElementById('editArticleForm').action = '/articles/update/' + id;

    if (rubriqueId) {
        document.getElementById('editRubriqueSelect').value = rubriqueId;
    }

    // 1. Ouvrir le modal
    openModal('editArticleModal');

    // 2. Après que le modal est rendu visible, charger TinyMCE
    setTimeout(function() {
        if (window.tinymce) {
            var existing = tinymce.get('editDescription');
            if (existing) {
                // Instance existante → juste mettre à jour le contenu
                existing.setContent(description);
            } else {
                // Première ouverture → init TinyMCE
                tinymce.init({
                    selector: '#editDescription',
                    plugins: 'lists image link',
                    toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignjustify | link image',
                    menubar: false, branding: false, height: 240,
                    skin: 'oxide-dark', content_css: 'dark',
                    content_style: 'body { font-family: Sora, sans-serif; font-size:14px; background:#0d0f14; color:#e8eaf0; }',
                    setup: function(editor) {
                        editor.on('init', function() {
                            editor.setContent(description);
                        });
                    }
                });
            }
        } else {
            document.getElementById('editDescription').value = description;
        }
    }, 100);
});

// ── Modifier pub ─────────────────────────────────────────────
function openEditPub(id, url, space) {
    document.getElementById('editPubUrl').value     = url;
    document.getElementById('editPubSpace').value   = space;
    document.getElementById('editPubForm').action   = '/publicites/' + id;
    openModal('editPubModal');
}

// ── Réseau social auto-fill URL ──────────────────────────────
var socialSelect = document.getElementById('socialUpdateSelect');
var socialUrlInput = document.getElementById('socialUpdateUrl');
if (socialSelect && socialUrlInput) {
    function updateSocialUrl() {
        var opt = socialSelect.options[socialSelect.selectedIndex];
        socialUrlInput.value = opt ? (opt.dataset.url || '') : '';
    }
    socialSelect.addEventListener('change', updateSocialUrl);
    updateSocialUrl();
}

// ── Countdown abonnement ─────────────────────────────────────
@if($expiryDate && !$isExpired)
var expiryTs = {{ $expiryDate->timestamp * 1000 }};
function updateCountdown() {
    var diff = expiryTs - Date.now();
    var el   = document.getElementById('subCountdownTimer');
    if (!el) return;
    if (diff <= 0) {
        el.textContent = 'Abonnement expiré';
        el.style.color = 'var(--accent)';
        return;
    }
    var d = Math.floor(diff / 86400000);
    var h = Math.floor((diff % 86400000) / 3600000);
    var m = Math.floor((diff % 3600000) / 60000);
    var s = Math.floor((diff % 60000) / 1000);
    el.textContent = d + 'j ' + h + 'h ' + m + 'm ' + s + 's restants';
    el.style.color = d <= 7 ? 'var(--accent)' : 'var(--text2)';
}
setInterval(updateCountdown, 1000);
updateCountdown();
@else
var timerEl = document.getElementById('subCountdownTimer');
if (timerEl) {
    timerEl.textContent = 'Abonnement inactif';
    timerEl.style.color = 'var(--accent)';
}
@endif

// ── Kkiapay widget abonnement dashboard ──────────────────────
document.addEventListener('DOMContentLoaded', function() {
    var monthsInput  = document.getElementById('subMonthsInput');
    var amountDisplay = document.getElementById('subAmountDisplay');
    var container    = document.getElementById('kkiapay-container-board');
    var subdomain    = "{{ $user->organization->subdomain }}";
    var host         = window.location.hostname;
    var baseDomain   = host.includes('e-benin.bj') ? 'e-benin.bj' : 'e-benin.com';

    function buildSubWidget() {
        var qty    = Math.max(1, parseInt(monthsInput.value) || 1);
        var amount = 10000 * qty;
        if (amountDisplay) amountDisplay.textContent = amount.toLocaleString('fr-FR') + ' FCFA';
        if (!container) return;
        container.innerHTML = '';
        var callbackUrl = 'https://' + baseDomain + '/update-subscription'
            + '?quantite=' + qty
            + '&subdomain=' + encodeURIComponent(subdomain);
        var w = document.createElement('kkiapay-widget');
        w.setAttribute('amount',   amount.toString());
        w.setAttribute('key',      'cb876650e192fdf79d12342d023a6f4ebe257de4');
        w.setAttribute('position', 'center');
        w.setAttribute('sandbox',  'false');
        w.setAttribute('data',     JSON.stringify({ objet: 'renouvellement', quantite: qty, subdomain: subdomain }));
        w.setAttribute('callback', callbackUrl);
        container.appendChild(w);
    }

    if (monthsInput) {
        monthsInput.addEventListener('input', buildSubWidget);
    }
    // Build widget when sub modal is opened
    document.getElementById('subModal').addEventListener('click', function() {});
    // On modal open
    var origOpenModal = window.openModal;
    window.openModal = function(id) {
        origOpenModal(id);
        if (id === 'subModal') buildSubWidget();
    };
});

// ── TinyMCE création ─────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function() {
    if (window.tinymce) {
        tinymce.init({
            selector: '#createDescription',
            plugins: 'lists image link',
            toolbar: 'undo redo | bold italic underline | aligncenter alignjustify | link image',
            menubar: false, branding: false, height: 220,
            skin: 'oxide-dark', content_css: 'dark',
            content_style: 'body { font-family: Sora, sans-serif; font-size:14px; background:#0d0f14; color:#e8eaf0; }',
            file_picker_callback: function(callback, value, meta) {
                if (meta.filetype === 'image') {
                    var input = document.createElement('input');
                    input.setAttribute('type', 'file');
                    input.setAttribute('accept', 'image/*');
                    input.onchange = function() {
                        var reader = new FileReader();
                        reader.onload = function(e) { callback(e.target.result, { alt: input.files[0].name }); };
                        reader.readAsDataURL(input.files[0]);
                    };
                    input.click();
                }
            },
        });
    }
});
</script>