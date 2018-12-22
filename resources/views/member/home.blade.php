@extends('member.layouts.app')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> @lang('quickadmin.qa_dashboard')</h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <!--------------------------
          | Your Page Content Here |
          -------------------------->
        <div class="row">
            <div class="col-md-12"> @if (Session::has('message'))
                    <div class="alert alert-info">
                        <p>{{ Session::get('message') }}</p>
                    </div>
                @endif
                @if ($errors->count() > 0)
                    <div class="alert alert-danger">
                        <ul class="list-unstyled">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
            @endif
            <!-- Main content -->
                <div class="row">
                    @can('member_academies')
                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3>{{$totalAcademies}}<sup style="font-size: 20px"></sup></h3>
                                <p>Total @lang('quickadmin.academy.title')</p>
                            </div>
                            <div class="icon"> <i class="ion ion-stats-bars"></i> </div>
                            <a href="{{ url('/member/academies') }}" class="small-box-footer">View All <i class="fa fa-arrow-circle-right"></i></a> </div>
                    </div>
                    @endcan
                    @can('member_teams')
                    <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-green">
                            <div class="inner">
                                <h3>{{$totalTeam}}<sup style="font-size: 20px"></sup></h3>
                                <p>Total @lang('quickadmin.teams.title')</p>
                            </div>
                            <div class="icon"> <i class="ion ion-stats-bars"></i> </div>
                            <a href="{{ url('/member/teams') }}" class="small-box-footer">View All <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    @endcan
                    @can('tournaments')
                    <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-yellow">
                            <div class="inner">
                                <h3>{{$totalTournamentOrganization}}<sup style="font-size: 20px"></sup></h3>
                                <p>Total @lang('quickadmin.tournament_organization.title')</p>
                            </div>
                            <div class="icon"> <i class="ion ion-stats-bars"></i> </div>
                            <a href="{{ url('/member/tournament_organizations') }}" class="small-box-footer">View All <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    @endcan
                    @can('member_showcase')
                    <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3>{{$totalShowcaseOrganization}}<sup style="font-size: 20px"></sup></h3>
                                <p>Total @lang('quickadmin.showcase_organization.title')</p>
                            </div>
                            <div class="icon"> <i class="ion ion-stats-bars"></i> </div>
                            <a href="{{ url('/member/showcase_organization') }}" class="small-box-footer">View All <i class="fa fa-arrow-circle-right"></i></a> </div>
                    </div>
                    @endcan
                    @can('member_showcase')
                    <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-orange">
                            <div class="inner">
                                <h3>{{$totalShowCases}}<sup style="font-size: 20px"></sup></h3>
                                <p>Total @lang('quickadmin.showcase_or_prospect.title')</p>
                            </div>
                            <div class="icon"> <i class="ion ion-stats-bars"></i> </div>
                            <a href="{{ url('/member/showcase_or_prospect') }}" class="small-box-footer">View All <i class="fa fa-arrow-circle-right"></i></a> </div>
                    </div>
                    @endcan
                </div>
                <!-- /.row -->
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection 