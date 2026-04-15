<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Ekspor Impor — BPS Trade</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
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
            --text: #e2e8f0;
            --text-dim: #64748b;
            --text-mid: #94a3b8;
            --card: rgba(15,22,37,0.95);
            --glow: 0 0 30px rgba(59,130,246,0.15);
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Sora', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        /* === HEADER === */
        .header {
            background: linear-gradient(135deg, #0a0e1a 0%, #0d1829 50%, #091220 100%);
            border-bottom: 1px solid var(--border);
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(20px);
        }
        .header-inner {
            max-width: 1400px;
            margin: 0 auto;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .logo-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
        }
        .logo-text {
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: -0.02em;
        }
        .logo-sub {
            font-size: 0.7rem;
            color: var(--text-dim);
            font-weight: 400;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }
        .header-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }
        .btn {
            padding: 8px 18px;
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            font-family: 'Sora', sans-serif;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: all 0.2s;
        }
        .btn-ghost {
            background: transparent;
            color: var(--text-mid);
            border: 1px solid var(--border);
        }
        .btn-ghost:hover {
            border-color: var(--accent);
            color: var(--accent);
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--accent), #2563eb);
            color: white;
            box-shadow: 0 4px 15px rgba(59,130,246,0.3);
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(59,130,246,0.4);
        }
        .btn-green {
            background: linear-gradient(135deg, var(--accent3), #059669);
            color: white;
        }

        /* === HERO === */
        .hero {
            background: linear-gradient(135deg, #0a0e1a 0%, #0d1829 100%);
            padding: 3rem 2rem 2.5rem;
            border-bottom: 1px solid var(--border);
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: -80px; right: -100px;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(59,130,246,0.08) 0%, transparent 70%);
            pointer-events: none;
        }
        .hero-inner {
            max-width: 1400px;
            margin: 0 auto;
        }
        .hero-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(59,130,246,0.1);
            border: 1px solid rgba(59,130,246,0.25);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.72rem;
            color: var(--accent);
            letter-spacing: 0.06em;
            text-transform: uppercase;
            margin-bottom: 1rem;
            font-weight: 600;
        }
        .hero h1 {
            font-size: clamp(1.6rem, 3vw, 2.4rem);
            font-weight: 800;
            letter-spacing: -0.03em;
            line-height: 1.15;
            margin-bottom: 0.6rem;
        }
        .hero h1 span {
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero p {
            color: var(--text-mid);
            font-size: 0.95rem;
            font-weight: 300;
            max-width: 600px;
        }
        .stats-row {
            display: flex;
            gap: 2rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }
        .stat-item {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }
        .stat-label {
            font-size: 0.72rem;
            color: var(--text-dim);
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }
        .stat-value {
            font-size: 1.4rem;
            font-weight: 700;
            font-family: 'JetBrains Mono', monospace;
            color: var(--accent);
        }
        .stat-value.green { color: var(--accent3); }
        .stat-value.cyan { color: var(--accent2); }

        /* === MAIN LAYOUT === */
        .main {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
            display: grid;
            grid-template-columns: 260px 1fr;
            gap: 1.5rem;
            align-items: start;
        }

        /* === FILTER SIDEBAR === */
        .sidebar {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1.25rem;
            position: sticky;
            top: 80px;
        }
        .sidebar-title {
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--text-dim);
            margin-bottom: 1.2rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .sidebar-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }
        .filter-group {
            margin-bottom: 1rem;
        }
        .filter-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-mid);
            margin-bottom: 6px;
            display: block;
        }
        .filter-input, .filter-select {
            width: 100%;
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--border);
            border-radius: 7px;
            padding: 8px 10px;
            color: var(--text);
            font-size: 0.82rem;
            font-family: 'Sora', sans-serif;
            outline: none;
            transition: border-color 0.2s;
            appearance: none;
        }
        .filter-input:focus, .filter-select:focus {
            border-color: var(--accent);
            background: rgba(59,130,246,0.05);
        }
        .filter-select option {
            background: #0f1625;
        }
        .filter-actions {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-top: 1.25rem;
        }
        .btn-filter {
            width: 100%;
            justify-content: center;
            padding: 9px;
        }
        .badge-jenis {
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }
        .badge-ekspor { background: rgba(16,185,129,0.15); color: var(--accent3); border: 1px solid rgba(16,185,129,0.25); }
        .badge-impor { background: rgba(244,63,94,0.12); color: var(--red); border: 1px solid rgba(244,63,94,0.2); }

        /* === TABLE AREA === */
        .table-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
        }
        .table-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .table-title {
            font-size: 0.9rem;
            font-weight: 700;
        }
        .records-count {
            font-size: 0.78rem;
            color: var(--text-dim);
        }
        .table-wrapper {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.82rem;
        }
        thead th {
            background: rgba(255,255,255,0.03);
            padding: 11px 14px;
            text-align: left;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: var(--text-dim);
            font-weight: 700;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }
        tbody tr {
            border-bottom: 1px solid rgba(99,179,237,0.06);
            transition: background 0.15s;
        }
        tbody tr:hover { background: rgba(59,130,246,0.04); }
        tbody tr:last-child { border-bottom: none; }
        td {
            padding: 11px 14px;
            color: var(--text);
            vertical-align: middle;
            white-space: nowrap;
        }
        td.mono {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.8rem;
            color: var(--accent2);
        }
        td.number {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.8rem;
            text-align: right;
            color: var(--text-mid);
        }
        .komoditas-cell {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-weight: 500;
        }
        .empty-state {
            text-align: center;
            padding: 5rem 2rem;
            color: var(--text-dim);
        }
        .empty-icon { font-size: 3rem; margin-bottom: 1rem; opacity: 0.4; }
        .empty-title { font-size: 1rem; font-weight: 600; color: var(--text-mid); margin-bottom: 0.5rem; }
        .empty-desc { font-size: 0.82rem; }

        /* === PAGINATION === */
        .pagination-wrap {
            padding: 1.25rem 1.5rem;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .pagination-info {
            font-size: 0.78rem;
            color: var(--text-dim);
        }
        .pagination {
            display: flex;
            gap: 4px;
            list-style: none;
        }
        .page-item .page-link {
            display: block;
            padding: 6px 11px;
            border-radius: 6px;
            font-size: 0.78rem;
            color: var(--text-mid);
            border: 1px solid var(--border);
            text-decoration: none;
            transition: all 0.15s;
            background: transparent;
        }
        .page-item.active .page-link {
            background: var(--accent);
            color: white;
            border-color: var(--accent);
        }
        .page-item .page-link:hover:not(.disabled) {
            border-color: var(--accent);
            color: var(--accent);
        }
        .page-item.disabled .page-link {
            opacity: 0.35;
            cursor: not-allowed;
        }

        @media (max-width: 900px) {
            .main {
                grid-template-columns: 1fr;
                padding: 1rem;
            }
            .sidebar { position: static; }
            .stats-row { gap: 1.2rem; }
            .header-actions { display: none; }
        }
    </style>
</head>
<body>

<header class="header">
    <div class="header-inner">
        <div class="logo">
            <div class="logo-icon">📊</div>
            <div>
                <div class="logo-text">BPS Trade Data</div>
                <div class="logo-sub">Sistem Informasi Perdagangan</div>
            </div>
        </div>
        <div class="header-actions">
        </div>
    </div>
</header>

<section class="hero">
    <div class="hero-inner">
        <div class="hero-tag">📦 Portal Data Publik</div>
        <h1>Data <span>Ekspor &amp; Impor</span><br>Indonesia</h1>
        <p>Akses dan unduh data perdagangan internasional berdasarkan komoditas, negara tujuan, pelabuhan, dan periode waktu.</p>
        <div class="stats-row">
            <div class="stat-item">
                <span class="stat-label">Total Records</span>
                <span class="stat-value">{{ number_format($totalRecords) }}</span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Total Berat (Kg)</span>
                <span class="stat-value cyan">{{ number_format($totalBerat, 0) }}</span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Total Nilai (USD)</span>
                <span class="stat-value green">{{ number_format($totalNilai, 0) }}</span>
            </div>
        </div>
    </div>
</section>

<div class="main">
    <!-- SIDEBAR FILTER -->
    <aside class="sidebar">
        <div class="sidebar-title">Filter Data</div>
        <form method="GET" action="{{ route('trade.index') }}">
            <div class="filter-group">
                <label class="filter-label">Jenis</label>
                <select name="jenis" class="filter-select">
                    <option value="">Semua</option>
                    <option value="ekspor" {{ ($filters['jenis'] ?? '') === 'ekspor' ? 'selected' : '' }}>Ekspor</option>
                    <option value="impor" {{ ($filters['jenis'] ?? '') === 'impor' ? 'selected' : '' }}>Impor</option>
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">Tahun</label>
                <select name="tahun" class="filter-select">
                    <option value="">Semua Tahun</option>
                    @foreach($tahunList as $t)
                        <option value="{{ $t }}" {{ ($filters['tahun'] ?? '') == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">Komoditas</label>
                <input type="text" name="komoditas" class="filter-input"
                    placeholder="Cari komoditas..."
                    value="{{ $filters['komoditas'] ?? '' }}">
            </div>
            <div class="filter-group">
                <label class="filter-label">Negara Tujuan</label>
                <input type="text" name="negara_tujuan" class="filter-input"
                    placeholder="Cari negara..."
                    value="{{ $filters['negara_tujuan'] ?? '' }}">
            </div>
            <div class="filter-group">
                <label class="filter-label">Pelabuhan</label>
                <select name="pelabuhan" class="filter-select">
                    <option value="">Semua Pelabuhan</option>
                    @foreach($pelabuhanList as $p)
                        <option value="{{ $p }}" {{ ($filters['pelabuhan'] ?? '') === $p ? 'selected' : '' }}>{{ $p }}</option>
                    @endforeach
                </select>
            </div>
            <!-- HS Code -->
            <div class="filter-group">
                <label class="filter-label">HS Code</label>
                <input type="text" name="hs_code" class="filter-input"
                    placeholder="Cari HS Code..."
                    value="{{ $filters['hs_code'] ?? '' }}">
            </div>

            <!-- Bulan -->
            <div class="filter-group">
                <label class="filter-label">Bulan</label>
                <select name="bulan" class="filter-select">
                    <option value="">Semua Bulan</option>
                    @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $i => $bln)
                        <option value="{{ $i+1 }}" {{ ($filters['bulan'] ?? '') == $i+1 ? 'selected' : '' }}>{{ $bln }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Range Berat -->
            <div class="filter-group">
                <label class="filter-label">Berat (Kg) — Range</label>
                <div style="display:flex; gap:6px;">
                    <input type="number" name="berat_min" class="filter-input" placeholder="Min" value="{{ $filters['berat_min'] ?? '' }}" style="width:50%">
                    <input type="number" name="berat_max" class="filter-input" placeholder="Max" value="{{ $filters['berat_max'] ?? '' }}" style="width:50%">
                </div>
            </div>

            <!-- Range Nilai -->
            <div class="filter-group">
                <label class="filter-label">Nilai (USD) — Range</label>
                <div style="display:flex; gap:6px;">
                    <input type="number" name="nilai_min" class="filter-input" placeholder="Min" value="{{ $filters['nilai_min'] ?? '' }}" style="width:50%">
                    <input type="number" name="nilai_max" class="filter-input" placeholder="Max" value="{{ $filters['nilai_max'] ?? '' }}" style="width:50%">
                </div>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn btn-primary btn-filter">
                    🔍 Terapkan Filter
                </button>
                <a href="{{ route('trade.index') }}" class="btn btn-ghost btn-filter">
                    ↺ Reset
                </a>
                <a href="{{ route('trade.export') }}?{{ http_build_query($filters ?? []) }}" class="btn btn-green btn-filter">
                    ⬇ Unduh CSV
                </a>
            </div>
        </form>
    </aside>

    <!-- TABLE AREA -->
    <main>
        <div class="table-card">
            <div class="table-header">
                <div>
                    <div class="table-title">Hasil Data</div>
                    <div class="records-count">{{ number_format($data->total()) }} records ditemukan</div>
                </div>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Tahun</th>
                            <th>Jenis</th>
                            <th>HS Code</th>
                            <th>Komoditas</th>
                            <th>Negara Tujuan</th>
                            <th style="text-align:right">Berat (Kg)</th>
                            <th style="text-align:right">Nilai (USD)</th>
                            <th>Pelabuhan</th>
                            <th>Keterangan</th>
                            <th>Unduh</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $row)
                        <tr>
                            <td class="mono">{{ $row->tahun }}</td>
                            <td>
                                <span class="badge-jenis badge-{{ $row->jenis }}">{{ $row->jenis }}</span>
                            </td>
                            <td class="mono">{{ $row->hs_code ?? '—' }}</td>
                            <td>
                                <div class="komoditas-cell" title="{{ $row->komoditas }}">{{ $row->komoditas }}</div>
                            </td>
                            <td>{{ $row->negara_tujuan }}</td>
                            <td class="number">{{ $row->berat_kg ? number_format($row->berat_kg, 2) : '—' }}</td>
                            <td class="number">{{ $row->nilai_usd ? number_format($row->nilai_usd, 2) : '—' }}</td>
                            <td>{{ $row->pelabuhan ?? '—' }}</td>
                            <td style="color:var(--text-dim); font-size:0.78rem; max-width:150px; overflow:hidden; text-overflow:ellipsis">
                                {{ $row->keterangan ?? '—' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <div class="empty-icon">📭</div>
                                    <div class="empty-title">Tidak ada data ditemukan</div>
                                    <div class="empty-desc">Coba ubah filter atau hubungi admin untuk upload data.</div>
                                </div>
                            </td>
                        </tr>
                        <td>
                            <a href="{{ route('trade.export.single', $row->id) }}"
                            class="btn btn-green"
                            style="padding:4px 10px; font-size:0.72rem;">
                                ⬇
                            </a>
                        </td>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($data->hasPages())
            <div class="pagination-wrap">
                <div class="pagination-info">
                    Menampilkan {{ $data->firstItem() }}–{{ $data->lastItem() }} dari {{ number_format($data->total()) }}
                </div>
                <ul class="pagination">
                    @if($data->onFirstPage())
                        <li class="page-item disabled"><span class="page-link">‹</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $data->previousPageUrl() }}">‹</a></li>
                    @endif

                    @foreach($data->getUrlRange(max(1,$data->currentPage()-2), min($data->lastPage(),$data->currentPage()+2)) as $page => $url)
                        <li class="page-item {{ $page === $data->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    @if($data->hasMorePages())
                        <li class="page-item"><a class="page-link" href="{{ $data->nextPageUrl() }}">›</a></li>
                    @else
                        <li class="page-item disabled"><span class="page-link">›</span></li>
                    @endif
                </ul>
            </div>
            @endif
        </div>
    </main>
</div>

</body>
</html>
