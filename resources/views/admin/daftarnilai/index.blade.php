@extends('layouts.app')

@section('title', 'Daftar Nilai Siswa')

@section('subtitle', 'Daftar Nilai Siswa')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">@yield('title')</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <x-slot name="header">
                    <h3 class="card-title">
                        <i class="fas fa-print mr-1 mt-2"></i>
                        @yield('subtitle')
                    </h3>
                    <div class="card-tools">
                        <div class="d-flex align-items-center">
                            <div class="mr-2">
                                <select id="rombel_id" name="rombel_id" class="form-control form-control-sm">
                                    <option value="">- Semua Rombel -</option>
                                    @foreach ($rombels as $item)
                                        <option value="{{ $item->id }}">{{ $item->kelas->nama }} {{ $item->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mr-2">
                                <select id="mata_pelajaran_id" name="mata_pelajaran_id" class="form-control form-control-sm"
                                    disabled>
                                    <option value="">- Semua Mata Pelajaran -</option>
                                </select>
                            </div>

                            <button onclick="downloadSemua()" type="button" class="btn btn-danger btn-sm"><i
                                    class="fas fa-download"></i>
                                Download PDF
                            </button>
                        </div>
                    </div>
                </x-slot>

                <x-table id="data-table">
                    <thead id="table-header" class="bg-success">
                        <tr>
                            <th colspan="10" class="text-center">Silakan pilih kelas dan mata pelajaran.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="10" class="text-center">Tidak ada data.</td>
                        </tr>
                    </tbody>
                </x-table>

            </x-card>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('body').addClass('sidebar-collapse');

            $('#rombel_id').change(function() {
                let rombelId = $(this).val();

                if (rombelId) {
                    Swal.fire({
                        title: 'Loading...',
                        text: 'Mohon tunggu, sedang mengambil data mata pelajaran...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // AJAX untuk mendapatkan daftar mata pelajaran berdasarkan rombel
                    $.ajax({
                        url: "{{ route('cetakdaftarnilai.get.mata_pelajaran') }}", // Sesuaikan dengan route yang menangani permintaan ini
                        type: "GET",
                        data: {
                            rombel_id: rombelId
                        },
                        success: function(response) {
                            Swal.close();

                            let mataPelajaranSelect = $('#mata_pelajaran_id');
                            mataPelajaranSelect.prop('disabled', false).html(
                                '<option value="">- Pilih Mata Pelajaran -</option>');

                            $.each(response.mata_pelajaran, function(key, item) {
                                mataPelajaranSelect.append(
                                    `<option value="${item.id}">${item.nama}</option>`
                                );
                            });
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Terjadi kesalahan saat mengambil data mata pelajaran!'
                            });
                        }
                    });
                } else {
                    $('#mata_pelajaran_id').prop('disabled', true).html(
                        '<option value="">- Pilih Mata Pelajaran -</option>');
                    $('#data-table').html(
                        '<tr><td colspan="10" class="text-center">Tidak ada data.</td></tr>');
                }
            });

            // Event ketika mata pelajaran dipilih
            $('#mata_pelajaran_id').change(function() {
                let mataPelajaranId = $(this).val();
                let rombelId = $('#rombel_id').val();

                if (mataPelajaranId) {
                    Swal.fire({
                        title: 'Loading...',
                        text: 'Mengambil data nilai akhir...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // AJAX untuk mendapatkan nilai akhir dari mata pelajaran yang dipilih
                    $.ajax({
                        url: "{{ route('cetakdaftarnilai.filter') }}", // Sesuaikan dengan route yang menangani permintaan ini
                        type: "GET",
                        data: {
                            rombel_id: rombelId,
                            mata_pelajaran_id: mataPelajaranId
                        },
                        success: function(response) {
                            Swal.close();

                            //$('#table-header').html(response.header);
                            $('#data-table').html(response.body);
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Terjadi kesalahan saat mengambil nilai akhir!'
                            });
                        }
                    });
                } else {
                    $('#data-table').html(
                        '<tr><td colspan="10" class="text-center">Tidak ada data.</td></tr>');
                }
            });
        });
    </script>
@endpush
