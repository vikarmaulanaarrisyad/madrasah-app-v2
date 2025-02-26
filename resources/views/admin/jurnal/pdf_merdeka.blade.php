<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jurnal Harian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            width: 100%;
            margin: 5px auto;
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
            background-color: #0074D9;
            color: white;
        }

        .sub-header {
            background-color: #BCE0FD;
            font-weight: bold;
        }

        .title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .info-table td {
            border: none;
            padding: 5px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 12px;
            margin-top: 20px;
            border-top: 1px solid black;
            padding-top: 5px;
        }

        .page-number:after {
            content: counter(page);
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="title">JURNAL HARIAN <br>PELAKSANAAN PEMBELAJARAN</div>

        <table class="info-table" style="margin-top: 20px">
            <tr>
                <td style="width: 20%;"><strong>Institusi</strong></td>
                <td>: {{ $sekolah->nama }}</td>
            </tr>
            <tr>
                <td><strong>Kelas / Semester</strong></td>
                <td>: {{ optional($jurnals->first()->rombel->kelas)->nama ?? '-' }}
                    {{ optional($jurnals->first()->rombel)->nama ?? '-' }} /
                    {{ optional($jurnals->first()->tahun_pelajaran->semester)->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Tahun Pelajaran</strong></td>
                <td>: {{ optional($jurnals->first()->tahun_pelajaran)->nama ?? '-' }}</td>
            </tr>
        </table>

        @foreach ($jurnals as $jurnal)
            <table>
                <tr>
                    <th colspan="2">Mata Pelajaran</th>
                    <td>{{ $jurnal->mata_pelajaran->nama }}</td>
                </tr>
                <tr>
                    <th colspan="2">Tema</th>
                    <td>{{ $jurnal->tema }}</td>
                </tr>
            </table>

            <table>
                <tr>
                    <th>Pblj</th>
                    <th>Tujuan Pembelajaran</th>
                    <th>Materi</th>
                    <th>Penilaian</th>
                    <th>Hari/Tanggal</th>
                </tr>
                <tr>
                    <td>{{ $jurnal->pembelajaran_ke }}</td>
                    <td>{!! nl2br(e($jurnal->tujuan_pembelajaran)) !!}</td>
                    <td>{!! nl2br(e($jurnal->materi)) !!}</td>
                    <td>{{ $jurnal->penilaian }}</td>
                    <td>{{ \Carbon\Carbon::parse($jurnal->tanggal)->translatedFormat('l, d F Y') }}</td>
                </tr>
            </table>

            {{--  <table>
                <tr>
                    <th>Metode Pembelajaran</th>
                    <th>Evaluasi</th>
                    <th>Refleksi</th>
                    <th>Tugas</th>
                </tr>
                <tr>
                    <td>{{ $jurnal->metode_pembelajaran }}</td>
                    <td>{{ $jurnal->evaluasi }}</td>
                    <td>{{ $jurnal->refleksi }}</td>
                    <td>{{ $jurnal->tugas }}</td>
                </tr>
            </table>  --}}

            <br>
        @endforeach

        <div class="signature">
            <table class="signature-table">
                <tr style="border:none;">
                    <td style="text-align: center; width: 50%;border:none;">
                        <p>Mengetahui,</p>
                        <p>Kepala {{ $sekolah->nama }}</p>
                        <br><br><br>
                        <p><strong>{{ $sekolah->guru->nama_lengkap ?? '--------------------------' }}</strong></p>
                    </td>
                    <td style="text-align: center; width: 50%;border:none;">
                        <p>Tarub, {{ tanggal_indonesia(now()->format('Y-m-d')) }}</p>
                        <p>Guru Kelas V</p>
                        <br><br><br>
                        <p><strong>{{ optional($jurnals->first()->guru)->nama_lengkap ?? '-' }}</strong></p>
                    </td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p>Halaman <span class="page-number"></span> | {{ $sekolah->nama }} | Tgl di cetak
                {{ now()->format('Y-m-d') }}</p>
        </div>

    </div>

</body>

</html>
