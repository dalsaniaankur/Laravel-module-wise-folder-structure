@extends('layouts.app')
@section('content')
    <section class="page-block">
        <div class="container">
            <div class="bg-white float-left w-100">
                <div class="page-block-inner">
                    <div class="page-block-heading bg-primary">
                        <h1 class="page-block-title">SOFTBALL MEMBER MODULES LISTING</h1>
                    </div>
                    <div class="page-block-body d-flex">
                        <div class="page-block-left">
                            <div class="text-block">

                                <div class="row">
                                    @if (in_array("tournaments", $memberModuleList))
                                        <div class="col-sm-12 col-md-2 member-module-warpper">
                                            <div class="member-module-btn-warpper">
                                                <a href="{{ url($url_key.'/tournaments') }}"
                                                   class="member-module-btn btn btn-danger btn-flat">TOURNAMENTS</a>
                                            </div>
                                        </div>
                                    @endif
                                    @if (in_array("teams", $memberModuleList))
                                        <div class="col-sm-12 col-md-2 member-module-warpper">
                                            <div class="member-module-btn-warpper">
                                                <a href="{{ url($url_key.'/teams') }}"
                                                   class="member-module-btn btn btn-danger btn-flat">TEAMS</a>
                                            </div>
                                        </div>
                                    @endif
                                    @if (in_array("tryouts", $memberModuleList))
                                        <div class="col-sm-12 col-md-2 member-module-warpper">
                                            <div class="member-module-btn-warpper">
                                                <a href="{{ url($url_key.'/tryouts') }}"
                                                   class="member-module-btn btn btn-danger btn-flat">TRYOUTS</a>
                                            </div>
                                        </div>
                                    @endif
                                    @if (in_array("academies", $memberModuleList))
                                        <div class="col-sm-12 col-md-2 member-module-warpper">
                                            <div class="member-module-btn-warpper">
                                                <a href="{{ url($url_key.'/academies') }}"
                                                   class="member-module-btn btn btn-danger btn-flat">ACADEMIES</a>
                                            </div>
                                        </div>
                                    @endif
                                    @if (in_array("showcase", $memberModuleList))
                                        <div class="col-sm-12 col-md-2 member-module-warpper">
                                            <div class="member-module-btn-warpper">
                                                <a href="{{ url($url_key.'/showcases') }}"
                                                   class="member-module-btn btn btn-danger btn-flat">SHOWCASES</a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection