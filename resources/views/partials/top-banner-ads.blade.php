@if(count($topBannerAds) > 0)
  
  @if(count($topBannerAds) > 1)
    <div class="ad-wrapper">
      <div class="ad-block" style="height: auto;">
        <div class="ad--slider">
          @foreach ($topBannerAds as $key => $data)
            @php($alt = !empty( trim($data->alt_image_text) ) ? "alt='".$data->alt_image_text."'" : '' )
            @if(!empty(trim($data->image_path)))

              <a href="{{ $data->forward_url }}" target="_blank" attr-banner_ads_id="{{ $data->banner_ads_id }}" attr-page_url="{{ Request::url() }}" onclick="bannerTracking( this );" >
                <img src="{{ url('/') }}/{{ $data->image_path }}" {{ $alt }}>
              </a>
            @endif  
          @endforeach
        </div>
      </div>
    </div>
  @endif

  @if(count($topBannerAds) == 1)
   <div class="ad-wrapper">
      <div class="ad-block d-flex align-items-center justify-content-center" style="height: auto;">
          @php($alt = !empty( trim($topBannerAds[0]->alt_image_text) ) ? "alt='".$topBannerAds[0]->alt_image_text."'" : '' )
          @if(!empty(trim($topBannerAds[0]->image_path)))
          
          <a href="{{ $topBannerAds[0]->forward_url }}" target="_blank" attr-banner_ads_id="{{ $topBannerAds[0]->banner_ads_id }}" attr-page_url="{{ Request::url() }}" onclick="bannerTracking( this );">

            <img src="{{ url('/') }}/{{ $topBannerAds[0]->image_path }}" {{ $alt }}>
          </a>
        @endif
      </div>
    </div>
  @endif 

@endif  