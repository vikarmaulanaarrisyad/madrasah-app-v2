@extends('layouts.front')

@section('content')
    <section id="page-banner" class="pt-50 pb-80 bg_cover" data-overlay="8"
        style="background-image: url(images/page-banner-3.jpg)">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="page-banner-cont">
                        <h2>PPDB</h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/') }}">Beranda</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ $ppdb->slug ?? 'PPDB' }}
                                </li>
                            </ol>
                        </nav>
                    </div> <!-- page banner cont -->
                </div>
            </div> <!-- row -->
        </div> <!-- container -->
    </section>

    <section id="event-singel" class="pt-40 pb-120 gray-bg">
        <div class="container">
            <div class="events-area">
                <div class="row">
                    @if ($ppdb)
                        <div class="col-lg-12">
                            <h4 class="text-primary font-weight-bold mb-2">{{ $ppdb->title }}</h4>
                            <p class="text-dark">
                                {!! $ppdb->description !!}
                            </p>
                            {{--  <p><strong>Jadwal Pendaftaran:</strong> {{ date('d M Y', strtotime($ppdb->jadwal)) }}  --}}
                            </p>
                            {{--  <p><strong>Syarat Pendaftaran:</strong></p>
                                    <ul>
                                        @foreach (explode("\n", $ppdb->syarat) as $syarat)
                                            <li>{{ $syarat }}</li>
                                        @endforeach
                                    </ul>  --}}
                        </div>
                    @else
                        <div class="col-lg-12 text-center">
                            <p class="text-muted">Informasi PPDB belum tersedia.</p>
                        </div>
                    @endif
                </div> <!-- row -->
            </div> <!-- events-area -->
        </div> <!-- container -->
    </section>
@endsection
