<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <script>
        (function () {
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

        .login-container {
            width: 100%;
            max-width: 420px;
            background: var(--bg-card);
            border-radius: 8px;
            padding: 0;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border-subtle);
        }

        .brand-header {
            text-align: center;
            margin-bottom: 10px;
            padding: 0 20px;
        }

        .brand-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 5px;
            margin-top: 25px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .brand-subtitle {
            font-size: 14px;
            color: var(--text-secondary);
            margin-bottom: 15px;
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

        .form-input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid var(--border-subtle);
            border-radius: 6px;
            font-size: 15px;
            background: var(--input-bg);
            color: var(--text-primary);
            transition: all 0.3s ease;
        }

        .form-input:focus {
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

        .back-link {
            text-align: center;
            margin-top: 20px;
            padding: 20px 0 20px;
            border-top: 1px solid var(--border-subtle);
            font-size: 14px;
            color: var(--text-secondary);
        }

        .back-link a {
            color: var(--accent-solid);
            text-decoration: none;
            font-weight: 600;
            margin-left: 5px;
        }

        .back-link a:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
            border-left: 4px solid;
            background: var(--bg-card);
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left-color: #28a745;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border-left-color: #dc3545;
        }

        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border-left-color: #17a2b8;
        }

        /* Step indicator */
        .step-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 25px;
            padding: 0 20px;
        }

        .step-dot {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
            transition: all 0.3s ease;
        }

        .step-dot.active {
            background: var(--accent-solid);
            color: white;
        }

        .step-dot.inactive {
            background: var(--border-subtle);
            color: var(--text-secondary);
        }

        .step-dot.completed {
            background: var(--accent-solid);
            color: white;
        }

        .step-line {
            width: 50px;
            height: 2px;
            background: var(--border-subtle);
            transition: background 0.3s ease;
        }

        .step-line.active {
            background: var(--accent-solid);
        }

        /* Password strength */
        .password-toggle {
            position: relative;
        }

        .password-toggle .toggle-eye {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--text-secondary);
            font-size: 16px;
        }

        .password-toggle .toggle-eye:hover {
            color: var(--accent-solid);
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

        /* Hide steps */
        .step-hidden {
            display: none;
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

            .login-container {
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
                <img src="/images/figure-n-fit-login.png" alt="Forgot Password Illustration">
            </div>
        </div>

        <div class="form-section">
            <div class="login-container">
                <div class="brand-header">
                    <h1 class="brand-title">Reset Password</h1>
                    <p class="brand-subtitle" id="stepSubtitle">Enter your email to reset your password</p>
                </div>

                <!-- Step Indicators -->
                <div class="step-indicator">
                    <div class="step-dot active" id="stepDot1">1</div>
                    <div class="step-line" id="stepLine"></div>
                    <div class="step-dot inactive" id="stepDot2">2</div>
                </div>

                <div class="form-container">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @if($errors->any())
                        @foreach($errors->all() as $error)
                            <div class="alert alert-danger">{{ $error }}</div>
                        @endforeach
                    @endif

                    <!-- STEP 1: Verify Email -->
                    <div id="step1">
                        <form id="verifyEmailForm">
                            @csrf
                            <div class="form-group">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" id="emailInput" class="form-input"
                                    placeholder="Enter your registered email" required>
                            </div>

                            <button type="submit" class="submit-btn" id="verifyBtn">
                                <i class="fas fa-search"></i> VERIFY EMAIL
                            </button>
                        </form>
                    </div>

                    <!-- STEP 2: Reset Password -->
                    <div id="step2" class="step-hidden">
                        <form action="{{ route('password.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="email" id="hiddenEmail">

                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-input" id="displayEmail" disabled style="opacity: 0.7;">
                            </div>

                            <div class="form-group">
                                <label class="form-label">New Password</label>
                                <div class="password-toggle">
                                    <input type="password" name="password" id="newPassword" class="form-input"
                                        placeholder="Enter new password" required minlength="6">
                                    <i class="fas fa-eye toggle-eye" onclick="togglePassword('newPassword', this)"></i>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Confirm Password</label>
                                <div class="password-toggle">
                                    <input type="password" name="password_confirmation" id="confirmPassword"
                                        class="form-input" placeholder="Confirm new password" required minlength="6">
                                    <i class="fas fa-eye toggle-eye"
                                        onclick="togglePassword('confirmPassword', this)"></i>
                                </div>
                            </div>

                            <button type="submit" class="submit-btn" id="resetBtn">
                                <i class="fas fa-key"></i> RESET PASSWORD
                            </button>
                        </form>
                    </div>

                    <p class="back-link">
                        Remember your password? <a href="{{ route('show-login') }}">Back to Login</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ---- Theme Switcher ----
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
                themeItems.forEach(item => item.classList.toggle('active', item.dataset.theme === theme));
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
                if (localStorage.getItem('theme') === 'system') applyTheme('system');
            });

            applyTheme(localStorage.getItem('theme') || 'system');

            // ---- Step 1: Verify Email ----
            document.getElementById('verifyEmailForm').addEventListener('submit', function (e) {
                e.preventDefault();

                const email = document.getElementById('emailInput').value.trim();
                const verifyBtn = document.getElementById('verifyBtn');
                const originalText = verifyBtn.innerHTML;

                if (!email) return;

                verifyBtn.disabled = true;
                verifyBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> VERIFYING...';

                fetch("{{ route('password.verify-email') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ email: email })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            // Move to step 2
                            document.getElementById('step1').classList.add('step-hidden');
                            document.getElementById('step2').classList.remove('step-hidden');
                            document.getElementById('hiddenEmail').value = email;
                            document.getElementById('displayEmail').value = email;
                            document.getElementById('stepSubtitle').textContent = 'Set your new password';

                            // Update step indicators
                            document.getElementById('stepDot1').classList.remove('active');
                            document.getElementById('stepDot1').classList.add('completed');
                            document.getElementById('stepDot1').innerHTML = '<i class="fas fa-check" style="font-size:12px;"></i>';
                            document.getElementById('stepLine').classList.add('active');
                            document.getElementById('stepDot2').classList.remove('inactive');
                            document.getElementById('stepDot2').classList.add('active');
                        } else {
                            showAlert(data.message || 'Email not found in our system.', 'danger');
                        }
                    })
                    .catch(() => {
                        showAlert('Something went wrong. Please try again.', 'danger');
                    })
                    .finally(() => {
                        verifyBtn.disabled = false;
                        verifyBtn.innerHTML = originalText;
                    });
            });

            // ---- Step 2: Validate before submit ----
            const resetForm = document.querySelector('#step2 form');
            if (resetForm) {
                resetForm.addEventListener('submit', function (e) {
                    const pass = document.getElementById('newPassword').value;
                    const confirm = document.getElementById('confirmPassword').value;

                    if (pass.length < 6) {
                        e.preventDefault();
                        showAlert('Password must be at least 6 characters long.', 'danger');
                        return;
                    }

                    if (pass !== confirm) {
                        e.preventDefault();
                        showAlert('Passwords do not match.', 'danger');
                        return;
                    }
                });
            }
        });

        // Toggle password visibility
        function togglePassword(inputId, icon) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Show alert dynamically
        function showAlert(message, type) {
            // Remove existing alerts
            document.querySelectorAll('.dynamic-alert').forEach(a => a.remove());

            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} dynamic-alert`;
            alertDiv.textContent = message;

            const formContainer = document.querySelector('.form-container');
            formContainer.insertBefore(alertDiv, formContainer.firstChild);

            // Auto-remove after 5 seconds
            setTimeout(() => alertDiv.remove(), 5000);
        }
    </script>
</body>

</html>