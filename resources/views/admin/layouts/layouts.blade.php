<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/588hospital_100778.webp') }}" type="image/webp">
    <title>
        @auth
            @php
                $userBranchName = auth()->user()->branch->branch_name ?? null;
                $isSuperadmin = auth()->user()->hasRole('Superadmin');
                $branchNameUpper = $userBranchName ? strtoupper($userBranchName) : '';
            @endphp
            @if($isSuperadmin)
                Figure 'n' Fit
            @elseif(str_contains($branchNameUpper, 'SVC'))
                SVC
            @elseif(str_contains($branchNameUpper, 'LHR'))
                LHR
            @elseif(str_contains($branchNameUpper, 'HYDRA'))
                Hydra
            @else
                Figure 'n' Fit
            @endif
        @else
            Shree Vallabh Clinic
        @endauth
    </title>
    <script>
        (function () {
            const theme = localStorage.getItem('theme') || 'system';
            const isDark = theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
            document.documentElement.classList.toggle('dark', isDark);


            const bgColor = isDark ? '#0f172a' : '#f8fafc';
            document.documentElement.style.backgroundColor = bgColor;
        })();
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        premium: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                            950: '#020617',
                        },
                    },
                },
            },
        }
    </script>
    <style>
        /* Fix for Bootstrap 5 Accordion + Tailwind Conflict */
        /* Tailwind sets .collapse to visibility: collapse, which breaks Bootstrap's display: none/block toggling */
        .collapse:not(.show) {
            display: none !important;
        }

        .collapse.show {
            display: block !important;
            visibility: visible !important;
        }

        /* Ensure visibility is reset if Tailwind messed with it */
        /* Ensure visibility is reset if Tailwind messed with it */
        .accordion-collapse {
            visibility: visible !important;
        }

        /* Global SweetAlert2 Styling - Only for Modals, not Toasts */
        div:where(.swal2-container) div:where(.swal2-popup):not(.swal2-toast) {
            border-radius: 1.5rem !important;
            padding: 2rem !important;
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1) !important;
        }

        div:where(.swal2-container):not(.swal2-toast-container) .swal2-title {
            font-size: 1.5rem !important;
            font-weight: 700 !important;
            color: #1f2937 !important;
            /* Slate 800 */
        }

        div:where(.swal2-container):not(.swal2-toast-container) .swal2-html-container {
            font-size: 1rem !important;
            color: #6b7280 !important;
            /* Gray 500 */
        }

        div:where(.swal2-container) button.swal2-styled.swal2-confirm {
            background-color: #10b981 !important;
            /* Emerald 500 */
            border-radius: 0.5rem !important;
            padding: 0.75rem 2rem !important;
            font-weight: 600 !important;
            border: none !important;
            box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.4) !important;
        }

        div:where(.swal2-container) button.swal2-styled.swal2-cancel {
            background-color: #6b7280 !important;
            /* Gray 500 */
            border-radius: 0.5rem !important;
            padding: 0.75rem 2rem !important;
            font-weight: 600 !important;
            border: none !important;
        }

        /* Dark Mode Adjustments for SweetAlert */
        .dark div:where(.swal2-container) div:where(.swal2-popup):not(.swal2-toast) {
            background-color: #1e293b !important;
            /* Slate 800 */
            color: #f1f5f9 !important;
        }

        .dark div:where(.swal2-container):not(.swal2-toast-container) .swal2-title {
            color: #f1f5f9 !important;
        }

        .dark div:where(.swal2-container):not(.swal2-toast-container) .swal2-html-container {
            color: #94a3b8 !important;
        }
    </style>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        /* Global protection for FontAwesome and Bootstrap Icons */
        .fas,
        .far,
        .fal,
        .fab,
        .fa,
        .fa-solid,
        .fa-regular,
        .fa-brands,
        .bi,
        [class^="fa-"],
        [class*=" fa-"],
        [class^="bi-"],
        [class*=" bi-"] {
            font-family: "Font Awesome 6 Free", "Font Awesome 6 Brands", "bootstrap-icons", "FontAwesome" !important;
        }
    </style>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 (Includes CSS) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <style>
        :root {
            --bg-main: #f8fafc;
            --bg-card: #ffffff;
            --bg-navbar: #ffffff;
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --border-subtle: #e2e8f0;
            --accent-glow: rgba(5, 150, 105, 0.1);
            --accent-solid: #059669;
            --accent-hover: #047857;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            --color-primary: #2563eb;
            --color-danger: #ef4444;
            --color-success: #10b981;
            --color-info: #0ea5e9;
            --icon-on-color: #ffffff;
        }

        .dark {
            --bg-main: #0b1120;
            --bg-card: #1e293b;
            --bg-navbar: #111827;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --border-subtle: #334155;
            --accent-glow: rgba(52, 211, 153, 0.15);
            --accent-solid: #10b981;
            --accent-hover: #34d399;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.5);
            --shadow-md: 0 10px 15px -3px rgb(0 0 0 / 0.5);
        }

        /* Global Branding Font - Poppins for Headlines and Logo */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@600;700;800&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--bg-main);
            color: var(--text-primary);
            line-height: 1.5;
            overflow-x: hidden;
            width: 100%;
            max-width: 100vw;
        }

        html {
            overflow-x: hidden;
            max-width: 100vw;
        }

        .navbar {
            background-color: var(--bg-navbar) !important;
            padding: 0.75rem 2rem !important;
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            box-shadow: var(--shadow-sm) !important;
            border-bottom: 1px solid var(--border-subtle) !important;
            position: sticky !important;
            top: 0 !important;
            z-index: 1050 !important;
            min-height: 64px !important;
            width: 100% !important;
        }

        .navbar-brand {
            font-family: 'Poppins', sans-serif !important;
            color: #197040 !important;
            font-size: 1.5rem !important;
            font-weight: 800 !important;
            letter-spacing: -0.025em !important;
            display: flex !important;
            align-items: center !important;
            gap: 0.5rem !important;
            text-decoration: none !important;
        }

        .navbar-brand img {
            height: 48px !important;
            width: auto !important;
            display: block !important;
            object-fit: contain !important;
        }

        .nav-links {
            display: flex;
            gap: 5px;
            align-items: center;
        }

        .nav-links a {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.75rem;
            font-weight: 500;
            transition: color 0.15s;
            padding: 0.35rem 0.5rem;
            border-radius: 0.4rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            white-space: nowrap;
        }

        .nav-links a:hover {
            color: var(--accent-solid);
            background-color: transparent;
        }

        .nav-links a.active {
            color: var(--accent-solid);
            background-color: transparent;
            font-weight: 500;
        }


        .nav-links a i {
            font-size: 14px;
            width: 14px;
            text-align: center;
        }

        .logout-nav-btn {
            background: transparent;
            border: 1px solid var(--border-subtle);
            color: var(--text-secondary);
            font-size: 0.75rem;
            font-weight: 500;
            padding: 0.35rem 0.5rem;
            border-radius: 0.4rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.2s;
        }

        .logout-nav-btn:hover {
            color: #ef4444;
            border-color: #ef4444;
            background-color: rgba(239, 68, 68, 0.05);
        }

        .dropdown {
            position: relative;
        }

        .dropdown>a {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .dropdown>a::after {
            content: "\f0d7";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            font-size: 10px;
            margin-left: 8px;
            display: inline-block;
            transition: transform 0.2s ease;
        }

        .dropdown.show>a::after {
            transform: rotate(180deg);
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: var(--bg-card);
            min-width: 220px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            z-index: 2000 !important;
            border-radius: 0.75rem;
            border: 1px solid var(--border-subtle);
            top: 100%;
            left: 0;
            padding: 0.5rem;
            margin-top: 0.5rem;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        .dropdown.show .dropdown-content {
            display: block !important;
            animation: dropdownFadeIn 0.2s ease-out;
        }

        @keyframes dropdownFadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-content a {
            color: var(--text-secondary);
            padding: 0.75rem 1rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.875rem;
            border-radius: 0.5rem;
            transition: none;
        }

        .dropdown-content a:hover {
            background-color: var(--accent-glow);
            color: var(--accent-solid);
            transform: translateX(4px);
        }

        .dropdown.show>a {
            color: var(--accent-solid);
            background: var(--accent-glow);
        }

        .main-content {
            padding: 30px;
        }

        /* Sidebar Mode Styles */
        body.layout-sidebar .navbar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: 260px;
            height: 100vh;
            flex-direction: column;
            align-items: flex-start;
            padding: 1.5rem 0;
            /* Switched to vertical padding; items will handle horizontal */
            overflow-y: auto;
            overflow-x: hidden;
            border-bottom: none;
            border-right: 1px solid var(--border-subtle);
            justify-content: flex-start;
            gap: 1.5rem;
            z-index: 1000;
        }

        body.layout-sidebar .navbar::-webkit-scrollbar {
            width: 4px;
            /* Thin and elegant */
        }

        body.layout-sidebar .navbar::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.2);
            /* Semi-transparent for dark/light capability */
            border-radius: 10px;
        }

        body.layout-sidebar .navbar {
            scrollbar-width: thin;
            /* Firefox */
            scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
        }

        .navbar-sidebar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            gap: 1rem;
            padding: 0 1.5rem;
            /* Header needs internal padding */
        }

        /* Desktop layout: auto width unless sidebar mode */
        @media (min-width: 993px) {
            body:not(.layout-sidebar) .navbar-sidebar-header {
                width: auto;
            }
        }

        body.layout-sidebar .navbar-sidebar-header {
            width: 100%;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-subtle);
        }

        body.layout-sidebar .navbar-brand {
            font-size: 1.15rem;
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
            width: 100%;
            white-space: normal;
            line-height: 1.2;
            word-break: break-word;
        }

        body.layout-sidebar .nav-links {
            flex-direction: column;
            width: 100%;
            align-items: flex-start;
            gap: 0.25rem;
        }

        body.layout-sidebar .nav-links a,
        body.layout-sidebar .logout-nav-btn {
            padding: 0.6rem 1.5rem;
            /* Full horizontal breathing room */
            font-size: 0.85rem;
            border-radius: 0;
            /* Standard sidebar look */
            width: 100%;
            justify-content: flex-start;
            border: none;
        }

        body.layout-sidebar .nav-links a:hover,
        body.layout-sidebar .nav-links a.active {
            background-color: var(--accent-glow);
            border-left: 3px solid var(--accent-solid);
        }

        body.layout-sidebar .dropdown-content {
            position: static;
            display: none;
            padding: 0;
            margin: 0;
            background: transparent;
            border: none;
            box-shadow: none;
            width: 100%;
        }

        body.layout-sidebar .dropdown.show .dropdown-content {
            display: block;
        }

        body.layout-sidebar .dropdown-content a {
            padding: 0.5rem 1.5rem 0.5rem 2.75rem;
            /* Balanced indentation */
            font-size: 0.8rem;
        }

        body.layout-sidebar .main-content {
            margin-left: 260px;
            width: calc(100% - 260px);
        }

        body.layout-sidebar .theme-switcher-container {
            margin-top: auto;
            width: 100%;
            margin-left: 0;
            padding-top: 1rem;
            border-top: 1px solid var(--border-subtle);
        }

        body.layout-sidebar .theme-btn {
            width: 100%;
            height: auto;
            border-radius: 0.5rem;
            justify-content: flex-start;
            padding: 0.6rem 0.75rem;
            gap: 0.75rem;
            border: none;
            background: transparent;
            font-size: 0.85rem;
            color: var(--text-secondary);
        }

        body.layout-sidebar .theme-btn:hover {
            color: var(--accent-solid);
            background: var(--accent-glow);
        }

        /* Dark Mode Sidebar Fixes */
        .dark body.layout-sidebar .theme-btn {
            color: var(--text-secondary);
        }

        .dark body.layout-sidebar .theme-btn:hover {
            color: var(--accent-solid);
            background: var(--accent-glow);
        }

        .dark body.layout-sidebar .theme-menu {
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
        }

        .dark body.layout-sidebar .theme-item {
            color: #e2e8f0;
            /* Bright grey/white */
        }

        .dark body.layout-sidebar .theme-item:hover {
            color: var(--accent-solid);
            background: rgba(255, 255, 255, 0.05);
        }

        body.layout-sidebar .theme-btn::after {
            content: "";
        }

        body.layout-sidebar .theme-menu {
            position: static;
            width: 100%;
            box-shadow: none;
            border: none;
            background: transparent;
            padding: 0;
            margin-top: 0.5rem;
            transform: none !important;
        }

        body.layout-sidebar .theme-item {
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            font-size: 0.8rem;
        }

        .layout-toggle-btn {
            background: transparent;
            border: 1px solid var(--border-subtle);
            color: var(--text-secondary);
            padding: 0.4rem;
            border-radius: 0.4rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .layout-toggle-btn:hover {
            color: var(--accent-solid);
            border-color: var(--accent-solid);
        }



        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #6c757d;
            font-size: 14px;
            padding: 8px 12px;
        }

        .user-role {
            background: rgb(8, 104, 56);
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }

        /* Nested dropdown styles */
        .dropdown-nested {
            position: relative;
            width: 100%;
        }

        .dropdown-nested-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 0.75rem 1.5rem;
            /* Consistent with other sidebar items */
            color: var(--text-secondary);
            background: transparent;
            border: none;
            cursor: pointer;
            font-size: 0.875rem;
            border-radius: 0;
            transition: all 0.2s;
            width: 100%;
        }

        .dropdown-nested-toggle:hover {
            background-color: var(--accent-glow);
            color: var(--accent-solid);
        }

        .nested-dropdown {
            display: none;
            position: absolute;
            left: 100%;
            top: 0;
            background-color: var(--bg-card);
            min-width: 220px;
            box-shadow: var(--shadow-lg);
            border-radius: 0.75rem;
            border: 1px solid var(--border-subtle);
            padding: 0.5rem;
            z-index: 2001;
            margin-left: 0.5rem;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        .dropdown-nested.active .nested-dropdown {
            display: block !important;
            animation: dropdownFadeIn 0.2s ease-out;
        }

        .dropdown-nested-toggle i:last-child {
            font-size: 0.8em;
            transition: transform 0.2s;
        }

        .dropdown-nested.active .dropdown-nested-toggle i:last-child {
            transform: rotate(90deg);
        }

        /* Sidebar mode: nested dropdowns expand inline (accordion), not flyout */
        body.layout-sidebar .nested-dropdown {
            position: static !important;
            left: 0 !important;
            top: 0 !important;
            margin-left: 0 !important;
            margin-top: 0 !important;
            box-shadow: none !important;
            border: none !important;
            border-left: 2px solid var(--border-subtle) !important;
            background: transparent !important;
            padding: 0 !important;
            min-width: 0 !important;
            width: 100% !important;
            margin-left: 1rem !important;
        }

        body.layout-sidebar .dropdown-nested-toggle {
            padding: 0.6rem 1.5rem !important;
        }

        body.layout-sidebar .nested-dropdown a {
            padding-left: 2.5rem !important;
        }

        @media (max-width: 992px) {
            .nested-dropdown {
                position: static !important;
                left: 0 !important;
                top: 0 !important;
                margin-left: 0 !important;
                margin-top: 0 !important;
                box-shadow: none !important;
                border: none !important;
                border-left: none !important;
                background: transparent !important;
                padding: 0 !important;
                min-width: 0 !important;
                width: 100% !important;
            }

            .dropdown-nested-toggle {
                padding: 0.75rem 1.5rem !important;
            }

            .nested-dropdown a {
                padding-left: 3rem !important;
                /* Deeper indentation for nested mobile/sidebar */
            }
        }


        /* Responsive adjustments */
        @media (max-width: 1400px) {
            .navbar {
                padding: 0.5rem 1rem !important;
            }

            .nav-links {
                gap: 2px;
            }

            .nav-links a {
                font-size: 9.5px;
                padding: 4px 5px;
            }

            .navbar-brand {
                font-size: 1.1rem !important;
            }
        }

        @media (max-width: 1200px) {
            .nav-links {
                gap: 1px;
            }

            .nav-links a {
                font-size: 9px;
                padding: 3px 4px;
            }

            .nav-links a i {
                font-size: 12px;
                width: 12px;
            }
        }

        @media (max-width: 992px) {
            .navbar {
                flex-direction: row !important;
                padding: 10px !important;
                justify-content: space-between !important;
                height: 74px !important;
                position: sticky !important;
                top: 0 !important;
                width: 100% !important;
                border-right: none !important;
                border-bottom: 1px solid var(--border-subtle) !important;
            }

            .navbar-brand {
                margin-bottom: 0 !important;
                padding-bottom: 0 !important;
                border-bottom: none !important;
                font-size: 1rem !important;
            }

            .nav-links {
                display: none !important;
                /* Managed by mobileMenuToggle script */
                position: fixed;
                top: 64px;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: var(--bg-navbar);
                flex-direction: column !important;
                padding: 1.5rem !important;
                z-index: 2000;
                overflow-y: auto;
                justify-content: flex-start !important;
                gap: 0.5rem !important;
                align-items: flex-start !important;
            }

            .nav-links.show {
                display: flex !important;
            }

            .nav-links a,
            .logout-nav-btn {
                width: 100% !important;
                padding: 0.75rem 1rem !important;
                font-size: 0.95rem !important;
                border: none !important;
                border-bottom: 1px solid var(--border-subtle) !important;
                border-radius: 0.5rem !important;
                justify-content: flex-start !important;
            }

            .main-content {
                padding: 1.25rem !important;
                margin-left: 0 !important;
                width: 100% !important;
                margin-top: 0 !important;
            }

            body.layout-sidebar .main-content {
                margin-left: 0 !important;
                width: 100% !important;
            }

            .dropdown-content {
                position: static !important;
                display: none;
                width: 100% !important;
                box-shadow: none !important;
                border: none !important;
                margin-top: 0 !important;
                padding-left: 1.5rem !important;
                background: transparent !important;
            }

            .dropdown.show .dropdown-content {
                display: block !important;
            }

            .nested-dropdown {
                position: static !important;
                width: 100% !important;
                margin-left: 1rem !important;
                box-shadow: none !important;
                border: none !important;
                border-left: 1px solid var(--border-subtle) !important;
            }

            /* Generic Responsive Utilities for Layout */
            .navbar-sidebar-header {
                margin-bottom: 0 !important;
                padding-bottom: 0 !important;
                border-bottom: none !important;
                gap: 1rem !important;
            }

            .mobile-menu-btn {
                display: flex !important;
                align-items: center;
                justify-content: center;
                width: 38px;
                height: 38px;
                background: var(--bg-main);
                border: 1px solid var(--border-subtle);
                border-radius: 8px;
                color: var(--text-primary);
                cursor: pointer;
            }

            .theme-switcher-container {
                margin-left: 0 !important;
                width: 100% !important;
                margin-top: 1rem !important;
                padding-top: 1rem !important;
                border-top: 1px solid var(--border-subtle) !important;
            }

            .theme-btn {
                width: 100% !important;
                border-radius: 8px !important;
                justify-content: flex-start !important;
                padding-left: 1rem !important;
            }
        }

        /* ==========================================================================
           Absolute Zero-White Protocol (Phase 3 - NUCLEAR OPTION)
           ========================================================================== */

        /* THE ULTIMATE FORCE: Target common container patterns found in profile and follow-up pages */
        .dark section,
        .dark .recipe-section,
        .dark .nutrition-data-section,
        .dark .inquiry-section,
        .dark .profile-section,
        .dark .assessment-section,
        .dark .diet-section,
        .dark .profile-container,
        .dark .profile-sidebar,
        .dark .profile-main,
        .dark .form-container,
        .dark .history-section,
        .dark .history-field,
        .dark .visit-card,
        .dark .single-visit-container,
        .dark .visit-time-header,
        .dark .compact-field,
        .dark .main_content,
        .dark .card_custom,
        .dark .patient_data_box,
        .dark .meal-row,
        .dark .image-card,
        .dark .image-card-header,
        .dark .image-details,
        .dark .display_filter_data,
        .dark .breakdown,
        .dark .no-image-placeholder,
        .dark .display_data,
        .dark .main-content>div:not(.navbar) {
            background-color: var(--bg-card) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-subtle) !important;
        }

        /* Catch any lingering white backgrounds in local <style> blocks or generic containers */
        .dark div[class*="-section"],
        .dark div[class*="-container"],
        .dark div[class*="-box"],
        .dark div[class*="Card"],
        .dark div[class*="Box"],
        .dark div[class*="-header"],
        .dark div[class*="-body"] {
            background-color: var(--bg-card) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-subtle) !important;
        }

        /* Dropdowns & Modals */
        .dark .dropdown-content,
        .dark .dropdown-menu,
        .dark .nested-dropdown,
        .dark .modal-content,
        .dark .modal-header,
        .dark .modal-body,
        .dark .modal-footer {
            background-color: var(--bg-card) !important;
            color: var(--text-primary) !important;
            border: 1px solid var(--border-subtle) !important;
            box-shadow: var(--shadow-md) !important;
        }

        /* Essential Overrides for Bootstrap/Tailwind Components */
        .dark body,
        .dark .main-content,
        .dark .wrapper,
        .dark .card,
        .dark .card-body,
        .dark .card-header,
        .dark .card-footer,
        .dark .list-group-item,
        .dark .bg-white,
        .dark .bg-light,
        .dark .form-section-container,
        .dark .diet-history-section,
        .dark .inner-container,
        .dark .patient-details-card,
        .dark .white-box,
        .dark [class*="card"],
        .dark [class*="bg-white"],
        .dark [class*="bg-light"],
        .dark [class*="container"] {
            background-color: var(--bg-card) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-subtle) !important;
        }

        /* Root Level Force */
        .dark body,
        .dark .main-content,
        .dark .wrapper {
            background-color: var(--bg-main) !important;
        }

        /* Inline Style Killer */
        .dark [style*="background"],
        .dark [style*="background-color"],
        .dark [style*="background:white"],
        .dark [style*="background:#fff"],
        .dark [style*="background: white"],
        .dark [style*="background: #fff"] {
            background-color: var(--bg-card) !important;
            color: var(--text-primary) !important;
        }

        .dark .bg-transparent {
            background-color: transparent !important;
        }

        /* Dropdowns & Selects Fix */
        .dark .dropdown-menu {
            background-color: var(--bg-card) !important;
            border: 1px solid var(--border-subtle) !important;
            box-shadow: var(--shadow-lg) !important;
        }

        .dark .dropdown-item {
            color: var(--text-primary) !important;
        }

        .dark .dropdown-item:hover,
        .dark .dropdown-item:focus,
        .dark .dropdown-item.active,
        .dark .dropdown-item:active {
            background-color: var(--bg-hover) !important;
            color: var(--text-primary) !important;
        }

        /* Fix for specific dropdown patterns */
        .dark .dropdown-menu li:hover,
        .dark .dropdown-menu li a:hover {
            background-color: var(--bg-hover) !important;
        }

        /* Table Precision */
        .dark table,
        .dark thead,
        .dark tbody,
        .dark tr,
        .dark td,
        .dark th {
            background-color: var(--bg-card) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-subtle) !important;
        }

        .dark thead th,
        .dark .table thead th {
            background-color: rgba(0, 0, 0, 0.4) !important;
            color: var(--text-secondary) !important;
        }

        .dark .table-striped tbody tr:nth-of-type(odd),
        .dark .data-table tr:nth-child(even),
        .dark .dataTables_wrapper .odd {
            background-color: rgba(255, 255, 255, 0.02) !important;
        }

        /* Form Controls - Massive Force */
        .dark input:not([type="submit"]):not([type="button"]):not([type="checkbox"]):not([type="radio"]),
        .dark select,
        .dark textarea,
        .dark .form-control,
        .dark .form-select,
        .dark .input-field {
            background-color: var(--bg-main) !important;
            color: var(--text-primary) !important;
            border: 1px solid var(--border-subtle) !important;
            border-radius: 0.5rem !important;
        }

        /* Universal Text & Icon Cleanup */
        .dark .main-content *,
        .dark .content-wrapper *,
        .dark .main_content *,
        .dark .container-fluid *,
        .dark .card *,
        .dark .modal-content * {
            color: var(--text-primary);
        }

        .dark label,
        .dark .form-label,
        .dark label *,
        .dark strong,
        .dark .label-text,
        .dark .info-label,
        .dark .history-label,
        .dark .dimension-label,
        .dark .measurement-label,
        .dark .date-label,
        .dark .diet-history-section h3,
        .dark .diet-history-section h4,
        .dark .meal-times h4,
        .dark .recipe-badge,
        .dark .fnf-title {
            color: #ffffff !important;
        }

        .dark .text-muted,
        .dark .text-secondary,
        .dark small,
        .dark .notes-cell,
        .dark .notes-content {
            color: var(--text-muted) !important;
        }

        /* Pagination & Buttons */
        .dark .pagination .page-link,
        .dark .pagination-btn,
        .dark .paginate_button,
        .dark .dataTables_paginate .paginate_button {
            background-color: var(--bg-card) !important;
            color: var(--text-secondary) !important;
            border: 1px solid var(--border-subtle) !important;
            margin: 0 2px !important;
        }

        .dark .pagination .page-item.active .page-link,
        .dark .pagination-btn.active,
        .dark .paginate_button.current,
        .dark .dataTables_paginate .paginate_button.current {
            background-color: var(--accent-solid) !important;
            color: white !important;
            border-color: var(--accent-solid) !important;
        }

        /* Cleanups */
        .dark .text-dark {
            color: var(--text-primary) !important;
        }

        .dark .text-muted,
        .dark .text-secondary {
            color: var(--text-secondary) !important;
        }

        .dark .border {
            border-color: var(--border-subtle) !important;
        }

        .dark i,
        .dark span:not(.badge):not(.visit-time-badge) {
            color: inherit;
        }

        .dark .required::after {
            color: #ff4d4d !important;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--accent-solid) !important;
            box-shadow: 0 0 0 3px var(--accent-glow) !important;
        }

        /* Buttons */
        .btn-primary {
            background-color: var(--accent-solid) !important;
            border: none !important;
            border-radius: 0.5rem !important;
            font-weight: 600 !important;
        }

        /* Theme Switcher UI */
        .theme-switcher-container {
            position: relative;
            margin-left: 1rem;
        }

        .theme-btn {
            background-color: var(--bg-main);
            border: 1px solid var(--border-subtle);
            color: var(--text-primary);
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .theme-btn:hover {
            color: var(--accent-solid);
            border-color: var(--accent-solid);
        }

        /* Select2 Dark Mode Fixes */
        .dark .select2-container--default .select2-selection--single,
        .dark .select2-container--default .select2-selection--multiple {
            background-color: var(--bg-main) !important;
            border: 1px solid var(--border-subtle) !important;
            color: var(--text-primary) !important;
        }

        .dark .select2-container--default .select2-selection--single .select2-selection__rendered,
        .dark .select2-container--default .select2-selection--multiple .select2-selection__choice {
            color: var(--text-primary) !important;
            background-color: var(--bg-card) !important;
        }

        .dark .select2-dropdown {
            background-color: var(--bg-card) !important;
            border: 1px solid var(--border-subtle) !important;
            color: var(--text-primary) !important;
        }

        .dark .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: var(--accent-solid) !important;
            color: white !important;
        }

        .dark .select2-container--default .select2-results__option[aria-selected="true"] {
            background-color: var(--bg-hover) !important;
        }

        .dark .select2-container--default .select2-search--dropdown .select2-search__field {
            background-color: var(--bg-main) !important;
            border: 1px solid var(--border-subtle) !important;
            color: var(--text-primary) !important;
        }

        /* Generic Dark Badges */
        .dark .badge {
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        #activeThemeIcon {
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-primary);
        }

        .theme-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 120%;
            background-color: var(--bg-card);
            border: 1px solid var(--border-subtle);
            border-radius: 0.75rem;
            min-width: 150px;
            box-shadow: var(--shadow-md);
            z-index: 1000;
            padding: 0.5rem;
        }

        .theme-menu.show {
            display: block;
        }

        .theme-item {
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .theme-item:hover {
            background-color: var(--accent-glow);
            color: var(--accent-solid);
        }

        .theme-item.active {
            color: var(--accent-solid);
            font-weight: 600;
        }

        /* Premium SweetAlert Styling */
        .premium-swal-popup {
            border-radius: 20px !important;
            padding: 2.5rem !important;
            border: 1px solid var(--border-subtle) !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5) !important;
            background-color: var(--bg-card) !important;
            color: var(--text-primary) !important;
            position: fixed !important;
            top: 50% !important;
            left: 50% !important;
            transform: translate(-50%, -50%) !important;
            margin: 0 !important;
            z-index: 9999 !important;
        }

        /* Ensure SweetAlert container is centered */
        .swal2-container {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100% !important;
            z-index: 9999 !important;
        }

        /* SweetAlert popup positioning override */
        .swal2-popup {
            position: fixed !important;
            top: 50% !important;
            left: 50% !important;
            transform: translate(-50%, -50%) !important;
            margin: 0 !important;
        }

        .premium-swal-popup .swal2-title {
            font-size: 1.6rem !important;
            font-weight: 700 !important;
            margin-bottom: 1rem !important;
            color: var(--text-primary) !important;
        }

        .premium-swal-popup .swal2-html-container {
            font-size: 1.1rem !important;
            opacity: 0.9 !important;
            color: var(--text-secondary) !important;
            margin-bottom: 1.5rem !important;
        }

        /* Button Styles */
        .premium-swal-confirm {
            padding: 0.8rem 2.5rem !important;
            border-radius: 12px !important;
            font-weight: 600 !important;
            font-size: 1rem !important;
            transition: all 0.3s ease !important;
            background-color: #10b981 !important;
            color: #ffffff !important;
            border: none !important;
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.4) !important;
        }

        .premium-swal-confirm:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.6) !important;
        }

        .premium-swal-confirm-danger {
            background-color: #ef4444 !important;
            box-shadow: 0 4px 14px rgba(239, 68, 68, 0.4) !important;
        }

        .premium-swal-confirm-danger:hover {
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.6) !important;
        }

        .premium-swal-cancel {
            padding: 0.8rem 2.5rem !important;
            border-radius: 12px !important;
            font-weight: 600 !important;
            font-size: 1rem !important;
            transition: all 0.3s ease !important;
            background-color: #6c757d !important;
            color: #ffffff !important;
            border: none !important;
            margin-left: 10px !important;
        }

        .premium-swal-cancel:hover {
            background-color: #5a6268 !important;
            transform: translateY(-2px) !important;
        }

        .premium-swal-backdrop {
            backdrop-filter: blur(2px) !important;
            background-color: rgba(0, 0, 0, 0.35) !important;
        }

        /* Responsive spacing utilities */
        @media (max-width: 768px) {
            .p-4 {
                padding: 1rem !important;
            }

            .p-5 {
                padding: 1.5rem !important;
            }

            .px-4 {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }

            .mb-4 {
                margin-bottom: 1rem !important;
            }

            .mb-5 {
                margin-bottom: 1.5rem !important;
            }

            .card-body {
                padding: 1rem !important;
            }

            .card-header {
                padding: 0.75rem 1rem !important;
            }

            h2,
            .h2 {
                font-size: 1.25rem !important;
            }

            h4,
            .h4 {
                font-size: 1.1rem !important;
            }

            .btn-lg {
                padding: 0.6rem 1.2rem !important;
                font-size: 0.9rem !important;
            }

            /* Responsive Button Width */
            .btn-mobile-full {
                width: 100% !important;
            }

            @media (min-width: 577px) {
                .btn-mobile-full {
                    width: auto !important;
                }
            }

            /* Form stacking on very small screens */
            .row>* {
                margin-bottom: 0.75rem;
            }

            .row>*:last-child {
                margin-bottom: 0;
            }
        }

        /* Table responsiveness improvements */
        .table-responsive {
            border: 0;
            margin-bottom: 1rem;
        }

        @media (max-width: 600px) {

            .table th,
            .table td {
                padding: 0.5rem !important;
                font-size: 0.8rem !important;
            }
        }

        /* ─────────────────────────────────────────────
           Global Mobile Fixes for all pages
           ───────────────────────────────────────────── */

        /* Fix search input-group max-width on mobile (Hydra / LHR search bars) */
        @media (max-width: 768px) {

            .hospital-card .input-group[style*="max-width"],
            .input-group[style*="max-width: 400px"] {
                max-width: 100% !important;
                width: 100% !important;
            }

            .hospital-card form.d-flex {
                flex-direction: column !important;
                align-items: stretch !important;
            }

            /* Ledger stats — wrap on mobile */
            .ledger-stats-container {
                flex-direction: column !important;
                width: 100% !important;
            }

            .stat-card {
                min-width: unset !important;
                width: 100% !important;
            }

            /* Finance filter action buttons */
            .col-lg-auto.ms-auto.d-flex {
                width: 100% !important;
                margin-left: 0 !important;
            }

            .col-lg-auto.ms-auto.d-flex .btn {
                width: 100% !important;
            }

            /* Patient profile avatar sizing */
            .avatar-large {
                width: 56px !important;
                height: 56px !important;
                font-size: 22px !important;
                border-radius: 12px !important;
            }

            /* Card header with stats pills */
            .stats-pills {
                flex-direction: column !important;
                width: 100% !important;
            }

            .stat-item {
                width: 100% !important;
                justify-content: center !important;
            }

            /* Modal dialog full-width on mobile */
            .modal-dialog {
                margin: 0.5rem !important;
                max-width: calc(100vw - 1rem) !important;
            }

            /* Fix dub_tab_field (patient profile) */
            .dub_tab_field {
                flex-direction: column !important;
            }

            /* Pagination stacking */
            .d-flex.justify-content-between.align-items-center.mt-4,
            .d-flex.justify-content-between.align-items-center.mt-3 {
                flex-direction: column !important;
                gap: 1rem !important;
                align-items: flex-start !important;
            }

            /* Card header action row */
            .card-header .d-flex.justify-content-between {
                flex-direction: column !important;
                gap: 0.75rem !important;
                align-items: flex-start !important;
            }

            /* hospital-title h1 sizing */
            .hospital-title {
                font-size: 1.2rem !important;
            }

            h1.hospital-title[style*="font-size: 1.5rem"] {
                font-size: 1.2rem !important;
            }
        }

        /* Ensure nav overlay doesn't conflict with modals */
        .modal-backdrop {
            z-index: 1040 !important;
        }

        .modal {
            z-index: 1055 !important;
        }

        /* Global fix for select elements overflowing on mobile */
        select {
            max-width: 100% !important;
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
        }

        select option {
            max-width: 100%;
            text-overflow: ellipsis;
            white-space: normal;
        }

        .nav-branch-badge {
            background-color: rgba(20, 184, 166, 0.08);
            border: 1px solid rgba(20, 184, 166, 0.25);
            transition: all 0.3s ease;
            color: #0d9488;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
            margin-right: 8px;
            white-space: nowrap;
        }
        .nav-branch-badge:hover {
            background-color: rgba(20, 184, 166, 0.15);
            border-color: rgba(20, 184, 166, 0.35);
            transform: translateY(-1px);
        }
        .dark .nav-branch-badge {
            background-color: rgba(20, 184, 166, 0.15);
            border-color: rgba(20, 184, 166, 0.35);
            color: #2dd4bf;
        }

        .nav-branch-badge.superadmin {
            background-color: rgba(59, 130, 246, 0.08);
            border-color: rgba(59, 130, 246, 0.25);
            color: #2563eb;
        }
        .nav-branch-badge.superadmin:hover {
            background-color: rgba(59, 130, 246, 0.15);
            border-color: rgba(59, 130, 246, 0.35);
        }
        .dark .nav-branch-badge.superadmin {
            background-color: rgba(59, 130, 246, 0.15);
            border-color: rgba(59, 130, 246, 0.35);
            color: #60a5fa;
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <div class="navbar-sidebar-header">
            <div class="navbar-brand">
                @auth
                    @php
                        $userBranchName = auth()->user()->branch->branch_name ?? null;
                        $isSuperadmin = auth()->user()->hasRole('Superadmin');
                        $branchNameUpper = $userBranchName ? strtoupper($userBranchName) : '';
                    @endphp

                    @if($isSuperadmin)
                        <img src="{{ asset('images/image.png') }}" alt="Figure n Fit" style="height:48px;width:auto;display:block;object-fit:contain;">
                    @elseif($userBranchName)
                        <i class="fas fa-hospital" style="color:#197040;font-size:1.2rem;"></i>
                        {{ $userBranchName }}
                    @else
                        <i class="fas fa-hospital" style="color:#197040;font-size:1.2rem;"></i>
                        Figure 'n' Fit
                    @endif
                @else
                    Shree Vallabh Clinic
                @endauth
            </div>
            <!-- Sidebar toggle button removed per user request -->
            <button class="mobile-menu-btn d-lg-none" id="mobileMenuToggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        <div class="nav-links">
            @auth
                @php
                    $userBranchName = auth()->user()->branch->branch_name ?? null;
                    $isSuperadmin = auth()->user()->hasRole('Superadmin');
                    $isDoctor = (auth()->user()->user_role == 6 || auth()->user()->hasRole('Doctor'));

                    $otherBranches = ['SVC', 'SVC-0001', 'LHR BD', 'LHR', 'BD HYDRA', 'BD HYDRA-0001', 'Hydra', 'HYDRA BD'];

                    $isFromOtherBranch = in_array($userBranchName, $otherBranches) || $isSuperadmin;
                @endphp

                @if(!empty($userBranchName))
                    <span class="nav-branch-badge">
                        <i class="fas fa-code-branch"></i>
                        {{ $userBranchName }}
                    </span>
                @elseif($isSuperadmin)
                    <span class="nav-branch-badge superadmin">
                        <i class="fas fa-user-shield"></i>
                        Superadmin
                    </span>
                @endif

                @if($isDoctor)
                    <a href="{{ $isSuperadmin ? route('admin.dashboard') : route('dashboard') }}"
                        class="{{ request()->is('admin/dashboard*') || request()->is('dashboard*') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        Dashboard
                    </a>
                @endif

                @if(!$isDoctor)

                    @if($isFromOtherBranch)
                        @if ($isSuperadmin)
                            <a href="{{ route('admin.dashboard') }}" class="{{ request()->is('admin/dashboard*') ? 'active' : '' }}">
                                <i class="fas fa-home"></i>
                                Dashboard
                            </a>
                            <div class="dropdown">
                                <a href="#"
                                    class="{{ request()->is('inquiry*') || request()->is('pending*') || request()->is('joined*') || request()->is('diet*') || request()->is('admin/online-abroad-inquiry*') ? 'active' : '' }}">
                                    <i class="fas fa-user"></i>
                                    Inquiry
                                </a>
                                <div class="dropdown-content">
                                    <a href="{{ route('pending.inquiry') }}">
                                        <i class="fas fa-user-clock"></i>
                                        Pending
                                    </a>
                                    <a href="{{ route('followup.patients.appointment') }}">
                                        <i class="fas fa-user-clock"></i>
                                        Follow Up
                                    </a>
                                    <a href="{{ route('joined.inquiry') }}">
                                        <i class="fas fa-chart-bar"></i>
                                        Joined
                                    </a>
                                    <a href="{{ route('diet.chart') }}">
                                        <i class="fas fa-chart-bar"></i>
                                        Diet Chart
                                    </a>
                                    <a href="{{ route('online.abroad.inquiry') }}">
                                        <i class="fas fa-globe"></i>
                                        Online/Abroad
                                    </a>
                                </div>
                            </div>
                            <a href="{{ route('monthly.assessment') }}"
                                class="{{ request()->is('monthly-assessment*') ? 'active' : '' }}">
                                <i class="fas fa-book"></i>
                                Monthly Assessment
                            </a>
                            <a href="{{ route('diet.plan') }}">
                                <i class="fas fa-apple"></i>
                                Diet Plan
                            </a>
                            <a href="{{ route('progress-reports') }}">
                                <i class="fas fa-chart-pie"></i>
                                Progress Report
                            </a>

                        @else
                            <a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard*') ? 'active' : '' }}">
                                <i class="fas fa-home"></i>
                                Dashboard
                            </a>
                        @endif

                        @if ($isSuperadmin)
                            <div class="dropdown">
                                <a href="#">
                                    <i class="fas fa-cog"></i>
                                    Manage
                                </a>
                                <div class="dropdown-content">
                                    <a href="{{ route('create.branch') }}">
                                        <i class="fas fa-code-branch"></i>
                                        Create Branch
                                    </a>
                                    <a href="{{ route('svc.charges') }}">
                                        <i class="fas fa-money-bill"></i>
                                        SVC Charge
                                    </a>
                                    <a href="{{ route('admin.manage-programs') }}">
                                        <i class="fas fa-tasks-alt"></i>
                                        Manage Programs
                                    </a>
                                    <a href="{{ route('nutrition-info') }}">
                                        <i class="fas fa-nutritionix"></i>
                                        Nutrition Info
                                    </a>
                                    <a href="{{ route('recipes.index') }}">
                                        <i class="fas fa-carrot"></i>
                                        Recipe Info
                                    </a>
                                </div>
                            </div>
                        @endif

                        <div class="dropdown">
                            <a href="#"
                                class="{{ request()->is('svc-patient*') || request()->is('indoor-patients*') || request()->is('lhr*') || request()->is('hydra*') ? 'active' : '' }}">
                                <i class="fas fa-code-branch"></i>
                                Other Branches
                            </a>

                            <div class="dropdown-content">
                                @if($isSuperadmin)
                                    <div class="dropdown-nested">
                                        <button class="dropdown-nested-toggle">
                                            <span style="display: flex; align-items: center; gap: 0.75rem;">
                                                <i class="fas fa-user-md"></i>
                                                SVC Patient
                                            </span>
                                            <i class="fas fa-chevron-right"></i>
                                        </button>
                                        <div class="nested-dropdown">
                                            <a href="{{ route('svc-patient') }}"
                                                class="{{ request()->is('svc-patient*') ? 'active' : '' }}">
                                                <i class="fas fa-user-injured"></i>
                                                SVC Patient
                                            </a>
                                            <a href="{{ route('indoor.patients') }}"
                                                class="{{ request()->is('indoor-patients*') ? 'active' : '' }}">
                                                <i class="fas fa-hospital-user"></i>
                                                Indoor Patients
                                            </a>
                                        </div>
                                    </div>

                                    <div class="dropdown-nested">
                                        <button class="dropdown-nested-toggle">
                                            <span style="display: flex; align-items: center; gap: 0.75rem;">
                                                <i class="fas fa-map-marker-alt"></i>
                                                LHR
                                            </span>
                                            <i class="fas fa-chevron-right"></i>
                                        </button>
                                        <div class="nested-dropdown">
                                            <a href="{{ route('lhr.pending') }}">
                                                <i class="fas fa-clock"></i>
                                                LHR Pending
                                            </a>
                                            <a href="{{ route('lhr.joined') }}">
                                                <i class="fas fa-user-check"></i>
                                                LHR Joined
                                            </a>
                                        </div>
                                    </div>

                                    <div class="dropdown-nested">
                                        <button class="dropdown-nested-toggle">
                                            <span style="display: flex; align-items: center; gap: 0.75rem;">
                                                <i class="fas fa-water"></i>
                                                Hydra
                                            </span>
                                            <i class="fas fa-chevron-right"></i>
                                        </button>
                                        <div class="nested-dropdown">
                                            <a href="{{ route('hydra.pending') }}">
                                                <i class="fas fa-clock"></i>
                                                Hydra Pending Data
                                            </a>
                                            <a href="{{ route('hydra.joined') }}">
                                                <i class="fas fa-user-check"></i>
                                                Hydra Joined Data
                                            </a>
                                        </div>
                                    </div>

                                @else
                                    @if(in_array($userBranchName, ['SVC', 'SVC-0001']))
                                        <div class="dropdown-nested">
                                            <button class="dropdown-nested-toggle">
                                                <span style="display: flex; align-items: center; gap: 0.75rem;">
                                                    <i class="fas fa-user-md"></i>
                                                    SVC Patient
                                                </span>
                                                <i class="fas fa-chevron-right"></i>
                                            </button>
                                            <div class="nested-dropdown">
                                                <a href="{{ route('svc-patient') }}"
                                                    class="{{ request()->is('svc-patient*') ? 'active' : '' }}">
                                                    <i class="fas fa-user-injured"></i>
                                                    SVC Patient
                                                </a>
                                                <a href="{{ route('indoor.patients') }}"
                                                    class="{{ request()->is('indoor-patients*') ? 'active' : '' }}">
                                                    <i class="fas fa-hospital-user"></i>
                                                    Indoor Patients
                                                </a>
                                            </div>
                                        </div>

                                    @elseif(in_array($userBranchName, ['LHR BD', 'LHR']))
                                        <div class="dropdown-nested">
                                            <button class="dropdown-nested-toggle">
                                                <span style="display: flex; align-items: center; gap: 0.75rem;">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                    LHR
                                                </span>
                                                <i class="fas fa-chevron-right"></i>
                                            </button>
                                            <div class="nested-dropdown">
                                                <a href="{{ route('lhr.pending') }}">
                                                    <i class="fas fa-clock"></i>
                                                    LHR Pending
                                                </a>
                                                <a href="{{ route('lhr.joined') }}">
                                                    <i class="fas fa-user-check"></i>
                                                    LHR Joined
                                                </a>
                                            </div>
                                        </div>

                                    @elseif(in_array($userBranchName, ['BD HYDRA', 'BD HYDRA-0001', 'Hydra']))
                                        <div class="dropdown-nested">
                                            <button class="dropdown-nested-toggle">
                                                <span style="display: flex; align-items: center; gap: 0.75rem;">
                                                    <i class="fas fa-water"></i>
                                                    Hydra
                                                </span>
                                                <i class="fas fa-chevron-right"></i>
                                            </button>
                                            <div class="nested-dropdown">
                                                <a href="{{ route('hydra.pending') }}">
                                                    <i class="fas fa-clock"></i>
                                                    Hydra Pending Data
                                                </a>
                                                <a href="{{ route('hydra.joined') }}">
                                                    <i class="fas fa-user-check"></i>
                                                    Hydra Joined Data
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>

                    @else
                        <a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard*') ? 'active' : '' }}">
                            <i class="fas fa-home"></i>
                            Dashboard
                        </a>

                        <div class="dropdown">
                            <a href="#"
                                class="{{ request()->is('inquiry*') || request()->is('pending*') || request()->is('joined*') || request()->is('diet*') || request()->is('admin/online-abroad-inquiry*') ? 'active' : '' }}">
                                <i class="fas fa-user"></i>
                                Inquiry
                            </a>
                            <div class="dropdown-content">
                                <a href="{{ route('pending.inquiries') }}">
                                    <i class="fas fa-user-clock"></i>
                                    Pending
                                </a>
                                <a href="{{ route('followup.patients.appointment') }}">
                                    <i class="fas fa-user-clock"></i>
                                    Follow Up
                                </a>
                                <a href="{{ route('joined.inquiry') }}">
                                    <i class="fas fa-chart-bar"></i>
                                    Joined
                                </a>
                                <a href="{{ route('diet.chart') }}">
                                    <i class="fas fa-chart-bar"></i>
                                    Diet Chart
                                </a>
                                <a href="{{ route('online.abroad.inquiry') }}">
                                    <i class="fas fa-globe"></i>
                                    Online/Abroad
                                </a>
                            </div>
                        </div>

                        <a href="{{ route('monthly.assessment') }}"
                            class="{{ request()->is('monthly-assessment*') ? 'active' : '' }}">
                            <i class="fas fa-book"></i>
                            Monthly Assessment
                        </a>

                        <a href="{{ route('diet.plan') }}" class="{{ request()->is('diet-plan*') ? 'active' : '' }}">
                            <i class="fas fa-apple"></i>
                            Diet Plan
                        </a>

                        <a href="{{ route('progress-reports') }}" class="{{ request()->is('progress-report*') ? 'active' : '' }}">
                            <i class="fas fa-chart-pie"></i>
                            Progress Report
                        </a>


                        @if ($isSuperadmin)
                            <div class="dropdown">
                                <a href="#">
                                    <i class="fas fa-cog"></i>
                                    Manage
                                </a>
                                <div class="dropdown-content">
                                    <a href="{{ route('create.branch') }}">
                                        <i class="fas fa-code-branch"></i>
                                        Create Branch
                                    </a>
                                    <a href="{{ route('svc.charges') }}">
                                        <i class="fas fa-money-bill"></i>
                                        SVC Charge
                                    </a>
                                    <a href="{{ route('admin.manage-programs') }}">
                                        <i class="fas fa-tasks-alt"></i>
                                        Manage Programs
                                    </a>
                                    <a href="{{ route('nutrition-info') }}">
                                        <i class="fas fa-nutritionix"></i>
                                        Nutrition Info
                                    </a>
                                    <a href="{{ route('recipes.index') }}">
                                        <i class="fas fa-carrot"></i>
                                        Recipe Info
                                    </a>
                                </div>
                            </div>
                        @endif

                    @endif

                @endif

                @if ($isDoctor)
                    <div class="dropdown">
                        <a href="#"
                            class="{{ request()->is('doctor/my-patients*') || request()->is('doctor/meeting-history*') ? 'active' : '' }}">
                            <i class="fas fa-user-doctor"></i>
                            My Patients
                        </a>
                        <div class="dropdown-content">
                            <a href="{{ route('doctor.my-patients') }}"
                                class="{{ request()->is('doctor/my-patients*') ? 'active' : '' }}">
                                <i class="fas fa-users"></i>
                                My Patients
                            </a>
                            <a href="{{ route('doctor.meeting-history') }}"
                                class="{{ request()->is('doctor/meeting-history*') ? 'active' : '' }}">
                                <i class="fas fa-video"></i>
                                Meeting History
                            </a>
                        </div>
                    </div>
                @endif
                @if(!$isDoctor)
                    <div class="dropdown">
                        <a href="#"
                            class="{{ request()->is('admin/appointments*') || request()->is('patient-analytics*') ? 'active' : '' }}">
                            <i class="fas fa-rocket"></i>
                            Insights &amp; Tools
                        </a>
                        <div class="dropdown-content">
                            <a href="{{ route('appointment.index') }}"
                                class="{{ request()->is('admin/appointments*') ? 'active' : '' }}">
                                <i class="fas fa-calendar-check"></i>
                                Appointments
                            </a>
                            <a href="{{ route('patient.analytics') }}"
                                class="{{ request()->is('patient-analytics*') ? 'active' : '' }}">
                                <i class="fas fa-chart-bar"></i>
                                Analytics
                            </a>
                        </div>
                    </div>

                    <a href="{{ route('add.invoice') }}" class="{{ request()->is('add-invoice*') ? 'active' : '' }}">
                        <i class="fas fa-file-invoice"></i>
                        Invoice
                    </a>

                    <a href="{{ route('transactions.index') }}"
                        class="{{ request()->is('patient-transactions*') ? 'active' : '' }}">
                        <i class="fas fa-file-invoice-dollar"></i>
                        Transactions
                    </a>
                @endif

                <a href="{{ route('profile') }}" class="{{ request()->is('profile*') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i>
                    Settings
                </a>
                <div class="theme-switcher-container">
                    <button class="theme-btn" id="themeBtn" title="Change Theme">
                        <i class="fas fa-sun" id="activeThemeIcon"></i>
                    </button>
                    <div class="theme-menu" id="themeMenu">
                        <div class="theme-item" data-theme="light">
                            <i class="fas fa-sun"></i> Light
                        </div>
                        <div class="theme-item" data-theme="dark">
                            <i class="fas fa-moon"></i> Dark
                        </div>
                        <div class="theme-item" data-theme="system">
                            <i class="fas fa-desktop"></i> System
                        </div>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="logout-nav-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </button>
                </form>

            @else
                <a href="{{ route('show-login') }}">
                    <i class="fas fa-sign-in-alt"></i>
                    Login
                </a>
                <a href="{{ route('show-register') }}">
                    <i class="fas fa-user-plus"></i>
                    Register
                </a>
            @endauth
        </div>
    </nav>

    <div class="main-content">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios@1.6.2/dist/axios.min.js"></script>

    @stack('scripts')
</body>

</html>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const otherBranchesBtn = document.getElementById('otherBranchesBtn');
        const otherBranchesDropdown = document.getElementById('otherBranchesDropdown');
        const navLinks = document.querySelectorAll('.nav-links a');

        if (otherBranchesBtn && otherBranchesDropdown) {
        }

        // Dropdown Toggle Logic - Anti-Conflict Mode (Capture Phase)
        // We use capture: true to intercept clicks BEFORE conflicting scripts (Bootstrap) see them.

        document.addEventListener('click', function (e) {
            const target = e.target;

            // 1. Nested Dropdown Toggles (LHR, et c.)
            const nestedToggle = target.closest('.dropdown-nested-toggle');
            if (nestedToggle) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation(); // CRITICAL: Stop everyone else

                const parent = nestedToggle.closest('.dropdown-nested');
                if (parent) {
                    parent.classList.toggle('active');
                }
                return;
            }

            // 2. Main Dropdown Toggles
            const mainDropdownToggle = target.closest('.dropdown > a');
            if (mainDropdownToggle) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation(); // CRITICAL: Stop everyone else

                const parent = mainDropdownToggle.parentElement;
                const wasOpen = parent.classList.contains('show');

                // Close other main dropdowns
                document.querySelectorAll('.dropdown').forEach(d => {
                    if (d !== parent) d.classList.remove('show');
                });

                // Close all nested dropdowns
                document.querySelectorAll('.dropdown-nested').forEach(dn => {
                    dn.classList.remove('active');
                });

                // Toggle Current
                if (wasOpen) {
                    parent.classList.remove('show');
                } else {
                    parent.classList.add('show');
                }
                return;
            }

            // 3. Link Shielding (Allow navigation, but stop bubbling)
            const isLink = target.closest('a');
            if (isLink) {
                const href = isLink.getAttribute('href');
                if (href && href !== '#' && href !== '') {
                    // Allow default (navigation), but stop bubbling
                    e.stopPropagation();
                    return;
                }
            }

            // 4. Outside Click (Close everything)
            // If we are here, we are not on a toggle or a link. 
            // We can safely assume it's an outside click or a click on padding/content.
            // We let this close our menus.
            const insideDropdown = target.closest('.dropdown');
            if (!insideDropdown) {
                document.querySelectorAll('.dropdown').forEach(d => d.classList.remove('show'));
                document.querySelectorAll('.dropdown-nested').forEach(dn => dn.classList.remove('active'));
                const themeMenu = document.getElementById('themeMenu');
                if (themeMenu) themeMenu.classList.remove('show');
            }

            // Note: We don't stop propagation here, allowing other things to work.

        }, true); // <--- TRUE = Capture Phase (The Magic Fix)

        const currentUrl = window.location.pathname;
        navLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (href && currentUrl.startsWith(href) && href !== '/') {
                link.classList.add('active');
            }
        });

        window.addEventListener('resize', function () {
            if (window.innerWidth > 992) {
                document.querySelectorAll('.dropdown-nested').forEach(dn => dn.classList.remove('active'));
                const navLinks = document.querySelector('.nav-links');
                if (navLinks) {
                    navLinks.classList.remove('show');
                    document.body.style.overflow = '';
                }
                const icon = document.querySelector('#mobileMenuToggle i');
                if (icon) {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            }
        });

        // Mobile Menu Toggle logic
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const navLinksContainer = document.querySelector('.nav-links');

        if (mobileMenuToggle && navLinksContainer) {
            mobileMenuToggle.addEventListener('click', function (e) {
                e.stopPropagation();
                navLinksContainer.classList.toggle('show');
                const icon = mobileMenuToggle.querySelector('i');
                if (navLinksContainer.classList.contains('show')) {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-times');
                    document.body.style.overflow = 'hidden';
                } else {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                    document.body.style.overflow = '';
                }
            });

            // Prevent links from closing menu if they are dropdown toggles
            navLinksContainer.addEventListener('click', function (e) {
                if (e.target.closest('.dropdown > a') || e.target.closest('.dropdown-nested-toggle')) {
                    // Let the dropdown logic handle it
                } else if (e.target.closest('a')) {
                    navLinksContainer.classList.remove('show');
                    document.body.style.overflow = '';
                    const icon = mobileMenuToggle.querySelector('i');
                    if (icon) {
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-bars');
                    }
                }
            });
        }

        // Theme Management
        const themeBtn = document.getElementById('themeBtn');
        const themeMenu = document.getElementById('themeMenu');
        const activeIcon = document.getElementById('activeThemeIcon');
        const themeItems = document.querySelectorAll('.theme-item');

        const icons = {
            light: 'fa-sun',
            dark: 'fa-moon',
            system: 'fa-desktop'
        };

        function applyTheme(theme) {
            if (!['light', 'dark', 'system'].includes(theme)) {
                theme = 'system';
            }
            const isDark = theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
            document.documentElement.classList.toggle('dark', isDark);
            activeIcon.className = 'fa-solid ' + icons[theme] + ' fa-fw';

            themeItems.forEach(item => {
                item.classList.toggle('active', item.dataset.theme === theme);
            });

            localStorage.setItem('theme', theme);
            // Also set body and html color to prevent flash/glitch
            const bgColor = isDark ? '#0b1120' : '#f8fafc';
            document.body.style.backgroundColor = bgColor;
            document.documentElement.style.backgroundColor = bgColor;
        }

        themeBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            themeMenu.classList.toggle('show');
        });

        themeItems.forEach(item => {
            item.addEventListener('click', () => {
                applyTheme(item.dataset.theme);
                themeMenu.classList.remove('show');
            });
        });

        document.addEventListener('click', () => themeMenu.classList.remove('show'));

        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
            if (localStorage.getItem('theme') === 'system') {
                applyTheme('system');
            }
        });

        // Init
        applyTheme(localStorage.getItem('theme') || 'system');

        // Layout logic removed per user request to only have navbar
    });
</script>
<script>
    // Helper function for SweetAlert config
    window.getSwalConfig = function (icon) {
        const isDark = document.documentElement.classList.contains('dark');
        return {
            icon: icon,
            background: isDark ? '#1e293b' : '#ffffff',
            color: isDark ? '#f1f5f9' : '#0f172a',
            iconColor: icon === 'success' ? '#10b981' : (icon === 'error' ? '#ef4444' : (icon === 'question' ? '#3b82f6' : '#f59e0b')),
            customClass: {
                popup: 'premium-swal-popup',
                backdrop: 'premium-swal-backdrop',
                confirmButton: icon === 'error' ? 'premium-swal-confirm premium-swal-confirm-danger' : 'premium-swal-confirm',
                cancelButton: 'premium-swal-cancel'
            },
            buttonsStyling: false,
            showCancelButton: icon === 'question',
        };
    };

    // Global SweetAlert Handler for Laravel Session Flash Messages
    @if(session('error'))
        Swal.fire({
            ...getSwalConfig('error'),
            title: 'Error!',
            text: "{{ session('error') }}",
            showConfirmButton: true,
            showCancelButton: false,
        });
    @endif

    @if(session('success'))
        Swal.fire({
            ...getSwalConfig('success'),
            title: 'Success!',
            text: "{{ session('success') }}",
            timer: 3000,
            showConfirmButton: false,
        });
    @endif

    @if($errors->any())
        Swal.fire({
            ...getSwalConfig('error'),
            title: 'Validation Error',
            html: '<ul style="text-align: left; list-style: none; padding-left: 0;">@foreach($errors->all() as $error)<li><i class="fas fa-times-circle me-2"></i>{{ $error }}</li>@endforeach</ul>',
            showConfirmButton: true,
            showCancelButton: false,
        });
    @endif

    window.copyPatientZoomLink = function(url) {
        const absoluteUrl = new URL(url, window.location.origin).href;
        navigator.clipboard.writeText(absoluteUrl).then(function () {
            Swal.fire({
                ...getSwalConfig('success'),
                icon: 'success',
                title: 'Link Copied!',
                text: 'Patient join link copied to clipboard. You can now share it with the patient.',
                timer: 2000,
                showConfirmButton: false,
            });
        }, function (err) {
            console.error('Could not copy text: ', err);
            const textArea = document.createElement("textarea");
            textArea.value = absoluteUrl;
            document.body.appendChild(textArea);
            textArea.select();
            try {
                document.execCommand('copy');
                Swal.fire({
                    ...getSwalConfig('success'),
                    icon: 'success',
                    title: 'Link Copied!',
                    text: 'Patient join link copied to clipboard.',
                    timer: 2000,
                    showConfirmButton: false,
                });
            } catch (err) {
                Swal.fire({
                    ...getSwalConfig('error'),
                    icon: 'error',
                    title: 'Failed to Copy',
                    text: 'Please copy the link manually from the browser address bar.',
                });
            }
            document.body.removeChild(textArea);
        });
    };
</script>
</body>

</html>