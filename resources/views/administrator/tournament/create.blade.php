@extends('administrator.layouts.app')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> @lang('quickadmin.tournament.title_single') </h1>
    </section>
    <!-- Main content -->
    <section class="content">
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
                <div class="nav-tabs-custom validation-tab" >
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#teamsInfo" data-toggle="tab">{{trans('quickadmin.tournament-tab.basic-info')}}</a>
                        </li>
                        <li>
                            <a href="#extra_details" data-toggle="tab">{{trans('quickadmin.tournament-tab.extra-details')}}</a>
                        </li>
                    </ul>
                    <!-- form start -->
                    @if(isset($tournament))
                        {!! Form::model($tournament, ['method' => 'POST', 'enctype' => 'multipart/form-data', 'class'=>'form-horizontal validation-form','data-parsley-validate', 'url' => 'administrator/tournament/save/'.$tournament_organization_id ]) !!}

                        <input type="hidden" name="id" value="{{$tournament->tournament_id}}" />
                    @else
                        {!! Form::open(['method' => 'POST', 'enctype' => 'multipart/form-data', 'name'=>'team-form', 'class'=>'form-horizontal validation-form','data-parsley-validate', 'url' => 'administrator/tournament/save/'.$tournament_organization_id]) !!}
                        <input type="hidden" name="id" value="" />
                    @endif

                    <div class="tab-content ">
                        <div class="tab-pane active" id="teamsInfo">
                            <div class="form-group"> {!! Form::label('submitted_by_id', trans('quickadmin.tournament.fields.submitted_by_id') .': ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8"> {!! Form::select('submitted_by_id', $member, old('submitted_by_id'), ['class' => 'form-control']) !!}
                                    <p class="help-block"></p>
                                </div>
                            </div>

                            <div class="form-group"> {!! Form::label('tournament_organization_id', trans('quickadmin.tournament.fields.organizer_name') .': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8"> {!! Form::select('tournament_organization_id', $tournamentOrganization, old('tournament_organization_id',$tournament_organization_id), ['class' => 'form-control']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('tournament_organization_id'))
                                        <p class="help-block"> {{ $errors->first('tournament_organization_id') }} </p>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('tournament_name', trans('quickadmin.tournament.fields.tournament_name').': ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('tournament_name', old('tournament_name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('tournament_name'))
                                        <p class="help-block"> {{ $errors->first('tournament_name') }} </p>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('competition_level_id', trans('quickadmin.tournament.fields.competition_level_id').':', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::select('competition_level_id', $competition_Level_list, old('competition_level_id') ,array('multiple'=>'multiple','name'=>'competition_level_id[]', "size" => count($competition_Level_list), 'class' => 'form-control')) !!}
                                    <p class="help-block"></p>
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('contact_name', trans('quickadmin.tournament.fields.contact_name').': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('contact_name', old('contact_name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('contact_name'))
                                        <p class="help-block"> {{ $errors->first('contact_name') }} </p>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('date', trans('quickadmin.tournament.fields.date').': ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('dates', old('dates'), ['class' => 'form-control', 'id'=>'date_rang' ,'placeholder' => '','autocomplete' => 'off']) !!}
                                    <p class="help-block"></p>
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('address_1', trans('quickadmin.tournament.fields.address_1').': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('address_1', old('address_1'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('address_1'))
                                        <p class="help-block"> {{ $errors->first('address_1') }} </p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('address_2', trans('quickadmin.tournament.fields.address_2').':  ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('address_2', old('address_2'), ['class' => 'form-control', 'placeholder' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('address_2'))
                                        <p class="help-block"> {{ $errors->first('address_2') }} </p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('stadium_or_field_name', trans('quickadmin.tournament.fields.stadium_or_field_name').': *', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('stadium_or_field_name', old('stadium_or_field_name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('stadium_or_field_name'))
                                        <p class="help-block"> {{ $errors->first('stadium_or_field_name') }} </p>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('state_id', trans('quickadmin.tournament.fields.state_id') .': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::select('state_id', $state, old('state_id'), ['class' => 'form-control','onchange' => "getCityDropDown()", 'id' => 'state_id', 'required' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('state_id'))
                                        <p class="help-block"> {{ $errors->first('state_id') }} </p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('city_id', trans('quickadmin.tournament.fields.city') .': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8 city">
                                    {!! Form::select('city_id', $city, old('city_id'), ['id' => 'city_id', 'class' => 'form-control', 'required' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('city_id'))
                                        <p class="help-block"> {{ $errors->first('city_id') }} </p>
                                    @endif
                                    <div class="custom-city-error-message"><span>@lang('quickadmin.qa_city_custom_error_msg')</span></div>
                                </div>
                            </div>


                            <div class="form-group">
                                {!! Form::label('url_key', trans('quickadmin.tournament.fields.url_key').': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('url_key', old('url_key'), ['class' => 'form-control', 'placeholder' => '', 'required' => '', 'data-parsley-pattern' => '^[a-z0-9-]*$' ]) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('url_key'))
                                        <p class="help-block"> {{ $errors->first('url_key') }} </p>
                                    @endif
                                    <div class="alert alert-warning url-key-info-block"> <strong>Info!</strong> {{ trans('quickadmin.help.url_key') }} </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-8">
                                    <button type="button" id="get_google_longitude_latitude" class="btn btn-primary" onclick="generateUrlKey(['tournament_name','city_id','state_id','date_rang']);">@lang('quickadmin.btn_generate_url_key') </button>
                                    <p class="help-block"></p>
                                </div>
                            </div>

                            <div class="form-group"> {!! Form::label('zip', trans('quickadmin.tournament.fields.zip').': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8"> {!! Form::text('zip', old('zip'), ['class' => 'form-control','data-parsley-type'=>'digits', 'data-parsley-trigger' => 'keyup', 'placeholder' => '', 'data-parsley-minlength' => '5', 'data-parsley-maxlength' => '5', 'required' => '']) !!}

                                    <p class="help-block"></p>
                                    @if($errors->has('zip'))
                                        <p class="help-block"> {{ $errors->first('zip') }} </p>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group"> {!! Form::label('guaranteed_games', trans('quickadmin.tournament.fields.guaranteed_games') .': ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8"> {!! Form::select('guaranteed_games', $guaranteedGamesList, old('guaranteed_games'), ['class' => 'form-control']) !!}
                                    <p class="help-block"></p>
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('hotel_required', trans('quickadmin.tournament.fields.hotel_required').': ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::checkbox('hotel_required', null,  old('hotel_required'), array('id' => 'hotel_required')) !!}
                                </div>
                            </div>

                        </div>
                        <div class="tab-pane" id="extra_details">

                            <div class="form-group">
                                {!! Form::label('phone_number', trans('quickadmin.tournament.fields.phone_number').': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8"> {!! Form::text('phone_number', old('phone_number'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('phone_number'))
                                        <p class="help-block"> {{ $errors->first('phone_number') }} </p>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group"> {!! Form::label('email', trans('quickadmin.tournament.fields.email').': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('longitude', trans('quickadmin.tournament.fields.longitude').': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('longitude', old('longitude'), ['class' => 'form-control', 'id' => 'longitude', 'placeholder' => '','required' => '']) !!}
                                    <p class="help-block"></p>
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('latitude', trans('quickadmin.tournament.fields.latitude').': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('latitude', old('latitude'), ['class' => 'form-control', 'id' => 'latitude', 'placeholder' => '','required' => '']) !!}
                                    <p class="help-block"></p>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-8">
                                    <button type="button" id="get_google_longitude_latitude" class="btn btn-primary" onclick='getGoogleLongitudeLatitude();'>@lang('quickadmin.btn_get_google_longitude_latitude') </button>
                                    <p class="help-block"></p>
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('event_website_url', trans('quickadmin.tournament.fields.event_website_url').': * ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('event_website_url', old('event_website_url'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('event_website_url'))
                                        <p class="help-block"> {{ $errors->first('event_website_url') }} </p>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('age_group_id', trans('quickadmin.tournament.fields.age_group_id').': *', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    @foreach ($agegroup as $key => $value)
                                        @php ($is_checked = false)
                                        @if (!empty($tournament->age_group_id) && in_array($key, $tournament->age_group_id))
                                            @php ($is_checked = true)
                                        @endif
                                        <div class="col-sm-3 col-xs-12 col-lg-3 col-md-3">
                                            {!! Form::checkbox('age_group_id[]', $key, $is_checked, array('id' => 'age_group'.$key)) !!}
                                            {!! Form::label('age_group'.$key, $value) !!}

                                        </div>
                                        <div class="col-sm-9 col-xs-12 col-lg-9 col-md-9">
                                            @php ( $entry_fee = !empty($ageGroupEntryFee[$key]) ? $ageGroupEntryFee[$key]: '0.00')

                                            {!! Form::label('entry_fee', trans('quickadmin.tournament.fields.entry_fee').': ', ['class' => 'col-sm-3 control-label entry-fee-label']) !!}
                                            {!! Form::text('entry_fee_'.$key , $entry_fee, ['class' => 'form-control entry-fee-text-box', 'placeholder' => '',"data-parsley-pattern"=>"^[0-9.]*\$", "data-parsley-trigger"=>"keyup" ]) !!}
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="form-group"> {!! Form::label('field_surface', trans('quickadmin.tournament.fields.field_surface') .': ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8"> {!! Form::select('field_surface', $field_surface_list, old('field_surface'), ['class' => 'form-control']) !!}
                                    <p class="help-block"></p>
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('information', trans('quickadmin.tournament.fields.information').':  ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-8">
                                    {{ Form::textarea('information', old('information'), ['class' => 'form-control', 'placeholder' => '', 'size' => '30x3']) }}
                                    <p class="help-block"></p>
                                </div>
                            </div>

                        </div>
                        <!-- /.box -->
                        <div class="form-navigation">
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-8">
                                    <a href="{{ url('administrator/tournament') }}/{{ $tournament_organization_id }} " class="btn btn-primary">@lang('quickadmin.cancel')</a>
                                    {!! Form::submit(trans('quickadmin.qa_save'), ['class' => 'btn btn-danger']) !!}
                                    <button type="button" class="previous btn btn-primary">@lang('quickadmin.previous')</button>
                                    <button type="button" class="next btn btn-primary">@lang('quickadmin.next') </button>
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
    </section>
    <!-- /.content -->
@endsection
@section('javascript')
    <script>
        var _token = "{{ csrf_token() }}";
        var get_google_longitude_latitude_url ="{{ URL::to('administrator/get_google_longitude_latitude') }}";

        $(document).ready(function() {
            $( "#competition_level_id" ).change(function() {
                selected_value = $(this).val();
                var isSelectAll =  jQuery.inArray("0", selected_value);
                if(isSelectAll >= 0) {
                    $('#competition_level_id option').prop('selected', true);
                }
            });
        });

    </script>
@endsection