<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login — BPS Trade</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f1f5f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
            padding: 2.5rem 2rem;
            width: 100%;
            max-width: 400px;
        }
        .logo {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .logo span {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e40af;
        }
        .logo small {
            display: block;
            font-size: 0.8rem;
            color: #64748b;
            margin-top: 2px;
        }
        h2 {
            text-align: center;
            font-size: 1.1rem;
            color: #334155;
            margin-bottom: 1.5rem;
        }
        label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 4px;
        }
        input[type=email], input[type=password] {
            width: 100%;
            padding: 0.55rem 0.85rem;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 0.95rem;
            color: #1e293b;
            outline: none;
            transition: border 0.2s;
            box-sizing: border-box;
        }
        input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.15);
        }
        .field { margin-bottom: 1.1rem; }
        .error-text {
            font-size: 0.8rem;
            color: #dc2626;
            margin-top: 4px;
        }
        .alert {
            background: #fef2f2;
            border: 1px solid #fca5a5;
            color: #b91c1c;
            border-radius: 8px;
            padding: 0.65rem 0.9rem;
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }
        .alert-success {
            background: #f0fdf4;
            border-color: #86efac;
            color: #166534;
        }
        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            color: #475569;
            margin-bottom: 1.25rem;
        }
        .btn {
            width: 100%;
            padding: 0.65rem;
            background: #1e40af;
            color: white;
            font-size: 0.95rem;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn:hover { background: #1d4ed8; }
        .back-link {
            text-align: center;
            margin-top: 1.2rem;
            font-size: 0.85rem;
        }
        .back-link a {
            color: #3b82f6;
            text-decoration: none;
        }
        .back-link a:hover { text-decoration: underline; }
        .badge-admin {
            display: inline-block;
            background: #dbeafe;
            color: #1e40af;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 20px;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">
            <span>📊 BPS Trade</span>
            <small>Sistem Data Perdagangan</small>
        </div>

        <div style="text-align:center; margin-bottom:1rem;">
            <span class="badge-admin">Admin Panel</span>
        </div>

        <h2>Login Administrator</h2>

        {{-- Alert error dari session --}}
        @if (session('error'))
            <div class="alert">{{ session('error') }}</div>
        @endif

        {{-- Status session (misal setelah logout) --}}
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
                <label for="remember" style="margin-bottom:0; font-weight:400;">Ingat saya</label>
            </div>

            <button type="submit" class="btn">Masuk sebagai Admin</button>
        </form>

        <div class="back-link">
            <a href="{{ url('/trade') }}">← Kembali ke Halaman Data Publik</a>
        </div>
    </div>
</body>
</html>
