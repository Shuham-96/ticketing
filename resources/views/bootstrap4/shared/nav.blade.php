<nav>
    <ul class="nav nav-pills justify-content-between">
        @if (auth()->check())
            @if (isAdmin())
                <li role="presentation" class="nav-item">
                    <a class="nav-link {!! fullUrlIs(action('\App\Http\Controllers\DashboardController@index')) || Request::is('admin-tickets/indicator*') ? 'active' : '' !!}" href="{{ action('\App\Http\Controllers\DashboardController@index') }}">{{ trans('admin.nav-dashboard') }}</a>
                </li>
            @endif
            <li role="presentation" class="nav-item">
                <a class="nav-link {{ !Request::is('tickets/complete') && (Request::is('tickets/*') || Request::is('tickets')) ? 'active' : '' }}"
                    href="{{ route('tickets.index') }}">{{ trans('lang.nav-active-tickets') }}
                    <span class="badge badge-pill badge-secondary ">
                        @if (isAdmin())
                            {{ App\Models\Ticket::active()->count() }}
                        @elseif (isAgent())
                            {{ App\Models\Ticket::active()->agentUserTickets(auth()->user()->id)->count() }}
                        @else
                            {{ App\Models\Ticket::userTickets(auth()->user()->id)->active()->count() }}
                        @endif
                    </span>
                </a>
            </li>
            <li role="presentation" class="nav-item">
                <a class="nav-link {{ Request::is('tickets/complete') ? 'active' : '' }}"
                    href="{{ route('tickets-complete') }}">{{ trans('lang.nav-completed-tickets') }}
                    <span class="badge badge-pill badge-secondary">
                        @if (isAdmin())
                            {{ App\Models\Ticket::complete()->count() }}
                        @elseif (isAgent())
                            {{ App\Models\Ticket::complete()->agentUserTickets(auth()->user()->id)->count() }}
                        @else
                            {{ App\Models\Ticket::userTickets(auth()->user()->id)->complete()->count() }}
                        @endif
                    </span>
                </a>
            </li>
            @if (isAdmin())
                <li role="presentation" class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {!! fullUrlIs(action('\App\Http\Controllers\StatusesController@index') . '*') || fullUrlIs(action('\App\Http\Controllers\PrioritiesController@index') . '*') || fullUrlIs(action('\App\Http\Controllers\AgentsController@index') . '*') || fullUrlIs(action('\App\Http\Controllers\CategoriesController@index') . '*') || fullUrlIs(action('\App\Http\Controllers\ConfigurationsController@index') . '*') || fullUrlIs(action('\App\Http\Controllers\AdministratorsController@index') . '*') ? 'active' : '' !!}" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        {{ trans('admin.nav-settings') }}
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item {!! fullUrlIs(action('\App\Http\Controllers\StatusesController@index') . '*') ? 'active' : '' !!}" href="{{ action('\App\Http\Controllers\StatusesController@index') }}">{{ __('admin.nav-statuses') }}</a>
                        <a class="dropdown-item {!! fullUrlIs(action('\App\Http\Controllers\PrioritiesController@index') . '*') ? 'active' : '' !!}" href="{{ action('\App\Http\Controllers\PrioritiesController@index') }}">{{ trans('admin.nav-priorities') }}</a>
                        <a class="dropdown-item {!! fullUrlIs(action('\App\Http\Controllers\AgentsController@index') . '*') ? 'active' : '' !!}" href="{{ action('\App\Http\Controllers\AgentsController@index') }}">{{ trans('admin.nav-agents') }}</a>
                        <a class="dropdown-item {!! fullUrlIs(action('\App\Http\Controllers\CategoriesController@index') . '*') ? 'active' : '' !!}" href="{{ action('\App\Http\Controllers\CategoriesController@index') }}">{{ trans('admin.nav-categories') }}</a>
                        {{-- <a class="dropdown-item {!! fullUrlIs(action('\App\Http\Controllers\ConfigurationsController@index') . '*') ? 'active' : '' !!}" href="{{ action('\App\Http\Controllers\ConfigurationsController@index') }}">{{ trans('admin.nav-configuration') }}</a> --}}
                        <a class="dropdown-item {!! fullUrlIs(action('\App\Http\Controllers\AdministratorsController@index') . '*') ? 'active' : '' !!}" href="{{ action('\App\Http\Controllers\AdministratorsController@index') }}">{{ trans('admin.nav-administrator') }}</a>
                    </div>
                </li>
            @endif
            <li role="presentation" class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        @endif
    </ul>
</nav>
