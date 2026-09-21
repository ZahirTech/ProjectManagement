<style>
    :root {
        --nav-height: 56px;
        --nav-bg: #ffffff;
        --nav-border: #dddfe2;
        --nav-icon: #65676b;
        --nav-icon-active: #1877f2;
        --nav-active-underline: #1877f2;
        --nav-badge: #e41e3f;
        --nav-hover-bg: #f0f2f5;
    }

    /* Prevent page content from hiding under the fixed bars */
    body {
        padding-top: var(--nav-height);
    }

    @media (max-width: 768px) {
        body {
            padding-top: 0;
            padding-bottom: var(--nav-height);
        }
    }

    .fb-navbar,
    .fb-navbar * {
        box-sizing: border-box;
    }

    /* ---------- Desktop / top bar ---------- */
    .fb-navbar {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        height: var(--nav-height);
        background: var(--nav-bg);
        border-bottom: 1px solid var(--nav-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 12px;
        z-index: 1000;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif;
    }

    .fb-navbar__brand {
        display: flex;
        align-items: center;
        gap: 8px;
        flex: 0 0 auto;
        min-width: 0;
    }

    .fb-navbar__logo {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--nav-icon-active);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 18px;
        flex-shrink: 0;
        text-decoration: none;
    }

    .fb-navbar__search {
        display: flex;
        align-items: center;
        gap: 8px;
        background: var(--nav-hover-bg);
        border-radius: 999px;
        padding: 8px 12px;
        width: 240px;
        max-width: 100%;
        color: var(--nav-icon);
        flex-shrink: 1;
    }

    .fb-navbar__search input {
        border: none;
        background: transparent;
        outline: none;
        font-size: 15px;
        width: 100%;
        color: #050505;
    }

    @media (max-width: 900px) {
        .fb-navbar__search {
            display: none;
        }
    }

    .fb-navbar__center {
        display: flex;
        align-items: stretch;
        height: 100%;
        flex: 1 1 auto;
        justify-content: center;
        gap: 4px;
    }

    .fb-navbar__item {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 112px;
        max-width: 112px;
        color: var(--nav-icon);
        text-decoration: none;
        border-bottom: 3px solid transparent;
    }

    .fb-navbar__item:hover {
        background: var(--nav-hover-bg);
        border-radius: 8px;
    }

    .fb-navbar__item svg {
        width: 24px;
        height: 24px;
    }

    .fb-navbar__item.active {
        color: var(--nav-icon-active);
        border-bottom-color: var(--nav-active-underline);
    }

    .fb-navbar__item.active:hover {
        background: transparent;
    }

    .fb-navbar__actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex: 0 0 auto;
    }

    .fb-navbar__icon-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--nav-hover-bg);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #050505;
        text-decoration: none;
        position: relative;
    }

    .fb-navbar__icon-btn svg {
        width: 20px;
        height: 20px;
    }

    .fb-navbar__avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        display: block;
    }

    /* Badge (shared by top bar + bottom bar) */
    .fb-navbar__badge {
        position: absolute;
        top: 2px;
        right: 18px;
        background: var(--nav-badge);
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        line-height: 1;
        min-width: 16px;
        height: 16px;
        border-radius: 999px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 4px;
        border: 2px solid var(--nav-bg);
    }

    .fb-navbar__icon-btn .fb-navbar__badge {
        top: -2px;
        right: -2px;
    }

    .fb-navbar__badge[data-empty="true"] {
        display: none;
    }

    /* ---------- Mobile / tablet: bottom bar ---------- */
    .fb-navbar-bottom {
        display: none;
    }

    @media (max-width: 768px) {
        .fb-navbar {
            display: none;
        }

        .fb-navbar-bottom {
            display: flex;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: var(--nav-height);
            background: var(--nav-bg);
            border-top: 1px solid var(--nav-border);
            z-index: 1000;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif;
            padding-bottom: env(safe-area-inset-bottom, 0px);
        }

        .fb-navbar-bottom__item {
            position: relative;
            flex: 1 1 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--nav-icon);
            text-decoration: none;
        }

        .fb-navbar-bottom__item svg {
            width: 26px;
            height: 26px;
        }

        .fb-navbar-bottom__item.active {
            color: var(--nav-icon-active);
        }

        .fb-navbar-bottom__item.is-avatar img {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid transparent;
        }

        .fb-navbar-bottom__item.is-avatar.active img {
            border-color: var(--nav-icon-active);
        }
    }

    /* Profiles  */

    .fb-profile {
        position: relative;
    }

    .fb-profile__trigger {
        cursor: pointer;
    }

    .fb-profile__menu {
        display: none;
        position: absolute;
        top: 52px;
        right: 0;
        background: #fff;
        border: 1px solid var(--nav-border);
        border-radius: 10px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.18);
        min-width: 220px;
        padding: 8px;
        z-index: 1100;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif;
    }

    .fb-profile.open .fb-profile__menu {
        display: block;
    }

    .fb-profile__header {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px;
    }

    .fb-profile__header img {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
    }

    .fb-profile__name {
        font-weight: 600;
        font-size: 14px;
        color: #050505;
    }

    .fb-profile__divider {
        height: 1px;
        background: var(--nav-border);
        margin: 6px 0;
    }

    .fb-profile__link,
    .fb-profile__logout {
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
        padding: 10px 8px;
        border-radius: 8px;
        text-align: left;
        background: none;
        border: none;
        font-size: 14px;
        color: #050505;
        text-decoration: none;
        cursor: pointer;
    }

    .fb-profile__link:hover,
    .fb-profile__logout:hover {
        background: var(--nav-hover-bg);
    }

    .fb-profile__link svg,
    .fb-profile__logout svg {
        width: 18px;
        height: 18px;
        color: var(--nav-icon);
    }

    /* On the bottom bar, the popup should open upward, above the bar */
    @media (max-width: 768px) {

        .fb-navbar-bottom .fb-profile {
            flex: 1 1 0;
            display: flex;
            align-items: center;
            justify-content: center;
            position: static;
            /* let the fixed-position menu below handle placement */
        }

        .fb-navbar-bottom .fb-profile__menu {
            position: fixed;
            top: auto;
            left: auto;
            right: 12px;
            bottom: calc(var(--nav-height) + env(safe-area-inset-bottom, 0px) + 8px);
        }

        .fb-navbar-bottom {
            width: 100%;
            max-width: 100vw;
            transform: translateZ(0);
        }
    }

    .fb-navbar__icon-btn .fb-navbar__avatar {
        width: 36px;
        height: 36px;
    }

    .table-scroll {
        width: 100%;
        max-width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .table-scroll table {
        min-width: 600px;
    }
</style>

<!-- ============ TOP BAR (desktop / laptop) ============ -->
<header class="fb-navbar" aria-label="Primary navigation">
    <div class="fb-navbar__brand">
        <a href="#" class="fb-navbar__logo" aria-label="Home">B</a>
        {{-- <div class="fb-navbar__search">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                <circle cx="11" cy="11" r="7" />
                <line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>
            <input type="text" placeholder="Search">
        </div> --}}
    </div>

    <nav class="fb-navbar__center">
        <a href="{{ route('dashboard') }}" class="fb-navbar__item active" title="Dashboard" data-count="0">
            <svg viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 3 2 12h3v8h6v-6h2v6h6v-8h3z" />
            </svg>
        </a>
        <a href="{{ route('notes.index') }}" class="fb-navbar__item" title="Notes" data-count="0">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z" />
                <polyline points="14 3 14 9 20 9" />
                <line x1="8" y1="13" x2="16" y2="13" />
                <line x1="8" y1="17" x2="16" y2="17" />
            </svg>
            {{-- <span class="fb-navbar__badge" data-empty="true">0</span> --}}
        </a>
        <a href="{{ route('projectmng.list') }}" class="fb-navbar__item" title="Assignments" data-count="3">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="9" y="2" width="6" height="4" rx="1" />
                <path d="M9 4H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-3" />
                <polyline points="9 14 11 16 15 12" />
            </svg>
            {{-- <span class="fb-navbar__badge">3</span> --}}
        </a>
        <a href="{{ route('projects.index') }}" class="fb-navbar__item" title="Project List" data-count="0">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="6" height="16" rx="1" />
                <rect x="10" y="4" width="6" height="10" rx="1" />
                <rect x="17" y="4" width="4" height="7" rx="1" />
            </svg>
        </a>
        {{-- <a href="#" class="fb-navbar__item" title="Error List" data-count="5">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                <line x1="12" y1="9" x2="12" y2="13" />
                <line x1="12" y1="17" x2="12.01" y2="17" />
            </svg>
            <span class="fb-navbar__badge">5</span>
        </a> --}}
    </nav>

    <div class="fb-navbar__actions">
        <a href="#" class="fb-navbar__icon-btn" title="Notifications">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9" />
                <path d="M13.73 21a2 2 0 0 1-3.46 0" />
            </svg>
            <span class="fb-navbar__badge">2</span>
        </a>

        <div class="fb-profile">
            <a href="#" class="fb-navbar__icon-btn fb-profile__trigger" title="Profile">
                <img class="fb-navbar__avatar" src="{{ auth()->user()->avatar ?? 'https://i.pravatar.cc/80' }}"
                    alt="Profile">
            </a>

            <div class="fb-profile__menu">
                <div class="fb-profile__header">
                    <img src="{{ auth()->user()->avatar ?? 'https://i.pravatar.cc/80' }}" alt="">
                    <span class="fb-profile__name">{{ auth()->user()->name ?? 'Your Name' }}</span>
                </div>
                <div class="fb-profile__divider"></div>
                <a href="#" class="fb-profile__link">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="8" r="4" />
                        <path d="M4 21v-1a7 7 0 0 1 14 0v1" />
                    </svg>
                    View Profile
                </a>
                <button type="submit" form="logout-form" class="fb-profile__logout">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                        <polyline points="16 17 21 12 16 7" />
                        <line x1="21" y1="12" x2="9" y2="12" />
                    </svg>
                    Log Out
                </button>
            </div>
        </div>

    </div>
</header>

<!-- ============ BOTTOM BAR (mobile / tablet) ============ -->
<nav class="fb-navbar-bottom" aria-label="Primary navigation">
    <a href="{{ route('dashboard') }}"
        class="fb-navbar-bottom__item {{ request()->routeIs('dashboard') ? 'active' : '' }}" title="Dashboard">
        <svg viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 3 2 12h3v8h6v-6h2v6h6v-8h3z" />
        </svg>
    </a>
    <a href="{{ route('notes.index') }}"
        class="fb-navbar-bottom__item {{ request()->routeIs('notes.*') ? 'active' : '' }}" title="Notes">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M14 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z" />
            <polyline points="14 3 14 9 20 9" />
            <line x1="8" y1="13" x2="16" y2="13" />
            <line x1="8" y1="17" x2="16" y2="17" />
        </svg>
        {{-- <span class="fb-navbar__badge" data-empty="true">0</span> --}}
    </a>
    <a href="{{ route('projectmng.list') }}"
        class="fb-navbar-bottom__item {{ request()->routeIs('projectmng.*') ? 'active' : '' }}" title="Assignments">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="9" y="2" width="6" height="4" rx="1" />
            <path d="M9 4H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-3" />
            <polyline points="9 14 11 16 15 12" />
        </svg>
        {{-- <span class="fb-navbar__badge">3</span> --}}
    </a>
    <a href="{{ route('projects.index') }}"
        class="fb-navbar-bottom__item {{ request()->routeIs('projects.*') ? 'active' : '' }}" title="Project List">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="4" width="6" height="16" rx="1" />
            <rect x="10" y="4" width="6" height="10" rx="1" />
            <rect x="17" y="4" width="4" height="7" rx="1" />
        </svg>
    </a>
    {{-- <a href="#" class="fb-navbar-bottom__item" title="Error List">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
            <line x1="12" y1="9" x2="12" y2="13" />
            <line x1="12" y1="17" x2="12.01" y2="17" />
        </svg>
        <span class="fb-navbar__badge">5</span>
    </a> --}}
    <div class="fb-profile">
        <a href="#" class="fb-navbar-bottom__item is-avatar fb-profile__trigger" title="Profile">
            <img src="{{ auth()->user()->avatar ?? 'https://i.pravatar.cc/80' }}" alt="Profile">
        </a>

        <div class="fb-profile__menu">
            <div class="fb-profile__header">
                <img src="{{ auth()->user()->avatar ?? 'https://i.pravatar.cc/80' }}" alt="">
                <span class="fb-profile__name">{{ auth()->user()->name ?? 'Your Name' }}</span>
            </div>
            <div class="fb-profile__divider"></div>
            <a href="#" class="fb-profile__link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="8" r="4" />
                    <path d="M4 21v-1a7 7 0 0 1 14 0v1" />
                </svg>
                View Profile
            </a>
            <button type="submit" form="logout-form" class="fb-profile__logout">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                    <polyline points="16 17 21 12 16 7" />
                    <line x1="21" y1="12" x2="9" y2="12" />
                </svg>
                Log Out
            </button>
        </div>
    </div>
</nav>

<script>
    // Optional: sets .active based on current URL path.
    // Update the "routes" map to match your real Laravel route paths,
    // or remove this and set the "active" class server-side with Blade instead.
    (function() {
        var routes = {
            '/dashboard': 'Dashboard',
            '/notes': 'Notes',
            '/project_manage': 'Assignments',
            '/projects': 'Project List',
            '/errors': 'Error List'
        };
        var current = routes[window.location.pathname];
        if (!current) return;

        document.querySelectorAll('.fb-navbar__item, .fb-navbar-bottom__item').forEach(function(el) {
            var isMatch = el.getAttribute('title') === current;
            el.classList.toggle('active', isMatch);
        });
    })();

    // Optional: hide badges whose data-count is 0/absent, and fill in numbers from data-count.
    (function() {
        document.querySelectorAll('[data-count]').forEach(function(el) {
            var count = parseInt(el.getAttribute('data-count'), 10) || 0;
            var badge = el.querySelector('.fb-navbar__badge');
            if (!badge) return;
            if (count <= 0) {
                badge.setAttribute('data-empty', 'true');
            } else {
                badge.removeAttribute('data-empty');
                badge.textContent = count > 99 ? '99+' : count;
            }
        });
    })();

    document.querySelectorAll('.fb-profile__trigger').forEach(function(trigger) {
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var parent = this.closest('.fb-profile');
            document.querySelectorAll('.fb-profile.open').forEach(function(p) {
                if (p !== parent) p.classList.remove('open');
            });
            parent.classList.toggle('open');
        });
    });

    document.addEventListener('click', function(e) {
        document.querySelectorAll('.fb-profile.open').forEach(function(p) {
            if (!p.contains(e.target)) p.classList.remove('open');
        });
    });
</script>
