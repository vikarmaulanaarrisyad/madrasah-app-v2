<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Mode</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            text-align: center;
            background: url('http://yes.sgp1.digitaloceanspaces.com/diginews/uploads/2021/06/1-Website-maintenance-768x576.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            position: relative;
        }

        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            /* Overlay gelap */
            z-index: 0;
        }

        .container {
            position: relative;
            background: rgba(255, 255, 255, 0.37);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            text-align: center;
            z-index: 1;
        }

        h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }

        p {
            font-size: 18px;
            margin-bottom: 20px;
        }

        .loader {
            border: 6px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top: 6px solid #f39c12;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>🚧 Website Sedang dalam Perbaikan 🚧</h1>
        <p>Kami sedang melakukan beberapa peningkatan untuk memberikan pengalaman yang lebih baik.</p>
        <div class="loader"></div>
        <p>Silakan kembali lagi nanti.</p>
    </div>
</body>

</html>
