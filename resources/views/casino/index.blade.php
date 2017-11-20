@extends('layouts/main')

{{-- Page title --}}
@section('title')
Poker Today
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
@stop

{{-- Page content --}}
@section('content')
    <!-- Container Section Start -->
    <div class="container">
        <!--Content Section Start -->

        <div class="row">
            <div class="col-lg-2"></div>

            <div class="col-lg-8">

                <h2 class="title">London Poker Rooms</h2>

                @foreach($casinos as $casino)
                <div class="col-lg-6">
                    
                    <div class="panel casino-tabs">
                        <div class="panel-heading text-center">
                            <h3 class="sub-title">
                                <a href="{{ URL::to('casino/'.$casino->url) }}">
                                    {{ $casino->name }}
                                </a>
                                
                            <h3>
                        </div>

                        <div class="panel-body panel-poker text-center">
                            <a href="{{ URL::to('casino/'.$casino->url) }}">
                                <img  class="casino-logo" src="{{ asset('assets/img/casino-logos/'.$casino->url.'.jpg') }}" alt="{{ $casino->name }} Logo">
                            </a>

                            <ul class="list-inline social-tab">
                                <li>
                                    <a href="{{ $casino->facebook }}"> <i class="livicon" data-name="facebook" data-size="28" data-loop="true"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ $casino->twitter }}"> <i class="livicon" data-name="twitter" data-size="28" data-loop="true" ></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ $casino->website }}"> <i class="livicon" data-name="chrome" data-size="28" data-loop="true" ></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div> <!-- end casino tabs -->
                </div>
                @endforeach

            </div>

        </div><!-- End Row -->

    </div><!-- //Content Section End -->

    
@stop

{{-- page level scripts --}}
@section('footer_scripts')

@stop
