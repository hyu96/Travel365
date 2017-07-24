<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">
                <i class="fa fa-plane" aria-hidden="true"></i> Travel365
            </a>
        </div>
        <ul class="nav navbar-nav">
            <li class="active">
                <a href="#"><span class="glyphicon glyphicon-home"></span> Home</a>
            </li>
            <li>
                <a href="#"><span class="glyphicon glyphicon glyphicon-plus-sign"></span> Create Trip</a>
            </li>
            <li>
                <a href="#"><span class="glyphicon glyphicon-globe"></span> Notification (0)</a>
            </li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            @if (Auth::check())
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user" aria-hidden="true"></i> {{ Auth::user()->name }} <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Profile</a></li>
                    </ul>
                </li>
                <li><a href="{{ url('/logout') }}"> Logout </a></li>
            @else
                <li><a href="{{ url('/login') }}">Login</a></li>
                <li><a href="{{ url('/register') }}">Register</a></li>
            @endif
        </ul>
    </div>
</nav>