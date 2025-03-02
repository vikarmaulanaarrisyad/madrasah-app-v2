@extends('layouts.app')

@section('title', 'Data Presensi Siswa')

@section('subtitle', 'Data Presensi Siswa')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Presensi Siswa</li>
@endsection

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
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>Presensi</th>
                    </x-slot>
                </x-table>
            </x-card>
        </div>
    </div>
@endsection

@include('includes.datatables')
@include('includes.datepicker')

@push('scripts')
    <script>
        let table;

        function updatePresensiStats() {
            let tanggal = $('#tanggalInput').val();
            $.ajax({
                url: '{{ route('presensisiswa.count') }}',
                type: 'GET',
                data: {
                    tanggal: tanggal
                },
                success: function(response) {

                },
                error: function(xhr, status, error) {
                    let errorMessage = "Terjadi kesalahan!";

                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error; // Jika ada pesan error dari backend
                    } else {
                        errorMessage = xhr
                        .responseText; // Tampilkan full response jika tidak ada properti `error`
                    }

                    console.error("Error Status:", status);
                    console.error("Error Message:", error);
                    console.error("Full Response:", xhr.responseText);

                    toastr.error(errorMessage, "Error", {
                        closeButton: true,
                        progressBar: true,
                        timeOut: 5000
                    });
                }
            });
        }

        $(document).ready(function() {
            // Inisialisasi DataTable
            table = $('.table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                responsive: true,
                ajax: {
                    url: '{{ route('presensisiswa.data') }}',
                    data: function(d) {
                        d.tanggal = $('#tanggalInput').val();
                    }
                },
                columns: [{
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
                    },
                ]
            });

            // Event saat tanggal berubah
            $('#tanggal').on('change.datetimepicker', function() {
                table.ajax.reload();
                updatePresensiStats();
            });

            // Event saat presensi diubah
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
                        table.ajax.reload();
                        updatePresensiStats();
                    },
                    error: function(xhr) {
                        console.error(xhr.responseJSON.error);
                    }
                });
            });

            // Load data pertama kali
            updatePresensiStats();
        });
    </script>
@endpush
