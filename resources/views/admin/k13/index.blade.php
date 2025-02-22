@extends('layouts.app')

@section('title', 'Data Nilai Siswa')

@section('subtitle', 'Data Nilai Siswa')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Data Nilai Siswa</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-left-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <i class="fas fa-exclamation-triangle fa-3x text-warning"></i>
                        </div>
                        <div>
                            <h5 class="font-weight-bold text-warning">📢 Pengumuman: Perhatikan KKM Setiap Mata Pelajaran
                            </h5>
                            <p class="mb-2 text-dark">
                                🎯 <strong>Perhatikan KKM setiap mata pelajaran!</strong><br>
                                Pastikan nilai siswa tidak di bawah KKM yang telah ditetapkan. Jika ada nilai yang belum
                                memenuhi KKM, lakukan perbaikan sebelum mencetak rapor.
                            </p>
                            <p class="mb-2 text-dark">
                                **Langkah-langkah yang perlu dilakukan:**
                            <ul class="mb-2">
                                <li>✅ Cek kembali nilai siswa yang belum memenuhi KKM</li>
                                <li>✅ Berikan kesempatan perbaikan bagi siswa</li>
                                <li>✅ Verifikasi data sebelum mencetak rapor</li>
                            </ul>
                            </p>
                            <p class="mb-0">
                                Silakan lakukan pengecekan melalui menu berikut:
                                <a href="#" class="btn btn-warning btn-sm font-weight-bold shadow">Kelola KKM</a>
                                <a href="#" class="btn btn-primary btn-sm font-weight-bold shadow">Cek Nilai Siswa</a>
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
                <x-table>
                    <x-slot name="thead">
                        <th>No</th>
                        <th>Mata Pelajaran</th>
                        <th>Semester</th>
                        <th>Kelas</th>
                        <th>KKM</th>
                        <th>Aksi</th>
                    </x-slot>
                </x-table>
            </x-card>
        </div>
    </div>
@endsection
