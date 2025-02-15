<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jurnal Harian</title>
    <style>
        @page {
            margin: 10px 40px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            display: table-header-group;
            /* Memastikan thead muncul di setiap halaman */
        }

        tbody {
            display: table-row-group;
        }

        th,
        td {
            border: 1px solid black;
            padding: 5px;
            text-align: left;
            font-size: 12px;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        .container {
            width: 100%;
            margin: auto;
            text-align: center;
        }

        h2 {
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            text-align: center;
        }

        .center {
            text-align: center;
        }

        .footer {
            margin-top: 20px;
            width: 100%;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }

        .signature-table td {
            vertical-align: top;
            padding: 20px;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>JURNAL HARIAN</h2>
        <table style="width: 25%; border-collapse: collapse; margin-bottom: 10px;">
            <tr style="border:none;">
                <td style="border:none;">Kelas / Semester</td>
                <td style="border:none;">:</td>
                <td style="border:none;">{{ optional($jurnals->first()->rombel->kelas)->nama ?? '-' }}
                    {{ optional($jurnals->first()->rombel)->nama ?? '-' }} /
                    {{ optional($jurnals->first()->tahun_pelajaran->semester)->nama ?? '-' }}</td>
            </tr>
            <tr style="border:none;">
                <td style="border:none;">Tahun Pelajaran</td>
                <td style="border:none;">:</td>
                <td style="border:none;">{{ optional($jurnals->first()->tahun_pelajaran)->nama ?? '-' }}</td>
            </tr>
        </table>


        <table>
            <thead>
                <tr>
                    <th style="width: 8%;">Hari/Tgl</th>
                    <th>Mata Pelajaran</th>
                    <th>Tema/Sub Tema/Bab</th>
                    <th>PB Ke</th>
                    <th>Kompetensi Dasar</th>
                    <th>Materi Pokok</th>
                    <th>Kegiatan Pembelajaran</th>
                    <th>Penilaian Pembelajaran</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($jurnals as $jurnal)
                    <tr>
                        <td>{{ $jurnal->tanggal }}</td>
                        <td>{{ $jurnal->mata_pelajaran->nama }}</td>
                        <td>{{ $jurnal->tema }}</td>
                        <td>{{ $jurnal->pembelajaran_ke }}</td>
                        <td>{{ $jurnal->tujuan_pembelajaran }}</td>
                        <td>{{ $jurnal->materi }}</td>
                        <td>{{ $jurnal->metode_pembelajaran }}</td>
                        <td>{{ $jurnal->penilaian }}</td>
                        {{--  <td>{{ $jurnal->evaluasi }}</td>
                        <td>{{ $jurnal->refleksi }}</td>
                        <td>{{ $jurnal->tugas }}</td>  --}}
                    </tr>
                @endforeach

            </tbody>
        </table>

        <div class="footer">
            <table class="signature-table">
                <tr style="border:none;">
                    <td style="text-align: center; width: 50%;border:none;">
                        <p>Mengetahui,</p>
                        <p>Kepala {{ $sekolah->nama }}</p>
                        <br><br><br><br>
                        <p><strong>{{ $sekolah->guru->nama_lengkap ?? '--------------------------' }}</strong></p>
                    </td>
                    <td style="text-align: center; width: 50%;border:none;">
                        <p>Tarub, {{ tanggal_indonesia(now()->format('Y-m-d')) }}</p>
                        <p>Guru Kelas V</p>
                        <br><br><br><br>
                        <p><strong>{{ optional($jurnals->first()->guru)->nama_lengkap ?? '-' }}</strong></p>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>
