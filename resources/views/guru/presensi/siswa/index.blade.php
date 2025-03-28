@extends('layouts.app')

@section('title', 'Data Presensi Siswa')

@section('subtitle', 'Data Presensi Siswa')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Presensi Siswa</li>
@endsection

@push('css')
    <style>
        .custom-select {
            width: 30% !important;
            background: transparent !important;
            border: none !important;
            padding-right: 24px !important;
            appearance: none;
            font-size: 16px;
        }
    </style>
@endpush

@section('content')
    <div class="row mt-3">
        <div class="col-lg-12">
            <x-card>
                <x-slot name="header">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="tanggal">Tanggal <span class="text-danger">*</span></label>
                                <div class="input-group datepicker" id="tanggal" data-target-input="nearest">
                                    <input type="text" id="tanggalInput" name="tanggal"
                                        class="form-control datetimepicker-input" data-target="#tanggal"
                                        data-toggle="datetimepicker" autocomplete="off" value="{{ now()->format('Y-m-d') }}"
                                        placeholder="Pilih Tanggal" />
                                    <div class="input-group-append" data-target="#tanggal" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-slot>

                <x-table>
                    <x-slot name="thead">
                        <th width="8%">
                            <div class="d-flex align-items-center">
                                <input type="checkbox" id="pilihSemua" class="custom-checkbox">
                                <button onclick="modalPresensi()" class="btn btn-sm">⏷</button>
                            </div>
                        </th>
                        <th width="5%">No</th>
                        <th>Nama Siswa</th>
                        <th>Presensi</th>
                    </x-slot>
                </x-table>
            </x-card>
        </div>
    </div>

    @include('guru.presensi.siswa.modal_presensi')
@endsection

@include('includes.datatables')
@include('includes.datepicker')

@push('scripts')
    <script>
        let table;
        let modal = '#modalPresensiStatus';

        // Pilih semua checkbox
        $('#pilihSemua').on('change', function() {
            $('.siswa-checkbox').prop('checked', $(this).prop('checked'));
        });

        function modalPresensi(title = 'Presensi') {
            let selectedStudents = [];

            $('.siswa-checkbox:checked').each(function() {
                selectedStudents.push($(this).val());
            });

            // Cek apakah ada checkbox yang dicentang
            if (selectedStudents.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops!',
                    text: 'Pilih minimal satu siswa!',
                });
                return;
            }
            $('#siswa_id').val(selectedStudents); // Memasukkan ID siswa ke input hidden
            // Jika ada yang dipilih, tampilkan modal
            $(modal).modal('show');
            $(`${modal} .modal-title`).text(title);

            // Reset form di dalam modal jika ada
            resetForm(`${modal} form`);
        }

        function updatePresensiStats() {
            let tanggal = $('#tanggalInput').val();
            $.ajax({
                url: '{{ route('presensisiswa.count') }}',
                type: 'GET',
                data: {
                    tanggal: tanggal
                },
                success: function(response) {
                    if (response.data.length === 0) { // Cek jika DataTables kosong
                        Swal.fire({
                            icon: 'warning',
                            title: 'Data Tidak Ditemukan',
                            text: 'Rombel tidak ditemukan untuk guru ini!',
                            confirmButtonText: 'OK'
                        });
                    }

                    // Tetap render DataTable meskipun kosong
                    $('#tabelPresensi').DataTable().clear().rows.add(response.data).draw();
                },
                error: function(xhr) {
                    let errorMessage = "Terjadi kesalahan!";

                    if (xhr.status === 404 && xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;

                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorMessage,
                            confirmButtonText: 'OK'
                        });
                    } else {
                        toastr.error(xhr.responseText, "Error", {
                            closeButton: true,
                            progressBar: true,
                            timeOut: 5000
                        });
                    }
                }

            });
        }

        table = $('.table').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            responsive: true,
            paging: false,
            ajax: {
                url: '{{ route('presensisiswa.data') }}',
                data: function(d) {
                    d.tanggal = $('#tanggalInput').val();
                }
            },
            columns: [{
                    data: 'checkbox',
                    name: 'checkbox',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'nama'
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        // Cek hari libur saat halaman pertama kali dibuka
        cekHariLibur($('#tanggalInput').val());

        $(document).on('change', '.presensi-radio', function() {
            let siswa_id = $(this).data('siswa');
            let status = $(this).val();
            let tanggal = $('#tanggalInput').val();

            $.ajax({
                url: '{{ route('presensissiswa.simpanPresensi') }}',
                type: 'POST',
                data: {
                    siswa_id: siswa_id,
                    status: status,
                    tanggal: tanggal,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Swal.fire({
                        title: "Berhasil!",
                        text: response.success,
                        icon: "success",
                        timer: 3000,
                        showConfirmButton: false,
                    }).then(() => {
                        updatePresensiStats();
                    });
                    //table.ajax.reload();
                    //updatePresensiStats();
                },
                error: function(xhr) {
                    let errorMessage = "Terjadi kesalahan.";
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    }

                    Swal.fire({
                        title: "Error!",
                        text: errorMessage,
                        icon: "error",
                        confirmButtonText: "OK"
                    }).then(() => {
                        table.ajax.reload();
                    });
                }
            });
        });

        // Event saat tanggal berubah
        $('#tanggal').on('change.datetimepicker', function() {
            let selectedDate = $('#tanggalInput').val().trim(); // Pastikan tidak ada spasi ekstra

            if (!selectedDate) {
                return;
            }

            let dayOfWeek = new Date(selectedDate).getDay(); // 0 = Minggu, 1 = Senin, dst.

            if (dayOfWeek === 0) { // Jika hari Minggu
                $('#tabelPresensi').hide();
                Swal.fire({
                    icon: 'warning',
                    title: 'Hari Minggu!',
                    text: 'Hari Minggu tidak ada presensi.',
                    confirmButtonText: 'OK'
                });
                return; // Hentikan eksekusi lebih lanjut
            }

            cekHariLibur(selectedDate);
        });

        function cekHariLibur(tanggal) {
            $.ajax({
                url: '{{ route('presensisiswa.cekHariLibur') }}',
                type: 'GET',
                data: {
                    tanggal: tanggal
                },
                success: function(response) {
                    if (response.status === "libur") {
                        $('#tabelPresensi').hide();
                        $('.presensi-radio').prop('disabled', true);
                        Swal.fire({
                            icon: 'warning',
                            title: 'Hari Libur!',
                            text: response.message,
                            confirmButtonText: 'OK'
                        });
                    } else {
                        $('#tabelPresensi').show();
                        $('.presensi-radio').prop('disabled', false);
                        table.ajax.reload();
                    }
                },
                error: function(xhr) {
                    //console.error("Terjadi kesalahan AJAX:", xhr.responseText);
                }
            });
        }

        function updatePresensiStats() {
            let tanggal = $('#tanggalInput').val();
            $.ajax({
                url: '{{ route('presensisiswa.count') }}',
                type: 'GET',
                data: {
                    tanggal: tanggal
                },
                success: function(response) {
                    if (response.data.length === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Data Tidak Ditemukan',
                            text: 'Rombel tidak ditemukan untuk guru ini!',
                            confirmButtonText: 'OK'
                        });
                    }
                    table.clear().rows.add(response.data).draw();
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        }

        updatePresensiStats();
    </script>
@endpush
