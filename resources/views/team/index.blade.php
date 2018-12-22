@extends('layouts.app')
@section('title', $pageTitle)
@include('partials.meta-for-module-page')
@section('content')
    <section class="page-block">
        <div class="container">
            <div class="bg-white float-left w-100">
                @include('partials.top-banner-ads')
                <div class="page-block-inner">
                    <div class="page-block-heading bg-primary">
                        <h1 class="page-block-title">SOFTBALL TEAMS LISTING</h1>
                    </div>
                    <div class="page-block-body d-flex">
                        <div class="page-block-left">
                            <div class="text-block">
                                <h3 class="text-block-title">SEARCH FOR ILLINOIS YOUTH SOFTBALL TEAMS</h3>
                                <div class="text-block-body">
                                    <p>Are you looking for youth softball teams in Illinois? Well, you have come to the
                                        right place. We have the most extensive listing of youth softball teams in the
                                        Chicago and throughout the state of Illinois. Our multiple search filters will
                                        help you to easily and quickly find teams in your area. Each team listing can
                                        include an overview of the organization, recent team and individual
                                        achievements, notable player alumni, tryout schedules and links to their website
                                        and social media pages.</p>
                                </div>
                            </div>

                            <div class="form-wrapper">
                                <div class="page-block-heading bg-danger">
                                    <h1 class="page-block-title"><span class="icon"><i class="fas fa-search"></i></span><span>FIND A TEAM</span></h1>
                                </div>
                                <div class="search-form common-form max-width-100">

                                    {!! Form::open( ['method' => 'GET', 'id' =>'front_search_page', 'class'=>'row','data-parsley-validate']) !!}

                                    {!! Form::hidden('sortedBy', $sortedBy, array('id' => 'sortedBy')) !!}
                                    {!! Form::hidden('sortedOrder', $sortedOrder, array('id' => 'sortedOrder')) !!}

                                    <div class="form-group col-sm-6 col-xs-12">
                                        <div class="label">
                                            {!! Form::label(trans('front.team_search.fields.team')) !!}
                                        </div>
                                        <div class="form-input">
                                            {!! Form::text('name',null,[]) !!}
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-6 col-xs-12">
                                        <div class="label align-self-start">
                                            {!! Form::label(trans('front.team_search.fields.age_group')) !!}
                                        </div>
                                        <div class="form-input">
                                            <div class="custom-select">
                                                {!! Form::select('age_group_id[]', $agegroup, old('age_group_id'), ['id' => 'age_group', 'multiple' => true, 'data-placeholder' => 'Select age group']) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-4 col-xs-12">
                                        <div class="label">
                                            {!! Form::label(trans('front.team_search.fields.state')) !!}
                                        </div>
                                        <div class="form-input">
                                            <div class="custom-select">
                                                {!! Form::select('state', $stateList, $state, ['onchange' => "getCityDropDown()", 'id' => 'state']) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-4 col-xs-12">
                                        <div class="label">
                                            {!! Form::label(trans('front.team_search.fields.city')) !!}
                                        </div>
                                        <div class="form-input">
                                            <div class="custom-select">
                                                {!! Form::select('city', $cityList, $city, ['id' => 'city']) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-4 col-xs-12">
                                        <div class="label">
                                            {!! Form::label(trans('front.team_search.fields.mile_radius')) !!}
                                        </div>
                                        <div class="form-input">
                                            <div class="custom-select">
                                                {!! Form::select('mileRadius', $mileRadiusList, $mileRadius) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-12 col-xs-12">
                                        <div class="label">
                                            {!! Form::label(trans('quickadmin.google_captcha')) !!}
                                        </div>
                                        <div class="form-input">
                                            {!! app('captcha')->display() !!}
                                            @if($errors->has('g-recaptcha-response'))
                                                <p class="help-block"> {{ $errors->first('g-recaptcha-response') }} </p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-12 col-xs-12">
                                        {!! Form::submit(trans('front.qa_search'), ['class' => 'btn btn-danger search']) !!}
                                    </div>

                                {!! Form::close() !!}

                                <!--Google Captcha Validation Message-->
                                    <div class="google-captcha-error-msg front-search-page"></div>

                                </div>
                                <div class="search-grid">

                                    <div class="card-style row">
                                        {!! $_data !!}
                                    </div>

                                </div>

                                <div class="pagination" style="text-align:center"> {!! $paging !!} </div>

                            </div>

                        </div>

                        <div class="page-block-right d-md-none d-lg-block">
                            @include('partials.side-banner-ads')
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('javascript')
    <script>
        var form = 'form#front_search_page';
    </script>
@endsection