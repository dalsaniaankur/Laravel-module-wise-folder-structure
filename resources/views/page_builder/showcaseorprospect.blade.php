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
                                    <h1 class="page-block-title"><span class="icon"><i class="fas fa-search"></i></span><span>FIND A SHOWCASE</span></h1>
                                </div>

                                <div class="search-form common-form max-width-100">
                                    {!! Form::open( ['method' => 'GET', 'id' =>'front_search_page', 'class'=>'row','data-parsley-validate']) !!}

                                    {!! Form::hidden('sortedBy', $sortedBy, array('id' => 'sortedBy')) !!}
                                    {!! Form::hidden('sortedOrder', $sortedOrder, array('id' => 'sortedOrder')) !!}

                                    <div class="form-group col-sm-6 col-xs-12">
                                        <div class="label">
                                            {!! Form::label(trans('front.showcases_search.fields.showcase')) !!}
                                        </div>
                                        <div class="form-input">
                                            {!! Form::text('name',null,[]) !!}
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-6 col-xs-12">
                                        <div class="label">
                                            {!! Form::label(trans('front.showcases_search.fields.organizer')) !!}
                                        </div>
                                        <div class="form-input">
                                            <div class="custom-select">
                                                {!! Form::select('showcase_organization_id', $showcaseOrganization, old('showcase_organization_id')) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-4 col-xs-12">
                                        <div class="label align-self-start">
                                            {!! Form::label(trans('front.tournaments_search.fields.age_group')) !!}
                                        </div>
                                        <div class="form-input">
                                            <div class="custom-select">
                                                {!! Form::select('age_group_id[]', $agegroup, old('age_group_id'), ['id' => 'age_group', 'multiple' => true, 'data-placeholder' => 'Select age group']) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-4 col-xs-12">
                                        <div class="label align-self-start">
                                            {!! Form::label(trans('front.showcases_search.fields.position')) !!}
                                        </div>
                                        <div class="form-input">
                                            <div class="custom-select">
                                                {!! Form::select('position_id[]', $position, old('position_id'), ['id' => 'position_id', 'multiple' => true, 'data-placeholder' => 'Select position']) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-4 col-xs-12">
                                        <div class="label align-self-start">
                                            {!! Form::label(trans('front.showcases_search.fields.open_or_invite')) !!}
                                        </div>
                                        <div class="form-input">
                                            <div class="custom-select">
                                                {!! Form::select('open_or_invite[]', $openOrInvites, old('open_or_invite'), ['id' => 'open_or_invite', 'multiple' => true, 'data-placeholder' => 'Select open or invite']) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-3 col-xs-12">
                                        <div class="label">
                                            {!! Form::label(trans('front.showcases_search.fields.start_date')) !!}
                                        </div>
                                        <div class="form-input">
                                            <div class="date-select">
                                                {!! Form::text('start_date',null , ['class'=>'showcases_search_date', 'id'=>'start_date', 'autocomplete' => 'Off']) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-3 col-xs-12">
                                        <div class="label">
                                            {!! Form::label(trans('front.showcases_search.fields.end_date')) !!}
                                        </div>
                                        <div class="form-input">
                                            <div class="date-select">
                                                {!! Form::text('end_date',null , ['class'=>'showcases_search_date','id'=>'end_date', 'autocomplete' => 'Off']) !!}
                                            </div>
                                        </div>
                                    </div>

                                    @if(!$page_builder->city_id > 0)
                                        <div class="form-group col-sm-3 col-xs-12">
                                            <div class="label">
                                                {!! Form::label(trans('front.showcases_search.fields.city')) !!}
                                            </div>
                                            <div class="form-input">
                                                <div class="custom-select" style="max-width:220px;">
                                                    {!! Form::select('city', $cityList, $city, ['id' => 'city']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    @endif

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
                                            <th class="name {{ ($sortedBy =='name') ? $short_class : 'sort' }}" onclick="sortWithSearch('name');">{{ trans('front.showcases_grid.fields.showcase') }}</th>
                                            <th class="{{ ($sortedBy =='address_1') ? $short_class : 'sort' }}" onclick="sortWithSearch('address_1');">{{ trans('front.showcases_grid.fields.location') }}</th>
                                            <th class="event-organization">{{ trans('front.showcases_grid.fields.organization') }}</th>
                                            <th class="{{ ($sortedBy =='date') ? $short_class : 'sort' }}" onclick="sortWithSearch('date');">{{ trans('front.showcases_grid.fields.dates') }}</th>
                                            <th>{{ trans('front.showcases_grid.fields.age_groups') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @if(count($showcaseOrProspects) > 0)
                                            @foreach ($showcaseOrProspects as $key => $data)
                                                <tr>
                                                    <td data-title='{{ trans('front.showcases_grid.fields.showcase') }}'><a href='{{ $data->getUrl() }}'>{{ $data->name }}</a></td>
                                                    <td data-title='{{ trans('front.showcases_grid.fields.location') }}'>{{ $data->state->name }}, {{ $data->city->city }}</td>
                                                    <td data-title='{{ trans('front.showcases_grid.fields.organization') }}'>{{ $data->showcaseorganization->name }}</td>
                                                    <td data-title='{{ trans('front.showcases_grid.fields.dates') }}'>{{ $data->dateList }}</td>
                                                    <td data-title='{{ trans('front.showcases_grid.fields.age_groups') }}'>{{ $data->age_group }}</td>

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

        /* open_or_invite change event */

        $('#open_or_invite').on("select2:unselect", function(e){

            if(e.params.data.id != '0'){
                var selected = $("#open_or_invite").val();
                selected = jQuery.grep(selected, function(value) {
                    return value != '0';
                });
                $("#open_or_invite").val(selected)
                $("#open_or_invite").trigger("change");
            }

        }).trigger('change');

        $('#open_or_invite').on("select2:select", function(e){

            if(e.params.data.id == '0'){

                $("#open_or_invite > option").prop("selected","selected");
                $("#open_or_invite").trigger("change");

            }
        }).trigger('change');


        /* position change event */
        $('#position_id').on("select2:unselect", function(e){

            if(e.params.data.id != '0'){
                var selected = $("#position_id").val();
                selected = jQuery.grep(selected, function(value) {
                    return value != '0';
                });
                $("#position_id").val(selected)
                $("#position_id").trigger("change");
            }

        }).trigger('change');

        $('#position_id').on("select2:select", function(e){

            if(e.params.data.id == '0'){

                $("#position_id > option").prop("selected","selected");
                $("#position_id").trigger("change");

            }
        }).trigger('change');

    </script>
@endsection
