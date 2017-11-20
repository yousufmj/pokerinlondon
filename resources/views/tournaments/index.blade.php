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

            <div class="col-lg-2">
                
            </div>
        
            <div class="col-lg-8">
                <h2 class=" hidden-xs title">
                    This Weeks Tournaments : {{ $weekStart->format('d M') }} - {{ $weekEnd->format('d M') }}
                </h2>

                <div class="padding-bottom"></div>
                <h2 class="visible-xs title">This Weeks Tournaments </h2>
                <h3 class="visible-xs sub-title">{{ $weekStart->format('d M') }} - {{ $weekEnd->format('d M') }}</h2>
                
                <div class="panel-body">
                    {{ Form::open(['route' => 'tournaments-main', 'method' => 'get']) }}
                        <div class="col-lg-3 col-sm-12">
                            <h5>Select a date range: </h5>
                        </div>
                        <div class="col-lg-4 col-sm-12">
                            {{ Form::date('date_from', $weekStart, ['class' => 'form-control']) }}
                        </div>
                        <div class="col-lg-4 col-sm-12">
                            {{ Form::date('date_to', $weekEnd, ['class' => 'form-control']) }}
                        </div>
                        <div class="col-lg-1 col-sm-12">
                            {{ Form::submit('Search', ['class' => 'btn btn-responsive btn-primary btn-sm']) }}
                        </div>
                    {{ Form::close() }}
                </div>
                @if($tournaments)
                

                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Casino</th>
                                <th>Date</th>
                                <th>Event</th>
                                <th>Buy In</th>
                            </tr>
                        </thead>

                        <tbody>
                        
                     
                            @foreach($tournaments as $tournament)

                                <tr>
                                    <td>{{ $tournament->casino }}</td>
                                    <td><?=date('D j M',strtotime($tournament->date))?></td>
                                    <td>{{ $tournament->event }}</td>
                                    <td>{{ $tournament->buyin }}</td>
                                </tr>
                        
                            @endforeach
                        </tbody>
                            
                    </table>
                    <!-- End tournaments table -->

                @endif   
            </div>  
        </div>
        <!-- End Row -->

    </div>
    <!-- //Content Section End -->

    
@stop

{{-- page level scripts --}}
@section('footer_scripts')

@stop
