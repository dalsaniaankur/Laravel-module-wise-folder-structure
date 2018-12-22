@extends('layouts.app')
@section('title', $pageTitle)
@include('partials.meta-for-module-page')
@section('content')
  <section class="page-block">
    <div class="page-top-block bg-primary">
      <div class="container">
        <div class="highlight-box d-flex">
          <div class="highlight-inner d-flex flex-xs-column bg-danger align-items-center">
            <div class="image-box">
              <img src="{{ url('front/assets/images/ball-image.png')}}" alt="">
            </div>
            <h1>GET SOFTBALL CONNECTED</h1>
            <p>YOUR COMMUNITY HOT SPOT
              <br>
              FOR ALL THINGS SOFTBALL IN THE MIDWEST</p>
          </div>
        </div>
        <div class="row listing-box">
          <div class="col-lg-4">
            <div class="list-box-wrapper">
              <div class="card">
                <div class="list-box-heading">
                  <h2>FIND A TOURNAMENT</h2>
                  <p>CLICK ON A STATE BELOW TO SEARCH</p>
                </div>
                <div class="card-footer">
                  <ul class="state-list">
                    <li>
                      <div class="list-item-block">
                        <a href="{{ url('illinois-softball-tournaments')}}">
                        <span>
                          <img src="{{ url('front/assets/images/illinois.png')}}" alt="">
                        </span>
                          ILLINOIS
                        </a>
                      </div>
                    </li>
                    <li>
                      <div class="list-item-block">
                        <a href="#">
                        <span>
                          <img src="{{ url('front/assets/images/indiana.png')}}" alt="">
                        </span>
                          INDIANA
                        </a>
                      </div>
                    </li>
                    <li>
                      <div class="list-item-block">
                        <a href="#">
                        <span>
                          <img src="{{ url('front/assets/images/missouri.png')}}" alt="">
                        </span>
                          MISSOURI
                        </a>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
              <a href="{{ url('/member/home') }}" class="btn btn-custom btn-block btn-yellow">LIST YOUR TOURNAMENT</a>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="list-box-wrapper">
              <div class="card">
                <div class="list-box-heading">
                  <h2>FIND A TEAM</h2>
                  <p>CLICK ON A STATE BELOW TO SEARCH</p>
                </div>
                <div class="card-footer">
                  <ul class="state-list">
                    <li>
                      <div class="list-item-block">
                        <a href="{{ url('illinois-softball-teams')}}">
                        <span>
                          <img src="{{ url('front/assets/images/illinois.png')}}" alt="">
                        </span>
                          ILLINOIS
                        </a>
                      </div>
                    </li>
                    <li>
                      <div class="list-item-block">
                        <a href="#">
                        <span>
                          <img src="{{ url('front/assets/images/indiana.png')}}" alt="">
                        </span>
                          INDIANA
                        </a>
                      </div>
                    </li>
                    <li>
                      <div class="list-item-block">
                        <a href="#">
                        <span>
                          <img src="{{ url('front/assets/images/missouri.png')}}" alt="">
                        </span>
                          MISSOURI
                        </a>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
              <a href="{{ url('/member/home') }}" class="btn btn-custom btn-block btn-yellow">LIST YOUR TEAM</a>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="list-box-wrapper">
              <div class="card">
                <div class="list-box-heading">
                  <h2>FIND A TRYOUT/
                    PLAYERS NEEDED LIST</h2>
                  <p>CLICK ON A STATE BELOW TO SEARCH</p>
                </div>
                <div class="card-footer">
                  <ul class="state-list">
                    <li>
                      <div class="list-item-block">
                        <a href="{{ url('illinois-softball-tryouts')}}">
                        <span>
                          <img src="{{ url('front/assets/images/illinois.png')}}" alt="">
                        </span>
                          ILLINOIS
                        </a>
                      </div>
                    </li>
                    <li>
                      <div class="list-item-block">
                        <a href="#">
                        <span>
                          <img src="{{ url('front/assets/images/indiana.png')}}" alt="">
                        </span>
                          INDIANA
                        </a>
                      </div>
                    </li>
                    <li>
                      <div class="list-item-block">
                        <a href="#">
                        <span>
                          <img src="{{ url('front/assets/images/missouri.png')}}" alt="">
                        </span>
                          MISSOURI
                        </a>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
              <a href="{{ url('/member/home') }}" class="btn btn-custom btn-block btn-yellow">LIST YOUR TRYOUT OR PLAYER NEEDED</a>
            </div>
          </div>

          <div class="col-lg-4">
            <div class="list-box-wrapper">
              <div class="card">
                <div class="list-box-heading">
                  <h2>FIND A SHOWCASE</h2>
                  <p>CLICK ON A STATE BELOW TO SEARCH</p>
                </div>
                <div class="card-footer">
                  <ul class="state-list">
                    <li>
                      <div class="list-item-block">
                        <a href="{{ url('illinois-softball-showcases')}}">
                        <span>
                          <img src="{{ url('front/assets/images/illinois.png')}}" alt="">
                        </span>
                          ILLINOIS
                        </a>
                      </div>
                    </li>
                    <li>
                      <div class="list-item-block">
                        <a href="#">
                        <span>
                          <img src="{{ url('front/assets/images/indiana.png')}}" alt="">
                        </span>
                          INDIANA
                        </a>
                      </div>
                    </li>
                    <li>
                      <div class="list-item-block">
                        <a href="#">
                        <span>
                          <img src="{{ url('front/assets/images/missouri.png')}}" alt="">
                        </span>
                          MISSOURI
                        </a>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
              <a href="{{ url('/member/home') }}" class="btn btn-custom btn-block btn-yellow">LIST YOUR SHOWCASE</a>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="list-box-wrapper">
              <div class="card">
                <div class="list-box-heading">
                  <h2>FREE TEAM LISTINGS</h2>
                  <p>LET SOFTBALL CONNECTED BE YOUR TEAM’S HUB</p>
                </div>
                <div class="card-footer">
                  <ul class="feature-list">
                    <li><a href="#">TEAM ROSTERS</a></li>
                    <li><a href="#">TEAM SCHEDULES</a></li>
                    <li><a href="#">PRINTABLE EMAIL LISTS</a></li>
                    <li><a href="#">CONTACT INFO</a></li>
                  </ul>
                </div>
              </div>
              <a href="{{ url('/member/home') }}" class="btn btn-custom btn-block btn-yellow">REIGSTER NOW</a>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="list-box-wrapper">
              <div class="card">
                <div class="list-box-heading">
                  <h2>FIND AN ACADEMY</h2>
                  <p>CLICK ON A STATE BELOW TO SEARCH</p>
                </div>
                <div class="card-footer">
                  <ul class="state-list">
                    <li>
                      <div class="list-item-block">
                        <a href="{{ url('illinois-softball-academies')}}">
                        <span>
                          <img src="{{ url('front/assets/images/illinois.png')}}" alt="">
                        </span>
                          ILLINOIS
                        </a>
                      </div>
                    </li>
                    <li>
                      <div class="list-item-block">
                        <a href="#">
                        <span>
                          <img src="{{ url('front/assets/images/indiana.png')}}" alt="">
                        </span>
                          INDIANA
                        </a>
                      </div>
                    </li>
                    <li>
                      <div class="list-item-block">
                        <a href="#">
                        <span>
                          <img src="{{ url('front/assets/images/missouri.png')}}" alt="">
                        </span>
                          MISSOURI
                        </a>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
              <a href="{{ url('/member/home') }}" class="btn btn-custom btn-block btn-yellow">LIST YOUR ACADEMY</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="list-slider-wrapper d-flex align-items-center">
        <div class="list-slider-left bg-danger">
          <h3>MY SHOP CONNECTED</h3>
          <p>FOR SOFTBALL AND BASEBALL FANS</p>
        </div>
        <div class="list-slider-right">
          <div class="shop-list-slider">
            <ul id="shop_slider">
              <li><a href="#"><img src="{{ url('front/assets/images/slider/slider_image_1.png')}}" alt="Slider Image 1"></a></li>
              <li><a href="#"><img src="{{ url('front/assets/images/slider/slider_image_2.png')}}" alt="Slider Image 2"></a></li>
              <li><a href="#"><img src="{{ url('front/assets/images/slider/slider_image_3.png')}}" alt="Slider Image 3"></a></li>
              <li><a href="#"><img src="{{ url('front/assets/images/slider/slider_image_4.png')}}" alt="Slider Image 4"></a></li>
              <li><a href="#"><img src="{{ url('front/assets/images/slider/slider_image_5.png')}}" alt="Slider Image 5"></a></li>
              <li><a href="#"><img src="{{ url('front/assets/images/slider/slider_image_6.png')}}" alt="Slider Image 6"></a></li>
              <li><a href="#"><img src="{{ url('front/assets/images/slider/slider_image_7.png')}}" alt="Slider Image 7"></a></li>
            </ul>
          </div>
        </div>
      </div>

      @include('partials.home-banner-ads')

      <div class="tournaments float-left w-100">
        <div class="row">
          <div class="col-sm-6">
            <div class="t-block">
              <img src="{{ url('front/assets/images/chicago.png')}}" alt="Chicago">
              <div class="t-block-overlay">
                <h3>Chicago</h3>
              </div>
              <a class="t-link" href="#"></a>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="t-block">
              <img src="{{ url('front/assets/images/chicago.png')}}" alt="INDIANAPOLIS">
              <div class="t-block-overlay">
                <h3>INDIANAPOLIS</h3>
              </div>
              <a class="t-link" href="#"></a>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="t-block">
              <img src="{{ url('front/assets/images/kansascity.png')}}" alt="KANSAS CITY">
              <div class="t-block-overlay">
                <h3>KANSAS CITY</h3>
              </div>
              <a class="t-link" href="#"></a>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="t-block">
              <img src="{{ url('front/assets/images/stloius.png')}}" alt="ST. LOUIS">
              <div class="t-block-overlay">
                <h3>ST. LOUIS</h3>
              </div>
              <a class="t-link" href="#"></a>
            </div>
          </div>
          <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="t-block t-block-small">
              <img src="{{ url('front/assets/images/branson.png')}}" alt="BRANSON">
              <div class="t-block-overlay">
                <h3>BRANSON</h3>
              </div>
              <a class="t-link" href="#"></a>
            </div>
          </div>
          <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="t-block t-block-small">
              <img src="{{ url('front/assets/images/columbia.png')}}" alt="COLUMBIA">
              <div class="t-block-overlay">
                <h3>COLUMBIA</h3>
              </div>
              <a class="t-link" href="#"></a>
            </div>
          </div>
          <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="t-block t-block-small">
              <img src="{{ url('front/assets/images/bloomington.png')}}" alt="BLOOMINGTON">
              <div class="t-block-overlay">
                <h3>BLOOMINGTON</h3>
              </div>
              <a class="t-link" href="#"></a>
            </div>
          </div>
          <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="t-block t-block-small">
              <img src="{{ url('front/assets/images/champagin.png')}}" alt="CHAMPAIGN, IL">
              <div class="t-block-overlay">
                <h3>CHAMPAIGN, IL</h3>
              </div>
              <a class="t-link" href="#"></a>
            </div>
          </div>
          <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="t-block t-block-small">
              <img src="{{ url('front/assets/images/fortvyne.png')}}" alt="FORT WAYNE">
              <div class="t-block-overlay">
                <h3>FORT WAYNE</h3>
              </div>
              <a class="t-link" href="#"></a>
            </div>
          </div>
          <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="t-block t-block-small">
              <img src="{{ url('front/assets/images/quardcity.png')}}" alt="QUAD CITIES">
              <div class="t-block-overlay">
                <h3>QUAD CITIES</h3>
              </div>
              <a class="t-link" href="#"></a>
            </div>
          </div>
          <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="t-block t-block-small">
              <img src="{{ url('front/assets/images/springfild.png')}}" alt="SPRINGFIELD, IL">
              <div class="t-block-overlay">
                <h3>SPRINGFIELD, IL</h3>
              </div>
              <a class="t-link" href="#"></a>
            </div>
          </div>
          <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="t-block t-block-small">
              <img src="{{ url('front/assets/images/springfildmq.png')}}" alt="SPRINGFIELD, MO">
              <div class="t-block-overlay">
                <h3>SPRINGFIELD, MO</h3>
              </div>
              <a class="t-link" href="#"></a>
            </div>
          </div>
          <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="t-block t-block-small">
              <img src="{{ url('front/assets/images/peoria.png')}}" alt="PEORIA">
              <div class="t-block-overlay">
                <h3>PEORIA</h3>
              </div>
              <a class="t-link" href="#"></a>
            </div>
          </div>
          <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="t-block t-block-small">
              <img src="{{ url('front/assets/images/rockford.png')}}" alt="ROCKFORD">
              <div class="t-block-overlay">
                <h3>ROCKFORD</h3>
              </div>
              <a class="t-link" href="#"></a>
            </div>
          </div>
          <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="t-block t-block-small">
              <img src="{{ url('front/assets/images/semple.png')}}" alt="XXXXX">
              <div class="t-block-overlay">
                <h3>XXXXX</h3>
              </div>
              <a class="t-link" href="#"></a>
            </div>
          </div>
          <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="t-block t-block-small">
              <img src="{{ url('front/assets/images/semple.png')}}" alt="XXXXX">
              <div class="t-block-overlay">
                <h3>XXXXX</h3>
              </div>
              <a class="t-link" href="#"></a>
            </div>
          </div>
        </div>
      </div>

      <div class="newsletter d-flex flex-sm-column flex-md-row w-100 float-left bg-primary">
        <h1 class="text-white">SUBSCRIBE TO THE SOFTBALL CONNECTED NEWSLETTER</h1>
        <div class="newsletter_form">

            <input type="text" placeholder="ENTER YOUR EMAIL HERE" name="subscribe_newsletter_email" id="subscribe_newsletter_email">
            <button type="button" class="btn btn-yellow" onclick="subscribeNewsletter();"><img src="{{ url('front/assets/images/newssubmit.png')}}" alt=""></button>
            <div class="subscribe_newsletter_message_block"><span id='subscribe_newsletter_message'></span></div>

        </div>
      </div>

      <div class="visit-block d-flex justify-content-center align-items-center bg-muted w-100 float-left">
        <h1 class="text-primary text-uppercase">VISIT OUR SISTER SITE</h1> <a href="http://baseballconnected.com/" target="_blank"><img src="{{ url('front/assets/images/visit-logo.png')}}" alt=""></a>
      </div>

      <div class="social-blocks float-left w-100">
        <div class="row">

          <div class="col-sm-12 col-md-6">
            <div class="social-box-wrap">
              <div class="page-block-heading social-box-title bg-primary">
                <div class="page-block-title h6 text-uppercase">Instagram</div>
              </div>
              <div class="social-box-body">
                <ul class="list-unstyled">
                  @if (count($instagram_feed_data) > 0)
                    @foreach ($instagram_feed_data as $key => $value)
                      <li class="media">
                        <a href="{{ $value['link'] }}"><img src="{{ $value['images']['thumbnail']['url'] }}"></a>
                        <div class="media-body">
                          <p> {!! $value['caption']['text'] !!} </p>
                        </div>
                      </li>
                    @endforeach
                  @elseif(!empty($instagram_error_message))
                    <li class="media">
                      <div class="media-body">
                        <p>{{ $instagram_error_message }} </p>
                      </div>
                    </li>
                  @else
                    <li class="media">
                      <div class="media-body">
                        <p>There are zero instagram found. </p>
                      </div>
                    </li>
                  @endif
                </ul>
              </div>
            </div>
          </div>
          <div class="col-sm-12 col-md-6">
            <div class="social-box-wrap">
              <div class="page-block-heading social-box-title bg-primary">
                <div class="page-block-title h6 text-uppercase">Blog</div>
              </div>
              <div class="social-box-body">
                <ul class="list-unstyled">
                  @if (count($blogList) > 0)
                    @foreach ($blogList as $key => $value)
                      <li class="media">
                        <div class="media-body">
                          <a href="{{ url('/') }}/{{$value->url_key}}" title="{{ $value->page_title }}" class="media-title text-primary"> {!! $value->page_title !!} </a>
                          @if(!empty($value->short_content))
                            <p> {!! $value->short_content !!} </p>
                          @endif
                        </div>
                      </li>
                    @endforeach
                  @else
                    <li class="media">
                      <div class="media-body">
                        <p>There are zero blog found. </p>
                      </div>
                    </li>
                  @endif

                </ul>
              </div>
            </div>
          </div>

          <div class="col-12">
            <div class="social-media">
              <ul>
                @if(!empty(trim($instagramUrl)))
                  <li>
                    <a class="s-media" href="{{ $instagramUrl }}" target="_blank" title="Instagram">
                      <i class="fab fa-instagram"></i>
                    </a>
                  </li>
                @endif
                @if(!empty(trim($facebookUrl)))
                  <li>
                    <a class="s-media" href="{{ $facebookUrl }}" target="_blank" title="Facebook">
                      <i class="fab fa-facebook-f"></i>
                    </a>
                  </li>
                @endif
                @if(!empty(trim($twitterUrl)))
                  <li>
                    <a class="s-media" href="{{ $twitterUrl }}" target="_blank" title="Twitter">
                      <i class="fab fa-twitter"></i>
                    </a>
                  </li>
                @endif
              </ul>
            </div>
          </div>

        </div>
      </div>

    </div>
  </section>
@endsection
@section('javascript')
  <script>
      window.subscribe_newsletter ="{{ URL::to('subscribe_newsletter') }}";
      function subscribeNewsletter(){

          showLoader();
          var email = $('#subscribe_newsletter_email').val();
          $('span#subscribe_newsletter_message').html("");

          /* Check validation */
          if(ValidateEmail(email)){

              jQuery.ajax({
                  url: window.subscribe_newsletter,
                  method: 'post',
                  dataType: 'JSON',
                  data : { '_token': window._token , 'email' : email },
                  success: function(response){

                      if(response.success == true && response.already_subscribed != true){
                          $('span#subscribe_newsletter_message').html('');
                          $("#subscribe_newsletter_email").val("");
                      }

                      $('span#subscribe_newsletter_message').html(response.message);
                      hideLoader();
                  },
                  error: function (xhr, status) {
                      $('span#subscribe_newsletter_message').html("Something went wrong");
                      hideLoader();
                  }
              });
          }else{

              /* email is null */
              if(email != undefined || email == '' || email == null){

                  $('span#subscribe_newsletter_message').html("Please enter email address");

              }else{

                  $('span#subscribe_newsletter_message').html("Please enter a valid email address");

              }
          }
          hideLoader();
          setTimeout(function(){ $('span#subscribe_newsletter_message').html(""); }, 3000);
      }
  </script>
@endsection