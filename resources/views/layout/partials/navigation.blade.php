<div class="navbar navbar-default navbar-static-top">
    <div class="container-fluid navbarPadding">
        <div class="navbar-header">
            <a class="navbar-brand brand-logo" href=" {{ URL::route('home') }}">
                <img alt="Brand" src="{{ URL::asset('/img/logo.png') }}" height="60px">
            </a>
            <a class="navbar-brand" href="{{ route('home') }}">
                <span class="pull-left">InventoryManager</span>
            </a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">

                @if(Auth::check())
                    <li><a href="{{ route('event.all') }}">All Events</a></li>
                @endif
            </ul>

            @if(Auth::check() && Auth::user()->admin)
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Admin <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="{{ route('admin.index') }}">Users</a>
                        </li>
                    </ul>
                </li>
            </ul>
            @endif

            @if(Auth::check())
            <p class="navbar-text navbar-right">
                Signed in as <strong> {{ Auth::user()->name }} </strong>
                (<a href="{{ route('auth.logout') }}" class="navbar-link">Logout</a>)
            </p>
            @endif
        </div>
    </div>
</div>