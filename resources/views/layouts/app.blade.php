<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body {
            background: white;
            margin: 0;
            padding: 0;
        }

        .chart-container::-webkit-scrollbar {
            width: 12px;
            height: 12px;
        }

        .chart-container::-webkit-scrollbar-thumb {
            background-color: #36A2EB;
            border-radius: 10px;
            border: 2px solid #ffffff;
        }

        .chart-container::-webkit-scrollbar-thumb:hover {
            background-color: #1e88e5;
        }

        .chart-container::-webkit-scrollbar-track {
            background-color: #f0f0f0;
            border-radius: 10px;
        }

        .chart-container {
            scrollbar-width: thin;
            scrollbar-color: #36A2EB #f0f0f0;
        }

        .chart-container {
            overflow-x: auto;
            overflow-y: auto;
            max-height: 400px;
            border: 1px solid #e0e0e0;
            padding: 10px;
            border-radius: 10px;
        }

        body::-webkit-scrollbar {
            width: 10px;
        }

        body::-webkit-scrollbar-thumb {
            background-color: #004d40;
            border-radius: 10px;
            border: 2px solid #ffffff;
        }

        body::-webkit-scrollbar-track {
            background-color: #f0f0f0;
        }

        .btn-success-custom {
            background-color: #004d40;
            color: white;
            border: 2px solid #004d40;
            border-radius: 8px;
            font-weight: bold;
            margin-right: 10px;
            padding: 5px 10px;
            transition: all 0.3s ease;
        }

        .btn-success-custom:hover {
            background-color: #004d40;
            color: #ffffff;
            border-color: #004d40;
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-success-custom:focus {
            outline: none;
            box-shadow: 0 0 5px rgba(40, 167, 69, 0.5);
        }

        .btn-success-custom:active {
            background-color: #1e7e34;
            transform: scale(0.98);
        }

        .navbar-custom {
            background-color: rgba(0, 77, 64, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 0px solid #004d40;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1030;
        }

        .navbar-custom .navbar-brand,
        .navbar-custom .nav-link {
            color: #ffffff !important;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .navbar-custom .nav-link.active {
            transform: translateY(-2px);
            color: #a3d4c7 !important;
            text-decoration: underline;
        }

        .navbar-custom .nav-link:hover {
            transform: translateY(-2px);
            color: #a3d4c7 !important;
        }

        .dropdown-menu {
            background-color: #004d40;
            border: none;
            border-radius: 10px;
        }

        .dropdown-item {
            color: #ffffff;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            transform: translateY(-2px);
            background-color: transparent !important;
            color: #a3d4c7 !important;
        }

        .footer-custom {
            background-color: rgba(0, 77, 64, 0.9);
            backdrop-filter: blur(10px);
            color: #ffffff;
            text-align: center;
            padding: 15px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
            z-index: 1030;
            border-top: 0px solid #004d40;
        }

        .content-wrapper {
            margin-top: 80px;
            margin-bottom: 70px;
            opacity: 0;
            transition: opacity 0.6s ease;
        }

        .content-wrapper.loaded {
            opacity: 1;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold">Dashboard Profesor</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon" style="background-color: #ffffff; border-radius: 5px;"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link @if (request()->routeIs('grupos.index')) active @endif" href="{{ route('grupos.index') }}">Grupos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if (request()->routeIs('alumnos.index')) active @endif" href="{{ route('alumnos.index') }}">Alumnos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if (request()->routeIs('asistencias.index')) active @endif" href="{{ route('asistencias.index') }}">Asistencias</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if (request()->routeIs('qr_codes.index')) active @endif" href="{{ route('qr_codes.index') }}">Códigos QR</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if (request()->routeIs('materias.index')) active @endif" href="{{ route('materias.index') }}">Materias</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-2"></i> Opciones
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="">Cuenta</a></li>
                            <li><a class="dropdown-item" href="">{{Auth::user()->name;}}</a></li>
                            <li><a class="dropdown-item" href="">{{Auth::user()->email;}}</a></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">Cerrar Sesión</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container content-wrapper">
        @yield('content')
    </div>

    <footer class="footer-custom">
        <div class="container">
            <p class="mb-0">© 2025 Dashboard Profesor | Todos los derechos reservados</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelector('.content-wrapper').classList.add('loaded');
        });
    </script>
</body>
</html>
