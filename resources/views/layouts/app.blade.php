<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700" rel="stylesheet">
    
    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    
    <!-- Bootstrap 4 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background: #f7f9fc;
            font-family: 'Open Sans', sans-serif;
            color: #525f7f;
        }

        /* Sidebar Style */
        .navbar-vertical {
            box-shadow: 0 0 2rem 0 rgba(136,152,170,.15);
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1000;
            padding: 0;
            overflow-y: auto;
            overflow-x: hidden;
            width: 250px;
            background: linear-gradient(87deg, #5e72e4 0, #825ee4 100%);
        }

        .navbar-vertical .navbar-brand {
            display: block;
            padding: 1.25rem 1.5rem;
            color: #fff;
            font-size: 1.125rem;
            font-weight: 600;
            text-decoration: none;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .navbar-vertical .navbar-brand:hover {
            color: #fff;
            text-decoration: none;
        }

        .navbar-vertical .navbar-nav {
            padding: 1rem 0;
            flex-direction: column;
            height: calc(100vh - 80px);
        }

        .navbar-vertical .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.8);
            font-weight: 400;
            margin: 0.25rem 0.5rem;
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .navbar-vertical .navbar-nav .nav-link:hover,
        .navbar-vertical .navbar-nav .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
            text-decoration: none;
        }

        .navbar-vertical .navbar-nav .nav-link i {
            margin-right: 1rem;
            font-size: 1rem;
            width: 1.25rem;
            text-align: center;
        }

        /* Logout Button - Stick to Bottom */
        .navbar-vertical .navbar-nav .logout-section {
            margin-top: auto;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 1rem;
        }

        .navbar-vertical .navbar-nav .nav-link.logout-btn {
            color: rgba(255, 255, 255, 0.7);
            background-color: rgba(248, 54, 92, 0.1);
        }

        .navbar-vertical .navbar-nav .nav-link.logout-btn:hover {
            color: #fff;
            background-color: rgba(248, 54, 92, 0.3);
        }

        /* Main Content dengan sidebar */
        .main-content {
            position: relative;
            margin-left: 250px;
            min-height: 100vh;
            background: #f7f9fc;
            padding: 2rem;
        }

        /* Dashboard Content sesuai screenshot */
        .dashboard-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 2rem;
            background: #fff;
            padding: 1.5rem 2rem;
            border-radius: 1rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        .dashboard-title {
            display: flex;
            align-items: center;
            color: #525f7f;
            font-weight: 400;
            font-size: 1.75rem;
            margin: 0;
        }

        .dashboard-icon {
            background: #4285f4;
            color: #fff;
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1rem;
        }

        .user-info-header {
            text-align: right;
            margin-left: auto;
        }

        .user-name {
            font-weight: 600;
            color: #32325d;
            font-size: 1rem;
        }

        .user-role {
            color: #8898aa;
            font-size: 0.875rem;
        }

        /* Card Style sesuai screenshot */
        .card-dashboard {
            background: #fff;
            border-radius: 1rem;
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .card-dashboard .card-body {
            padding: 2rem;
        }

        /* Section Header dengan icon sesuai screenshot */
        .section-header {
            display: flex;
            align-items: center;
            margin-bottom: 2rem;
            font-size: 1.125rem;
            font-weight: 600;
        }

        .section-header.status {
            color: #4285f4;
        }

        .section-header.info {
            color: #17a2b8;
        }

        .section-icon {
            width: 1.5rem;
            height: 1.5rem;
            border-radius: 50%;
            background: currentColor;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
            font-size: 0.75rem;
        }

        /* Action Buttons sesuai screenshot */
        .action-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .btn-action {
            border: none;
            border-radius: 0.75rem;
            padding: 1.5rem;
            font-weight: 600;
            font-size: 1rem;
            color: #fff;
            text-decoration: none;
            text-align: center;
            transition: all 0.15s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            color: #fff;
            text-decoration: none;
        }

        .btn-action i {
            margin-right: 0.75rem;
            font-size: 1.125rem;
        }

        /* Button Colors sesuai screenshot */
        .btn-absensi {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        }

        .btn-izin {
            background: linear-gradient(135deg, #06b6d4 0%, #67e8f9 100%);
        }

        /* Info Grid sesuai screenshot */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            margin-top: 1.5rem;
        }

        .info-item {
            text-align: center;
            padding: 1rem 0;
        }

        .info-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 50%;
            margin: 0 auto 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .info-icon.shift {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: #fff;
        }

        .info-icon.location {
            background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
            color: #fff;
        }

        .info-icon.time {
            background: linear-gradient(135deg, #06b6d4 0%, #67e8f9 100%);
            color: #fff;
        }

        .info-label {
            color: #8898aa;
            font-weight: 500;
            font-size: 0.875rem;
            margin-bottom: 0.75rem;
        }

        .info-value {
            font-size: 0.875rem;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 1rem;
            display: inline-block;
        }

        .badge-shift {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: #fff;
        }

        .badge-location {
            background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
            color: #fff;
        }

        .badge-time {
            background: linear-gradient(135deg, #06b6d4 0%, #67e8f9 100%);
            color: #fff;
        }

        /* Alert Status sesuai screenshot */
        .alert-status {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 0;
            border-left: 4px solid #4285f4;
        }

        .alert-status h6 {
            color: #4285f4;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .alert-status .status-content {
            color: #64748b;
            font-size: 0.9rem;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .navbar-vertical { 
                margin-left: -250px; 
                transition: margin-left 0.3s ease;
            }
            .navbar-vertical.show { 
                margin-left: 0; 
            }
            .main-content { 
                margin-left: 0; 
            }
            .action-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            .info-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
        }
    </style>
</head>
<body>
    @auth
    <!-- Sidebar -->
    <nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light">
        <div class="container-fluid d-flex flex-column h-100">
            <!-- Brand -->
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="fas fa-clock mr-2"></i>Sistem Absensi
            </a>
            
            <!-- Navigation -->
            <div class="navbar-inner flex-grow-1 d-flex flex-column">
                <div class="collapse navbar-collapse h-100 d-flex flex-column">
                    <ul class="navbar-nav flex-grow-1">
                        <!-- Dashboard -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                               href="{{ route('dashboard') }}">
                                <i class="fas fa-home"></i>Dashboard
                            </a>
                        </li>
                        
                        @if(auth()->user()->isMagang())
                        <!-- Menu untuk Magang -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('absensi.form') ? 'active' : '' }}" 
                               href="{{ route('absensi.form') }}">
                                <i class="fas fa-clock"></i>Absensi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('izin.*') ? 'active' : '' }}" 
                               href="{{ route('izin.index') }}">
                                <i class="fas fa-calendar-alt"></i>Izin Saya
                            </a>
                        </li>
                        @endif
                        
                        @if(auth()->user()->isMentor())
                        <!-- Menu untuk Mentor -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('absensi.riwayat') ? 'active' : '' }}" 
                               href="{{ route('absensi.riwayat') }}">
                                <i class="fas fa-history"></i>Riwayat Absen
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('izin.daftar') ? 'active' : '' }}" 
                               href="{{ route('izin.daftar') }}">
                                <i class="fas fa-clipboard-list"></i>Kelola Izin
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" 
                               href="{{ route('users.index') }}">
                                <i class="fas fa-users"></i>Kelola User
                            </a>
                        </li>
                        @endif
                        
                        <!-- LOGOUT BUTTON - Stick to Bottom -->
                        <li class="nav-item logout-section mt-auto">
                            <a class="nav-link logout-btn" href="#" id="logout-link">
                                <i class="fas fa-sign-out-alt"></i>Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hidden Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
    @endauth

    <!-- Main content dengan sidebar -->
    <div class="main-content">
        @auth
        <!-- Dashboard Header sesuai screenshot -->
        <div class="dashboard-header">
            <h1 class="dashboard-title">
                <div class="dashboard-icon">
                    <i class="fas fa-tachometer-alt"></i>
                </div>
                Dashboard
            </h1>
            <div class="user-info-header">
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-role">{{ auth()->user()->isMentor() ? 'Mentor' : 'Magang' }}</div>
            </div>
        </div>
        @endauth
        
        <!-- Page content -->
        <div class="container-fluid p-0">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle mr-2"></i>
                    <strong>Berhasil!</strong> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Error!</strong> {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif
            
            @yield('content')
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Auto hide alerts
        $('.alert').delay(5000).fadeOut();
        
        // Logout functionality
        document.getElementById('logout-link').addEventListener('click', function(e) {
            e.preventDefault();
            
            if (confirm('Yakin ingin logout?')) {
                document.getElementById('logout-form').submit();
            }
        });
        
        // Mobile sidebar toggle
        function toggleSidebar() {
            document.querySelector('.navbar-vertical').classList.toggle('show');
        }
    </script>
    
    @yield('scripts')
</body>
</html>