<li>
    <a href="{{ route('dashboard.index') }}" class="side-menu {{ str_starts_with($ROUTE, 'dashboard.') ? 'side-menu--active' : '' }}">
        <div class="side-menu__icon">@icon('home')</div>
        <div class="side-menu__title">{{ __('in-sidebar.dashboard') }}</div>
    </a>
</li>

@php ($active = str_starts_with($ROUTE, 'server'))

<li>
    <a href="javascript:;" class="side-menu {{ $active ? 'side-menu--active' : '' }}">
        <div class="side-menu__icon">@icon('server')</div>
        <div class="side-menu__title">
            {{ __('in-sidebar.server') }} <div class="side-menu__sub-icon {{ $active ? 'transform rotate-180' : '' }}">@icon('chevron-down')</div>
        </div>
    </a>

    <ul class="{{ $active ? 'side-menu__sub-open' : '' }}">
        <li>
            <a href="{{ route('server.index') }}" class="side-menu {{ ($ROUTE === 'server.index') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">@icon('list')</div>
                <div class="side-menu__title">{{ __('in-sidebar.server-index') }}</div>
            </a>
        </li>

        <li>
            <a href="{{ route('server.create') }}" class="side-menu {{ ($ROUTE === 'server.create') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">@icon('plus-circle')</div>
                <div class="side-menu__title">{{ __('in-sidebar.server-create') }}</div>
            </a>
        </li>
    </ul>
</li>

@php ($active = str_starts_with($ROUTE, 'user') || str_starts_with($ROUTE, 'ip-lock.'))

<li>
    <a href="javascript:;" class="side-menu {{ $active ? 'side-menu--active' : '' }}">
        <div class="side-menu__icon">@icon('users')</div>
        <div class="side-menu__title">
            {{ __('in-sidebar.user') }} <div class="side-menu__sub-icon {{ $active ? 'transform rotate-180' : '' }}">@icon('chevron-down')</div>
        </div>
    </a>

    <ul class="{{ $active ? 'side-menu__sub-open' : '' }}">
        <li>
            <a href="{{ route('user.index') }}" class="side-menu {{ ($ROUTE === 'user.index') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">@icon('list')</div>
                <div class="side-menu__title">{{ __('in-sidebar.user-index') }}</div>
            </a>
        </li>

        <li>
            <a href="{{ route('user.create') }}" class="side-menu {{ ($ROUTE === 'user.create') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">@icon('plus-circle')</div>
                <div class="side-menu__title">{{ __('in-sidebar.user-create') }}</div>
            </a>
        </li>

        <li>
            <a href="{{ route('user-session.index') }}" class="side-menu {{ str_starts_with($ROUTE, 'user-session.') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">@icon('key')</div>
                <div class="side-menu__title">{{ __('in-sidebar.user-session') }}</div>
            </a>
        </li>

        <li>
            <a href="{{ route('ip-lock.index') }}" class="side-menu {{ str_starts_with($ROUTE, 'ip-lock.') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">@icon('lock')</div>
                <div class="side-menu__title">{{ __('in-sidebar.ip-lock') }}</div>
            </a>
        </li>
    </ul>
</li>

@php ($active = str_starts_with($ROUTE, 'monitor.'))

<li>
    <a href="javascript:;" class="side-menu {{ $active ? 'side-menu--active' : '' }}">
        <div class="side-menu__icon">@icon('activity')</div>
        <div class="side-menu__title">
            {{ __('in-sidebar.monitor') }} <div class="side-menu__sub-icon {{ $active ? 'transform rotate-180' : '' }}">@icon('chevron-down')</div>
        </div>
    </a>

    <ul class="{{ $active ? 'side-menu__sub-open' : '' }}">
        <li>
            <a href="{{ route('monitor.index') }}" class="side-menu {{ ($ROUTE === 'monitor.index') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">@icon('server')</div>
                <div class="side-menu__title">{{ __('in-sidebar.monitor-index') }}</div>
            </a>
        </li>

        <li>
            <a href="{{ route('monitor.installation') }}" class="side-menu {{ ($ROUTE === 'monitor.installation') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">@icon('check-circle')</div>
                <div class="side-menu__title">{{ __('in-sidebar.monitor-installation') }}</div>
            </a>
        </li>

        <li>
            <a href="{{ route('monitor.database') }}" class="side-menu {{ ($ROUTE === 'monitor.database') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">@icon('database')</div>
                <div class="side-menu__title">{{ __('in-sidebar.monitor-database') }}</div>
            </a>
        </li>

        <li>
            <a href="{{ route('monitor.log') }}" class="side-menu {{ ($ROUTE === 'monitor.log') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">@icon('file-text')</div>
                <div class="side-menu__title">{{ __('in-sidebar.monitor-log') }}</div>
            </a>
        </li>
    </ul>
</li>

<li>
    <a href="{{ route('profile.update') }}" class="side-menu {{ str_starts_with($ROUTE, 'profile.update') ? 'side-menu--active' : '' }}">
        <div class="side-menu__icon">@icon('user')</div>
        <div class="side-menu__title">{{ __('in-sidebar.profile') }}</div>
    </a>
</li>

<li>
    <a href="{{ route('user.logout') }}" class="side-menu">
        <div class="side-menu__icon">@icon('toggle-right')</div>
        <div class="side-menu__title">{{ __('in-sidebar.logout') }}</div>
    </a>
</li>
