<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login — BPS Trade</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f5f4f0;
            --bg2: #ffffff;
            --bg3: #eeece8;
            --border: #e0ddd8;
            --border-dark: #c8c4be;
            --accent: #1e3a5f;
            --accent2: #2563eb;
            --red: #e53e3e;
            --text: #1a1a1a;
            --text-dim: #9b9890;
            --text-mid: #6b6863;
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 2.5rem 2rem;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.08);
        }

        .logo {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .logo-icon {
            width: 72px; height: 72px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.75rem;
        }
        .logo-icon img { width:100%; height:100%; object-fit:contain; }
        .logo-name {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--text);
        }
        .logo-sub {
            font-size: 0.75rem;
            color: var(--text-dim);
            margin-top: 3px;
        }

        .badge-admin {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: transparent;
            color: var(--text-mid);
            border: 1px solid var(--border-dark);
            font-size: 0.68rem;
            font-weight: 500;
            padding: 4px 12px;
            border-radius: 999px;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }
        .badge-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: #22c55e;
            display: inline-block;
        }

        h2 {
            text-align: center;
            font-size: 1rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 1.75rem;
        }

        .alert {
            padding: 0.7rem 0.9rem;
            border-radius: 8px;
            font-size: 0.8rem;
            margin-bottom: 1rem;
        }
        .alert-error   { background: #fee2e2; border: 1px solid #fecaca; color: #991b1b; }
        .alert-success { background: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; }

        .field { margin-bottom: 1.1rem; }
        label {
            display: block;
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--text-mid);
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 6px;
        }
        input[type=email], input[type=password] {
            width: 100%;
            padding: 0.65rem 0.9rem;
            background: var(--bg3);
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 0.9rem;
            color: var(--text);
            font-family: 'Inter', sans-serif;
            outline: none;
            transition: border 0.2s, background 0.2s;
        }
        input[type=email]::placeholder, input[type=password]::placeholder {
            color: var(--text-dim);
        }
        input:focus {
            border-color: var(--accent2);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.08);
        }
        .error-text {
            font-size: 0.75rem;
            color: var(--red);
            margin-top: 5px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.82rem;
            color: var(--text-mid);
            margin-bottom: 1.4rem;
        }
        .remember input[type=checkbox] {
            width: 15px; height: 15px;
            accent-color: var(--accent);
            cursor: pointer;
        }
        .remember label { margin-bottom:0; text-transform:none; letter-spacing:0; font-weight:400; color:var(--text-mid); }

        .btn-submit {
            width: 100%;
            padding: 0.75rem;
            background: var(--accent);
            color: white;
            font-size: 0.88rem;
            font-weight: 500;
            font-family: 'Inter', sans-serif;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-submit:hover { background: #16304f; }

        .divider {
            height: 1px;
            background: var(--border);
            margin: 1.5rem 0;
        }

        .back-link {
            text-align: center;
            font-size: 0.8rem;
        }
        .back-link a {
            color: var(--text-dim);
            text-decoration: none;
            transition: color 0.15s;
        }
        .back-link a:hover { color: var(--text); }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">
            <div class="logo-icon"><img src="/images/logo-bps.png" alt="Logo BPS"></div>
            <div class="logo-name">BPS Provinsi Jambi</div>
            <div class="logo-sub">Sistem Data Perdagangan</div>
        </div>

        <div style="text-align:center; margin-bottom:1rem;">
            <span class="badge-admin"><span class="badge-dot"></span> Admin Panel</span>
        </div>

        <h2>Login Admin</h2>

        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf

            <div class="field">
                <label for="email">Email</label>
                <input id="email" type="email" name="email"
                       value="{{ old('email') }}"
                       placeholder="admin@example.com"
                       autocomplete="username" required autofocus>
                @error('email')
                    <p class="error-text">{{ $message }}</p>
                @enderror
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input id="password" type="password" name="password"
                       placeholder="••••••••"
                       autocomplete="current-password" required>
                @error('password')
                    <p class="error-text">{{ $message }}</p>
                @enderror
            </div>

            <div class="remember">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Ingat saya</label>
            </div>

            <button type="submit" class="btn-submit">Masuk sebagai Admin</button>
        </form>

        <div class="divider"></div>

        <div class="back-link">
            <a href="{{ route('trade.index') }}">← Kembali ke Portal Data</a>
        </div>
    </div>
</body>
</html>