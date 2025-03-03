@extends('layouts.front')


@section('content')
    <!--====== SLIDER PART START ======-->

    @include('layouts.frontend.slider-part')

    <!--====== SLIDER PART ENDS ======-->

    <!--====== CATEGORY PART START ======-->

    @include('layouts.frontend.category-part')

    <!--====== CATEGORY PART ENDS ======-->

    <!--====== ABOUT PART START ======-->

    @include('layouts.frontend.about-part')

    <!--====== ABOUT PART ENDS ======-->

    <!--====== APPLY PART START ======-->

    @include('layouts.frontend.apply-aprt')

    <!--====== APPLY PART ENDS ======-->

    <!--====== COURSE PART START ======-->

    {{--  @include('layouts.frontend.course-part')  --}}

    <!--====== COURSE PART ENDS ======-->

    <!--====== VIDEO FEATURE PART START ======-->
    @include('layouts.frontend.video-feature')
    <!--====== VIDEO FEATURE PART ENDS ======-->

    <!--====== TEACHERS PART START ======-->

    {{--  @include('layouts.frontend.teachers-part')  --}}

    <!--====== TEACHERS PART ENDS ======-->

    <!--====== TEASTIMONIAL PART START ======-->


    <!--====== TEASTIMONIAL PART ENDS ======-->

    <!--====== NEWS PART START ======-->

    @include('layouts.frontend.news-part')

    <!--====== NEWS PART ENDS ======-->

    <!--====== PATNAR LOGO PART START ======-->

    <div id="patnar-logo" class="pt-40 pb-80 gray-bg" style="display: none;">
        <div class="container">
            <div class="row patnar-slied">
                <div class="col-lg-12">
                    <div class="singel-patnar text-center mt-40">
                        <img src="{{ asset('education') }}/images/patnar-logo/p-1.png" alt="Logo">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="singel-patnar text-center mt-40">
                        <img src="{{ asset('education') }}/images/patnar-logo/p-2.png" alt="Logo">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="singel-patnar text-center mt-40">
                        <img src="{{ asset('education') }}/images/patnar-logo/p-3.png" alt="Logo">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="singel-patnar text-center mt-40">
                        <img src="{{ asset('education') }}/images/patnar-logo/p-4.png" alt="Logo">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="singel-patnar text-center mt-40">
                        <img src="{{ asset('education') }}/images/patnar-logo/p-2.png" alt="Logo">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="singel-patnar text-center mt-40">
                        <img src="{{ asset('education') }}/images/patnar-logo/p-3.png" alt="Logo">
                    </div>
                </div>
            </div> <!-- row -->
        </div> <!-- container -->
    </div>
@endsection
