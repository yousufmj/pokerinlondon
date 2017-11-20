@extends('admin/layouts/admin-main')

{{-- Page title --}}
@section('title')
    Manage All Cash Games
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/pages/tables.css') }}" />
@stop

{{-- Page content --}}
@section('content')

    <section class="content-header">
        <h1>Manage All Cash Games</h1>
    </section>

    <section class="content">
        <div class="row ">
                <div class="col-md-12">
                <div class="portlet box default">

                {{-- Table Header --}}
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="livicon" data-name="edit" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                            Current Games
                        </div>
                    </div>

                    <div class="portlet-body">
                        <div class="table-toolbar">
                            <div class="btn-group">
                                <button id="sample_editable_1_new" class=" btn btn-custom">
                                    Add New
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                                     
                        </div>
                            {{ Form::open(array('url' => 'admin/cash/edit')) }}
                                <input type="submit" class="btn btn-primary" value="Update">
                                
                                    <table class="table table-striped table-bordered table-hover no-footer" role="grid">
                                        <thead>
                                            <tr role="row">
                                                <th rowspan="1" colspan="1">Casino</th>
                                                <th rowspan="1" colspan="1" style="width: 222px;">Stakes</th>
                                                <th rowspan="1" colspan="1" style="width: 124px;">Table</th>
                                                <th rowspan="1" colspan="1" style="width: 152px;">Games</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php $i = 1; ?>
                                        @foreach($cash as $c)
                                        <?php  $type = $i . '[]'; ?>
                                            <tr role="row" class="odd">
                                                <td class="sorting_1">{{ Form::text($type, $c->name) }}</td>
                                                <td>{{  Form::text($type,$c->stakes) }}</td>
                                                <td>{{  Form::text($type,$c->tables) }}</td>
                                                <td>{{  Form::text($type,$c->game) }}</td>
                                            </tr>
                                            <?php $i++ ?>
                                        @endforeach   

                                        </tbody>
                                    </table>
                                   
                            {{ Form::close() }} 
                </div>

                </div>
        </div>
    </section>

@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.responsive.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/select2/js/select2.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('assets/js/pages/table-editable.js') }}" ></script>
@stop
