@extends('layouts.app')

@section('title', 'Cetak Presensi Siswa')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Cetak Presensi Siswa</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Filter Data</h5>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <!-- Dropdown Filter Kelas -->
                        <div class="col-md-3">
                            <label class="form-label">Filter Rombel</label>
                            <select name="rombel" id="filterRombel" class="form-control">
                                <option value="">Semua Kelas</option>
                                @foreach ($rombels as $item)
                                    <option value="{{ $item->id }}">{{ $item->kelas->nama ?? '' }} {{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Dropdown Filter Bulan -->
                        <div class="col-md-3">
                            <label class="form-label">Filter Bulan</label>
                            <select id="filterMonth" class="form-control" disabled>
                                <option value="">-- Pilih Bulan --</option>
                                <option value="01">Januari</option>
                                <option value="02">Februari</option>
                                <option value="03">Maret</option>
                                <option value="04">April</option>
                                <option value="05">Mei</option>
                                <option value="06">Juni</option>
                                <option value="07">Juli</option>
                                <option value="08">Agustus</option>
                                <option value="09">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="col-md-6 d-flex align-items-end">
                            <button id="btnTampilkan" class="btn btn-primary" disabled>Tampilkan</button>
                            <button id="btnReset" class="btn btn-warning ml-2 mr-2">Reset</button>
                            <button id="btnDownloadPdf" class="btn btn-danger" disabled>Download PDF</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kontainer untuk Tabel Presensi -->
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <div id="presensiTableContainer" class="mt-4">
                </div>
            </x-card>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#filterRombel').change(function() {
                if ($(this).val()) {
                    $('#filterMonth, #btnTampilkan').prop('disabled', false);
                } else {
                    $('#filterMonth, #btnTampilkan, #btnDownloadPdf').prop('disabled', true).val('');
                }
            });

            // Reset Filter
            $('#btnReset').click(function() {
                $('#filterRombel, #filterMonth').val('');
                $('#filterMonth, #btnTampilkan, #btnDownloadPdf').prop('disabled', true);
                $('#presensiTableContainer').html(''); // Hapus data tampilan
            });

            // Tampilkan Data (Contoh AJAX)
            $('#btnTampilkan').click(function() {
                let rombel = $('#filterRombel').val();
                let bulan = $('#filterMonth').val();
                if (rombel) {
                    Swal.fire({
                        title: 'Memuat Data...',
                        text: 'Silakan tunggu sebentar',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: "{{ route('presensi.siswa.filter') }}",
                        type: "GET",
                        data: {
                            bulan: bulan,
                            rombel: rombel
                        },
                        success: function(response) {
                            Swal.close();

                            if (response.data && Object.keys(response.data).length > 0) {
                                renderTable(response.data, response.namaBulan, response.count);
                                $('#btnDownloadPdf').prop('disabled', false);
                            } else {
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Tidak Ada Data!',
                                    text: 'Tidak ditemukan data presensi untuk bulan ini.',
                                });
                                $('#presensiTableContainer').html("");
                            }
                        },
                        error: function() {
                            Swal.close();
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan!',
                                text: 'Gagal mengambil data, coba lagi.',
                            });
                        }
                    });
                }
            });

            // Download PDF (Tambahkan aksi sesuai kebutuhan)
            $('#btnDownloadPdf').click(function() {
                let rombel = $('#filterRombel').val();
                let bulan = $('#filterMonth').val();
                if (rombel) {
                    window.location.href = "{{ route('presensi.siswa.download') }}?bulan=" + bulan +
                        "&rombel=" + rombel;
                }

            });
        });

        const bulanAngka = {
            "Januari": "01",
            "Februari": "02",
            "Maret": "03",
            "April": "04",
            "Mei": "05",
            "Juni": "06",
            "Juli": "07",
            "Agustus": "08",
            "September": "09",
            "Oktober": "10",
            "November": "11",
            "Desember": "12"
        };

        function renderTable(data, namaBulan, jumlahHari) {
            let tableHtml = `
    <div class="table-responsive">
        <h5 class="text-center">Presensi Bulan ${namaBulan}</h5>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Nama Siswa</th>`;

            // Header tabel untuk setiap hari dalam bulan
            for (let i = 1; i <= jumlahHari; i++) {
                let tanggal = `${(new Date()).getFullYear()}-${bulanAngka[namaBulan]}-${String(i).padStart(2, '0')}`;
                let currentDate = new Date(tanggal);
                let dayOfWeek = currentDate.getDay(); // 0 = Minggu, 1 = Senin, dst.

                // Jika hari Minggu, beri tanda khusus
                if (dayOfWeek === 0) {
                    tableHtml += `<th class="">${i}</th>`;
                } else {
                    tableHtml += `<th>${i}</th>`;
                }
            }

            // Tambahkan header untuk rekap (Hadir, Izin, Sakit, Alpha)
            tableHtml += `<th>H</th><th>I</th><th>S</th><th>A</th></tr></thead><tbody>`;

            // Mengambil daftar hari libur secara AJAX sebelum mengisi tabel
            $.ajax({
                url: "{{ route('presensi.siswa.cekHariLibur') }}", // Sesuaikan dengan rute backend
                type: "GET",
                dataType: "json",
                success: function(hariLiburList) {
                    // Pastikan format tanggal hari libur sesuai
                    let hariLiburArr = hariLiburList.map(libur => libur.tanggal);

                    // Mengisi tabel data siswa
                    for (let namaSiswa in data) {
                        let hadir = 0,
                            izin = 0,
                            sakit = 0,
                            alpha = 0;

                        tableHtml += `<tr><td>${namaSiswa}</td>`;

                        // Loop setiap hari dalam bulan
                        for (let i = 1; i <= jumlahHari; i++) {
                            let tanggal =
                                `${(new Date()).getFullYear()}-${bulanAngka[namaBulan]}-${String(i).padStart(2, '0')}`;
                            let currentDate = new Date(tanggal);
                            let dayOfWeek = currentDate.getDay();

                            // Cek apakah hari Minggu atau Hari Libur
                            if (dayOfWeek === 0 || hariLiburArr.includes(tanggal)) {
                                tableHtml += `<td class="bg-warning text-dark">-</td>`;
                            } else {
                                let status = data[namaSiswa][tanggal] || '-';

                                // Hitung total H, I, S, A hanya jika statusnya bukan '-'
                                if (status !== '-') {
                                    if (status === 'H') {
                                        hadir++;
                                    } else if (status === 'I') {
                                        izin++;
                                    } else if (status === 'S') {
                                        sakit++;
                                    } else {
                                        alpha++;
                                    }
                                }

                                tableHtml += `<td>${status}</td>`;
                            }
                        }

                        // Tambahkan rekap jumlah Hadir, Izin, Sakit, Alpha
                        tableHtml += `<td>${hadir}</td><td>${izin}</td><td>${sakit}</td><td>${alpha}</td></tr>`;
                    }

                    tableHtml += `</tbody></table></div>`;

                    // Update tampilan tabel di halaman setelah data lengkap
                    $('#presensiTableContainer').html(tableHtml);
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan!',
                        text: 'Gagal mengambil data hari libur, coba lagi.',
                    });
                }
            });
        }


        function renderTable1(data, namaBulan, jumlahHari) {
            let tableHtml = `
    <div class="table-responsive">
        <h5 class="text-center">Presensi Bulan ${namaBulan}</h5>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Nama Siswa</th>`;
            // Create header for each day of the month
            for (let i = 1; i <= jumlahHari; i++) {
                tableHtml += `<th>${i}</th>`;
            }

            // Add headers for the recap totals (H, I, S, A)
            tableHtml += `<th>H</th><th>I</th><th>S</th><th>A</th></tr></thead><tbody>`;

            // Fill the table with student data and calculate the totals for H, I, S, A
            for (let namaSiswa in data) {
                let hadir = 0,
                    izin = 0,
                    sakit = 0,
                    alpha = 0;

                tableHtml += `<tr><td>${namaSiswa}</td>`;

                // Loop for each day of the month
                for (let i = 1; i <= jumlahHari; i++) {
                    let tanggal = `${(new Date()).getFullYear()}-${bulanAngka[namaBulan]}-${String(i).padStart(2, '0')}`;
                    let currentDate = new Date(tanggal);
                    let dayOfWeek = currentDate.getDay(); // Get the day of the week (0 = Sunday, 1 = Monday, etc.)

                    if (dayOfWeek === 0) { // Skip Sundays (0 = Sunday)
                        tableHtml += `<td>-</td>`; // Empty cell for Sundays
                        continue; // Skip counting this day
                    }

                    let status = data[namaSiswa][tanggal] || '-'; // Default '-' if status is empty (or not present)

                    // Count presence types and display only 'H' for Hadir
                    if (status === 'Hadir') {
                        hadir++;
                        status = 'H'; // Only show 'H' for Hadir
                    } else if (status === 'Izin') {
                        izin++;
                        status = 'I'; // Show 'I' for Izin
                    } else if (status === 'Sakit') {
                        sakit++;
                        status = 'S'; // Show 'S' for Sakit
                    } else if (status === '-' || status === 'A') {
                        alpha++; // Treat empty or missing status as 'Alpa'
                        status = 'A'; // Show 'A' for Alpa
                    }

                    tableHtml += `<td>${status}</td>`;
                }

                // Add recap totals to the end of the row
                tableHtml += `<td>${hadir}</td><td>${izin}</td><td>${sakit}</td><td>${alpha}</td></tr>`;
            }

            tableHtml += `</tbody></table></div>`;


            // Update the table container with the generated HTML
            $('#presensiTableContainer').html(tableHtml);
        }
    </script>
@endpush
