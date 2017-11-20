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

            <div class="col-lg-3">
            <p class="text-center"></p>
                
            </div>

            <div class="col-lg-6">
                <h2 class="title text-center">{{ $casino[0]->name }}</h2>
                <p>{{ $casino[0]->info }}</p>
                
                <table id="cash-table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Stakes</th>
                            <th>Tables</th>
                            <th>Game</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            @foreach($cashGames as $c)
                        
                            <td>{{ $c->stakes }}</td>
                            <td>{{ $c->tables }}</td>
                            <td>{{ $c->game }}</td>
                        </tr>
                            @endforeach
                    </tbody>
                </table>

                @if($tournaments)
                 <table id="cash-table" class="table table-bordered table-hover">
                        <thead>
                            <tr>
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
                                    <td>{{ $tournament->start }}</td>
                                    <td>{{ $tournament->event }}</td>
                                    <td >{{ $tournament->buyin }}</td>
                                    <td class="hidden-xs">{{ $tournament->stack }}</td>
                                    <td class="hidden-xs">{{ $tournament->clock }}</td>
                                </tr>
                        
                            @endforeach
                        </tbody>
                            
                    </table>   
                    @endif 
            </div>

            <div class="col-lg-3">
                <p class="text-center">{{ $casino[0]->address }}</p>
                <p class="text-center"> {{ $casino[0]->postcode}}
            </div>
                
            </div>
        </div>
        <!-- End Row -->

    </div>
    <!-- //Content Section End -->

    
@stop

{{-- page level scripts --}}
@section('footer_scripts')

@stop
