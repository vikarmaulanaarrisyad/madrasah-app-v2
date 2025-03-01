@extends('layouts.app')

@section('title', 'Interval Predikat')

@section('subtitle', 'Interval Predikat')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Interval Predikat</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <x-table>
                    <x-slot name="thead">
                        <th>No</th>
                        <th>Mata Pelajaran</th>
                        <th>Semester</th>
                        <th>Kelas</th>
                        <th>Batas Bawah Predikat C</th>
                        <th>Batas Bawah Predikat B</th>
                        <th>Batas Bawah Predikat A</th>
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
        let modal = '#modal-form';
        let button = '#submitBtn';

        table = $('.table').DataTable({
            processing: false,
            serverSide: true,
            autoWidth: false,
            responsive: true,
            ajax: {
                url: '{{ route('k13interval.data') }}',
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'mapel'
                },
                {
                    data: 'semester'
                },
                {
                    data: 'kelas'
                },
                {
                    data: 'predikat_c'
                },
                {
                    data: 'predikat_b'
                },
                {
                    data: 'predikat_a'
                },
            ]
        })
    </script>
@endpush
