<nav class="app-header {{ config('adminlte.classes_topnav', 'navbar-expand bg-body') }} navbar">
    <div class="{{ config('adminlte.classes_topnav_container', 'container-fluid') }}">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button" aria-label="{{ __('Toggle sidebar') }}">
                    <i class="bi bi-list" aria-hidden="true"></i>
                </a>
            </li>
        </ul>

        <ul class="navbar-nav ms-auto">
            @include('adminlte::partials.navbar-notifications')

            <li class="nav-item">
                <a class="nav-link" href="#" data-lte-toggle="fullscreen" aria-label="{{ __('Toggle fullscreen') }}">
                    <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen" aria-hidden="true"></i>
                    <i data-lte-icon="minimize" class="bi bi-fullscreen-exit d-none" aria-hidden="true"></i>
                </a>
            </li>

            @if (config('adminlte.color_mode_toggle', true))
                @include('adminlte::partials.color-mode')
            @endif

            @if (config('adminlte.usermenu_enabled', true))
                @include('adminlte::partials.usermenu')
            @endif
        </ul>
    </div>
</nav>
