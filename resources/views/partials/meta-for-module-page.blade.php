@section('meta')
  @if(!empty( $metaTitle ))
    <meta name="title" content="{{ $metaTitle }}">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta itemprop="name" content="{{ $metaTitle }}">
  @endif
  @if(!empty( $metaKeyword ))
    <meta name="keywords" content="{{ $metaKeyword }}">
  @endif
  @if(!empty( $metaDescription ))
    <meta name="description" content="{{ $metaDescription }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
  @endif
  @if(!empty($metaImage))
    <meta property="og:image" content="{{ $metaImage }}">
    <meta itemprop="image" content="{{ $metaImage }}">
    <meta name="twitter:image" content="{{ $metaImage }}">
  @endif
@endsection