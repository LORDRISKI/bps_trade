<!DOCTYPE html>
<html>
<head>
    <title>Dashboard User</title>
</head>
<body>

<h1>Dashboard User</h1>

<p>Selamat datang, <b>{{ auth()->user()->name }}</b></p>

<hr>

<h3>Menu:</h3>

<ul>
    <li><a href="/trade">📊 Lihat Data Perdagangan</a></li>
    <li><a href="/profile">👤 Profile</a></li>
</ul>

<br>

<form method="POST" action="/logout">
    @csrf
    <button type="submit">Logout</button>
</form>

</body>
</html>