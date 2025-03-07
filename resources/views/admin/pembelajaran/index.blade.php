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
                            <label for="filterRombel" class="col-auto col-form-label"><strong>Pilih
                                    Rombel</strong></label>
                            <div class="col">
                                <select id="filterRombel" class="form-control form-control-sm" name="filterRombel">
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

@push('scripts')
    <script>
        $(document).ready(function() {
            let daftarGuru = [];

            // Ambil daftar guru saat halaman dimuat
            $.ajax({
                url: "{{ route('pembelajaran.getGuru') }}",
                type: "GET",
                success: function(response) {
                    daftarGuru = response; // Simpan daftar guru untuk dropdown
                },
                error: function() {
                    console.error("Gagal memuat daftar guru.");
                }
            });

            // Saat Rombel dipilih, ambil data mata pelajaran dan guru
            $('#filterRombel').change(function() {
                let rombel_id = $(this).val();
                if (rombel_id) {
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
                            response.data.forEach((mapel, index) => {
                                let selectedGuruId = mapel.pembelajaran ? mapel
                                    .pembelajaran.guru_id : '';
                                let guruOptions =
                                    '<option value="">-- Pilih Guru --</option>';

                                daftarGuru.forEach(guru => {
                                    let selected = guru.id == selectedGuruId ?
                                        'selected' : '';
                                    guruOptions +=
                                        `<option value="${guru.id}" ${selected}>${guru.nama_lengkap}</option>`;
                                });

                                html += `<tr>
                                    <td>${index + 1}</td>
                                    <td>${mapel.nama}</td>
                                    <td>
                                        <select class="form-control pilih-guru" data-mapel-id="${mapel.id}">
                                            ${guruOptions}
                                        </select>
                                    </td>
                                </tr>`;
                            });
                            $('#dataContainer').html(html);
                        },

                        error: function() {
                            Swal.fire({
                                icon: "error",
                                title: "Gagal memuat data!",
                                text: "Terjadi kesalahan saat mengambil data."
                            });
                        }
                    });
                } else {
                    $('#dataContainer').html('');
                }
            });

            // Saat guru dipilih, update ke database
            $(document).on('change', '.pilih-guru', function() {
                let mapel_id = $(this).data('mapel-id');
                let guru_id = $(this).val();
                let rombel_id = $('#filterRombel').val(); // Perbaikan seleksi elemen

                if (!guru_id) {
                    Swal.fire({
                        icon: "warning",
                        title: "Pilih Guru",
                        text: "Silakan pilih guru sebelum menyimpan."
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
