<header class="main-header">
	<div class="container">
		<nav class="navbar navbar-expand-lg">
			<div class="navbarLeft">
				<a class="navbar-brand" href="{{ url('') }}"><img src="{{ url('front/assets/images/sbc-logo.png')}}" alt="SBC"></a>
			</div>
			<div class="navbarRight ml-auto">
				<div class="navbar-collapse">
					<ul class="navbar-nav mr-auto mt-2 mt-lg-0">
						<li class="nav-item">
							<a class="navbar-link" href="{{ url('tournaments') }}">TOURNAMENTS</a>
						</li>
						<li class="nav-item">
							<a class="navbar-link" href="{{ url('teams') }}">TEAMS</a>
						</li>
						<li class="nav-item">
							<a class="navbar-link" href="{{ url('tryouts') }}">TRYOUTS</a>
						</li>
						<li class="nav-item">
							<a class="navbar-link" href="{{ url('academies') }}">ACADEMIES</a>
						</li>
						<li class="nav-item">
							<a class="navbar-link" href="{{ url('showcases') }}">SHOWCASES</a>
						</li>
						<li class="nav-item">
							<a class="navbar-link" href="{{ url('blog') }}">BLOG</a>
						</li>
					</ul>
				</div>
				<div class="navbar-form">
					<div class="navform-wrap">
						<input type="text" class="search-input" autocomplete="off" id="search" placeholder="SEARCH">
					</div>
					
					<div class="nav-buttons">
						@if (!empty(Auth::guard('member')->user()->member_id) && Auth::guard('member')->user()->member_id > 0)
							<a href="{{ url('member/home')}}" class="btn btn-custom btn-yellow">My Account</a>
						@else
							<a href="{{ route('member_login')}}" class="btn btn-custom btn-yellow">Login</a>
						@endif
					</div>
				</div>
				<button class="navbar-toggler" type="button"> <i class="fas fa-bars"></i> </button>
			</div>
		</nav>
	</div>
</header>
