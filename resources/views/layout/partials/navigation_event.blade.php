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
                    <li><a href="{{ route('event.overview') }}">Overview</a></li>
                    <li><a href="{{ route('event.products') }}">Products</a></li>
                    <li><a href="{{ route('event.storages') }}">Storages</a></li>
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

                <p class="navbar-text navbar-right">
                    Current Event <strong> {{ Auth::user()->Event()->name }} </strong>
                    (<a href="{{ route('event.logout') }}" class="navbar-link">Logout</a>)
                </p>
            @endif

            @if(Auth::check() && Auth::user()->admin && Auth::user()->Event()->activeAPI)
                <p class="navbar-text navbar-right">
                    API Status <strong class="api-on">API Turned On</strong>
                    (<a href="{{ route('izettle.deactivateAPI', $user->Event()->eventID) }}" class="navbar-link">Turn API Off</a>)
                </p>
            @else
                <p class="navbar-text navbar-right">
                    API Status <strong class="api-off">API Turned Off</strong>
                    (<a href="{{ route('izettle.activateAPI', $user->Event()->eventID) }}" class="navbar-link">Turn API On</a>)
                </p>
            @endif
        </div>
    </div>
</div>