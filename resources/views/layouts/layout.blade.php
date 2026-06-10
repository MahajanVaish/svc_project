<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Shree Vallabh Clinic</title>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background: white;
        }
        .navbar {
            background: #f0f2f3;
            padding: 25px 30px !important;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar-brand {
            color: rgb(8, 104, 56) !important;
            font-size: 24px;
            font-weight: bold;
            padding-left: 70px;
        }
        .nav-links {
            display: flex;
            gap: 25px;
            align-items: center;
        }
        .nav-links a {
            color: #2c3e50;
            text-decoration: none;
            font-size: 16px;
            transition: color 0.3s ease;
            padding: 8px 12px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .nav-links a:hover {
            color: rgb(8, 104, 56);
        }
        .nav-links a.active {
            color: rgb(8, 104, 56);
            font-weight: bold;
        }

        /* Icon styling */
        .nav-links a i {
            font-size: 16px;
            width: 16px;
            text-align: center;
        }

        /* Dropdown Styles */
        .dropdown {
            position: relative;
        }
        .dropdown > a {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .dropdown > a::after {
            content: "▼";
            font-size: 10px;
            margin-left: 4px;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            background: white;
            min-width: 180px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1000;
            border-radius: 4px;
            border: 1px solid #d1d7dd;
            top: 100%;
            left: 0;
        }
        .dropdown-content a {
            color: #2c3e50;
            padding: 12px 16px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            border-bottom: 1px solid #f1f1f1;
        }
        .dropdown-content a:last-child {
            border-bottom: none;
        }
        .dropdown-content a:hover {
            background: #f8f9fa;
            color: rgb(8, 104, 56);
        }
        .dropdown:hover .dropdown-content {
            display: block;
        }
        .dropdown:hover > a {
            color: rgb(8, 104, 56);
            background: rgba(8, 104, 56, 0.1);
        }

        .main-content {
            padding: 30px;
        }
        .section-title {
            color: #2c3e50;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="navbar-brand">Shree Vallabh Clinic</div>
        <div class="nav-links">
            <a href="/dashboard" class="{{ request()->is('dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                Dashboard
            </a>

            <div class="dropdown">
                <a href="#" class="{{ request()->is('svc-patient*') || request()->is('add-svc-inquiry*') ? 'active' : '' }}">
                    <i class="fas fa-code-branch"></i>
                    Other Branches
                </a>
                <div class="dropdown-content">
                    <a href="/svc-patient">
                        <i class="fas fa-user-injured"></i>
                        SVC Patient
                    </a>
                </div>
            </div>

            <a href="/add-invoice" class="{{ request()->is('invoice') ? 'active' : '' }}">
                <i class="fas fa-file-invoice"></i>
                Invoice
            </a>

            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
               <i class="fas fa-sign-out-alt"></i>
               Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        @yield('content')
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
