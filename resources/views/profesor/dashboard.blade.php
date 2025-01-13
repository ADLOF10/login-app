<!--<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        
        .navbar-custom {
            background: linear-gradient(90deg, #004d40, #00695c);
            border-bottom: 2px solid #004d40;
        }

        .navbar-custom .navbar-brand,
        .navbar-custom .nav-link {
            color: #ffffff !important;
            font-weight: bold;
        }

        .navbar-custom .nav-link:hover {
            background-color: #ffffff;
            color: #004d40 !important;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .navbar-custom .btn-link.text-danger {
            color: #ff5252 !important;
            font-weight: bold;
        }

       
        .footer-custom {
            background: linear-gradient(90deg, #004d40, #00695c);
            color: #ffffff;
            text-align: center;
            padding: 15px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
            z-index: 1030;
            border-top: 2px solid #004d40;
        }

      
        .content-wrapper {
            margin-top: 80px; 
            margin-bottom: 70px; 
        }
    </style>
</head>
<body>
    
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold">Dashboard Profesor</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon" style="background-color: #ffffff; border-radius: 5px;"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('grupos.index') }}">Grupos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('alumnos.index') }}">Alumnos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('asistencias.index') }}">Asistencias</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('qr_codes.index') }}">Códigos QR</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('materias.index') }}">Materias</a>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link text-danger fw-bold" style="border: none; padding: 0;">Cerrar Sesión</button>
                        </form>
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
</body>
</html>
