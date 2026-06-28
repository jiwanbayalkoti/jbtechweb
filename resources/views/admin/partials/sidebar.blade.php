<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
        <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
    </a>
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.tenants.index') }}" class="nav-link {{ request()->routeIs('admin.tenants.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-building"></i>
                        <p>Tenants</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.plans.index') }}" class="nav-link {{ request()->routeIs('admin.plans.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-box"></i>
                        <p>Plans</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.plan-requests.index') }}" class="nav-link {{ request()->routeIs('admin.plan-requests.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-envelope-open-text"></i>
                        <p>Plan Requests</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.subscriptions.index') }}" class="nav-link {{ request()->routeIs('admin.subscriptions.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-credit-card"></i>
                        <p>Subscriptions</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.invoices.index') }}" class="nav-link {{ request()->routeIs('admin.invoices.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-invoice-dollar"></i>
                        <p>Invoices</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>Settings</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
