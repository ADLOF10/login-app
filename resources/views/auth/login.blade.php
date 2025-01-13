<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #004d40, #ffffff);
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .top-bar, .bottom-bar {
            position: fixed;
            width: 100%;
            height: 50px;
            background-color: rgba(0, 77, 64, 0.7);
            backdrop-filter: blur(10px);
            z-index: 1000;
        }

        .top-bar {
            top: 0;
        }

        .bottom-bar {
            bottom: 0;
        }

        .login-container {
            background-color: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            width: 350px;
            padding: 2rem;
            text-align: center;
        }

        .login-container img {
            max-width: 150px;
            max-height: 150px;
            margin-bottom: 1rem;
            object-fit: contain;
        }

        .login-container h2 {
            margin-bottom: 1rem;
            color: #004d40;
            font-weight: bold;
        }

        .form-control {
            background-color: #f8f9fa;
            border: 1px solid #ccc;
            border-radius: 10px;
            padding-right: 2.5rem;
            height: 45px;
        }

        .form-control:focus {
            border-color: #004d40;
            box-shadow: 0 0 5px rgba(0, 77, 64, 0.5);
        }

        .form-group {
            position: relative;
            margin-bottom: 1rem;
        }

        .form-group .toggle-password {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #004d40;
            display: none;
        }

        .form-check-label {
            color: #004d40;
            font-size: 0.9rem;
        }

        .login-container .forgot-password {
            float: right;
            font-size: 0.9rem;
            text-decoration: none;
            color: #004d40;
        }

        .login-container .forgot-password:hover {
            text-decoration: underline;
        }

        .btn-primary {
            background-color: #004d40;
            border: none;
            width: 100%;
            height: 45px;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: bold;
        }

        .btn-primary:hover {
            background-color: #00332e;
        }
    </style>
</head>
<body>
    <div class="top-bar"></div>
    <div class="login-container">
        <img src="{{ asset('images/imagen.png') }}" alt="Logo">
        <h2>Inicio de Sesión</h2>
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="example@profesor.uaemex.wip" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" id="password" placeholder="Contraseña" required oninput="togglePasswordVisibility()">
                <i class="bi bi-eye-fill toggle-password" onclick="togglePassword()"></i>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="rememberMe">
                    <label class="form-check-label" for="rememberMe">Recuérdame</label>
                </div>
                
            </div>
            <div class="text-center mt-3">
                <a href="{{ route('register') }}" class="btn btn-link">¿No tienes cuenta? Regístrate</a>
            </div>
            <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
        </form>
    </div>
    <div class="bottom-bar"></div>
    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const icon = document.querySelector('.toggle-password');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('bi-eye-fill');
                icon.classList.add('bi-eye-slash-fill');
            } else {
                passwordField.type = 'password';
                icon.classList.remove('bi-eye-slash-fill');
                icon.classList.add('bi-eye-fill');
            }
        }

        function togglePasswordVisibility() {
            const passwordField = document.getElementById('password');
            const icon = document.querySelector('.toggle-password');
            if (passwordField.value.length > 0) {
                icon.style.display = 'block';
            } else {
                icon.style.display = 'none';
            }
        }
    </script>
</body>
</html>
