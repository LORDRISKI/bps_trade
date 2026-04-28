<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login — BPS Trade</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0a0e1a; --bg2: #0f1625; --bg3: #151d30;
            --border: rgba(99,179,237,0.12);
            --accent: #3b82f6; --accent2: #06b6d4; --accent3: #10b981;
            --red: #f43f5e;
            --text: #e2e8f0; --text-dim: #64748b; --text-mid: #94a3b8;
            --card: rgba(15,22,37,0.95);
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Sora', sans-serif;
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
            box-shadow: 0 24px 60px rgba(0,0,0,0.5);
        }

        /* LOGO */
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
        .logo-icon img {
            width: 100%; height: 100%;
            object-fit: contain;
        }
        .logo-name {
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--text);
        }
        .logo-sub {
            font-size: 0.75rem;
            color: var(--text-dim);
            margin-top: 3px;
        }

        /* BADGE */
        .badge-admin {
            display: inline-block;
            background: rgba(59,130,246,0.12);
            color: var(--accent);
            border: 1px solid rgba(59,130,246,0.25);
            font-size: 0.68rem;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }

        h2 {
            text-align: center;
            font-size: 1rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 1.75rem;
        }

        /* ALERTS */
        .alert {
            padding: 0.7rem 0.9rem;
            border-radius: 8px;
            font-size: 0.8rem;
            margin-bottom: 1rem;
        }
        .alert-error   { background: rgba(244,63,94,0.08); border: 1px solid rgba(244,63,94,0.25); color: var(--red); }
        .alert-success { background: rgba(16,185,129,0.08); border: 1px solid rgba(16,185,129,0.25); color: var(--accent3); }

        /* FIELDS */
        .field { margin-bottom: 1.1rem; }
        label {
            display: block;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--text-mid);
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 6px;
        }
        input[type=email], input[type=password] {
            width: 100%;
            padding: 0.6rem 0.9rem;
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 0.9rem;
            color: var(--text);
            font-family: 'Sora', sans-serif;
            outline: none;
            transition: border 0.2s, box-shadow 0.2s;
        }
        input[type=email]::placeholder, input[type=password]::placeholder {
            color: var(--text-dim);
        }
        input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
        }
        .error-text {
            font-size: 0.75rem;
            color: var(--red);
            margin-top: 5px;
        }

        /* REMEMBER */
        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.82rem;
            color: var(--text-dim);
            margin-bottom: 1.4rem;
        }
        .remember input[type=checkbox] {
            width: 15px; height: 15px;
            accent-color: var(--accent);
            cursor: pointer;
        }
        .remember label { margin-bottom: 0; text-transform: none; letter-spacing: 0; font-weight: 400; color: var(--text-dim); }

        /* BUTTON */
        .btn-submit {
            width: 100%;
            padding: 0.7rem;
            background: linear-gradient(135deg, var(--accent), #2563eb);
            color: white;
            font-size: 0.88rem;
            font-weight: 700;
            font-family: 'Sora', sans-serif;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(59,130,246,0.3);
            transition: transform 0.15s, box-shadow 0.15s;
        }
        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(59,130,246,0.4);
        }

        /* BACK LINK */
        .back-link {
            text-align: center;
            margin-top: 1.25rem;
            font-size: 0.8rem;
        }
        .back-link a {
            color: var(--text-dim);
            text-decoration: none;
            transition: color 0.15s;
        }
        .back-link a:hover { color: var(--accent); }

        /* DIVIDER */
        .divider {
            height: 1px;
            background: var(--border);
            margin: 1.5rem 0;
        }
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
            <span class="badge-admin">Admin Panel</span>
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
           
        </div>
    </div>
</body>
</html>