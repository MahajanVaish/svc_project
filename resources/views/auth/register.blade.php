<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Figure N Fit</title>
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
            overflow-y: auto;
        }

        .register-card {
            width: 100%;
            max-width: 460px;
            background: var(--bg-card);
            border-radius: 20px;
            padding: 36px 32px;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--border-subtle);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            margin: auto;
        }

        .brand-logo-area {
            text-align: center;
            margin-bottom: 24px;
        }

        .brand-icon-wrapper {
            width: 56px;
            height: 56px;
            background: var(--accent-glow);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            color: var(--accent-solid);
            font-size: 24px;
            border: 1px solid var(--accent-glow);
        }

        .brand-title {
            font-size: 24px;
            font-weight: 800;
            color: var(--text-primary);
            letter-spacing: -0.5px;
            margin-bottom: 4px;
        }

        .brand-subtitle {
            font-size: 13px;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 18px;
            position: relative;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 6px;
            font-size: 12px;
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
            font-size: 15px;
            transition: color 0.3s ease;
            z-index: 2;
        }

        .form-input, .form-select {
            width: 100%;
            padding: 12px 16px 12px 44px;
            border: 1.5px solid var(--input-border);
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            background: var(--input-bg);
            color: var(--text-primary);
            transition: all 0.25s ease;
        }

        .form-input:focus, .form-select:focus {
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
            right: 14px;
            cursor: pointer;
            color: var(--text-secondary);
            font-size: 15px;
            transition: color 0.3s ease;
            z-index: 5;
        }

        .password-toggle:hover {
            color: var(--accent-solid);
        }

        /* Password Strength Meter */
        .strength-meter {
            height: 4px;
            background: var(--input-border);
            border-radius: 4px;
            margin-top: 8px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .strength-bar {
            height: 100%;
            width: 0%;
            transition: all 0.3s ease;
            border-radius: 4px;
        }

        .strength-weak { width: 33%; background: #ef4444; }
        .strength-medium { width: 66%; background: #f59e0b; }
        .strength-strong { width: 100%; background: #10b981; }

        .strength-label {
            font-size: 11px;
            font-weight: 600;
            margin-top: 4px;
            display: flex;
            justify-content: space-between;
            color: var(--text-secondary);
        }

        .match-status {
            font-size: 12px;
            font-weight: 600;
            margin-top: 4px;
            display: none;
        }

        .match-status.matched {
            display: block;
            color: #10b981;
        }

        .match-status.unmatched {
            display: block;
            color: #ef4444;
        }

        .submit-btn {
            width: 100%;
            padding: 14px;
            background: var(--accent-solid);
            color: #ffffff;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 10px;
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

        .login-footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid var(--border-subtle);
            font-size: 13px;
            color: var(--text-secondary);
        }

        .login-footer a {
            color: var(--accent-solid);
            text-decoration: none;
            font-weight: 700;
            margin-left: 4px;
        }

        .login-footer a:hover {
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
                max-width: 300px;
            }

            .hero-tagline { font-size: 18px; }
            .hero-subtext { display: none; }

            .form-section {
                padding: 20px 16px 40px;
                flex: 1;
            }

            .register-card {
                padding: 26px 20px;
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
                <img src="{{ asset('/images/figure-n-fit-login.png') }}" alt="Figure N Fit Register">
                <h3 class="hero-tagline">Join Figure N Fit Team</h3>
                <p class="hero-subtext">Create your account to start managing patients and clinics</p>
            </div>
        </div>

        <div class="form-section">
            <div class="register-card">
                <div class="brand-logo-area">
                    <div class="brand-icon-wrapper">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h2 class="brand-title">Create Account</h2>
                    <p class="brand-subtitle">Fill in your details to register</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger d-flex align-items-center mb-3 p-3 rounded-3" style="background: rgba(220, 53, 69, 0.1); border: 1px solid rgba(220, 53, 69, 0.3); color: #dc3545;">
                        <i class="fas fa-exclamation-circle me-2 font-size-18"></i>
                        <span class="small font-weight-600">{{ $errors->first() }}</span>
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST" id="registerForm">
                    @csrf

                    <div class="form-group">
                        <label class="form-label" for="branch_id">Select Branch</label>
                        <div class="input-wrapper">
                            <select name="branch_id" id="branch_id" class="form-select" required>
                                <option value="" disabled selected>Choose your branch...</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->branch_name }}
                                    </option>
                                @endforeach
                            </select>
                            <i class="fas fa-building input-icon"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="name">Full Name</label>
                        <div class="input-wrapper">
                            <input type="text" name="name" id="name" class="form-input" placeholder="e.g. John Doe" value="{{ old('name') }}" required autocomplete="name">
                            <i class="fas fa-user input-icon"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email">Email Address</label>
                        <div class="input-wrapper">
                            <input type="email" name="email" id="email" class="form-input" placeholder="name@domain.com" value="{{ old('email') }}" required autocomplete="email">
                            <i class="fas fa-envelope input-icon"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">Password</label>
                        <div class="input-wrapper">
                            <input type="password" name="password" id="password" class="form-input" placeholder="Min. 6 characters" required autocomplete="new-password">
                            <i class="fas fa-lock input-icon"></i>
                            <i class="fas fa-eye password-toggle" id="togglePassword" title="Show/Hide Password"></i>
                        </div>
                        <div class="strength-meter">
                            <div class="strength-bar" id="strengthBar"></div>
                        </div>
                        <div class="strength-label">
                            <span>Password Strength:</span>
                            <span id="strengthText" style="font-weight: 700;">Too short</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">Confirm Password</label>
                        <div class="input-wrapper">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" placeholder="Re-enter password" required autocomplete="new-password">
                            <i class="fas fa-shield-alt input-icon"></i>
                            <i class="fas fa-eye password-toggle" id="toggleConfirmPassword" title="Show/Hide Password"></i>
                        </div>
                        <div class="match-status" id="matchStatus"></div>
                    </div>

                    <button type="submit" class="submit-btn" id="submitBtn">
                        <span>CREATE ACCOUNT</span>
                        <i class="fas fa-user-check"></i>
                    </button>
                </form>

                <div class="login-footer">
                    Already have an account? <a href="{{ route('show-login') }}">Login Here</a>
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

            // Password Toggle Functions
            function setupToggle(toggleId, inputId) {
                const toggleBtn = document.getElementById(toggleId);
                const inputEl = document.getElementById(inputId);
                if (toggleBtn && inputEl) {
                    toggleBtn.addEventListener('click', function() {
                        const isPassword = inputEl.getAttribute('type') === 'password';
                        inputEl.setAttribute('type', isPassword ? 'text' : 'password');
                        this.classList.toggle('fa-eye', !isPassword);
                        this.classList.toggle('fa-eye-slash', isPassword);
                    });
                }
            }

            setupToggle('togglePassword', 'password');
            setupToggle('toggleConfirmPassword', 'password_confirmation');

            // Password Strength & Match Real-time Indicator
            const passwordInput = document.getElementById('password');
            const confirmInput = document.getElementById('password_confirmation');
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');
            const matchStatus = document.getElementById('matchStatus');

            passwordInput.addEventListener('input', function() {
                const val = this.value;
                let score = 0;

                if (val.length >= 6) score++;
                if (/[A-Z]/.test(val)) score++;
                if (/[0-9]/.test(val)) score++;
                if (/[^A-Za-z0-9]/.test(val)) score++;

                strengthBar.className = 'strength-bar';
                if (val.length === 0) {
                    strengthBar.style.width = '0%';
                    strengthText.innerText = 'Too short';
                    strengthText.style.color = 'var(--text-secondary)';
                } else if (score <= 1) {
                    strengthBar.classList.add('strength-weak');
                    strengthText.innerText = 'Weak';
                    strengthText.style.color = '#ef4444';
                } else if (score === 2 || score === 3) {
                    strengthBar.classList.add('strength-medium');
                    strengthText.innerText = 'Medium';
                    strengthText.style.color = '#f59e0b';
                } else {
                    strengthBar.classList.add('strength-strong');
                    strengthText.innerText = 'Strong';
                    strengthText.style.color = '#10b981';
                }

                checkMatch();
            });

            confirmInput.addEventListener('input', checkMatch);

            function checkMatch() {
                const pVal = passwordInput.value;
                const cVal = confirmInput.value;

                if (!cVal) {
                    matchStatus.className = 'match-status';
                    matchStatus.innerText = '';
                    return;
                }

                if (pVal === cVal) {
                    matchStatus.className = 'match-status matched';
                    matchStatus.innerHTML = '<i class="fas fa-check-circle me-1"></i> Passwords Match';
                } else {
                    matchStatus.className = 'match-status unmatched';
                    matchStatus.innerHTML = '<i class="fas fa-times-circle me-1"></i> Passwords do not match';
                }
            }

            // Form Submit Loader
            const registerForm = document.getElementById('registerForm');
            const submitBtn = document.getElementById('submitBtn');

            if (registerForm && submitBtn) {
                registerForm.addEventListener('submit', function() {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Registering...';
                });
            }

            // Init Theme
            applyTheme(localStorage.getItem('theme') || 'system');
        });
    </script>
</body>
</html>
