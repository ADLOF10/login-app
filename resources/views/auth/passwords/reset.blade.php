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
        <form action="<?= route('password.update') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="token" value="<?= $token ?>">
            <div class="form-group mb-3">
                <label for="email" style="color: #004d40;">Correo Electrónico</label>
                <input type="email" name="email" class="form-control" required placeholder="example@profesor.uaemex.wip">
            </div>
            <div class="form-group mb-3">
                <label for="password" style="color: #004d40;">Nueva Contraseña</label>
                <input type="password" name="password" class="form-control" required placeholder="Mínimo 8 caracteres">
            </div>
            <div class="form-group mb-3">
                <label for="password_confirmation" style="color: #004d40;">Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" class="form-control" required placeholder="Confirma tu contraseña">
            </div>
            <button type="submit" class="btn btn-primary w-100">Restablecer Contraseña</button>
        </form>
    </div>
</body>
</html>
