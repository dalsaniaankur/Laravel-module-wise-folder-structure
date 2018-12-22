@if(!empty($sideBannerAds[0]) || !empty($sideBannerAds[1]) || !empty($sideBannerAds[2]))
  <div class="row ads-block">
    @if(!empty($sideBannerAds[0]) && !empty(trim($sideBannerAds[0]->image_path)))
    @php($alt = !empty( trim($sideBannerAds[0]->alt_image_text) ) ? "alt='".$sideBannerAds[0]->alt_image_text."'" : '' )
    <div class="col-md-4">
      <div class="ad-block d-flex align-items-center justify-content-center">
        <a href="{{ $sideBannerAds[0]->forward_url }}" target="_blank" attr-banner_ads_id="{{ $sideBannerAds[0]->banner_ads_id }}" attr-page_url="{{ Request::url() }}" onclick="bannerTracking( this );" >  
          <img src="{{ url('/') }}/{{ $sideBannerAds[0]->image_path }}" {{ $alt }}>
        </a>
      </div>
    </div>
    @endif

    @if(!empty($sideBannerAds[1]) && !empty(trim($sideBannerAds[1]->image_path)))
    <div class="col-md-4">
      <div class="ad-block d-flex align-items-center justify-content-center">
        <a href="{{ $sideBannerAds[1]->forward_url }}" target="_blank" attr-banner_ads_id="{{ $sideBannerAds[1]->banner_ads_id }}" attr-page_url="{{ Request::url() }}" onclick="bannerTracking( this );" >    
          <img src="{{ url('/') }}/{{ $sideBannerAds[1]->image_path }}" alt="{{ $sideBannerAds[1]->alt_image_text }}">
        </a>
      </div>
    </div>
    @endif

    @if(!empty($sideBannerAds[2]) && !empty(trim($sideBannerAds[2]->image_path)))
    <div class="col-md-4">
      <div class="ad-block d-flex align-items-center justify-content-center">
        <a href="{{ $sideBannerAds[2]->forward_url }}" target="_blank" attr-banner_ads_id="{{ $sideBannerAds[2]->banner_ads_id }}" attr-page_url="{{ Request::url() }}" onclick="bannerTracking( this );" >    
          <img src="{{ url('/') }}/{{ $sideBannerAds[2]->image_path }}" alt="{{ $sideBannerAds[2]->alt_image_text }}">
        </a>
      </div>
    </div>
    @endif
  </div>
@endif