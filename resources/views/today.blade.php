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
        
        <!---------------
        Cash game table 
        ------------------>
        <div class="row">

            <div class="col-lg-2">
                
            </div>
        
            <div class="col-lg-8">
                <h2 class="title">Poker on {{ date('l j M') }}</h2>
                <h3 class="sub-title">Cash Games</h3>

                @if($games)
                
                    <table id="cash-table" class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-left">Casnio</th>
                                <th>Stakes</th>
                                <th>Tables</th>
                                <th>Game</th>
                            </tr>
                        </thead>

                        <tbody>
                                
                                @if(count($aspers) > 0)
                                    <tr>
                                        <td class ="cash-casino" rowspan="{{ count($aspers) }}">
                                            <a href="{{ $aspers[0]->url }}" target="_blank">
                                                <img  class="hidden-xs" src="{{ asset('assets/img/Twitter.png') }}" width ="32" alt="">
                                            </a>
                                            Aspers
                                            @if(isset($results['aspers_updated']) && $results['aspers_updated'] >= 3)
                                               <br><span class="red">last update:{{$results['aspers_updated']}} hours ago</span>
                                            @endif
                                        </td>
                                        @foreach($aspers as $a)
                                        
                                            <td>{{ $a->stakes }}</td>
                                            <td>{{ $a->tables }}</td>
                                            <td>{{ $a->game }}</td>
                                    </tr>
                                        @endforeach
                                @else
                                    <tr>
                                        <td class ="cash-casino">Aspers</td>
                                        <td colspan="3">No Updates Today</td>
                                    </tr>
                                @endif

                                @if(count($empire) > 0)
                                    <tr>
                                        <td class ="cash-casino" rowspan="{{ count($empire) }}">
                                            
                                            <a href="{{ $empire[0]->url }}" target="_blank">
                                                <img class="hidden-xs" src="{{ asset('assets/img/Twitter.png') }}" width ="32" alt="">
                                            </a>
                                            Empire
                                            @if(isset($results['empire_updated']) && $results['empire_updated'] >= 3)
                                                <br><span class="red">last update: {{$results['empire_updated']}} hours ago</span>
                                            @endif
                                        </td>
                                        @foreach($empire as $e)
                                            <td>{{ $e->stakes }}</td>
                                            <td>{{ $e->tables }}</td>
                                            <td>{{ $e->game }}</td>
                                    </tr>
                                        @endforeach

                                @else
                                    <tr>
                                        <td class ="cash-casino" >Empire</td>
                                        <td colspan="3">No Updates Today</td>
                                    <tr>
                                @endif

                       

                                @if(count($hippo) > 0)
                                    <tr>
                                        <td class ="cash-casino" rowspan="{{ count($hippo) }}">
                                            
                                            <a href="{{ $hippo[0]->url }}" target="_blank">
                                                <img class="hidden-xs" src="{{ asset('assets/img/Twitter.png') }}" width ="32" alt="">
                                            </a>
                                            Hippodrome
                                            @if(isset($results['hippo_updated']) && $results['hippo_updated'] >= 3)
                                                <br><span class="red">last update: {{$results['hippo_updated']}} hours ago </span>
                                            @endif
                                        </td>
                                        @foreach($hippo as $h)
                                            <td>{{ $h->stakes }}</td>
                                            <td>{{ $h->tables }}</td>
                                            <td>{{ $h->game }}</td>
                                    </tr>   
                                        @endforeach
                                @else
                                    <tr>
                                        <td class ="cash-casino" >Hippodrome</td>
                                        <td colspan="3">No Updates Today</td>
                                    </tr>
                                @endif

                                @if(count($vic) > 0)
                                    <tr>
                                        <td class ="cash-casino" rowspan="{{ count($vic) }}">
                                            
                                            <a href="{{ $vic[0]->url }}" target="_blank">
                                                <img class="hidden-xs" src="{{ asset('assets/img/Twitter.png') }}" width ="32" alt="">
                                            </a>
                                            The Vic
                                             @if(isset($results['vic_updated']) && $results['vic_updated'] >= 3)
                                                <br><span class="red">last update: {{$results['vic_updated']}} hours ago</span>
                                            @endif
                                        </td>
                                        @foreach($vic as $v)
                                            <td>{{ $v->stakes }}</td>
                                            <td>{{ $v->tables }}</td>
                                            <td>{{ $v->game }}</td>
                                    </tr>
                                        @endforeach
                                @else
                                    <tr>
                                        <td class ="cash-casino">The Vic</td>
                                        <td colspan="3">No Updates Today</td>
                                    </tr>
                                @endif
                            </tr>

                        </tbody>
                            
                    </table>
                    <!-- End cash game table -->
                    <div class="pull-right"><small>*Updates are based off casino's latest tweets</small></div>

                @endif   
            </div>  
        </div>

        <!----------------------
            Tournamen section
         ---------------------->
        <div class="row">  

            <div class="col-lg-2">
            </div>   
            
            <div class="col-lg-8">
                <h3 class="sub-title">Tournaments</h3>

                @if($tournaments)
                

                    <table id="cash-table" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-left">Casnio</th>
                                <th>Start Time</th>
                                <th>Event</th>
                                <th>Buy In</th>
                                <th class="hidden-xs">Starting Stack</th>
                                <th class="hidden-xs">Clock</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($tournaments as $tournament)
                                <tr>
                                    <td class="cash-casino">{{ $tournament->casino }}</td>
                                    <td>{{ $tournament->start }}</td>
                                    <td>{{ $tournament->event }}</td>
                                    <td >{{ $tournament->buyin }}</td>
                                    <td class="hidden-xs">{{ $tournament->stack }}</td>
                                    <td class="hidden-xs">{{ $tournament->clock }}</td>
                                </tr>
                        
                            @endforeach
                        </tbody>
                            
                    </table>
                    <!-- End tournaments table -->

                @endif   
            </div>
            <!-- End column -->
        </div>
        <!-- End row -->

        </div>
        <!-- //Content Section End -->
    </div>
    
@stop

{{-- page level scripts --}}
@section('footer_scripts')

@stop
