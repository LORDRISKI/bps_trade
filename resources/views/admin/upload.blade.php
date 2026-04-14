<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Upload — BPS Trade</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            --yellow: #f59e0b;
            --text: #e2e8f0;
            --text-dim: #64748b;
            --text-mid: #94a3b8;
            --card: rgba(15,22,37,0.95);
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Sora', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        .header {
            background: var(--bg2);
            border-bottom: 1px solid var(--border);
            padding: 0 2rem;
        }
        .header-inner {
            max-width: 1100px;
            margin: 0 auto;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .logo {
            display: flex; align-items: center; gap: 12px;
        }
        .logo-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
        }
        .logo-text { font-size: 1.1rem; font-weight: 700; }
        .logo-sub { font-size: 0.7rem; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.05em; }
        .btn {
            padding: 8px 18px; border-radius: 8px; font-size: 0.82rem; font-weight: 600;
            cursor: pointer; border: none; font-family: 'Sora', sans-serif;
            text-decoration: none; display: inline-flex; align-items: center;
            gap: 7px; transition: all 0.2s;
        }
        .btn-ghost {
            background: transparent; color: var(--text-mid);
            border: 1px solid var(--border);
        }
        .btn-ghost:hover { border-color: var(--accent); color: var(--accent); }
        .btn-primary {
            background: linear-gradient(135deg, var(--accent), #2563eb);
            color: white; box-shadow: 0 4px 15px rgba(59,130,246,0.3);
        }
        .btn-primary:hover { transform: translateY(-1px); }
        .btn-primary:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

        .main {
            max-width: 1100px;
            margin: 0 auto;
            padding: 2rem;
            display: grid;
            grid-template-columns: 1fr 360px;
            gap: 1.5rem;
            align-items: start;
        }

        /* === UPLOAD CARD === */
        .upload-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 14px;
            overflow: hidden;
        }
        .card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border);
        }
        .card-title {
            font-size: 1rem; font-weight: 700; margin-bottom: 3px;
        }
        .card-desc { font-size: 0.8rem; color: var(--text-dim); }

        /* === DROP ZONE === */
        .drop-zone {
            margin: 1.5rem;
            border: 2px dashed var(--border);
            border-radius: 12px;
            padding: 3.5rem 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
            background: rgba(255,255,255,0.01);
        }
        .drop-zone:hover, .drop-zone.drag-over {
            border-color: var(--accent);
            background: rgba(59,130,246,0.05);
        }
        .drop-zone.drag-over {
            transform: scale(1.01);
            box-shadow: 0 0 0 4px rgba(59,130,246,0.1), inset 0 0 30px rgba(59,130,246,0.05);
        }
        .drop-zone input[type="file"] {
            position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;
        }
        .drop-icon {
            font-size: 3.5rem;
            margin-bottom: 1.2rem;
            display: block;
            transition: transform 0.2s;
        }
        .drop-zone:hover .drop-icon, .drop-zone.drag-over .drop-icon {
            transform: scale(1.1) translateY(-4px);
        }
        .drop-title {
            font-size: 1.05rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .drop-subtitle {
            font-size: 0.82rem;
            color: var(--text-dim);
            margin-bottom: 1.2rem;
        }
        .drop-formats {
            display: flex;
            gap: 8px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .format-badge {
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
            font-family: 'JetBrains Mono', monospace;
            letter-spacing: 0.04em;
        }
        .fmt-xlsx { background: rgba(16,185,129,0.12); color: var(--accent3); border: 1px solid rgba(16,185,129,0.25); }
        .fmt-xls  { background: rgba(16,185,129,0.08); color: var(--accent3); border: 1px solid rgba(16,185,129,0.2); }
        .fmt-csv  { background: rgba(59,130,246,0.12); color: var(--accent); border: 1px solid rgba(59,130,246,0.25); }

        /* === FILE PREVIEW === */
        .file-preview {
            display: none;
            margin: 0 1.5rem;
            background: rgba(59,130,246,0.06);
            border: 1px solid rgba(59,130,246,0.2);
            border-radius: 10px;
            padding: 1rem 1.25rem;
            align-items: center;
            gap: 1rem;
            justify-content: space-between;
        }
        .file-preview.visible { display: flex; }
        .file-info { display: flex; align-items: center; gap: 10px; }
        .file-icon { font-size: 1.5rem; }
        .file-name { font-size: 0.85rem; font-weight: 600; }
        .file-size { font-size: 0.75rem; color: var(--text-dim); }
        .file-remove {
            background: rgba(244,63,94,0.12);
            border: 1px solid rgba(244,63,94,0.2);
            color: var(--red);
            border-radius: 6px;
            padding: 5px 10px;
            font-size: 0.75rem;
            cursor: pointer;
            font-family: 'Sora', sans-serif;
        }

        /* === FORM OPTIONS === */
        .form-options {
            padding: 1.25rem 1.5rem;
        }
        .option-label {
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--text-mid);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }
        .jenis-toggle {
            display: flex;
            gap: 8px;
        }
        .jenis-btn {
            flex: 1;
            padding: 9px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: transparent;
            color: var(--text-mid);
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s;
            font-family: 'Sora', sans-serif;
        }
        .jenis-btn.active-ekspor {
            background: rgba(16,185,129,0.12);
            border-color: var(--accent3);
            color: var(--accent3);
        }
        .jenis-btn.active-impor {
            background: rgba(244,63,94,0.12);
            border-color: var(--red);
            color: var(--red);
        }

        /* === PROGRESS === */
        .upload-progress {
            display: none;
            padding: 0 1.5rem;
        }
        .upload-progress.visible { display: block; }
        .progress-bar-wrap {
            background: rgba(255,255,255,0.06);
            border-radius: 100px;
            height: 8px;
            overflow: hidden;
            margin-bottom: 8px;
        }
        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, var(--accent), var(--accent2));
            border-radius: 100px;
            transition: width 0.3s ease;
            width: 0%;
        }
        .progress-bar.indeterminate {
            animation: indeterminate 1.5s ease-in-out infinite;
            width: 40%;
        }
        @keyframes indeterminate {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(350%); }
        }
        .progress-text { font-size: 0.78rem; color: var(--text-dim); text-align: center; }

        /* === RESULT === */
        .result-box {
            display: none;
            margin: 0 1.5rem 1.5rem;
            padding: 1rem 1.25rem;
            border-radius: 10px;
        }
        .result-box.visible { display: block; }
        .result-box.success {
            background: rgba(16,185,129,0.08);
            border: 1px solid rgba(16,185,129,0.25);
        }
        .result-box.error {
            background: rgba(244,63,94,0.08);
            border: 1px solid rgba(244,63,94,0.2);
        }
        .result-title {
            font-size: 0.85rem; font-weight: 700; margin-bottom: 4px;
        }
        .result-box.success .result-title { color: var(--accent3); }
        .result-box.error .result-title { color: var(--red); }
        .result-msg { font-size: 0.8rem; color: var(--text-mid); }

        /* === ACTION ROW === */
        .action-row {
            padding: 1rem 1.5rem 1.5rem;
            display: flex;
            gap: 10px;
        }
        .btn-upload {
            flex: 1;
            justify-content: center;
            padding: 11px;
            font-size: 0.88rem;
        }

        /* === SIDEBAR INFO === */
        .info-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
        }
        .info-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border);
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: var(--text-dim);
        }
        .info-body { padding: 1.25rem; }
        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 1rem;
        }
        .info-item:last-child { margin-bottom: 0; }
        .info-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: var(--accent);
            margin-top: 5px;
            flex-shrink: 0;
        }
        .info-text { font-size: 0.8rem; color: var(--text-mid); line-height: 1.5; }
        .info-text strong { color: var(--text); display: block; margin-bottom: 2px; }
        .cols-list {
            margin: 0.5rem 0 0;
            padding: 0.75rem;
            background: rgba(255,255,255,0.03);
            border-radius: 8px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.72rem;
            color: var(--accent2);
        }
        .cols-list span {
            display: block;
            padding: 2px 0;
            border-bottom: 1px solid rgba(255,255,255,0.03);
        }
        .cols-list span:last-child { border: none; }
        .template-link {
            display: block;
            margin-top: 1rem;
            text-align: center;
            padding: 8px;
            border-radius: 8px;
            border: 1px dashed var(--border);
            font-size: 0.78rem;
            color: var(--accent);
            text-decoration: none;
            transition: all 0.15s;
        }
        .template-link:hover { background: rgba(59,130,246,0.07); border-color: var(--accent); }

        /* === LOGS TABLE === */
        .logs-card {
            grid-column: 1 / -1;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
            margin-top: 0.5rem;
        }
        .logs-header {
            padding: 1.1rem 1.5rem;
            border-bottom: 1px solid var(--border);
            font-size: 0.88rem;
            font-weight: 700;
        }
        table { width: 100%; border-collapse: collapse; font-size: 0.82rem; }
        thead th {
            background: rgba(255,255,255,0.02);
            padding: 10px 14px;
            text-align: left;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: var(--text-dim);
            border-bottom: 1px solid var(--border);
        }
        tbody tr { border-bottom: 1px solid rgba(99,179,237,0.05); }
        tbody tr:hover { background: rgba(59,130,246,0.03); }
        tbody tr:last-child { border: none; }
        td { padding: 10px 14px; color: var(--text); vertical-align: middle; }
        .mono { font-family: 'JetBrains Mono', monospace; font-size: 0.78rem; color: var(--text-dim); }
        .badge {
            padding: 3px 9px; border-radius: 20px; font-size: 0.68rem; font-weight: 700;
        }
        .badge-done { background: rgba(16,185,129,0.12); color: var(--accent3); border: 1px solid rgba(16,185,129,0.25); }
        .badge-processing { background: rgba(245,158,11,0.12); color: var(--yellow); border: 1px solid rgba(245,158,11,0.25); }
        .badge-failed { background: rgba(244,63,94,0.1); color: var(--red); border: 1px solid rgba(244,63,94,0.2); }
        .del-btn {
            background: rgba(244,63,94,0.1); color: var(--red); border: 1px solid rgba(244,63,94,0.2);
            border-radius: 6px; padding: 4px 9px; font-size: 0.72rem; cursor: pointer;
            font-family: 'Sora', sans-serif;
        }

        @media (max-width: 880px) {
            .main { grid-template-columns: 1fr; }
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
                <div class="logo-sub">Upload Data Perdagangan</div>
            </div>
        </div>
        <a href="{{ route('trade.index') }}" class="btn btn-ghost">
            ← Kembali ke Portal
        </a>
    </div>
</header>

<div class="main">

    <!-- UPLOAD CARD -->
    <div class="upload-card">
        <div class="card-header">
            <div class="card-title">⬆ Upload File Data</div>
            <div class="card-desc">Tarik &amp; lepas file ke area di bawah, atau klik untuk memilih file</div>
        </div>

        <!-- DROP ZONE -->
        <div class="drop-zone" id="dropZone">
            <input type="file" id="fileInput" accept=".xlsx,.xls,.csv" />
            <span class="drop-icon">📂</span>
            <div class="drop-title">Tarik &amp; Lepas File Di Sini</div>
            <div class="drop-subtitle">atau klik untuk browse dari komputer Anda</div>
            <div class="drop-formats">
                <span class="format-badge fmt-xlsx">XLSX</span>
                <span class="format-badge fmt-xls">XLS</span>
                <span class="format-badge fmt-csv">CSV</span>
            </div>
        </div>

        <!-- FILE PREVIEW -->
        <div class="file-preview" id="filePreview">
            <div class="file-info">
                <span class="file-icon" id="fileIcon">📄</span>
                <div>
                    <div class="file-name" id="fileName">—</div>
                    <div class="file-size" id="fileSize">—</div>
                </div>
            </div>
            <button class="file-remove" id="removeFile">✕ Hapus</button>
        </div>

        <!-- OPTIONS -->
        <div class="form-options">
            <div class="option-label">Jenis Data</div>
            <div class="jenis-toggle">
                <button type="button" class="jenis-btn active-ekspor" data-jenis="ekspor" id="btnEkspor">📤 Ekspor</button>
                <button type="button" class="jenis-btn" data-jenis="impor" id="btnImpor">📥 Impor</button>
            </div>
        </div>

        <!-- PROGRESS -->
        <div class="upload-progress" id="uploadProgress">
            <div class="progress-bar-wrap">
                <div class="progress-bar indeterminate" id="progressBar"></div>
            </div>
            <div class="progress-text" id="progressText">Mengolah file...</div>
        </div>

        <!-- RESULT -->
        <div class="result-box" id="resultBox">
            <div class="result-title" id="resultTitle"></div>
            <div class="result-msg" id="resultMsg"></div>
        </div>

        <!-- ACTION -->
        <div class="action-row">
            <button class="btn btn-primary btn-upload" id="uploadBtn" disabled>
                ⬆ Upload & Import Data
            </button>
        </div>
    </div>

    <!-- INFO SIDEBAR -->
    <div>
        <div class="info-card">
            <div class="info-header">📋 Panduan Format</div>
            <div class="info-body">
                <div class="info-item">
                    <div class="info-dot"></div>
                    <div class="info-text">
                        <strong>Kolom yang dikenali otomatis:</strong>
                        <div class="cols-list">
                            <span>tahun / year</span>
                            <span>komoditas / commodity</span>
                            <span>hs_code / hs</span>
                            <span>negara_tujuan / country</span>
                            <span>berat_kg / weight / kg</span>
                            <span>nilai_usd / value / usd</span>
                            <span>pelabuhan / port</span>
                            <span>keterangan / notes</span>
                        </div>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-dot" style="background:var(--accent3)"></div>
                    <div class="info-text">
                        <strong>Baris pertama = Header</strong>
                        Baris pertama file harus berisi nama kolom.
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-dot" style="background:var(--yellow)"></div>
                    <div class="info-text">
                        <strong>Ukuran maks: 10 MB</strong>
                        Gunakan format angka tanpa titik pemisah ribuan.
                    </div>
                </div>
                <a href="{{ route('admin.upload.template') }}" class="template-link">
                    ⬇ Unduh Template CSV
                </a>
            </div>
        </div>
    </div>

    <!-- UPLOAD LOGS -->
    <div class="logs-card">
        <div class="logs-header">📜 Riwayat Upload</div>
        <table>
            <thead>
                <tr>
                    <th>File</th>
                    <th>Total Baris</th>
                    <th>Berhasil</th>
                    <th>Gagal</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" title="{{ $log->original_name }}">
                        {{ $log->original_name }}
                    </td>
                    <td class="mono">{{ number_format($log->total_rows) }}</td>
                    <td class="mono" style="color:var(--accent3)">{{ number_format($log->success_rows) }}</td>
                    <td class="mono" style="color:var(--red)">{{ $log->failed_rows > 0 ? number_format($log->failed_rows) : '—' }}</td>
                    <td>
                        <span class="badge badge-{{ $log->status }}">{{ strtoupper($log->status) }}</span>
                    </td>
                    <td class="mono">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.upload.destroy', $log->id) }}" onsubmit="return confirm('Hapus log ini?')">
                            @csrf @method('DELETE')
                            <button class="del-btn">🗑</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:3rem;color:var(--text-dim)">
                        Belum ada riwayat upload
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

<script>
(function () {
    const dropZone     = document.getElementById('dropZone');
    const fileInput    = document.getElementById('fileInput');
    const filePreview  = document.getElementById('filePreview');
    const fileIcon     = document.getElementById('fileIcon');
    const fileNameEl   = document.getElementById('fileName');
    const fileSizeEl   = document.getElementById('fileSize');
    const removeBtn    = document.getElementById('removeFile');
    const uploadBtn    = document.getElementById('uploadBtn');
    const progressWrap = document.getElementById('uploadProgress');
    const progressBar  = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const resultBox    = document.getElementById('resultBox');
    const resultTitle  = document.getElementById('resultTitle');
    const resultMsg    = document.getElementById('resultMsg');
    const btnEkspor    = document.getElementById('btnEkspor');
    const btnImpor     = document.getElementById('btnImpor');

    let selectedFile = null;
    let selectedJenis = 'ekspor';

    // Jenis toggle
    btnEkspor.addEventListener('click', () => {
        selectedJenis = 'ekspor';
        btnEkspor.className = 'jenis-btn active-ekspor';
        btnImpor.className = 'jenis-btn';
    });
    btnImpor.addEventListener('click', () => {
        selectedJenis = 'impor';
        btnImpor.className = 'jenis-btn active-impor';
        btnEkspor.className = 'jenis-btn';
    });

    // Drag & drop events
    ['dragenter','dragover'].forEach(evt => {
        dropZone.addEventListener(evt, e => {
            e.preventDefault();
            dropZone.classList.add('drag-over');
        });
    });
    ['dragleave','drop'].forEach(evt => {
        dropZone.addEventListener(evt, e => {
            e.preventDefault();
            dropZone.classList.remove('drag-over');
        });
    });
    dropZone.addEventListener('drop', e => {
        const files = e.dataTransfer.files;
        if (files.length) handleFile(files[0]);
    });
    fileInput.addEventListener('change', () => {
        if (fileInput.files.length) handleFile(fileInput.files[0]);
    });

    function handleFile(file) {
        const allowed = ['xlsx','xls','csv'];
        const ext = file.name.split('.').pop().toLowerCase();
        if (!allowed.includes(ext)) {
            showResult(false, 'Format Tidak Didukung', `File .${ext} tidak diterima. Gunakan XLSX, XLS, atau CSV.`);
            return;
        }
        if (file.size > 10 * 1024 * 1024) {
            showResult(false, 'File Terlalu Besar', 'Ukuran file maksimal 10 MB.');
            return;
        }
        selectedFile = file;
        const icons = { xlsx:'📗', xls:'📗', csv:'📄' };
        fileIcon.textContent = icons[ext] || '📄';
        fileNameEl.textContent = file.name;
        fileSizeEl.textContent = formatSize(file.size);
        filePreview.classList.add('visible');
        dropZone.style.display = 'none';
        uploadBtn.disabled = false;
        hideResult();
    }

    removeBtn.addEventListener('click', () => {
        selectedFile = null;
        fileInput.value = '';
        filePreview.classList.remove('visible');
        dropZone.style.display = '';
        uploadBtn.disabled = true;
        hideResult();
    });

    uploadBtn.addEventListener('click', async () => {
        if (!selectedFile) return;
        uploadBtn.disabled = true;
        progressWrap.classList.add('visible');
        progressText.textContent = 'Mengunggah & memproses file...';
        hideResult();

        const form = new FormData();
        form.append('file', selectedFile);
        form.append('jenis', selectedJenis);
        form.append('_token', document.querySelector('meta[name="csrf-token"]').content);

        try {
            const res = await fetch('{{ route("admin.upload.store") }}', {
                method: 'POST',
                body: form,
            });
            const data = await res.json();
            progressWrap.classList.remove('visible');

            if (data.success) {
                showResult(true, '✅ Berhasil', data.message);
                removeBtn.click();
                setTimeout(() => location.reload(), 2000);
            } else {
                showResult(false, '❌ Gagal', data.message);
                uploadBtn.disabled = false;
            }
        } catch (err) {
            progressWrap.classList.remove('visible');
            showResult(false, '❌ Error', 'Terjadi kesalahan jaringan. Silakan coba lagi.');
            uploadBtn.disabled = false;
        }
    });

    function showResult(ok, title, msg) {
        resultBox.className = 'result-box visible ' + (ok ? 'success' : 'error');
        resultTitle.textContent = title;
        resultMsg.textContent = msg;
    }
    function hideResult() {
        resultBox.classList.remove('visible');
    }
    function formatSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
    }
})();
</script>
</body>
</html>
