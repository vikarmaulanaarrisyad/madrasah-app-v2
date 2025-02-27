@extends('layouts.front')

@section('content')
    <section id="page-banner" class="pt-50 pb-40 bg_cover" data-overlay="8"
        style="background-image: url(images/page-banner-4.jpg)">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="page-banner-cont">
                        <h2>Artikel</h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/') }}">Beranda</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Artikel</li>
                            </ol>
                        </nav>
                    </div> <!-- page banner cont -->
                </div>
            </div> <!-- row -->
        </div> <!-- container -->
    </section>

    <section id="blog-page" class="pt-40 pb-120 gray-bg">
        <div class="container">
            <div class="row">
                @if ($artikelList->isNotEmpty())
                    @foreach ($artikelList as $item)
                        <div class="col-lg-4">
                            <div class="singel-blog mt-20">
                                <div class="blog-thum">
                                    <img src="{{ Storage::url($item->image) }}" alt="Blog" style="height:200px">
                                </div>
                                <div class="blog-cont">
                                    <a href="{{ route('front.artikel_detail', $item->slug) }}">
                                        <h5>{{ Str::limit($item->judul, 50, '...') }}</h5>
                                    </a>
                                    <ul>
                                        <li><a href="#"><i
                                                    class="fa fa-calendar"></i>{{ tanggal_indonesia($item->tgl_publish) }}</a>
                                        </li>
                                        <li><a href="#"><i class="fa fa-user"></i>By Admin</a></li>
                                        <li><a href="#"><i class="fa fa-tags"></i>{{ $item->kategori->nama }}</a></li>
                                    </ul>
                                    <p>
                                        {!! Str::limit(strip_tags($item->content), 200) !!}
                                    </p>
                                </div>
                            </div> <!-- singel blog -->
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <nav class="courses-pagination mt-50">
                        <ul class="pagination justify-content-center">
                            {{-- Tombol Previous --}}
                            <li class="page-item {{ $artikelList->onFirstPage() ? 'disabled' : '' }}">
                                <a href="{{ $artikelList->previousPageUrl() }}" aria-label="Previous">
                                    <i class="fa fa-angle-left"></i>
                                </a>
                            </li>

                            {{-- Nomor Halaman --}}
                            @for ($i = 1; $i <= $artikelList->lastPage(); $i++)
                                <li class="page-item {{ $i == $artikelList->currentPage() ? 'active' : '' }}">
                                    <a class=" {{ $i == $artikelList->currentPage() ? 'active' : '' }}"
                                        href="{{ $artikelList->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor

                            {{-- Tombol Next --}}
                            <li class="page-item {{ $artikelList->hasMorePages() ? '' : 'disabled' }}">
                                <a href="{{ $artikelList->nextPageUrl() }}" aria-label="Next">
                                    <i class="fa fa-angle-right"></i>
                                </a>
                            </li>
                        </ul>
                    </nav> <!-- courses pagination -->
                </div>
            </div>
            <!-- row -->
        </div> <!-- container -->
    </section>
@endsection

@push('css')
    <style>
        .singel-blog .blog-cont {
            padding: 20px 20px !important;
            background-color: #fff;
            text-align: justify;
        }
    </style>
@endpush
