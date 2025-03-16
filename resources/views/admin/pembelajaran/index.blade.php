@extends('layouts.app')

@section('title', 'Pembelajaran')
@section('subtitle', 'Pembelajaran')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Pembelajaran</li>
@endsection

@section('content')
    <div class="row mt-3">
        <div class="col-lg-12">
            <x-card>
                <x-slot name="header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="form-group row align-items-center">
                            <label for="filterRombel" class="col-auto col-form-label"><strong>Pilih Rombel</strong></label>
                            <div class="col">
                                <select id="filterRombel" class="form-control form-control-sm select2" name="filterRombel">
                                    <option value="">-- Pilih Rombel --</option>
                                    @foreach ($rombels as $rombel)
                                        <option value="{{ $rombel->id }}">{{ $rombel->kelas->nama }} {{ $rombel->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </x-slot>

                <table class="table table-bordered table-striped" id="mapelTable">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Mata Pelajaran</th>
                            <th>Guru</th>
                            <th>Jam Ke</th>
                        </tr>
                    </thead>
                    <tbody id="dataContainer">
                        <!-- Data akan dimuat via AJAX -->
                    </tbody>
                </table>
            </x-card>
        </div>
    </div>
@endsection

@include('includes.select2')

@push('scripts')
    <script>
        $(document).ready(function() {
            let daftarGuru = [];

            function initSelect2() {
                $('.select2').select2({
                    theme: 'bootstrap4',
                    width: '200px'
                });
            }


            // Ambil daftar guru saat halaman dimuat
            function fetchGuruList() {
                $.ajax({
                    url: "{{ route('pembelajaran.getGuru') }}",
                    type: "GET",
                    success: function(response) {
                        daftarGuru = response;
                    },
                    error: function() {
                        console.error("Gagal memuat daftar guru.");
                    }
                });
            }
            fetchGuruList(); // Panggil fungsi saat halaman dimuat

            // Saat Rombel dipilih, ambil data mata pelajaran dan guru
            $('#filterRombel').change(function() {
                let rombel_id = $(this).val();
                if (!rombel_id) {
                    $('#dataContainer').html('');
                    return;
                }

                $.ajax({
                    url: "{{ route('pembelajaran.getMapelByRombel') }}",
                    type: "GET",
                    data: {
                        rombel_id: rombel_id
                    },
                    beforeSend: function() {
                        Swal.fire({
                            title: "Memuat data...",
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading()
                        });
                    },
                    success: function(response) {
                        Swal.close();
                        let html = '';

                        if (Array.isArray(response.data)) {
                            console.log("Response Data:", response.data);

                            response.data.forEach((mapel, index) => {
                                let selectedGuruId = mapel.pembelajaran ? mapel
                                    .pembelajaran.guru_id : '';

                                // Jika jamke berbentuk string "1,2", ubah menjadi array [1, 2]
                                let selectedJamKe = mapel.pembelajaran && mapel
                                    .pembelajaran.jamke ?
                                    mapel.pembelajaran.jamke.split(",").map(Number) :
                                [];

                                let guruOptions =
                                    '<option value="">-- Pilih Guru --</option>';
                                daftarGuru.forEach(guru => {
                                    let selected = (String(guru.id) === String(
                                        selectedGuruId)) ? 'selected' : '';
                                    guruOptions +=
                                        `<option value="${guru.id}" ${selected}>${guru.nama_lengkap}</option>`;
                                });

                                let jamOptions =
                                    '<option value="">-- Pilih Jam --</option>';
                                for (let i = 1; i <= 12; i++) {
                                    let selected = selectedJamKe.includes(i) ?
                                        'selected' : '';
                                    jamOptions +=
                                        `<option value="${i}" ${selected}>${i}</option>`;
                                }

                                html += `<tr>
            <td>${index + 1}</td>
            <td>${mapel.nama}</td>
            <td>
                <select class="form-control select2 pilih-guru" data-mapel-id="${mapel.id}">
                    ${guruOptions}
                </select>
            </td>
            <td>
                <select class="form-control select2 pilih-jam-ke" data-mapel-id="${mapel.id}" multiple>
                    ${jamOptions}
                </select>
            </td>
        </tr>`;
                            });
                        } else {

                        }


                        $('#dataContainer').html(html);

                        // Inisialisasi Select2 setelah elemen dimuat
                        initSelect2();

                        // Fix untuk select2 agar tidak menyebabkan error dengan focus
                        $('.select2').on('select2:open', function() {
                            let searchField = document.querySelector(
                                '.select2-search__field');
                            if (searchField) searchField.focus();
                        });
                    },


                    error: function() {
                        Swal.fire({
                            icon: "error",
                            title: "Gagal memuat data!",
                            text: "Terjadi kesalahan saat mengambil data."
                        });
                    }
                });
            });

            // Saat guru atau jam dipilih, update ke database
            $(document).on('change', '.pilih-guru, .pilih-jam-ke', function() {
                let mapel_id = $(this).data('mapel-id');
                let guru_id = $(`.pilih-guru[data-mapel-id="${mapel_id}"]`).val();
                let jam_ke = $(`.pilih-jam-ke[data-mapel-id="${mapel_id}"]`).val(); // Mengambil array

                let rombel_id = $('#filterRombel').val();

                if (!guru_id || !jam_ke) {
                    Swal.fire({
                        icon: "warning",
                        title: "Pilih Guru & Jam Ke",
                        text: "Silakan pilih guru dan jam sebelum menyimpan."
                    });
                    return;
                }

                $.ajax({
                    url: "{{ route('pembelajaran.setGuru') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        mapel_id: mapel_id,
                        guru_id: guru_id,
                        jam_ke: JSON.stringify(jam_ke), // Kirim sebagai JSON string
                        rombel_id: rombel_id
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: response.success ? "success" : "error",
                            title: response.success ? "Berhasil!" : "Gagal!",
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                    },
                    error: function() {
                        Swal.fire({
                            icon: "error",
                            title: "Terjadi Kesalahan",
                            text: "Silakan coba lagi atau periksa koneksi Anda."
                        });
                    }
                });
            });
        });
    </script>
@endpush
