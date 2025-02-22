<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Induk Siswa</title>
    <style>
        @page {
            size: 21cm 33cm;
            /* Ukuran F4 */
            margin: 1cm 1cm 1cm 1cm;
            /* Atas 1cm, Kanan 1.5cm, Bawah 1cm, Kiri 1cm */
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            padding: 1cm;
        }

        .title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .indent-title {
            padding-left: 25px;
            /* Sesuaikan nilai ini */
        }

        .indent {
            padding-left: 45px;
            /* Sesuaikan nilai ini */
        }

        .indent-sub {
            padding-left: 60px;
            /* Sesuaikan nilai ini */
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 5px;
            vertical-align: top;
        }

        td:first-child {
            width: 250px;
            /* Sesuaikan dengan kebutuhan */
            white-space: nowrap;
        }

        th,
        td {
            padding: 5px;
            text-align: left;
            vertical-align: top;
        }

        .section-title {
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .photo-box {
            width: 80px;
            height: 100px;
            border: 1px solid black;
            text-align: center;
            vertical-align: middle;
            font-size: 10px;
            line-height: 100px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>

    <div class="container">
        @foreach ($siswas as $siswa)
            <div class="title">II. LEMBAR BUKU INDUK SISWA</div>
            {{--  <td rowspan="5" class="photo-box">Pas Foto 3x4</td>  --}}
            <table width="100%">
                <tr>
                    <td><strong>Nomor Induk Siswa</strong></td>
                    <td>: {{ $siswa->nis ?? '-' }}</td>
                    <td><strong>NIK</strong></td>
                    <td>: {{ $siswa->nik ?? '-' }}</td>
                </tr>
                <tr>
                    <td><strong>Nomor Induk Siswa Nasional</strong></td>
                    <td>: {{ $siswa->nisn ?? '-' }}</td>
                    <td><strong>No. KK</strong></td>
                    <td>: {{ $siswa->no_kk ?? '-' }}</td>
                </tr>
                <tr>

            </table>

            <div class="section-title">A. Keterangan Siswa</div>
            <table>
                <tr>
                    <td class="indent-title">1. Nama Siswa</td>
                </tr>
                <tr>
                    <td class="indent">Nama Lengkap</td>
                    <td>: {{ $siswa->nama_lengkap ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="indent">Nama Panggilan</td>
                    <td>: {{ $siswa->nama_panggilan ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="indent-title">2. Jenis Kelamin</td>
                    <td>: {{ $siswa->jenis_kelamin->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="indent-title">3. Kelahiran</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="indent">a. Tanggal</td>
                    <td>: {{ tanggal_indonesia($siswa->tgl_lahir) ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="indent">b. Tempat</td>
                    <td>: {{ $siswa->tempat_lahir ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="indent-title">4. Agama</td>
                    <td>: {{ $siswa->agama->nama ?? '-' }}</td>
                    {{--  <td rowspan="4" class="photo-box">Pas Foto 3x4</td>  --}}
                </tr>
                <tr>
                    <td class="indent-title">5. Kewarganegaraan</td>
                    <td>: {{ $siswa->kewarganegaraan->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="indent-title">6. Jumlah Saudara</td>
                    <td>: {{ $siswa->jumlah_saudara ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="indent-title">7. Anak Ke</td>
                    <td>: {{ $siswa->anakke ?? '1' }}</td>
                </tr>
                <tr>
                    <td class="indent-title">8. Alamat dan No. Telepon</td>
                    <td>: {{ $siswa->alamat ?? '-' }} / {{ $siswa->telepon ?? '-' }}</td>
                </tr>
            </table>

            <div class="section-title">B. Keterangan Orang Tua / Wali Siswa</div>
            <table>
                <tr>
                    <td class="indent-title">9. Nama Orang Tua Kandung</td>
                </tr>
                <tr>
                    <td class="indent">a. Ayah</td>
                    <td>: {{ $siswa->orangtua->nama_lengkap_ayah ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="indent">b. Ibu</td>
                    <td>: {{ $siswa->orangtua->nama_lengkap_ibu ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="indent-title">10. Pendidikan Tertinggi</td>
                </tr>
                <tr>
                    <td class="indent">a. Ayah</td>
                    <td>: {{ $siswa->pendidikan_ayah->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="indent">b. Ibu</td>
                    <td>: {{ $siswa->pendidikan_ibu->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="indent-title">11. Pekerjaan</td>
                </tr>
                <tr>
                    <td class="indent">a. Ayah</td>
                    <td>: {{ $siswa->pekerjaan_ayah->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="indent">b. Ibu</td>
                    <td>: {{ $siswa->pekerjaan_ibu->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <td>12. Wali Murid</td>
                </tr>
                <tr>
                    <td class="indent">a. Nama</td>
                    <td>: {{ $siswa->orangtua->nama_walimurid ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="indent">b. Hubungan Keluarga</td>
                    <td>: {{ $siswa->pekerjaan_ibu->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="indent">c. Pendidikan Terakhir</td>
                    <td>: {{ $siswa->pekerjaan_ibu->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="indent">c. Pekerjaan</td>
                    <td>: {{ $siswa->pekerjaan_walimurid->nama ?? '-' }}</td>
                </tr>
            </table>

            <div class="section-title">C. Perkembangan Siswa</div>
            <table>
                <tr>
                    <td class="indent-title">16. Pendidikan Sebelumnya</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="indent">a. Masuk menjadi siswa baru tingkat I</td>
                </tr>
                <tr>
                    <td class="indent-sub">1. Asal Sekolah/Madrasah </td>
                    <td>:</td>
                </tr>
                <tr>
                    <td class="indent-sub">2. Nama Taman Kanak-kanak </td>
                    <td>:</td>
                </tr>
                <tr>
                    <td class="indent-sub">3. Tanggal dan Nomor STTB </td>
                    <td>:</td>
                </tr>
                <tr>
                    <td class="indent">b. Pindah dari sekolah lain/Madrasah</td>
                </tr>
                <tr>
                    <td class="indent-sub">1. Asal Sekolah/Madrasah </td>
                    <td>:</td>
                </tr>
                <tr>
                    <td class="indent-sub">2. Dari Tingkat</td>
                    <td>:</td>
                </tr>
                <tr>
                    <td class="indent-sub">3. Diterima Tanggal</td>
                    <td>:</td>
                </tr>

            </table>

            @if (!$loop->last)
                <div class="page-break"></div>
            @endif
        @endforeach
    </div>

</body>

</html>
