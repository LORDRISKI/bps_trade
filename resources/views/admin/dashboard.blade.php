<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — BPS Trade</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        :root {
            --bg: #0a0e1a;
            --bg2: #0f1625;
            --bg3: #151d30;
            --border: rgba(99,179,237,0.12);
            --accent: #3b82f6;
            --accent2: #06b6d4;
            --accent3: #10b981;
            --red: #f43f5e;
            --yellow: #f59e0b;
            --purple: #8b5cf6;
            --text: #e2e8f0;
            --text-dim: #64748b;
            --text-mid: #94a3b8;
            --card: rgba(15,22,37,0.95);
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Sora',sans-serif; background:var(--bg); color:var(--text); min-height:100vh; }

        /* HEADER */
        .header { background:var(--bg2); border-bottom:1px solid var(--border); padding:0 2rem; }
        .header-inner { max-width:1300px; margin:0 auto; height:64px; display:flex; align-items:center; justify-content:space-between; }
        .logo { display:flex; align-items:center; gap:12px; }
        .logo-icon { width:36px; height:36px; background:linear-gradient(135deg,var(--accent),var(--accent2)); border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:18px; }
        .logo-text { font-size:1.1rem; font-weight:700; }
        .logo-sub { font-size:0.7rem; color:var(--text-dim); text-transform:uppercase; letter-spacing:0.05em; }
        .nav-links { display:flex; align-items:center; gap:8px; }
        .btn { padding:8px 18px; border-radius:8px; font-size:0.82rem; font-weight:600; cursor:pointer; border:none; font-family:'Sora',sans-serif; text-decoration:none; display:inline-flex; align-items:center; gap:7px; transition:all 0.2s; }
        .btn-ghost { background:transparent; color:var(--text-mid); border:1px solid var(--border); }
        .btn-ghost:hover { border-color:var(--accent); color:var(--accent); }
        .btn-active { background:rgba(59,130,246,0.12); color:var(--accent); border:1px solid rgba(59,130,246,0.3); }

        /* MAIN */
        .main { max-width:1300px; margin:0 auto; padding:2rem; }

        /* PAGE TITLE */
        .page-title { margin-bottom:1.75rem; }
        .page-title h1 { font-size:1.5rem; font-weight:800; margin-bottom:4px; }
        .page-title p { font-size:0.85rem; color:var(--text-dim); }

        /* STAT CARDS */
        .stats-grid { display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:1rem; margin-bottom:1.5rem; }
        .stat-card { background:var(--card); border:1px solid var(--border); border-radius:12px; padding:1.25rem 1.5rem; position:relative; overflow:hidden; }
        .stat-card::before { content:''; position:absolute; top:0; left:0; right:0; height:2px; }
        .stat-card.blue::before { background:linear-gradient(90deg,var(--accent),var(--accent2)); }
        .stat-card.green::before { background:linear-gradient(90deg,var(--accent3),#34d399); }
        .stat-card.red::before { background:linear-gradient(90deg,var(--red),#fb7185); }
        .stat-card.yellow::before { background:linear-gradient(90deg,var(--yellow),#fcd34d); }
        .stat-card.purple::before { background:linear-gradient(90deg,var(--purple),#a78bfa); }
        .stat-card.teal::before { background:linear-gradient(90deg,var(--accent2),#67e8f9); }
        .stat-label { font-size:0.72rem; text-transform:uppercase; letter-spacing:0.07em; color:var(--text-dim); margin-bottom:8px; font-weight:700; }
        .stat-value { font-size:1.6rem; font-weight:800; line-height:1; margin-bottom:4px; }
        .stat-sub { font-size:0.75rem; color:var(--text-dim); }
        .stat-icon { position:absolute; right:1.25rem; top:1.25rem; font-size:1.8rem; opacity:0.15; }

        /* CHARTS GRID */
        .charts-grid { display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; margin-bottom:1.25rem; }
        .charts-grid.full { grid-template-columns:1fr; }
        .chart-card { background:var(--card); border:1px solid var(--border); border-radius:12px; overflow:hidden; }
        .chart-card.span2 { grid-column: span 2; }
        .chart-header { padding:1.1rem 1.5rem; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; }
        .chart-title { font-size:0.88rem; font-weight:700; }
        .chart-sub { font-size:0.72rem; color:var(--text-dim); margin-top:2px; }
        .chart-body { padding:1.25rem; position:relative; }
        .chart-body canvas { max-height:280px; }

        /* NERACA BADGE */
        .neraca-badge { padding:4px 12px; border-radius:20px; font-size:0.72rem; font-weight:700; }
        .neraca-surplus { background:rgba(16,185,129,0.12); color:var(--accent3); border:1px solid rgba(16,185,129,0.25); }
        .neraca-defisit { background:rgba(244,63,94,0.1); color:var(--red); border:1px solid rgba(244,63,94,0.2); }

        /* TOP LIST */
        .top-list { padding:0; }
        .top-item { display:flex; align-items:center; gap:10px; padding:0.65rem 1.5rem; border-bottom:1px solid rgba(99,179,237,0.04); }
        .top-item:last-child { border:none; }
        .top-rank { width:22px; height:22px; border-radius:6px; background:rgba(255,255,255,0.04); font-size:0.68rem; font-weight:800; display:flex; align-items:center; justify-content:center; color:var(--text-dim); flex-shrink:0; }
        .top-rank.gold { background:rgba(245,158,11,0.15); color:var(--yellow); }
        .top-rank.silver { background:rgba(148,163,184,0.1); color:#94a3b8; }
        .top-rank.bronze { background:rgba(180,83,9,0.12); color:#b45309; }
        .top-name { flex:1; font-size:0.8rem; font-weight:600; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .top-bar-wrap { width:120px; height:5px; background:rgba(255,255,255,0.06); border-radius:100px; flex-shrink:0; }
        .top-bar { height:100%; border-radius:100px; background:linear-gradient(90deg,var(--accent),var(--accent2)); }
        .top-val { font-size:0.72rem; color:var(--text-dim); font-family:'JetBrains Mono',monospace; white-space:nowrap; min-width:80px; text-align:right; }

        /* RECENT LOGS */
        .logs-table { width:100%; border-collapse:collapse; font-size:0.8rem; }
        .logs-table thead th { padding:9px 14px; text-align:left; font-size:0.68rem; text-transform:uppercase; letter-spacing:0.07em; color:var(--text-dim); border-bottom:1px solid var(--border); background:rgba(255,255,255,0.02); }
        .logs-table tbody tr { border-bottom:1px solid rgba(99,179,237,0.04); }
        .logs-table tbody tr:hover { background:rgba(59,130,246,0.03); }
        .logs-table td { padding:9px 14px; vertical-align:middle; }
        .mono { font-family:'JetBrains Mono',monospace; font-size:0.75rem; color:var(--text-dim); }
        .badge { padding:2px 8px; border-radius:20px; font-size:0.65rem; font-weight:700; }
        .badge-done { background:rgba(16,185,129,0.12); color:var(--accent3); border:1px solid rgba(16,185,129,0.25); }
        .badge-processing { background:rgba(245,158,11,0.12); color:var(--yellow); border:1px solid rgba(245,158,11,0.25); }
        .badge-failed { background:rgba(244,63,94,0.1); color:var(--red); border:1px solid rgba(244,63,94,0.2); }

        @media(max-width:900px) {
            .charts-grid { grid-template-columns:1fr; }
            .chart-card.span2 { grid-column:span 1; }
            .stats-grid { grid-template-columns:repeat(2,1fr); }
        }
        @media(max-width:500px) {
            .stats-grid { grid-template-columns:1fr; }
            .main { padding:1rem; }
        }
    </style>
</head>
<body>

<header class="header">
    <div class="header-inner">
        <div class="logo">
            <div class="logo-icon">📊</div>
            <div>
                <div class="logo-text">Admin Panel</div>
                <div class="logo-sub">BPS Trade System</div>
            </div>
        </div>
        <div class="nav-links">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-active">📈 Dashboard</a>
            <a href="{{ route('admin.upload.index') }}" class="btn btn-ghost">📤 Upload Data</a>
            <a href="{{ route('trade.index') }}" class="btn btn-ghost">← Data Publik</a>
            <form method="POST" action="{{ route('admin.logout') }}" style="display:inline">
                @csrf
                <button type="submit" class="btn btn-ghost">Logout</button>
            </form>
        </div>
    </div>
</header>

<div class="main">

    <div class="page-title">
        <h1>Dashboard Perdagangan</h1>
        <p>Statistik dan visualisasi data ekspor-impor BPS Provinsi Jambi</p>
    </div>

    {{-- STAT CARDS --}}
    <div class="stats-grid">
        <div class="stat-card blue">
            <div class="stat-icon">📦</div>
            <div class="stat-label">Total Rekod Ekspor</div>
            <div class="stat-value">{{ number_format($totalEkspor) }}</div>
            <div class="stat-sub">baris data</div>
        </div>
        <div class="stat-card red">
            <div class="stat-icon">🚢</div>
            <div class="stat-label">Total Rekod Impor</div>
            <div class="stat-value">{{ number_format($totalImpor) }}</div>
            <div class="stat-sub">baris data</div>
        </div>
        <div class="stat-card green">
            <div class="stat-icon">💰</div>
            <div class="stat-label">Nilai Ekspor</div>
            <div class="stat-value">$ {{ number_format($totalNilaiEkspor / 1e6, 1) }}M</div>
            <div class="stat-sub">USD</div>
        </div>
        <div class="stat-card yellow">
            <div class="stat-icon">💸</div>
            <div class="stat-label">Nilai Impor</div>
            <div class="stat-value">$ {{ number_format($totalNilaiImpor / 1e6, 1) }}M</div>
            <div class="stat-sub">USD</div>
        </div>
        <div class="stat-card {{ $neracaPerdagangan >= 0 ? 'teal' : 'red' }}">
            <div class="stat-icon">⚖️</div>
            <div class="stat-label">Neraca Perdagangan</div>
            <div class="stat-value" style="color:{{ $neracaPerdagangan >= 0 ? 'var(--accent2)' : 'var(--red)' }}">
                {{ $neracaPerdagangan >= 0 ? '+' : '' }}$ {{ number_format(abs($neracaPerdagangan) / 1e6, 1) }}M
            </div>
            <div class="stat-sub">{{ $neracaPerdagangan >= 0 ? 'Surplus' : 'Defisit' }}</div>
        </div>
        <div class="stat-card purple">
            <div class="stat-icon">📁</div>
            <div class="stat-label">File Terupload</div>
            <div class="stat-value">{{ $totalUpload }}</div>
            <div class="stat-sub">upload berhasil</div>
        </div>
    </div>

    {{-- CHART ROW 1: Nilai per Tahun + Top Negara --}}
    <div class="charts-grid">
        <div class="chart-card">
            <div class="chart-header">
                <div>
                    <div class="chart-title">Nilai Ekspor & Impor per Tahun</div>
                    <div class="chart-sub">dalam juta USD</div>
                </div>
            </div>
            <div class="chart-body">
                <canvas id="chartNilaiTahun"></canvas>
            </div>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <div>
                    <div class="chart-title">Top 10 Negara Tujuan Ekspor</div>
                    <div class="chart-sub">berdasarkan nilai USD</div>
                </div>
            </div>
            @php $maxNegara = $topNegara->max('total_nilai') ?: 1; @endphp
            <div class="top-list">
                @forelse($topNegara as $i => $negara)
                <div class="top-item">
                    <div class="top-rank {{ $i == 0 ? 'gold' : ($i == 1 ? 'silver' : ($i == 2 ? 'bronze' : '')) }}">
                        {{ $i + 1 }}
                    </div>
                    <div class="top-name" title="{{ $negara->negara_tujuan }}">{{ $negara->negara_tujuan }}</div>
                    <div class="top-bar-wrap">
                        <div class="top-bar" style="width:{{ round($negara->total_nilai / $maxNegara * 100) }}%"></div>
                    </div>
                    <div class="top-val">$ {{ number_format($negara->total_nilai / 1e6, 2) }}M</div>
                </div>
                @empty
                <div style="padding:2rem; text-align:center; color:var(--text-dim); font-size:0.82rem;">Belum ada data</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- CHART ROW 2: Berat per Tahun + Top Komoditas --}}
    <div class="charts-grid">
        <div class="chart-card">
            <div class="chart-header">
                <div>
                    <div class="chart-title">Volume Berat Ekspor & Impor per Tahun</div>
                    <div class="chart-sub">dalam juta kilogram</div>
                </div>
            </div>
            <div class="chart-body">
                <canvas id="chartBeratTahun"></canvas>
            </div>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <div>
                    <div class="chart-title">Top 10 Komoditas Ekspor</div>
                    <div class="chart-sub">berdasarkan nilai USD</div>
                </div>
            </div>
            @php $maxKomoditas = $topKomoditas->max('total_nilai') ?: 1; @endphp
            <div class="top-list">
                @forelse($topKomoditas as $i => $kom)
                <div class="top-item">
                    <div class="top-rank {{ $i == 0 ? 'gold' : ($i == 1 ? 'silver' : ($i == 2 ? 'bronze' : '')) }}">
                        {{ $i + 1 }}
                    </div>
                    <div class="top-name" title="{{ $kom->komoditas }}">{{ $kom->komoditas }}</div>
                    <div class="top-bar-wrap">
                        <div class="top-bar" style="width:{{ round($kom->total_nilai / $maxKomoditas * 100) }}%; background:linear-gradient(90deg,var(--accent3),#34d399)"></div>
                    </div>
                    <div class="top-val">$ {{ number_format($kom->total_nilai / 1e6, 2) }}M</div>
                </div>
                @empty
                <div style="padding:2rem; text-align:center; color:var(--text-dim); font-size:0.82rem;">Belum ada data</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- CHART ROW 3: Per Pelabuhan (full width) --}}
    @if(count($pelabuhanLabels) > 0)
    <div class="charts-grid" style="grid-template-columns:1fr; margin-bottom:1.25rem;">
        <div class="chart-card">
            <div class="chart-header">
                <div>
                    <div class="chart-title">Nilai Ekspor & Impor per Pelabuhan</div>
                    <div class="chart-sub">Top 8 pelabuhan, dalam juta USD</div>
                </div>
            </div>
            <div class="chart-body">
                <canvas id="chartPelabuhan" style="max-height:300px;"></canvas>
            </div>
        </div>
    </div>
    @endif

    {{-- RECENT UPLOADS --}}
    <div class="chart-card" style="margin-bottom:1.25rem;">
        <div class="chart-header">
            <div>
                <div class="chart-title">Upload Terbaru</div>
                <div class="chart-sub">5 upload terakhir</div>
            </div>
            <a href="{{ route('admin.upload.index') }}" class="btn btn-ghost" style="font-size:0.75rem; padding:5px 12px;">Lihat Semua →</a>
        </div>
        <table class="logs-table">
            <thead>
                <tr>
                    <th>File</th>
                    <th>Status</th>
                    <th>Berhasil</th>
                    <th>Gagal</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentLogs as $log)
                <tr>
                    <td>
                        <div style="font-size:0.8rem; font-weight:600;">{{ $log->original_name }}</div>
                        <div class="mono">{{ $log->filename }}</div>
                    </td>
                    <td>
                        <span class="badge badge-{{ $log->status }}">{{ strtoupper($log->status) }}</span>
                    </td>
                    <td style="color:var(--accent3)">{{ number_format($log->success_rows ?? 0) }}</td>
                    <td style="color:var(--red)">{{ number_format($log->failed_rows ?? 0) }}</td>
                    <td class="mono">{{ $log->created_at->format('d M Y H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center; color:var(--text-dim); padding:2rem;">Belum ada upload</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

<script>
    // Data dari PHP
    const tahunLabels     = @json($tahunList);
    const eksporNilai     = @json($eksporPerTahun);
    const imporNilai      = @json($imporPerTahun);
    const eksporBerat     = @json($eksporBeratPerTahun);
    const imporBerat      = @json($imporBeratPerTahun);
    const pelabuhanLabels = @json($pelabuhanLabels);
    const pelabuhanEkspor = @json($pelabuhanEkspor);
    const pelabuhanImpor  = @json($pelabuhanImpor);

    // Default Chart.js global config
    Chart.defaults.color = '#64748b';
    Chart.defaults.borderColor = 'rgba(99,179,237,0.08)';
    Chart.defaults.font.family = "'Sora', sans-serif";

    const tooltipStyle = {
        backgroundColor: '#0f1625',
        borderColor: 'rgba(99,179,237,0.2)',
        borderWidth: 1,
        titleColor: '#e2e8f0',
        bodyColor: '#94a3b8',
        padding: 10,
        cornerRadius: 8,
    };

    // === CHART 1: Nilai per Tahun ===
    new Chart(document.getElementById('chartNilaiTahun'), {
        type: 'bar',
        data: {
            labels: tahunLabels,
            datasets: [
                {
                    label: 'Ekspor (Juta USD)',
                    data: eksporNilai,
                    backgroundColor: 'rgba(59,130,246,0.7)',
                    borderColor: '#3b82f6',
                    borderWidth: 1,
                    borderRadius: 5,
                },
                {
                    label: 'Impor (Juta USD)',
                    data: imporNilai,
                    backgroundColor: 'rgba(244,63,94,0.6)',
                    borderColor: '#f43f5e',
                    borderWidth: 1,
                    borderRadius: 5,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { labels: { color: '#94a3b8', font: { size: 11 } } }, tooltip: tooltipStyle },
            scales: {
                x: { ticks: { color: '#64748b' }, grid: { color: 'rgba(99,179,237,0.05)' } },
                y: { ticks: { color: '#64748b' }, grid: { color: 'rgba(99,179,237,0.05)' } }
            }
        }
    });

    // === CHART 2: Berat per Tahun ===
    new Chart(document.getElementById('chartBeratTahun'), {
        type: 'line',
        data: {
            labels: tahunLabels,
            datasets: [
                {
                    label: 'Ekspor (Juta Kg)',
                    data: eksporBerat,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16,185,129,0.1)',
                    borderWidth: 2,
                    pointBackgroundColor: '#10b981',
                    pointRadius: 4,
                    fill: true,
                    tension: 0.3,
                },
                {
                    label: 'Impor (Juta Kg)',
                    data: imporBerat,
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245,158,11,0.08)',
                    borderWidth: 2,
                    pointBackgroundColor: '#f59e0b',
                    pointRadius: 4,
                    fill: true,
                    tension: 0.3,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { labels: { color: '#94a3b8', font: { size: 11 } } }, tooltip: tooltipStyle },
            scales: {
                x: { ticks: { color: '#64748b' }, grid: { color: 'rgba(99,179,237,0.05)' } },
                y: { ticks: { color: '#64748b' }, grid: { color: 'rgba(99,179,237,0.05)' } }
            }
        }
    });

    // === CHART 3: Per Pelabuhan ===
    if (document.getElementById('chartPelabuhan')) {
        new Chart(document.getElementById('chartPelabuhan'), {
            type: 'bar',
            data: {
                labels: pelabuhanLabels,
                datasets: [
                    {
                        label: 'Ekspor (Juta USD)',
                        data: pelabuhanEkspor,
                        backgroundColor: 'rgba(6,182,212,0.7)',
                        borderColor: '#06b6d4',
                        borderWidth: 1,
                        borderRadius: 5,
                    },
                    {
                        label: 'Impor (Juta USD)',
                        data: pelabuhanImpor,
                        backgroundColor: 'rgba(139,92,246,0.6)',
                        borderColor: '#8b5cf6',
                        borderWidth: 1,
                        borderRadius: 5,
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: { legend: { labels: { color: '#94a3b8', font: { size: 11 } } }, tooltip: tooltipStyle },
                scales: {
                    x: { ticks: { color: '#64748b', maxRotation: 30 }, grid: { color: 'rgba(99,179,237,0.05)' } },
                    y: { ticks: { color: '#64748b' }, grid: { color: 'rgba(99,179,237,0.05)' } }
                }
            }
        });
    }
</script>

</body>
</html>