<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Presensi Harian Siswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }

        .info {
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
        }

        th {
            background-color: #f0f0f0;
        }

        .footer {
            margin-top: 20px;
            font-size: 12px;
        }

        .signature-table {
            width: 100%;
            margin-top: 30px;
            text-align: center;
            border: none;
        }

        .signature-table td {
            width: 50%;
            padding-top: 10px;
            vertical-align: top;
            border: none;
        }

        .page-footer {
            position: fixed;
            bottom: 10px;
            width: 100%;
            text-align: center;
            font-size: 10px;
        }

        .page-number {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="title">
        PRESENSI Guru<br>
        TAHUN PELAJARAN {{ $rombel->tahun_pelajaran->nama }}
    </div>
    <br><br>

    <table style="margin-top: 10px; width: 100%; border: none;">
        <tr style="border: none">
            <td style="text-align: left; border:none;"><strong>Kelas / Semester : </strong>{{ $rombel->kelas->nama }}
                {{ $rombel->nama }} / {{ $rombel->tahun_pelajaran->semester->nama }}
            </td>
            <td style="text-align: right; border:none;"><strong>Bulan:</strong> {{ $namaBulan }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">NIS</th>
                <th rowspan="2">NISN</th>
                <th rowspan="2">Nama</th>
                <th colspan="{{ $jumlahHari }}">Tanggal</th>
                <th colspan="4">Jumlah</th>
            </tr>
            <tr>
                @for ($i = 1; $i <= $jumlahHari; $i++)
                    <th>{{ $i }}</th>
                @endfor
                <th>H</th>
                <th>S</th>
                <th>I</th>
                <th>A</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalSakit = $totalIzin = $totalAlpha = 0;
            @endphp

            @foreach ($dataPresensi as $nisn => $siswa)
                @php
                    $hadir = $izin = $sakit = $alpha = 0;
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $siswa['nis'] }}</td>
                    <td>{{ $siswa['nisn'] }}</td>
                    <td>{{ $siswa['nama'] }}</td>

                    @for ($i = 1; $i <= $jumlahHari; $i++)
                        @php
                            $status =
                                $siswa['kehadiran'][Carbon\Carbon::create(null, $bulan, $i)->toDateString()] ?? '';
                            // Hitung jumlah hadir, sakit, izin, alpa
                            if ($status === 'Hadir' || $status === 'H') {
                                $hadir++;
                                $status = 'H';
                            } elseif ($status === 'Izin' || $status === 'I') {
                                $izin++;
                                $status = 'I';
                            } elseif ($status === 'Sakit' || $status === 'S') {
                                $sakit++;
                                $status = 'S';
                            } elseif ($status === 'Alpa' || $status === 'A') {
                                $alpha++;
                                $status = 'A';
                            }
                        @endphp
                        <td>{{ $status }}</td>
                    @endfor

                    <!-- Tampilkan total jumlah hadir, sakit, izin, dan alpa -->
                    <td>{{ $hadir }}</td>
                    <td>{{ $sakit }}</td>
                    <td>{{ $izin }}</td>
                    <td>{{ $alpha }}</td>
                </tr>

                <!-- Tambahkan ke total untuk perhitungan persentase -->
                @php
                    $totalSakit += $sakit;
                    $totalIzin += $izin;
                    $totalAlpha += $alpha;
                @endphp
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>
            (S) Sakit =
            <strong>{{ number_format(($totalSakit / ($jumlahHari * count($dataPresensi))) * 100, 2) }}%</strong> |
            (I) Izin =
            <strong>{{ number_format(($totalIzin / ($jumlahHari * count($dataPresensi))) * 100, 2) }}%</strong> |
            (A) Alpha =
            <strong>{{ number_format(($totalAlpha / ($jumlahHari * count($dataPresensi))) * 100, 2) }}%</strong>
        </p>
    </div>

    <table class="signature-table">
        <tr>
            <td>
                <p>Mengetahui,</p>
                <p>Kepala {{ $sekolah->nama }}</p>
                <br><br><br><br>
                <p><strong>
                        @if ($sekolah->guru)
                            {{ $sekolah->guru->gelar_depan ? $sekolah->guru->gelar_depan . ' ' : '' }}
                            {{ $sekolah->guru->nama_lengkap }}
                            @if ($sekolah->guru->gelar_belakang)
                                , {{ $sekolah->guru->gelar_belakang }}
                            @endif
                        @endif
                    </strong></p>

            </td>
            <td>
                <p>Kemanggungan, </p>
                <p>Guru </p>
                <br><br><br><br>
                <p><strong>
                        @if ($rombel->walikelas && $rombel->walikelas->gelar_depan)
                            {{ $rombel->walikelas->gelar_depan }}
                        @endif
                        {{ $rombel->walikelas ? $rombel->walikelas->nama_lengkap : '' }}
                        @if ($rombel->walikelas && $rombel->walikelas->gelar_belakang)
                            , {{ $rombel->walikelas->gelar_belakang }}
                        @endif
                    </strong></p>

            </td>
        </tr>
    </table>
</body>

</html>
