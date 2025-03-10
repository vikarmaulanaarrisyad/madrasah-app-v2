@extends('layouts.app')

@section('title', 'Nilai PTS & PAS')
@section('subtile', 'Nilai PTS & PAS')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Nilai PTS & PAS</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <div class="table-responsive">
                    <x-table id="penilaian-table">
                        <x-slot name="thead">
                            <tr>
                                <th rowspan="2" class="text-center" style="width: 100px;">No</th>
                                <th rowspan="2" class="text-center">Mata Pelajaran</th>
                                <th rowspan="2" class="text-center">Kelas</th>
                                <th colspan="2" class="text-center" style="width: 200px;">Jumlah</th>
                                <th rowspan="2" class="text-center" style="width: 100px;">Input Nilai</th>
                            </tr>
                            <tr>
                                <th class="text-center" style="width: 100px;">Anggota Kelas</th>
                                <th class="text-center" style="width: 100px;">Telah Dinilai</th>
                            </tr>
                        </x-slot>
                    </x-table>
                </div>
            </x-card>
        </div>
    </div>
@endsection

@include('includes.datatables')

@push('scripts')
    <script>
        let modal = '#modal-form';
        let button = '#submitBtn';
        let table = $('#penilaian-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('nilaiptspas.data') }}", // Pastikan route ini sesuai dengan fungsi `data()`
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                },
                {
                    data: 'mata_pelajaran',
                    name: 'mataPelajaran.nama',
                },
                {
                    data: 'kelas',
                    name: 'rombel.nama',
                    className: 'text-center'
                },
                {
                    data: 'jumlah_anggota_rombel',
                    name: 'jumlah_anggota_rombel',
                    className: 'text-center'
                },
                {
                    data: 'jumlah_telah_dinilai',
                    name: 'jumlah_telah_dinilai',
                    className: 'text-center'
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                }
            ],
            dom: 'Brt',
            bSort: false,
        });

        function addForm(url, id, title = 'Data Nilai PTS & PAS') {
            Swal.fire({
                title: "Memuat...",
                text: "Mohon tunggu sebentar...",
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            setTimeout(() => {
                window.location.href = url;
            }, 1000); // Menunggu 1 detik sebelum navigasi
        }

        function editForm(url, id) {
            Swal.fire({
                title: "Memuat...",
                text: "Mohon tunggu sebentar...",
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            setTimeout(() => {
                window.location.href = url;
            }, 1000); // Menunggu 1 detik sebelum navigasi
        }
    </script>
@endpush
