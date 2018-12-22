@inject('request', 'Illuminate\Http\Request')
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img class="img-circle" src="{{url('images/person-placeholder.jpg')}}" alt="User profile picture">
            </div>
            <div class="pull-left info">
                <p>
                    {{ Auth::guard('member')->user()->first_name }}
                    {{ Auth::guard('member')->user()->last_name }}
                </p>
                <!-- Status -->
                <a href="#"><i class="fa text-success"></i>
                    Member
                </a>
            </div>
        </div>
        <!-- search form (Optional) -->
        <!-- /.search form -->
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu" data-widget="tree">
            <!-- Optionally, you can add icons to the links -->

            <li>
                <a href="{{ url('') }}"><i class="fa fa-users"></i><span>@lang('quickadmin.qa_home')</span></a>
            </li>

            <li>
                <a href="{{ url( Auth::guard('member')->user()->url_key ) }}"><i class="fa fa-users"></i><span>@lang('quickadmin.qa_my_modules')</span></a>
            </li>

            <li class="{{ $request->segment(2) == 'home' ? 'active' : '' }}"><a href="{{ url('/member/home') }}"><i
                            class="fa fa-dashboard"></i><span>@lang('quickadmin.qa_dashboard')</span></a>
            </li>

            <!-- academies -->
            @can('member_academies')
                <li class="{{ $request->segment(2) == 'academies' ? 'active' : '' }}
                {{ $request->segment(2) == 'tournament' ? 'active' : '' }}">
                    <a href="{{ route('member.academies.index') }}"><i
                                class="fa fa-users"></i><span>@lang('quickadmin.academy.title')</span></a>
                </li>

            @endcan
        <!-- teams -->
            @can('member_teams')
                <li class="treeview  {{ $request->segment(2) == 'teams' ? 'active' : '' }}">

                    <a href="#"><i class="fa fa-users"></i> <span>@lang('quickadmin.teams.title')</span>
                        <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
                    </a>
                    <ul class="treeview-menu active menu-open">
                        <li class="{{ $request->segment(2) == 'teams' ? 'active' : '' }}">
                            <a href="{{ route('member.teams.index') }}"><span>@lang('quickadmin.teams.title')</span></a>
                        </li>

                    </ul>
                </li>
            @endcan

        <!-- tournament_organizations -->
            @can('member_tournaments')
                <li class="{{ $request->segment(2) == 'tournament_organizations' ? 'active' : '' }}
                {{ $request->segment(2) == 'tournament' ? 'active' : '' }}">
                    <a href="{{ route('member.tournament_organizations.index') }}"><i
                                class="fa fa-users"></i><span>@lang('quickadmin.tournament_organization.title')</span></a>
                </li>
            @endcan

        <!-- showcase_organizations -->
            @can('member_showcase')
                <li class="treeview {{ $request->segment(2) == 'showcase_organization' ? 'active' : '' }}
                {{ $request->segment(2) == 'showcase_or_prospect' ? 'active' : '' }}
                {{ $request->segment(2) == 'showcase_age_groups' ? 'active' : '' }}">
                    <a href="#"><i class="fa fa-users"></i> <span>@lang('quickadmin.showcase_organization.title')</span>
                        <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
                    </a>
                    <ul class="treeview-menu active menu-open">
                        <li class="{{ $request->segment(2) == 'showcase_organization' ? 'active' : '' }}">
                            <a href="{{ route('member.showcase_organization.index') }}">@lang('quickadmin.showcase_organization.title')</a>
                        </li>

                        <li class="{{ $request->segment(2) == 'showcase_or_prospect' ? 'active' : '' }}">
                            <a href="{{ route('member.showcase_or_prospect.index') }}">@lang('quickadmin.showcase_or_prospect.title')</a>
                        </li>

                        <!--<li class="{{ $request->segment(2) == 'showcase_age_groups' ? 'active' : '' }}">
                            <a href="{{ route('member.showcase_age_groups.index') }}">@lang('quickadmin.showcase_age_groups.title')</a>
                        </li>-->

                    </ul>
                </li>
            @endcan


        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>