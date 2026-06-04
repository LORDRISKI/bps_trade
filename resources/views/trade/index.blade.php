<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Ekspor Impor — BPS Trade</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,700;1,700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
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
            --text: #1a1a1a;
            --text-dim: #9b9890;
            --text-mid: #6b6863;
            --card: #ffffff;
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        /* === NAVBAR === */
        .header {
            background: var(--bg2);
            border-bottom: 1px solid var(--border);
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 100;
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
            width: 44px; height: 44px;
            display: flex; align-items: center; justify-content: center;
        }
        .logo-icon img {
            width: 100%; height: 100%;
            object-fit: contain;
        }
        .logo-text {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text);
            letter-spacing: -0.01em;
        }
        .logo-sub {
            font-size: 0.68rem;
            color: var(--text-dim);
            font-weight: 400;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }
        .header-actions {
            display: flex;
            gap: 24px;
            align-items: center;
        }
        .nav-link {
            font-size: 0.88rem;
            color: var(--text-mid);
            text-decoration: none;
            font-weight: 400;
            transition: color 0.15s;
        }
        .nav-link:hover { color: var(--text); }
        .btn {
            padding: 8px 18px;
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 500;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: all 0.2s;
            border: none;
        }
        .btn-ghost {
            background: transparent;
            color: var(--text-mid);
            border: 1px solid var(--border-dark);
        }
        .btn-ghost:hover {
            background: var(--bg3);
            color: var(--text);
        }
        .btn-primary {
            background: var(--accent);
            color: white;
        }
        .btn-primary:hover {
            background: #16304f;
        }
        .btn-green {
            background: var(--accent3);
            color: white;
        }

        /* === HERO === */
        .hero {
            background: var(--bg2);
            padding: 5rem 2rem 4rem;
            border-bottom: 1px solid var(--border);
            text-align: center;
        }
        .hero-inner {
            max-width: 700px;
            margin: 0 auto;
        }
        .hero-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: transparent;
            border: 1px solid var(--border-dark);
            padding: 5px 16px;
            border-radius: 999px;
            font-size: 0.72rem;
            color: var(--text-mid);
            letter-spacing: 0.07em;
            text-transform: uppercase;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }
        .hero-tag-dot {
            width: 7px; height: 7px;
            border-radius: 50%;
            background: #22c55e;
            display: inline-block;
        }
        .hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.2rem, 5vw, 3.5rem);
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 1.25rem;
            color: var(--text);
        }
        .hero h1 em {
            color: #2563eb;
            font-style: italic;
        }
        .hero h1 .jambi {
            color: #b8860b;
            font-style: italic;
        }
        .hero p {
            color: var(--text-mid);
            font-size: 1rem;
            font-weight: 400;
            max-width: 480px;
            margin: 0 auto 2rem;
            line-height: 1.7;
        }
        .hero-btns {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 3rem;
        }
        .btn-hero-primary {
            background: var(--accent);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px 24px;
            font-size: 0.9rem;
            font-weight: 500;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: background 0.2s;
        }
        .btn-hero-primary:hover { background: #16304f; }
        .btn-hero-outline {
            background: transparent;
            color: var(--text);
            border: 1px solid var(--border-dark);
            border-radius: 8px;
            padding: 12px 24px;
            font-size: 0.9rem;
            font-weight: 500;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: background 0.2s;
        }
        .btn-hero-outline:hover { background: var(--bg3); }
        .stats-row {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        .stat-item {
            background: var(--bg3);
            border-radius: 10px;
            padding: 14px 24px;
            min-width: 160px;
            text-align: center;
        }
        .stat-label {
            font-size: 0.7rem;
            color: var(--text-dim);
            text-transform: uppercase;
            letter-spacing: 0.07em;
            font-weight: 500;
            margin-bottom: 4px;
        }
        .stat-value {
            font-size: 1.35rem;
            font-weight: 600;
            color: var(--accent2);
        }
        .stat-value.green { color: #0f6e56; }
        .stat-value.cyan { color: #0f6e56; }

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
            font-size: 0.72rem;
            font-weight: 600;
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
            font-weight: 500;
            color: var(--text-mid);
            margin-bottom: 6px;
            display: block;
        }
        .filter-input, .filter-select {
            width: 100%;
            background: var(--bg3);
            border: 1px solid var(--border);
            border-radius: 7px;
            padding: 8px 10px;
            color: var(--text);
            font-size: 0.82rem;
            font-family: 'Inter', sans-serif;
            outline: none;
            transition: border-color 0.2s;
            appearance: none;
        }
        .filter-input:focus, .filter-select:focus {
            border-color: var(--accent2);
            background: #fff;
        }
        .filter-select option {
            background: #fff;
            color: var(--text);
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
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }
        .badge-ekspor { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .badge-impor { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

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
            font-weight: 600;
            color: var(--text);
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
            background: var(--bg3);
            padding: 11px 14px;
            text-align: left;
            font-size: 0.69rem;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: var(--text-dim);
            font-weight: 600;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }
        tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background 0.15s;
        }
        tbody tr:hover { background: #f8f7f4; }
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
            border: 1px solid var(--border-dark);
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
            border-color: var(--accent2);
            color: var(--accent2);
        }
        .page-item.disabled .page-link {
            opacity: 0.35;
            cursor: not-allowed;
        }

        /* === LIVE INDICATOR === */
        .live-indicator {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.7rem;
            color: #16a34a;
            font-weight: 600;
            letter-spacing: 0.05em;
        }
        .live-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #22c55e;
            animation: pulse-dot 2s infinite;
        }
        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(0.7); }
        }

        @media (max-width: 900px) {
            .main {
                grid-template-columns: 1fr;
                padding: 1rem;
            }
            .sidebar { position: static; }
            .stats-row { gap: 0.75rem; }
            .header-actions { display: none; }
        }
    </style>
</head>
<body>

<header class="header">
    <div class="header-inner">
        <div class="logo">
            <div class="logo-icon"><img src="/images/logo-bps.png" alt="Logo BPS"></div>
            <div>
                <div class="logo-text">BPS Provinsi Jambi</div>
                <div class="logo-sub">Sistem Informasi Perdagangan</div>
            </div>
        </div>
        <div class="header-actions">
        </div>
    </div>
</header>

<section class="hero">
    <div class="hero-inner">
        <div class="hero-tag"><span class="hero-tag-dot"></span> Portal Data Publik BPS</div>
        <h1>Statistik <em>Ekspor &amp; Impor</em><br>Provinsi <span class="jambi">Jambi</span></h1>
        <p>Akses data perdagangan internasional secara terbuka — berdasarkan komoditas, negara tujuan, pelabuhan, dan periode waktu.</p>
        <div style="margin-bottom: 3rem;"></div>
        <div class="stats-row">
            <div class="stat-item">
                <div class="stat-label">Total Records</div>
                <div class="stat-value">{{ number_format($totalRecords) }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Total Berat (Kg)</div>
                <div class="stat-value cyan">{{ number_format($totalBerat, 0) }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Total Nilai (USD)</div>
                <div class="stat-value green">{{ number_format($totalNilai, 0) }}</div>
            </div>
        </div>
    </div>
</section>

<div class="main" id="data-section">
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
                {{-- Indikator live sync --}}
                <div class="live-indicator">
                    <span class="live-dot"></span> LIVE
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
                            <td>
                                <a href="{{ route('trade.export.single', $row->id) }}"
                                   class="btn btn-green"
                                   style="padding:4px 10px; font-size:0.72rem;">
                                    ⬇
                                </a>
                            </td>
                        </tr>
                       @empty
                        <tr>
                            <td colspan="10">
                                <div class="empty-state">
                                    <div class="empty-icon">🔍</div>
                                    <div class="empty-title">Tidak ada data ditemukan</div>
                                    <div class="empty-desc">Coba ubah filter atau hubungi admin untuk upload data.</div>
                                </div>
                            </td>
                        </tr>
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

<script>
    // Cek setiap 10 detik apakah jumlah data berubah
    let currentCount = {{ $totalRecords ?? 0 }};

    setInterval(async () => {
        try {
            const res = await fetch('{{ route("trade.count") }}');
            const data = await res.json();
            if (data.count !== currentCount) {
                location.reload();
            }
        } catch (e) {}
    }, 10000); // 10 detik
</script>

</body>
</html>