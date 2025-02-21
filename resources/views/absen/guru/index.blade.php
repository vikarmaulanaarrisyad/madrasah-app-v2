@extends('layouts.app')

@section('title', 'Cetak Presensi Guru')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Cetak Presensi Guru</li>
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
                            <label class="form-label">Filter Guru</label>
                            <select name="guru" id="filterGuru" class="form-control">
                                <option value="">Semua Guru</option>
                                @foreach ($gurus as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_lengkap ?? '' }}
                                        {{ $item->gelar_belakang ?? '' }}
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
            $('#filterGuru').change(function() {
                if ($(this).val()) {
                    $('#filterMonth, #btnTampilkan').prop('disabled', false);
                } else {
                    $('#filterMonth, #btnTampilkan, #btnDownloadPdf').prop('disabled', true).val('');
                }
            });

            // Reset Filter
            $('#btnReset').click(function() {
                $('#filterGuru, #filterMonth').val('');
                $('#filterMonth, #btnTampilkan, #btnDownloadPdf').prop('disabled', true);
                $('#presensiTableContainer').html(''); // Hapus data tampilan
            });

            // Tampilkan Data (Contoh AJAX)
            $('#btnTampilkan').click(function() {
                let guru = $('#filterGuru').val();
                let bulan = $('#filterMonth').val();
                if (guru) {
                    Swal.fire({
                        title: 'Memuat Data...',
                        text: 'Silakan tunggu sebentar',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: "{{ route('presensi.guru.filter') }}",
                        type: "GET",
                        data: {
                            bulan: bulan,
                            guru: guru
                        },
                        success: function(response) {
                            Swal.close();

                            if (response.data && Object.keys(response.data).length > 0) {
                                renderTable(response);

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
                let guru = $('#filterGuru').val();
                let bulan = $('#filterMonth').val();
                if (guru) {
                    window.location.href = "{{ route('presensi.guru.download') }}?bulan=" + bulan +
                        "&guru=" + guru;
                }

            });
        });

        function renderTable(response) {
            let data = response.data;
            let namaBulan = response.namaBulan; // Misalnya "Februari"
            let bulan = response.bulan; // Misalnya "Februari"
            let jumlahHari = response.jumlahHari;
            let bulanAngka = {
                "01": "01",
                "02": "02",
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

            // Menghancurkan konten lama jika ada
            $('#presensiTableContainer').empty();

            // Membuat elemen HTML untuk tabel
            let tableHtml = `
        <div class="table-responsive">
            <h5 class="text-center">Presensi Bulan ${namaBulan}</h5>
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Tgl</th>
                        <th>Jam Masuk</th>
                        <th>Jam Keluar</th>
                        <th>Waktu Masuk</th>
                        <th>Waktu Keluar</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>`;

            // Loop untuk setiap hari dalam bulan
            let no = 1;
            for (let namaGuru in data) {
                let guruData = data[namaGuru];

                // Loop through each day of the month
                for (let i = 1; i <= jumlahHari; i++) {

                    let tanggal = `2025-${String(bulan).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
                    let tanggalData = guruData[tanggal] || tanggal;
                    console.log(tanggalData);
                    // Ensure there's data for the specific date, like 2025-02-18
                    let waktuMasuk = (tanggalData?.is_holiday == '1') ? "-" : tanggalData?.waktu_masuk || "-";
                    let waktuKeluar = (tanggalData?.is_holiday == '1') ? "-" : tanggalData?.waktu_keluar || "-";
                    let jamMasuk = tanggalData?.jam_masuk || "-";
                    let jamKeluar = tanggalData?.jam_keluar || "-";
                    // Check if it's a holiday and set the status accordingly
                    let status = (tanggalData?.is_holiday == '1') ? "Libur" : (tanggalData?.status || "-");

                    // Add a row for the specific date and display data
                    tableHtml += `
                <tr>
                    <td>${no}</td>
                    <td>${tanggal}</td>
                    <td>${jamMasuk}</td>
                    <td>${jamKeluar}</td>
                    <td>${waktuMasuk}</td>
                    <td>${waktuKeluar}</td>
                    <td>${status}</td>
                </tr>`;

                    no++;
                }
            }


            tableHtml += `</tbody></table></div>`;

            // Menambahkan tabel ke dalam container
            $('#presensiTableContainer').html(tableHtml);
        }
    </script>
@endpush
