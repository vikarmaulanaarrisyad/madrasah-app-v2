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
                <td>: {{ optional($jurnals->first()->first()->rombel->kelas)->nama ?? '-' }}
                    {{ optional($jurnals->first()->first()->rombel)->nama ?? '-' }} /
                    {{ optional($jurnals->first()->first()->tahun_pelajaran->semester)->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Tahun Pelajaran</strong></td>
                <td>: {{ optional($jurnals->first()->first()->tahun_pelajaran)->nama ?? '-' }}</td>
            </tr>
        </table>
        @foreach ($jurnals as $mataPelajaranId => $jurnalGroup)
            <table>
                <tr>
                    <th colspan="2">Mata Pelajaran</th>
                    <td>{{ $jurnalGroup->first()->mata_pelajaran->nama }}</td>
                </tr>
                <tr>
                    <th colspan="2">Tema</th>
                    <td>{{ $jurnalGroup->first()->tema }}</td>
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
                @foreach ($jurnalGroup as $jurnal)
                    <tr>
                        <td>{{ $jurnal->pembelajaran_ke }}</td>
                        <td>{!! nl2br(e($jurnal->tujuan_pembelajaran)) !!}</td>
                        <td>{!! nl2br(e($jurnal->materi)) !!}</td>
                        <td>{{ $jurnal->penilaian }}</td>
                        <td>{{ \Carbon\Carbon::parse($jurnal->tanggal)->translatedFormat('l, d F Y') }}</td>
                    </tr>
                @endforeach
            </table>

            <br>
        @endforeach


        <div class="signature">
            <table class="signature-table">
                <tr style="border:none;">
                    <td style="text-align: center; width: 50%;border:none;">
                        <p>Mengetahui,</p>
                        <p>Kepala {{ $sekolah->nama }}</p>
                        <br><br><br>
                        <p><strong>{{ $sekolah->kepala_madrasah->nama_lengkap ?? '--------------------------' }},
                                {{ $sekolah->kepala_madrasah->gelar_belakang }}</strong>
                        </p>
                    </td>
                    <td style="text-align: center; width: 50%;border:none;">
                        <p>Tarub, {{ tanggal_indonesia(now()->format('Y-m-d')) }}</p>
                        <p>Guru Kelas
                            {{ optional(optional(optional($jurnals->first()->first())->guru)->rombel)->kelas->nama ?? '' }}
                        </p>

                        <br><br><br>
                        <p><strong>{{ optional($jurnals->first()->first()->guru)->nama_lengkap ?? '-' }},
                                {{ optional($jurnals->first()->first()->guru)->gelar_belakang ?? '' }}</strong></p>
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
