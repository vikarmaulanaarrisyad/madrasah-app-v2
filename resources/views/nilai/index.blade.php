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
            <div class="card shadow-sm border-left-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <i class="fas fa-print fa-3x text-primary"></i>
                        </div>
                        <div>
                            <h5 class="font-weight-bold text-primary">📢 Pengumuman: Persiapan Cetak Rapor</h5>
                            <p class="mb-2 text-dark">
                                Pastikan semua data nilai siswa sudah **terinput dengan benar** sebelum mencetak rapor.
                                Periksa kembali nilai harian, nilai PAS, nilai PAT, serta KKM setiap mata pelajaran.
                            </p>
                            <p class="mb-2 text-dark">
                                **Langkah-langkah yang perlu dilakukan sebelum mencetak rapor:**
                            <ul class="mb-2">
                                <li>✅ Pastikan semua siswa telah memiliki rombel</li>
                                <li>✅ Periksa dan lengkapi nilai siswa</li>
                                <li>✅ Verifikasi data sebelum cetak</li>
                            </ul>
                            </p>
                            <p class="mb-0">
                                Silakan lakukan pengecekan melalui menu berikut:
                                <a href="{{ route('nilai.index') }}"
                                    class="btn btn-primary btn-sm font-weight-bold shadow">Kelola Nilai</a>
                                <a href="#" class="btn btn-success btn-sm font-weight-bold shadow">Cetak Rapor</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">

        </div>
    </div>
@endsection
