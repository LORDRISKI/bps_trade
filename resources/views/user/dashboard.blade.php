<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard User</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f6f9;
            margin: 0;
        }

        /* NAVBAR */
        .navbar {
            background: #2d4db7;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h2 {
            margin: 0;
        }

        /* CONTAINER */
        .container {
            padding: 30px;
        }

        .welcome {
            font-size: 18px;
            margin-bottom: 20px;
        }

        /* CARD */
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card h3 {
            margin: 0;
            color: #2d4db7;
        }

        .card p {
            font-size: 14px;
            color: #555;
        }

        .card a {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            color: white;
            background: #2d4db7;
            padding: 6px 12px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <div class="navbar">
        <h2>BPS Trade - User</h2>
    </div>

    <!-- CONTENT -->
    <div class="container">

        <div class="welcome">
            👋 Selamat datang di BPS Trade
        </div>

        <div class="cards">

            <div class="card">
                <h3>📊 Data Perdagangan</h3>
                <p>Lihat data impor & ekspor terbaru</p>
                <a href="/trade">Lihat Data</a>
            </div>

            <div class="card">
                <h3>📄 Export Data</h3>
                <p>Download laporan data perdagangan</p>
                <a href="/trade/export">Download</a>
            </div>

        </div>

    </div>

</body>
</html>