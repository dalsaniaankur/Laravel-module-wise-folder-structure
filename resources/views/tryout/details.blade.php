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
            <h1 class="page-block-title ml-4">{{ $tryout->tryout_name }}</h1>
          </div>
          <div class="page-block-body d-flex">
            <div class="page-block-left">
              <div class="row d-flex justify-content-between t-block-wrapper">
                <div class="t-block-inner align-content-start">
                  <div class="card">
                    <ul>
                      @if(!empty($tryout->contact_name))
                        <li>
                          <span class="label"><i class="fas fa-user"></i>Contact Name</span>
                          <span class="texts">{{ $tryout->contact_name }}</span>
                        </li>
                      @endif

                      @if(!empty($tryout->phone_number))
                        <li>
                          <span class="label"><i class="fas fa-phone"></i>Phone</span>
                          <span class="texts"><a
                                    href="tel:{{ $tryout->phone_number }}">{{ $tryout->phone_number }}</a></span>
                        </li>
                      @endif

                      @if(!empty($tryout->email))
                        <li>
                          <input type="hidden" value="{{ $tryout->tryout_id }}" id="entity_id">
                          <input type="hidden" value="tryout" id="entity_type">
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

                      @if(!empty($tryout->attachment_path_1) || !empty($tryout->attachment_path_2))
                        <li>
                          <input type="hidden" value="{{ $tryout->tryout_id }}" id="entity_id">
                          <input type="hidden" value="tryout" id="entity_type">
                          <span class="label"><i class="fas fa-envelope"></i>Attachments</span>
                          <span class="texts"><a href="{{ URL::to('/').$tryout->attachment_path_1 }}" target="_blank">{{ $tryout-> attachment_name_1 }}</a></span>
                          <span class="texts"><a href="{{ URL::to('/').$tryout->attachment_path_2 }}" target="_blank">{{ $tryout-> attachment_name_2 }}</a></span>
                        </li>
                      @endif

                      @if(!empty($tryout->age_group))
                        <li>
                          <span class="label"><i class="fas fa-user-friends"></i> Age Groups</span>
                          <span class="texts">{{ $tryout->age_group }}</span>
                        </li>
                      @endif

                      @if(!empty($tryout->age_group_result))
                        <li>
                          <span class="label"><i class="fas fa-air-freshener"></i> Age Group / Position</span>
                          @foreach ($tryout->age_group_result as $key => $data)
                            <span class="texts"><b>Age Group: {{ $data }}</b></span>
                            @if(!empty($tryout->age_group_position_list[$key]))
                              @foreach ($tryout->age_group_position_list[$key] as $position)
                                <span class="texts">{{ $position }} <br></span>
                              @endforeach
                            @endif
                          @endforeach
                        </li>
                      @endif

                      @if(!empty($tryout->information))
                          <li>
                              <span class="label"><i class="fas fa-grip-vertical"></i> Additional Information</span>
                              <span class="texts">{!!  $tryout->information !!}</span>
                          </li>
                      @endif

                    </ul>
                  </div>
                </div>
                <div class="t-block-inner">
                  @if(!empty(trim($tryout->address_1)))
                    <div class="card">
                      <div class="card-header">
                        <b class="card-title h6"><i class="fas fa-map-marker-alt"></i> Tryout Location</b>
                        <p class="card-text">
                          {{ $tryout->address_1}}<br>
                          {{ $tryout->city->city }}
                          , {{ $tryout->state->name }} {{ $tryout->zip }}
                        </p>
                        @if(!empty(trim($tryout->address_2)))
                          <p class="card-text">
                            {{ $tryout->address_2}}<br>
                            {{ $tryout->city->city }}
                            , {{ $tryout->state->name }} {{ $tryout->zip }}
                          </p>
                        @endif
                      </div>
                      <div class="card-body">
                        @php(  $address = str_replace(",", "", str_replace(" ", "+", $tryout->address_1.', '.$tryout->state->name.'. '.$tryout->city->city.', '.$tryout->zip)) )
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