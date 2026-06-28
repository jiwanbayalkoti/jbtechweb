<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('tenant.dashboard') }}" class="brand-link">
        <span class="brand-text font-weight-light">{{ auth()->user()->tenant->name }}</span>
    </a>
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column">
                <li class="nav-item">
                    <a href="{{ route('tenant.dashboard') }}" class="nav-link {{ request()->routeIs('tenant.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('tenant.websites.index') }}" class="nav-link {{ request()->routeIs('tenant.websites.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-globe"></i>
                        <p>Website Settings</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('tenant.pages.index') }}" class="nav-link {{ request()->routeIs('tenant.pages.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file"></i>
                        <p>Pages</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('tenant.blogs.index') }}" class="nav-link {{ request()->routeIs('tenant.blogs.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-blog"></i>
                        <p>Blog</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('tenant.services.index') }}" class="nav-link {{ request()->routeIs('tenant.services.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>Services</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('tenant.plan-requests.index') }}" class="nav-link {{ request()->routeIs('tenant.plan-requests.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-envelope-open-text"></i>
                        <p>Plan Requests</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('tenant.portfolios.index') }}" class="nav-link {{ request()->routeIs('tenant.portfolios.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-images"></i>
                        <p>Portfolio</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('tenant.testimonials.index') }}" class="nav-link {{ request()->routeIs('tenant.testimonials.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-quote-right"></i>
                        <p>Testimonials</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('tenant.careers.index') }}" class="nav-link {{ request()->routeIs('tenant.careers.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-briefcase"></i>
                        <p>Careers</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('tenant.media.index') }}" class="nav-link {{ request()->routeIs('tenant.media.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-folder-open"></i>
                        <p>Media</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
