@extends('layouts.app')
@section('title', $pageTitle)
@include('partials.meta-for-module-page')
@section('content')
    <section class="page-block">
        <div class="container">
            <div class="bg-white float-left w-100">
                @include('partials.top-banner-ads')
                <div class="page-block-inner">
                    <div class="page-block-heading bg-primary d-flex align-items-center">
                        @if (!empty($team->Images->image_path))
                            <div class="tournament-logo text-center">
                                <img src="{{ URL::to('/') .'/'.$team->Images->image_path }}"
                                     alt="Tournament Logo goes here">
                            </div>
                        @endif
                        <h1 class="page-block-title ml-4">{{ $team->name}}</h1>
                    </div>
                    <div class="page-block-body d-flex">
                        <div class="page-block-left">
                            <div class="row d-flex justify-content-between t-block-wrapper">
                                <div class="t-block-inner align-content-start">
                                    <div class="card">
                                        <ul>
                                            @if(!empty($team->contact_name))
                                                <li>
                                                    <span class="label"><i class="fas fa-user"></i>Contact Name</span>
                                                    <span class="texts">{{ $team->contact_name }}</span>
                                                </li>
                                            @endif
                                            @if(!empty($team->phone_number))
                                                <li>
                                                    <span class="label"><i class="fas fa-phone"></i>Phone</span>
                                                    <span class="texts"><a
                                                                href="tel:{{ $team->phone_number }}">{{ $team->phone_number }}</a></span>
                                                </li>
                                            @endif
                                            @if(!empty($team->email))
                                                <li>
                                                    <input type="hidden" value="{{ $team->team_id }}" id="entity_id">
                                                    <input type="hidden" value="team" id="entity_type">
                                                    <span class="label"><i class="fas fa-envelope"></i>Email</span>
                                                    <span class="texts"><a href="javascript:void(0);"
                                                                           onclick="openEmailModal()">Click to email</a></span>
                                                </li>
                                            @endif
                                            @if(!empty($team->website_url))
                                                <li>
                                                    <span class="label"><i class="fas fa-globe"></i>Website</span>
                                                    <span class="texts"><a href="{{ $team->website_url }}" target="_blank">{{ $team->website_url }}</a></span>
                                                </li>
                                            @endif

                                            @if(!empty($team->blog_url))
                                                <li>
                                                    <span class="label"><i class="fab fa-blogger-b"></i>Blog Url</span>
                                                    <span class="texts"><a href="{{ $team->blog_url }}" target="_blank">{{ $team->blog_url }}</a></span>
                                                </li>
                                            @endif

                                            @if(!empty($team->facebook_url))
                                                <li>
                                                    <span class="label"><i class="fab fa-facebook-square"></i>Facebook Url</span>
                                                    <span class="texts"><a href="{{ $team->facebook_url }}" target="_blank">{{ $team->facebook_url }}</a></span>
                                                </li>
                                            @endif

                                            @if(!empty($team->twitter_url))
                                                <li>
                                                    <span class="label"><i class="fab fa-twitter-square"></i>Twitter Url</span>
                                                    <span class="texts"><a href="{{ $team->twitter_url }}" target="_blank">{{ $team->twitter_url }}</a></span>
                                                </li>
                                            @endif
                                            @if(!empty($team->age_group))
                                                <li>
                                                    <span class="label"><i
                                                                class="fas fa-user-friends"></i> Age Groups</span>
                                                    <span class="texts">{{ $team->age_group }}</span>
                                                </li>
                                            @endif

                                            @if(!empty($team->about))
                                                <li>
                                                    <span class="label"><i class="fas fa-info-circle"></i> About</span>
                                                    <span class="texts">{!! $team->about !!}</span>
                                                </li>
                                            @endif

                                            @if(!empty($team->achievements))
                                                <li>
                                                    <span class="label"><i class="fab fa-accessible-icon"></i> Achievements</span>
                                                    <span class="texts">{!! $team->achievements !!}</span>
                                                </li>
                                            @endif

                                            @if(!empty($team->general_information))
                                                <li>
                                                    <span class="label"><i class="fab fa-pagelines"></i> General Information</span>
                                                    <span class="texts">{!! $team->general_information !!}</span>
                                                </li>
                                            @endif

                                            @if(!empty($team->notable_alumni))
                                                <li>
                                                    <span class="label"><i class="fas fa-air-freshener"></i> Notable Alumni</span>
                                                    <span class="texts">{!! $team->notable_alumni !!}</span>
                                                </li>
                                            @endif

                                        </ul>
                                    </div>
                                </div>
                                <div class="t-block-inner">
                                    @if(!empty(trim($team->address_1)))
                                    <div class="card">
                                        <div class="card-header">
                                            <b class="card-title h6"><i class="fas fa-map-marker-alt"></i> Team Location</b>
                                            <p class="card-text">
                                                {{ $team->address_1}}<br>
                                                {{ $team->city->city }}
                                                , {{ $team->state->name }} {{ $team->zip }}
                                            </p>
                                            @if(!empty(trim($team->address_2)))
                                                <p class="card-text">
                                                    {{ $team->address_2}}<br>
                                                    {{ $team->city->city }}
                                                    , {{ $team->state->name }} {{ $team->zip }}
                                                </p>
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            @php(  $address = str_replace(",", "", str_replace(" ", "+", $team->address_1.', '.$team->state->name.'. '.$team->city->city.', '.$team->zip)) )
                                            <iframe class="card-img-top"
                                                    src="https://maps.google.com/maps?q=' . {{ $address }} .'&z=14&output=embed"
                                                    frameborder="0"></iframe>
                                        </div>
                                    </div>
                                    @endif
                                    @if(!empty(trim($team->youtube_video_id_1)))
                                    <div class="card">
                                        <div class="card-body">
                                            <iframe class="card-img-top" src="http://www.youtube.com/embed/{{ $team->youtube_video_id_1 }}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                                        </div>
                                    </div>
                                    @endif

                                    @if(!empty(trim($team->youtube_video_id_2)))
                                        <div class="card">
                                            <div class="card-body">
                                                <iframe class="card-img-top" src="http://www.youtube.com/embed/{{ $team->youtube_video_id_2 }}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                                            </div>
                                        </div>
                                    @endif

                                </div>
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
    @include('partials.email_popup')
@endsection