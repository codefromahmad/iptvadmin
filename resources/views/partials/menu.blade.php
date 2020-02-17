<aside class="main-sidebar sidebar-dark-primary elevation-4" style="min-height: 917px;">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <span class="brand-text font-weight-light">IPTV Admin</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route("admin.dashboard.home") }}" class="nav-link {{ request()->is('admin/home') || request()->is('admin') ? 'active' : '' }}">
                        <p>
                            <i class="fas fa-tachometer-alt">

                            </i>
                            <span>{{ trans('global.dashboard') }}</span>
                        </p>
                    </a>
                </li>
                @can('iptvuser_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.iptvuser.index") }}" class="nav-link {{ request()->is('admin/iptvuser') || request()->is('admin/iptvuser/*') ? 'active' : '' }}" >
                            <i class="fas fa-user">

                            </i>
                            <p>
                                <span>Iptv Users</span>
                            </p>
                        </a>
                    </li>
                @endcan
                @can('news_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.news.index") }}" class="nav-link {{ request()->is('admin/news') || request()->is('admin/news/*') ? 'active' : '' }}" >
                            <i class="fas fa-newspaper">

                            </i>
                            <p>
                                <span>News</span>
                            </p>
                        </a>
                    </li>
                @endcan
                <li class="nav-item">
                    <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                        <p>
                            <i class="fas fa-sign-out-alt">

                            </i>
                            <span>{{ trans('global.logout') }}</span>
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
