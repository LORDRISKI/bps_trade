<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login User</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

<style>
body{
    font-family:Inter;
    background:#eef1f5;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}
.card{
    width:420px;
    background:white;
    padding:35px;
    border-radius:18px;
    box-shadow:0 10px 25px rgba(0,0,0,0.1);
    text-align:center;
}
.logo{font-size:28px;font-weight:700;color:#1e40af;}
.subtitle{color:#64748b;margin-bottom:20px;}
.badge{
    background:#dbeafe;
    color:#1e40af;
    padding:6px 14px;
    border-radius:20px;
    display:inline-block;
    margin:15px 0;
    font-weight:600;
}
input{
    width:100%;padding:14px;margin-top:8px;margin-bottom:15px;
    border-radius:10px;border:1px solid #cbd5e1;font-size:15px;
}
button{
    width:100%;padding:14px;border:none;border-radius:10px;
    background:#1e40af;color:white;font-size:16px;font-weight:600;
}
.link{display:block;margin-top:18px;color:#3b82f6;text-decoration:none;}
</style>
</head>

<body>
<div class="card">
    <div class="logo">📊 BPS Trade</div>
    <div class="subtitle">Sistem Data Perdagangan</div>

    <div class="badge">USER PANEL</div>
    <h2>Login Pengguna</h2>

    <form method="POST" action="{{ route('login.user') }}">
        @csrf
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>

        <button type="submit">Masuk sebagai User</button>
    </form>

    <a href="/" class="link">← Kembali ke Halaman Data Publik</a>
</div>
</body>
</html>