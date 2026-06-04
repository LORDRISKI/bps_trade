<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel — BPS Trade</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,700;1,700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        :root {
            --bg: #f5f4f0;
            --bg2: #ffffff;
            --bg3: #eeece8;
            --border: #e0ddd8;
            --border-dark: #c8c4be;
            --accent: #1e3a5f;
            --accent2: #2563eb;
            --accent3: #10b981;
            --red: #e53e3e;
            --yellow: #d97706;
            --purple: #7c3aed;
            --text: #1a1a1a;
            --text-dim: #9b9890;
            --text-mid: #6b6863;
            --card: #ffffff;
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Inter',sans-serif; background:var(--bg); color:var(--text); min-height:100vh; }

        /* HEADER */
        .header { background:var(--bg2); border-bottom:1px solid var(--border); padding:0 2rem; position:sticky; top:0; z-index:100; }
        .header-inner { max-width:1300px; margin:0 auto; height:64px; display:flex; align-items:center; justify-content:space-between; }
        .logo { display:flex; align-items:center; gap:12px; }
        .logo-icon { width:44px; height:44px; display:flex; align-items:center; justify-content:center; }
        .logo-icon img { width:100%; height:100%; object-fit:contain; }
        .logo-text { font-size:1rem; font-weight:600; color:var(--text); letter-spacing:-0.01em; }
        .logo-sub { font-size:0.68rem; color:var(--text-dim); text-transform:uppercase; letter-spacing:0.06em; }
        .nav-links { display:flex; align-items:center; gap:8px; }
        .btn { padding:8px 18px; border-radius:8px; font-size:0.82rem; font-weight:500; cursor:pointer; border:none; font-family:'Inter',sans-serif; text-decoration:none; display:inline-flex; align-items:center; gap:7px; transition:all 0.2s; }
        .btn-ghost { background:transparent; color:var(--text-mid); border:1px solid var(--border-dark); }
        .btn-ghost:hover { background:var(--bg3); color:var(--text); }
        .btn-primary { background:var(--accent); color:white; }
        .btn-primary:hover { background:#16304f; }
        .btn-primary:disabled { opacity:0.5; cursor:not-allowed; }

        /* MAIN */
        .main { max-width:1300px; margin:0 auto; padding:2rem; }

        /* SECTION TITLE */
        .section-title { font-size:0.95rem; font-weight:600; margin-bottom:1rem; display:flex; align-items:center; gap:10px; color:var(--text); }
        .section-title span { width:3px; height:18px; background:var(--accent); border-radius:2px; display:inline-block; }

        /* STAT CARDS */
        .stats-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:1rem; margin-bottom:1.5rem; }
        .stat-card { background:var(--card); border:1px solid var(--border); border-radius:12px; padding:1.1rem 1.25rem; position:relative; overflow:hidden; }
        .stat-card::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; }
        .stat-card.blue::before   { background:var(--accent2); }
        .stat-card.green::before  { background:var(--accent3); }
        .stat-card.red::before    { background:var(--red); }
        .stat-card.yellow::before { background:var(--yellow); }
        .stat-card.teal::before   { background:#0891b2; }
        .stat-card.purple::before { background:var(--purple); }
        .stat-label { font-size:0.67rem; text-transform:uppercase; letter-spacing:0.07em; color:var(--text-dim); margin-bottom:6px; font-weight:600; }
        .stat-value { font-size:1.5rem; font-weight:600; line-height:1; margin-bottom:3px; color:var(--text); }
        .stat-sub { font-size:0.72rem; color:var(--text-dim); }
        .stat-icon { position:absolute; right:1rem; top:1rem; font-size:1.6rem; opacity:0.1; }

        /* CHARTS */
        .charts-row { display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; margin-bottom:1.25rem; }
        .chart-card { background:var(--card); border:1px solid var(--border); border-radius:12px; overflow:hidden; }
        .chart-header { padding:1rem 1.25rem; border-bottom:1px solid var(--border); }
        .chart-title { font-size:0.88rem; font-weight:600; color:var(--text); }
        .chart-sub { font-size:0.72rem; color:var(--text-dim); margin-top:2px; }
        .chart-body { padding:1.25rem; }
        .chart-body canvas { max-height:240px; }

        /* TOP LIST */
        .top-list { padding:0; }
        .top-item { display:flex; align-items:center; gap:8px; padding:0.55rem 1.25rem; border-bottom:1px solid var(--border); }
        .top-item:last-child { border:none; }
        .top-rank { width:20px; height:20px; border-radius:5px; background:var(--bg3); font-size:0.65rem; font-weight:700; display:flex; align-items:center; justify-content:center; color:var(--text-dim); flex-shrink:0; }
        .top-rank.gold   { background:#fef3c7; color:#92400e; }
        .top-rank.silver { background:#f1f5f9; color:#475569; }
        .top-rank.bronze { background:#fde8d0; color:#92400e; }
        .top-name { flex:1; font-size:0.78rem; font-weight:500; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .top-bar-wrap { width:90px; height:4px; background:var(--bg3); border-radius:100px; flex-shrink:0; }
        .top-bar { height:100%; border-radius:100px; background:var(--accent2); }
        .top-val { font-size:0.7rem; color:var(--text-dim); font-family:'JetBrains Mono',monospace; min-width:72px; text-align:right; }

        /* UPLOAD + LOG GRID */
        .bottom-grid { display:grid; grid-template-columns:380px 1fr; gap:1.25rem; align-items:start; }

        /* UPLOAD CARD */
        .upload-card { background:var(--card); border:1px solid var(--border); border-radius:14px; overflow:hidden; }
        .card-header { padding:1.1rem 1.5rem; border-bottom:1px solid var(--border); }
        .card-title { font-size:0.95rem; font-weight:600; margin-bottom:3px; color:var(--text); }
        .card-desc { font-size:0.78rem; color:var(--text-dim); }

        .drop-zone { margin:1.25rem; border:2px dashed var(--border-dark); border-radius:12px; padding:2.5rem 1.5rem; text-align:center; cursor:pointer; transition:all 0.2s; position:relative; background:var(--bg3); }
        .drop-zone:hover,.drop-zone.drag-over { border-color:var(--accent2); background:#eff6ff; }
        .drop-zone input[type="file"] { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
        .drop-icon { font-size:2.8rem; margin-bottom:0.8rem; display:block; transition:transform 0.2s; }
        .drop-zone:hover .drop-icon { transform:scale(1.1) translateY(-3px); }
        .drop-title { font-size:0.95rem; font-weight:600; margin-bottom:4px; color:var(--text); }
        .drop-subtitle { font-size:0.78rem; color:var(--text-dim); margin-bottom:0.8rem; }
        .drop-formats { display:flex; gap:6px; justify-content:center; flex-wrap:wrap; }
        .format-badge { padding:2px 8px; border-radius:20px; font-size:0.67rem; font-weight:600; font-family:'JetBrains Mono',monospace; }
        .fmt-xlsx { background:#d1fae5; color:#065f46; border:1px solid #a7f3d0; }
        .fmt-xls  { background:#d1fae5; color:#065f46; border:1px solid #a7f3d0; }
        .fmt-csv  { background:#dbeafe; color:#1e40af; border:1px solid #bfdbfe; }

        .file-preview { display:none; margin:0 1.25rem; background:#eff6ff; border:1px solid #bfdbfe; border-radius:10px; padding:0.9rem 1rem; align-items:center; gap:0.8rem; justify-content:space-between; }
        .file-preview.visible { display:flex; }
        .file-info { display:flex; align-items:center; gap:8px; }
        .file-name { font-size:0.82rem; font-weight:600; color:var(--text); }
        .file-size { font-size:0.72rem; color:var(--text-dim); }
        .file-remove { background:#fee2e2; border:1px solid #fecaca; color:var(--red); border-radius:6px; padding:4px 9px; font-size:0.72rem; cursor:pointer; font-family:'Inter',sans-serif; }

        .form-options { padding:1rem 1.25rem; }
        .option-label { font-size:0.72rem; font-weight:600; color:var(--text-mid); margin-bottom:6px; text-transform:uppercase; letter-spacing:0.06em; }
        .jenis-toggle { display:flex; gap:8px; }
        .jenis-btn { flex:1; padding:8px; border-radius:8px; border:1px solid var(--border-dark); background:transparent; color:var(--text-mid); font-size:0.8rem; font-weight:500; cursor:pointer; transition:all 0.15s; font-family:'Inter',sans-serif; }
        .jenis-btn.active-ekspor { background:#d1fae5; border-color:#6ee7b7; color:#065f46; }
        .jenis-btn.active-impor  { background:#fee2e2; border-color:#fca5a5; color:#991b1b; }

        .upload-progress { display:none; padding:0 1.25rem; }
        .upload-progress.visible { display:block; }
        .progress-bar-wrap { background:var(--bg3); border-radius:100px; height:7px; overflow:hidden; margin-bottom:7px; }
        .progress-bar { height:100%; background:var(--accent2); border-radius:100px; }
        .progress-bar.indeterminate { animation:indeterminate 1.5s ease-in-out infinite; width:40%; }
        @keyframes indeterminate { 0%{transform:translateX(-100%)} 100%{transform:translateX(350%)} }
        .progress-text { font-size:0.75rem; color:var(--text-dim); text-align:center; }

        .result-box { display:none; margin:0 1.25rem 1.25rem; padding:0.9rem 1rem; border-radius:10px; }
        .result-box.visible { display:block; }
        .result-box.success { background:#d1fae5; border:1px solid #6ee7b7; }
        .result-box.error   { background:#fee2e2; border:1px solid #fca5a5; }
        .result-title { font-size:0.82rem; font-weight:600; margin-bottom:3px; }
        .result-box.success .result-title { color:#065f46; }
        .result-box.error   .result-title { color:#991b1b; }
        .result-msg { font-size:0.78rem; color:var(--text-mid); }

        .action-row { padding:0.9rem 1.25rem 1.25rem; display:flex; gap:8px; }
        .btn-upload { flex:1; justify-content:center; padding:10px; font-size:0.85rem; }

        /* LOG TABLE */
        .logs-card { background:var(--card); border:1px solid var(--border); border-radius:12px; overflow:hidden; }
        .logs-header { padding:1rem 1.25rem; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; }
        .logs-title { font-size:0.88rem; font-weight:600; color:var(--text); }
        table { width:100%; border-collapse:collapse; font-size:0.8rem; }
        thead th { background:var(--bg3); padding:9px 12px; text-align:left; font-size:0.67rem; text-transform:uppercase; letter-spacing:0.07em; color:var(--text-dim); border-bottom:1px solid var(--border); font-weight:600; }
        tbody tr { border-bottom:1px solid var(--border); }
        tbody tr:hover { background:#f8f7f4; }
        tbody tr:last-child { border:none; }
        td { padding:9px 12px; vertical-align:middle; color:var(--text); }
        .mono { font-family:'JetBrains Mono',monospace; font-size:0.72rem; color:var(--text-dim); }
        .badge { padding:2px 8px; border-radius:20px; font-size:0.65rem; font-weight:600; }
        .badge-done       { background:#d1fae5; color:#065f46; border:1px solid #a7f3d0; }
        .badge-processing { background:#fef3c7; color:#92400e; border:1px solid #fde68a; }
        .badge-failed     { background:#fee2e2; color:#991b1b; border:1px solid #fecaca; }
        .del-btn { background:#fee2e2; color:var(--red); border:1px solid #fecaca; border-radius:6px; padding:4px 9px; font-size:0.7rem; cursor:pointer; font-family:'Inter',sans-serif; }
        .del-btn:hover { background:#fecaca; }

        /* ALERT */
        .alert { padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:0.82rem; }
        .alert-success { background:#d1fae5; border:1px solid #6ee7b7; color:#065f46; }

        /* ADMIN DROPDOWN */
        .admin-menu { position:relative; }
        .admin-menu-btn { background:none; border:none; cursor:pointer; padding:0; }
        .admin-avatar { width:36px; height:36px; border-radius:50%; background:var(--accent); display:flex; align-items:center; justify-content:center; font-size:0.85rem; font-weight:600; color:white; font-family:'Inter',sans-serif; transition:opacity 0.2s; }
        .admin-menu-btn:hover .admin-avatar { opacity:0.85; }
        .admin-dropdown { display:none; position:absolute; right:0; top:calc(100% + 10px); min-width:190px; background:var(--bg2); border:1px solid var(--border); border-radius:12px; overflow:hidden; box-shadow:0 8px 24px rgba(0,0,0,0.1); z-index:200; }
        .admin-dropdown.open { display:block; }
        .admin-dropdown-header { padding:0.85rem 1rem; border-bottom:1px solid var(--border); }
        .admin-dropdown-name { font-size:0.85rem; font-weight:600; color:var(--text); }
        .admin-dropdown-role { font-size:0.7rem; color:var(--text-dim); margin-top:2px; }
        .admin-dropdown-item { display:flex; align-items:center; gap:8px; width:100%; padding:0.65rem 1rem; font-size:0.82rem; font-weight:500; color:var(--text-mid); text-decoration:none; background:none; border:none; cursor:pointer; font-family:'Inter',sans-serif; transition:background 0.15s,color 0.15s; text-align:left; }
        .admin-dropdown-item:hover { background:var(--bg3); color:var(--text); }
        .admin-dropdown-divider { height:1px; background:var(--border); }
        .admin-dropdown-logout:hover { background:#fee2e2; color:var(--red); }

        @media(max-width:1100px) { .bottom-grid { grid-template-columns:1fr; } }
        @media(max-width:800px)  { .charts-row { grid-template-columns:1fr; } .stats-grid { grid-template-columns:repeat(2,1fr); } }
        @media(max-width:480px)  { .stats-grid { grid-template-columns:1fr; } .main { padding:1rem; } }
    </style>
</head>
<body>

<header class="header">
    <div class="header-inner">
        <div class="logo">
            <div class="logo-icon"><img src="/images/logo-bps.png" alt="Logo BPS"></div>
            <div>
                <div class="logo-text">Admin Panel</div>
                <div class="logo-sub">BPS Trade System</div>
            </div>
        </div>
        <div class="nav-links">
            <div class="admin-menu" id="adminMenu">
                <button class="admin-menu-btn" onclick="toggleMenu()" title="Menu Admin">
                    <div class="admin-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                </button>
                <div class="admin-dropdown" id="adminDropdown">
                    <div class="admin-dropdown-header">
                        <div class="admin-dropdown-name">{{ auth()->user()->name }}</div>
                        <div class="admin-dropdown-role">Administrator</div>
                    </div>
                    <a href="{{ route('trade.index') }}" class="admin-dropdown-item">
                        <span>←</span> Data Publik
                    </a>
                    <div class="admin-dropdown-divider"></div>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="admin-dropdown-item admin-dropdown-logout">
                            <span>⏻</span> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="main">

    @if(session('success'))
    <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif

    {{-- STAT CARDS --}}
    <div class="section-title"><span></span> Ringkasan Data Perdagangan</div>
    <div class="stats-grid">
        <div class="stat-card blue">
            <div class="stat-icon">📦</div>
            <div class="stat-label">Total Rekod Ekspor</div>
            <div class="stat-value" style="color:var(--accent2)">{{ number_format($totalEkspor) }}</div>
            <div class="stat-sub">baris data</div>
        </div>
        <div class="stat-card red">
            <div class="stat-icon">🚢</div>
            <div class="stat-label">Total Rekod Impor</div>
            <div class="stat-value" style="color:var(--red)">{{ number_format($totalImpor) }}</div>
            <div class="stat-sub">baris data</div>
        </div>
        <div class="stat-card green">
            <div class="stat-icon">💰</div>
            <div class="stat-label">Nilai Ekspor</div>
            <div class="stat-value" style="color:var(--accent3)">${{ number_format($totalNilaiEkspor / 1e6, 1) }}M</div>
            <div class="stat-sub">USD</div>
        </div>
        <div class="stat-card yellow">
            <div class="stat-icon">💸</div>
            <div class="stat-label">Nilai Impor</div>
            <div class="stat-value" style="color:var(--yellow)">${{ number_format($totalNilaiImpor / 1e6, 1) }}M</div>
            <div class="stat-sub">USD</div>
        </div>
        <div class="stat-card {{ $neracaPerdagangan >= 0 ? 'teal' : 'red' }}">
            <div class="stat-icon">⚖️</div>
            <div class="stat-label">Neraca Perdagangan</div>
            <div class="stat-value" style="color:{{ $neracaPerdagangan >= 0 ? '#0891b2' : 'var(--red)' }}">
                {{ $neracaPerdagangan >= 0 ? '+' : '' }}${{ number_format(abs($neracaPerdagangan) / 1e6, 1) }}M
            </div>
            <div class="stat-sub">{{ $neracaPerdagangan >= 0 ? 'Surplus' : 'Defisit' }}</div>
        </div>
        <div class="stat-card purple">
            <div class="stat-icon">📁</div>
            <div class="stat-label">File Terupload</div>
            <div class="stat-value" style="color:var(--purple)">{{ $totalUpload }}</div>
            <div class="stat-sub">upload berhasil</div>
        </div>
    </div>

    {{-- CHARTS --}}
    <div class="section-title"><span></span> Visualisasi Data</div>
    <div class="charts-row">
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">Nilai Ekspor & Impor per Tahun</div>
                <div class="chart-sub">dalam juta USD</div>
            </div>
            <div class="chart-body">
                <canvas id="chartNilaiTahun"></canvas>
            </div>
        </div>
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">Top 8 Negara Tujuan Ekspor</div>
                <div class="chart-sub">berdasarkan nilai USD</div>
            </div>
            @php $maxNegara = $topNegara->max('total_nilai') ?: 1; @endphp
            <div class="top-list">
                @forelse($topNegara as $i => $neg)
                <div class="top-item">
                    <div class="top-rank {{ $i==0?'gold':($i==1?'silver':($i==2?'bronze':'')) }}">{{ $i+1 }}</div>
                    <div class="top-name">{{ $neg->negara_tujuan }}</div>
                    <div class="top-bar-wrap"><div class="top-bar" style="width:{{ round($neg->total_nilai/$maxNegara*100) }}%"></div></div>
                    <div class="top-val">${{ number_format($neg->total_nilai/1e6,2) }}M</div>
                </div>
                @empty
                <div style="padding:2rem;text-align:center;color:var(--text-dim);font-size:0.8rem;">Belum ada data</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="charts-row" style="margin-bottom:1.5rem;">
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">Top 8 Komoditas Ekspor</div>
                <div class="chart-sub">berdasarkan nilai USD</div>
            </div>
            @php $maxKom = $topKomoditas->max('total_nilai') ?: 1; @endphp
            <div class="top-list">
                @forelse($topKomoditas as $i => $kom)
                <div class="top-item">
                    <div class="top-rank {{ $i==0?'gold':($i==1?'silver':($i==2?'bronze':'')) }}">{{ $i+1 }}</div>
                    <div class="top-name" title="{{ $kom->komoditas }}">{{ $kom->komoditas }}</div>
                    <div class="top-bar-wrap"><div class="top-bar" style="width:{{ round($kom->total_nilai/$maxKom*100) }}%;background:var(--accent3)"></div></div>
                    <div class="top-val">${{ number_format($kom->total_nilai/1e6,2) }}M</div>
                </div>
                @empty
                <div style="padding:2rem;text-align:center;color:var(--text-dim);font-size:0.8rem;">Belum ada data</div>
                @endforelse
            </div>
        </div>
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">Komposisi Ekspor vs Impor</div>
                <div class="chart-sub">berdasarkan jumlah rekod</div>
            </div>
            <div class="chart-body" style="display:flex;align-items:center;justify-content:center;">
                <canvas id="chartDonut" style="max-height:220px;max-width:220px;"></canvas>
            </div>
        </div>
    </div>

    {{-- UPLOAD + LOG --}}
    <div class="section-title"><span></span> Upload Data & Riwayat</div>
    <div class="bottom-grid">

        <div class="upload-card">
            <div class="card-header">
                <div class="card-title">📤 Upload File Data</div>
                <div class="card-desc">CSV, XLSX, atau XLS • Maks 10MB</div>
            </div>

            <div class="drop-zone" id="dropZone">
                <input type="file" id="fileInput" accept=".csv,.xlsx,.xls,.txt">
                <span class="drop-icon">📂</span>
                <div class="drop-title">Drag & drop file di sini</div>
                <div class="drop-subtitle">atau klik untuk pilih file</div>
                <div class="drop-formats">
                    <span class="format-badge fmt-xlsx">XLSX</span>
                    <span class="format-badge fmt-xls">XLS</span>
                    <span class="format-badge fmt-csv">CSV</span>
                </div>
            </div>

            <div class="file-preview" id="filePreview">
                <div class="file-info">
                    <span style="font-size:1.4rem">📄</span>
                    <div>
                        <div class="file-name" id="fileName">—</div>
                        <div class="file-size" id="fileSize">—</div>
                    </div>
                </div>
                <button class="file-remove" onclick="clearFile()">✕ Hapus</button>
            </div>

            <div class="form-options">
                <div class="option-label">Jenis Data</div>
                <div class="jenis-toggle">
                    <button type="button" class="jenis-btn active-ekspor" id="btnEkspor" onclick="setJenis('ekspor')">📦 Ekspor</button>
                    <button type="button" class="jenis-btn" id="btnImpor" onclick="setJenis('impor')">🚢 Impor</button>
                </div>
            </div>

            <div class="upload-progress" id="uploadProgress">
                <div class="progress-bar-wrap">
                    <div class="progress-bar indeterminate" id="progressBar"></div>
                </div>
                <div class="progress-text" id="progressText">Memproses file...</div>
            </div>

            <div class="result-box" id="resultBox">
                <div class="result-title" id="resultTitle"></div>
                <div class="result-msg" id="resultMsg"></div>
            </div>

            <div class="action-row">
                <button class="btn btn-primary btn-upload" id="btnUpload" onclick="doUpload()" disabled>
                    ⬆ Upload & Import
                </button>
                <a href="{{ route('admin.upload.template') }}" class="btn btn-ghost" style="padding:10px 14px;" title="Download Template">📋</a>
            </div>
        </div>

        <div class="logs-card">
            <div class="logs-header">
                <div class="logs-title">📋 Riwayat Upload</div>
                <span class="mono" style="font-size:0.72rem;">{{ $logs->total() }} total</span>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>File</th>
                        <th>Status</th>
                        <th>Berhasil</th>
                        <th>Gagal</th>
                        <th>Waktu</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td>
                            <div style="font-size:0.8rem;font-weight:500;max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $log->original_name }}">
                                {{ $log->original_name }}
                            </div>
                        </td>
                        <td><span class="badge badge-{{ $log->status }}">{{ strtoupper($log->status) }}</span></td>
                        <td style="color:var(--accent3)">{{ number_format($log->success_rows ?? 0) }}</td>
                        <td style="color:var(--red)">{{ number_format($log->failed_rows ?? 0) }}</td>
                        <td class="mono">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.upload.destroy', $log->id) }}"
                                  onsubmit="return confirm('Hapus log ini beserta {{ number_format($log->success_rows ?? 0) }} data terkait?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="del-btn">🗑 Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align:center;color:var(--text-dim);padding:2rem;">Belum ada upload</td></tr>
                    @endforelse
                </tbody>
            </table>
            @if($logs->hasPages())
            <div style="padding:0.75rem 1.25rem;border-top:1px solid var(--border);font-size:0.78rem;color:var(--text-dim);">
                {{ $logs->links() }}
            </div>
            @endif
        </div>

    </div>
</div>

<script>
    Chart.defaults.color = '#9b9890';
    Chart.defaults.borderColor = 'rgba(0,0,0,0.06)';
    Chart.defaults.font.family = "'Inter', sans-serif";

    const tooltip = { backgroundColor:'#fff', borderColor:'#e0ddd8', borderWidth:1, titleColor:'#1a1a1a', bodyColor:'#6b6863', padding:10, cornerRadius:8 };

    new Chart(document.getElementById('chartNilaiTahun'), {
        type: 'bar',
        data: {
            labels: @json($tahunList),
            datasets: [
                { label:'Ekspor (Juta USD)', data:@json($eksporPerTahun), backgroundColor:'rgba(37,99,235,0.7)', borderColor:'#2563eb', borderWidth:1, borderRadius:5 },
                { label:'Impor (Juta USD)',  data:@json($imporPerTahun),  backgroundColor:'rgba(229,62,62,0.6)',  borderColor:'#e53e3e', borderWidth:1, borderRadius:5 }
            ]
        },
        options: { responsive:true, plugins:{ legend:{ labels:{ color:'#6b6863', font:{size:11} } }, tooltip }, scales:{ x:{ ticks:{color:'#9b9890'}, grid:{color:'rgba(0,0,0,0.04)'} }, y:{ ticks:{color:'#9b9890'}, grid:{color:'rgba(0,0,0,0.04)'} } } }
    });

    new Chart(document.getElementById('chartDonut'), {
        type: 'doughnut',
        data: {
            labels: ['Ekspor', 'Impor'],
            datasets: [{ data: [{{ $totalEkspor }}, {{ $totalImpor }}], backgroundColor: ['rgba(37,99,235,0.8)', 'rgba(229,62,62,0.7)'], borderColor: ['#2563eb','#e53e3e'], borderWidth: 2, hoverOffset: 6 }]
        },
        options: { responsive:true, cutout:'65%', plugins:{ legend:{ position:'bottom', labels:{ color:'#6b6863', font:{size:11}, padding:16 } }, tooltip } }
    });

    let selectedFile = null;
    let selectedJenis = 'ekspor';

    const dropZone   = document.getElementById('dropZone');
    const fileInput  = document.getElementById('fileInput');
    const filePreview = document.getElementById('filePreview');
    const btnUpload  = document.getElementById('btnUpload');

    fileInput.addEventListener('change', e => { if(e.target.files[0]) setFile(e.target.files[0]); });
    dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('drag-over'); });
    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('drag-over'));
    dropZone.addEventListener('drop', e => {
        e.preventDefault(); dropZone.classList.remove('drag-over');
        if(e.dataTransfer.files[0]) setFile(e.dataTransfer.files[0]);
    });

    function setFile(f) {
        selectedFile = f;
        document.getElementById('fileName').textContent = f.name;
        document.getElementById('fileSize').textContent = (f.size / 1024).toFixed(1) + ' KB';
        filePreview.classList.add('visible');
        btnUpload.disabled = false;
        hideResult();
    }

    function clearFile() {
        selectedFile = null;
        fileInput.value = '';
        filePreview.classList.remove('visible');
        btnUpload.disabled = true;
        hideResult();
    }

    function setJenis(j) {
        selectedJenis = j;
        document.getElementById('btnEkspor').className = 'jenis-btn' + (j === 'ekspor' ? ' active-ekspor' : '');
        document.getElementById('btnImpor').className  = 'jenis-btn' + (j === 'impor'  ? ' active-impor'  : '');
    }

    function hideResult() {
        document.getElementById('resultBox').className = 'result-box';
    }

    function doUpload() {
        if (!selectedFile) return;
        const fd = new FormData();
        fd.append('file', selectedFile);
        fd.append('jenis', selectedJenis);
        fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);

        document.getElementById('uploadProgress').classList.add('visible');
        btnUpload.disabled = true;
        hideResult();

        fetch('{{ route("admin.upload.store") }}', { method:'POST', body:fd })
            .then(r => r.json())
            .then(data => {
                document.getElementById('uploadProgress').classList.remove('visible');
                const box = document.getElementById('resultBox');
                box.className = 'result-box visible ' + (data.success ? 'success' : 'error');
                document.getElementById('resultTitle').textContent = data.success ? '✅ Berhasil!' : '❌ Gagal';
                document.getElementById('resultMsg').textContent   = data.message;
                if (data.success) setTimeout(() => location.reload(), 1500);
                else btnUpload.disabled = false;
            })
            .catch(() => {
                document.getElementById('uploadProgress').classList.remove('visible');
                const box = document.getElementById('resultBox');
                box.className = 'result-box visible error';
                document.getElementById('resultTitle').textContent = '❌ Error';
                document.getElementById('resultMsg').textContent   = 'Terjadi kesalahan jaringan.';
                btnUpload.disabled = false;
            });
    }

    function toggleMenu() {
        document.getElementById('adminDropdown').classList.toggle('open');
    }
    document.addEventListener('click', function(e) {
        const menu = document.getElementById('adminMenu');
        if (menu && !menu.contains(e.target)) {
            document.getElementById('adminDropdown').classList.remove('open');
        }
    });
</script>
</body>
</html>