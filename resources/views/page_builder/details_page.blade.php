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
              <div class="page-block-body d-flex">
                <div class="page-block-left">

                  <div class="single-blog">
                    <div class="card">
                      @if(!empty($page_builder->getMetaImage()))
                        <img class="card-img-top" src="{{ $page_builder->getMetaImage() }}" alt="image">
                      @endif
                      <div class="card-body">
                        <div class="card-topbar">
                          <h1 class="card-title">{!! $page_builder->page_title !!}</h1>
                          <div class="card-date"><i class="far fa-calendar-alt"></i> {{ DateFacades::dateFormat($page_builder->created_at,'formate-3') }}</div>
                        </div>
                        <div class="card-text">
                          {!! $page_builder->content !!}
                        </div>
                      </div>
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
@endsection