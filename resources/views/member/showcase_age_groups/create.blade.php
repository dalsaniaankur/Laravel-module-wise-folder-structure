@extends('member.layouts.app')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> @lang('quickadmin.showcase_age_groups.title_single') </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <!--------------------------
          | Your Page Content Here |
          -------------------------->
        <div class="row">
            <div class="col-md-12">
                @if (Session::has('success'))
                    <div class="alert alert-success">
                        <p>{{ Session::get('success') }}</p>
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
            </div>
        </div>
        <div class="row">
            <!-- left column -->
            <div class="col-md-9">
                <div class="nav-tabs-custom validation-tab">
                    <ul class="nav nav-tabs">
                    </ul>

                    <!-- form start -->
                    @if(isset($ageGroup))
                        {!! Form::model($ageGroup, ['method' => 'POST', 'enctype' => 'multipart/form-data', 'class'=>'form-horizontal validation-form','data-parsley-validate',
                        'route' => ['member.showcase_age_group.save']]) !!}

                        <input type="hidden" name="id" value="{{$ageGroup->age_group_id}}" />

                    @else

                        {!! Form::open(['method' => 'POST', 'enctype' => 'multipart/form-data', 'name'=>'event-form', 'class'=>'form-horizontal validation-form','data-parsley-validate','route' => ['member.showcase_age_group.save']]) !!}

                        <input type="hidden" name="id" value=""/>
                    @endif

                    <input type="hidden" name="module_id" value="{{ $module_id }}"/>

                    <div class="tab-content ">
                        <div class="tab-pane active" id="eventInfo">

                            <div class="form-group">
                                {!! Form::label('name', trans('quickadmin.showcase_age_groups.fields.name').': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('name'))
                                        <p class="help-block"> {{ $errors->first('name') }} </p>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('short_order', trans('quickadmin.showcase_age_groups.fields.short_order').': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('short_order', old('short_order'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('short_order'))
                                        <p class="help-block"> {{ $errors->first('short_order') }} </p>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('status', trans('quickadmin.showcase_age_groups.fields.status') .': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::select('status', $statusList, old('status'), ['class' => 'form-control','required' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('status'))
                                        <p class="help-block"> {{ $errors->first('status') }} </p>
                                    @endif
                                </div>
                            </div>

                            <!-- /.box -->
                            <div class="form-navigation">
                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-8">
                                        <a href="{{ route('member.showcase_age_groups.index') }}"
                                           class="btn btn-primary">@lang('quickadmin.cancel')</a>
                                        {!! Form::submit(trans('quickadmin.qa_save'), ['class' => 'btn btn-danger']) !!}
                                        <span class="clearfix"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    {!! Form::close() !!}
                    <!-- End page content-->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
    <script type="text/javascript">
        tinymceInit();
    </script>
@endsection 