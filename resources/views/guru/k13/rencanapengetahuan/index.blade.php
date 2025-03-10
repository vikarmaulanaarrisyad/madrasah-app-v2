@extends('layouts.app')

@section('title', 'Rencana Pengetahuan')
@section('subtile', 'Nilai Pengetahuan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Rencana Nilai Pengetahuan</li>
@endsection


@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <x-slot name="header">
                    <h3 class="card-title"><i class="fas fa-list-alt"></i> @yield('title')</h3>
                    <div class="card-tools">
                        <button onclick="addForm(`{{ route('jurnalmengajar.store') }}`)" class="btn btn-sm btn-primary"><i
                                class="fas fa-plus-circle"></i> Tambah Data</button>
                    </div>
                </x-slot>
                <div class="table-responsive">
                    <x-table id="tabel-bobot">
                        <x-slot name="thead">
                            <th>No</th>
                            <th>Mata Pelajaran</th>
                            <th>Rombel</th>
                            <th>Jumlah Rencana Penilaian</th>
                            <th>Aksi</th>
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
        $('#tabel-bobot').DataTable({
            processing: true,
            serverSide: true,
            paging: false, // Tetap aktifkan pagination agar pengguna bisa memilih
            ajax: "{{ route('rencanapengetahuan.data') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'mata_pelajaran',
                    name: 'mata_pelajaran'
                },
                {
                    data: 'rombel',
                    name: 'rombel'
                },
                {
                    data: 'jumlah_rencana_penilaian',
                    name: 'jumlah_rencana_penilaian'
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                    orderable: false,
                    searchable: false
                }
            ],
            dom: 'Brt',
            bSort: false,
        });
    </script>
@endpush
