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
                <h1 class="page-block-title ml-4">{{ $tournament->tournament_name}} / {{ $tournament->tournamentOrganization->name}}</h1>
            </div>
          </div>
          <div class="page-block-body d-flex">

              <div class="page-block-left">
                  <div class="row d-flex justify-content-between t-block-wrapper">
                      <div class="t-block-inner align-content-start">
                          <div class="card">
                              <ul>
                                  @if(!empty($tournament->contact_name))
                                  <li>
                                      <span class="label"><i class="fas fa-user"></i>Contact Name</span>
                                      <span class="texts">{{ $tournament->contact_name }}</span>
                                  </li>
                                  @endif
                                  @if(!empty($tournament->phone_number))
                                  <li>
                                      <span class="label"><i class="fas fa-phone"></i>Phone</span>
                                      <span class="texts"><a href="tel:{{ $tournament->phone_number }}">{{ $tournament->phone_number }}</a></span>
                                  </li>
                                  @endif
                                  @if(!empty($tournament->email))
                                  <li>
                                      <input type="hidden" value="{{ $tournament->tournament_id }}" id="entity_id">
                                      <input type="hidden" value="tournament" id="entity_type">
                                      <span class="label"><i class="fas fa-envelope"></i>Email</span>
                                      <span class="texts"><a href="javascript:void(0);" onclick="openEmailModal()">Click to email</a></span>
                                  </li>
                                  @endif
                                  @if(!empty($tournament->event_website_url))
                                  <li>
                                      <span class="label"><i class="fas fa-globe"></i>Website</span>
                                      <span class="texts"><a href="{{ $tournament->event_website_url }}" target="_blank">{{ $tournament->event_website_url }}</a></span>
                                  </li>
                                  @endif
                                  @if(!empty($tournament->start_date) && $tournament->start_date != '0000-00-00' && !empty($tournament->end_date) && $tournament->end_date != '0000-00-00')
                                      <li>
                                          <span class="label"> <i class="far fa-calendar-plus"></i>Start Date / End Date</span>
                                          <span class="texts">{{ $tournament->start_date}} to {{ $tournament->end_date}}</span>
                                      </li>
                                  @endif
                                  @if(!empty($competitionLevelList))
                                      <li>
                                          <span class="label"><i class="fas fa-grip-vertical"></i> Competition Level</span>
                                          <span class="texts">
                                      @foreach($competitionLevelList as $key => $value)
                                                  @if(!$loop->first), @endif {{ $value}}
                                              @endforeach
                                  </span>
                                      </li>
                                  @endif
                                  @if(!empty($tournament->age_group_entry_fee) && count($tournament->age_group_entry_fee) > 0)
                                  <li>
                                      <span class="label"><i class="fas fa-file-invoice-dollar"></i> Age Group / Entry Fee</span>
                                      @foreach ($tournament->age_group_entry_fee as $key => $data)
                                          <span class="texts">{{ $data->name }} / ${{ $data->entry_fee }}</span>
                                      @endforeach
                                  </li>
                                  @endif
                                  @if(!empty($tournament->field_surface))
                                      <li>
                                          <span class="label"><i class="fas fa-life-ring"></i> Field Surface</span>
                                          <span class="texts">{{ $tournament->field_surface }}</span>
                                      </li>
                                  @endif
                                  @if(!empty($tournament->stadium_or_field_name))
                                      <li>
                                          <span class="label"><i class="fas fa-air-freshener"></i> Stadium / Field Name</span>
                                          <span class="texts">{{ $tournament->stadium_or_field_name }}</span>
                                      </li>
                                  @endif
                                  @if(!empty($tournament->information))
                                      <li>
                                          <span class="label"><i class="fas fa-info-circle"></i> Additional Information</span>
                                          <span class="texts">{!! $tournament->information !!}</span>
                                      </li>
                                  @endif
                              </ul>
                          </div>
                      </div>
                      <div class="t-block-inner">
                          @if(!empty(trim($tournament->address_1)))
                          <div class="card">
                              <div class="card-header">
                                  <b class="card-title h6"><i class="fas fa-map-marker-alt"></i> Tournament Location</b>
                                  <p class="card-text">
                                      {{ $tournament->address_1}}<br>
                                      {{ $tournament->city->city }}, {{ $tournament->state->name }} {{ $tournament->zip }}
                                  </p>
                                  @if(!empty(trim($tournament->address_2)))
                                  <p class="card-text">
                                      {{ $tournament->address_2}}<br>
                                      {{ $tournament->city->city }}, {{ $tournament->state->name }} {{ $tournament->zip }}
                                  </p>
                                  @endif
                              </div>
                              <div class="card-body">
                                  @php(  $address = str_replace(",", "", str_replace(" ", "+", $tournament->address_1.', '.$tournament->state->name.'. '.$tournament->city->city.', '.$tournament->zip)) )
                                  <iframe class="card-img-top" src="https://maps.google.com/maps?q=' . {{ $address }} .'&z=14&output=embed" frameborder="0"></iframe>
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