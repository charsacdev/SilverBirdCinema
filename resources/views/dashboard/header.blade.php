<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Silverbird Cinemas Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" rel="stylesheet">
    <link rel="stylesheet" href="vendor/css/style.css">
    <link rel="stylesheet" href="vendor/css/generate-tickets.css">
    <link rel="stylesheet" href="vendor/css/history.css">
    <link rel="stylesheet" href="vendor/css/view-batch.css">
    <link rel="stylesheet" href="vendor/css/partner.css">
    <link rel="stylesheet" href="vendor/css/settings.css">
    @livewireStyles
</head>
<body>
    <nav class="sidebar" id="sidebar">
        <div class="logo-section">
            <div class="logo-text">
                <div class="logo-main">
                    <img src="vendor/images/logo.png" class="img-fluid">
                </div>
            </div>
        </div>

        <div class="sidebar-nav">
            <a href="dashboard" class="sidebar-item {{ Request::is('dashboard') ? 'active' : '' }}">
                <img src="vendor/images/House.png">
                Home
            </a>
            @if (Auth::check() && Auth::user()->isManager())
                <a href="generate-ticket" class="sidebar-item {{ Request::is('generate-ticket') ? 'active' : '' }}">
                    <img src="vendor/images/PlusCircle.png">
                    Generate Tickets
                </a>
             @endif

             <a href="history" class="sidebar-item {{ Request::is('history') || Request::is('view-batch') ? 'active' : '' }}">
                <img src="vendor/images/Receipt.png">
                History
            </a>
           
            @if (Auth::check() && Auth::user()->isAdmin())
                 <a href="generate-ticket" class="sidebar-item {{ Request::is('generate-ticket') ? 'active' : '' }}">
                    <img src="vendor/images/PlusCircle.png">
                    Generate Tickets
                </a>
            
                <a href="partners" class="sidebar-item {{ Request::is('partners') ? 'active' : '' }}">
                    <img src="vendor/images/partners.png">
                    Partners
                </a>
                <a href="settings" class="sidebar-item {{ Request::is('settings') ? 'active' : '' }}">
                    <img src="vendor/images/GearSix.png">
                    Settings
                </a>
            @endif
        </div>

        <div class="qr-section">
            <div class="qr-title">Click here to<br>validate a ticket</div>
            <div class="qr-code">
                <img src="vendor/images/QRCode.png">
            </div>
            <button class="validate-btn" id="validateBtn">Validate Ticket</button>
        </div>

        <div class="user-section" onclick="toggleDropdown()">
            <div class="user-avatar">👤</div>
            <div class="user-email">
                @if(Auth::user()->isAgent() || Auth::user()->isManager())
                  {{auth()->guard('web')->user()->username}}
                @else
                  {{auth()->guard('web')->user()->email}}
                @endif
            </div>
            <i class="fas fa-chevron-down user-dropdown-icon"></i>

            <div class="user-dropdown" id="userDropdown">
                <a href="{{route('logout')}}">Logout</a>
            </div>
        </div>

    </nav>

    <header class="header"> 
        <button class="mobile-menu-btn" id="mobileMenuBtn">
            <i class="fas fa-bars"></i>
        </button>

        <div class="search-container">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="search-input" placeholder="search for anything...">
        </div>
        <button class="notification-btn" id="notificationBtn">
            <i class="fas fa-bell"></i>
            <span class="notification-badge"></span>
        </button>
    </header>

    <!---YEILD---->
    @yield('content')


    @livewire('header-dashboard')

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="vendor/js/script.js"></script>
    <script src="vendor/js/partner.js"></script>
    <script src="vendor/js/generate-tickets.js"></script>
    <script src="vendor/js/history.js"></script>
    <script src="vendor/js/view-batch.js"></script>
    <script src="vendor/js/settings.js"></script>

    @livewireScripts
</body>
</html>
