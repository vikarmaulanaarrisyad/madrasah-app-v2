<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Laporan Rincian Harian</title>
    <style>
        th {
            background-color: yellow;
        }

        .highlight {
            background-color: yellow;
        }

        .total-row {
            font-weight: bold;
            background-color: #f8f9fa;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="text-center my-4">LAPORAN RINCIAAN HARIAN</h1>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nama Shift</th>
                    <th>Jam Masuk</th>
                    <th>Scan Masuk</th>
                    <th>Telat (Menit)</th>
                    <th>Jam Keluar</th>
                    <th>Scan Keluar</th>
                    <th>Durasi</th>
                    <th>Lembur Awal</th>
                    <th>Lembur Akhir</th>
                    <th>Lembur Akhir 2</th>
                    <th>Shift Lembur</th>
                    <th>Istrihat</th>
                    <th>Istrihat Lebih</th>
                    <th>Istrihat Lebih 2</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Wednesday 01/01/2025</td>
                    <td>Libur</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>00:00</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="highlight">Libur</td>
                </tr>
                <tr>
                    <td>Thursday 02/01/2025</td>
                    <td>Senin - kamis (NON PNS)</td>
                    <td>07:00</td>
                    <td>06:45</td>
                    <td>15</td>
                    <td>14:30</td>
                    <td>14:35</td>
                    <td>06:00</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <!-- Tambahkan baris lain sesuai kebutuhan -->
                <tr class="total-row">
                    <td colspan="15" class="text-right">Total:</td>
                    <td>138:30</td>
                </tr>
            </tbody>
        </table>
        <div class="text-center">
            <p>Halaman: 1 | dari: | Tgl. Cetak: 31/01/2025 22:29:34 | Oleh: admin</p>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
