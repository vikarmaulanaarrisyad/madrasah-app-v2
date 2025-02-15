@extends('layouts.app')

@section('title', 'Data Jurnal')

@section('subtitle', 'Data Jurnal')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Data Jurnal</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <!-- Card Pengumuman -->
            <div class="card shadow-sm border-left-purple">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <i class="fas fa-book fa-3x text-purple"></i>
                        </div>
                        <div>
                            <h5 class="font-weight-bold text-purple">Pengumuman</h5>
                            <p class="mb-2 text-dark">
                                Setiap guru wajib mengisi jurnal harian untuk mencatat aktivitas pembelajaran yang telah
                                dilakukan. Pastikan jurnal diisi dengan lengkap, termasuk materi yang diajarkan, metode
                                pembelajaran, dan evaluasi singkat. Gunakan <strong>filter kelas, guru dan tanggal</strong>
                                di bawah ini untuk mencari jurnal
                                lebih cepat
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                                @foreach ($rombel as $item)
                                    <option value="{{ $item->id }}">{{ $item->kelas->nama }} {{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Dropdown Filter Guru -->
                        <div class="col-md-3">
                            <label class="form-label">Filter Guru</label>
                            <select name="guru" id="filterGuru" class="form-control">
                                <option value="">Semua Guru</option>
                                @foreach ($guru as $g)
                                    <option value="{{ $g->id }}">{{ $g->nama_lengkap }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{--  <div class="col-md-3">
                            <label class="form-label">Filter Mata Pelajaran</label>
                            <select name="kelas" id="filterRombel" class="form-control">
                                <option value="">Semua Kelas</option>
                                @foreach ($kelas as $k)
                                    @foreach ($k->rombel as $item)
                                        <option value="{{ $item->id }}">{{ $k->nama }} {{ $item->nama }}</option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>  --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <x-slot name="header">
                    <h3 class="card-title">
                        <i class="fas fa-calendar-alt mr-1 mt-2"></i>
                        @yield('subtitle')
                    </h3>
                    <div class="card-tools">
                        <button onclick="exportPDF()" id="downloadPdf" class="btn btn-danger btn-sm">
                            <i class="fas fa-file-pdf"></i> Download PDF
                        </button>
                    </div>

                </x-slot>

                <x-table>
                    <x-slot name="thead">
                        <th>No</th>
                        <th style="width: 10%;">Tanggal</th>
                        <th>Rombel</th>
                        <th>Guru</th>
                        <th>Mapel</th>
                        <th>Materi</th>
                        <th>Penilaian</th>
                    </x-slot>
                </x-table>
            </x-card>
        </div>
    </div>
    @include('jurnal.form')
@endsection

@include('includes.datatables')
@include('includes.datepicker')

@push('css')
    <style>
        .table td,
        .table th {
            white-space: normal !important;
            word-wrap: break-word !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        let table;
        let modal = '#modal-form';
        let button = '#submitBtn';

        table = $('.table').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            responsive: true,
            ajax: {
                url: '{{ route('jurnal.data') }}',
                data: function(d) {
                    d.startDate = $('[name=tglMulai]').val();
                    d.endDate = $('[name=tglSelesai]').val();
                    d.rombel = $('[name=rombel]').val();
                    d.guru = $('[name=guru]').val();
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'tanggal'
                },
                {
                    data: 'rombel'
                },
                {
                    data: 'guru'
                },
                {
                    data: 'mapel'
                },
                {
                    data: 'materi'
                },
                {
                    data: 'penilaian'
                },
            ]
        })

        $('#filterRombel').on('change', function() {
            if ($(this).val() !== '') {
                $('#filterGuru').val('');
                $('#inputTglMulai').val('');
                $('#inputTglSelesai').val('').prop('disabled', true);
            }
            table.ajax.reload();
        });

        $('#filterGuru').on('change', function() {
            if ($(this).val() !== '') {
                $('#filterRombel').val('');
                $('#inputTglMulai').val('');
                $('#inputTglSelesai').val('').prop('disabled', true);
            }
            table.ajax.reload();
        });

        function addForm(url, title = 'Form Data Jurnal') {
            $(modal).modal('show');
            $(`${modal} .modal-title`).text(title);
            $(`${modal} form`).attr('action', url);
            $(`${modal} [name=_method]`).val('post');

            resetForm(`${modal} form`);
        }

        function editForm(url, title = 'Form Data Jurnal') {
            Swal.fire({
                title: "Memuat...",
                text: "Mohon tunggu sebentar...",
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading(); // Menampilkan spinner loading
                }
            });

            $.get(url)
                .done(response => {
                    Swal.close(); // Tutup loading setelah sukses
                    $(modal).modal('show');
                    $(`${modal} .modal-title`).text(title);
                    $(`${modal} form`).attr('action', url);
                    $(`${modal} [name=_method]`).val('put');

                    resetForm(`${modal} form`);
                    loopForm(response.data);
                })
                .fail(errors => {
                    Swal.close(); // Tutup loading jika terjadi error
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops! Gagal',
                        text: errors.responseJSON?.message || 'Terjadi kesalahan saat memuat data.',
                        showConfirmButton: true,
                    });

                    if (errors.status == 422) {
                        loopErrors(errors.responseJSON.errors);
                    }
                });
        }

        function submitForm(originalForm) {
            $(button).prop('disabled', true);

            // Menampilkan Swal loading
            Swal.fire({
                title: 'Mohon Tunggu...',
                text: 'Sedang memproses data',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading(); // Menampilkan animasi loading
                }
            });

            $.ajax({
                url: $(originalForm).attr('action'),
                type: $(originalForm).attr('method') || 'POST', // Gunakan method dari form
                data: new FormData(originalForm),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(response, textStatus, xhr) {
                    Swal.close(); // Tutup Swal Loading

                    if (xhr.status === 201 || xhr.status === 200) {
                        $(modal).modal('hide');

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 3000
                        }).then(() => {
                            $(button).prop('disabled', false);
                            table.ajax.reload(); // Reload DataTables
                        });
                    }
                },
                error: function(xhr) {
                    Swal.close(); // Tutup Swal Loading
                    $(button).prop('disabled', false);

                    let errorMessage = "Terjadi kesalahan!";
                    if (xhr.responseJSON?.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Oops! Gagal',
                        text: errorMessage,
                        showConfirmButton: false,
                        timer: 3000,
                    });

                    if (xhr.status === 422) {
                        loopErrors(xhr.responseJSON.errors);
                    }
                }
            });
        }

        // fungsi mendownload pdf
        function exportPDF() {
            let filterRombel = $('#filterRombel').val(); // Ambil nilai filter kelas
            let filterGuru = $('#filterGuru').val(); // Ambil nilai filter guru

            // Cek apakah setidaknya ada satu filter yang dipilih
            if (!filterRombel && !filterGuru) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan!',
                    text: 'Silakan pilih filter kelas atau guru sebelum mendownload PDF.',
                    confirmButtonColor: '#d33',
                });
                return; // Hentikan proses jika tidak ada filter yang dipilih
            }

            // Tampilkan SweetAlert Loading
            Swal.fire({
                title: 'Sedang memproses...',
                text: 'Harap tunggu, sedang menyiapkan file PDF.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Buat URL dengan parameter yang tersedia (jika tidak ada, tidak ditambahkan ke URL)
            let url = `{{ route('jurnal.exportPDF') }}?`;
            if (filterRombel) url += `rombel=${filterRombel}&`;
            if (filterGuru) url += `guru=${filterGuru}&`;

            // Hapus karakter '&' terakhir jika ada
            url = url.replace(/&$/, '');

            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    Swal.close(); // Tutup SweetAlert Loading

                    if (response.status === 403) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops!',
                            text: response.message,
                        });
                    } else {
                        // Redirect ke link download PDF dengan filter yang dipilih
                        window.location.href = url;
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan, silakan coba lagi.',
                    });
                }
            });
        }
    </script>
@endpush
