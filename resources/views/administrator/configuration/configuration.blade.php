@extends('administrator.layouts.app')
@section('content')
    <section class="content-header">
        <h1>@lang('quickadmin.administrator-configuration.title')</h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <!--------------------------
          | Your Page Content Here |
          -------------------------->
        <div class="row">
            <div class="col-md-12">
                @if (Session::has('message'))
                    <div class="alert alert-info">
                        <p>{{ Session::get('message') }}</p>
                    </div>
                @endif
                @if ($errors->count() > 0)
                    <div class="alert alert-danger">
                        <ul class="list-unstyled">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
            </div>
        </div>
        <div class="row">
            <!-- Page content goes here-->
            <!-- left column -->
            <div class="col-md-9">
                <!-- general form elements -->
                <div class="box box-primary">
                    <!-- form start -->
                    {!! Form::open(['method' => 'POST', 'route' => ['administrator.configuration']]) !!}
                    <div class="box-body">
                        @if(count($prepareArray)>0)

                            @foreach($prepareArray as $key=>$value)
                                <div class="form-group">

                                    {!! Form::label($key,trans('quickadmin.administrator-configuration.fields.'.$value['key']), ['class' => 'control-label']) !!}

                                    @if($value['key'] =='default_theme')
                                        {!! Form::select($value['key'], $themes, $value['value'],['class' => 'form-control','required' => '']) !!}

                                    @elseif($value['key'] == 'default_approval_status')
                                        {!! Form::select($value['key'], $approvalStatusList, $value['value'],['class' => 'form-control','required' => '']) !!}

                                    @elseif($value['key'] == 'meta_keywords')

                                        {{ Form::textarea($value['key'], $value['value'], ['class' => 'form-control', 'placeholder' => '', 'required' => '', 'size' => '39x4']) }}

                                    @else

                                        {!! Form::text($value['key'], $value['value'], ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                                    @endif

                                    <p class="help-block"></p>
                                    @if($errors->has($key))
                                        <p class="help-block">
                                            {{ $errors->first($key)}}
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                        @else
                        @endif
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{{ url('administrator/home') }}" class="btn btn-primary">@lang('quickadmin.cancel')</a>
                        {!! Form::submit(trans('quickadmin.qa_update'), ['class' => 'btn btn-danger']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
                <!-- /.box -->
            </div>
            <!-- End page content-->
        </div>
    </section>
    <!-- /.content -->
@endsection
