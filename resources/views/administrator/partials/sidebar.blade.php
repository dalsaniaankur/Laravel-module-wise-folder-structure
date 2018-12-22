@inject('request', 'Illuminate\Http\Request')
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                @if (Auth::guard('administrator')->user()->profile_picture)
                    <img class="img-circle" src="{{url(Auth::guard('administrator')->user()->profile_picture)}}"
                         alt="User profile picture">
                @else
                    <img class="img-circle" src="{{url('images/person-placeholder.jpg')}}" alt="User profile picture">
                @endif
            </div>
            <div class="pull-left info">
                <p>
                    {{ Auth::guard('administrator')->user()->first_name }}
                    {{ Auth::guard('administrator')->user()->last_name }}
                </p>
                <!-- Status -->
                <a href="#"><i class="fa text-success"></i>
                    Admin
                </a>
            </div>
        </div>
        <!-- search form (Optional) -->
        <!-- /.search form -->
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu" data-widget="tree">
            <!-- Optionally, you can add icons to the links -->
            <li class="{{ $request->segment(2) == 'home' ? 'active' : '' }}"><a href="{{ url('/administrator') }}"><i
                            class="fa fa-dashboard"></i><span>@lang('quickadmin.qa_dashboard')</span></a>
            </li>

            <!-- user -->
            @can('user_management')
                <li class="{{ $request->segment(2) == 'users' ? 'active' : '' }}">
                    <a href="{{ route('administrator.users.index') }}"><i
                                class="fa fa-users"></i><span>@lang('quickadmin.user.title')</span></a>
                </li>
            @endcan

        <!-- members -->
            @can('members')
                <li class="{{ $request->segment(2) == 'members' ? 'active' : '' }}">
                    <a href="{{ route('administrator.members.index') }}"><i class="fa fa-users"></i>
                        <span>@lang('quickadmin.member.title')</span></a>
                </li>
            @endcan

        <!-- event -->
            @can('events')
                <li class="{{ $request->segment(2) == 'events' ? 'active' : '' }}">
                    <a href="{{ route('administrator.events.index') }}"><i
                                class="fa fa-users"></i><span>@lang('quickadmin.event.title')</span></a>
                </li>
            @endcan

        <!-- instructors -->
            @can('instructors')
                <li class="treeview  {{ $request->segment(2) == 'instructors' ? 'active' : '' }}
                {{ $request->segment(2) == 'instructor_review' ? 'active' : '' }}">
                    <a href="#"><i class="fa fa-users"></i> <span>@lang('quickadmin.instructor.title')</span>
                        <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
                    </a>
                    <ul class="treeview-menu active menu-open">
                        <li class="{{ $request->segment(2) == 'instructors' ? 'active' : '' }}">
                            <a href="{{ route('administrator.instructors.index') }}">@lang('quickadmin.instructor.title')</a>
                        </li>
                        @can('instructor_review_submissions')
                            <li class="{{ $request->segment(2) == 'instructor_review' ? 'active' : '' }}">
                                <a href="{{ route('administrator.instructor_review.index') }}">@lang('quickadmin.instructor_review.title')</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan

        <!-- academies -->
            @can('academies')
                @can('academies')
                    <li class="{{ $request->segment(2) == 'academies' ? 'active' : '' }}">
                        <a href="{{ route('administrator.academies.index') }}"><i
                                    class="fa fa-users"></i><span>@lang('quickadmin.academy.title')</span></a>
                    </li>
                @endcan
            @endcan

        <!-- Teams -->
            @can('teams')
                <li class="treeview  {{ $request->segment(2) == 'teams' ? 'active' : '' }}
                {{ $request->segment(2) == 'gallery' ? 'active' : '' }}
                {{ $request->segment(2) == 'team_group' ? 'active' : '' }}">

                    <a href="#"><i class="fa fa-users"></i> <span>@lang('quickadmin.teams.title')</span>
                        <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
                    </a>
                    <ul class="treeview-menu active menu-open">
                        <li class="{{ $request->segment(2) == 'teams' ? 'active' : '' }}">
                            <a href="{{ route('administrator.teams.index') }}"><span>@lang('quickadmin.teams.title')</span></a>
                        </li>
                        @can('team_photo_gallery')
                            <li class="{{ $request->segment(2) == 'gallery' ? 'active' : '' }}">
                                <a href="{{ route('administrator.gallery.index') }}">@lang('quickadmin.gallery.title')</a>
                            </li>
                        @endcan
                        @can('team_groups')
                            <li class="{{ $request->segment(2) == 'team_group' ? 'active' : '' }}">
                                <a href="{{ route('administrator.team_group.index') }}">@lang('quickadmin.team_group.title')</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan

        <!-- tournament_organizations -->
            @can('tournament_organizations')
                <li class="{{ $request->segment(2) == 'tournament_organizations' ? 'active' : '' }}
                {{ $request->segment(2) == 'tournament' ? 'active' : '' }}">
                    <a href="{{ route('administrator.tournament_organizations.index') }}"><i
                                class="fa fa-users"></i><span>@lang('quickadmin.tournament_organization.title')</span></a>
                </li>
            @endcan

        <!-- coaches_needed -->
            @can('coaches_needed')
                <li class="{{ $request->segment(2) == 'coaches_needed' ? 'active' : '' }}">
                    <a href="{{ route('administrator.coaches_needed.index') }}"><i class="fa fa-users"></i>
                        <span>@lang('quickadmin.coaches_needed.title')</span>
                    </a>
                </li>
            @endcan
            @can('players_looking_for_a_team')
                <li class="{{ $request->segment(2) == 'lookup_for_player_experience' ? 'active' : '' }}">
                    <a href="{{ route('administrator.lookup_for_player_experience.index') }}"><i
                                class="fa fa-users"></i>
                        <span>@lang('quickadmin.lookup_for_player_experience.title')</span>
                    </a>
                </li>
            @endcan

        <!-- showcase_organizations -->
            @can('showcase_organizations')
                <li class="treeview {{ $request->segment(2) == 'showcase_organization' ? 'active' : '' }}
                {{ $request->segment(2) == 'camp_or_clinic' ? 'active' : '' }}
                {{ $request->segment(2) == 'showcase_or_prospect' ? 'active' : '' }}
                {{ $request->segment(2) == 'showcase_age_groups' ? 'active' : '' }}">
                    <a href="#"><i class="fa fa-users"></i> <span>@lang('quickadmin.showcase_organization.title')</span>
                        <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
                    </a>
                    <ul class="treeview-menu active menu-open">
                        <li class="{{ $request->segment(2) == 'showcase_organization' ? 'active' : '' }}">
                            <a href="{{ route('administrator.showcase_organization.index') }}">@lang('quickadmin.showcase_organization.title')</a>
                        </li>
                        @can('camp_or_clinic')
                            <li class="{{ $request->segment(2) == 'camp_or_clinic' ? 'active' : '' }}">
                                <a href="{{ route('administrator.camp_or_clinic.index') }}">@lang('quickadmin.camp_or_clinic.title')</a>
                            </li>
                        @endcan
                        @can('showcase_or_prospect')
                            <li class="{{ $request->segment(2) == 'showcase_or_prospect' ? 'active' : '' }}">
                                <a href="{{ route('administrator.showcase_or_prospect.index') }}">@lang('quickadmin.showcase_or_prospect.title')</a>
                            </li>

                            <li class="{{ $request->segment(2) == 'showcase_age_groups' ? 'active' : '' }}">
                                <a href="{{ route('administrator.showcase_age_groups.index') }}">@lang('quickadmin.showcase_age_groups.title')</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan

        <!-- email_template    -->
            @can('email_templates')
                <li class="{{ $request->segment(2) == 'email_template' ? 'active' : '' }}">
                    <a href="{{ route('administrator.email_template.index') }}"><i
                                class="fa fa-users"></i><span>@lang('quickadmin.email_template.title')</span></a>
                </li>
            @endcan

        <!-- categories  -->
            @can('categories')
                <li class="{{ $request->segment(2) == 'categories' ? 'active' : '' }}">
                    <a href="{{ route('administrator.categories.index') }}"><i
                                class="fa fa-users"></i><span>@lang('quickadmin.categories.title')</span></a>
                </li>
            @endcan

        <!-- Page Builder  -->
            @can('page_builder')
                <li class="{{ $request->segment(2) == 'page_builder' ? 'active' : '' }}">
                    <a href="{{ route('administrator.page_builder.index') }}"><i
                                class="fa fa-users"></i><span>@lang('quickadmin.page_builder.title')</span></a>
                </li>
            @endcan

        <!-- Banner Ads Category -->
            @can('banner_ads_category')
                <li class="{{ $request->segment(2) == 'banner_ads_category' ? 'active' : '' }}">
                    <a href="{{ route('administrator.banner_ads_category.index') }}"><i
                                class="fa fa-users"></i><span>@lang('quickadmin.banner_ads_category.title')</span></a>
                </li>
            @endcan

        <!-- subscribes -->
            @can('subscribes')
                <li class="{{ $request->segment(2) == 'subscribes' ? 'active' : '' }}">
                    <a href="{{ route('administrator.subscribes.index') }}"><i
                                class="fa fa-users"></i><span>@lang('quickadmin.subscribes.title')</span></a>
                </li>
            @endcan

        <!-- banner_tracking -->
            @can('banner_trackings')
                <li class="{{ $request->segment(2) == 'banner_tracking' ? 'active' : '' }}">
                    <a href="{{ route('administrator.banner_tracking.index') }}"><i
                                class="fa fa-users"></i><span>@lang('quickadmin.banner_tracking.title')</span></a>
                </li>
            @endcan

        <!-- Configuration -->
            @can('configuration')
                <li class="{{ $request->segment(2) == 'configuration' ? 'active' : '' }}">
                    <a href="{{ route('administrator.configuration') }}">
                        <i class="fa fa-circle-o text-red"></i>
                        <span class="title">@lang('quickadmin.administrator-configuration.title')</span>
                    </a>
                </li>
        @endcan
        <!-- change password -->
            <li class="{{ $request->segment(1) == 'administrator_change_password' ? 'active' : '' }}">
                <a href="{{ route('administrator.auth.change_password') }}">
                    <i class="fa fa-key"></i>
                    <span class="title">@lang('quickadmin.qa_change_password')</span>
                </a>
            </li>
        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>