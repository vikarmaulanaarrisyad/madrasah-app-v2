@extends('layouts.front')

@section('content')
    <section id="page-banner" class="pt-50 pb-80 bg_cover" data-overlay="8"
        style="background-image: url(images/page-banner-3.jpg)">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="page-banner-cont">
                        <h2>Events</h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/') }}">Beranda</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Events</li>
                            </ol>
                        </nav>
                    </div> <!-- page banner cont -->
                </div>
            </div> <!-- row -->
        </div> <!-- container -->
    </section>

    <section id="event-page" class="pt-50 pb-120 gray-bg">
        <div class="container">
            <div class="row">
                @if ($eventList->isNotEmpty())
                    @foreach ($eventList as $item)
                        <div class="col-lg-6">
                            <div class="singel-event-list mt-10">
                                <div class="event-thum">
                                    <img src="{{ Storage::url($item->image) }}" alt="Event">
                                </div>
                                <div class="event-cont">
                                    <span><i class="fa fa-calendar"></i> {{ tanggal_indonesia($item->tanggal) }}</span>
                                    <a href="{{ route('front.event_detail', $item->slug) }}">
                                        <h4>{{ $item->judul }}</h4>
                                    </a>
                                    <span><i class="fa fa-clock-o"></i>
                                        {{ \Carbon\Carbon::parse($item->waktu_mulai)->format('H:i A') }} -
                                        {{ \Carbon\Carbon::parse($item->waktu_selesai)->format('H:i A') }}</span>
                                    <span><i class="fa fa-map-marker"></i> {{ $item->lokasi }}</span>
                                    <p>{!! Str::limit($item->deskripsi, 120, '...') !!}</p>

                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div> <!-- row -->
            <div class="row">
                <div class="col-lg-12">
                    <nav class="courses-pagination mt-50">
                        <ul class="pagination justify-content-center">
                            {{-- Tombol Previous --}}
                            <li class="page-item {{ $eventList->onFirstPage() ? 'disabled' : '' }}">
                                <a href="{{ $eventList->previousPageUrl() }}" aria-label="Previous">
                                    <i class="fa fa-angle-left"></i>
                                </a>
                            </li>

                            {{-- Nomor Halaman --}}
                            @for ($i = 1; $i <= $eventList->lastPage(); $i++)
                                <li class="page-item {{ $i == $eventList->currentPage() ? 'active' : '' }}">
                                    <a href="{{ $eventList->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor

                            {{-- Tombol Next --}}
                            <li class="page-item {{ $eventList->hasMorePages() ? '' : 'disabled' }}">
                                <a href="{{ $eventList->nextPageUrl() }}" aria-label="Next">
                                    <i class="fa fa-angle-right"></i>
                                </a>
                            </li>
                        </ul>
                    </nav> <!-- courses pagination -->
                </div>
            </div> <!-- row -->

        </div> <!-- container -->
    </section>
@endsection
