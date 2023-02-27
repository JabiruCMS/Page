@extends('layouts.master', ['disable_vue' => true])

@section('content-header')
    <h1>
        {{ trans('page::pages.tree') }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
        <li class="active">{{ trans('page::pages.tree') }}</li>
    </ol>

    <style>
        .btn-group {margin: 0px; position: relative; top: -8px;}
    </style>
@stop

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="btn-group pull-right" style="margin: 0 15px 15px 0;">

                </div>
            </div>
            <div class="box box-primary">
                <div class="box-header">
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="box box-primary" style="overflow: hidden;">
                        <div class="box-body">
                            {!! $pageStructure !!}
                        </div>
                    </div>

                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </div>
@stop

@section('footer')
@stop

@push('css-stack')
    <link href="{!! Module::asset('menu:css/nestable.css') !!}" rel="stylesheet" type="text/css" />
@endpush

@push('js-stack')
    <script src="{!! Module::asset('menu:js/jquery.nestable.js') !!}"></script>
    <script>
        $( document ).ready(function() {
            $('.dd').nestable();
            $('.dd').on('change', function() {
                var data = $('.dd').nestable('serialize');
                $.ajax({
                    type: 'PUT',
                    url: '{{ route('api.page.page.sort') }}',
                    data: {
                        'pages': JSON.stringify(data),
                        '_token': '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(data) {

                    },
                    error:function (xhr, ajaxOptions, thrownError){
                        alert('ERROR');
                    }
                });
            });
        });
    </script>
@endpush

