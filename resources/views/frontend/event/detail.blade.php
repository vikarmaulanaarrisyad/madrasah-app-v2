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
                                <li class="breadcrumb-item" aria-current="page"><a
                                        href="{{ route('front.event_index') }}">Events</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $eventDetail->slug }}</li>
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
                    <div class="col-lg-8">
                        <div class="events-left">
                            <h3>{{ $eventDetail->judul }}</h3>
                            <a href="#"><span><i
                                        class="fa fa-calendar"></i>{{ tanggal_indonesia($eventDetail->tanggal) }}</span></a>
                            <a href="#"><span><i class="fa fa-clock-o"></i>
                                    {{ \Carbon\Carbon::parse($eventDetail->waktu_mulai)->format('H:i A') }} -
                                    {{ \Carbon\Carbon::parse($eventDetail->waktu_selesai)->format('H:i A') }}
                                </span></a>
                            <a href="#"><span><i class="fa fa-map-marker"></i> {{ $eventDetail->lokasi }}</span></a>
                            <img src="{{ Storage::url($eventDetail->image) }}" alt="Event">
                            <p class="text-justify">
                                {!! $eventDetail->deskripsi !!}
                            </p>
                        </div> <!-- events left -->
                    </div>
                    <div class="col-lg-4">
                        <div class="events-right">
                            <div class="events-coundwon bg_cover" data-overlay="8"
                                style="background-image: url(images/event/singel-event/coundown.jpg)">
                                {{--  <div data-countdown="2020/03/12">
                                    <div class="count-down-time">
                                        <div class="singel-count"><span class="number">00 :</span><span
                                                class="title">Days</span></div>
                                        <div class="singel-count"><span class="number">00 :</span><span
                                                class="title">Hours</span></div>
                                        <div class="singel-count"><span class="number">00 :</span><span
                                                class="title">Minuits</span></div>
                                        <div class="singel-count"><span class="number">00</span><span
                                                class="title">Seconds</span></div>
                                    </div>
                                </div>  --}}

                                <div class="countdown-container"
                                    data-datetime="{{ \Carbon\Carbon::parse($eventDetail->tanggal . ' ' . $eventDetail->waktu_mulai)->format('Y-m-d H:i:s') }}">
                                    <div class="count-down-time">
                                        <div class="singel-count"><span class="number hours">00</span> <span
                                                class="title">Hours</span></div>
                                        <div class="singel-count"><span class="number minutes">00</span> <span
                                                class="title">Minutes</span></div>
                                        <div class="singel-count"><span class="number seconds">00</span> <span
                                                class="title">Seconds</span></div>
                                    </div>
                                </div>

                            </div> <!-- events coundwon -->
                            <div class="events-address mt-30">
                                <ul>
                                    <li>
                                        <div class="singel-address">
                                            <div class="icon">
                                                <i class="fa fa-clock-o"></i>
                                            </div>
                                            <div class="cont">
                                                <h6>Waktu Mulai</h6>
                                                <span>
                                                    {{ \Carbon\Carbon::parse($eventDetail->waktu_mulai)->format('H:i A') }}</span>
                                            </div>
                                        </div> <!-- singel address -->
                                    </li>
                                    <li>
                                        <div class="singel-address">
                                            <div class="icon">
                                                <i class="fa fa-bell-slash"></i>
                                            </div>
                                            <div class="cont">
                                                <h6>Waktu Selesai</h6>
                                                <span>
                                                    {{ \Carbon\Carbon::parse($eventDetail->waktu_selesai)->format('H:i A') }}
                                                </span>
                                            </div>
                                        </div> <!-- singel address -->
                                    </li>
                                    <li>
                                        <div class="singel-address">
                                            <div class="icon">
                                                <i class="fa fa-map"></i>
                                            </div>
                                            <div class="cont">
                                                <h6>Lokasi</h6>
                                                <span>{{ $eventDetail->lokasi }}</span>
                                            </div>
                                        </div> <!-- singel address -->
                                    </li>
                                </ul>
                            </div> <!-- events address -->
                        </div> <!-- events right -->
                    </div>
                </div> <!-- row -->
            </div> <!-- events-area -->
        </div> <!-- container -->
    </section>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function updateCountdown() {
                document.querySelectorAll('.countdown-container').forEach(function(el) {
                    let eventDateTime = el.getAttribute('data-datetime'); // Format: YYYY-MM-DD HH:mm:ss
                    let now = moment();
                    let eventMoment = moment(eventDateTime, "YYYY-MM-DD HH:mm:ss");
                    let distance = eventMoment.diff(now);

                    if (distance > 0) {
                        let totalHours = Math.floor(moment.duration(distance).asHours());
                        let minutes = moment.duration(distance).minutes();
                        let seconds = moment.duration(distance).seconds();

                        el.querySelector('.hours').textContent = totalHours.toString().padStart(2, '0');
                        el.querySelector('.minutes').textContent = minutes.toString().padStart(2, '0');
                        el.querySelector('.seconds').textContent = seconds.toString().padStart(2, '0');
                    } else {
                        el.innerHTML = "<span style='color: red;'>Event Telah Berakhir</span>";
                    }
                });
            }

            setInterval(updateCountdown, 1000);
            updateCountdown();
        });
    </script>
@endpush
