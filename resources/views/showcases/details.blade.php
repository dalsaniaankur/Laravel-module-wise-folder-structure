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
              <div class="tournament-logo text-center">
              </div>
            <h1 class="page-block-title ml-4">{{ $showcase->name }}</h1>
          </div>
          <div class="page-block-body d-flex">
            <div class="page-block-left">

              <div class="row d-flex justify-content-between t-block-wrapper">

                <div class="t-block-inner align-content-start">
                  <div class="card">
                    <ul>
                      @if(!empty($showcase->phone_number))
                        <li>
                          <span class="label"><i class="fas fa-phone"></i>Phone</span>
                          <span class="texts"><a href="tel:{{ $showcase->phone_number }}">{{ $showcase->phone_number }}</a></span>
                        </li>
                      @endif
                      @if(!empty($showcase->email))
                        <li>
                          <input type="hidden" value="{{ $showcase->showcase_or_prospect_id }}" id="entity_id">
                          <input type="hidden" value="showcase" id="entity_type">
                          <span class="label"><i class="fas fa-envelope"></i>Email</span>
                          <span class="texts"><a href="javascript:void(0);" onclick="openEmailModal()">Click to email</a></span>
                        </li>
                      @endif
                      @if(!empty($dateList))
                          <li>
                              <span class="label"> <i class="far fa-calendar-plus"></i>Dates</span>
                              @foreach($dateList as $date)
                                  <span class="texts">{{ $date }}</span>
                              @endforeach
                          </li>
                      @endif
                      @if(!empty($showcase->cost_or_notes))
                        <li>
                          <span class="label"><i class="fas fa-sticky-note"></i>Cost Notes</span>
                          <span class="texts">{{ $showcase->cost_or_notes }}</span>
                        </li>
                      @endif
                      @if(!empty($showcase->website_url))
                        <li>
                          <span class="label"><i class="fas fa-globe"></i>Website</span>
                          <span class="texts"><a href="{{ $showcase->website_url }}" target="_blank">{{ $showcase->website_url }}</a></span>
                        </li>
                      @endif
                        @if(!empty($showcase->open_or_invite))
                          <li>
                            <span class="label"><i class="fas fa-user"></i>Open / Invite</span>
                            <span class="texts">{{ $showcase->open_or_invite ==1 ? 'Open' : 'Invite' }}</span>
                          </li>
                        @endif
                      @if(!empty($showcase->age_group))
                        <li>
                          <span class="label"><i class="fas fa-user-friends"></i> Age Groups</span>
                          <span class="texts">{{ $showcase->age_group }}</span>
                        </li>
                      @endif
                      @if(!empty($showcase->description))
                        <li>
                          <span class="label"><i class="fab fa-accessible-icon"></i> Description</span>
                          <span class="texts">{!! $showcase->description !!}</span>
                        </li>
                      @endif
                      @if(!empty($showcase->other_information))
                        <li>
                          <span class="label"><i class="fas fa-info-circle"></i> Other Information</span>
                          <span class="texts">{!! $showcase->other_information !!}</span>
                        </li>
                      @endif
                        @if(!empty($showcase->attachment_path_1) || !empty($showcase->attachment_path_2))
                          <li>
                            <span class="label"><i class="fab fa-pagelines"></i> Attachments</span>
                            <span class="texts"><a href="{{ URL::to('/').$showcase->attachment_path_1 }}" target="_blank">{{ $showcase-> attachment_name_1 }}</a></span>
                            <span class="texts"><a href="{{ URL::to('/').$showcase->attachment_path_2 }}" target="_blank">{{ $showcase-> attachment_name_2 }}</a></span>
                          </li>
                        @endif
                        @if(!empty($showcase->position))
                          <li>
                            <span class="label"><i class="fas fa-air-freshener"></i> Position</span>
                            <span class="texts">{{ $showcase->position }}</span>
                          </li>
                        @endif

                    </ul>
                  </div>
                </div>

                <div class="t-block-inner">
                  @if(!empty(trim($showcase->address_1)))
                    <div class="card">
                      <div class="card-header">
                        <b class="card-title h6"><i class="fas fa-map-marker-alt"></i> Showcase Location</b>
                        <p class="card-text">
                          {{ $showcase->address_1}}<br>
                          {{ $showcase->city->city }}
                          , {{ $showcase->state->name }} {{ $showcase->zip }}
                        </p>
                        @if(!empty(trim($showcase->address_2)))
                          <p class="card-text">
                            {{ $showcase->address_2}}<br>
                            {{ $showcase->city->city }}
                            , {{ $showcase->state->name }} {{ $showcase->zip }}
                          </p>
                        @endif
                      </div>
                      <div class="card-body">
                        @php(  $address = str_replace(",", "", str_replace(" ", "+", $showcase->address_1.', '.$showcase->state->name.'. '.$showcase->city->city.', '.$showcase->zip)) )
                        <iframe class="card-img-top"
                                src="https://maps.google.com/maps?q=' . {{ $address }} .'&z=14&output=embed"
                                frameborder="0"></iframe>
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