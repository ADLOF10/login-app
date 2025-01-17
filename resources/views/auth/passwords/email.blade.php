<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
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
        .card {
            background-color: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            width: 350px;
            padding: 2rem;
        }
        .btn-primary {
            background-color: #004d40;
            border: none;
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
    <div class="card">
        <h2 class="mb-4 text-center" style="color: #004d40;">Restablecer Contraseña</h2>
        <!-- Mensaje de éxito -->
        <?php if (session('status')): ?>
            <div class="alert alert-success">
                <?= session('status') ?>
            </div>
        <?php endif; ?>
        <!-- Formulario -->
        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            <div class="form-group mb-3">
                <label for="email">Correo Electrónico</label>
                <input type="email" name="email" class="form-control" required placeholder="example@correo.com">
            </div>
            <button type="submit" class="btn btn-primary">Enviar enlace de restablecimiento</button>
        </form>        
    </div>
</body>
</html>
