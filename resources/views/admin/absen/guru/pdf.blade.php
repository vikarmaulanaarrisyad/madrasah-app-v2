<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kehadiran</title>
    <style>
        @page {
            size: 210mm 297mm;
            /* Ukuran kertas F4 */
            margin: 1cm;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 1px;
        }

        .kop {
            width: 100%;
            border-top: none;
            border-bottom: 5px solid black;
            text-align: center;
        }

        .kop img {
            width: 80px;
            height: auto;
        }

        .kop td {
            vertical-align: middle;
        }

        .kop .lembaga {
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .kop .alamat {
            font-size: 14px;
        }

        h2 {
            text-align: left;
            font-size: 18px;
            margin-bottom: 10px;
        }

        table.identitas {
            text-align: left;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 8px;
            text-align: center;
            font-size: 14px;
        }

        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        td:first-child,
        td:nth-child(2) {
            font-weight: bold;
        }

        .keterangan {
            font-weight: bold;
            text-align: left;
        }

        .wali-kelas {
            border: none;
            padding: 10px;
            margin-top: 20px;
            display: inline-block;
        }

        .wali-kelas-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .wali-kelas-table td {
            padding: 20px;
        }
    </style>
</head>

<body>
    <table class="kop">
        <tr>
            <td style="width: 10%; text-align: center; border:none;">
                <img src="{{ public_path('images/logo-madrasah.jpg') }}" alt="Logo Kiri" style="width:100px;">
            </td>

            <td style="width: 90%; text-align: center; border:none;">
                <div class="lembaga">
                    <span style="font-size: 20px; font-weight: bold;">YAYASAN ASSALAFIYAH AL MUNAWAROH</span><br>
                    <span style="font-size: 22px; font-weight: bold;">MADRASAH IBTIDAIYAH ASSALAFIYAH</span><br>
                    <span style="font-size: 14px; font-weight: bold;">STATUS: TERAKREDITASI B</span><br>
                    <span style="font-size: 14px;">Desa Kemanggungan, Kec. Tarub, Kab. Tegal</span>
                </div>
                <div class="alamat" style="margin-top: 5px; font-size: 13px;">
                    Alamat: Jl. Projosumarto II Gang Mawar 1, Kemanggungan, Tarub, Tegal 52184
                </div>
            </td>
        </tr>
    </table>

    <h2 style="text-align: center;">Data Kehadiran Guru</h2>

    <table>
        <tr>
            <th style="width: 13%;">TANGGAL</th>
            <th>HARI</th>
            <th>JAM MASUK</th>
            <th>JAM PULANG</th>
            <th>ABSEN MASUK</th>
            <th>ABSEN PULANG</th>
            <th>KETERANGAN</th>
        </tr>
        {{--  @dd($data);  --}}

        @foreach ($data as $guruNama => $presensiList)
            @foreach ($presensiList as $tglPresensi => $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($tglPresensi)->format('d-m-Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($tglPresensi)->translatedFormat('l') }}</td>

                    @if (!empty($item['is_holiday']) && $item['is_holiday'] == 1)
                        {{-- Jika libur, tampilkan "-" untuk semua kolom --}}
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td><b style="color: red;">{{ $item['description'] }}</b></td>
                    @else
                        {{-- Jika tidak libur, tampilkan data normal --}}
                        <td>
                            {{ $item['jam_kerja_masuk'] && $item['jam_kerja_masuk'] !== '-'
                                ? \Carbon\Carbon::parse($item['jam_kerja_masuk'])->format('H:i')
                                : '-' }}
                        </td>
                        <td>
                            {{ $item['jam_kerja_pulang'] && $item['jam_kerja_pulang'] !== '-'
                                ? \Carbon\Carbon::parse($item['jam_kerja_pulang'])->format('H:i')
                                : '-' }}
                        </td>
                        <td>{{ $item['waktu_masuk'] ? \Carbon\Carbon::parse($item['waktu_masuk'])->format('H:i') : '-' }}
                        </td>
                        <td>{{ $item['waktu_keluar'] ? \Carbon\Carbon::parse($item['waktu_keluar'])->format('H:i') : '-' }}
                        </td>
                        <td>{{ $item['status'] ?? '-' }}</td>
                    @endif
                </tr>
            @endforeach
        @endforeach

    </table>

    <table class="wali-kelas-table" style="width: 100%; border: none;">
        <tr style=" border: none;">
            <td colspan="2" style="text-align: center; padding-top: 30px;  border: none;">
                <p> </p>
                Kepala Madrasah
                <br><br><br><br>

                <br>
                <br>
                <b>{{ $sekolah->guru->gelar_depan ?? '' }}
                    {{ $sekolah->guru->nama_lengkap ?? '...........................' }}
                    {{ $sekolah->guru->gelar_belakang ?? '' }}</b>
            </td>
            <td style="text-align: center; width: 50%;  border: none;">
                <p>{{ $sekolah->desa ?? 'Kemanggungan' }}, {{ tanggal_indonesia(now()->format('Y-m-d')) }}</p>
                Guru
                <br><br><br><br>
                <br>
                <br>
                <b> ...........................</b>
            </td>
        </tr>
    </table>

</body>

</html>
