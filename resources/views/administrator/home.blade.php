@extends('administrator.layouts.app')
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
                    @can('user_management')
                        @if(isset($totalUsers))
                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <div class="small-box bg-green">
                                    <div class="inner">
                                        <h3>{{$totalUsers}}<sup style="font-size: 20px"></sup></h3>
                                        <p>Total Users</p>
                                    </div>
                                    <div class="icon"> <i class="ion ion-stats-bars"></i> </div>
                                    <a href="{{ url('/administrator/users') }}" class="small-box-footer">View All <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        @endif
                    @endcan
                    @can('members')
                        @if(isset($totalMembers))
                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <div class="small-box bg-yellow">
                                    <div class="inner">
                                        <h3>{{$totalMembers}}<sup style="font-size: 20px"></sup></h3>
                                        <p>Total @lang('quickadmin.member.title')</p>
                                    </div>
                                    <div class="icon"> <i class="ion ion-stats-bars"></i> </div>
                                    <a href="{{ url('/administrator/members') }}" class="small-box-footer">View All <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        @endif
                    @endcan
                    @can('events')
                        @if(isset($totalEvents))
                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <div class="small-box bg-orange">
                                    <div class="inner">
                                        <h3>{{$totalEvents}}<sup style="font-size: 20px"></sup></h3>
                                        <p>Total @lang('quickadmin.event.title')</p>
                                    </div>
                                    <div class="icon"> <i class="ion ion-stats-bars"></i> </div>
                                    <a href="{{ url('/administrator/events') }}" class="small-box-footer">View All <i class="fa fa-arrow-circle-right"></i></a> </div>
                            </div>
                        @endif
                    @endcan
                    @can('instructors')
                        @if(isset($totalInstructors))
                            <div class="col-lg-3 col-xs-6">
                                <div class="small-box bg-red">
                                    <div class="inner">
                                        <h3>{{$totalInstructors}}</h3>
                                        <p>Total @lang('quickadmin.instructor.title')</p>
                                    </div>
                                    <div class="icon"> <i class="ion ion-stats-bars"></i> </div>
                                    <a href="{{ url('/administrator/instructors') }}" class="small-box-footer">View All <i class="fa fa-arrow-circle-right"></i></a> </div>
                            </div>
                        @endif
                    @endcan
                    @can('academies')
                        @if(isset($totalAcademies))
                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <div class="small-box bg-aqua">
                                    <div class="inner">
                                        <h3>{{$totalAcademies}}<sup style="font-size: 20px"></sup></h3>
                                        <p>Total @lang('quickadmin.academy.title')</p>
                                    </div>
                                    <div class="icon"> <i class="ion ion-stats-bars"></i> </div>
                                    <a href="{{ url('/administrator/academies') }}" class="small-box-footer">View All <i class="fa fa-arrow-circle-right"></i></a> </div>
                            </div>
                        @endif
                    @endcan
                    @can('teams')
                        @if(isset($totalTeam))
                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <div class="small-box bg-green">
                                    <div class="inner">
                                        <h3>{{$totalTeam}}<sup style="font-size: 20px"></sup></h3>
                                        <p>Total @lang('quickadmin.teams.title')</p>
                                    </div>
                                    <div class="icon"> <i class="ion ion-stats-bars"></i> </div>
                                    <a href="{{ url('/administrator/teams') }}" class="small-box-footer">View All <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        @endif
                    @endcan
                    @can('tournament_organizations')
                        @if(isset($totalTournamentOrganization))
                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <div class="small-box bg-yellow">
                                    <div class="inner">
                                        <h3>{{$totalTournamentOrganization}}<sup style="font-size: 20px"></sup></h3>
                                        <p>Total @lang('quickadmin.tournament_organization.title')</p>
                                    </div>
                                    <div class="icon"> <i class="ion ion-stats-bars"></i> </div>
                                    <a href="{{ url('/administrator/tournament_organizations') }}" class="small-box-footer">View All <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        @endif
                    @endcan
                    @can('coaches_needed')
                        @if(isset($totalCoachesNeeded))
                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <div class="small-box bg-orange">
                                    <div class="inner">
                                        <h3>{{$totalCoachesNeeded}}<sup style="font-size: 20px"></sup></h3>
                                        <p>Total @lang('quickadmin.coaches_needed.title')</p>
                                    </div>
                                    <div class="icon"> <i class="ion ion-stats-bars"></i> </div>
                                    <a href="{{ url('/administrator/coaches_needed') }}" class="small-box-footer">View All <i class="fa fa-arrow-circle-right"></i></a> </div>
                            </div>
                        @endif
                    @endcan
                    @can('players_looking_for_a_team')
                        @if(isset($totalLookupForPlayerExperience))
                            <div class="col-lg-3 col-xs-6">
                                <div class="small-box bg-red">
                                    <div class="inner">
                                        <h3>{{$totalLookupForPlayerExperience}}</h3>
                                        <p>Total @lang('quickadmin.lookup_for_player_experience.title')</p>
                                    </div>
                                    <div class="icon"> <i class="ion ion-stats-bars"></i> </div>
                                    <a href="{{ url('/administrator/lookup_for_player_experience') }}" class="small-box-footer">View All <i class="fa fa-arrow-circle-right"></i></a> </div>
                            </div>
                        @endif
                    @endcan
                    @can('showcase_organizations')
                        @if(isset($totalShowcaseOrganization))
                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <div class="small-box bg-aqua">
                                    <div class="inner">
                                        <h3>{{$totalShowcaseOrganization}}<sup style="font-size: 20px"></sup></h3>
                                        <p>Total @lang('quickadmin.showcase_organization.title')</p>
                                    </div>
                                    <div class="icon"> <i class="ion ion-stats-bars"></i> </div>
                                    <a href="{{ url('/administrator/showcase_organization') }}" class="small-box-footer">View All <i class="fa fa-arrow-circle-right"></i></a> </div>
                            </div>
                        @endif
                    @endcan
                </div>
                <!-- /.row -->
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection 