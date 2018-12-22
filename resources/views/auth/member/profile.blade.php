@extends('layouts.app')
@section('content')
    <section class="page-block">
        <div class="container">
            <div class="bg-white float-left w-100">
                <div class="page-block-inner">
                    <div class="page-block-heading bg-primary custom_section">
                        <h1 class="page-block-title">CHANGE PROFILE</h1>
                    </div>
                    <div class="page-block-body d-flex">
                        <div class="page-block-left">

                            <div class="col-md-8 col-md-offset-2">
                                <div class="panel panel-default">
                                    <div class="panel-body">

                                        <div class="col-md-12">
                                            @if (Session::has('success'))
                                                <div class="alert alert-success">
                                                    <p>{{ Session::get('success') }}</p>
                                                </div>
                                            @endif
                                            @if ($errors->count() > 0)
                                                <div class="alert alert-danger"> <strong>@lang('quickadmin.qa_whoops')</strong> @lang('quickadmin.qa_there_were_problems_with_input'): <br>
                                                    <br>
                                                    <ul class="list-unstyled">
                                                        @foreach($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    {!! Form::model($member, ['method' => 'POST','class'=>'form-horizontal validation-form','data-parsley-validate', 'route' => ['member.change_profile']]) !!}

                                    <input type="hidden" name="id" value="{{$member->member_id}}" />

                                    {{ csrf_field() }}

                                    <div class="form-group"> {!! Form::label('email', trans('quickadmin.member.fields.email').': * ', ['class' => 'col-sm-3 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                                            <p class="help-block"></p>
                                            @if($errors->has('email'))
                                                <p class="help-block"> {{ $errors->first('email') }} </p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        {!! Form::label('first_name', trans('quickadmin.member.fields.first_name').': * ', ['class' => 'col-sm-3 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::text('first_name', old('first_name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '', 'data-parsley-trigger' => 'keyup', 'data-parsley-maxlength' => '100' ]) !!}
                                            <p class="help-block"></p>
                                            @if($errors->has('first_name'))
                                                <p class="help-block"> {{ $errors->first('first_name') }} </p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        {!! Form::label('last_name', trans('quickadmin.member.fields.last_name').': * ', ['class' => 'col-sm-3 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::text('last_name', old('last_name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '', 'data-parsley-trigger' => 'keyup', 'data-parsley-maxlength' => '100']) !!}
                                            <p class="help-block"></p>
                                            @if($errors->has('last_name'))
                                                <p class="help-block"> {{ $errors->first('last_name') }} </p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        {!! Form::label('description_id', trans('quickadmin.member.fields.description_id').': ', ['class' => 'col-sm-3 control-label']) !!}
                                        <div class="col-sm-8">
                                            @foreach ($description as $key => $value)
                                                @php ($is_checked = false)
                                                @if (!empty($member->description_id) && in_array($key, $member->description_id))
                                                    @php ($is_checked = true)
                                                @endif
                                                {!! Form::checkbox('description_id[]', $key, $is_checked, array('id' => 'description_'.$key)) !!}
                                                {!! Form::label('description_'.$key, $value) !!}<br>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        {!! Form::label('affiliation', trans('quickadmin.member.fields.affiliation').': ', ['class' => 'col-sm-3 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::text('affiliation', old('affiliation'), ['class' => 'form-control', 'placeholder' => '']) !!}
                                            <p class="help-block"></p>
                                            @if($errors->has('affiliation'))
                                                <p class="help-block"> {{ $errors->first('affiliation') }} </p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group"> {!! Form::label('address_1', trans('quickadmin.member.fields.address_1') .': ', ['class' => 'col-sm-3 control-label']) !!}
                                        <div class="col-sm-8"> {!! Form::text('address_1', old('address_1'), ['class' => 'form-control', 'placeholder' => '']) !!}
                                            <p class="help-block"></p>
                                            @if($errors->has('address_1'))
                                                <p class="help-block"> {{ $errors->first('address_1') }} </p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group"> {!! Form::label('address_2', trans('quickadmin.member.fields.address_2') .': ', ['class' => 'col-sm-3 control-label']) !!}
                                        <div class="col-sm-8"> {!! Form::text('address_2', old('address_2'), ['class' => 'form-control', 'placeholder' => '']) !!}
                                            <p class="help-block"></p>
                                            @if($errors->has('address_2'))
                                                <p class="help-block"> {{ $errors->first('address_2') }} </p>
                                            @endif </div>
                                    </div>

                                    <div class="form-group"> {!! Form::label('city', trans('quickadmin.member.fields.city') .': ', ['class' => 'col-sm-3 control-label']) !!}
                                        <div class="col-sm-8"> {!! Form::text('city', old('city'), ['class' => 'form-control', 'placeholder' => '']) !!}
                                            <p class="help-block"></p>
                                            @if($errors->has('city'))
                                                <p class="help-block"> {{ $errors->first('city') }} </p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group"> {!! Form::label('state', trans('quickadmin.member.fields.state') .': ', ['class' => 'col-sm-3 control-label']) !!}
                                        <div class="col-sm-8"> {!! Form::select('state_id', $state, old('state_id'), ['class' => 'form-control']) !!}
                                            <p class="help-block"></p>
                                        </div>

                                    </div>

                                    <div class="form-group"> {!! Form::label('zip', trans('quickadmin.member.fields.zip').': ', ['class' => 'col-sm-3 control-label']) !!}
                                        <div class="col-sm-8"> {!! Form::text('zip', old('zip'), ['class' => 'form-control','data-parsley-type'=>'digits', 'placeholder' => '', 'data-parsley-trigger' => 'keyup', 'data-parsley-minlength' => '5', 'data-parsley-maxlength' => '5']) !!}

                                            <p class="help-block"></p>
                                            @if($errors->has('zip'))
                                                <p class="help-block"> {{ $errors->first('zip') }} </p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group"> {!! Form::label('phone', trans('quickadmin.member.fields.phone').': ', ['class' => 'col-sm-3 control-label']) !!}
                                        <div class="col-sm-8"> {!! Form::text('phone', old('phone'), ['class' => 'form-control', 'placeholder' => '']) !!}
                                            <p class="help-block"></p>
                                            @if($errors->has('phone'))
                                                <p class="help-block"> {{ $errors->first('phone') }} </p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-8 col-md-offset-4">
                                            {!! Form::submit(trans('quickadmin.qa_pudate_profile'), ['class' => 'btn btn-primary']) !!}
                                        </div>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection