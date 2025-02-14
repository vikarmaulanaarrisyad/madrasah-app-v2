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
                            <h5 class="font-weight-bold text-purple">📓 Jurnal Guru</h5>
                            <p class="mb-2 text-dark">
                                Setiap guru wajib mengisi jurnal harian untuk mencatat aktivitas pembelajaran yang telah
                                dilakukan. Pastikan jurnal diisi dengan lengkap, termasuk materi yang diajarkan, metode
                                pembelajaran, dan evaluasi singkat.
                            </p>
                            <p class="mb-0">
                                Gunakan <strong>filter kelas, guru dan tanggal</strong> di bawah ini untuk mencari jurnal
                                lebih cepat
                                🔍.
                            </p>
                        </div>
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
                        <div class="d-flex align-items-center">
                            <div class="row g-2 align-items-center">
                                <!-- Dropdown Filter Kelas -->
                                <div class="col-auto">
                                    <select name="kelas" id="filterKelas" class="form-control">
                                        <option value="">Semua Kelas</option>
                                        @foreach ($kelas as $k)
                                            @foreach ($k->rombel as $item)
                                                <option value="{{ $item->id }}">{{ $k->nama }} {{ $item->nama }}
                                                </option>
                                            @endforeach
                                        @endforeach

                                    </select>
                                </div>

                                <!-- Dropdown Filter Guru -->
                                <div class="col-3">
                                    <select name="guru" id="filterGuru" class="form-control">
                                        <option value="">Semua Guru</option>
                                        @foreach ($guru as $g)
                                            <option value="{{ $g->id }}">{{ $g->nama_lengkap }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Input Tanggal Mulai -->
                                <div class="col-3">
                                    <div class="input-group date" id="tglMulai" data-target-input="nearest">
                                        <input type="text" name="tglMulai" id="inputTglMulai"
                                            class="form-control datetimepicker-input" data-toggle="datetimepicker"
                                            data-target="#tglMulai" autocomplete="off" />
                                        <div class="input-group-append" data-target="#tglMulai"
                                            data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Label "s/d" -->
                                <div class="col-auto">
                                    <label class="form-label mb-0">s/d</label>
                                </div>

                                <!-- Input Tanggal Selesai -->
                                <div class="col-3">
                                    <div class="input-group date" id="tglSelesai" data-target-input="nearest">
                                        <input type="text" name="tglSelesai" data-toggle="datetimepicker"
                                            id="inputTglSelesai" class="form-control datetimepicker-input"
                                            data-target="#tglSelesai" autocomplete="off" disabled />
                                        <div class="input-group-append" data-target="#tglSelesai"
                                            data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-slot>

                <x-table>
                    <x-slot name="thead">
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Kelas</th>
                        <th>Guru</th>
                        <th>Materi</th>
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
                    d.kelas = $('[name=kelas]').val();
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
                    data: 'kelas.nama'
                },
                {
                    data: 'guru'
                },
                {
                    data: 'materi'
                },
            ]
        })

        $('#filterKelas').on('change', function() {
            if ($(this).val() !== '') {
                $('#filterGuru').val('');
                $('#inputTglMulai').val('');
                $('#inputTglSelesai').val('').prop('disabled', true);
            }
            table.ajax.reload();
        });

        $('#filterGuru').on('change', function() {
            if ($(this).val() !== '') {
                $('#filterKelas').val('');
                $('#inputTglMulai').val('');
                $('#inputTglSelesai').val('').prop('disabled', true);
            }
            table.ajax.reload();
        });

        // Inisialisasi DateTimePicker
        $('#tglMulai').datetimepicker({
            format: 'YYYY-MM-DD',
            useCurrent: false,
        });

        $('#tglSelesai').datetimepicker({
            format: 'YYYY-MM-DD',
            useCurrent: false,
        });

        // Event ketika tanggal mulai dipilih
        $("#tglMulai").on("change.datetimepicker", function(e) {
            let tglMulai = e.date ? e.date.format('YYYY-MM-DD') : '';

            if (tglMulai) {
                // Enable input tglSelesai dan set batas minimal
                $("#inputTglSelesai").prop("disabled", false).val("");
                $('#tglSelesai').datetimepicker('minDate', moment(tglMulai, 'YYYY-MM-DD'));
                $('#filterKelas').val('');
                $('#filterGuru').val('');
            } else {
                // Jika kosong, disable kembali tglSelesai
                $("#inputTglSelesai").prop("disabled", true).val("");
                $('#tglSelesai').datetimepicker('minDate', false);
            }
        });

        // Event ketika tanggal selesai dipilih
        $("#tglSelesai").on("change.datetimepicker", function(e) {
            let tglMulai = $("#inputTglMulai").val();
            let tglSelesai = e.date ? e.date.format('YYYY-MM-DD') : '';
            $('#filterKelas').val('');
            $('#filterGuru').val('');
            table.ajax.reload();

            if (tglMulai && tglSelesai && moment(tglSelesai).isBefore(moment(tglMulai))) {
                Swal.fire({
                    icon: 'error',
                    title: 'Tanggal Tidak Valid!',
                    text: 'Tanggal Selesai tidak boleh sebelum Tanggal Mulai.',
                });
                $("#inputTglSelesai").val(""); // Reset input jika tidak valid
            }
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
    </script>
@endpush
