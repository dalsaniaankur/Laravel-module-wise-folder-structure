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
                        <h1 class="page-block-title">SOFTBALL ACADEMIES LISTING</h1>
                    </div>
                    <div class="page-block-body d-flex">
                        <div class="page-block-left">
                            <div class="text-block">
                                <h3 class="text-block-title">ACADEMIES IN ILLINOIS. </h3>
                                <div class="text-block-body">
                                    <p>Search for softball academies throughout Chicago and across the state of
                                        Illinois. Academy profiles include a description of the academy, the services
                                        they provide and contact information. Try adding a zip code to your search for a
                                        Google map of the academies in your neighborhood.</p>
                                </div>
                            </div>

                            <div class="form-wrapper">
                                <div class="page-block-heading bg-danger">
                                    <h1 class="page-block-title"><span class="icon"><i class="fas fa-search"></i></span><span>FIND A ACADEMY</span> </h1>
                                </div>
                                <div class="search-form common-form max-width-100">

                                    {!! Form::open( ['method' => 'GET', 'id' =>'front_search_page', 'class'=>'row','data-parsley-validate']) !!}

                                    {!! Form::hidden('sortedBy', $sortedBy, array('id' => 'sortedBy')) !!}
                                    {!! Form::hidden('sortedOrder', $sortedOrder, array('id' => 'sortedOrder')) !!}

                                    <div class="form-group col-sm-6 col-xs-12">
                                        <div class="label">
                                            {!! Form::label(trans('front.academies_search.fields.academy')) !!}
                                        </div>
                                        <div class="form-input">
                                            {!! Form::text('academy_name',null,[]) !!}
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-6 col-xs-12">
                                        <div class="label align-self-start">
                                            {!! Form::label(trans('front.academies_search.fields.service_id')) !!}
                                        </div>
                                        <div class="form-input">
                                            <div class="custom-select">
                                                {!! Form::select('service_id[]', $services, old('service_id'), ['id' => 'service_id', 'multiple' => true, 'data-placeholder' => 'Select services']) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-4 col-xs-12">
                                        <div class="label">
                                            {!! Form::label(trans('front.academies_search.fields.state')) !!}
                                        </div>
                                        <div class="form-input">
                                            <div class="custom-select">
                                                {!! Form::select('state', $stateList, $state, ['onchange' => "getCityDropDown()", 'id' => 'state']) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-4 col-xs-12">
                                        <div class="label">
                                            {!! Form::label(trans('front.academies_search.fields.city')) !!}
                                        </div>
                                        <div class="form-input">
                                            <div class="custom-select">
                                                {!! Form::select('city', $cityList, $city, ['id' => 'city']) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-4 col-xs-12">
                                        <div class="label">
                                            {!! Form::label(trans('front.academies_search.fields.mile_radius')) !!}
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

                                    <div class="form-group col-sm-6 col-xs-12">
                                        {!! Form::submit(trans('front.qa_search'), ['class' => 'btn btn-danger search']) !!}
                                    </div>
                                {!! Form::close() !!}

                                <!--Google Captcha Validation Message-->
                                    <div class="google-captcha-error-msg front-search-page"></div>

                                </div>
                                <div class="search-grid">
                                    <table class="grid search_with_pagination_table">
                                        <thead>
                                        <tr>
                                            @php($short_class = ( strtolower($sortedOrder) == 'asc') ? 'ascending' : 'desanding' )
                                            <th class="academy-name {{ ($sortedBy =='academy_name') ? $short_class : 'sort' }}"
                                                onclick="sortWithSearch('academy_name');">{{ trans('front.academies_grid.fields.academy') }}</th>
                                            <th class="academy-location {{ ($sortedBy =='address_1') ? $short_class : 'sort' }}"
                                                onclick="sortWithSearch('address_1');">{{ trans('front.academies_grid.fields.location') }}</th>
                                            <th>{{ trans('front.academies_grid.fields.service_id') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        {!! $_data !!}
                                        </thead>
                                    </table>
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