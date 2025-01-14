<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
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
        .register-container {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            padding: 2rem;
            width: 350px;
        }
    </style>
</head>
<body>
<div class="register-container">
    <h2 class="text-center mb-4">Registro</h2>
    <form action="{{ route('register') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Correo Institucional</label>
            <input type="email" class="form-control" id="email" name="email" 
                   value="{{ old('email') }}" 
                   pattern="^[a-zA-Z0-9._%+-]+@profesor\.uaemex\.wip$" 
                   placeholder="example@profesor.uaemex.wip" required>
            <div class="form-text">Debe tener el dominio @profesor.uaemex.wip</div>
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="real_email" class="form-label">Correo Real</label>
            <input type="email" class="form-control" id="real_email" name="real_email" 
                   value="{{ old('real_email') }}" 
                   placeholder="Correo donde recibirás la validación" required>
            @error('real_email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" 
                pattern="(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}" 
                title="La contraseña debe tener al menos 8 caracteres, incluyendo una mayúscula, una minúscula, un número y un carácter especial $,!,@."
                required>
            <small class="form-text text-muted">La contraseña debe tener al menos 8 caracteres, incluyendo una mayúscula, una minúscula, un número y un carácter especial.</small>
            @error('password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
            @error('password_confirmation')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn w-100 btn-primary" style="background-color: #004d40; border-color: #004d40; color: white;">
            Registrar
        </button>
    </form>
    <div class="mt-3 text-center">
        <a href="{{ route('login') }}">¿Ya tienes una cuenta? Inicia sesión</a>
    </div>
</div>

</body>
</html>
