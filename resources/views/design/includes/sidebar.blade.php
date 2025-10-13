    <!-- Mobile Menu Toggle -->
    <button class="menu-toggle" id="menuToggle" onclick="toggleSidebar()">☰</button>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" onclick="closeSidebar()"></div>

    <!-- Sidebar Navigation -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <h2>📊 PM System</h2>
            </div>
            <button class="close-sidebar" onclick="closeSidebar()">✕</button>
        </div>
        <ul class="nav-menu">
            {{-- <li class="nav-item active" onclick="showPage('dashboard')"> --}}
            <a class="link_remove" href="{{ route('dashboard') }}">
                <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <span class="nav-icon">🏠</span>
                    <span>Dashboard</span>
                </li>
            </a>
            {{-- <li class="nav-item" onclick="showPage('create')"> --}}
            <a class="link_remove" href="{{ route('projectmng.create') }}">
                <li class="nav-item {{ request()->routeIs('projectmng.create') ? 'active' : '' }}">
                    <span class="nav-icon">➕</span>
                    <span>Create New</span>
                </li>
            </a>
            <a class="link_remove" href="{{ route('projectmng.list') }}">
                <li class="nav-item {{ request()->routeIs('projectmng.list') ? 'active' : '' }}">
                    <span class="nav-icon">📋</span>
                    <span>All Items</span>
                </li>
            </a>
        </ul>
        <form method="POST" action="{{ route('logout') }}"
            onsubmit="return confirm('Are you sure you want to log out?')">
            @csrf
            <button type="submit" class="logout-btn logout-nav">
                <span class="nav-icon">🚪</span>
                <span>Logout</span>
            </button>
        </form>

    </div>
