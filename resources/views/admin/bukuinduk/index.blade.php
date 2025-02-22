@extends('layouts.app')

@section('title', 'Cetak Buku Induk Siswa')
@section('subtitle', 'Cetak Buku Induk Siswa')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Cetak Buku Induk Siswa</li>
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
                            <button onclick="downloadSemua()" type="button" class="btn btn-danger btn-sm"><i
                                    class="fas fa-download"></i>
                                Download PDF
                            </button>
                        </div>
                    </div>
                </x-slot>

                <x-table>
                    <x-slot name="thead">
                        <th>No</th>
                        <th>Nama Lengkap</th>
                        <th>NISN</th>
                        <th>NIS</th>
                        <th>Kelas / Rombel</th>
                        <th>Aksi</th>
                    </x-slot>
                </x-table>
            </x-card>
        </div>
    </div>
@endsection

@include('includes.datatables')

@push('scripts')
    <script>
        let table;

        table = $('.table').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            responsive: true,
            ajax: {
                url: '{{ route('bukuinduk.data') }}',
                data: function(d) {
                    d.rombelId = $('#rombel_id').val();
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'nama_lengkap'
                },
                {
                    data: 'nisn'
                },
                {
                    data: 'nis'
                },
                {
                    data: 'rombel'
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        $('#rombel_id').on('change', function() {
            table.ajax.reload();
        })

        function downloadSemua1() {
            let rombelId = $('#rombel_id').val();

            if (!rombelId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pilih Rombel!',
                    text: 'Silakan pilih rombel terlebih dahulu sebelum mengunduh.',
                });
                return;
            }

            Swal.fire({
                title: 'Sedang Memproses...',
                text: 'Mohon tunggu, sedang menyiapkan file PDF.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: '{{ route('bukuinduk.download_all') }}', // Ganti dengan route yang sesuai
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}', // Pastikan ada CSRF token
                    rombel_id: rombelId
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'PDF berhasil dibuat, unduhan akan segera dimulai.',
                        timer: 2000,
                        showConfirmButton: false
                    });

                    // Jika respons berisi URL file PDF

                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan!',
                        text: xhr.responseJSON?.message || 'Gagal mengunduh PDF. Silakan coba lagi.',
                    });
                }
            });
        }

        function downloadSemua() {
            let rombelId = $('#rombel_id').val();

            if (!rombelId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pilih Rombel!',
                    text: 'Silakan pilih rombel terlebih dahulu sebelum mengunduh.',
                });
                return;
            }

            Swal.fire({
                title: 'Sedang Memproses...',
                text: 'Mohon tunggu, sedang menyiapkan file PDF.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Buka PDF di tab baru
            window.open(`{{ route('bukuinduk.download_all') }}?rombel_id=${rombelId}`, '_blank');

            Swal.close();
        }
    </script>
@endpush
