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
        /* Top Header Layout Fix */
.top-header {
    background: #fff;
    padding: 1.5rem 2rem;
    box-shadow: 0 1px 3px rgba(50, 50, 93, 0.15);
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
}

.header-left {
    flex: 1;
}

.header-right {
    flex: 0 0 auto;
}

/* Dashboard Title - Rata Kiri */
.dashboard-title {
    display: flex;
    align-items: center;
    color: #525f7f;
    font-weight: 400;
    font-size: 1.75rem;
    margin: 0;
    text-align: left;
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
    flex-shrink: 0;
}

/* Profile Menu - Fixed Position */
.profile-menu {
    position: relative;
    z-index: 1000;
}

.profile-dropdown-btn {
    background: #f8f9fe;
    border: 1px solid #e9ecef;
    border-radius: 50px;
    display: flex;
    align-items: center;
    padding: 0.5rem 1rem;
    transition: all 0.15s ease;
    text-decoration: none;
    color: #525f7f;
    cursor: pointer;
    min-width: 200px;
}

.profile-dropdown-btn:hover {
    background: #e9ecef;
    color: #525f7f;
    text-decoration: none;
    border-color: #d1ecf1;
}

.profile-photo-wrapper {
    position: relative;
    margin-right: 0.75rem;
    flex-shrink: 0;
}

.profile-photo-small {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #fff;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.profile-photo-fallback {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background: #5e72e4;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.75rem;
    border: 2px solid #fff;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

/* User Info di Profile Menu */
.profile-info {
    text-align: left;
    flex: 1;
    min-width: 0;
}

.profile-name {
    font-weight: 600;
    color: #32325d;
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
    line-height: 1.2;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.profile-role {
    color: #8898aa;
    font-size: 0.75rem;
    margin-bottom: 0;
    line-height: 1;
}

/* Dropdown Menu Style */
.profile-dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    background: #fff;
    border-radius: 0.75rem;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    min-width: 220px;
    z-index: 1050;
    display: none;
    border: 1px solid #e9ecef;
    margin-top: 0.75rem;
    overflow: hidden;
}

.profile-dropdown.show {
    display: block;
    animation: fadeInDown 0.3s ease;
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.profile-dropdown::before {
    content: '';
    position: absolute;
    top: -9px;
    right: 25px;
    width: 0;
    height: 0;
    border-left: 9px solid transparent;
    border-right: 9px solid transparent;
    border-bottom: 9px solid #fff;
    filter: drop-shadow(0 -2px 3px rgba(0,0,0,0.1));
}

.profile-dropdown-item {
    display: flex;
    align-items: center;
    padding: 0.875rem 1.25rem;
    color: #525f7f;
    text-decoration: none;
    font-size: 0.875rem;
    border-bottom: 1px solid #f8f9fe;
    transition: all 0.15s ease;
}

.profile-dropdown-item:hover {
    background: #f8f9fe;
    color: #5e72e4;
    text-decoration: none;
}

.profile-dropdown-item:last-child {
    border-bottom: none;
}

.profile-dropdown-item.logout {
    color: #f5365c;
    border-top: 1px solid #f8f9fe;
}

.profile-dropdown-item.logout:hover {
    background: #fdf2f2;
    color: #ec0c38;
}

.profile-dropdown-item i {
    margin-right: 0.75rem;
    width: 1rem;
    text-align: center;
}

/* Dropdown Divider */
.dropdown-divider {
    height: 0;
    margin: 0;
    overflow: hidden;
    border-top: 1px solid #e9ecef;
}

/* Content Area Spacing */
.content-area {
    padding: 2rem;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .top-header {
        padding: 1rem;
        flex-direction: row;
        justify-content: space-between;
    }
    
    .dashboard-title {
        font-size: 1.5rem;
    }
    
    .dashboard-icon {
        width: 2rem;
        height: 2rem;
        font-size: 0.875rem;
    }
    
    .profile-info {
        display: none;
    }
    
    .profile-dropdown-btn {
        min-width: auto;
        padding: 0.5rem;
        border-radius: 50%;
    }
    
    .profile-dropdown {
        min-width: 180px;
        right: -20px;
    }
}

/* Fix untuk layout yang centered - buat rata kiri */
.content-area .row {
    margin-left: 0;
    margin-right: 0;
}

.content-area .col-12,
.content-area .col-lg-8,
.content-area .col-md-6 {
    padding-left: 0;
    padding-right: 15px;
}

/* Dashboard Content Layout */
.dashboard-content {
    max-width: 1200px;
    width: 100%;
}

/* Card positioning */
.card-dashboard {
    width: 100%;
    margin-left: 0;
    margin-right: 0;
}

/* Section headers alignment */
.section-header {
    text-align: left;
    justify-content: flex-start;
}

/* Action grid alignment */
.action-grid {
    width: 100%;
    margin-left: 0;
    margin-right: 0;
}

/* Info grid alignment */
.info-grid {
    width: 100%;
}
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
        }

        /* Top Header dengan Profil Menu */
        .top-header {
            background: #fff;
            padding: 1rem 2rem;
            box-shadow: 0 1px 3px rgba(50, 50, 93, 0.15);
            display: flex;
            justify-content: space-between;
            align-items: center;
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

        /* Profile Menu di Pojok Kanan Atas */
       /* Profile Photo Wrapper */
.profile-photo-wrapper {
    position: relative;
    margin-right: 0.75rem;
}

.profile-photo-small {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e9ecef;
    display: block;
}

.profile-photo-fallback {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #5e72e4;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.875rem;
    border: 2px solid #e9ecef;
}

/* Dropdown Divider */
.dropdown-divider {
    height: 0;
    margin: 0.5rem 0;
    overflow: hidden;
    border-top: 1px solid #e9ecef;
}

/* Profile Menu di pojok kanan atas */
.profile-menu {
    position: relative;
    z-index: 1000;
}

.profile-dropdown-btn {
    background: none;
    border: none;
    display: flex;
    align-items: center;
    padding: 0.5rem;
    border-radius: 50px;
    transition: all 0.15s ease;
    text-decoration: none;
    color: #525f7f;
    cursor: pointer;
}

.profile-dropdown-btn:hover {
    background: #f8f9fe;
    color: #525f7f;
    text-decoration: none;
}

.profile-info {
    text-align: right;
    margin-right: 0.75rem;
}

.profile-name {
    font-weight: 600;
    color: #32325d;
    font-size: 0.875rem;
    margin-bottom: 0;
    line-height: 1.2;
}

.profile-role {
    color: #8898aa;
    font-size: 0.75rem;
    margin-bottom: 0;
}

/* Dropdown Menu Style */
.profile-dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    background: #fff;
    border-radius: 0.5rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    min-width: 200px;
    z-index: 1050;
    display: none;
    border: 1px solid #e9ecef;
    margin-top: 0.5rem;
}

.profile-dropdown.show {
    display: block;
    animation: fadeInDown 0.2s ease;
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.profile-dropdown::before {
    content: '';
    position: absolute;
    top: -8px;
    right: 20px;
    width: 0;
    height: 0;
    border-left: 8px solid transparent;
    border-right: 8px solid transparent;
    border-bottom: 8px solid #fff;
}

.profile-dropdown-item {
    display: block;
    padding: 0.75rem 1rem;
    color: #525f7f;
    text-decoration: none;
    font-size: 0.875rem;
    border-bottom: 1px solid #f8f9fe;
    transition: all 0.15s ease;
}

.profile-dropdown-item:hover {
    background: #f8f9fe;
    color: #5e72e4;
    text-decoration: none;
}

.profile-dropdown-item:last-child {
    border-bottom: none;
    border-radius: 0 0 0.5rem 0.5rem;
}

.profile-dropdown-item.logout {
    color: #f5365c;
}

.profile-dropdown-item.logout:hover {
    background: #fdf2f2;
    color: #ec0c38;
}

        /* Content Area */
        .content-area {
            padding: 2rem;
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

        /* Section Header */
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

        /* Action Buttons */
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

        .btn-absensi {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        }

        .btn-izin {
            background: linear-gradient(135deg, #06b6d4 0%, #67e8f9 100%);
        }

        /* Info Grid */
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

        /* Alert Status */
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
            .top-header {
                padding: 1rem;
            }
            .profile-info {
                display: none;
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
<!-- Top Header dengan Profile Menu di Pojok Kanan -->
<div class="top-header">
    <!-- Dashboard Title - Rata Kiri -->
    <div class="header-left">
        <h1 class="dashboard-title">
            <div class="dashboard-icon">
                <i class="fas fa-tachometer-alt"></i>
            </div>
            Dashboard
        </h1>
    </div>
    
    <!-- Profile Menu - Pojok Kanan -->
    <div class="header-right">
        <div class="profile-menu">
            <a href="#" class="profile-dropdown-btn" onclick="toggleProfileDropdown(event)">
                <!-- Foto Profil -->
                <div class="profile-photo-wrapper">
                    @if(auth()->user()->hasProfilePhoto())
                        <img src="{{ auth()->user()->profile_photo_url }}" 
                             alt="Foto Profil {{ auth()->user()->name }}"
                             class="profile-photo-small"
                             onload="this.style.display='block'; this.nextElementSibling.style.display='none';"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="profile-photo-fallback" style="display: none;">
                            {{ auth()->user()->initials }}
                        </div>
                    @else
                        <div class="profile-photo-fallback">
                            {{ auth()->user()->initials }}
                        </div>
                    @endif
                </div>
                
                <!-- User Info -->
                <div class="profile-info">
                    <div class="profile-name">{{ auth()->user()->name }}</div>
                    <div class="profile-role">{{ auth()->user()->isMentor() ? 'Mentor' : 'Magang' }}</div>
                </div>
                
                <!-- Dropdown Arrow -->
                <i class="fas fa-chevron-down ml-2" style="font-size: 0.75rem; color: #8898aa;"></i>
            </a>
            
            <!-- Dropdown Menu -->
            <div class="profile-dropdown" id="profile-dropdown">
                @if(auth()->user()->isMagang())
                <a href="{{ route('profil.index') }}" class="profile-dropdown-item">
                    <i class="fas fa-user-circle mr-2"></i>Profil Saya
                </a>
                {{-- <a href="{{ route('profil.index') }}" class="profile-dropdown-item">
                    <i class="fas fa-camera mr-2"></i>Ubah Foto
                </a> --}}
                <div class="dropdown-divider"></div>
                @endif
                <a href="#" class="profile-dropdown-item logout" onclick="event.preventDefault(); confirmLogout();">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </a>
            </div>
        </div>
    </div>
</div>
        @endauth
        
        <!-- Page content -->
        <div class="content-area">
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
    
    <script defer>
        // Auto hide alerts
        $('.alert').delay(5000).fadeOut();
        
        // Logout functionality
        document.getElementById('logout-link').addEventListener('click', function(e) {
            e.preventDefault();
            confirmLogout();
        });
        
        // Profile dropdown toggle
        function toggleProfileDropdown(event) {
            event.preventDefault();
            event.stopPropagation();
            
            const dropdown = document.getElementById('profile-dropdown');
            dropdown.classList.toggle('show');
        }
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('profile-dropdown');
            const profileMenu = document.querySelector('.profile-menu');
            
            if (!profileMenu.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });
        
        // Logout confirmation
        function confirmLogout() {
            if (confirm('Yakin ingin logout?')) {
                document.getElementById('logout-form').submit();
            }
        }
    </script>
    <script defer>
    // Auto hide alerts
    $('.alert').delay(5000).fadeOut();
    
    // Logout functionality
    document.getElementById('logout-link').addEventListener('click', function(e) {
        e.preventDefault();
        confirmLogout();
    });
    
    // Profile dropdown toggle
    function toggleProfileDropdown(event) {
        event.preventDefault();
        event.stopPropagation();
        
        const dropdown = document.getElementById('profile-dropdown');
        dropdown.classList.toggle('show');
    }
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('profile-dropdown');
        const profileMenu = document.querySelector('.profile-menu');
        
        if (!profileMenu.contains(event.target)) {
            dropdown.classList.remove('show');
        }
    });
    
    // Logout confirmation
    function confirmLogout() {
        if (confirm('Yakin ingin logout?')) {
            document.getElementById('logout-form').submit();
        }
    }
    
    // Fix image loading errors
    function fixImageError(img) {
        console.log('Image failed to load:', img.src);
        img.style.display = 'none';
        
        // Show fallback
        const fallback = img.nextElementSibling;
        if (fallback && fallback.classList.contains('profile-photo-fallback')) {
            fallback.style.display = 'flex';
        }
    }
</script>
    
    @yield('scripts')
</body>
</html>