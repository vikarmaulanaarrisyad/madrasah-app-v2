@extends('layouts.front')

@section('content')
    <section id="teachers-singel" class="pt-20 pb-120 gray-bg">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="teachers-right mt-20">
                        <div class="tab-content" id="myTabContent">
                            <div class="sejarah-cont">
                                <div class="singel-sejarah pt-40">
                                    <h5 class="mb-2">{{ $page->title }}</h5>
                                    <p class="text-justify">
                                        {!! $page->content !!}
                                    </p>
                                </div> <!-- singel sejarah -->
                            </div> <!-- sejarah cont -->
                        </div> <!-- tab content -->
                    </div> <!-- teachers right -->
                </div>
            </div> <!-- row -->
        </div> <!-- container -->
    </section>
@endsection


@push('css')
    <style>
        #teachers-singel {
            min-height: 50vh !important;
            /* Minimal setinggi layar */
            padding-top: 20px;
            padding-bottom: 10px;
        }
    </style>
@endpush
