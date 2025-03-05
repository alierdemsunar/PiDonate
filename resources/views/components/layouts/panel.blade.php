<!DOCTYPE html>
<html class="h-100">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Panel') | {{ config('app.name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body>
<!-- Üst Menü -->
<header class="navbar navbar-dark fixed-top bg-dark p-0 shadow" id="pi-donate-header">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 text-center" href="{{ route('panel.dashboard') }}">
        Panel
    </a>
    <button class="navbar-toggler d-md-none" type="button" id="sidebarToggler">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-nav ms-auto">
        <div class="nav-item text-nowrap">
            <a class="nav-link px-3" href="{{ route('panel.logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" title="Çıkış Yap">
                <i class="bi bi-power"></i>
                <form id="logout-form" action="{{ route('panel.logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </a>
        </div>
    </div>
</header>

<!-- Sidebar ve Ana İçerik -->
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="sidebar" id="pi-donate-menu">
            <div class="pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center" href="{{ route('panel.dashboard') }}">
                            <i class="nav-icon fa-duotone fa-clone me-2"></i>
                            <span>Panel</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center" href="">
                            <i class="nav-icon fa-duotone fa-clone me-2"></i>
                            <span>Siparişler</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <main class="col-md-9 col-lg-10 ms-sm-auto px-md-4" id="pi-donate-main">
            {{ $slot ?? '' }}
        </main>
    </div>
</div>

<!-- Footer -->
<footer class="bg-dark text-light text-center py-2" id="pi-donate-footer">
    Copyright © 2017-{{ date('Y') }} {{ config('app.name') }}
</footer>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mobil menü toggle
        document.getElementById('sidebarToggler').addEventListener('click', function() {
            document.getElementById('pi-donate-menu').classList.toggle('show');
        });

        // Aktif menü öğesini işaretle
        const currentLocation = window.location.href;
        document.querySelectorAll('.nav-link').forEach(link => {
            if (link.href === currentLocation) {
                link.classList.add('active');
            }
        });
    });
</script>
@livewireScripts
</body>
</html>
