<!-- Main Header -->
  <header class="main-header">
    <!-- Logo -->
     <a href="{{ url('') }}" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
     <span class="logo-mini">
      	<img src="{{url('images/logos/fsm-small.png')}}" alt=@lang('quickadmin.quickadmin_title')/>
      </span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg">
      	<img src="{{url('images/logos/fsm-large.png')}}" alt=@lang('quickadmin.quickadmin_title')/>
      </span>
    </a>
    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- User Account Menu -->
          <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- The user image in the navbar-->
              @if (Auth::guard('member')->user()->profile_picture)
	        	  <img class="user-image" src="{{url(Auth::guard('member')->user()->profile_picture)}}" alt="User profile picture">
         	  @else
          	   <img  class="user-image" src="{{url('images/person-placeholder.jpg')}}" alt="User profile picture">
        	  @endif
     	      <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <span class="hidden-xs">
               {{ Auth::guard('member')->user()->first_name }}
               {{ Auth::guard('member')->user()->last_name }}
               </span>
            </a>
            <ul class="dropdown-menu">
              <!-- The user image in the menu -->
              <li class="user-header">
                 @if (Auth::guard('member')->user()->profile_picture)
	          		<img class="img-circle" src="{{url(Auth::guard('member')->user()->profile_picture)}}" alt="User profile picture">
          		 @else
             		<img  class="img-circle" src="{{url('images/person-placeholder.jpg')}}" alt="User profile picture">
        		 @endif
                <p>
                  {{ Auth::guard('member')->user()->first_name }} {{ Auth::guard('member')->user()->last_name }}
                  <small>Member since {{ DateFacades::dateFormat(Auth::guard('member')->user()->created_at,'formate-1') }}
                  </small>
                </p>
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="{{ route('member_profile') }}" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="#logout" onclick="$('#logout').submit();" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <!--<li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>-->
        </ul>
      </div>
    </nav>
  </header>
