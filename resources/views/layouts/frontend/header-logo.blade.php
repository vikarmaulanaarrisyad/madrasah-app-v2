     <div class="header-logo-support pt-30 pb-30">
         <div class="container">
             <div class="row">
                 <div class="col-lg-4 col-md-4">
                     <div class="logo">
                         <a href="{{ url('/') }}">
                             {{--  <img src="{{ asset('images/logo-frontend.jpg') }}" alt="Logo" width="55%">  --}}
                             <img src="{{ Storage::url($sekolah->logo) }}" alt="Logo" width="55%">
                         </a>
                     </div>
                 </div>
                 <div class="col-lg-8 col-md-8">
                     <div class="support-button float-right d-none d-md-block">
                         <div class="support float-left">
                             <div class="icon">
                                 <img src="{{ asset('education') }}/images/all-icon/support.png" alt="icon">
                             </div>
                             <div class="cont">
                                 <p>Butuh Bantuan? Hubungi</p>
                                 <span>{{ $sekolah->notelp }}</span>
                             </div>
                         </div>
                     </div>
                 </div>
             </div> <!-- row -->
         </div> <!-- container -->
     </div>
