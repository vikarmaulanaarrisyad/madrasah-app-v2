@extends('layouts.app')

@section('title', 'Dashboard')

@section('subtitle', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $guru }}</h3>

                    <p>GTK</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-friends"></i>
                </div>
                <a href="" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $siswa }}</h3>

                    <p>Siswa</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <a href="./jam-kbm" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $rombel }}</h3>

                    <p>Ruang / Rombel</p>
                </div>
                <div class="icon">
                    <i class="fab fa-instalod"></i>
                </div>
                <a href="./ruang-rombel" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $kurikulum }}</h3>

                    <p>Kurikulum</p>
                </div>
                <div class="icon">
                    <i class="fas fa-book-open"></i>
                </div>
                <a href="./mapel" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
@endsection
