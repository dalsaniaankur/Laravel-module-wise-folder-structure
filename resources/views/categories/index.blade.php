@extends('layouts.app')
@section('title', $pageTitle)
@section('meta')
  @if(!empty($categories->getMetaTitle()))
    <meta name="title" content="{{ $categories->getMetaTitle() }}">
    <meta property="og:title" content="{{ $categories->getMetaTitle() }}">
    <meta name="twitter:title" content="{{ $categories->getMetaTitle() }}">
    <meta itemprop="name" content="{{ $categories->getMetaTitle() }}">
  @endif
  @if(!empty($categories->getMetaKeywords()))
    <meta name="keywords" content="{{ $categories->getMetaKeywords() }}">
  @endif
  @if(!empty($categories->getMetaDescription()))
    <meta name="description" content="{{ $categories->getMetaDescription() }}">
    <meta property="og:description" content="{{ $categories->getMetaDescription() }}">
    <meta name="twitter:description" content="{{ $categories->getMetaDescription() }}">
  @endif
  @if(!empty($categories->getMetaImage()))
    <meta property="og:image" content="{{ $categories->getMetaImage() }}">
    <meta itemprop="image" content="{{ $categories->getMetaImage() }}">
    <meta name="twitter:image" content="{{ $categories->getMetaImage() }}">
  @endif
@endsection

@section('content')

<section class="page-block">
        <div class="container">
          <div class="bg-white float-left w-100">
            @include('partials.top-banner-ads')
            <div class="page-block-inner">
              <div class="page-block-heading bg-primary">
                <h1 class="page-block-title">SOFTBALL BLOG LISTING</h1>
              </div>
              <div class="page-block-body d-flex">
                <div class="page-block-left">
                  <div class="form-wrapper">
                    <div class="search-grid categories-list">
                        @if(!empty($page_builder))
                        @foreach ($page_builder as $key => $data)
                            <div class="blog-wapper">
                                <div class="blog-title">
                                    <a href="{{ url('/') }}/{{ $data->url_key}}" name="{{ $data->page_title }}">
                                        <span>{{ $data->page_title }}</span>
                                    </a>
                                </div>
                                <div class="blog-content text-block-body">
                                    @if(!empty($data->short_content))
                                        <p>{!! $data->short_content !!}</p>
                                    @endif
                                    <div class="blog-date"><i class="far fa-calendar-alt"></i> {{ DateFacades::dateFormat($data->created_at,'formate-3') }}</div>
                                </div>
                            </div>
                        @endforeach
                        @else
                          <div class="blog-no-record">@lang('quickadmin.qa_no_entries_in_table')</div>
                        @endif
                    </div>
                    <div class="pagination" style="text-align:center"> {!! $paging !!} </div>
                  </div>
                </div>
                <div class="page-block-right d-md-none d-lg-block">
                  @if(!empty($childCategories) && count($childCategories) > 0)
                    <div class="categories-wrap">
                        <div class="categories-list-header">
                            <span>CATEGORIES</span>
                        </div>
                        <ul class="categories-list-box">
                            @foreach ($childCategories as $key => $data)
                            <li class="categories-list"><a href="{{ \URL::to($categories->url_key) }}/{{ $data->url_key }}">{{ $data->title }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                  @endif
                  @include('partials.side-banner-ads')
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
@endsection