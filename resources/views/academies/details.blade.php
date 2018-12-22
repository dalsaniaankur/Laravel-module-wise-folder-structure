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
                @if (!empty($academy->Images->image_path))
                    <div class="tournament-logo text-center">
                        <img src="{{ URL::to('/') .'/'.$academy->Images->image_path }}"
                             alt="Academy Logo goes here">
                    </div>
                @endif
                <h1 class="page-block-title ml-4">{{ $academy->academy_name}}</h1>
            </div>
          <div class="page-block-body d-flex">
            <div class="page-block-left">
              <div class="row d-flex justify-content-between t-block-wrapper">
                  <div class="t-block-inner align-content-start">
                      <div class="card">
                          <ul>

                              @if(!empty($academy->contact_name))
                                  <li>
                                      <span class="label"><i class="fas fa-user"></i>Contact Name</span>
                                      <span class="texts">{{ $academy->contact_name }}</span>
                                  </li>
                              @endif
                              @if(!empty($academy->phone_number))
                                  <li>
                                      <span class="label"><i class="fas fa-phone"></i>Phone</span>
                                      <span class="texts"><a href="tel:{{ $academy->phone_number }}">{{ $academy->phone_number }}</a></span>
                                  </li>
                              @endif
                              @if(!empty($academy->email))
                                  <li>
                                      <input type="hidden" value="{{ $academy->academy_id }}" id="entity_id">
                                      <input type="hidden" value="academy" id="entity_type">
                                      <span class="label"><i class="fas fa-envelope"></i>Email</span>
                                      <span class="texts"><a href="javascript:void(0);" onclick="openEmailModal()">Click to email</a></span>
                                  </li>
                              @endif
                              @if(!empty($academy->website_url))
                                  <li>
                                      <span class="label"><i class="fas fa-globe"></i>Website</span>
                                      <span class="texts"><a href="{{ $academy->website_url }}" target="_blank">{{ $academy->website_url }}</a></span>
                                  </li>
                              @endif

                              @if(!empty($academy->facebook_url))
                                  <li>
                                      <span class="label"><i class="fab fa-facebook-square"></i>Facebook Url</span>
                                      <span class="texts"><a href="{{ $academy->facebook_url }}" target="_blank">{{ $academy->facebook_url }}</a></span>
                                  </li>
                              @endif

                              @if(!empty($academy->twitter_url))
                                  <li>
                                      <span class="label"><i class="fab fa-twitter-square"></i>Twitter Url</span>
                                      <span class="texts"><a href="{{ $academy->twitter_url }}" target="_blank">{{ $academy->twitter_url }}</a></span>
                                  </li>
                              @endif

                              @if(!empty($academy->services_list))
                                  <li>
                                      <span class="label"><i class="fas fa-user-friends"></i> Services List</span>
                                      <span class="texts">{{ $academy->services_list }}</span>
                                  </li>
                              @endif
                              @if(!empty($academy->about))
                                  <li>
                                  <span class="label"><i class="fas fa-info-circle"></i> About</span>
                                  <span class="texts">{!!  $academy->about !!}</span>
                                  </li>
                              @endif

                              @if(!empty($academy->objectives))
                                  <li>
                                      <span class="label"><i class="fab fa-accessible-icon"></i> Objectives</span>
                                      <span class="texts">{!! $academy->objectives !!}</span>
                                  </li>
                              @endif

                              @if(!empty($academy->general_information))
                                  <li>
                                      <span class="label"><i class="fas fa-user-friends"></i> General Information</span>
                                      <span class="texts">{!! $academy->general_information !!}</span>
                                  </li>
                              @endif

                              @if(!empty($academy->programs))
                                  <li>
                                      <span class="label"><i class="fab fa-pagelines"></i> Programs</span>
                                      <span class="texts">{!! $academy->programs !!}</span>
                                  </li>
                              @endif

                              @if(!empty($academy->alumni))
                                  <li>
                                      <span class="label"><i class="fas fa-air-freshener"></i> Alumni</span>
                                      <span class="texts">{!! $academy->alumni !!}</span>
                                  </li>
                              @endif


                          </ul>
                      </div>
                  </div>
                  <div class="t-block-inner">
                      @if(!empty(trim($academy->address_1)))
                          <div class="card">
                              <div class="card-header">
                                  <b class="card-title h6"><i class="fas fa-map-marker-alt"></i> Team Location</b>
                                  <p class="card-text">
                                      {{ $academy->address_1}}<br>
                                      {{ $academy->city->city }}
                                      , {{ $academy->state->name }} {{ $academy->zip }}
                                  </p>
                                  @if(!empty(trim($academy->address_2)))
                                      <p class="card-text">
                                          {{ $academy->address_2}}<br>
                                          {{ $academy->city->city }}
                                          , {{ $academy->state->name }} {{ $academy->zip }}
                                      </p>
                                  @endif
                              </div>
                              <div class="card-body">
                                  @php(  $address = str_replace(",", "", str_replace(" ", "+", $academy->address_1.', '.$academy->state->name.'. '.$academy->city->city.', '.$academy->zip)) )
                                  <iframe class="card-img-top"
                                          src="https://maps.google.com/maps?q=' . {{ $address }} .'&z=14&output=embed"
                                          frameborder="0"></iframe>
                              </div>
                          </div>
                      @endif
                      @if(!empty(trim($academy->youtube_video_id_1)))
                          <div class="card">
                              <div class="card-body">
                                  <iframe class="card-img-top" src="http://www.youtube.com/embed/{{ $academy->youtube_video_id_1 }}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                              </div>
                          </div>
                      @endif

                      @if(!empty(trim($academy->youtube_video_id_2)))
                          <div class="card">
                              <div class="card-body">
                                  <iframe class="card-img-top" src="http://www.youtube.com/embed/{{ $academy->youtube_video_id_2 }}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
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