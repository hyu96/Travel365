<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="{{ route('homepage') }}">
                <i class="fa fa-plane" aria-hidden="true"></i> Travel365
            </a>
        </div>
        <ul class="nav navbar-nav">
            <li id="home-navbar">
                <a href="{{ route('homepage') }}"><span class="glyphicon glyphicon-home"></span> Home</a>
            </li>
            <li id="create-navbar">
                <a href="{{ route('trips.create') }}">
                    <span class="glyphicon glyphicon glyphicon-plus-sign"></span> Create Trip
                </a>
            </li>
            <li id="noti-navbar">
                <a href="{{ route('users.noti') }}"><span class="glyphicon glyphicon-globe"></span> 
                    Notification (<span id="notify">{{ $notiNum }}</span>)
                </a>
            </li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            @if (Auth::check())
                <img src="{{ asset(Auth::user()->avatar) }}" style="width: 60px; height: 50px; float: left;">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        {{ Auth::user()->name }} <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('users.profile', Auth::id()) }}"><i class="fa fa-user" aria-hidden="true"></i> Profile</a></li>
                        <li>
                            <a href="{{ url('/logout') }}">
                                <span class="glyphicon glyphicon-log-out"></span> Logout 
                            </a>
                        </li>
                    </ul>
                </li>
            @else
                <li><a href="{{ url('/login') }}">Login</a></li>
                <li><a href="{{ url('/register') }}">Register</a></li>
            @endif
        </ul>
    </div>
</nav>