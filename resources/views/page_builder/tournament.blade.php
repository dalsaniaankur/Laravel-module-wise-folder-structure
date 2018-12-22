@extends('layouts.app')
@section('title', $page_builder->getPageTitle())
@section('meta')
    @if(!empty($page_builder->getMetaTitle()))
        <meta name="title" content="{{ $page_builder->getMetaTitle() }}">
        <meta property="og:title" content="{{ $page_builder->getMetaTitle() }}">
        <meta name="twitter:title" content="{{ $page_builder->getMetaTitle() }}">
        <meta itemprop="name" content="{{ $page_builder->getMetaTitle() }}">
    @endif
    @if(!empty($page_builder->getMetaKeywords()))
        <meta name="keywords" content="{{ $page_builder->getMetaKeywords() }}">
    @endif
    @if(!empty($page_builder->getMetaDescription()))
        <meta name="description" content="{{ $page_builder->getMetaDescription() }}">
        <meta property="og:description" content="{{ $page_builder->getMetaDescription() }}">
        <meta name="twitter:description" content="{{ $page_builder->getMetaDescription() }}">
    @endif
    @if(!empty($page_builder->getMetaImage()))
        <meta property="og:image" content="{{ $page_builder->getMetaImage() }}">
        <meta itemprop="image" content="{{ $page_builder->getMetaImage() }}">
        <meta name="twitter:image" content="{{ $page_builder->getMetaImage() }}">
    @endif

@endsection
@section('content')
    <section class="page-block">
        <div class="container">
            <div class="bg-white float-left w-100">
                @include('partials.top-banner-ads')
                <div class="page-block-inner">
                    <div class="page-block-heading bg-primary">
                        <h1 class="page-block-title">{!! $page_builder->page_title !!}</h1>
                    </div>
                    <div class="page-block-body d-flex">
                        <div class="page-block-left">
                            <div class="text-block">
                                @if(!empty($page_builder->getMetaImage()))
                                    <img class='feature-img' src="{{ $page_builder->getMetaImage() }}" alt="image" >
                                @endif
                                {!! $page_builder->content !!}
                            </div>
                            <div class="form-wrapper">

                                <div class="page-block-heading bg-danger">
                                    <h1 class="page-block-title"><span class="icon"><i class="fas fa-search"></i></span><span>FIND A TOURNAMENT</span></h1>
                                </div>

                                <div class="search-form common-form max-width-100">
                                    {!! Form::open( ['method' => 'GET', 'id' =>'front_search_page', 'class'=>'row','data-parsley-validate']) !!}

                                    <div class="form-group col-sm-6 col-xs-12">
                                        <div class="label">
                                            {!! Form::label(trans('front.tournaments_search.fields.tournament')) !!}
                                        </div>
                                        <div class="form-input">
                                            {!! Form::text('tournament_name',null,[]) !!}
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-6 col-xs-12">
                                        <div class="label align-self-start">
                                            {!! Form::label(trans('front.tournaments_search.fields.organizer')) !!}
                                        </div>
                                        <div class="form-input">
                                            <div class="custom-select">
                                                {!! Form::select('tournament_organization_id', $tournamentOrganization) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-6 col-xs-12">
                                        <div class="label align-self-start">
                                            {!! Form::label(trans('front.tournaments_search.fields.age_group')) !!}
                                        </div>
                                        <div class="form-input">
                                            <div class="custom-select">
                                                {!! Form::select('age_group_id[]', $agegroup, old('age_group_id'), ['id' => 'age_group', 'multiple' => true, 'data-placeholder' => 'Select age group']) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-6 col-xs-12">
                                        <div class="label align-self-start">
                                            {!! Form::label(trans('front.tournaments_search.fields.competition_level')) !!}
                                        </div>
                                        <div class="form-input">
                                            <div class="custom-select">
                                                {!! Form::select('competition_level_id[]', $competitionLevellist, old('competition_level_id'), ['id' => 'competition_level', 'multiple' => true, 'data-placeholder' => 'Select competition level' ]) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-3 col-xs-12">
                                        <div class="label">
                                            {!! Form::label(trans('front.tournaments_search.fields.start_date')) !!}
                                        </div>
                                        <div class="form-input">
                                            <div class="date-select">
                                                {!! Form::text('start_date',null , ['class'=>'tournament_search_date', 'id' => 'start_date', 'autocomplete' => 'Off']) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-3 col-xs-12">
                                        <div class="label">
                                            {!! Form::label(trans('front.tournaments_search.fields.end_date')) !!}
                                        </div>
                                        <div class="form-input">
                                            <div class="date-select">
                                                {!! Form::text('end_date',null , ['class'=>'tournament_search_date' , 'id' => 'end_date', 'autocomplete' => 'Off']) !!}
                                            </div>
                                        </div>
                                    </div>

                                    @if(!$page_builder->city_id > 0)
                                        <div class="form-group col-sm-3 col-xs-12">
                                            <div class="label">
                                                {!! Form::label(trans('front.tournaments_search.fields.city')) !!}
                                            </div>
                                            <div class="form-input">
                                                <div class="custom-select">
                                                    {!! Form::select('city', $cityList, $city, ['id' => 'city']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="form-group col-sm-3 col-xs-12">
                                        <div class="label">
                                            {!! Form::label(trans('front.tournaments_search.fields.field_surface')) !!}
                                        </div>
                                        <div class="form-input">
                                            <div class="custom-select" >
                                                {!! Form::select('field_surface', $fieldSurface, old('field_surface')) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-6 col-xs-12">
                                        <div class="label">
                                            {!! Form::label(trans('front.tournaments_search.fields.entry_fee_less_then')) !!}
                                        </div>
                                        <div class="form-input">
                                            {!! Form::text('entry_fee',null) !!}
                                        </div>

                                    </div>

                                    <div class="form-group col-sm-6 col-xs-12">
                                        <div class="label align-self-start">
                                            {!! Form::label(trans('front.tournaments_search.fields.guaranteed_games')) !!}
                                        </div>
                                        <div class="form-input">
                                            <div class="custom-select">
                                                {!! Form::select('guaranteed_games', $guaranteedGamesList) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-6 col-xs-12">
                                        <div class="label align-self-start">
                                            {!! Form::label(trans('front.tournaments_search.fields.hotel_required')) !!}
                                        </div>
                                        <div class="form-input">
                                            <div class="custom-select">
                                                {!! Form::select('hotel_required', $hotelRequiredDropDown) !!}
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

                                    {!! Form::hidden('sortedBy',  $sortedBy, array('id' => 'sortedBy')) !!}
                                    {!! Form::hidden('sortedOrder', $sortedOrder, array('id' => 'sortedOrder')) !!}
                                    {!! Form::hidden('state',  $state, array('id' => 'state')) !!}

                                    <div class="form-group col-sm-12 col-xs-12">
                                        {!! Form::submit(trans('front.qa_search'), ['class' => 'btn btn-danger search']) !!}
                                    </div>
                                {!! Form::close() !!}

                                <!--Google Captcha Validation Message-->
                                    <div class="google-captcha-error-msg front-search-page"></div>
                                </div>

                                <div class="search-grid">
                                    <table class="grid search_with_pagination_table page-builder">
                                        <thead>
                                        <tr>
                                            @php($short_class = ( strtolower($sortedOrder) == 'asc') ? 'ascending' : 'desanding' )
                                            <th class="tournament_name {{ ($sortedBy =='tournament_name') ? $short_class : 'sort' }}" onclick="sortWithSearch('tournament_name', this);">{{ trans('front.tournaments_grid.fields.tournament') }}</th>
                                            <th class="{{ ($sortedBy =='address_1') ? $short_class : 'sort' }}" onclick="sortWithSearch('address_1');">{{ trans('front.tournaments_grid.fields.location') }}</th>
                                            <th class="{{ ($sortedBy =='name') ? $short_class : 'sort' }}" onclick="sortWithSearch('name');">{{ trans('front.tournaments_grid.fields.organizer') }}</th>
                                            <th class="tournament-date {{ ($sortedBy =='start_date') ? $short_class : 'sort' }}" onclick="sortWithSearch('start_date');">{{ trans('front.tournaments_grid.fields.start_date') }}</th>
                                            <th class="tournament-date {{ ($sortedBy =='end_date') ? $short_class : 'sort' }}" onclick="sortWithSearch('end_date');">{{ trans('front.tournaments_grid.fields.end_date') }}</th>
                                            <th class="tournament-age-group">{{ trans('front.tournaments_grid.fields.age_groups') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @if(count($tournament) > 0)
                                            @foreach ($tournament as $key => $data)
                                                <tr>
                                                    <td data-title='{{ trans('front.tournaments_grid.fields.tournament') }}'><a href='{{ $data->getUrl() }}'>{{ $data->tournament_name }}</a></td>
                                                    <td data-title='{{ trans('front.tournaments_grid.fields.location') }}'>{{ $data->state->name }}, {{ $data->city->city }}</td>
                                                    <td data-title='{{ trans('front.tournaments_grid.fields.organizer') }}'>{{ $data->tournamentOrganization->name }}</td>
                                                    <td data-title='{{ trans('front.tournaments_grid.fields.start_date') }}'>{{ (($data->start_date !='0000-00-00') ? $data->start_date : '') }}</td>
                                                    <td data-title='{{ trans('front.tournaments_grid.fields.end_date') }}'>{{ (($data->end_date !='0000-00-00') ? $data->end_date : '') }}</td>
                                                    <td data-title='{{ trans('front.tournaments_grid.fields.age_groups') }}'>{{ $data->age_group }}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan='100'>@lang('quickadmin.qa_no_entries_in_table')</td>
                                            </tr>
                                        @endif
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
