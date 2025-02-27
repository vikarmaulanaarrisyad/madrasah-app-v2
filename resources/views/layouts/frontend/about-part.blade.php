 <section id="about-part" class="pt-65">
     <div class="container">
         <div class="row">
             <div class="col-lg-5">
                 <div class="section-title mt-50">
                     <h5>Sambutan Kepala {{ $sekolah->nama }}</h5>
                     <h3 class="mt-2">Selamat Datang </h3>
                 </div> <!-- section title -->
                 <div class="about-cont">
                     <p>
                         {!! $sekolah->sambutan !!}
                     </p>
                 </div>
             </div> <!-- about cont -->
             <div class="col-lg-6 offset-lg-1">
                 <div class="about-event mt-50">
                     <div class="event-title">
                         <h3>Upcoming events</h3>
                     </div> <!-- event title -->
                     <ul>
                         @if ($events->isNotEmpty())
                             @foreach ($events as $event)
                                 <li>
                                     <div class="singel-event">
                                         <span><i class="fa fa-calendar"></i>
                                             {{ \Carbon\Carbon::parse($event->tanggal)->format('d F Y') }}</span>
                                         <a href="#">
                                             <h4>{{ $event->judul }}</h4>
                                         </a>
                                         <span><i class="fa fa-clock-o"></i>
                                             {{ \Carbon\Carbon::parse($event->waktu_mulai)->format('H:i A') }} -
                                             {{ \Carbon\Carbon::parse($event->waktu_selesai)->format('H:i A') }}
                                         </span>
                                         <span><i class="fa fa-map-marker"></i> {{ $event->lokasi }}</span>
                                     </div>
                                 </li>
                             @endforeach
                         @else
                             <li>Tidak ada event yang tersedia.</li>
                         @endif

                     </ul>
                 </div> <!-- about event -->
             </div>
         </div> <!-- row -->
     </div> <!-- container -->
     <div class="about-bg">
         <img src="{{ asset('education') }}/images/about/bg-1.png" alt="About">
     </div>
 </section>
