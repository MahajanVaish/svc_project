<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Figure N Fit</title>
    <link rel="icon" href="{{ asset('images/588hospital_100778.webp') }}" type="image/webp">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        (function() {
            const theme = localStorage.getItem('theme') || 'system';
            const isDark = theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
            document.documentElement.classList.toggle('dark', isDark);
            document.documentElement.style.backgroundColor = isDark ? '#0b1120' : '#f0fdf4';
        })();
    </script>

    <style>
        :root {
            --font-family: 'Plus Jakarta Sans', sans-serif;
            --bg-main: #f0fdf4;
            --bg-card: #ffffff;
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --border-subtle: #cbd5e1;
            --accent-glow: rgba(8, 104, 56, 0.15);
            --accent-solid: #086838;
            --accent-hover: #06502b;
            --input-bg: #f8fafc;
            --input-border: #e2e8f0;
            --section-bg: linear-gradient(135deg, #e6f4ea 0%, #c9ebe2 100%);
            --card-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
        }

        .dark {
            --bg-main: #0b1120;
            --bg-card: #1e293b;
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --border-subtle: #334155;
            --accent-glow: rgba(52, 211, 153, 0.2);
            --accent-solid: #10b981;
            --accent-hover: #34d399;
            --input-bg: #0f172a;
            --input-border: #334155;
            --section-bg: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            --card-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.4), 0 8px 10px -6px rgba(0, 0, 0, 0.3);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: var(--font-family);
        }

        body {
            background-color: var(--bg-main);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s ease, color 0.3s ease;
            overflow-x: hidden;
        }

        .full-screen-container {
            display: flex;
            width: 100vw;
            height: 100vh;
        }

        .image-section {
            flex: 1.1;
            background: var(--section-bg);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px;
            position: relative;
        }

        .image-container {
            max-width: 520px;
            width: 100%;
            text-align: center;
        }

        .image-container img {
            width: 100%;
            height: auto;
            border-radius: 16px;
            filter: drop-shadow(0 15px 25px rgba(0,0,0,0.1));
            transition: transform 0.4s ease;
        }

        .image-container img:hover {
            transform: translateY(-5px);
        }

        .hero-tagline {
            margin-top: 24px;
            font-size: 22px;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.5px;
        }

        .hero-subtext {
            margin-top: 8px;
            font-size: 14px;
            color: var(--text-secondary);
        }

        .form-section {
            flex: 0.9;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg-main);
            padding: 40px;
        }

        .login-card {
            width: 100%;
            max-width: 440px;
            background: var(--bg-card);
            border-radius: 20px;
            padding: 40px 36px;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--border-subtle);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .brand-logo-area {
            text-align: center;
            margin-bottom: 30px;
        }

        .brand-icon-wrapper {
            width: 60px;
            height: 60px;
            background: var(--accent-glow);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            color: var(--accent-solid);
            font-size: 26px;
            border: 1px solid var(--accent-glow);
        }

        .brand-title {
            font-size: 26px;
            font-weight: 800;
            color: var(--text-primary);
            letter-spacing: -0.5px;
            margin-bottom: 6px;
        }

        .brand-subtitle {
            font-size: 14px;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 22px;
            position: relative;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 8px;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            color: var(--text-secondary);
            font-size: 16px;
            transition: color 0.3s ease;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px 14px 46px;
            border: 1.5px solid var(--input-border);
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            background: var(--input-bg);
            color: var(--text-primary);
            transition: all 0.25s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--accent-solid);
            box-shadow: 0 0 0 4px var(--accent-glow);
            background: var(--bg-card);
        }

        .form-input:focus + .input-icon,
        .input-wrapper:focus-within .input-icon {
            color: var(--accent-solid);
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            cursor: pointer;
            color: var(--text-secondary);
            font-size: 16px;
            transition: color 0.3s ease;
            z-index: 5;
        }

        .password-toggle:hover {
            color: var(--accent-solid);
        }

        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
            font-size: 14px;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            user-select: none;
            color: var(--text-primary);
            font-weight: 500;
        }

        .checkbox-container input {
            accent-color: var(--accent-solid);
            width: 18px;
            height: 18px;
            cursor: pointer;
            border-radius: 4px;
        }

        .forgot-link {
            color: var(--accent-solid);
            text-decoration: none;
            font-weight: 600;
            font-size: 13px;
            transition: all 0.2s ease;
        }

        .forgot-link:hover {
            color: var(--accent-hover);
            text-decoration: underline;
        }

        .submit-btn {
            width: 100%;
            padding: 15px;
            background: var(--accent-solid);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 4px 12px var(--accent-glow);
        }

        .submit-btn:hover {
            background: var(--accent-hover);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px var(--accent-glow);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .register-footer {
            text-align: center;
            margin-top: 26px;
            padding-top: 20px;
            border-top: 1px solid var(--border-subtle);
            font-size: 14px;
            color: var(--text-secondary);
        }

        .register-footer a {
            color: var(--accent-solid);
            text-decoration: none;
            font-weight: 700;
            margin-left: 4px;
        }

        .register-footer a:hover {
            text-decoration: underline;
        }

        /* Floating Theme Switcher */
        .auth-theme-switcher {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 1000;
        }

        .theme-btn {
            background: var(--bg-card);
            border: 1px solid var(--border-subtle);
            color: var(--text-primary);
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        .theme-btn:hover {
            border-color: var(--accent-solid);
            color: var(--accent-solid);
            transform: scale(1.05);
        }

        .theme-menu {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 10px;
            background: var(--bg-card);
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            padding: 8px;
            min-width: 150px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15);
        }

        .theme-menu.show {
            display: block;
        }

        .theme-item {
            padding: 10px 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-secondary);
            transition: all 0.2s ease;
        }

        .theme-item:hover, .theme-item.active {
            background: var(--accent-glow);
            color: var(--accent-solid);
            font-weight: 600;
        }

        /* Mobile Responsive */
        @media (max-width: 992px) {
            .full-screen-container {
                flex-direction: column;
            }

            .image-section {
                padding: 30px 20px;
                flex: none;
            }

            .image-container {
                max-width: 320px;
            }

            .hero-tagline { font-size: 18px; }
            .hero-subtext { display: none; }

            .form-section {
                padding: 24px 20px 40px;
                flex: 1;
            }

            .login-card {
                padding: 30px 24px;
            }
        }
    </style>
</head>
<body>
    <div class="auth-theme-switcher">
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

    <div class="full-screen-container">
        <div class="image-section">
            <div class="image-container">
                <img src="{{ asset('/images/figure-n-fit-login.png') }}" alt="Figure N Fit">
                <h3 class="hero-tagline">Welcome to Figure N Fit</h3>
                <p class="hero-subtext">Comprehensive Patient & Clinic Management System</p>
            </div>
        </div>

        <div class="form-section">
            <div class="login-card">
                <div class="brand-logo-area">
                    <div class="brand-icon-wrapper">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <h2 class="brand-title">Account Login</h2>
                    <p class="brand-subtitle">Sign in to access your dashboard</p>
                </div>

                @if (session('success'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: "{{ session('success') }}",
                                confirmButtonColor: '#086838',
                                timer: 3000
                            });
                        });
                    </script>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger d-flex align-items-center mb-4 p-3 rounded-3" style="background: rgba(220, 53, 69, 0.1); border: 1px solid rgba(220, 53, 69, 0.3); color: #dc3545;">
                        <i class="fas fa-exclamation-circle me-2 font-size-18"></i>
                        <span class="small font-weight-600">{{ $errors->first() }}</span>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" id="loginForm">
                    @csrf

                    <div class="form-group">
                        <label class="form-label" for="email">Email Address</label>
                        <div class="input-wrapper">
                            <input type="email" name="email" id="email" class="form-input" placeholder="name@domain.com" value="{{ old('email') }}" required autofocus autocomplete="username">
                            <i class="fas fa-envelope input-icon"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">Password</label>
                        <div class="input-wrapper">
                            <input type="password" name="password" id="password" class="form-input" placeholder="••••••••" required autocomplete="current-password">
                            <i class="fas fa-lock input-icon"></i>
                            <i class="fas fa-eye password-toggle" id="togglePassword" title="Show/Hide Password"></i>
                        </div>
                    </div>

                    <div class="form-options">
                        <label class="checkbox-container">
                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <span>Remember Me</span>
                        </label>
                        <a href="{{ route('password.forgot') }}" class="forgot-link">Forgot Password?</a>
                    </div>

                    <button type="submit" class="submit-btn" id="submitBtn">
                        <span>LOG IN</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </form>

                <div class="register-footer">
                    Don't have an account? <a href="{{ route('show-register') }}">Register Now</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Theme Switcher Logic
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
                if (!['light', 'dark', 'system'].includes(theme)) theme = 'system';
                const isDark = theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
                document.documentElement.classList.toggle('dark', isDark);
                activeIcon.className = 'fas ' + icons[theme];
                
                themeItems.forEach(item => {
                    item.classList.toggle('active', item.dataset.theme === theme);
                });

                localStorage.setItem('theme', theme);
                document.documentElement.style.backgroundColor = isDark ? '#0b1120' : '#f0fdf4';
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

            // Password Toggle
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');

            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function() {
                    const isPassword = passwordInput.getAttribute('type') === 'password';
                    passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                    this.classList.toggle('fa-eye', !isPassword);
                    this.classList.toggle('fa-eye-slash', isPassword);
                });
            }

            // Form Submit Loader
            const loginForm = document.getElementById('loginForm');
            const submitBtn = document.getElementById('submitBtn');

            if (loginForm && submitBtn) {
                loginForm.addEventListener('submit', function() {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Logging in...';
                });
            }

            // Init Theme
            applyTheme(localStorage.getItem('theme') || 'system');
        });
    </script>
</body>
</html>
