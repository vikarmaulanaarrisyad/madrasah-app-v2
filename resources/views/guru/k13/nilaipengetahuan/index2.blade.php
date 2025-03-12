@extends('layouts.app')

@section('title', 'Nilai Harian')
@section('subtile', 'Nilai Harian')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Nilai Harian</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <x-slot name="header">
                    <h5 class="card-title mb-2">Nilai Harian
                        <span class="text-muted text-sm">{{ $rombel->kelas->nama }} {{ $rombel->nama }}</span>
                        <p class="text-xs">{{ $mataPelajaran->nama }}</p>
                    </h5>
                    <div class="btn-group text-center text-md-right">
                        <a href="#" class="btn btn-danger mb-1">Kembali</a>
                        <a href="{{ route('nilaipengetahuan.create', [$rombel->id, $mataPelajaran->id]) }}"
                            class="btn btn-primary mb-1">Tambah</a>
                        {{--  <button onclick="addNilaiSiswa(`{{ route('nilaipengetahuan.store') }}`)"
                            class="btn btn-primary mb-1">Tambah</button>  --}}
                        <a href="#" class="btn btn-success mb-1">Upload</a>
                        <a href="#" class="btn btn-warning mb-1">Export</a>
                    </div>
                </x-slot>
                <div class="table-responsive">
                    <x-table data-rombel-id="{{ $rombel->id }}" id="nilai-harian-table">
                        <x-slot name="thead">
                            <th>No</th>
                            <th>Nama</th>
                            <th>Nilai</th>
                        </x-slot>
                    </x-table>

                </div>
            </x-card>
        </div>
    </div>
    @include('guru.k13.nilaipengetahuan.form')
@endsection

@include('includes.datatables')

@push('scripts')
    <script>
        let rombelId = $('#nilai-harian-table').data('rombel-id'); // Ambil rombel_id
        let modal = '#modal-form';
        let button = '#submitBtn';

        $('#nilai-harian-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            paging: false,
            ajax: {
                url: "{{ route('nilaipengetahuan.siswa_data') }}",
                data: function(d) {
                    d.rombel_id = rombelId; // Kirim rombel_id ke server
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'nama',
                    name: 'nama'
                },
                {
                    data: 'nilai',
                    name: 'nilai'
                }
            ],
            dom: 'Brt',
            bSort: false,

        });
    </script>
@endpush
