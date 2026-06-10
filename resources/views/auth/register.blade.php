<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script>
        (function() {
            const theme = localStorage.getItem('theme') || 'system';
            const isDark = theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
            document.documentElement.classList.toggle('dark', isDark);
            
            const bgColor = isDark ? '#0b1120' : '#f8fafc';
            document.documentElement.style.backgroundColor = bgColor;
        })();
    </script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --bg-main: #f8fafc;
            --bg-card: #ffffff;
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --border-subtle: #e2e8f0;
            --accent-glow: rgba(5, 150, 105, 0.1);
            --accent-solid: #059669;
            --accent-hover: #047857;
            --input-bg: #ffffff;
            --section-bg: #c9ebe2;
        }

        .dark {
            --bg-main: #0b1120;
            --bg-card: #1e293b;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --border-subtle: #334155;
            --accent-glow: rgba(52, 211, 153, 0.15);
            --accent-solid: #10b981;
            --accent-hover: #34d399;
            --input-bg: #0f172a;
            --section-bg: #0f172a;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--bg-main);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .full-screen-container {
            display: flex;
            width: 100vw;
            height: 100vh;
        }

        .image-section {
            flex: 1;
            background: var(--bg-main);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .image-container {
            max-width: 500px;
            width: 100%;
        }

        .image-container img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .form-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--section-bg);
            padding: 40px;
        }

        .register-container {
            width: 100%;
            max-width: 400px;
            background: var(--bg-card);
            border-radius: 8px;
            padding: 0;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border-subtle);
        }

        .brand-header {
            text-align: center;
            margin-bottom: 30px;
            padding: 0 20px;
        }

        .brand-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 5px;
            margin-top: 25px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .form-container {
            padding: 0 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 8px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-input, .form-select {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid var(--border-subtle);
            border-radius: 6px;
            font-size: 15px;
            background: var(--input-bg);
            color: var(--text-primary);
            transition: all 0.3s ease;
        }

        .form-input:focus, .form-select:focus {
            outline: none;
            border-color: var(--accent-solid);
            box-shadow: 0 0 0 3px var(--accent-glow);
        }

        .submit-btn {
            width: 100%;
            padding: 15px;
            background: var(--accent-solid);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .submit-btn:hover {
            background: var(--accent-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px var(--accent-glow);
        }

        .login-link {
            text-align: center;
            margin-top: 25px;
            padding: 20px 0 20px;
            border-top: 1px solid var(--border-subtle);
            font-size: 14px;
            color: var(--text-secondary);
        }

        .login-link a {
            color: var(--accent-solid);
            text-decoration: none;
            font-weight: 600;
            margin-left: 5px;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 6px;
            margin-bottom: 25px;
            font-size: 14px;
            border-left: 4px solid;
            background: var(--bg-card);
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border-left-color: #dc3545;
        }

        /* Floating Theme Switcher */
        .auth-theme-switcher {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }

        .theme-btn {
            background: var(--bg-card);
            border: 1px solid var(--border-subtle);
            color: var(--text-primary);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .theme-btn:hover {
            border-color: var(--accent-solid);
            color: var(--accent-solid);
        }

        .theme-menu {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 10px;
            background: var(--bg-card);
            border: 1px solid var(--border-subtle);
            border-radius: 8px;
            padding: 8px;
            min-width: 140px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .theme-menu.show {
            display: block;
        }

        .theme-item {
            padding: 10px 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            color: var(--text-secondary);
            transition: all 0.2s ease;
        }

        .theme-item:hover {
            background: var(--accent-glow);
            color: var(--accent-solid);
        }

        .theme-item.active {
            background: var(--accent-glow);
            color: var(--accent-solid);
            font-weight: 600;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .full-screen-container {
                flex-direction: column;
            }

            .image-section {
                padding: 30px 20px;
                flex: none;
                height: auto;
            }

            .form-section {
                padding: 30px 20px;
                flex: 1;
            }

            .image-container {
                max-width: 400px;
            }

            .register-container {
                max-width: 100%;
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
                <img src="public/images/figure-n-fit-login.png" alt="Register Illustration">
            </div>
        </div>

        <div class="form-section">
            <div class="register-container">
                <div class="brand-header">
                    <h1 class="brand-title">Register</h1>
                </div>

                <div class="form-container">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul style="margin-bottom: 0; padding-left: 20px;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('register') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Branch</label>
                            <select name="branch_id" class="form-select" required>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-input" placeholder="Enter full name" value="{{ old('name') }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-input" placeholder="Enter email" value="{{ old('email') }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-input" placeholder="Enter password" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-input" placeholder="Confirm password" required>
                        </div>

                        <button type="submit" class="submit-btn">REGISTER</button>
                    </form>

                    <p class="login-link">
                        Already have an account? <a href="{{ route('show-login') }}">Login</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
                const isDark = theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
                document.documentElement.classList.toggle('dark', isDark);
                activeIcon.className = 'fas ' + icons[theme];
                
                themeItems.forEach(item => {
                    item.classList.toggle('active', item.dataset.theme === theme);
                });

                localStorage.setItem('theme', theme);
                document.body.style.backgroundColor = isDark ? '#0b1120' : '#f8fafc';
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
        });
    </script>
</body>
</html>
