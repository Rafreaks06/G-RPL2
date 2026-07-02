{{-- resources/views/components/committee-sidebar.blade.php --}}

<aside class="committee-sidebar">
    <div class="committee-sidebar-glow committee-sidebar-glow-1"></div>
    <div class="committee-sidebar-glow committee-sidebar-glow-2"></div>

    <div class="committee-sidebar-top">
        <div class="committee-sidebar-topbar">
            {{-- Brand --}}
            <a href="/approvals" class="committee-sidebar-brand" aria-label="G-RPL Committee Approvals">
                <span class="committee-sidebar-logo">
                    @if (file_exists(public_path('images/logo.png')))
                        <img src="{{ asset('images/logo.png') }}" alt="Logo G-RPL">
                    @else
                        <strong>G</strong>
                    @endif
                </span>

                <span class="committee-sidebar-brand-text">
                    <strong>G-RPL</strong>
                    <small>Committee Panel</small>
                </span>
            </a>

            <button
                type="button"
                id="committee-sidebar-menu-button"
                class="committee-sidebar-menu-button"
                aria-label="Buka menu"
                aria-expanded="false"
                aria-controls="committee-sidebar-collapse"
            >
                <svg id="committee-sidebar-menu-open" class="committee-sidebar-menu-icon" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/>
                </svg>
                <svg id="committee-sidebar-menu-close" class="committee-sidebar-menu-icon committee-sidebar-menu-icon-hidden" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M6 18L18 6M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/>
                </svg>
            </button>
        </div>
    </div>

    <div class="committee-sidebar-collapse" id="committee-sidebar-collapse">
        {{-- User Card --}}
        <div class="committee-sidebar-user">
            <div class="committee-sidebar-user-head">
                <div>
                    <p>Signed In</p>
                    <h2 data-user-name data-sidebar-user-name>Committee</h2>
                </div>

                <span class="committee-sidebar-user-dot" aria-hidden="true"></span>
            </div>

            <div class="committee-sidebar-role-wrap">
                <span class="committee-sidebar-role" data-user-role data-sidebar-user-role>Committee</span>
            </div>
        </div>

        {{-- Menu --}}
        <nav class="committee-sidebar-menu" aria-label="Committee Navigation">
            <a href="/approvals"
               class="committee-sidebar-link"
               data-committee-sidebar-link="approvals">
                <span class="committee-sidebar-link-icon">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2Zm-1 16H6V5h12v14ZM8 7h8v2H8V7Zm0 4h8v2H8v-2Zm0 4h5v2H8v-2Z"/>
                    </svg>
                </span>
                <span class="committee-sidebar-link-text">Daftar Pengajuan</span>
            </a>

            <a href="/approvals/approved"
               class="committee-sidebar-link"
               data-committee-sidebar-link="approved">
                <span class="committee-sidebar-link-icon">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20Zm-1.1 14.2-4-4 1.4-1.4 2.6 2.58 5.8-5.78 1.4 1.4-7.2 7.2Z"/>
                    </svg>
                </span>
                <span class="committee-sidebar-link-text">Pengajuan Disetujui</span>
            </a>
        </nav>

        {{-- Bottom --}}
        <div class="committee-sidebar-bottom">
            <div class="committee-sidebar-help">
                <span>Committee RPL Area</span>
                <strong>Meninjau dan menyelesaikan proses persetujuan pengajuan RPL</strong>
            </div>

            <button type="button" class="committee-sidebar-logout" data-logout onclick="committeeLogout()">
                <span class="committee-sidebar-logout-icon">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M10.09 15.59 11.5 17l5-5-5-5-1.41 1.41L12.67 11H3v2h9.67l-2.58 2.59ZM19 3H5c-1.1 0-2 .9-2 2v4h2V5h14v14H5v-4H3v4c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2Z"/>
                    </svg>
                </span>
                <span>Logout</span>
            </button>
        </div>
    </div>
</aside>

<style>
    /*
    |--------------------------------------------------------------------------
    | Committee Shell Layout Fix
    |--------------------------------------------------------------------------
    | Ini penting. Karena sidebar model card/sticky butuh parent 2 kolom.
    | Jadi halaman committee tidak akan tampil sidebar doang lagi.
    */

    .committee-shell.app-shell {
        width: 100%;
        min-height: 100vh;
        display: grid !important;
        grid-template-columns: 280px minmax(0, 1fr) !important;
        align-items: start !important;
        gap: 26px !important;
        padding: 28px !important;
        background:
            linear-gradient(rgba(21, 101, 192, 0.045) 1px, transparent 1px),
            linear-gradient(90deg, rgba(21, 101, 192, 0.045) 1px, transparent 1px),
            radial-gradient(circle at top left, rgba(21, 101, 192, 0.08), transparent 34%),
            radial-gradient(circle at bottom right, rgba(249, 168, 37, 0.07), transparent 30%),
            #f6f8fc !important;
        background-size: 56px 56px, 56px 56px, auto, auto, auto !important;
    }

    .committee-workspace.workspace,
    .committee-workspace {
        width: 100% !important;
        min-width: 0 !important;
        max-width: none !important;
        display: block !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    .committee-shell .workspace {
        min-width: 0 !important;
    }

    .committee-shell .workspace-header,
    .committee-workspace-header {
        width: 100% !important;
    }

    .committee-shell .table-container,
    .committee-table-container {
        width: 100% !important;
        max-width: 100% !important;
        overflow-x: auto !important;
    }

    /*
    |--------------------------------------------------------------------------
    | Sidebar Style
    |--------------------------------------------------------------------------
    */

    .committee-sidebar,
    .committee-sidebar * {
        box-sizing: border-box;
    }

    .committee-sidebar {
        width: 100% !important;
        max-width: 280px !important;
        position: sticky;
        top: 28px;
        min-height: calc(100vh - 56px);
        display: flex;
        flex-direction: column;
        isolation: isolate;
        overflow: hidden;
        padding: 20px;
        border-radius: 30px;
        color: #ffffff;
        background:
            radial-gradient(circle at 18% 0%, rgba(249, 168, 37, 0.22), transparent 30%),
            radial-gradient(circle at 105% 12%, rgba(21, 101, 192, 0.42), transparent 34%),
            radial-gradient(circle at 30% 92%, rgba(229, 57, 53, 0.14), transparent 28%),
            linear-gradient(180deg, #102347 0%, #0b1428 48%, #070d19 100%);
        border: 1px solid rgba(255, 255, 255, 0.12);
        box-shadow:
            0 28px 80px rgba(15, 23, 42, 0.24),
            inset 0 1px 0 rgba(255, 255, 255, 0.08);
    }

    .committee-sidebar::before {
        content: "";
        position: absolute;
        inset: 0 0 auto;
        z-index: -1;
        height: 5px;
        background: linear-gradient(90deg, #1565C0 0%, #F9A825 48%, #E53935 100%);
    }

    .committee-sidebar::after {
        content: "";
        position: absolute;
        inset: 1px;
        z-index: -2;
        border-radius: 29px;
        background:
            linear-gradient(145deg, rgba(255, 255, 255, 0.08), transparent 28%),
            linear-gradient(180deg, rgba(255, 255, 255, 0.04), transparent 58%);
        pointer-events: none;
    }

    .committee-sidebar-glow {
        position: absolute;
        z-index: -1;
        border-radius: 999px;
        filter: blur(4px);
        pointer-events: none;
    }

    .committee-sidebar-glow-1 {
        width: 120px;
        height: 120px;
        top: 66px;
        right: -70px;
        background: rgba(21, 101, 192, 0.22);
    }

    .committee-sidebar-glow-2 {
        width: 90px;
        height: 90px;
        bottom: 120px;
        left: -54px;
        background: rgba(249, 168, 37, 0.14);
    }

    .committee-sidebar-top {
        display: grid;
        gap: 22px;
    }

    .committee-sidebar-topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .committee-sidebar-menu-button {
        display: none;
        width: 42px;
        height: 42px;
        flex: 0 0 42px;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        color: #ffffff;
        background: rgba(255, 255, 255, 0.07);
        border: 1px solid rgba(255, 255, 255, 0.12);
        cursor: pointer;
        transition: background 0.2s ease, border-color 0.2s ease;
    }

    .committee-sidebar-menu-button:hover {
        background: rgba(255, 255, 255, 0.12);
        border-color: rgba(255, 255, 255, 0.18);
    }

    .committee-sidebar-menu-icon {
        width: 20px;
        height: 20px;
    }

    .committee-sidebar-menu-icon-hidden {
        display: none;
    }

    .committee-sidebar-brand {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
        text-decoration: none;
        color: #ffffff;
    }

    .committee-sidebar-logo {
        width: 50px;
        height: 50px;
        flex: 0 0 50px;
        display: grid;
        place-items: center;
        overflow: hidden;
        border-radius: 18px;
        background:
            radial-gradient(circle at 28% 16%, rgba(249, 168, 37, 0.20), transparent 36%),
            linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid rgba(255, 255, 255, 0.20);
        box-shadow:
            0 16px 34px rgba(0, 0, 0, 0.20),
            inset 0 1px 0 rgba(255, 255, 255, 0.9);
    }

    .committee-sidebar-logo img {
        width: 34px;
        height: 34px;
        object-fit: contain;
        display: block;
    }

    .committee-sidebar-logo strong {
        color: #1565C0;
        font-size: 1.35rem;
        line-height: 1;
        font-weight: 950;
    }

    .committee-sidebar-brand-text {
        min-width: 0;
        display: block;
    }

    .committee-sidebar-brand strong {
        display: block;
        color: #ffffff;
        font-family: 'Sora', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        font-size: 1.18rem;
        line-height: 1;
        font-weight: 950;
        letter-spacing: -0.045em;
        white-space: nowrap;
    }

    .committee-sidebar-brand small {
        display: block;
        margin-top: 5px;
        color: rgba(255, 255, 255, 0.58);
        font-size: 0.69rem;
        line-height: 1;
        font-weight: 900;
        letter-spacing: 0.17em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .committee-sidebar-collapse {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        flex: 1;
        min-height: 0;
    }

    .committee-sidebar-user {
        position: relative;
        overflow: hidden;
        margin-top: 22px;
        padding: 16px;
        border-radius: 22px;
        background:
            linear-gradient(145deg, rgba(255, 255, 255, 0.105), rgba(255, 255, 255, 0.045)),
            radial-gradient(circle at 100% 0%, rgba(249, 168, 37, 0.14), transparent 38%);
        border: 1px solid rgba(255, 255, 255, 0.115);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.08);
    }

    .committee-sidebar-user::before {
        content: "";
        position: absolute;
        width: 70px;
        height: 70px;
        right: -38px;
        bottom: -38px;
        border-radius: 999px;
        background: rgba(21, 101, 192, 0.20);
    }

    .committee-sidebar-user-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        position: relative;
        z-index: 1;
    }

    .committee-sidebar-user p {
        margin: 0 0 8px;
        color: #F9A825;
        font-size: 0.69rem;
        line-height: 1;
        font-weight: 950;
        letter-spacing: 0.17em;
        text-transform: uppercase;
    }

    .committee-sidebar-user h2 {
        max-width: 150px;
        margin: 0;
        overflow: hidden;
        color: #ffffff;
        font-family: 'Sora', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        font-size: 1.38rem;
        line-height: 1.13;
        font-weight: 950;
        letter-spacing: -0.055em;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .committee-sidebar-user-dot {
        width: 12px;
        height: 12px;
        flex: 0 0 12px;
        margin-top: 2px;
        border-radius: 999px;
        background: #22c55e;
        border: 2px solid rgba(255, 255, 255, 0.72);
        box-shadow: 0 0 0 5px rgba(34, 197, 94, 0.14);
    }

    .committee-sidebar-role-wrap {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 13px;
        position: relative;
        z-index: 1;
    }

    .committee-sidebar-role {
        display: inline-flex;
        width: fit-content;
        min-height: 31px;
        align-items: center;
        justify-content: center;
        padding: 0 12px;
        border-radius: 999px;
        color: #ffffff;
        background: rgba(21, 101, 192, 0.36);
        border: 1px solid rgba(147, 197, 253, 0.20);
        font-size: 0.76rem;
        line-height: 1;
        font-weight: 950;
    }

    .committee-sidebar-menu {
        display: grid;
        gap: 9px;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.105);
    }

    .committee-sidebar-link {
        position: relative;
        min-height: 48px;
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 0 13px;
        overflow: hidden;
        border-radius: 16px;
        color: rgba(255, 255, 255, 0.72);
        background: transparent;
        border: 1px solid transparent;
        font-size: 0.92rem;
        line-height: normal;
        font-weight: 850;
        text-decoration: none;
        outline: none;
        transition:
            transform 0.22s ease,
            color 0.22s ease,
            background 0.22s ease,
            border-color 0.22s ease,
            box-shadow 0.22s ease;
    }

    .committee-sidebar-link::before {
        content: "";
        position: absolute;
        inset: 8px auto 8px 0;
        width: 3px;
        border-radius: 999px;
        background: #F9A825;
        opacity: 0;
        transform: translateX(-8px);
        transition: 0.22s ease;
    }

    .committee-sidebar-link-icon {
        width: 31px;
        height: 31px;
        flex: 0 0 31px;
        display: grid;
        place-items: center;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.07);
        border: 1px solid rgba(255, 255, 255, 0.08);
        transition: 0.22s ease;
    }

    .committee-sidebar-link-icon svg {
        width: 17px;
        height: 17px;
        fill: currentColor;
        display: block;
    }

    .committee-sidebar-link-text {
        min-width: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .committee-sidebar-link:hover {
        color: #ffffff;
        background: rgba(255, 255, 255, 0.078);
        border-color: rgba(255, 255, 255, 0.105);
        transform: translateX(3px);
    }

    .committee-sidebar-link:hover .committee-sidebar-link-icon {
        background: rgba(255, 255, 255, 0.105);
        border-color: rgba(255, 255, 255, 0.14);
    }

    .committee-sidebar-link.active {
        color: #ffffff;
        background:
            linear-gradient(135deg, rgba(21, 101, 192, 0.48), rgba(249, 168, 37, 0.14)),
            rgba(255, 255, 255, 0.06);
        border-color: rgba(249, 168, 37, 0.24);
        box-shadow:
            0 14px 26px rgba(0, 0, 0, 0.13),
            inset 0 1px 0 rgba(255, 255, 255, 0.10);
    }

    .committee-sidebar-link.active::before {
        opacity: 1;
        transform: translateX(0);
    }

    .committee-sidebar-link.active .committee-sidebar-link-icon {
        color: #0b1428;
        background: linear-gradient(135deg, #F9A825, #ffe08a);
        border-color: rgba(255, 255, 255, 0.22);
        box-shadow: 0 10px 22px rgba(249, 168, 37, 0.18);
    }

    .committee-sidebar-link:focus-visible,
    .committee-sidebar-logout:focus-visible,
    .committee-sidebar-menu-button:focus-visible {
        box-shadow: 0 0 0 4px rgba(249, 168, 37, 0.18);
        border-color: rgba(249, 168, 37, 0.45);
    }

    .committee-sidebar-bottom {
        display: grid;
        gap: 13px;
        margin-top: 22px;
        padding-top: 18px;
        border-top: 1px solid rgba(255, 255, 255, 0.105);
    }

    .committee-sidebar-help {
        padding: 13px 14px;
        border-radius: 18px;
        background: rgba(255, 255, 255, 0.055);
        border: 1px solid rgba(255, 255, 255, 0.085);
    }

    .committee-sidebar-help span {
        display: block;
        margin-bottom: 5px;
        color: #F9A825;
        font-size: 0.66rem;
        line-height: 1;
        font-weight: 950;
        letter-spacing: 0.15em;
        text-transform: uppercase;
    }

    .committee-sidebar-help strong {
        display: block;
        color: rgba(255, 255, 255, 0.74);
        font-size: 0.8rem;
        line-height: 1.35;
        font-weight: 750;
    }

    .committee-sidebar-logout {
        width: 100%;
        min-height: 46px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        border-radius: 16px;
        color: #fecaca;
        background:
            linear-gradient(135deg, rgba(229, 57, 53, 0.15), rgba(229, 57, 53, 0.07));
        border: 1px solid rgba(229, 57, 53, 0.20);
        font-family: inherit;
        font-size: 0.9rem;
        line-height: 1;
        font-weight: 950;
        cursor: pointer;
        transition:
            transform 0.22s ease,
            color 0.22s ease,
            background 0.22s ease,
            border-color 0.22s ease,
            box-shadow 0.22s ease;
    }

    .committee-sidebar-logout-icon {
        width: 19px;
        height: 19px;
        display: grid;
        place-items: center;
    }

    .committee-sidebar-logout-icon svg {
        width: 18px;
        height: 18px;
        fill: currentColor;
        display: block;
    }

    .committee-sidebar-logout:hover {
        color: #ffffff;
        background:
            linear-gradient(135deg, rgba(229, 57, 53, 0.28), rgba(229, 57, 53, 0.13));
        border-color: rgba(229, 57, 53, 0.34);
        transform: translateY(-1px);
        box-shadow: 0 14px 30px rgba(229, 57, 53, 0.12);
    }

    .committee-sidebar-logout:active {
        transform: translateY(0);
    }

    @media (max-width: 1100px) {
        .committee-shell.app-shell {
            grid-template-columns: 260px minmax(0, 1fr) !important;
            gap: 20px !important;
            padding: 22px !important;
        }

        .committee-sidebar {
            max-width: 260px !important;
            padding: 18px;
            border-radius: 26px;
        }

        .committee-sidebar-brand strong {
            font-size: 1.05rem;
        }

        .committee-sidebar-user h2 {
            font-size: 1.24rem;
        }

        .committee-sidebar-link {
            min-height: 46px;
            padding-inline: 12px;
        }
    }

    @media (max-width: 900px) {
        .committee-shell.app-shell {
            display: grid !important;
            grid-template-columns: 1fr !important;
            gap: 18px !important;
            padding: 18px !important;
        }

        .committee-sidebar {
            width: 100% !important;
            max-width: none !important;
            position: relative;
            top: 0;
            min-height: auto;
        }

        .committee-sidebar-menu-button {
            display: inline-flex;
        }

        .committee-sidebar-collapse {
            display: none;
            flex: initial;
        }

        .committee-sidebar-collapse.is-open {
            display: flex;
        }

        .committee-sidebar-menu {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .committee-sidebar-link:hover {
            transform: translateY(-1px);
        }

        .committee-sidebar-bottom {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 640px) {
        .committee-shell.app-shell {
            padding: 14px !important;
            gap: 14px !important;
        }

        .committee-sidebar {
            padding: 16px;
            border-radius: 24px;
        }

        .committee-sidebar-top {
            gap: 18px;
        }

        .committee-sidebar-brand {
            gap: 10px;
        }

        .committee-sidebar-logo {
            width: 46px;
            height: 46px;
            flex-basis: 46px;
            border-radius: 16px;
        }

        .committee-sidebar-logo img {
            width: 31px;
            height: 31px;
        }

        .committee-sidebar-user {
            padding: 14px;
            border-radius: 19px;
        }

        .committee-sidebar-menu {
            grid-template-columns: 1fr;
            margin-top: 18px;
            padding-top: 16px;
        }

        .committee-sidebar-link {
            min-height: 45px;
            border-radius: 15px;
        }

        .committee-sidebar-help {
            display: none;
        }
    }

    @media (max-width: 380px) {
        .committee-sidebar-brand small {
            letter-spacing: 0.12em;
        }

        .committee-sidebar-user h2 {
            max-width: 120px;
        }

        .committee-sidebar-link {
            font-size: 0.86rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const currentPath = window.location.pathname.replace(/\/$/, '') || '/';
        const links = document.querySelectorAll('[data-committee-sidebar-link]');

        // Kalau ini halaman list, update sessionStorage
        if (currentPath === '/approvals') {
            sessionStorage.setItem('committee_active_tab', 'approvals');
        } else if (currentPath === '/approvals/approved') {
            sessionStorage.setItem('committee_active_tab', 'approved');
        }

        // Tentukan active state
        const isDetailPage = /^\/approvals\/\d+$/.test(currentPath);
        const lastTab = sessionStorage.getItem('committee_active_tab') || 'approvals';

        links.forEach(function (link) {
            const type = link.getAttribute('data-committee-sidebar-link');
            let isActive = false;

            if (type === 'approvals') {
                isActive = currentPath === '/approvals'
                    || (isDetailPage && lastTab === 'approvals');
            }

            if (type === 'approved') {
                isActive = currentPath === '/approvals/approved'
                    || (isDetailPage && lastTab === 'approved');
            }

            link.classList.toggle('active', isActive);
        });

        // ... sisa kode nama/role tetap sama
        const storedUserName =
            localStorage.getItem('user_name')
            || sessionStorage.getItem('user_name')
            || localStorage.getItem('name')
            || sessionStorage.getItem('name')
            || 'Committee';

        const storedUserRole =
            localStorage.getItem('user_role')
            || sessionStorage.getItem('user_role')
            || 'committee';

        document.querySelectorAll('[data-sidebar-user-name], [data-user-name]').forEach(function (element) {
            if (element) element.textContent = storedUserName;
        });

        document.querySelectorAll('[data-sidebar-user-role], [data-user-role]').forEach(function (element) {
            if (element) {
                const cleanRole = storedUserRole.replace(/_/g, ' ');
                element.textContent = cleanRole.charAt(0).toUpperCase() + cleanRole.slice(1);
            }
        });

        const menuButton = document.getElementById('committee-sidebar-menu-button');
        const collapse = document.getElementById('committee-sidebar-collapse');
        const menuOpenIcon = document.getElementById('committee-sidebar-menu-open');
        const menuCloseIcon = document.getElementById('committee-sidebar-menu-close');

        if (!menuButton || !collapse) {
            return;
        }

        function setMenuState(isOpen) {
            collapse.classList.toggle('is-open', isOpen);
            menuOpenIcon?.classList.toggle('committee-sidebar-menu-icon-hidden', isOpen);
            menuCloseIcon?.classList.toggle('committee-sidebar-menu-icon-hidden', !isOpen);
            menuButton.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        }

        menuButton.addEventListener('click', function () {
            setMenuState(!collapse.classList.contains('is-open'));
        });

        // Auto-tutup menu tiap klik link (biar gak nyangkut kebuka pas pindah halaman)
        links.forEach(function (link) {
            link.addEventListener('click', function () {
                if (window.matchMedia('(max-width: 900px)').matches) {
                    setMenuState(false);
                }
            });
        });

        // Reset state kalau resize dari mobile balik ke desktop
        window.addEventListener('resize', function () {
            if (window.innerWidth > 900) {
                setMenuState(false);
            }
        });
    });
</script>